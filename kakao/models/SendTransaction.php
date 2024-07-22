<?php
require_once 'Database.php';

class SendTransaction
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function saveMessage($name, $date, $system, $fdestine,$fcallback)
    {
        try {
            $message = "[테스트]\n {$name}님은 {$date} 부로 {$system}에 가입하셨습니다.더 좋은 서비스를 위해 노력하겠습니다. 감사합니다.";
            $stmt = $this->conn->prepare("INSERT INTO TBL_SEND_TRAN_KKO
                (fyellowid, ftemplatekey, fkkoresendtype, fmsgtype, fmessage, fsenddate, fdestine, fcallback)
                VALUES
                ('9f89e73de6a9466c80a847d4bf832512', 'LGHV_51585550377247458320', 'N', 4, :message, now(), :fdestine, :fcallback)");
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':fdestine', $fdestine);
            $stmt->bindParam(':fcallback', $fcallback);
            $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception('Failed to save message: ' . $e->getMessage());
        }
    }
}
?>
