<?php
// core/Controller.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";
class Controller {
    private $memberModel;
    private $Mail;
    protected $data = [];

    public function __construct() {
        $this->memberModel = new MemberModel();
        $this->Mail = new PHPMailer(true);
        $route = isset($_GET['route']) ? $_GET['route'] : '';
        require_once $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php";
        // 로그인 여부 확인
        if ($route !== 'findIdpass' && $route !== 'getUserMail' ) {
            if (empty($_SESSION['member_coinc_idx'])) {
                if(empty($_SESSION['admin_coinc_id'])){
                    // 로그인되지 않은 경우 알림창과 함께 리다이렉트
                    echo '<script type="text/javascript">';
                    echo 'alert(" * 먼저 로그인 해주세요.");';
                    echo 'window.location.href = "/";'; // 로그인 페이지로 리다이렉트
                    echo '</script>';
                    exit(); // 이후 코드 실행 방지
                }
            }
        }
        // $data 배열에 초기 값을 설정
        $this->data['_P_DIR_FILE'] = $_P_DIR_FILE;
        $this->data['_P_DIR_WEB_FILE'] = $_P_DIR_WEB_FILE;
        $this->data['inc_fdata_ctype'] = $inc_fdata_ctype;
        $this->data['inc_fdata_domain'] = $inc_fdata_domain;
        $this->data['inc_fdata_shopid'] = $inc_fdata_shopid;
        $this->data['inc_fdata_shopkey'] = $inc_fdata_shopkey;
        $this->data['inc_fdata_server'] = $inc_fdata_server;
        $this->data['inc_partner_idx'] = $inc_partner_idx;
        $this->data['inc_partner_id'] = $inc_partner_id;
        $this->data['inc_fdata_url'] = $inc_fdata_url;
        $this->data['_SITE_TITLE'] = $_SITE_TITLE;
        $this->data['_SITE_ADMIN_TITLE'] = $_SITE_ADMIN_TITLE;
        $this->data['_SITE_PARTNER_TITLE'] = $_SITE_PARTNER_TITLE;
        $this->data['inc_confg_sns_kakao'] = $inc_confg_sns_kakao;
        $this->data['inc_confg_sns_teleg'] = $inc_confg_sns_teleg;
        $this->data['inc_confg_bank_name'] = $inc_confg_bank_name;
        $this->data['inc_confg_bank_num'] = $inc_confg_bank_num;
        $this->data['inc_confg_bank_owner'] = $inc_confg_bank_owner;
        $this->data['inc_confg_conf_tel_2'] = $inc_confg_conf_tel_2;
        $this->data['inc_confg_conf_time_s'] = $inc_confg_conf_time_s;
        $this->data['inc_confg_conf_time_e'] = $inc_confg_conf_time_e;
        $this->data['inc_confg_conf_time_s2'] = $inc_confg_conf_time_s2;
        $this->data['inc_confg_conf_time_e2'] = $inc_confg_conf_time_e2;
        $this->data['inc_confg_conf_fax'] = $inc_confg_conf_fax;
        $this->data['inc_confg_conf_email_1'] = $inc_confg_conf_email_1;
        $this->data['inc_confg_conf_comname'] = $inc_confg_conf_comname;
        $this->data['inc_confg_conf_comowner'] = $inc_confg_conf_comowner;
        $this->data['inc_confg_conf_manager'] = $inc_confg_conf_manager;
        $this->data['inc_confg_conf_comnum_1'] = $inc_confg_conf_comnum_1;
        $this->data['inc_confg_conf_comnum_2'] = $inc_confg_conf_comnum_2;
        $this->data['inc_confg_conf_addr'] = $inc_confg_conf_addr;
        $this->data['inc_confg_conf_tel_1'] = $inc_confg_conf_tel_1;
        $this->data['inc_confg_conf_email_2'] = $inc_confg_conf_email_2;
        $this->data['inc_confg_file_chg'] = $inc_confg_file_chg;
        $this->data['inc_sms_denie_num'] = $inc_sms_denie_num;
        $this->data['inc_pubyoil_arr'] = $inc_pubyoil_arr;
        $this->data['inc_member_row'] = $this->memberModel->getMemberData($_SESSION['member_coinc_idx']);


//        if($this->data['inc_member_row']['member_gubun']!='4'){
//
//            echo '<script type="text/javascript">';
//            echo 'alert(" * 알림톡 기능은 사업자 회원만 이용 가능합니다..");';
//            echo 'window.location.href = "/";'; // 로그인 페이지로 리다이렉트
//            echo '</script>';
//            exit(); // 이후 코드 실행 방지
//        }


        require_once $_SERVER["DOCUMENT_ROOT"] . '/kakao/models/CRUD.php';
        $crud = new CRUD('board_content');

        $where = [
            'is_del' => 'N',
            'step' => '0',
            'bbs_code' => 'notice'
        ];

        $order = 'ref DESC, step ASC, depth ASC';
        $limit = '0, 3';

        $this->data['inc_notice_query'] = $crud->selectWithOrderAndLimit($where, $order, $limit);
    }
    function getClientIP() {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // X-Forwarded-For 헤더에는 여러 IP가 콤마로 구분되어 있을 수 있으므로, 첫 번째 IP를 선택함
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
    function generateUniqueNumericKey() {
        // 현재 시간(마이크로초 포함)을 숫자로 변환
        $time = microtime(true) * 10000; // 소수점 제거를 위해 10000을 곱함

        // 4자리 난수 생성
        $randomNumber = rand(1000, 9999);

        // 시간과 난수를 결합하여 숫자로만 이루어진 고유 키 반환
        return (string) $time . (string) $randomNumber;
    }
    public function view($view, $data = []) {
        $data = array_merge($this->data, $data);
        extract($this->data);
        require_once $_SERVER['DOCUMENT_ROOT']."/kakao/views/$view.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/common/footer.php";
    }
    public function sendJsonResponse($data) {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode($data);
    }
    protected function sendAlimtalkRequest($message, $fdestine, $fcallback, $profileKey, $templateKey, $param)
    {

        $template = $this->getTemplateForAPI($profileKey,$templateKey);

        $url = 'https://wt-api.carrym.com:8443/v3/A/leahue1/messages';
        $method = 'POST';
        $data = [
            [
                "custMsgSn" => 'F46CBA8E658BAC08965FD887B767CBC1',
                "senderKey" => $profileKey,
                "templateCode" => $templateKey,
                "message" => $message,
                "phoneNum" => $fdestine

                //            "receiveList" =>[
                //                [
                //                    "receiveNum" => $fdestine,
                //                    "param" => $param
                //                ]
                //            ]
            ]
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer F46CBA8E658BAC08965FD887B767CBC1',
        ];
        if($template['data']['templateEmphasizeType']=="IMAGE"){
            $data[0]['msgType']= 'AI';
        }
        if(isset($template['data']['templateTitle'])){
            $data[0]['title']= $template['data']['templateTitle'];
        }
        if(isset($template['data']['templateHeader'])){
            $data[0]['header']= $template['data']['templateHeader'];
        }
        if(isset($template['data']) && $template['data']['templateEmphasizeType']=='ITEM_LIST'){

            if(isset($template['data']['templateItemHighlight'])){
                if (isset($template['data']['templateItemHighlight']['title'])) {
                    $data[0]['itemHighlight']['title'] = $template['data']['templateItemHighlight']['title'];
                }
                if (isset($template['data']['templateItemHighlight']['title'])) {
                    $data[0]['itemHighlight']['description'] = $template['data']['templateItemHighlight']['description'];
                }
            }
            if(isset($template['data']['templateItem']["list"])){
                $ltemList = [];
                foreach ($template["data"]["templateItem"]["list"] as $templateItemList) {

                    if (isset($templateItemList['title'])) {
                        $ltemList['title'] = $templateItemList['title'];
                    }
                    if (isset($templateItemList['description'])) {
                        $ltemList['description'] = $templateItemList['description'];
                    }
                    $data[0]['item']['list'][] = $ltemList;
                }
            }
            if(isset($template['data']['templateItem']["summary"])){
                $summaryList = [];
                foreach ($template["data"]["templateItem"]["summary"] as $templateItemSummary) {

                    if (isset($templateItemSummary['title'])) {
                        $summaryList['title'] = $templateItemSummary['title'];
                    }
                    if (isset($templateItemSummary['description'])) {
                        $summaryList['description'] = $templateItemSummary['description'];
                    }
                    $data[0]['item']['summary'][] = $summaryList;
                }
            }
        }

        if(isset($param['smssendyn'])){
            $smslength=strlen($param['smsmemo']);
            if($smslength <=0){
                $data[0]["smsMessage" ] = $message;
            }else{
                $data[0]["smsMessage" ]= $param['smsmemo'];
            }
            $data[0]["smsSndNum" ]= $fcallback;
            if($smslength <= 90){
                $data[0]["smsKind" ]= "S";
            }else{
                $data[0]["smsKind" ]= "L";
                $data[0]["subject" ]= $param['subject'];
            }
        }
        // 버튼 데이터 추가
        if(isset($template["data"]["buttons"])){
            foreach ($template["data"]["buttons"] as $index => $button) {
                // 각 버튼에 대해 처리할 버튼 데이터 배열 생성
                $buttonData = [];

                // 버튼 이름 주입
                if (isset($button['name'])) {
                    $buttonData['name'] = $button['name'];
                }

                // linkType에 따른 type 주입
                if (isset($button['linkType'])) {
                    $buttonData['type'] = $button['linkType'];
                }

                // 모바일 URL 주입
                if (isset($button['linkMo'])) {
                    $buttonData['url_mobile'] = $button['linkMo'];
                }
                // 모바일 URL 주입
                if (isset($button['linkPc'])) {
                    $buttonData['url_pc'] = $button['linkPc'];
                }
                // iOS 스킴 주입
                if (isset($button['linkIos'])) {
                    $buttonData['scheme_ios'] = $button['linkIos'];
                }

                // Android 스킴 주입
                if (isset($button['linkAnd'])) {
                    $buttonData['scheme_android'] = $button['linkAnd'];
                }

                // 버튼 데이터를 $data 배열의 첫 번째 요소의 'button' 항목에 추가
                $data[0]['button'][] = $buttonData;
            }
        }
        if(isset($template["data"]["quickReplies"])){
            foreach ($template["data"]["quickReplies"] as $index => $puickRepliesData) {
                // 각 버튼에 대해 처리할 버튼 데이터 배열 생성
                $puickRepliesData = [];

                // 버튼 이름 주입
                if (isset($puickRepliesData['name'])) {
                    $puickRepliesData['name'] = $puickRepliesData['name'];
                }

                // linkType에 따른 type 주입
                if (isset($puickRepliesData['linkType'])) {
                    $puickRepliesData['type'] = $puickRepliesData['linkType'];
                }

                // 모바일 URL 주입
                if (isset($puickRepliesData['linkMo'])) {
                    $puickRepliesData['url_mobile'] = $puickRepliesData['linkMo'];
                }
                // 모바일 URL 주입
                if (isset($puickRepliesData['linkPc'])) {
                    $puickRepliesData['url_pc'] = $puickRepliesData['linkPc'];
                }
                // iOS 스킴 주입
                if (isset($puickRepliesData['linkIos'])) {
                    $puickRepliesData['scheme_ios'] = $puickRepliesData['linkIos'];
                }

                // Android 스킴 주입
                if (isset($puickRepliesData['linkAnd'])) {
                    $puickRepliesData['scheme_android'] = $puickRepliesData['linkAnd'];
                }

                // 버튼 데이터를 $data 배열의 첫 번째 요소의 'button' 항목에 추가
                $data[0]['quickReply'][] = $puickRepliesData;
            }
        }

        $apiResponse = $this->sendCurlRequest($url, $method, json_encode($data), $headers);
        $responseData = json_decode($apiResponse, true);


        return $responseData;

    }
    protected function sendFriendTalkRequest($message, $fdestine, $fcallback, $profileKey, $custMsgSn, $param)
    {
        $adFlag = isset($param['adFlag'])??'';
        $adFlag?$adFlag='Y':$adFlag='N';
        $param['msgType']=='FW'?$wide='Y':$wide='N';

        $url = 'https://wt-api.carrym.com:8443/v3/C/leahue1/messages';
        $method = 'POST';
        $data = [
            [
                "custMsgSn" => 'F46CBA8E658BAC08965FD887B767CBC1',
                "senderKey" => $profileKey,
                "message" => $message,
                "phoneNum" => $fdestine,
                "msgType" => $param['msgType'],
                "adFlag" => $adFlag,
                "wide" => $wide,
            ]
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer F46CBA8E658BAC08965FD887B767CBC1',
        ];
        $_POST['image_path']? $data[0]["image"]["img_url"]=$_POST['image_path']:'';

        if(isset($param['smssendyn'])){
            $smslength=strlen($param['smsmemo']);
            if($smslength <=0){
                $data[0]["smsMessage" ] = $message;
            }else{
                $data[0]["smsMessage" ]= $param['smsmemo'];
            }
            $data[0]["smsSndNum" ]= $fcallback;
            if($smslength <= 90){
                $data[0]["smsKind" ]= "S";
            }else{
                $data[0]["smsKind" ]= "L";
                $data[0]["subject" ]= $param['subject'];
            }
        }
        if (!empty($_POST['buttons'])) {
            foreach ($_POST['buttons'] as $button) {
                // 각 버튼에 대해 처리할 버튼 데이터 배열 생성
                $buttonData = [];

                // 버튼 이름 주입
                if (isset($button['name'])) {
                    $buttonData['name'] = $button['name'];
                }

                // linkType에 따른 type 주입
                if (isset($button['linkType'])) {
                    $buttonData['type'] = $button['linkType'];
                }

                // 모바일 URL 주입
                if (isset($button['linkMo'])) {
                    $buttonData['url_mobile'] = $button['linkMo'];
                }
                // 모바일 URL 주입
                if (isset($button['linkPc'])) {
                    $buttonData['url_pc'] = $button['linkPc'];
                }
                // iOS 스킴 주입
                if (isset($button['linkIos'])) {
                    $buttonData['scheme_ios'] = $button['linkIos'];
                }

                // Android 스킴 주입
                if (isset($button['linkAnd'])) {
                    $buttonData['scheme_android'] = $button['linkAnd'];
                }

                // 버튼 데이터를 $data 배열의 첫 번째 요소의 'button' 항목에 추가
                $data[0]['button'][] = $buttonData;
            }
        }


        $apiResponse = $this->sendCurlRequest($url, $method, json_encode($data), $headers);
        $responseData = json_decode($apiResponse, true);
        $responseData['requestData']=$data;

        return $responseData;

    }
    public function getTemplateForAPI($profile_key=null,$template_key=null)
    {
        $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/template';

        $data = [
            'senderKey' => $profile_key,
            'templateCode' => $template_key
        ];
        $headers = [
            'Content-Type: application/json'
        ];
        // 파일 전송 요청
        $response = $this->sendCurlRequest($url, 'GET', $data, $headers);
        $responseDecoded = json_decode($response, true);
        return $responseDecoded;

    }
    public function sendCurlRequest($url, $method = 'GET', $data = null, $headers = []) {
        $curl = curl_init();

        // 기본 옵션 설정
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ));
        // 헤더에서 Content-Type을 확인
        $isFormUrlEncoded = false;
        foreach ($headers as $header) {
            if (stripos($header, 'Content-Type: application/x-www-form-urlencoded') !== false) {
                $isFormUrlEncoded = true;
                break;
            }
        }
        // HTTP 메서드에 따라 옵션 설정
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                if ($data) {
                    if ($isFormUrlEncoded) {
                        // x-www-form-urlencoded 형태로 전송
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                    } else {
                        // 기본 전송 (JSON 등)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    }
                }
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
                }
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
                }
                break;
            default:
                // GET or other methods
                if ($data) {
                    $url .= '?' . http_build_query($data);
                }
                curl_setopt($curl, CURLOPT_URL, $url);
                break;
        }

        // 헤더 설정
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        // 요청 실행 및 응답 반환
        $response = curl_exec($curl);

        // 오류 처리
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new Exception('cURL Error: ' . $error_msg);
        }

        curl_close($curl);

        return $response;
    }
    function replaceIconsWithImages($content, $iconData) {
        // 먼저, 정규식을 사용하여 (아이콘명) 패턴을 찾아 치환
        $contentWithIcons = preg_replace_callback('/\(([^)]+)\)/', function($matches) use ($iconData) {
            $iconName = $matches[1]; // (아이콘명)에서 아이콘명을 추출

            // 아이콘 데이터에서 해당 아이콘명을 찾아서 이미지 태그로 변환
            foreach ($iconData as $icon) {
                if ($icon['name'] === $iconName) {
                    // 이미지 태그로 변환하여 반환
                    return '<img class="view-icon" src="' . $icon['image'] . '" alt="' . $iconName . '">';
                }
            }

            // 해당 아이콘이 없는 경우, 원래 텍스트 반환
            return $matches[0];
        }, $content);

        // 줄바꿈 (\n)을 <br> 태그로 변환하여 반환
        return nl2br($contentWithIcons);
    }
    public function generateRandomString($length = 12) {
        $upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // 대문자
        $lowerCase = 'abcdefghijklmnopqrstuvwxyz'; // 소문자
        $numbers = '0123456789'; // 숫자
        $specialChars = '!@#$%^&*()_+{}[]|:;<>?,./'; // 특수문자

        // 모든 문자들을 하나로 합치기
        $allCharacters = $upperCase . $lowerCase . $numbers;

        // 각 카테고리에서 최소 하나씩 문자를 뽑아 추가 (대문자, 소문자, 숫자, 특수문자)
        $randomString = '';
        $randomString .= $upperCase[array_rand(str_split($upperCase))];
        $randomString .= $lowerCase[array_rand(str_split($lowerCase))];
        $randomString .= $numbers[array_rand(str_split($numbers))];
//        $randomString .= $specialChars[array_rand(str_split($specialChars))];

        // 나머지 자리는 모든 문자 집합에서 무작위로 선택
        for ($i = 4; $i < $length; $i++) {
            $randomString .= $allCharacters[array_rand(str_split($allCharacters))];
        }

        // 문자열을 섞어서 랜덤성을 더욱 높임
        return str_shuffle($randomString);
    }
    function sendMail($passStr)
    {
        try {

            $email = $_REQUEST['email'] ?? '';

            if (empty($email)) {
                throw new Exception('Email is required');
            }

            //Server settings
//            $this->Mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $this->Mail->SCharSet = PHPMailer::CHARSET_UTF8; //안쓰면 한글깨짐
            $this->Mail->isSMTP();                                            //Send using SMTP
            $this->Mail->SMTPSecure  = 'ssl';
            $this->Mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $this->Mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->Mail->Username   = 'ewha.softj@gmail.com';                     //SMTP username
            $this->Mail->Password   = 'secret';                               //SMTP password
            $this->Mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $this->Mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $this->Mail->Mailer     = 'smtp';
            $this->Mail->Password   = 'lktyadlcpbeclmtb';
            //Recipients
            $this->Mail->setFrom($email, 'Mailer');
            $this->Mail->addAddress('dev.softj@gmail.com', 'Joe User');     //Add a recipient

//            $this->Mail->addAddress('ellen@example.com');               //Name is optional
//            $this->Mail->addReplyTo('info@example.com', 'Information');
//            $this->Mail->addCC('cc@example.com');
//            $this->Mail->addBCC('bcc@example.com');

            //Attachments
//            $this->Mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//            $this->Mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
//            $this->Mail->isHTML(true);                                  //Set email format to HTML
            $this->Mail->Subject = '판다문자 임시 비밀번호 /';
            $this->Mail->Body    = '임시비밀번호 : '.$passStr;
            $this->Mail->AltBody = '변경 된 임시비밀번호 를 사용하여 로그인 해주세요.';

            // Send email and handle response
            return $this->Mail->send();
        } catch (Exception $e) {
            error_log($this->Mail->ErrorInfo);
        }
    }
    public function findIdpass() {
//        $SendTranKKOModel = new SendTranKKO();
        $data[] =null;
        $this->view('findid', $data);
    }
    public function getUserMail() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $email = $_REQUEST['email']??'';
            $data = $this->memberModel->getUserMail($email);
            // 데이터 유효성 검증
            if ($data === null || empty($data)) {
                // 데이터가 없거나 유효하지 않은 경우
                $this->sendJsonResponse(['success' => false, 'message' => '입력한 이메일 은 회원가입 정보를 찾을 수 없습니다.']);
            } else {
                $passStr = $this->generateRandomString();
                $passMd5 = md5($passStr);
                $this->memberModel->updateUserPasswd($email,$passMd5);
                // 유효한 데이터를 반환하는 경우
                if($this->sendMail($passStr)){
                    $this->sendJsonResponse(['success' => true, 'message' => '입력한 이메일 로 임시비밀번호를 전송했습니다.']);
                }else{
                    $this->sendJsonResponse(['success' => false, 'message' => '이메일 전송 실패']);
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred'.getMessage()]);
        }
    }
}
?>
