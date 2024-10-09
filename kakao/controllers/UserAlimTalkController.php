<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/UserAlimTalkModel.php';
include $_SERVER["DOCUMENT_ROOT"] . "/master/include/xlsx_writer.php";
class UserAlimTalkController extends Controller
{
    private $UserAlimTalkModel;
    public function __construct()
    {
        // 부모 클래스의 생성자를 명시적으로 호출하여 상속된 속성을 초기화
        parent::__construct();
        $this->UserAlimTalkModel = new UserAlimTalkModel();
    }
    public function index() {

        $this->view('userSendList');
    }
    public function getUserAlimTalkSendList(){
        try {
            $member_idx=$this->data['inc_member_row']['idx'];
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
            $currentDate = new DateTime();  // 현재 날짜
            $startDate = (isset($_GET['s_date']) && trim($_GET['s_date']) !== '') ? new DateTime($_GET['s_date']) : (clone $currentDate)->modify('-1 months');
            $endDate = (isset($_GET['e_date']) && trim($_GET['e_date']) !== '') ? new DateTime($_GET['e_date']) : $currentDate;
            // 키워드에서 숫자 이외의 모든 문자를 제거
            if ($keyword !== null) {
                $keyword = preg_replace('/[^0-9]/', '', $keyword);
            }
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $data = $this->UserAlimTalkModel->getKakaoSendList($member_idx,$offset, $limit,$keyword,$startDate,$endDate);
            $total = $this->UserAlimTalkModel->getTotalKakaoSendList($member_idx,$keyword,$startDate,$endDate);
            $this->sendJsonResponse(['success' => true, 'data' => $data, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred'.getMessage()]);
        }
    }
    public function sendDetail(){
        try {
            $group_key = isset($_GET['group_key']) ? $_GET['group_key'] : null;
            if($group_key){
                $data = $this->UserAlimTalkModel->sendDetail($group_key);
                $data['list'] = $this->UserAlimTalkModel->sendKkoListDetail($group_key);
            }else{
                throw new Exception('서버에서 data 를 불러오는 중 오류 발생');
            }


            $this->sendJsonResponse(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred'.getMessage()]);
        }
    }
    public function getSendListDetail(){
        try {
            $idx = !empty(trim($_GET['idx'] ?? '')) ? $_GET['idx'] : null;
            $data['smsSave'] = $this->UserAlimTalkModel->getSendListDetailSmsSave($idx);
            $mb_short_fee = $data['smsSave'][0]['mb_short_fee'];
            $mb_long_fee = $data['smsSave'][0]['mb_long_fee'];
            $mb_img_fee = $data['smsSave'][0]['mb_img_fee'];
            $module_type = $data['smsSave'][0]['module_type'];
            $yearMonth = date('Ym', strtotime($data['smsSave'][0]['wdate']));
            $tableName = "TBL_SEND_LOG_" . $yearMonth;

            if($module_type=="LG"){
                $data['sum'] = $this->UserAlimTalkModel->getSendListDetailLG($idx,$mb_short_fee,$mb_long_fee,$mb_img_fee,$tableName);
                $data['saveCall'] = $this->UserAlimTalkModel->getSendListDetailSaveCall($idx,$tableName,'fetc1');
            }
            if($module_type=="JUD1"){
                $data['sum'] = $this->UserAlimTalkModel->getSendListDetailJUD1($idx,$mb_short_fee,$mb_long_fee,$mb_img_fee,$tableName);
                $data['saveCall'] = $this->UserAlimTalkModel->getSendListDetailSaveCall($idx,'SMS_BACKUP_AGENT_JUD1','S_ETC1');
            }
            if($module_type=="JUD2"){
                $data['sum'] = $this->UserAlimTalkModel->getSendListDetailJUD2($idx,$mb_short_fee,$mb_long_fee,$mb_img_fee,$tableName);
                $data['saveCall'] = $this->UserAlimTalkModel->getSendListDetailSaveCall($idx,'SMS_BACKUP_AGENT_JUD2','S_ETC1');
            }
            $tot_cnt = $this->UserAlimTalkModel->getTotalSendListDetailSaveCall($idx);
            $data['smsSave'][0]['tot_cnt']=$tot_cnt[0]['cnt'];
            $this->sendJsonResponse(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred'.getMessage()]);
        }
    }
    public function excelDownload(){
        $idx = !empty(trim($_GET['idx'] ?? '')) ? $_GET['idx'] : null;
        $downloadSuccess = filter_var($_GET['downloadSuccess'], FILTER_VALIDATE_BOOLEAN);
        $header = array(
            "전송일시" => "string",
            "발신번호" => "string",
            "수신번호" => "string",
            "통신사" => "string",
            "발송결과" => "string",
        );
        $data['smsSave'] = $this->UserAlimTalkModel->getSendListDetailSmsSave($idx);

        $module_type = $data['smsSave'][0]['module_type'];
        $yearMonth = date('Ym', strtotime($data['smsSave'][0]['wdate']));
        $tableName = "TBL_SEND_LOG_" . $yearMonth;

        if($module_type=="LG"){
            $data['saveCall'] = $this->UserAlimTalkModel->getSendListDetailSaveCallExcel($idx,$tableName,'fetc1',$downloadSuccess,'frsltstat','06','fmobilecomp','LG');
        }
        if($module_type=="JUD1"){
            $data['saveCall'] = $this->UserAlimTalkModel->getSendListDetailSaveCallExcel($idx,'SMS_BACKUP_AGENT_JUD1','S_ETC1',$downloadSuccess,'RSTATE','0','TELECOM');
        }
        if($module_type=="JUD2"){
            $data['saveCall'] = $this->UserAlimTalkModel->getSendListDetailSaveCallExcel($idx,'SMS_BACKUP_AGENT_JUD2','S_ETC1',$downloadSuccess,'RSTATE','0','TELECOM');
        }

        $row_data = array();
        foreach ($data['saveCall'] as $row) {
            $filedValues = array(
                preg_replace('/[\"]/', '""', $row['work_date']),
                preg_replace('/[\"]/', '""', $row['cell_send']),
                preg_replace('/[\"]/', '""', $row['cell']),
                preg_replace('/[\"]/', '""', $row['isp']),
                preg_replace('/[\"]/', '""', $row['status']),
                preg_replace('/[\"]/', '""', $row['code_description'])
            );
            array_push($row_data, $filedValues);
        }

        $file_date = date("YmdHis");
        $filename = "전체_발송내역_" . $file_date . ".xlsx";
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header);
        foreach ($row_data as $rows) {
            $writer->writeSheetRow('Sheet1', $rows);
        }
        $writer->writeToStdOut();
    }
    public function excelDownloadKaKao(){
        $idx = !empty(trim($_GET['idx'] ?? '')) ? $_GET['idx'] : null;
        $downloadSuccess = filter_var($_GET['downloadSuccess'], FILTER_VALIDATE_BOOLEAN);
        $header = array(
            "전송일시" => "string",
            "발신번호" => "string",
            "수신번호" => "string",
            "결과코드" => "string",
            "결과메세지" => "string",
        );

        $data['saveCall'] = $this->UserAlimTalkModel->getSendListDetailSaveCallExcelKaKao($idx,$downloadSuccess);

        $row_data = array();
        foreach ($data['saveCall'] as $row) {
            $filedValues = array(
                preg_replace('/[\"]/', '""', $row['fsenddate']),
                preg_replace('/[\"]/', '""', $row['fcallback']),
                preg_replace('/[\"]/', '""', $row['fdestine']),
                preg_replace('/[\"]/', '""', $row['fetc3']),
                preg_replace('/[\"]/', '""', $row['fetc4'])
            );
            array_push($row_data, $filedValues);
        }

        $file_date = date("YmdHis");
        $filename = "전체_발송내역_" . $file_date . ".xlsx";
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header);
        foreach ($row_data as $rows) {
            $writer->writeSheetRow('Sheet1', $rows);
        }
        $writer->writeToStdOut();
    }
}