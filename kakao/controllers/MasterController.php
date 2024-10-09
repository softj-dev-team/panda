<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/MasterModel.php';
class MasterController extends Controller
{
    private $MasterModel;
    public function __construct()
    {
        // 부모 클래스의 생성자를 명시적으로 호출하여 상속된 속성을 초기화
        parent::__construct();
        $this->MasterModel = new MasterModel();
    }
    public function getKakaoSendList(){
        try {
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $keyword = !empty(trim($_GET['keyword'] ?? '')) ? $_GET['keyword'] : null;
            $currentDate = new DateTime();  // 현재 날짜
            $startDate = (isset($_GET['s_date']) && trim($_GET['s_date']) !== '') ? new DateTime($_GET['s_date']) : (clone $currentDate)->modify('-1 months');
            $endDate = (isset($_GET['e_date']) && trim($_GET['e_date']) !== '') ? new DateTime($_GET['e_date']) : $currentDate;


            $data = $this->MasterModel->getKakaoSendList($offset, $limit,$keyword,$startDate,$endDate);
            $total = $this->MasterModel->getTotalKakaoSendList($keyword,$startDate,$endDate);
            $this->sendJsonResponse(['success' => true, 'data' => $data, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred'.getMessage()]);
        }
    }
    public function getKakaoSendListDetail(){
        try {
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $keyword = !empty(trim($_GET['keyword'] ?? '')) ? $_GET['keyword'] : null;
            $group_key = !empty(trim($_GET['group_key'] ?? '')) ? $_GET['group_key'] : null;
            $currentDate = new DateTime();  // 현재 날짜
            $startDate = (isset($_GET['s_date']) && trim($_GET['s_date']) !== '') ? new DateTime($_GET['s_date']) : (clone $currentDate)->modify('-1 months');
            $endDate = (isset($_GET['e_date']) && trim($_GET['e_date']) !== '') ? new DateTime($_GET['e_date']) : $currentDate;


            $data = $this->MasterModel->getKakaoSendListDetail($offset, $limit,$keyword,$startDate,$endDate,$group_key);
            $total = $this->MasterModel->getTotalKakaoSendListDetail($keyword,$startDate,$endDate,$group_key);
            $this->sendJsonResponse(['success' => true, 'data' => $data, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred'.getMessage()]);
        }
    }
}