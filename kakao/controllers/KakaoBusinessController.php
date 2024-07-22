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

    public function getUserProfiles()
    {
        session_start();
        $user_idx = isset($_SESSION['member_coinc_idx']) ? $_SESSION['member_coinc_idx'] : null;
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
            $user_idx = isset($_SESSION['member_coinc_idx']) ? $_SESSION['member_coinc_idx'] : null;
            if ($user_idx === null) {
                throw new Exception("User idx is not set in session");
            }

            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $profiles = $this->kakaoBusinessModel->getProfiles($user_idx,$offset, $limit);
            $total = $this->kakaoBusinessModel->getTotalProfiles($user_idx);

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
