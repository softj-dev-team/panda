<?php
// models/DatabaseAsis.php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/config/configAsis.php';
class DatabaseAsis {
    private $host = DB_HOST_ASIS;
    private $db_name = DB_NAME_ASIS;
    private $username = DB_USER_ASIS;
    private $password = DB_PASS_ASIS;
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
