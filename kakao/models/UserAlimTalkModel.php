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


    public function getKakaoSendList($member_idx,$offset, $limit,$keyword = null)
    {
        // 기본 SQL 쿼리
        $sql = "SELECT * 
            FROM TBL_SEND_TRAN_KKO 
            WHERE fetc8 = :member_idx";

        // 키워드가 있을 경우 추가 조건
        if ($keyword !== null) {
            $sql .= " AND REPLACE(REGEXP_REPLACE(fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
        }

        // 정렬 및 페이징 처리
        $sql .= " ORDER BY fseq DESC
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

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalKakaoSendList($member_idx)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) as total FROM TBL_SEND_TRAN_KKO where fetc8 = :member_idx "
            );
            $stmt->bindParam(':member_idx', $member_idx, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve total getTotalKakaoSendList: ' . $e->getMessage());
        }
    }


}
?>
