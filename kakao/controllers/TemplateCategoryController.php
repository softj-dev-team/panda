<?php
///kakao/TemplateCategoryController.php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/TemplateCategoryModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/SendTransaction.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/MemberModel.php';

class TemplateCategoryController extends Controller
{
    private $memberModel;
    private $templateCategory;
    private $sendTransaction;

    public function __construct()
    {
        $this->templateCategory = new TemplateCategoryModel();
        $this->sendTransaction = new SendTransaction();
        $this->memberModel = new MemberModel();

    }
    public function updateTemplateStatus()
    {
        try {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $template_key = $_POST['template_key'];

            $this->templateCategory->updateTemplateStatus($id, $status,$template_key);
            $this->sendJsonResponse(['success' => true, 'message' => '상태가 성공적으로 업데이트되었습니다.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '상태 업데이트에 실패했습니다: ' . $e->getMessage()]);
        }
    }
    public function saveTemplate()
    {

        $response = ['success' => false, 'message' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profile_id = isset($_POST['profile_id']) ? $_POST['profile_id'] : '';
            $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
            $template_type = isset($_POST['template_type']) ? $_POST['template_type'] : '';
            $template_name = isset($_POST['template_name']) ? $_POST['template_name'] : '';
            $template_title = isset($_POST['template_title']) ? $_POST['template_title'] : '';
            $strong_title = isset($_POST['strong_title']) ? $_POST['strong_title'] : '';
            $strong_sub_title = isset($_POST['strong_sub_title']) ? $_POST['strong_sub_title'] : '';
            $template_subtitle = isset($_POST['template_subtitle']) ? $_POST['template_subtitle'] : '';
            $image_path = null;
            $item_list = null;
            $created_at = date('Y-m-d H:i:s');

            if ($profile_id === '' || $category_id === '' || $template_type === '') {
                $response['message'] = '필수 항목이 누락되었습니다.';
                echo json_encode($response);
                exit();
            }

            // 파일 업로드 처리
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $target_dir = $_SERVER['DOCUMENT_ROOT'].'/upload_file/kakao/';
                $image_path = $target_dir . basename($_FILES["file"]["name"]);

                if (!move_uploaded_file($_FILES["file"]["tmp_name"], $image_path)) {
                    $response['message'] = '파일 업로드에 실패했습니다.';
                    echo json_encode($response);
                    exit();
                }
                $image_path = "/upload_file/kakao/" . basename($_FILES["file"]["name"]);
            }

            $data = [
                'code' => uniqid('tpl_'),
                'template_name' => $template_name,
                'category_id' => $category_id,
                'template_type' => $template_type,
                'template_title' => $template_title,
                'template_subtitle' => $template_subtitle,
                'image_path' => $image_path,
                'item_list' => $item_list,
                'created_at' => $created_at,
                'strong_title' => $strong_title,
                'strong_sub_title' => $strong_sub_title,
                'profile_id' => $profile_id
            ];

            try {
                if ($this->templateCategory->saveTemplate($data)) {
                    $response['success'] = true;
                    $response['message'] = '템플릿이 성공적으로 등록되었습니다.';
                } else {
                    $response['message'] = '데이터베이스에 저장하는 중 오류가 발생했습니다.';
                }
            } catch (Exception $e) {
                $response['message'] = '데이터베이스 오류: ' . $e->getMessage();
            }

            echo json_encode($response);
        }
    }
    public function getTemplate()
    {
        try {
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $template = $this->templateCategory->getTemplate($offset, $limit);
            $total = $this->templateCategory->getTotalTemplate();
            $this->sendJsonResponse(['success' => true, 'template' => $template, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred']);
        }
    }
    public function getUserTemplate()
    {
        try {
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $profile_id = $_GET['profile_id'];
            $template_type = $_GET['template_type'];
            $template = $this->templateCategory->getUserTemplate($profile_id,$template_type,$offset, $limit);
            $total = $this->templateCategory->getUserTotalTemplate($profile_id,$template_type);
            $this->sendJsonResponse(['success' => true, 'template' => $template, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred']);
        }
    }
    public function getCategories()
    {
        try {
            $categories = $this->templateCategory->getAllCategories();
            $this->sendJsonResponse($categories);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred']);
        }
    }
    public function sendMessage()
    {
        try {
            $name = $_POST['name'];
            $date = $_POST['date'];
            $system = $_POST['system'];
            $fdestine = $_POST['fdestine'];
            $fcallback = $_POST['fcallback'];

            $this->sendTransaction->saveMessage($name, $date, $system, $fdestine, $fcallback);
            header('Location: index.php?route=send');
            exit();
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo "Failed to send message: " . $e->getMessage();
        }
    }

    public function showForm()
    {

//        $data['user'] =$this->memberModel->getMemberData($_SESSION['member_coinc_idx']);;
        $this->view('template');
    }
}


?>
