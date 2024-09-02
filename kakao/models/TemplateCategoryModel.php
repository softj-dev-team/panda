<?php

require_once 'Database.php';

class TemplateCategoryModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllCategories()
    {
        $stmt = $this->conn->query('SELECT id, code,name, parent_id FROM template_category ORDER BY parent_id, name');
        return $stmt->fetchAll();
    }
    public function updateTemplateStatus($id, $status,$template_key)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE template SET status = :status,template_key=:template_key WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':template_key', $template_key);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception('Failed to update status: ' . $e->getMessage());
        }
    }
    public function updateTemplate($id, $status,$templateContent,$inspection_status)
    {
        try {
            $sql = "UPDATE template SET status = :status,template_title=:template_title,inspection_status=:inspection_status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':template_title', $templateContent);
            $stmt->bindParam(':inspection_status', $inspection_status);
            $stmt->execute();
            error_log("Executing updateTemplate query: " . $sql);
        } catch (Exception $e) {
            throw new Exception('Failed to update status: ' . $e->getMessage());
        }
    }
    public function getTemplateTitleById($templateId)
    {
        $stmt = $this->conn->prepare("SELECT template_title FROM template WHERE id = ?");
        $stmt->execute([$templateId]);
        return $stmt->fetchColumn();
    }
    public function getTemplate($offset, $limit)
    {
        $stmt = $this->conn->prepare(
            "SELECT t.*, 
                        tc.name as category_name, 
                        kb.profile_key, 
                        kb.business_name, 
                        kb.chananel_name, 
                        mi.user_id, 
                        mi.user_name
                     FROM template t
                     LEFT JOIN template_category tc ON t.category_id = tc.id
                     LEFT JOIN kakao_business kb ON t.profile_id = kb.id
                     LEFT JOIN member_info mi ON kb.user_idx = mi.idx
                     ORDER BY t.id DESC
                     LIMIT :limit OFFSET :offset"
        );
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalTemplate()
    {
        $stmt = $this->conn->query('SELECT count(*) FROM template');
        return $stmt->fetchColumn();
    }
    public function getUserTemplate($profile_id,$template_type,$offset, $limit,$template_emphasize_type)
    {
        // 쿼리 문자열에 LIMIT와 OFFSET 값을 직접 삽입
        $sql = "SELECT t.*, tc.name as category_name, kb.profile_key, kb.business_name, kb.cs_phone_number, kb.profile_key, kb.isp_code
            FROM template t
            LEFT JOIN template_category tc ON t.category_id = tc.id
            LEFT JOIN kakao_business kb ON t.profile_id = kb.id
            WHERE t.profile_id = :profile_id
            AND t.template_type =:template_type
            AND t.template_emphasize_type =:template_emphasize_type
            order by t.id desc
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
        $stmt->bindParam(':template_type', $template_type,PDO::PARAM_STR);
        $stmt->bindParam(':template_emphasize_type', $template_emphasize_type,PDO::PARAM_STR);

        $stmt->execute();

        // 쿼리 출력
        //error_log("Executing getUserTemplate query: " . $sql);
        //error_log("With parameters: profile_id = $profile_id, template_type = '$template_type', limit = $limit, offset = $offset");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserTotalTemplate($profile_id,$template_type)
    {
        $sql = 'SELECT count(*) FROM template WHERE profile_id = :profile_id AND template_type =:template_type';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
        $stmt->bindParam(':template_type', $template_type,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getTemplateById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT t.*, tc.name as category_name, kb.profile_key, kb.business_name, kb.cs_phone_number,kb.profile_key 
         FROM template t
         LEFT JOIN template_category tc ON t.category_id = tc.id
         LEFT JOIN kakao_business kb ON t.profile_id = kb.id
         WHERE t.id = :id"
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // profile_key로 isp_code 조회
    public function getIspCodeByProfileKey($profile_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT isp_code,profile_key FROM kakao_business WHERE id = :profile_id");
            $stmt->bindParam(':profile_id', $profile_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve ISP code: ' . $e->getMessage());
        }
    }
    public function getProfileByProfileKey($profile_key)
    {
        try {
            $stmt = $this->conn->prepare("SELECT isp_code FROM kakao_business WHERE profile_key = :profile_key");
            $stmt->bindParam(':profile_key', $profile_key);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (Exception $e) {
            throw new Exception('Failed : ' . $e->getMessage());
        }
    }
    public function saveTemplate($data)
    {
        try {
            // Prepare the SQL query with placeholders
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO template ($columns) VALUES ($placeholders)";

            $stmt = $this->conn->prepare($sql);

            // Bind parameters dynamically with string handling for category_id
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            // Generate the full query string with values for debugging
            $boundSql = $sql;
            foreach ($data as $key => $value) {
                $boundSql = str_replace(':' . $key, $this->conn->quote($value), $boundSql);
            }
            error_log( 'saveTemplate exqute sql : ' .$boundSql);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Failed to save template: ' . $e->getMessage());
        }
    }
}
?>
