<?php
// core/Controller.php

class Controller {
    public function view($view, $data = []) {
        require_once $_SERVER['DOCUMENT_ROOT']."/kakao/views/$view.php";
    }
    public function sendJsonResponse($data) {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode($data);
    }
}
?>
