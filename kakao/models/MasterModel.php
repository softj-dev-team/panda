<?php

require_once 'Database.php';

class MasterModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }


    public function getKakaoSendList($offset, $limit,$keyword=null,$startDate=null,$endDate=null)
    {
       $sql=
            "SELECT a.*,mb.user_id,mb.user_name,count(a.fetc7) as tot_cnt
                     FROM TBL_SEND_TRAN_KKO a
                         left join member_info mb on mb.idx=a.fetc8 ";
        // 키워드가 있을 경우 추가 조건
        if ($keyword !== null) {
            $sql .= " AND REPLACE(REGEXP_REPLACE(a.fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
            $sql .= " AND mb.user_id LIKE :keyword";
            $sql .= " AND mb.user_name LIKE :keyword";
        }

        // 날짜 범위가 있을 경우 추가 조건
        if ($startDate !== null) {
            $sql .= " AND DATE(a.finsertdate) >= :startDate";
        }
        if ($endDate !== null) {
            $sql .= " AND DATE(a.finsertdate) <= :endDate";
        }
        $sql .= " group by a.fetc7 ORDER BY a.fseq DESC 
                     LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
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
        error_log($sql);
        error_log('keyword : '.$keyword);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalKakaoSendList($keyword=null,$startDate=null,$endDate=null)
    {
        try {
            $sql="SELECT COUNT(*) as total FROM TBL_SEND_TRAN_KKO a left join member_info mb on mb.idx=a.fetc8";
            // 키워드가 있을 경우 추가 조건
            if ($keyword !== null) {
                $sql .= " AND REPLACE(REGEXP_REPLACE(a.fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
                $sql .= " AND mb.user_id LIKE :keyword";
                $sql .= " AND mb.user_name LIKE :keyword";
            }

            // 날짜 범위가 있을 경우 추가 조건
            if ($startDate !== null) {
                $sql .= " AND DATE(a.finsertdate) >= :startDate";
            }
            if ($endDate !== null) {
                $sql .= " AND DATE(a.finsertdate) <= :endDate";
            }
            $sql .= " group by a.fetc7";
            $stmt = $this->conn->prepare($sql);
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
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve total getTotalKakaoSendList: ' . $e->getMessage());
        }
    }


}
?>
