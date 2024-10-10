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
    public function getBlockCallNumber($call=null){
        $sql=
            "SELECT cell_num FROM spam_list where cell_num=:cell_num";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cell_num', $call, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKakaoSendList($offset, $limit,$keyword=null,$startDate=null,$endDate=null)
    {
       $sql=
            "SELECT a.*,mb.user_id,mb.user_name,count(a.fetc7) as tot_cnt
                     FROM TBL_SEND_TRAN_KKO a
                         left join member_info mb on mb.idx=a.fetc8 where 1";
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalKakaoSendList($keyword=null,$startDate=null,$endDate=null)
    {
        try {
            $sql="SELECT COUNT(*) as total FROM TBL_SEND_TRAN_KKO a left join member_info mb on mb.idx=a.fetc8 where 1";
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
    public function getKakaoSendListDetail($offset, $limit,$keyword=null,$startDate=null,$endDate=null,$group_key=null)
    {
        $sql=
            "SELECT a.*,mb.user_id,mb.user_name
                     FROM TBL_SEND_TRAN_KKO a
                         left join member_info mb on mb.idx=a.fetc8 where 1";
        // 키워드가 있을 경우 추가 조건
        if ($keyword !== null) {
            $sql .= " AND REPLACE(REGEXP_REPLACE(a.fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
            $sql .= " AND mb.user_id LIKE :keyword";
            $sql .= " AND mb.user_name LIKE :keyword";
        }

        if ($group_key !== null) {
            $sql .= " AND a.fetc7 = :fetc7";
        }
        // 날짜 범위가 있을 경우 추가 조건
        if ($startDate !== null) {
            $sql .= " AND DATE(a.finsertdate) >= :startDate";
        }
        if ($endDate !== null) {
            $sql .= " AND DATE(a.finsertdate) <= :endDate";
        }
        $sql .= " ORDER BY a.fseq DESC 
                     LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        if ($keyword !== null) {
            $keywordParam = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);
        }
        if ($group_key !== null) {
            $stmt->bindParam(':fetc7', $group_key, PDO::PARAM_STR);
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
        error_log('data :['.$group_key.']['.$limit.'] ['.$offset.']['.$startDate->format('Y-m-d').']['.$endDate->format('Y-m-d')).']';
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalKakaoSendListDetail($keyword=null,$startDate=null,$endDate=null,$group_key=null)
    {
        try {
            $sql="SELECT COUNT(*) as total FROM TBL_SEND_TRAN_KKO a left join member_info mb on mb.idx=a.fetc8 where 1";
            // 키워드가 있을 경우 추가 조건
            if ($keyword !== null) {
                $sql .= " AND REPLACE(REGEXP_REPLACE(a.fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
                $sql .= " AND mb.user_id LIKE :keyword";
                $sql .= " AND mb.user_name LIKE :keyword";
            }
            if ($group_key !== null) {
                $sql .= " AND a.fetc7 = :fetc7";
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
            if ($group_key !== null) {
                $stmt->bindParam(':fetc7', $group_key, PDO::PARAM_STR);
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
