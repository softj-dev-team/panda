<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/Database.php';

class MemberModel {
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->connect();
        } catch (PDOException $e) {
            echo 'Database Connection Error: ' . $e->getMessage();
            exit;
        }
    }

    public function getMemberData($member_idx) {
        try {
            $sql = "SELECT *,
                    (SELECT logdate FROM mem_login_count WHERE member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS last_login,
                    (SELECT cur_mile FROM member_point WHERE point_sect = 'smspay' AND mile_sect != 'P' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS current_point,
                    (SELECT com_name FROM member_info_company WHERE is_del = 'N' AND idx = a.partner_idx ORDER BY idx DESC LIMIT 1) AS com_name,
                    (SELECT mb_short_fee FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS mb_short_fee,
                    (SELECT mb_long_fee FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS mb_long_fee,
                    (SELECT mb_img_fee FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS mb_img_fee,
                    (SELECT mb_kko_fee FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS mb_kko_fee,
                    (SELECT mb_short_cnt FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS mb_short_cnt,
                    (SELECT mb_long_cnt FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS mb_long_cnt,
                    (SELECT mb_img_cnt FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS mb_img_cnt,
                    (SELECT call_num FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS call_num,
                    (SELECT call_memo FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS call_memo,
                    (SELECT use_yn FROM member_info_sendinfo WHERE is_del = 'N' AND member_idx = a.idx ORDER BY idx DESC LIMIT 1) AS use_yn
                    FROM member_info a
                    WHERE idx = :member_idx AND del_yn = 'N'";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':member_idx', $member_idx, PDO::PARAM_INT);

            // 쿼리 로그 출력
            //$this->logQuery($sql, [':member_idx' => $member_idx]);

            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo 'Query Error: ' . $e->getMessage();
            return false;
        }
    }

    private function logQuery($sql, $params) {
        $query = $sql;
        foreach ($params as $key => $value) {
            $query = str_replace($key, $this->conn->quote($value), $query);
        }
        error_log('Executing query: ' . $query);
    }
}
?>
