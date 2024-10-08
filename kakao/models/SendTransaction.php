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

    public function saveMessage( $fdestine,$fcallback,$message,$profile_key,$template_key,$sn,$code,$altCode,$altMsg,$altSndDtm,$altRcptDtm,$group_key,$member_idx)
    {
        try {

            $stmt = $this->conn->prepare("INSERT INTO TBL_SEND_TRAN_KKO
                (fyellowid, ftemplatekey, fkkoresendtype, fmsgtype, fmessage, fsenddate, fdestine, fcallback,fetc1,fetc2,fetc3,fetc4,fetc5,fetc6,fetc7, fetc8)
                VALUES
                (:profile_key, :template_key, 'N', 4, :message, now(), :fdestine, :fcallback, :fetc1, :fetc2, :fetc3, :fetc4, :fetc5, :fetc6,:fetc7, :fetc8)");
            $stmt->bindParam(':profile_key', $profile_key);
            $stmt->bindParam(':template_key', $template_key);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':fdestine', $fdestine);
            $stmt->bindParam(':fcallback', $fcallback);
            $stmt->bindParam(':fetc1', $sn);
            $stmt->bindParam(':fetc2', $code);
            $stmt->bindParam(':fetc3', $altCode);
            $stmt->bindParam(':fetc4', $altMsg);
            $stmt->bindParam(':fetc5', $altSndDtm);
            $stmt->bindParam(':fetc6', $altRcptDtm);
            $stmt->bindParam(':fetc7', $group_key);
            $stmt->bindParam(':fetc8', $member_idx);
            $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception('Failed to save message: ' . $e->getMessage());
        }
    }
    public function saveMessageByList( $fdestine,$fcallback,$message,$profile_key,$template_key,$member_idx,$group_key)
    {
        try {

            $stmt = $this->conn->prepare("INSERT INTO TBL_SEND_TRAN_KKO
                (fyellowid, ftemplatekey, fkkoresendtype, fmsgtype, fmessage, fsenddate, fdestine, fcallback,fetc8,fetc7)
                VALUES
                (:profile_key, :template_key, 'N', 4, :message, now(), :fdestine, :fcallback,:fetc8,:fetc7)");

                $stmt->bindParam(':profile_key', $profile_key);
                $stmt->bindParam(':template_key', $template_key);
                $stmt->bindParam(':message', $message);
                $stmt->bindParam(':fdestine', $fdestine);
                $stmt->bindParam(':fcallback', $fcallback);
                $stmt->bindParam(':fetc8', $member_idx);
                $stmt->bindParam(':fetc7', $group_key);
                $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception('Failed to save message: ' . $e->getMessage());
        }
    }
}
?>
