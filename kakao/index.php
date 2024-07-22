<?php
ob_start(); // 출력 버퍼링 시작
require_once $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
require_once $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/TemplateCategoryModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/SendTransaction.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/controllers/TemplateCategoryController.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/controllers/KakaoBusinessController.php';
require_once 'controllers/Send.php';
// 라우팅 설정
$route = isset($_GET['route']) ? $_GET['route'] : '';


switch ($route) {
    case 'sendMessage':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new TemplateCategoryController();
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
    case 'getUserTemplate':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new TemplateCategoryController();
            $controller->getUserTemplate();
        }
        break;
    case 'getProfiles':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller = new KakaoBusinessController();
            $controller->getProfiles();
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
