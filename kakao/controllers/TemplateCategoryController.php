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

    public function apiRequestTemplate()
    {
        $id = $_POST['id'];
        try {
            $template = $this->templateCategory->getTemplateById($id);
            $requestUrl = 'https://wt-api.carrym.com:8445/api/v1/leahue/template/request';
            $requestMethod = 'POST';
            $requestData = [
                "senderKey" => $template['profile_key'],
                "templateCode" => $template['template_key'],
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
            // 응답 코드 505 처리
            if (isset($requestResponseData['code']) && $requestResponseData['code'] == '405') {
                throw new Exception($requestResponseData['message']);
            }
            $data['inspection_status'] = "REQ";

            $this->sendJsonResponse(['success' => true, 'message' => '검수요청 이 성공적으로 등록되었습니다.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '검수요청에 실패했습니다: ' . $e->getMessage()]);
        }
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
        $templateItemList=[];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 입력 데이터 수집
            $profile_id = $_POST['profile_id'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $template_type = $_POST['template_type'] ?? '';
            $template_name = $_POST['template_name'] ?? '';
            $template_title = $_POST['template_title'] ?? '';
            $strong_title = $_POST['strong_title'] ?? '';
            $strong_sub_title = $_POST['strong_sub_title'] ?? '';
            $template_subtitle = $_POST['template_subtitle'] ?? '';//부가정보
            $template_emphasize_type =  $_POST['template_emphasize_type'] ?? '';
            $template_strong_title = $_POST['strong_title'] ?? '';
            $template_strong_sub_title = $_POST['strong_sub_title'] ?? '';
            $securityFlag = isset($_POST['securityFlag']) ? 'true' : 'false';
            $templateHeader= $_POST['templateHeader'] ?? '';
            $templateItemHighlight_title = $_POST['itemHighlightTitle'] ?? '';
            $templateItemHighlight_description = $_POST['itemHlightDescription'] ?? '';
            $image_path = null;
            $item_list = null;
            $templateImageName = null;
            $templateImageUrl = null;
            $thumbnailImageUrl = null;
            $created_at = date('Y-m-d H:i:s');

            // 버튼 데이터가 있을 경우 처리
            $buttons = [];
            $quickReplies =[];

            if (isset($_POST['title']) && is_array($_POST['title'])) {
                foreach ($_POST['title'] as $index => $postLinkType) {
                    $itemList = [
                        'title' => $_POST['title'][$index] ?? '',
                        'description' => $_POST['description'][$index] ?? ''
                    ];

                    $templateItemList[] = $itemList;  // 버튼 추가
                }
            }
            if (isset($_POST['postLinkType']) && is_array($_POST['postLinkType'])) {
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
            if (isset($_POST['quickReplies_linkType']) && is_array($_POST['quickReplies_linkType'])) {
                foreach ($_POST['quickReplies_linkType'] as $index => $postLinkType) {
                    $quickReplie = [
                        'ordering' => $_POST['quickReplies_ordering'][$index] ,
                        'linkType' => $postLinkType,
                        'name' => $_POST['quickReplies_name'][$index] ?? ''
                    ];
                    // 버튼 종류에 따라 추가 필드를 설정
                    if (!empty($_POST['quickReplies_linkMo'][$index])) {
                        $button['linkMo'] = $_POST['quickReplies_linkMo'][$index];              // 모바일 링크
                    }
                    if (!empty($_POST['quickReplies_linkPc'][$index])) {
                        $button['linkPc'] = $_POST['quickReplies_linkPc'][$index];
                    }
                    if (!empty($_POST['quickReplies_linkAnd'][$index])) {
                        $button['linkAnd'] = $_POST['quickReplies_linkAnd'][$index];
                    }
                    if (!empty($_POST['quickReplies_linkIos'][$index])) {
                        $button['linkIos'] = $_POST['quickReplies_linkIos'][$index];
                    }

                    $quickReplies[] = $quickReplie;  // 버튼 추가
                }
            }
            // 필수 항목 검증
            if (empty($profile_id) || empty($category_id) || empty($template_type)) {
                $response['message'] = '필수 항목이 누락되었습니다.';
                echo json_encode($response);
                exit();
            }

            try {
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
                        $templateImageUrl=$image_path;
                    }
                    // 200
                    if (isset($responseImagePost['code']) && $responseImagePost['code'] == '200' && $responseImagePost['message']) {
                        throw new Exception($responseImagePost['message']);
                    }
                }
                // 하이라이트 썸내일
                if (isset($_FILES['highlightFile']) && $_FILES['highlightFile']['error'] == 0) {
                    $target_dir = $_SERVER['DOCUMENT_ROOT'].'/upload_file/kakao/';
                    $highlightThumbnailFileName = basename($_FILES["highlightFile"]["name"]);
                    $highlightThumbnailPath = $target_dir . $highlightThumbnailFileName;

                    if (!move_uploaded_file($_FILES["highlightFile"]["tmp_name"], $highlightThumbnailPath)) {
                        throw new Exception('파일 업로드에 실패했습니다.');
                    }

                    $cfile = new CURLFile($highlightThumbnailPath, $_FILES['highlightFile']['type'], $highlightThumbnailFileName);

                    $imgUploadurl = 'https://wt-api.carrym.com:8445/api/v1/leahue/image/alimtalk/itemHighlight';

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
                        $thumbnailImageUrl = $responseImagePostDecoded['image'];
                    }
                    // 200
                    if (isset($responseImagePost['code']) && $responseImagePost['code'] == '200' && $responseImagePost['message']) {
                        throw new Exception($responseImagePost['message']);
                    }
                }
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
                    "securityFlag" => $securityFlag,
                ];
                if($template_emphasize_type == "ITEM_LIST"){
                    $data['templateHeader'] = $templateHeader;
                    $data['templateItemHighlight.title'] = $templateItemHighlight_title;
                    $data['templateItemHighlight.description'] = $templateItemHighlight_description;
                    $data['templateItemHighlight.imageUrl'] = $thumbnailImageUrl;

                    if (!empty($templateItemList)) {
                        foreach ($templateItemList as $index => $item) {
                            foreach ($item as $key => $value) {
                                $data["templateItem.list[$index].$key"] = $value;
                            }
                        }
                    }
                }
                if($template_emphasize_type == "TEXT"){
                    $data['templateTitle'] = $template_strong_title;
                    $data['templateSubtitle'] = $template_strong_sub_title;
                }
                if($template_emphasize_type == "IMAGE"){
                    $data['templateImageName'] = $templateImageName;
                    $data['templateImageUrl'] = $templateImageUrl;
                }

                if($template_type == "EX"){
                    $data['templateExtra'] = $template_subtitle;
                }
                if($template_type == "MI"){
                    $data['templateExtra'] = $template_subtitle;
                }
                // 버튼이 있는 경우, API 요청에 버튼 데이터 추가
                if (!empty($buttons)) {
                    foreach ($buttons as $index => $button) {
                        foreach ($button as $key => $value) {
                            $data["buttons[$index].$key"] = $value; // API 요청 포맷에 맞게 배열을 구성
                        }
                    }
                }
                if (!empty($quickReplies)) {
                    foreach ($quickReplies as $index => $quickReplies) {
                        foreach ($quickReplie as $key => $value) {
                            $data["quickReplies[$index].$key"] = $value; // API 요청 포맷에 맞게 배열을 구성
                        }
                    }
                }
                $headers = [
                    'Content-Type: application/x-www-form-urlencoded',
                ];

                // 외부 API 호출
                $apiResponse = $this->sendCurlRequest($url, $method, $data, $headers);
                $responseData = json_decode($apiResponse, true);
                // 응답 코드 405 처리
                if (isset($responseData['code']) && $responseData['code'] == '405') {
                    throw new Exception($responseData['message']);
                }
                // 응답 코드 505 처리
                if (isset($responseData['code']) && $responseData['code'] == '505') {
                    throw new Exception($responseData['message']);
                }
                // 응답 코드 511 처리
                if (isset($responseData['code']) && $responseData['code'] == '511') {
                    throw new Exception($responseData['message']);
                }
                // 응답 코드
                if (isset($responseData['code']) && $responseData['code'] != '200') {
                    throw new Exception($responseData['message']);
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
    public function requestUpdateTemplate()
    {
        $response = ['success' => false, 'message' => ''];
        $templateItemList=[];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 입력 데이터 수집
            $profile_id = $_POST['profile_id'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $template_type = $_POST['template_type'] ?? '';
            $template_name = $_POST['template_name'] ?? '';
            $template_title = $_POST['template_title'] ?? '';
            $strong_title = $_POST['strong_title'] ?? '';
            $strong_sub_title = $_POST['strong_sub_title'] ?? '';
            $template_subtitle = $_POST['template_subtitle'] ?? '';//부가정보
            $template_emphasize_type =  $_POST['template_emphasize_type'] ?? '';
            $template_strong_title = $_POST['strong_title'] ?? '';
            $template_strong_sub_title = $_POST['strong_sub_title'] ?? '';
            $securityFlag = isset($_POST['securityFlag']) ? 'true' : 'false';
            $template_id = $_POST['template_id'] ?? '';
            $templateHeader= $_POST['templateHeader'] ?? '';
            $templateItemHighlight_title = $_POST['itemHighlightTitle'] ?? '';
            $templateItemHighlight_description = $_POST['itemHlightDescription'] ?? '';
            $image_path = null;
            $item_list = null;
            $templateImageName = $_POST['templateImageName'] ?? '';
            $templateImageUrl = $_POST['templateImageUrl'] ?? '';
            $thumbnailImageUrl = $_POST['templateItemHighlightImageUrl'] ?? '';
            $created_at = date('Y-m-d H:i:s');
            $template = $this->templateCategory->getTemplateById($template_id);
            // 버튼 데이터가 있을 경우 처리
            $buttons = [];
            $quickReplies =[];
            if (isset($_POST['title']) && is_array($_POST['title'])) {
                foreach ($_POST['title'] as $index => $postLinkType) {
                    $itemList = [
                        'title' => $_POST['title'][$index] ?? '',
                        'description' => $_POST['description'][$index] ?? ''
                    ];

                    $templateItemList[] = $itemList;  // 버튼 추가
                }
            }
            if (isset($_POST['postLinkType']) && is_array($_POST['postLinkType'])) {
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
            if (isset($_POST['quickReplies_linkType']) && is_array($_POST['quickReplies_linkType'])) {
                foreach ($_POST['quickReplies_linkType'] as $index => $postLinkType) {
                    $quickReplie = [
                        'ordering' => $_POST['quickReplies_ordering'][$index] ,
                        'linkType' => $postLinkType,
                        'name' => $_POST['quickReplies_name'][$index] ?? ''
                    ];
                    // 버튼 종류에 따라 추가 필드를 설정
                    if (!empty($_POST['quickReplies_linkMo'][$index])) {
                        $quickReplie['linkMo'] = $_POST['quickReplies_linkMo'][$index];              // 모바일 링크
                    }
                    if (!empty($_POST['quickReplies_linkPc'][$index])) {
                        $quickReplie['linkPc'] = $_POST['quickReplies_linkPc'][$index];
                    }
                    if (!empty($_POST['quickReplies_linkAnd'][$index])) {
                        $quickReplie['linkAnd'] = $_POST['quickReplies_linkAnd'][$index];
                    }
                    if (!empty($_POST['quickReplies_linkIos'][$index])) {
                        $quickReplie['linkIos'] = $_POST['quickReplies_linkIos'][$index];
                    }

                    $quickReplies[] = $quickReplie;  // 버튼 추가
                }
            }
            // 필수 항목 검증
            if (empty($profile_id) || empty($category_id) || empty($template_type)) {
                $response['message'] = '필수 항목이 누락되었습니다.';
                echo json_encode($response);
                exit();
            }

            try {
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
                        $templateImageUrl=$image_path;
                    }
                    // 200
                    if (isset($responseImagePost['code']) && $responseImagePost['code'] == '200' && $responseImagePost['message']) {
                        throw new Exception($responseImagePost['message']);
                    }
                }
                // 하이라이트 썸내일
                if (isset($_FILES['highlightFile']) && $_FILES['highlightFile']['error'] == 0) {
                    $target_dir = $_SERVER['DOCUMENT_ROOT'].'/upload_file/kakao/';
                    $highlightThumbnailFileName = basename($_FILES["highlightFile"]["name"]);
                    $highlightThumbnailPath = $target_dir . $highlightThumbnailFileName;

                    if (!move_uploaded_file($_FILES["highlightFile"]["tmp_name"], $highlightThumbnailPath)) {
                        throw new Exception('파일 업로드에 실패했습니다.');
                    }

                    $cfile = new CURLFile($highlightThumbnailPath, $_FILES['highlightFile']['type'], $highlightThumbnailFileName);

                    $imgUploadurl = 'https://wt-api.carrym.com:8445/api/v1/leahue/image/alimtalk/itemHighlight';

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
                        $thumbnailImageUrl = $responseImagePostDecoded['image'];
                    }
                    // 200
                    if (isset($responseImagePost['code']) && $responseImagePost['code'] == '200' && $responseImagePost['message']) {
                        throw new Exception($responseImagePost['message']);
                    }
                }
                // KakaoBusinessModel 인스턴스 생성 및 ISP 코드 조회
                $profile = $this->templateCategory->getIspCodeByProfileKey($profile_id);


                $template_key = $template["template_key"];
                $new_template_key = 'CPS_TML_' . date('YmdHis');
                $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/template/update';
                $method = 'POST';

                $data = [
                    "senderKey" => $profile['profile_key'],
//                    "senderKeyType" => "S",
                    "templateCode" => $template_key,
                    "newSenderKey" => $profile['profile_key'],
                    "newTemplateCode" => $new_template_key,
                    "newTemplateName" => $template_name,
                    "newTemplateMessageType" => $template_type,
                    "newTemplateEmphasizeType" => $template_emphasize_type,
                    "newCategoryCode" => $category_id,
                    "newTemplateContent" => $template_title,
                    "securityFlag" => $securityFlag,
                ];
                if($template_emphasize_type == "ITEM_LIST"){
                    $data['newTemplateHeader'] = $templateHeader;
                    $data['newTemplateItemHighlight.title'] = $templateItemHighlight_title;
                    $data['newTemplateItemHighlight.description'] = $templateItemHighlight_description;
                    $data['newTemplateItemHighlight.imageUrl'] = $thumbnailImageUrl;

                    if (!empty($templateItemList)) {
                        foreach ($templateItemList as $index => $item) {
                            foreach ($item as $key => $value) {
                                $data["newTemplateItem.list[$index].$key"] = $value;
                            }
                        }
                    }
                }
                if($template_emphasize_type == "TEXT"){
                    $data['newTemplateTitle'] = $template_strong_title;
                    $data['newTemplateSubtitle'] = $template_strong_sub_title;
                }
                if($template_emphasize_type == "IMAGE" || $template_emphasize_type == "ITEM_LIST"){
                    $data['newTemplateImageName'] = $templateImageName;
                    $data['newTemplateImageUrl'] = $templateImageUrl;
                }
                if($template_type == "EX" || $template_type == "MI" ){
                    $data['newTemplateExtra'] = $template_subtitle;
                }
                // 버튼이 있는 경우, API 요청에 버튼 데이터 추가
                if (!empty($buttons)) {
                    foreach ($buttons as $index => $button) {
                        foreach ($button as $key => $value) {
                            $data["buttons[$index].$key"] = $value; // API 요청 포맷에 맞게 배열을 구성
                        }
                    }
                }
                if (!empty($quickReplies)) {
                    foreach ($quickReplies as $index => $quickReplies) {
                        foreach ($quickReplie as $key => $value) {
                            $data["quickReplies[$index].$key"] = $value; // API 요청 포맷에 맞게 배열을 구성
                        }
                    }
                }
                $headers = [
                    'Content-Type: application/x-www-form-urlencoded',
                ];

                // 외부 API 호출
                $apiResponse = $this->sendCurlRequest($url, $method, $data, $headers);
                $responseData = json_decode($apiResponse, true);
                // 응답 코드 405 처리
                if (isset($responseData['code']) && $responseData['code'] == '405') {
                    throw new Exception($responseData['message']);
                }
                // 응답 코드 505 처리
                if (isset($responseData['code']) && $responseData['code'] == '505') {
                    throw new Exception($responseData['message']);
                }
                // 응답 코드 511 처리
                if (isset($responseData['code']) && $responseData['code'] == '511') {
                    throw new Exception($responseData['message']);
                }
                if (isset($responseData['code']) && $responseData['code'] == '508') {
                    throw new Exception($responseData['message']);
                }
                if (isset($responseData['code']) && $responseData['code'] != '200') {
                    throw new Exception($responseData['message']);
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
                    'template_key' => $new_template_key,
                    'template_emphasize_type' => $template_emphasize_type,
                ];

                if (!$this->templateCategory->updateRequestTemplate($data,$template_id)) {
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
            $template_type = isset($_GET['template_type']) ? $_GET['template_type'] : null;
            $template_emphasize_type = isset($_GET['template_emphasize_type']) ? $_GET['template_emphasize_type'] : null;
            $inspection_status = isset($_GET['inspection_status']) ? $_GET['inspection_status'] : null;
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            $template_title = isset($_GET['template_title']) ? $_GET['template_title'] : null;

            // 사용자 템플릿 가져오기
            $templates = $this->templateCategory->getUserTemplate($profile_id, $template_type, $offset, $limit, $template_emphasize_type, $inspection_status, $status, $template_title);
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
                $template=[];
                if ($responseData['code']=="200") {
                    $template['status'] = $responseData['data']['status'];
                    $template['template_title'] = $responseData['data']['templateContent'];
                    $template['inspection_status'] = $responseData['data']['inspectionStatus'];
                    $template['template_name'] = $responseData['data']['templateName'];
                    $template['template_type'] = $responseData['data']['templateMessageType'];
                    $template['strong_sub_title'] = $responseData['data']['templateTitle'];
                    $template['template_emphasize_type'] = $responseData['data']['templateEmphasizeType'];
                    $template['category_id'] = $responseData['data']['categoryCode'];
                    // modifiedAt 값이 있는 경우에만 update_at에 할당
                    if (!empty($templateData['modifiedAt'])) {
                        $template['update_at'] = $responseData['data']['modifiedAt'];
                    }
                    if (!empty($responseData['data']['comments'])) {
                        $template['inspection_comments'] = $responseData['data']['comments'][0]['content'];
                    }
                    if (!empty($templateData['templateImageUrl'])) {
                        $template['image_path'] = $responseData['data']['modifiedAt'];
                    }
                    try {
                        // 데이터베이스에서 템플릿 상태 업데이트
//                        $this->templateCategory->updateTemplate($template_id, $template['status'], $template['templateContent'],$template['inspection_status'],$template['comments'],$template['update_at']);
                        $this->templateCategory->updateTemplateByArray($template_id, $template);
                    } catch (Exception $e) {
                        // 업데이트 실패 시 예외 처리
                        error_log("Failed to update template ID $template_id: " . $e->getMessage());
                    }
                }
            }

            // 최신 상태로 다시 템플릿 데이터 가져오기
            $updatedTemplates = $this->templateCategory->getUserTemplate($profile_id, $template_type, $offset, $limit, $template_emphasize_type, $inspection_status, $status, $template_title);
            $total = $this->templateCategory->getUserTotalTemplate($profile_id, $template_type, $template_emphasize_type, $inspection_status, $status, $template_title);

            if ($total > 0) {
                // 결과 반환
                $this->sendJsonResponse(['success' => true, 'template' => $updatedTemplates, 'total' => $total]);
            } else {
                // total이 0이거나 없을 때
                $this->sendJsonResponse(['success' => false, 'message' => 'No templates found', 'total' => 0]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred']);
        }
    }
    public function getMasterUserTemplate()
    {
        try {
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $template_type = isset($_GET['template_type']) ? $_GET['template_type'] : null;
            $template_emphasize_type = isset($_GET['template_emphasize_type']) ? $_GET['template_emphasize_type'] : null;
            $inspection_status = isset($_GET['inspection_status']) ? $_GET['inspection_status'] : null;
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            $template_title = isset($_GET['template_title']) ? $_GET['template_title'] : null;

            // 사용자 템플릿 가져오기
//            $templates = $this->templateCategory->getMasterUserTemplate($profile_id=null, $template_type, $offset, $limit, $template_emphasize_type, $inspection_status, $status, $template_title);


//            foreach ($templates as &$template) {
//                $template_id = $template['id'];
//                $template_key = $template['template_key'];
//                $senderKey = $template['profile_key'];
//                // 외부 API 호출을 통해 템플릿 상태 가져오기
//                $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/template?senderKey='.$senderKey.'&templateCode='.$template_key;
//                $method = 'GET';
//
//                $headers = [
//                    'Content-Type: application/json'
//                ];
//
//                $apiResponse = $this->sendCurlRequest($url, $method, null, $headers);
//                $responseData = json_decode($apiResponse, true);
//                error_log("Executing update request: " . $responseData['code'] ."/". $responseData['data']['status'] ."/". $responseData['data']['inspectionStatus']);
//                // 외부 API 응답에서 상태 값 추출
//                $template=[];
//                if ($responseData['code']=="200") {
//                    $template['status'] = $responseData['data']['status'];
//                    $template['template_title'] = $responseData['data']['templateContent'];
//                    $template['inspection_status'] = $responseData['data']['inspectionStatus'];
//                    $template['template_name'] = $responseData['data']['templateName'];
//                    $template['template_type'] = $responseData['data']['templateMessageType'];
//                    $template['strong_sub_title'] = $responseData['data']['templateTitle'];
//                    $template['template_emphasize_type'] = $responseData['data']['templateEmphasizeType'];
//                    $template['category_id'] = $responseData['data']['categoryCode'];
//                    // modifiedAt 값이 있는 경우에만 update_at에 할당
//                    if (!empty($templateData['modifiedAt'])) {
//                        $template['update_at'] = $responseData['data']['modifiedAt'];
//                    }
//                    if (!empty($responseData['data']['comments'])) {
//                        $template['inspection_comments'] = $responseData['data']['comments'][0]['content'];
//                    }
//                    if (!empty($templateData['templateImageUrl'])) {
//                        $template['image_path'] = $responseData['data']['modifiedAt'];
//                    }
//                    try {
//                        // 데이터베이스에서 템플릿 상태 업데이트
////                        $this->templateCategory->updateTemplate($template_id, $template['status'], $template['templateContent'],$template['inspection_status'],$template['comments'],$template['update_at']);
//                        $this->templateCategory->updateTemplateByArray($template_id, $template);
//                    } catch (Exception $e) {
//                        // 업데이트 실패 시 예외 처리
//                        error_log("Failed to update template ID $template_id: " . $e->getMessage());
//                    }
//                }
//            }

            // 최신 상태로 다시 템플릿 데이터 가져오기
            $updatedTemplates = $this->templateCategory->getMasterUserTemplate($profile_id=null, $template_type, $offset, $limit, $template_emphasize_type, $inspection_status, $status, $template_title);
            $total = $this->templateCategory->getMasterUserTotalTemplate($profile_id=null, $template_type, $template_emphasize_type, $inspection_status, $status, $template_title);

            if ($total > 0) {
                // 결과 반환
                $this->sendJsonResponse(['success' => true, 'template' => $updatedTemplates, 'total' => $total]);
            } else {
                // total이 0이거나 없을 때
                $this->sendJsonResponse(['success' => false, 'message' => 'No templates found', 'total' => 0]);
            }
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
//                $profile = $this->getSender($template["profile_key"]);
                $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/template';

                $data = [
                    'senderKey' => $template["profile_key"],
                    'templateCode' => $template["template_key"]
                ];
                $headers = [
                    'Content-Type: application/json'
                ];
                // 파일 전송 요청
                $response = $this->sendCurlRequest($url, 'GET', $data, $headers);
                $responseDecoded = json_decode($response, true);
                // JSON 파일 경로 지정
                $filePath = $_SERVER["DOCUMENT_ROOT"] .'/kakao/public/kko_icon.json';
                if (file_exists($filePath)) {
                    $jsonData = file_get_contents($filePath);
                    // JSON 데이터를 PHP 배열로 디코딩
                    $iconData = json_decode($jsonData, true);
                }
                $templateContent = $responseDecoded["data"]["templateContent"];
                $updatedContent = $this->replaceIconsWithImages($templateContent, $iconData);
                $responseDecoded["data"]["convContent"] = $updatedContent;
                $template['apiRespone']=$responseDecoded['data'];
//                $template['kakao_ch_id']=$profile['data']['uuid'];
//                $template['kakao_ch_name']=$profile['data']['name'];
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
        if(isset($param['smssendyn']) && $param['smsmemo']){
            $smslength=strlen($param['smsmemo']);
            $data[0]["smsSndNum" ]= $fcallback;
            if($smslength <= 90){
                $data[0]["smsKind" ]= "S";
                $data[0]["smsMessage" ]= $param['smsmemo'];
            }else{
                $data[0]["smsKind" ]= "L";
                $data[0]["lmsMessage" ]= $param['smsmemo'];
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
                $profileKey = $_POST['profile_key'];
                $templateKey = $_POST['template_key'];
                $responseData = $this->sendAlimtalkRequest($message,$fdestine, $fcallback, $profileKey, $templateKey,$_POST);

                if (isset($responseData[0]['code'])) {
                    $code = $responseData[0]['code'];
                    $responseMessage = isset($responseData[0]['altMsg']) ? $responseData[0]['altMsg'] : 'No message provided';

                    switch ($code) {
                        case 'AS':
                            // 알림톡/친구톡 발송 성공
                            break;
                        case 'AF':
                            throw new Exception('알림톡/친구톡 발송 실패: ' . $responseMessage);
                        case 'SS':
                            // 문자 발송 성공
                            break;
                        case 'SF':
                            throw new Exception('문자 발송 실패: ' . $responseMessage);
                        case 'EW':
                            throw new Exception('문자 발송 중, 내부 처리 중: ' . $responseMessage);
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
    public function editForm()
    {
        $id = $_GET["id"] ??  '';
        $template = $this->templateCategory->getTemplateById($id);
        $url = 'https://wt-api.carrym.com:8445/api/v1/leahue/template';

        $data = [
            'senderKey' => $template["profile_key"],
            'templateCode' => $template["template_key"]
        ];
        $headers = [
            'Content-Type: application/json'
        ];
        // 파일 전송 요청
        $response = $this->sendCurlRequest($url, 'GET', $data, $headers);
        $responseDecoded = json_decode($response, true);
        // JSON 파일 경로 지정
        $filePath = $_SERVER["DOCUMENT_ROOT"] .'/kakao/public/kko_icon.json'; // 실제 JSON 파일 경로로 변경하세요.

        // JSON 파일을 읽어서 문자열로 가져오기
        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            // JSON 데이터를 PHP 배열로 디코딩
            $iconData = json_decode($jsonData, true);
        }
        $templateContent = $responseDecoded["data"]["templateContent"];
        // templateContent에서 아이콘을 이미지 태그로 치환
        $updatedContent = $this->replaceIconsWithImages($templateContent, $iconData);
        // $updatedContent 값을 $responseDecoded["data"]["convContent"]에 주입
        $responseDecoded["data"]["convContent"] = $updatedContent;
        $template["apiResponeData"]=$responseDecoded["data"];
        $this->view('templateEdit',$template);
    }
    // 텍스트에서 (아이콘명) 형식을 찾아 이미지 태그로 치환하고, 줄바꿈을 <br>로 변환하는 함수
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
    public function deleteTemplate()
    {
        $id = $_POST["id"] ??  '';
        $status = "D";
        $this->templateCategory->deleteTemplateStatus($id, $status);
        $this->sendJsonResponse(['success' => true, 'message' => '상태가 성공적으로 업데이트되었습니다.']);
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
    public function getKakaoIcon()
    {
        try {

            // JSON 파일 경로 지정
            $filePath = $_SERVER["DOCUMENT_ROOT"] .'/kakao/public/kko_icon.json'; // 실제 JSON 파일 경로로 변경하세요.

            // JSON 파일을 읽어서 문자열로 가져오기
            if (file_exists($filePath)) {
                $jsonData = file_get_contents($filePath);

                // JSON 데이터를 PHP 배열로 디코딩
                $iconData = json_decode($jsonData, true);

                // 성공적으로 JSON 파일을 불러왔을 때 응답
                $this->sendJsonResponse(['success' => true, 'data' => $iconData]);
            } else {
                // 파일이 존재하지 않을 경우
                $this->sendJsonResponse(['success' => false, 'message' => 'JSON 파일을 찾을 수 없습니다.']);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => '실패: ' . $e->getMessage()]);
        }
    }
}



?>
