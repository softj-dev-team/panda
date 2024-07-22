<?php
// index.php

require_once 'controllers/Send.php';
require_once 'controllers/SendTranKKOController.php';
$controller = new SendTranKKOController();

//if (isset($_GET['action'])) {
//    $action = $_GET['action'];
//    $id = isset($_GET['id']) ? $_GET['id'] : null;
//
//    switch ($action) {
//        case 'createUser':
//            $controller->createUser();
//            break;
//        case 'updateUser':
//            $controller->updateUser($id);
//            break;
//        case 'deleteUser':
//            $controller->deleteUser($id);
//            break;
//        default:
//            $controller->index();
//            break;
//    }
//} else {
$controller->index();
//}
?>
