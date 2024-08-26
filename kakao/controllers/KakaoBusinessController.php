<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/KakaoBusinessModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';

class KakaoBusinessController extends Controller
{
    private $kakaoBusinessModel;

    public function __construct()
    {
        $database = new Database();
        $this->kakaoBusinessModel = new KakaoBusinessModel($database->connect());
        parent::__construct();
    }

    public function saveProfile()
    {
        try {
            session_start();
            $user_idx = isset($_SESSION['member_coinc_idx']) ? $_SESSION['member_coinc_idx'] : null;
            if ($user_idx === null) {
                throw new Exception("User idx is not set in session");
            }
            $chananel_name = $_POST['chananel_name'];
            $business_name = $_POST['business_name'];
            $registration_number = $_POST['registration_number'];
            $industry = $_POST['industry'];
            $cs_phone_number = $_POST['cs_phone_number'];
            $file_path = null;
            if (!empty($_FILES['file']['name'])) {
                $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/upload_file/kakao/";
                $file_path = $target_dir . basename($_FILES["file"]["name"]);
                move_uploaded_file($_FILES["file"]["tmp_name"], $file_path);
                $file_path = "/upload_file/kakao/" . basename($_FILES["file"]["name"]);
            }
            $this->kakaoBusinessModel->saveProfile($user_idx,$chananel_name, $business_name, $registration_number, $industry, $cs_phone_number,$file_path);
            $this->sendJsonResponse(['success' => true, 'message' => '프로필이 성공적으로 저장되었습니다.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '프로필 저장에 실패했습니다: ' . $e->getMessage()]);
        }
    }
    /**
     *getKakaoProfileCategory
     */
    public function getKakaoProfileCategory() {
        try {
            $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/category/all';
            $method = 'GET';

            $headers = [
                'Content-Type: application/json',
            ];
            $apiResponse = $this->sendCurlRequest($url, $method, null, $headers);
            $responseData = json_decode($apiResponse, true);

            if (isset($responseData['code']) && $responseData['code'] == '200') {
                $categories = $responseData['data'];
                $this->sendJsonResponse(['success' => true, 'data' => $categories,'code'=>'200']);
            } else {
                $this->sendJsonResponse(['success' => false, 'message' => 'API 요청이 실패했습니다.']);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => 'API 요청이 실패했습니다. ' . $e->getMessage()]);
        }
    }
    /**
    *토큰 요청
    */
    public function authenticationRequest()
    {
        try {

            $member_idx=$this->data['inc_member_row']['idx'];
            $chananel_name = $_POST['chananel_name'];
            $cs_phone_number = $_POST['cs_phone_number'];

            $getUserProfileByChananelName = $this->kakaoBusinessModel->getUserProfileByChananelName($member_idx, $chananel_name);

            if ($getUserProfileByChananelName !== false) {
                $this->sendJsonResponse(['success' => false, 'message' => '기존에 등록 신청 내역이 있습니다. 신청 목록의 상태를 확인해주세요']);
                return;
            }
            $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/sender/token?yellowId='.$chananel_name.'&phoneNumber='.$cs_phone_number;
            $method = 'GET';

            $headers = [
                'Content-Type: application/json',
            ];

            // 외부 API 호출
            $apiResponse = $this->sendCurlRequest($url, $method, null, $headers);
            $responseData = json_decode($apiResponse, true);

            // 응답 코드 처리
            if (isset($responseData['code']) && $responseData['code'] != '200') {
                throw new Exception($responseData['message'].$cs_phone_number);
            }

//            $this->kakaoBusinessModel->authenticationRequest($member_idx,$chananel_name,$cs_phone_number);
            $this->sendJsonResponse(['success' => true, 'message' => '성공적으로 인증요청 되었습니다. 카카오톡 비즈메시즈에서 전송한 OTP 인증번호를 인증토큰 에 입력 후 채널 연동 버튼을 눌러주세요']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '인증요청에 실패했습니다: ' . $e->getMessage()]);
        }
    }
    public function requestProfileKey()
    {
        try {

            $member_idx=$this->data['inc_member_row']['idx'];
            $chananel_name = $_POST['chananel_name'];
            $cs_phone_number = $_POST['cs_phone_number'];
            $auth_token = $_POST['auth_token'];
            $industry = $_POST['industry'];
            $getUserProfileByChananelName = $this->kakaoBusinessModel->getUserProfileByChananelName($member_idx, $chananel_name);

//            if ($getUserProfileByChananelName !== false) {
//                $this->sendJsonResponse(['success' => false, 'message' => '기존에 등록 신청 내역이 있습니다. 신청 목록의 상태를 확인해주세요']);
//                return;
//            }
            $url = 'https://wt-api.carrym.com:8445/api/v3/leahue/sender/create';
            $method = 'POST';

            $headers = [
                'Content-Type: multipart/form-data',
            ];
            $data = [
                "token" => $auth_token,
                "phoneNumber" => $cs_phone_number,
                "yellowId" => $chananel_name,
                "categoryCode" => $industry
            ];
            // 외부 API 호출
            $apiResponse = $this->sendCurlRequest($url, $method, $data, $headers);
            $responseData = json_decode($apiResponse, true);

            // 응답 코드 처리
            if (isset($responseData['code']) && $responseData['code'] != '200') {
                throw new Exception($responseData['code'].$responseData['message']);
            }
            if (isset($responseData['code']) && $responseData['code'] == '200') {
                $profileKey = $responseData['data']['senderKey'];
                $status = $responseData['data']['status'];
                $this->kakaoBusinessModel->authenticationSaveProfileKey($member_idx,$chananel_name,$cs_phone_number,$profileKey,$industry,$status);
            }

            $this->sendJsonResponse(['success' => true, 'message' => '체널 연동이 완료되었습니다.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '인증요청에 실패했습니다: ' . $e->getMessage()]);
        }
    }
    public function getUserProfiles()
    {

        $user_idx = $this->data['inc_member_row']['idx'];
        if ($user_idx === null) {
            echo json_encode(['success' => false, 'message' => 'User is not logged in']);
            return;
        }

        try {
            $profiles = $this->kakaoBusinessModel->getUserProfiles($user_idx);
            echo json_encode(['success' => true, 'data' => $profiles]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function getProfiles()
    {
        try {
            $member_idx=$this->data['inc_member_row']['idx'];

            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $profiles = $this->kakaoBusinessModel->getProfiles($member_idx,$offset, $limit);
            $total = $this->kakaoBusinessModel->getTotalProfiles($member_idx);

            $this->sendJsonResponse(['success' => true, 'profiles' => $profiles, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '프로필 가져오기에 실패했습니다: ' . $e->getMessage()]);
        }
    }
    public function getProfilesForMaster()
    {
        try {

            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $profiles = $this->kakaoBusinessModel->getProfilesForMaster($offset, $limit);
            $total = $this->kakaoBusinessModel->getTotalProfilesForMaster();

            $this->sendJsonResponse(['success' => true, 'profiles' => $profiles, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '프로필 가져오기에 실패했습니다: ' . $e->getMessage()]);
        }
    }
    public function updateStatus()
    {
        try {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $profile_key=$_POST['profile_key'];
            $this->kakaoBusinessModel->updateStatus($id, $status,$profile_key);
            $this->sendJsonResponse(['success' => true, 'message' => '상태가 성공적으로 업데이트되었습니다.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '상태 업데이트에 실패했습니다: ' . $e->getMessage()]);
        }
    }
}

?>
