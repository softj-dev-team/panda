import pymysql
import requests
import json
import time
from dotenv import load_dotenv
import os
import logging
# .env 파일에서 환경 변수 로드
load_dotenv()
logging.basicConfig(filename='app_log.log', level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
# 데이터베이스 연결 설정
db_config = {
    'host': os.getenv('DB_HOST'),
    'user': os.getenv('DB_USER'),
    'password': os.getenv('DB_PASSWORD'),
    'database': os.getenv('DB_NAME'),
    'charset': os.getenv('DB_CHARSET')
}

# API 설정
api_base_url = os.getenv('API_BASE_URL')
api_template_url = os.getenv('API_TEMPLATE_URL')
api_endpoint = '/v3/A/leahue1/messages'
api_url = f"{api_base_url}{api_endpoint}"  # 베이스 URL과 엔드포인트 결합
api_ft_url = f"{api_base_url}/v3/C/leahue1/messages"
headers = {
    'Content-Type': 'application/json',
    'Authorization': os.getenv('API_AUTHORIZATION')
}

# 포인트 섹션 설정
point_sect = os.getenv('POINT_SECT')

# CUST_MSG_SN 설정
cust_msg_sn = os.getenv('CUST_MSG_SN')

def get_current_point(cursor, member_idx):
    cursor.execute("""
        SELECT cur_mile FROM member_point
        WHERE member_idx = %s AND point_sect = %s
        ORDER BY idx DESC LIMIT 1
    """, (member_idx, point_sect))
    result = cursor.fetchone()
    return float(result['cur_mile']) if result and result['cur_mile'] is not None else 0.0

def get_mb_kko_fee(cursor, member_info_idx):
    cursor.execute("""
        SELECT mb_kko_fee FROM member_info_sendinfo
        WHERE member_idx = %s
    """, (member_info_idx,))
    result = cursor.fetchone()
    return float(result['mb_kko_fee']) if result and result['mb_kko_fee'] is not None else 0.0

def update_point(cursor, member_idx, chg_mile, mile_title):
    mile_pre = get_current_point(cursor, member_idx)
    chg_mile = float(chg_mile)  # 문자열로 전달될 수 있는 경우 대비하여 형 변환
    cur_mile = max(float(mile_pre) - chg_mile, 0.0)

    sql = """
    INSERT INTO member_point
    (order_num, member_idx, pay_price, mile_title, mile_sect, mile_pre, chg_mile, cur_mile, point_sect, wdate)
    VALUES
    (NULL, %s, NULL, %s, 'M', %s, %s, %s, %s, NOW())
    """
    cursor.execute(sql, (member_idx, mile_title, mile_pre, chg_mile, cur_mile, point_sect))

def process_data():
    connection = pymysql.connect(**db_config)
    try:
        with connection.cursor(pymysql.cursors.DictCursor) as cursor:
            # 유휴 상태 확인을 위한 SQL 쿼리 (예: 특정 상태를 확인)
            cursor.execute("SELECT COUNT(*) AS cnt FROM TBL_SEND_TRAN_KKO WHERE fetc2 = 'AR'")
            result = cursor.fetchone()

            if result and result['cnt'] == 0:
                print("유휴 상태: 처리할 데이터가 없습니다.")
                return

            # fetc2 컬럼에 'AR' 값이 있는 행을 여러 개 선택 (예: 100개씩 배치 처리)
            batch_size = 1000
            sql = "SELECT * FROM TBL_SEND_TRAN_KKO WHERE fetc2 = 'AR' LIMIT %s"
            cursor.execute(sql, (batch_size,))
            results = cursor.fetchall()

            if results:
                total_sent = 0  # 발송 성공 건수 누적

                for result in results:
                    member_info_idx = result['fetc8']
                    sms_send_yn = result['sms_send_yn']
                    sms_kind = result['sms_kind']
                    mb_kko_fee = get_mb_kko_fee(cursor, member_info_idx)

                    if mb_kko_fee is None:
                        logging.warning(f"Member info not found or mb_kko_fee is missing for member_idx: {member_info_idx}")
                        continue

                    # API 전송을 위한 데이터 구성
                    payload ={
                        "custMsgSn": cust_msg_sn,
                        "senderKey": result['fyellowid'],
                        "phoneNum": result['fdestine'],
                        "templateCode": result['ftemplatekey'],
                        "message": result['fmessage']
                    }
                    # 조건에 따라 배열 항목 추가
                    if sms_send_yn == 1:  # 특정 조건을 만족할 경우
                        payload['smsSndNum'] = result['fcallback']
                        payload['smsKind'] = result['sms_kind']
                        payload['kisaOrigCode'] = result['kisa_orig_code']
                        if sms_kind == 'S':
                            payload['smsMessage'] = result['smsmessage']
                        if sms_kind == 'L':
                            payload['lmsMessage'] = result['smsmessage']
                    templateLoad ={
                        "senderKey": result['fyellowid'],
                        "templateCode": result['ftemplatekey'],
                    }

                    data = [{}]
                    try:
                        if result['fuserid'] in 'AT':
                            template = requests.get(f"{api_template_url}{'/api/v1/leahue/template'}", headers=headers, params=templateLoad)
                            template.raise_for_status()
                            response_template = template.json()

                            # msgType 처리
                            if response_template['data'].get('templateEmphasizeType') == "IMAGE":
                                data[0]['msgType'] = 'AI'

                            # 제목과 헤더 추가
                            if 'templateTitle' in response_template['data']:
                                data[0]['title'] = response_template['data']['templateTitle']

                            if 'templateHeader' in response_template['data']:
                                data[0]['header'] = response_template['data']['templateHeader']

                            # ITEM_LIST 처리
                            if response_template['data'].get('templateEmphasizeType') == 'ITEM_LIST':
                                if 'templateItemHighlight' in response_template['data']:
                                    if 'title' in response_template['data']['templateItemHighlight']:
                                        data[0]['itemHighlight'] = {'title': response_template['data']['templateItemHighlight']['title']}
                                    if 'description' in response_template['data']['templateItemHighlight']:
                                        data[0]['itemHighlight']['description'] = response_template['data']['templateItemHighlight']['description']

                                # 리스트 처리
                                if 'list' in response_template['data']['templateItem']:
                                    item_list = []
                                    for template_item_list in response_template['data']['templateItem']['list']:
                                        item = {}
                                        if 'title' in template_item_list:
                                            item['title'] = template_item_list['title']
                                        if 'description' in template_item_list:
                                            item['description'] = template_item_list['description']
                                        item_list.append(item)
                                    data[0]['item'] = {'list': item_list}

                                # 요약 처리
                                if 'summary' in response_template['data']['templateItem']:
                                    summary_list = []
                                    for template_item_summary in response_template['data']['templateItem']['summary']:
                                        summary = {}
                                        if 'title' in template_item_summary:
                                            summary['title'] = template_item_summary['title']
                                        if 'description' in template_item_summary:
                                            summary['description'] = template_item_summary['description']
                                        summary_list.append(summary)
                                    data[0]['item']['summary'] = summary_list

                            # 버튼 데이터 추가
                            if 'buttons' in response_template['data']:
                                button_list = []
                                for button in response_template['data']['buttons']:
                                    button_data = {}
                                    if 'name' in button:
                                        button_data['name'] = button['name']
                                    if 'linkType' in button:
                                        button_data['type'] = button['linkType']
                                    if 'linkMo' in button:
                                        button_data['url_mobile'] = button['linkMo']
                                    if 'linkPc' in button:
                                        button_data['url_pc'] = button['linkPc']
                                    if 'linkIos' in button:
                                        button_data['scheme_ios'] = button['linkIos']
                                    if 'linkAnd' in button:
                                        button_data['scheme_android'] = button['linkAnd']
                                    button_list.append(button_data)
                                data[0]['button'] = button_list

                            # Quick Replies 데이터 추가
                            if 'quickReplies' in response_template['data']:
                                quick_reply_list = []
                                for quick_reply in response_template['data']['quickReplies']:
                                    quick_reply_data = {}
                                    if 'name' in quick_reply:
                                        quick_reply_data['name'] = quick_reply['name']
                                    if 'linkType' in quick_reply:
                                        quick_reply_data['type'] = quick_reply['linkType']
                                    if 'linkMo' in quick_reply:
                                        quick_reply_data['url_mobile'] = quick_reply['linkMo']
                                    if 'linkPc' in quick_reply:
                                        quick_reply_data['url_pc'] = quick_reply['linkPc']
                                    if 'linkIos' in quick_reply:
                                        quick_reply_data['scheme_ios'] = quick_reply['linkIos']
                                    if 'linkAnd' in quick_reply:
                                        quick_reply_data['scheme_android'] = quick_reply['linkAnd']
                                    quick_reply_list.append(quick_reply_data)
                                data[0]['quickReply'] = quick_reply_list

                            # 데이터 처리 후 병합
                            if isinstance(data[0], dict):
                                payload.update(data[0])

                            response = requests.post(api_url, headers=headers, data=json.dumps([payload]))
                            response.raise_for_status()
                            response_data = response.json()
                        if result['fuserid'] in 'FT':
                            if result['msg_type']:
                                data[0]['msgType'] = result['msg_type']
                            if 'image' not in data[0]:
                                data[0]['image'] = {}  # 'image' 키를 초기화
                            if result['img_path']:
                                data[0]['image']['img_url'] = result['img_path']
                            # 버튼 데이터 추가
                            if 'buttons' in result:
                                buttons_obj = json.loads(result['buttons'])
                                button_list = []
                                for button in buttons_obj:
                                    button_data = {}
                                    if 'name' in button:
                                        button_data['name'] = button['name']
                                    if 'linkType' in button:
                                        button_data['type'] = button['linkType']
                                    if 'linkMo' in button:
                                        button_data['url_mobile'] = button['linkMo']
                                    if 'linkPc' in button:
                                        button_data['url_pc'] = button['linkPc']
                                    if 'linkIos' in button:
                                        button_data['scheme_ios'] = button['linkIos']
                                    if 'linkAnd' in button:
                                        button_data['scheme_android'] = button['linkAnd']
                                    button_list.append(button_data)
                                data[0]['button'] = button_list
                                # 데이터 처리 후 병합
                            if isinstance(data[0], dict):
                                payload.update(data[0])
                            response = requests.post(api_ft_url, headers=headers, data=json.dumps([payload]))
                            response.raise_for_status()
                            response_data = response.json()
                        if response_data and isinstance(response_data, list) and len(response_data) > 0:
                            data = response_data[0]

                            # 발송 성공 여부 확인 ("code"가 "AS"인 경우)
                            if data.get("code") == "AS":
                                total_sent += 1

                            # 데이터베이스 업데이트
                            update_sql = """
                            UPDATE TBL_SEND_TRAN_KKO
                            SET
                                fetc2 = %s,
                                fetc3 = %s,
                                fetc4 = %s,
                                fetc5 = %s,
                                fetc6 = %s
                            WHERE fseq = %s
                            """
                            cursor.execute(update_sql, (
                                data.get("code", None),
                                data.get("altCode", None),
                                data.get("altMsg", None),
                                data.get("altSndDtm", None),
                                data.get("altRcptDtm", None),
                                result['fseq']
                            ))

                        else:
                           logging.error(f"응답 데이터가 유효하지 않습니다: {response_data}")

                    except requests.exceptions.RequestException as e:
                        logging.error(f"API 요청 중 오류 발생: {e}")

                # 발송 건수가 2건 이상이고, 발송 성공 건수가 있는 경우 포인트 차감
                if total_sent >= 2:
                    total_fee = total_sent * mb_kko_fee
                    update_point(cursor, member_info_idx, total_fee, f"알림톡 발송({total_sent}건)")
                    logging.info(f"{total_sent}건의 발송 성공에 대해 포인트 {total_fee} 차감 완료")

                connection.commit()
                logging.info(f"{batch_size}건의 API 요청 처리 및 데이터베이스 업데이트 완료")
            else:
                logging.info("fetc2 컬럼이 'AR'인 데이터를 찾을 수 없습니다.")
    except pymysql.MySQLError as e:
        logging.error(f"데이터베이스 작업 중 오류 발생: {e}")
    finally:
        connection.close()

def batch_worker():
    while True:
        process_data()
        print("유휴 상태 확인 및 5초 대기 중...")
        time.sleep(5)  # 5초 대기

if __name__ == "__main__":
    batch_worker()
