<?php
ob_start(); // 출력 버퍼링 시작

require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/TemplateCategoryModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/SendTransaction.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/controllers/TemplateCategoryController.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/controllers/UserAlimTalkController.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/controllers/KakaoBusinessController.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/controllers/MasterController.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/controllers/Send.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/kakao/controllers/SendFtalkController.php';
// 라우팅 설정
$route = isset($_GET['route']) ? $_GET['route'] : '';


switch ($route) {
    case 'getAddressSendNumber':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new SendFtalkController();
            $controller->getAddressSendNumber();
        }
        break;
    case 'excelDownloadKaKao':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new UserAlimTalkController();
            $controller->excelDownloadKaKao();
        }
        break;
    case 'excelDownload':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new UserAlimTalkController();
            $controller->excelDownload();
        }
        break;
    case 'sendDetail':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new UserAlimTalkController();
            $controller->sendDetail();
        }
        break;
    case 'getKakaoIcon':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new TemplateCategoryController();
            $controller->getKakaoIcon();
        }
        break;
    case 'apiRequestTemplate':
        $controller = new TemplateCategoryController();
        $controller->apiRequestTemplate();
        break;
    case 'userAlimTalkSendList':
        $controller = new UserAlimTalkController();
        $controller->index();
        break;
    case 'getUserAlimTalkSendList':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new UserAlimTalkController();
            $controller->getUserAlimTalkSendList();
        }
        break;
    case 'getSendListDetail':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new UserAlimTalkController();
            $controller->getSendListDetail();
        }
        break;
    case 'sendMessage':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new TemplateCategoryController();
            $controller->sendMessage();
        }
        break;
    case 'sendFtalkMessage'://친구톡
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new SendFtalkController();
            $controller->sendMessage();
        }
        break;
    case 'getCategories':
        $controller = new TemplateCategoryController();
        $controller->getCategories();
        break;
    case 'template':
        $controller = new TemplateCategoryController();
        $controller->showForm();
        break;
    case 'editTemplate':
        $controller = new TemplateCategoryController();
        $controller->editForm();
        break;
    case 'deleteTemplate':
        $controller = new TemplateCategoryController();
        $controller->deleteTemplate();
        break;
    case 'templateList':
        $controller = new TemplateCategoryController();
        $controller->templateList();
        break;
    case 'saveProfile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new KakaoBusinessController();
            $controller->saveProfile();
        }
        break;
    case 'getTemplate':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new TemplateCategoryController();
            $controller->getTemplate();
        }
        break;
    case 'getKakaoSendList':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new MasterController();
            $controller->getKakaoSendList();
        }
        break;
    case 'getBlockCallNumber':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new MasterController();
            $controller->getBlockCallNumber();
        }
        break;
    case 'getKakaoSendListDetail':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new MasterController();
            $controller->getKakaoSendListDetail();
        }
        break;
    case 'getUserTemplate':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new TemplateCategoryController();
            $controller->getUserTemplate();
        }
        break;
    case 'getMasterUserTemplate':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new TemplateCategoryController();
            $controller->getMasterUserTemplate();
        }
        break;
    case 'getTemplateDetails':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new TemplateCategoryController();
            $controller->getTemplateDetails();
        }
        break;
    case 'getProfiles':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new KakaoBusinessController();
            $controller->getProfiles();
        }
        break;
    case 'getKakaoProfileCategory':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new KakaoBusinessController();
            $controller->getKakaoProfileCategory();
        }
        break;
    case 'deleteProfile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new KakaoBusinessController();
            $controller->deleteProfile();
        }
        break;
    case 'requestProfileKey':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new KakaoBusinessController();
            $controller->requestProfileKey();
        }
        break;
    case 'getProfilesForMaster':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new KakaoBusinessController();
            $controller->getProfilesForMaster();
        }
        break;
    case 'getUserProfiles':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new KakaoBusinessController();
            $controller->getUserProfiles();
        }
        break;
    case 'updateStatus':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new KakaoBusinessController();
            $controller->updateStatus();
        }
        break;
    case 'updateTemplateStatus':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new TemplateCategoryController();
            $controller->updateTemplateStatus();
        }
        break;
    case 'saveTemplate':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new TemplateCategoryController();
            $controller->saveTemplate();
        }
        break;
    case 'requestUpdateTemplate':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new TemplateCategoryController();
            $controller->requestUpdateTemplate();
        }
        break;
    case 'authenticationRequest':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new KakaoBusinessController();
            $controller->authenticationRequest();
        }
        break;
    case 'downloadSample':
        $controller = new TemplateCategoryController();
        $controller->downloadExcelSample($_GET['template_id']);
        break;
    case 'uploadTemplate':
        $controller = new TemplateCategoryController();
        $controller->uploadTemplate();
        break;
//    case 'getUploadedList':
//        $controller = new TemplateCategoryController();
//        $controller->getUploadedList();
//        break;
    case 'sendMessages':
        $controller = new TemplateCategoryController();
        $controller->sendMessages();
        break;
    case 'sendFtalk':
        $controller= new SendFtalkController();
        $controller->index();
        break;
    default:
//        redirectToMainWithAlert();
        $controller= new Send();
        $controller->index();
        break;
}
function redirectToMainWithAlert() {
    echo '<script>alert("준비중입니다."); window.location.href = "/index.php";</script>';
    exit();
}
ob_end_flush(); // 출력 버퍼링 종료 및 출력
?>
