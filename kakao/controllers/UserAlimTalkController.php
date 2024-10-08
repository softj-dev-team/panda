<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/core/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/kakao/models/UserAlimTalkModel.php';
class UserAlimTalkController extends Controller
{
    private $UserAlimTalkModel;
    public function __construct()
    {
        // 부모 클래스의 생성자를 명시적으로 호출하여 상속된 속성을 초기화
        parent::__construct();
        $this->UserAlimTalkModel = new UserAlimTalkModel();
    }
    public function index() {

        $this->view('userSendList');
    }
    public function getUserAlimTalkSendList(){
        try {
            $member_idx=$this->data['inc_member_row']['idx'];
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
            $currentDate = new DateTime();  // 현재 날짜
            $startDate = isset($_REQUEST['s_date']) ? new DateTime($_REQUEST['s_date']) : (clone $currentDate)->modify('-1 months');
            $endDate = isset($_REQUEST['e_date']) ? new DateTime($_REQUEST['e_date']) : $currentDate;
            // 키워드에서 숫자 이외의 모든 문자를 제거
            if ($keyword !== null) {
                $keyword = preg_replace('/[^0-9]/', '', $keyword);
            }
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $data = $this->UserAlimTalkModel->getKakaoSendList($member_idx,$offset, $limit,$keyword,$startDate,$endDate);
            $total = $this->UserAlimTalkModel->getTotalKakaoSendList($member_idx,$keyword,$startDate,$endDate);
            $this->sendJsonResponse(['success' => true, 'data' => $data, 'total' => $total]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred'.getMessage()]);
        }
    }
    public function sendDetail(){
        try {
            $group_key = isset($_GET['group_key']) ? $_GET['group_key'] : null;
            if($group_key){
                $data = $this->UserAlimTalkModel->sendDetail($group_key);
            }else{
                throw new Exception('서버에서 data 를 불러오는 중 오류 발생');
            }


            $this->sendJsonResponse(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->sendJsonResponse(['error' => 'An error occurred'.getMessage()]);
        }
    }
}