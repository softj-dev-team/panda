<?php
// index.php

require_once 'controllers/Send.php';
require_once 'controllers/SendTranKKOController.php';
require_once 'models/TemplateCategoryModel.php';
require_once 'controllers/TemplateCategoryController.php';

$route = isset($_GET['route']) ? $_GET['route'] : '';

switch ($route) {
    case 'template':
//        require_once 'views/template.php';
        break;
    case 'send':
        $controllerSend = new Send();
        $controllerSend->index();
        break;
    default:
//        require_once 'views/template.php';
        break;
}
?>
