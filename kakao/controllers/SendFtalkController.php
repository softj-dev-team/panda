<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/TemplateCategoryModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/CommonModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/PointModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/MemberModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/SendTransaction.php';
class SendFtalkController extends Controller {
    private $CommonModel;
    private $templateCategory;
    private $sendTransaction;
    private $pointModel;
    public function __construct()
    {
        // 부모 클래스의 생성자를 명시적으로 호출하여 상속된 속성을 초기화
        parent::__construct();
        $this->CommonModel = new CommonModel();
        $this->templateCategory = new TemplateCategoryModel();
        $this->sendTransaction = new SendTransaction();
        $this->pointModel = new PointModel();
    }
    public function index() {
//        $SendTranKKOModel = new SendTranKKO();
        $member_idx =$this->data['inc_member_row']['idx'];
        $data['result_group'] =$this->CommonModel->getAddress($member_idx);
        $filtering_list =$this->CommonModel->getFilteringText('filtering');
        $data['filteringArray'] = explode(",", $filtering_list['filtering_text']);
        $data['my_member_row']=$this->data['inc_member_row'];
        $this->view('sendftalk', $data);
    }
    public function getAddressSendNumber()
    {
        $group_idx=$_REQUEST['group_idx']??'';
        $keyword=$_REQUEST['keyword']??'';
        $member_idx=$this->data['inc_member_row']['idx'];
        // 파일 전송 요청
        $data = $this->CommonModel->getAddressSendNumber($member_idx,$group_idx,$keyword);
        if($data){
            $response = [
                'status' => 'success',
                'data' => $data
            ];
        }else{
            $response = ['success' => false, 'message' => '서버 요청 중 오류발생'];
        }

        echo json_encode($response);
    }
    public function sendMessage()
    {
        $response = ['status' => 'error', 'message' => 'An unknown error occurred'];
        $client_ip = $this->getClientIP();
        try {
            $smsSubject = $_REQUEST['subject']??'';
            $smssendyn =isset($_REQUEST['smssendyn'])??'';
            $smsmessage = $_REQUEST['smsmemo']??'';
            $kisaOrigCode = '301230126';
            $fcallback = $_REQUEST['cell_send']??'';
            $message = $_REQUEST['message'];
            $profile_id = $_REQUEST['profile_id']??'';
            $custMsgSn = 'PANDA_MSGSN_' . microtime(true) * 10000;
            $templateKey = $custMsgSn;
            $fuserid = 'FT';
            $profiles = $this->CommonModel->getProfileByProfileID($profile_id);
            $profile_key = $profiles['profile_key'];
            $receive_cell_num_arr=$_REQUEST['receive_cell_num_arr']??'';
            $fdestine = $receive_cell_num_arr[0];
            $sendSizeOf = sizeof($receive_cell_num_arr);
            $postLinkType =$_POST['postLinkType']??'';
            $member_idx=$this->data['inc_member_row']['idx'];
            $group_key = $this->generateUniqueNumericKey();
            $buttons=[];
            // 파일 업로드 처리
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $target_dir = $_SERVER['DOCUMENT_ROOT'].'/upload_file/kakao/';
                $fileName = basename($_FILES["file"]["name"]);
                $filePath = $target_dir . $fileName;

                if (!move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
                    throw new Exception('파일 업로드에 실패했습니다.');
                }

                $cfile = new CURLFile($filePath, $_FILES['file']['type'], $fileName);

                $imgUploadurl = 'https://wt-api.carrym.com:8445/api/v1/leahue/image/alimtalk/template';

                $data = [
                    'image' => $cfile,
                    'otherField' => 'some value' // 추가로 전송할 데이터가 있으면 여기에 포함
                ];
                $headers = [
                    'Content-Type: multipart/form-data'
                ];
                // 파일 전송 요청
                $responseImagePost = $this->sendCurlRequest($imgUploadurl, 'POST', $data, $headers);
                $responseImagePostDecoded = json_decode($responseImagePost, true);
                // 성공
                if (isset($responseImagePostDecoded['code']) && $responseImagePostDecoded['code'] == '0000' && $responseImagePostDecoded['image']) {
                    $image_path =$responseImagePostDecoded['image'];
                    $templateImageName=$fileName;
                    $_POST['image_path']=$image_path;
                }
                // 200
                if (isset($responseImagePost['code']) && $responseImagePost['code'] == '200' && $responseImagePost['message']) {
                    throw new Exception($responseImagePost['message']);
                }
            }

            if($smssendyn && $smsmessage){
                $smslength=strlen($smsmessage);
                if($smslength <= 90){
                    $smsKind= "S";
                }else{
                    $smsKind= "L";
                }
            }
            if($smssendyn && !$smsmessage){
                $smslength=strlen($message);
                if($smslength <= 90){
                    $smsKind= "S";
                    $smsmessage=$message;
                }else{
                    $smsKind= "L";
                    $smsmessage=$message;
                }
            }
            if (isset($postLinkType) && is_array($postLinkType)) {
                foreach ($_POST['postLinkType'] as $index => $postLinkType) {
                    $button = [
                        'ordering' => $_POST['ordering'][$index] ,
                        'linkType' => $postLinkType,
                        'name' => $_POST['name'][$index] ?? ''
                    ];
                    // 버튼 종류에 따라 추가 필드를 설정
                    if (!empty($_POST['linkMo'][$index])) {
                        $button['linkMo'] = $_POST['linkMo'][$index];              // 모바일 링크
                    }
                    if (!empty($_POST['linkPc'][$index])) {
                        $button['linkPc'] = $_POST['linkPc'][$index];
                    }
                    if (!empty($_POST['linkAnd'][$index])) {
                        $button['linkAnd'] = $_POST['linkAnd'][$index];
                    }
                    if (!empty($_POST['linkIos'][$index])) {
                        $button['linkIos'] = $_POST['linkIos'][$index];
                    }
                    if (!empty($_POST['bizFormId'][$index])) {
                        $button['bizFormId'] = $_POST['bizFormId'][$index];
                    }
                    if (!empty($_POST['pluginId'][$index])) {
                        $button['pluginId'] = $_POST['pluginId'][$index];
                    }
                    $buttons[] = $button;  // 버튼 추가
                }
            }
            $_POST['buttons']=$buttons;
            $save_buttons = json_encode($buttons);
            if($sendSizeOf > 1){
                foreach ($receive_cell_num_arr as $value){
                    $this->sendTransaction->saveMessageByFtalkList(
                        $value,
                        $fcallback,
                        $message,
                        $profile_key,
                        $templateKey,
                        $member_idx,
                        $group_key,
                        $client_ip,
                        $smssendyn,
                        $smsmessage,
                        $smsKind,
                        $kisaOrigCode,
                        $fuserid,
                        $save_buttons,
                        $_REQUEST['msgType'],
                        $image_path,
                        $smsSubject
                    );
                }
                // 성공한 경우
                $response = [
                    'status' => 'success',
                    'fuserid' => 'FT'
                ];
            }else{

                $responseData = $this->sendFriendTalkRequest($message,$fdestine, $fcallback, $profile_key, $custMsgSn,$_POST);

                if (isset($responseData[0]['code'])) {
                    $code = $responseData[0]['code'];
                    $responseMessage = isset($responseData[0]['altMsg']) ? $responseData[0]['altMsg'] : 'No message provided';

                    switch ($code) {
                        case 'AS':
                            // 알림톡/친구톡 발송 성공
                            break;
                        case 'SO':
                            throw new Exception('메시지 발송 가능한 시간이 아닙니다.(친구톡/마케팅 메시지는 08 시부터 20 시 50 분까지 발송 가능) ' . $responseMessage);
                        case 'AF':
                            throw new Exception('알림톡/친구톡 발송 실패: ' . $responseMessage);
                        case 'SS':
                            // 문자 발송 성공
                            break;
                        case 'SF':
                            throw new Exception('문자 발송 실패: ' . $responseMessage);
                        case 'EW':
                            $responseData[0]['altMsg']='알림톡 수신이 불가능한 사용자입니다. 대체문자 전송 완료.';
                            break;
                        case 'EL':
                            throw new Exception('발송결과 조회 데이터 없음: ' . $responseMessage);
                        case 'EF':
                            throw new Exception('시스템 실패 처리: ' . $responseMessage);
                        case 'EE':
                            throw new Exception('시스템 오류: ' . $responseMessage);
                        case 'EO':
                            throw new Exception('시스템 타임아웃: ' . $responseMessage);
                        default:
                            throw new Exception('Unknown error code: ' . $code . ' - ' . $responseMessage);
                    }
                } else {
                    throw new Exception('No response code received from the server.');
                }

                $img_path = $responseData['requestData'][0]['image']['img_url'];
                $this->sendTransaction->saveMessage(
                    $fdestine,
                    $fcallback,
                    $message,
                    $profile_key,
                    $templateKey,
                    $responseData[0]['sn'],
                    $responseData[0]['code'],
                    $responseData[0]['altCode'],
                    $responseData[0]['altMsg'],
                    $responseData[0]['altSndDtm'],
                    $responseData[0]['altRcptDtm'],
                    $group_key,
                    $member_idx,
                    $client_ip,
                    $fuserid,
                    $save_buttons,
                    $img_path,
                    $_REQUEST['msgType']
                );

                $point_sect = "smspay"; //
                $mile_title = "알림톡 발송"; // 포인트 차감 내역
                $mile_sect = "M"; // 포인트  종류 = A : 적립, P : 대기, M : 차감

                if($responseData[0]['code']=='EW'){
                    $smsType = $responseData[0]['smsKind'];
                    switch ($smsType) {
                        case 'S':
                            $mb_kko_fee=$this->data['inc_member_row']['mb_short_fee'];
                            break;
                        case 'L':
                            $mb_kko_fee=$this->data['inc_member_row']['mb_long_fee'];
                            break;
                        case 'M':
                            $mb_kko_fee=$this->data['inc_member_row']['mb_img_fee'];
                            break;
                    }
                }else{
                    $mb_kko_fee=$this->data['inc_member_row']['mb_kko_fee'];
                }
                $this->pointModel->coin_plus_minus($point_sect,$member_idx,$mile_sect,$mb_kko_fee,$mile_title,"","","");
                // 성공한 경우
                $response = [
                    'status' => 'success',
                    'fuserid' => 'FT',
                    'message' => '알림톡 전송 결과 : '
                        .$responseData[0]['altMsg']

                ];
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            $response['message'] = '알림톡 전송 실패: ' . $e->getMessage();
        }
        echo json_encode($response);
    }
}
?>
