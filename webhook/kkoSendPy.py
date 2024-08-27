import pymysql
import requests
import json
from concurrent.futures import ThreadPoolExecutor, as_completed

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
        # fetc2 컬럼에 'AR' 값이 있는 행을 여러 개 선택 (예: 100개씩 배치 처리)
        batch_size = 100
        sql = f"SELECT * FROM TBL_SEND_TRAN_KKO WHERE fetc2 = 'AR' LIMIT {batch_size}"
        cursor.execute(sql)
        results = cursor.fetchall()

        if results:
            # 비동기로 API 요청 처리
            def send_request(result):
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
                return response_data, result['fseq']

            with ThreadPoolExecutor(max_workers=10) as executor:
                futures = [executor.submit(send_request, result) for result in results]

                for future in as_completed(futures):
                    try:
                        response_data, fseq = future.result()

                        if response_data and isinstance(response_data, list) and len(response_data) > 0:
                            data = response_data[0]
                            # 데이터베이스 업데이트
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
                                fseq
                            ))
                        else:
                            print(f"응답 데이터가 유효하지 않습니다: {response_data}")

                    except ValueError as e:
                        print(f"응답 JSON 파싱에 실패했습니다: {e}")
                    except Exception as e:
                        print(f"API 요청 중 오류 발생: {e}")

            connection.commit()
            print(f"{batch_size}건의 API 요청 처리 및 데이터베이스 업데이트 완료")
        else:
            print("fetc2 컬럼이 'AR'인 데이터를 찾을 수 없습니다.")

finally:
    connection.close()
