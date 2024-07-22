<?php
// models/User.php

require_once 'CRUD.php';

class User extends CRUD {
    public function __construct() {
        parent::__construct('TBL_SEND_TRAN_KKO');
    }
}
?>
