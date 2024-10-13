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

    public function saveMessage(
        $fdestine=null,$fcallback=null,$message=null,$profile_key=null,$template_key=null,$sn=null,$code=null,$altCode=null,$altMsg=null,
        $altSndDtm=null,$altRcptDtm=null,$group_key=null, $member_idx=null,$client_ip=null,$fuserid=null,$buttons=null,
        $img_path=null,$msg_type=null
    )
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO TBL_SEND_TRAN_KKO
                (
                 fyellowid, ftemplatekey,fkkoresendtype,fmsgtype,fmessage, fsenddate, fdestine,fcallback,
                 fetc1,fetc2,fetc3,fetc4,fetc5,fetc6,fetc7,fetc8,
                 fuserid,buttons,img_path,msg_type
                 )
                VALUES
                (:profile_key, :template_key, 'N', 4, :message, now(), :fdestine, :fcallback,
                 :fetc1, :fetc2, :fetc3, :fetc4, :fetc5, :fetc6,:fetc7, :fetc8,:fuserid,:buttons,:img_path,:msg_type)");
            $stmt->bindParam(':profile_key', $profile_key);
            $stmt->bindParam(':template_key', $template_key);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':fdestine', $fdestine);
            $stmt->bindParam(':fcallback', $fcallback);
            $stmt->bindParam(':fetc1', $client_ip);
            $stmt->bindParam(':fetc2', $code);
            $stmt->bindParam(':fetc3', $altCode);
            $stmt->bindParam(':fetc4', $altMsg);
            $stmt->bindParam(':fetc5', $altSndDtm);
            $stmt->bindParam(':fetc6', $altRcptDtm);
            $stmt->bindParam(':fetc7', $group_key);
            $stmt->bindParam(':fetc8', $member_idx);
            $stmt->bindParam(':fuserid', $fuserid);
            $stmt->bindParam(':buttons', $buttons);
            $stmt->bindParam(':img_path', $img_path);
            $stmt->bindParam(':msg_type', $msg_type);
            $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception('Failed to save message: ' . $e->getMessage());
        }
    }
    public function saveMessageByList($fdestine, $fcallback, $message, $profile_key, $template_key,
                                      $member_idx, $group_key, $client_ip,$smssendyn,$smsmessage,
                                      $smsKind,$kisaOrigCode,$fuserid,$buttons=null,$msg_type=null,$img_path=null)
    {
        try {

            $stmt = $this->conn->prepare("INSERT INTO TBL_SEND_TRAN_KKO
                (fyellowid, ftemplatekey, fkkoresendtype, fmsgtype, fmessage, fsenddate, fdestine, fcallback,
                 fetc8,fetc7,fetc1,smsmessage,sms_send_yn,sms_kind,kisa_orig_code,fuserid,buttons,msg_type,img_path,)
                VALUES
                (:profile_key, :template_key, 'N', 4, :message, now(), :fdestine, :fcallback,:fetc8,:fetc7,:fetc1,:smsmessage,
                 :sms_send_yn,:sms_kind,:kisa_orig_code, :fuserid,:buttons, :msg_type, :img_path)");

                $stmt->bindParam(':profile_key', $profile_key);
                $stmt->bindParam(':template_key', $template_key);
                $stmt->bindParam(':message', $message);
                $stmt->bindParam(':fdestine', $fdestine);
                $stmt->bindParam(':fcallback', $fcallback);
                $stmt->bindParam(':fetc8', $member_idx);
                $stmt->bindParam(':fetc7', $group_key);
                $stmt->bindParam(':fetc1', $client_ip);
                $stmt->bindParam(':smsmessage', $smsmessage);
                $stmt->bindParam(':sms_send_yn', $smssendyn);
                $stmt->bindParam(':sms_kind', $smsKind);
                $stmt->bindParam(':kisa_orig_code', $kisaOrigCode);
                $stmt->bindParam(':fuserid', $fuserid);
                $stmt->bindParam(':buttons', $buttons);
                $stmt->bindParam(':msg_type', $msg_type);
                $stmt->bindParam(':img_path', $img_path);
                $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception('Failed to save message: ' . $e->getMessage());
        }
    }
    public function saveMessageByFtalkList($fdestine, $fcallback, $message, $profile_key, $template_key,
                                      $member_idx, $group_key, $client_ip,$smssendyn,$smsmessage,
                                      $smsKind,$kisaOrigCode,$fuserid,$buttons=null,$msg_type=null,$img_path=null)
    {
        try {

            $stmt = $this->conn->prepare("
                INSERT INTO TBL_SEND_TRAN_KKO
                    (fyellowid, ftemplatekey, fkkoresendtype, fmsgtype, fmessage, fsenddate, fdestine, fcallback,
                    fetc8,fetc7,fetc1,smsmessage,sms_send_yn,sms_kind,kisa_orig_code,fuserid,buttons,msg_type,img_path)
                VALUES
                    (
                        :profile_key, :template_key, 'N', 4, :message, now(), :fdestine, :fcallback,:fetc8,:fetc7,:fetc1,:smsmessage,
                        :sms_send_yn,:sms_kind,:kisa_orig_code, :fuserid,:buttons, :msg_type, :img_path
                    )
            ");

            $stmt->bindParam(':profile_key', $profile_key);
            $stmt->bindParam(':template_key', $template_key);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':fdestine', $fdestine);
            $stmt->bindParam(':fcallback', $fcallback);
            $stmt->bindParam(':fetc8', $member_idx);
            $stmt->bindParam(':fetc7', $group_key);
            $stmt->bindParam(':fetc1', $client_ip);
            $stmt->bindParam(':smsmessage', $smsmessage);
            $stmt->bindParam(':sms_send_yn', $smssendyn);
            $stmt->bindParam(':sms_kind', $smsKind);
            $stmt->bindParam(':kisa_orig_code', $kisaOrigCode);
            $stmt->bindParam(':fuserid', $fuserid);
            $stmt->bindParam(':buttons', $buttons);
            $stmt->bindParam(':msg_type', $msg_type);
            $stmt->bindParam(':img_path', $img_path);
            $stmt->execute();

        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception('Failed to save message: ' . $e->getMessage());
        }
    }
}
?>
