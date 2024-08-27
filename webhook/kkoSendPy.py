import pymysql
import requests
import json
from dotenv import load_dotenv
import os

# .env 파일에서 환경 변수 로드
load_dotenv()

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
api_endpoint = '/v3/A/leahue1/messages'
api_url = f"{api_base_url}{api_endpoint}"  # 베이스 URL과 엔드포인트 결합
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
            # fetc2 컬럼에 'AR' 값이 있는 행을 여러 개 선택 (예: 100개씩 배치 처리)
            batch_size = 100
            sql = "SELECT * FROM TBL_SEND_TRAN_KKO WHERE fetc2 = 'AR' LIMIT %s"
            cursor.execute(sql, (batch_size,))
            results = cursor.fetchall()

            if results:
                total_sent = 0  # 발송 성공 건수 누적

                for result in results:
                    member_info_idx = result['fetc8']  # `member_info_idx`는 이제 `fetc8`에서 가져옵니다
                    mb_kko_fee = get_mb_kko_fee(cursor, member_info_idx)  # `member_info_sendinfotable`에서 `mb_kko_fee`를 가져옵니다

                    if mb_kko_fee is None:
                        print(f"Member info not found or mb_kko_fee is missing for member_idx: {member_info_idx}")
                        continue

                    # API 전송을 위한 데이터 구성
                    payload = json.dumps([
                        {
                            "custMsgSn": cust_msg_sn,
                            "senderKey": result['fyellowid'],
                            "phoneNum": result['fdestine'],
                            "templateCode": result['ftemplatekey'],
                            "message": result['fmessage']
                        }
                    ])

                    try:
                        response = requests.post(api_url, headers=headers, data=payload)
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
                                fetc1 = %s,
                                fetc2 = %s,
                                fetc3 = %s,
                                fetc4 = %s,
                                fetc5 = %s,
                                fetc6 = %s,
                                fetc8 = %s
                            WHERE fseq = %s
                            """
                            cursor.execute(update_sql, (
                                data.get("sn", None),
                                data.get("code", None),
                                data.get("altCode", None),
                                data.get("altMsg", None),
                                data.get("altSndDtm", None),
                                data.get("altRcptDtm", None),
                                member_info_idx,
                                result['fseq']
                            ))

                        else:
                            print(f"응답 데이터가 유효하지 않습니다: {response_data}")

                    except requests.exceptions.RequestException as e:
                        print(f"API 요청 중 오류 발생: {e}")

                # 발송 건수가 2건 이상이고, 발송 성공 건수가 있는 경우 포인트 차감
                if total_sent >= 2:
                    total_fee = total_sent * mb_kko_fee
                    update_point(cursor, member_info_idx, total_fee, f"알림톡 발송({total_sent}건)")
                    print(f"{total_sent}건의 발송 성공에 대해 포인트 {total_fee} 차감 완료")

                connection.commit()
                print(f"{batch_size}건의 API 요청 처리 및 데이터베이스 업데이트 완료")
            else:
                print("fetc2 컬럼이 'AR'인 데이터를 찾을 수 없습니다.")
    except pymysql.MySQLError as e:
        print(f"데이터베이스 작업 중 오류 발생: {e}")
    finally:
        connection.close()

if __name__ == "__main__":
    process_data()
