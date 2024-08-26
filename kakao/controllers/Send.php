<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';

class Send extends Controller {
    public function index() {
//        $SendTranKKOModel = new SendTranKKO();
        $data['sendlist'] =null;
        $this->view('send', $data);
    }

}
?>
