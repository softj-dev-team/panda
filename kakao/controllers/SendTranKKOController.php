<?php
// controllers/HomeController.php

require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/SendTranKKO.php';

class SendTranKKOController extends Controller {
    public function index() {
        $SendTranKKOModel = new SendTranKKO();
        $data['sendlist'] = $SendTranKKOModel->select();
        $this->view('sendlist', $data);
    }

//    public function createUser() {
//        $userModel = new User();
//        $newUser = [
//            'name' => 'John Doe',
//            'email' => 'john.doe@example.com'
//        ];
//        $userModel->insert($newUser);
//        header('Location: ' . BASE_URL);
//    }
//
//    public function updateUser($id) {
//        $userModel = new User();
//        $updatedUser = [
//            'name' => 'Jane Doe'
//        ];
//        $where = ['id' => $id];
//        $userModel->update($updatedUser, $where);
//        header('Location: ' . BASE_URL);
//    }
//
//    public function deleteUser($id) {
//        $userModel = new User();
//        $where = ['id' => $id];
//        $userModel->delete($where);
//        header('Location: ' . BASE_URL);
//    }
}
?>
