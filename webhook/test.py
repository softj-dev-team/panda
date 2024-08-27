import pymysql

# 데이터베이스 연결 설정
db_config = {
    'host': 'localhost',  # 로컬 서버
    'user': 'asssahcom9',
    'password': 'soulvocal7!!',
    'database': 'asssahcom9',
    'charset': 'utf8mb4'
}

# 데이터베이스 연결 시도
try:
    connection = pymysql.connect(**db_config)
    print("데이터베이스에 성공적으로 연결되었습니다.")

    # 연결된 상태에서 간단한 쿼리 실행 (예: 테이블 목록 가져오기)
    with connection.cursor() as cursor:
        cursor.execute("SHOW TABLES;")
        tables = cursor.fetchall()
        print("데이터베이스에 있는 테이블 목록:")
        for table in tables:
            print(table)

except pymysql.MySQLError as e:
    print(f"데이터베이스 연결 실패: {e}")
finally:
    if connection:
        connection.close()
        print("데이터베이스 연결이 종료되었습니다.")
