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


    public function getKakaoSendList($offset, $limit)
    {
        $stmt = $this->conn->prepare(
            "SELECT * 
                     FROM TBL_SEND_TRAN_KKO                     
                     ORDER BY fseq DESC
                     LIMIT :limit OFFSET :offset"
        );
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalKakaoSendList()
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) as total FROM TBL_SEND_TRAN_KKO"
            );

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve total getTotalKakaoSendList: ' . $e->getMessage());
        }
    }


}
?>
