<?php
// models/User.php

require_once 'CRUD.php';

class SendTranKKO extends CRUD {
    public function __construct() {
        parent::__construct('TBL_SEND_KKO_LOG_202407');
    }
}
?>
