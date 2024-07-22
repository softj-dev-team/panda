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
        $stmt = $this->conn->query('SELECT id, name, parent_id FROM template_category ORDER BY parent_id, name');
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
    public function getTemplate($offset, $limit)
    {
        $stmt = $this->conn->prepare(
            "SELECT t.*, tc.name as category_name, kb.profile_key, kb.business_name 
                     FROM template t
                     LEFT JOIN template_category tc ON t.category_id = tc.id
                     LEFT JOIN kakao_business kb ON t.profile_id = kb.id
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
    public function getUserTemplate($profile_id,$template_type,$offset, $limit)
    {
        // 쿼리 문자열에 LIMIT와 OFFSET 값을 직접 삽입
        $sql = "SELECT t.*, tc.name as category_name, kb.profile_key, kb.business_name 
            FROM template t
            LEFT JOIN template_category tc ON t.category_id = tc.id
            LEFT JOIN kakao_business kb ON t.profile_id = kb.id
            WHERE t.profile_id = :profile_id
            AND t.template_type =:template_type
            LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
        $stmt->bindParam(':template_type', $template_type,PDO::PARAM_STR);

        $stmt->execute();

        // 쿼리 출력
        error_log("Executing query: " . $sql);
        error_log("With parameters: profile_id = $profile_id, template_type = '$template_type', limit = $limit, offset = $offset");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserTotalTemplate($profile_id)
    {
        $sql = 'SELECT count(*) FROM template WHERE profile_id = :profile_id AND template_type =:template_type';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
        $stmt->bindParam(':template_type', $template_type,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function saveTemplate($data)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO template (code, template_name, category_id, template_type, template_title, template_subtitle, image_path, item_list,strong_title, strong_sub_title, created_at, profile_id)
                                          VALUES (:code, :template_name, :category_id, :template_type, :template_title, :template_subtitle, :image_path, :item_list,:strong_title, :strong_sub_title, :created_at, :profile_id)");

            $stmt->bindParam(':code', $data['code']);
            $stmt->bindParam(':template_name', $data['template_name']);
            $stmt->bindParam(':category_id', $data['category_id']);
            $stmt->bindParam(':template_type', $data['template_type']);
            $stmt->bindParam(':template_title', $data['template_title']);
            $stmt->bindParam(':template_subtitle', $data['template_subtitle']);
            $stmt->bindParam(':image_path', $data['image_path']);
            $stmt->bindParam(':item_list', $data['item_list']);
            $stmt->bindParam(':created_at', $data['created_at']);
            $stmt->bindParam(':strong_title', $data['strong_title']);
            $stmt->bindParam(':strong_sub_title', $data['strong_sub_title']);
            $stmt->bindParam(':profile_id', $data['profile_id']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Failed to save template: ' . $e->getMessage());
        }
    }
}
?>
