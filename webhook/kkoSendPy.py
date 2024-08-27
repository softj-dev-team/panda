import pymysql
import requests
import json

# 데이터베이스 연결 설정
db_config = {
    'host': 'localhost',
    'user': 'asssahcom9',
    'password': 'soulvocal7!!',
    'database': 'asssahcom9',
    'charset': 'utf8mb4'
}

# API 설정
api_url = 'https://wt-api.carrym.com:8443/v3/A/leahue1/messages'
headers = {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer F46CBA8E658BAC08965FD887B767CBC1'
}

def get_current_point(cursor, member_idx):
    cursor.execute("""
        SELECT cur_mile FROM member_point
        WHERE member_idx = %s AND point_sect = 'smspay'
        ORDER BY idx DESC LIMIT 1
    """, (member_idx,))
    result = cursor.fetchone()
    return result['cur_mile'] if result else 0

def update_point(cursor, member_idx, chg_mile, mile_title):
    mile_pre = get_current_point(cursor, member_idx)
    cur_mile = max(mile_pre - chg_mile, 0)

    sql = """
    INSERT INTO member_point
    (order_num, member_idx, pay_price, mile_title, mile_sect, mile_pre, chg_mile, cur_mile, point_sect, wdate)
    VALUES
    (NULL, %s, NULL, %s, 'M', %s, %s, %s, 'smspay', NOW())
    """
    cursor.execute(sql, (member_idx, mile_title, mile_pre, chg_mile, cur_mile))

def process_data():
    connection = pymysql.connect(**db_config)
    try:
        with connection.cursor(pymysql.cursors.DictCursor) as cursor:
            # fetc2 컬럼에 'AR' 값이 있는 행을 여러 개 선택 (예: 100개씩 배치 처리)
            batch_size = 100
            sql = f"SELECT * FROM TBL_SEND_TRAN_KKO WHERE fetc2 = 'AR' LIMIT {batch_size}"
            cursor.execute(sql)
            results = cursor.fetchall()

            if results:
                for result in results:
                    member_info_idx = result['member_info_idx']  # `member_info_idx` 가져오기
                    mb_kko_fee = 10  # 가정: 알림톡 발송에 따른 포인트 차감 금액이 10포인트라고 가정

                    # API 전송을 위한 데이터 구성
                    payload = json.dumps([
                        {
                            "custMsgSn": "F46CBA8E658BAC08965FD887B767CBC1",
                            "senderKey": result['fyellowid'],
                            "phoneNum": result['fdestine'],
                            "templateCode": result['ftemplatekey'],
                            "message": result['fmessage']
                        }
                    ])

                    response = requests.post(api_url, headers=headers, data=payload)
                    response_data = response.json()

                    if response_data and isinstance(response_data, list) and len(response_data) > 0:
                        data = response_data[0]

                        # 데이터베이스 업데이트 (fetc8에 member_info_idx 저장)
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

                        # 포인트 차감 로직 수행
                        update_point(cursor, member_info_idx, mb_kko_fee, "알림톡 발송")
                    else:
                        print(f"응답 데이터가 유효하지 않습니다: {response_data}")

                connection.commit()
                print(f"{batch_size}건의 API 요청 처리 및 데이터베이스 업데이트 완료")
            else:
                print("fetc2 컬럼이 'AR'인 데이터를 찾을 수 없습니다.")
    finally:
        connection.close()

if __name__ == "__main__":
    process_data()
