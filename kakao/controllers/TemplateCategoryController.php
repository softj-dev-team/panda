<?php
///kakao/TemplateCategoryController.php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/TemplateCategoryModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/SendTransaction.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/MemberModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/PointModel.php';
require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class TemplateCategoryController extends Controller
{

    private $templateCategory;
    private $sendTransaction;
    private $pointModel;
    public function __construct()
    {
        // 부모 클래스의 생성자를 명시적으로 호출하여 상속된 속성을 초기화
        parent::__construct();

        $this->templateCategory = new TemplateCategoryModel();
        $this->sendTransaction = new SendTransaction();
        $this->pointModel = new PointModel();

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
            // 입력 데이터 수집
            $profile_id = $_POST['profile_id'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $template_type = $_POST['template_type'] ?? '';
            $template_name = $_POST['template_name'] ?? '';
            $template_title = $_POST['template_title'] ?? '';
            $strong_title = $_POST['strong_title'] ?? '';
            $strong_sub_title = $_POST['strong_sub_title'] ?? '';
            $template_subtitle = $_POST['template_subtitle'] ?? '';
            $template_emphasize_type =  $_POST['template_emphasize_type'] ?? '';
            $image_path = null;
            $item_list = null;
            $created_at = date('Y-m-d H:i:s');

            // 필수 항목 검증
            if (empty($profile_id) || empty($category_id) || empty($template_type)) {
                $response['message'] = '필수 항목이 누락되었습니다.';
                echo json_encode($response);
                exit();
            }

            try {
                // KakaoBusinessModel 인스턴스 생성 및 ISP 코드 조회
                $profile = $this->templateCategory->getIspCodeByProfileKey($profile_id);

                // 코드 생성 로직
                $template_key = '';

                $template_key = 'CPS_TML_' . date('YmdHis');
                $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/template/create';
                $method = 'POST';
                $data = [
                    "senderKey" => $profile['profile_key'],
//                    "senderKeyType" => "S",
                    "templateCode" => $template_key,
                    "templateName" => $template_name,
                    "templateMessageType" => $template_type,
                    "templateEmphasizeType" => $template_emphasize_type,
                    "categoryCode" => $category_id,
                    "templateContent" => $template_title,
                ];
                $headers = [
                    'Content-Type: application/x-www-form-urlencoded',
                ];

                // 외부 API 호출
                $apiResponse = $this->sendCurlRequest($url, $method, $data, $headers);
                $responseData = json_decode($apiResponse, true);
                // 응답 코드 405 처리
                if (isset($responseData['code']) && $responseData['code'] == '405') {
                    throw new Exception('카테고리코드가 존재하지않습니다. 관리자에게 문의하세요');
                }
                // 응답 코드 505 처리
                if (isset($responseData['code']) && $responseData['code'] == '505') {
                    throw new Exception($responseData['message']);
                }

                // 파일 업로드 처리
                if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                    $target_dir = $_SERVER['DOCUMENT_ROOT'].'/upload_file/kakao/';
                    $image_path = $target_dir . basename($_FILES["file"]["name"]);

                    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $image_path)) {
                        throw new Exception('파일 업로드에 실패했습니다.');
                    }

                    $image_path = "/upload_file/kakao/" . basename($_FILES["file"]["name"]);
                }

                // 데이터베이스에 저장할 데이터 준비
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
                    'profile_id' => $profile_id,
                    'template_key' => $template_key,
                    'template_emphasize_type' => $template_emphasize_type,
                ];
                // 검수 처리
                if (isset($responseData['code']) && $responseData['code'] == '200') {
                    $requestUrl = 'https://wt-api.carrym.com:8445/api/v1/leahue/template/request';
                    $requestMethod = 'POST';
                    $requestData = [
                        "senderKey" => $profile['profile_key'],
                        "templateCode" => $template_key
                    ];
                    $requestHeaders = [
                        'Content-Type: application/x-www-form-urlencoded',
                    ];

                    // 외부 API 호출
                    $apiResponse = $this->sendCurlRequest($requestUrl, $requestMethod, $requestData, $requestHeaders);
                    $requestResponseData = json_decode($apiResponse, true);
                    // 응답 코드 505 처리
                    if (isset($requestResponseData['code']) && $requestResponseData['code'] == '509') {
                        throw new Exception($requestResponseData['message']);
                    }
                    $data['inspection_status'] = "REQ";
                }
                error_log($category_id);
                // 데이터베이스에 템플릿 저장
                if (!$this->templateCategory->saveTemplate($data)) {
                    throw new Exception('데이터베이스에 저장하는 중 오류가 발생했습니다.');
                }

                $response['success'] = true;
                $response['message'] = '템플릿이 성공적으로 등록되었습니다.';
            } catch (Exception $e) {
                $response['message'] = '오류: ' . $e->getMessage();
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
//    public function getUserTemplate()
//    {
//        try {
//            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
//            $limit = 10;
//            $offset = ($page - 1) * $limit;
//            $profile_id = $_GET['profile_id'];
//            $template_type = $_GET['template_type'];
//
//            $template = $this->templateCategory->getUserTemplate($profile_id,$template_type,$offset, $limit);
//            $total = $this->templateCategory->getUserTotalTemplate($profile_id,$template_type);
//            $this->sendJsonResponse(['success' => true, 'template' => $template, 'total' => $total]);
//        } catch (Exception $e) {
//            error_log($e->getMessage());
//            $this->sendJsonResponse(['error' => 'An error occurred']);
//        }
//    }
    public function getUserTemplate()
    {
        try {
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $profile_id = $_GET['profile_id'];
            $template_type = $_GET['template_type'];
            $template_emphasize_type = $_GET['template_emphasize_type'];
            // 사용자 템플릿 가져오기
            $templates = $this->templateCategory->getUserTemplate($profile_id, $template_type, $offset, $limit,$template_emphasize_type);
            // KakaoBusinessModel 인스턴스 생성 및 ISP 코드 조회
            $profile = $this->templateCategory->getIspCodeByProfileKey($profile_id);
            // 각 템플릿에 대해 상태 업데이트
            foreach ($templates as &$template) {
                $template_id = $template['id'];
                $template_key = $template['template_key'];
                $senderKey = $profile['profile_key'];
                // 외부 API 호출을 통해 템플릿 상태 가져오기
                $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/template?senderKey='.$senderKey.'&templateCode='.$template_key;
                $method = 'GET';

                $headers = [
                    'Content-Type: application/json'
                ];

                $apiResponse = $this->sendCurlRequest($url, $method, null, $headers);
                $responseData = json_decode($apiResponse, true);
                error_log("Executing update request: " . $responseData['code'] ."/". $responseData['data']['status'] ."/". $responseData['data']['inspectionStatus']);
                // 외부 API 응답에서 상태 값 추출
                if ($responseData['code']=="200") {
                    $template['status'] = $responseData['data']['status'];
                    $template['templateContent'] = $responseData['data']['templateContent'];
                    $template['inspection_status'] = $responseData['data']['inspectionStatus'];
                    try {
                        // 데이터베이스에서 템플릿 상태 업데이트
                        $this->templateCategory->updateTemplate($template_id, $template['status'], $template['templateContent'],$template['inspection_status']);
                    } catch (Exception $e) {
                        // 업데이트 실패 시 예외 처리
                        error_log("Failed to update template ID $template_id: " . $e->getMessage());
                    }
                }
            }

            // 최신 상태로 다시 템플릿 데이터 가져오기
            $updatedTemplates = $this->templateCategory->getUserTemplate($profile_id, $template_type, $offset, $limit,$template_emphasize_type);
            $total = $this->templateCategory->getUserTotalTemplate($profile_id, $template_type);

            // 결과 반환
            $this->sendJsonResponse(['success' => true, 'template' => $updatedTemplates, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred']);
        }
    }
    public function getTemplateDetails()
    {
        try {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id > 0) {
                $template = $this->templateCategory->getTemplateById($id);
                $this->sendJsonResponse(['success' => true, 'template' => $template]);
            } else {
                throw new Exception('Invalid template ID');
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'error' => 'An error occurred']);
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
    // Alimtalk 메시지 전송 요청 메서드
    private function sendAlimtalkRequest($message,$fdestine, $fcallback, $profileKey, $templateKey,$param)
    {
        $url = 'https://wt-api.carrym.com:8443/v3/A/leahue1/messages';
        $method = 'POST';
        $data = [
            [
                "custMsgSn" => 'F46CBA8E658BAC08965FD887B767CBC1',
                    "senderKey" => $profileKey,
                    "templateCode" => $templateKey,
                    "message" => $message,
                    "phoneNum" => $fdestine,
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

        $apiResponse = $this->sendCurlRequest($url, $method, json_encode($data), $headers);
        $responseData = json_decode($apiResponse, true);


        return $responseData;

    }
    public function sendMessage()
    {
        $response = ['status' => 'error', 'message' => 'An unknown error occurred'];
        try {
            if (isset($_FILES['templateFile']) && $_FILES['templateFile']['error'] == 0) {
                $filePath = $_FILES['templateFile']['tmp_name'];
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                // Retrieve the necessary details for message composition
                $templateId = (Int)$_POST['template_id'];

                $templateDetails = $this->templateCategory->getTemplateById($templateId);
                $templateTitle = $templateDetails['template_title'];
                $profileKey = $templateDetails['profile_key'];
                $templateKey = $templateDetails['template_key'];
                $callbackNumber = $templateDetails['cs_phone_number'];

                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Skip the header row

                    $message = $templateTitle;
                    preg_match_all('/#\{(.*?)\}/', $templateTitle, $matches);

                    // Replace each placeholder with the corresponding value from the Excel row
                    foreach ($matches[1] as $i => $placeholder) {
                        $message = str_replace('#{' . $placeholder . '}', $row[$i + 1], $message);
                    }
                    // Check if the first column (phone number) starts with '10' and add '0' if necessary
                    $fdestine = (string)$row[0];
                    if (strlen($fdestine) === 10 && substr($fdestine, 0, 2) === '10') {
                        $fdestine = '0' . $fdestine;
                    }

                    // Log the phone number for debugging
                    error_log("Processed phone number: $fdestine");
                    $member_idx=$this->data['inc_member_row']['idx'];
                    $this->sendTransaction->saveMessageByList(
                        $fdestine, // fdestine
                        $callbackNumber, // fcallback
                        $message,
                        $profileKey,
                        $templateKey,
                        $member_idx
                    );
                }
                // 성공한 경우
                $response = [
                    'status' => 'success',
                    'message' => '발송 목록이 정상 등록 되었습니다. : '
                ];
            } else {
                $fdestine = $_POST['fdestine'];
                $fcallback = $_POST['fcallback'];
                $message = $_POST['message'];
                $ori_message = $_POST['ori_message'];
                $profileKey = $_POST['profile_key'];
                $templateKey = $_POST['template_key'];

                // KakaoBusinessModel 인스턴스 생성 및 ISP 코드 조회
                $profile = $this->templateCategory->getProfileByProfileKey($profileKey);


//                if ($profile['isp_code'] == "KT") {
//                    preg_match_all('/\#\{([^}]+)\}/', $ori_message, $matches);
//                    $methods = $matches[1];
//                    $variables = $_POST['variables'];
//
//                    if (count($methods) !== count($variables)) {
//                        throw new Exception('메서드의 개수와 변수의 개수가 일치하지 않습니다.');
//                    }
//
//                    $param = [];
//                    foreach ($methods as $index => $method) {
//                        $param[$method] = $variables[$index];
//                    }
//
//                    $responseData = $this->sendAlimtalkRequest($message,$fdestine, $fcallback, $profileKey, $templateKey,$param);
//                    // $responseData['data']['code']가 있을 경우 예외 처리
//                    if (isset($responseData['data']['code'])) {
//                        throw new Exception('Error Code: ' . $responseData['data']['code'] . ' - ' . $responseData['data']['message']);
//                    }
//                }
                $responseData = $this->sendAlimtalkRequest($message,$fdestine, $fcallback, $profileKey, $templateKey,null);

                if (isset($responseData[0]['code'])) {
                    $code = $responseData[0]['code'];
                    $responseMessage = isset($responseData['message']) ? $responseData['message'] : 'No message provided';

                    switch ($code) {
                        case 'AS':
                            // 알림톡/친구톡 발송 성공
                            break;
                        case 'AF':
                            throw new Exception('알림톡/친구톡 발송 실패: ' . $message);
                        case 'SS':
                            // 문자 발송 성공
                            break;
                        case 'SF':
                            throw new Exception('문자 발송 실패: ' . $message);
                        case 'EW':
                            throw new Exception('문자 발송 중, 내부 처리 중: ' . $message);
                        case 'EL':
                            throw new Exception('발송결과 조회 데이터 없음: ' . $message);
                        case 'EF':
                            throw new Exception('시스템 실패 처리: ' . $message);
                        case 'EE':
                            throw new Exception('시스템 오류: ' . $message);
                        case 'EO':
                            throw new Exception('시스템 타임아웃: ' . $message);
                        default:
                            throw new Exception('Unknown error code: ' . $code . ' - ' . $responseMessage);
                    }

                } else {
                    throw new Exception('No response code received from the server.');
                }
                $member_idx=$this->data['inc_member_row']['idx'];

                // 예를 들어, 템플릿이 성공적으로 저장되었을 경우
               $this->sendTransaction->saveMessage($fdestine, $fcallback, $message, $profileKey, $templateKey,$responseData[0]['sn'],$responseData[0]['code'],$responseData[0]['altCode'],$responseData[0]['altMsg'],$responseData[0]['altSndDtm'],$responseData[0]['altRcptDtm'],$member_idx);


                $point_sect = "smspay"; //
                $mile_title = "알림톡 발송"; // 포인트 차감 내역
                $mile_sect = "M"; // 포인트  종류 = A : 적립, P : 대기, M : 차감

                $mb_kko_fee=$this->data['inc_member_row']['mb_kko_fee'];
                $this->pointModel->coin_plus_minus($point_sect,$member_idx,$mile_sect,$mb_kko_fee,$mile_title,"","","");
                // 성공한 경우
                $response = [
                    'status' => 'success',
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

    public function showForm()
    {

//        $data['user'] =$this->memberModel->getMemberData($_SESSION['member_coinc_idx']);;
        $this->view('template');
    }
    public function templateList()
    {

        $this->view('templateList');
    }
    public function downloadExcelSample($templateId)
    {

        $templateTitle = $this->templateCategory->getTemplateTitleById($templateId);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // 문서 속성 설정
        $spreadsheet->getProperties()->setCreator("Your Name")
            ->setTitle("Sample Excel")
            ->setSubject("Sample Excel for Template")
            ->setDescription("Sample Excel file for template");

        // 데이터 추가
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet->setCellValueExplicit('A1', '수신번호', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $regex = '/#\{(.*?)\}/';
        preg_match_all($regex, $templateTitle, $matches);

        $col = 'B';
        foreach ($matches[1] as $placeholder) {
            $sheet->setCellValueExplicit($col . '1', $placeholder, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $col++;
        }

        // 워크시트 이름 변경
        $spreadsheet->getActiveSheet()->setTitle('Sample');

        // 활성 워크시트 인덱스를 첫 번째 시트로 설정
        $spreadsheet->setActiveSheetIndex(0);

        // 출력 설정 및 클라이언트 브라우저로 리다이렉트 (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="sample.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    public function uploadTemplate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['templateFile'])) {
            $fileTmpPath = $_FILES['templateFile']['tmp_name'];
            $fileName = $_FILES['templateFile']['name'];
            $fileSize = $_FILES['templateFile']['size'];
            $fileType = $_FILES['templateFile']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('xlsx', 'xls');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/upload_file/kakao/';
                $dest_path = $uploadFileDir . $fileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $this->processTemplateFile($dest_path);
                } else {
                    echo json_encode(["success" => false, "message" => "파일 업로드에 실패했습니다."]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "허용되지 않는 파일 형식입니다."]);
            }
        }
    }
    public function processTemplateFile($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        echo json_encode(["success" => true, "data" => $data]);
    }
    public function sendMessages()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $templateId = $_POST['template_id'];
            $uploadedData = $_SESSION['uploaded_data'];

            $templateTitle = $this->getTemplateTitleById($templateId);

            foreach ($uploadedData as $index => $row) {
                if ($index == 0) continue; // 첫 번째 행은 헤더이므로 건너뜁니다.

                $phoneNumber = $row[0];
                $message = $templateTitle;

                // #{변수} 치환
                for ($i = 1; $i < count($row); $i++) {
                    $placeholder = $uploadedData[0][$i]; // 헤더 행의 변수를 가져옵니다.
                    $message = str_replace('#{' . $placeholder . '}', $row[$i], $message);
                }

                // 데이터베이스에 저장
                $stmt = $this->conn->prepare("INSERT INTO TBL_SEND_TRAN_KKO (phone_number, message) VALUES (:phone_number, :message)");
                $stmt->bindParam(':phone_number', $phoneNumber);
                $stmt->bindParam(':message', $message);
                $stmt->execute();
            }

            echo json_encode(["success" => true, "message" => "메시지가 성공적으로 발송되었습니다."]);
        }
    }
}



?>
