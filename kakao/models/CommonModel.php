<?php

require_once 'Database.php';
class CommonModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }
    public function getProfileByProfileID($profile_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT profile_key FROM kakao_business WHERE id = :id");
            $stmt->bindParam(':id', $profile_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (Exception $e) {
            throw new Exception('Failed : ' . $e->getMessage());
        }
    }
    public function getAddress($member_idx)
    {
        // 기본 SQL 쿼리
        $sql = "select *,
                    (select count(idx) from address_group_num 
                       where 1 and address_group_num.group_idx=address_group.idx) as group_cnt 
                from address_group where 1 and is_del != 'Y'";

        $sql .= " and member_idx=:member_idx";
        $sql .= " order by idx desc";
        $stmt = $this->conn->prepare($sql);
        // 기본 바인딩
        $stmt->bindParam(':member_idx', $member_idx, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAddressSendNumber($member_idx=null,$group_idx=null,$keyword=null)
    {
        try {

            $group_idx_arr = explode(",", $group_idx);
            $placeholders = [];
            foreach ($group_idx_arr as $index => $group) {
                $placeholders[] = ":group" . $index;
            }
            $group_idx_placeholders = implode(",", $placeholders);
            $query = "SELECT * FROM address_group_num WHERE member_idx = :member_idx and group_idx in($group_idx_placeholders)";
            if (!empty($keyword)) {
                $query .= " AND (receive_name LIKE :keyword OR receive_num LIKE :keyword)";
            }
            $query .= " ORDER BY idx DESC";
            $stmt = $this->conn->prepare($query);
            $params[":member_idx"] = $member_idx;
            foreach ($group_idx_arr as $index => $group) {
                $params[":group" . $index] = $group;
            }
            if (!empty($keyword)) {
                $params[":keyword"] = '%' . $keyword . '%';
            }

            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // 에러 로그 기록
            error_log("Database query error: " . $e->getMessage());
            return false; // 또는 에러 메시지 반환
        }
    }
    public function getFilteringText($filtering=null)
    {
        // 기본 SQL 쿼리
        $sql = "select filtering_text from filtering where key_name=:filtering";
        $stmt = $this->conn->prepare($sql);
        // 기본 바인딩
        $stmt->bindParam(':filtering',$filtering , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
