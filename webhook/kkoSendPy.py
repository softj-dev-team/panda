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

# 데이터베이스 연결
connection = pymysql.connect(**db_config)

try:
    with connection.cursor(pymysql.cursors.DictCursor) as cursor:
        # fetc2 컬럼에 'AR' 값이 있는 행 중 하나를 선택
        sql = "SELECT * FROM TBL_SEND_TRAN_KKO WHERE fetc2 = 'AR' LIMIT 1"
        cursor.execute(sql)
        result = cursor.fetchone()

        if result:
            # API로 전송할 데이터 구성
            payload = json.dumps([
                {
                    "custMsgSn": "F46CBA8E658BAC08965FD887B767CBC1",
                    "senderKey": result['fyellowid'],
                    "phoneNum": result['fdestine'],
                    "templateCode": result['ftemplatekey'],
                    "message": result['fmessage']
                }
            ])

            # API 요청 전송
            response = requests.post(api_url, headers=headers, data=payload)

            try:
                response_data = response.json()

                # 응답 데이터 유효성 확인
                if response_data and isinstance(response_data, list) and len(response_data) > 0:
                    data = response_data[0]
                    # fetc1~fetc6 컬럼 업데이트
                    update_sql = """
                    UPDATE TBL_SEND_TRAN_KKO
                    SET
                        fetc1 = %s,
                        fetc2 = %s,
                        fetc3 = %s,
                        fetc4 = %s,
                        fetc5 = %s,
                        fetc6 = %s
                    WHERE fseq = %s
                    """
                    cursor.execute(update_sql, (
                        data.get("sn", None),
                        data.get("code", None),
                        data.get("altCode", None),
                        data.get("altMsg", None),
                        data.get("altSndDtm", None),
                        data.get("altRcptDtm", None),
                        result['fseq']
                    ))
                    connection.commit()
                    print("API 요청 성공 및 데이터베이스 업데이트 완료")
                else:
                    print("응답 데이터가 유효하지 않습니다:", response_data)

            except ValueError:
                print("응답 JSON 파싱에 실패했습니다:", response.text)
        else:
            print("fetc2 컬럼이 'AR'인 데이터를 찾을 수 없습니다.")

finally:
    connection.close()