<?php
// core/Controller.php

class Controller {
    private $memberModel;
    protected $data = [];

    public function __construct() {
        $this->memberModel = new MemberModel();

        require_once $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php";
        // 로그인 여부 확인
        if (empty($_SESSION['member_coinc_idx'])) {
            if(empty($_SESSION['admin_coinc_id'])){
                // 로그인되지 않은 경우 알림창과 함께 리다이렉트
                echo '<script type="text/javascript">';
                echo 'alert(" * 먼저 로그인 해주세요.");';
                echo 'window.location.href = "/";'; // 로그인 페이지로 리다이렉트
                echo '</script>';
                exit(); // 이후 코드 실행 방지
            }
        }
        // $data 배열에 초기 값을 설정
        $this->data['_P_DIR_FILE'] = $_P_DIR_FILE;
        $this->data['_P_DIR_WEB_FILE'] = $_P_DIR_WEB_FILE;
        $this->data['inc_fdata_ctype'] = $inc_fdata_ctype;
        $this->data['inc_fdata_domain'] = $inc_fdata_domain;
        $this->data['inc_fdata_shopid'] = $inc_fdata_shopid;
        $this->data['inc_fdata_shopkey'] = $inc_fdata_shopkey;
        $this->data['inc_fdata_server'] = $inc_fdata_server;
        $this->data['inc_partner_idx'] = $inc_partner_idx;
        $this->data['inc_partner_id'] = $inc_partner_id;
        $this->data['inc_fdata_url'] = $inc_fdata_url;
        $this->data['_SITE_TITLE'] = $_SITE_TITLE;
        $this->data['_SITE_ADMIN_TITLE'] = $_SITE_ADMIN_TITLE;
        $this->data['_SITE_PARTNER_TITLE'] = $_SITE_PARTNER_TITLE;
        $this->data['inc_confg_sns_kakao'] = $inc_confg_sns_kakao;
        $this->data['inc_confg_sns_teleg'] = $inc_confg_sns_teleg;
        $this->data['inc_confg_bank_name'] = $inc_confg_bank_name;
        $this->data['inc_confg_bank_num'] = $inc_confg_bank_num;
        $this->data['inc_confg_bank_owner'] = $inc_confg_bank_owner;
        $this->data['inc_confg_conf_tel_2'] = $inc_confg_conf_tel_2;
        $this->data['inc_confg_conf_time_s'] = $inc_confg_conf_time_s;
        $this->data['inc_confg_conf_time_e'] = $inc_confg_conf_time_e;
        $this->data['inc_confg_conf_time_s2'] = $inc_confg_conf_time_s2;
        $this->data['inc_confg_conf_time_e2'] = $inc_confg_conf_time_e2;
        $this->data['inc_confg_conf_fax'] = $inc_confg_conf_fax;
        $this->data['inc_confg_conf_email_1'] = $inc_confg_conf_email_1;
        $this->data['inc_confg_conf_comname'] = $inc_confg_conf_comname;
        $this->data['inc_confg_conf_comowner'] = $inc_confg_conf_comowner;
        $this->data['inc_confg_conf_manager'] = $inc_confg_conf_manager;
        $this->data['inc_confg_conf_comnum_1'] = $inc_confg_conf_comnum_1;
        $this->data['inc_confg_conf_comnum_2'] = $inc_confg_conf_comnum_2;
        $this->data['inc_confg_conf_addr'] = $inc_confg_conf_addr;
        $this->data['inc_confg_conf_tel_1'] = $inc_confg_conf_tel_1;
        $this->data['inc_confg_conf_email_2'] = $inc_confg_conf_email_2;
        $this->data['inc_confg_file_chg'] = $inc_confg_file_chg;
        $this->data['inc_sms_denie_num'] = $inc_sms_denie_num;
        $this->data['inc_pubyoil_arr'] = $inc_pubyoil_arr;
        $this->data['inc_member_row'] = $this->memberModel->getMemberData($_SESSION['member_coinc_idx']);
        require_once $_SERVER["DOCUMENT_ROOT"] . '/kakao/models/CRUD.php';
        $crud = new CRUD('board_content');

        $where = [
            'is_del' => 'N',
            'step' => '0',
            'bbs_code' => 'notice'
        ];

        $order = 'ref DESC, step ASC, depth ASC';
        $limit = '0, 3';

        $this->data['inc_notice_query'] = $crud->selectWithOrderAndLimit($where, $order, $limit);
    }
    function getClientIP() {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // X-Forwarded-For 헤더에는 여러 IP가 콤마로 구분되어 있을 수 있으므로, 첫 번째 IP를 선택함
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
    function generateUniqueNumericKey() {
        // 현재 시간(마이크로초 포함)을 숫자로 변환
        $time = microtime(true) * 10000; // 소수점 제거를 위해 10000을 곱함

        // 4자리 난수 생성
        $randomNumber = rand(1000, 9999);

        // 시간과 난수를 결합하여 숫자로만 이루어진 고유 키 반환
        return (string) $time . (string) $randomNumber;
    }
    public function view($view, $data = []) {
        $data = array_merge($this->data, $data);
        extract($this->data);
        require_once $_SERVER['DOCUMENT_ROOT']."/kakao/views/$view.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/common/footer.php";
    }
    public function sendJsonResponse($data) {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode($data);
    }
    public function sendCurlRequest($url, $method = 'GET', $data = null, $headers = []) {
        $curl = curl_init();

        // 기본 옵션 설정
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ));
        // 헤더에서 Content-Type을 확인
        $isFormUrlEncoded = false;
        foreach ($headers as $header) {
            if (stripos($header, 'Content-Type: application/x-www-form-urlencoded') !== false) {
                $isFormUrlEncoded = true;
                break;
            }
        }
        // HTTP 메서드에 따라 옵션 설정
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                if ($data) {
                    if ($isFormUrlEncoded) {
                        // x-www-form-urlencoded 형태로 전송
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                    } else {
                        // 기본 전송 (JSON 등)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    }
                }
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
                }
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
                }
                break;
            default:
                // GET or other methods
                if ($data) {
                    $url .= '?' . http_build_query($data);
                    curl_setopt($curl, CURLOPT_URL, $url);
                }
                break;
        }

        // 헤더 설정
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        // 요청 실행 및 응답 반환
        $response = curl_exec($curl);

        // 오류 처리
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new Exception('cURL Error: ' . $error_msg);
        }

        curl_close($curl);

        return $response;
    }
}
?>
