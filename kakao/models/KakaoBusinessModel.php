<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/Database.php';

class KakaoBusinessModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function saveProfile($user_idx,$chananel_name, $business_name, $registration_number, $industry, $cs_phone_number,$file_path)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO kakao_business (user_idx,chananel_name, business_name, registration_number, industry, cs_phone_number,file_path) VALUES (:user_idx,:chananel_name, :business_name, :registration_number, :industry, :cs_phone_number, :file_path)");
            $stmt->bindParam(':user_idx', $user_idx, PDO::PARAM_INT);
            $stmt->bindParam(':chananel_name', $chananel_name);
            $stmt->bindParam(':business_name', $business_name);
            $stmt->bindParam(':registration_number', $registration_number);
            $stmt->bindParam(':industry', $industry);
            $stmt->bindParam(':cs_phone_number', $cs_phone_number);
            $stmt->bindParam(':file_path', $file_path);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception('Failed to save profile: ' . $e->getMessage());
        }
    }
    public function getProfiles($user_idx,$offset, $limit)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM kakao_business where user_idx = :user_idx LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':user_idx', $user_idx, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve profiles: ' . $e->getMessage());
        }
    }

    public function getUserProfiles($user_idx)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, business_name FROM kakao_business WHERE status = '01' AND user_idx = :user_idx");
            $stmt->bindParam(':user_idx', $user_idx, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve profiles: ' . $e->getMessage());
        }
    }
    public function getProfilesForMaster($offset, $limit)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM kakao_business LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve profiles: ' . $e->getMessage());
        }
    }
    public function getTotalProfiles($user_idx)
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM kakao_business where user_idx= :user_idx");
            $stmt->bindParam(':user_idx', $user_idx, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve total profiles: ' . $e->getMessage());
        }
    }
    public function getTotalProfilesForMaster()
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM kakao_business");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve total profiles: ' . $e->getMessage());
        }
    }
    public function updateStatus($id, $status,$profile_key)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE kakao_business SET status = :status, profile_key= :profile_key WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':profile_key', $profile_key);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception('Failed to update status: ' . $e->getMessage());
        }
    }
}
?>
