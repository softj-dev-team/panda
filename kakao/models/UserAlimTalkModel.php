<?php

require_once 'Database.php';

class UserAlimTalkModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function sendDetail($group_key)
    {
        // 기본 SQL 쿼리
        $sql = "SELECT 
                        a.*,count(a.fetc7) as tot_cnt,
                        mi.mb_kko_fee,
                        SUM(CASE WHEN fetc2 = 'AS' THEN 1 ELSE 0 END) AS receive_cnt_suc,
                        SUM(CASE WHEN fetc2 != 'AS' THEN 1 ELSE 0 END) AS receive_cnt_fail,
                        tp.id as template_id,
                        FORMAT(SUM(CASE WHEN fetc2 = 'AS' THEN 1 ELSE 0 END) * mi.mb_kko_fee, 2) as use_point
                    FROM TBL_SEND_TRAN_KKO a 
                        LEFT JOIN member_info_sendinfo mi ON mi.member_idx = a.fetc8
                        left join template tp on tp.template_key = a.ftemplatekey
                    WHERE a.fetc7 =:fetc7 group by a.fetc7";
        $stmt = $this->conn->prepare($sql);
        // 기본 바인딩
        $stmt->bindParam(':fetc7', $group_key, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getKakaoSendList($member_idx,$offset, $limit,$keyword = null,$startDate=null,$endDate=null)
    {
        // 기본 SQL 쿼리
        $sql = "SELECT *,count(fetc7) as tot_cnt 
            FROM TBL_SEND_TRAN_KKO 
            WHERE fetc8 = :member_idx ";

        // 키워드가 있을 경우 추가 조건
        if ($keyword !== null) {
            $sql .= " AND REPLACE(REGEXP_REPLACE(fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
        }

        // 날짜 범위가 있을 경우 추가 조건
        if ($startDate !== null) {
            $sql .= " AND DATE(finsertdate) >= :startDate";
        }
        if ($endDate !== null) {
            $sql .= " AND DATE(finsertdate) <= :endDate";
        }
        // 정렬 및 페이징 처리
        $sql .= " GROUP BY fetc7 
              ORDER BY fseq DESC
              LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);

        // 기본 바인딩
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':member_idx', $member_idx, PDO::PARAM_INT);

        // 키워드가 있을 경우 바인딩
        if ($keyword !== null) {
            $keywordParam = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);
        }
        // 날짜 바인딩 처리
        if ($startDate !== null) {
            $stmt->bindParam(':startDate', $startDate->format('Y-m-d'), PDO::PARAM_STR);
        }
        if ($endDate !== null) {
            $stmt->bindParam(':endDate', $endDate->format('Y-m-d'), PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalKakaoSendList($member_idx, $keyword = null,$startDate=null,$endDate=null)
    {
        try {
            // 기본 SQL 쿼리
            $sql = "SELECT COUNT(*) as total 
                FROM (
                    SELECT fetc7 
                    FROM TBL_SEND_TRAN_KKO 
                    WHERE fetc8 = :member_idx ";

            if ($keyword !== null) {
                $sql .= " AND REPLACE(REGEXP_REPLACE(fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
            }
            // 날짜 범위가 있을 경우 추가 조건
            if ($startDate !== null) {
                $sql .= " AND DATE(finsertdate) >= :startDate";
            }
            if ($endDate !== null) {
                $sql .= " AND DATE(finsertdate) <= :endDate";
            }
            // 서브쿼리 종료 및 외부 쿼리
            $sql .= " GROUP BY fetc7
                  ) AS send_total";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':member_idx', $member_idx, PDO::PARAM_INT);

            if ($keyword !== null) {
                $keywordParam = '%' . $keyword . '%';
                $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);
            }

            // 날짜 바인딩
            if ($startDate !== null) {
                $stmt->bindParam(':startDate', $startDate->format('Y-m-d'), PDO::PARAM_STR);
            }
            if ($endDate !== null) {
                $stmt->bindParam(':endDate', $endDate->format('Y-m-d'), PDO::PARAM_STR);
            }
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve total getTotalKakaoSendList: ' . $e->getMessage());
        }
    }


}
?>
