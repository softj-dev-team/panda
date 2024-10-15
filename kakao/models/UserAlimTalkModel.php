<?php

require_once 'Database.php';

class UserAlimTalkModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function sendDetail($group_key)
    {
        // 기본 SQL 쿼리
        $sql = "SELECT 
                        a.*,count(1) as tot_cnt,
                        mi.mb_kko_fee,
                        SUM(CASE WHEN fetc2 IN ('AS', 'EW') THEN 1 ELSE 0 END) AS receive_cnt_suc,
                        SUM(CASE WHEN fetc2 not IN ('AS', 'EW') THEN 1 ELSE 0 END) AS receive_cnt_fail,
                        tp.id as template_id,
                        FORMAT(SUM(CASE WHEN fetc2 = 'EW' THEN 1 ELSE 0 END) * mi.mb_short_fee, 2) as use_short_point,
                        FORMAT(SUM(CASE WHEN fetc2 = 'EW' THEN 1 ELSE 0 END) * mi.mb_long_fee, 2) as use_long_point,
                        FORMAT(SUM(CASE WHEN fetc2 = 'EW' THEN 1 ELSE 0 END) * mi.mb_img_fee, 2) as use_img_point,
                        FORMAT(SUM(CASE WHEN fetc2 = 'AS' THEN 1 ELSE 0 END) * mi.mb_kko_fee, 2) as use_point
                    FROM TBL_SEND_TRAN_KKO a 
                        LEFT JOIN member_info_sendinfo mi ON mi.member_idx = a.fetc8
                        left join template tp on tp.template_key = a.ftemplatekey
                    WHERE a.fetc7 =:fetc7 group by a.fetc7";
        $stmt = $this->conn->prepare($sql);
        // 기본 바인딩
        $stmt->bindParam(':fetc7', $group_key, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function sendKkoListDetail($group_key)
    {
        // 기본 SQL 쿼리
        $sql = "SELECT a.*,(select chananel_name from kakao_business where profile_key=a.fyellowid limit 1) as chananel_name
                    FROM TBL_SEND_TRAN_KKO a 
                        LEFT JOIN member_info_sendinfo mi ON mi.member_idx = a.fetc8
                        left join template tp on tp.template_key = a.ftemplatekey                        
                    WHERE a.fetc7 =:fetc7 ";
        $stmt = $this->conn->prepare($sql);
        // 기본 바인딩
        $stmt->bindParam(':fetc7', $group_key, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getKakaoSendList($member_idx,$offset, $limit,$keyword = null,$startDate=null,$endDate=null)
    {
        // 기본 SQL 쿼리
        $sql = "SELECT a.*,count(1) as tot_cnt,(select chananel_name from kakao_business where profile_key=a.fyellowid limit 1) as chananel_name
            FROM TBL_SEND_TRAN_KKO a
            WHERE a.fetc8 = :member_idx ";

        // 키워드가 있을 경우 추가 조건
        if ($keyword !== null) {
            $sql .= " AND REPLACE(REGEXP_REPLACE(fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
        }

        // 날짜 범위가 있을 경우 추가 조건
        if ($startDate !== null) {
            $sql .= " AND DATE(finsertdate) >= :startDate";
        }
        if ($endDate !== null) {
            $sql .= " AND DATE(finsertdate) <= :endDate";
        }
        // 정렬 및 페이징 처리
        $sql .= " GROUP BY fetc7 
              ORDER BY fseq DESC
              LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);

        // 기본 바인딩
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':member_idx', $member_idx, PDO::PARAM_INT);

        // 키워드가 있을 경우 바인딩
        if ($keyword !== null) {
            $keywordParam = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);
        }
        // 날짜 바인딩 처리
        if ($startDate !== null) {
            $stmt->bindParam(':startDate', $startDate->format('Y-m-d'), PDO::PARAM_STR);
        }
        if ($endDate !== null) {
            $stmt->bindParam(':endDate', $endDate->format('Y-m-d'), PDO::PARAM_STR);
        }
        error_log($startDate->format('Y-m-d').$endDate->format('Y-m-d'));
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalKakaoSendList($member_idx, $keyword = null,$startDate=null,$endDate=null)
    {
        try {
            // 기본 SQL 쿼리
            $sql = "SELECT COUNT(*) as total 
                FROM (
                    SELECT fetc7 
                    FROM TBL_SEND_TRAN_KKO 
                    WHERE fetc8 = :member_idx ";

            if ($keyword !== null) {
                $sql .= " AND REPLACE(REGEXP_REPLACE(fdestine, '[^0-9]', ''), ' ', '') LIKE :keyword";
            }
            // 날짜 범위가 있을 경우 추가 조건
            if ($startDate !== null) {
                $sql .= " AND DATE(finsertdate) >= :startDate";
            }
            if ($endDate !== null) {
                $sql .= " AND DATE(finsertdate) <= :endDate";
            }
            // 서브쿼리 종료 및 외부 쿼리
            $sql .= " GROUP BY fetc7
                  ) AS send_total";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':member_idx', $member_idx, PDO::PARAM_INT);

            if ($keyword !== null) {
                $keywordParam = '%' . $keyword . '%';
                $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);
            }

            // 날짜 바인딩
            if ($startDate !== null) {
                $stmt->bindParam(':startDate', $startDate->format('Y-m-d'), PDO::PARAM_STR);
            }
            if ($endDate !== null) {
                $stmt->bindParam(':endDate', $endDate->format('Y-m-d'), PDO::PARAM_STR);
            }
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve total getTotalKakaoSendList: ' . $e->getMessage());
        }
    }
    public function getSendListDetailSmsSave($idx=null)
    {
        $sql=
            "SELECT  sc.save_idx,a.wdate,
                        a.module_type,
                        a.sms_title, 
                        a.sms_content,
                        a.sms_type, 
                        length(a.sms_content) sms_content_length,
                        a.send_type,
                        COUNT(sc.idx) AS receive_cnt_tot,
                        mi.mb_short_fee,
                        mi.mb_long_fee,
                        b.file_chg,
                        mi.mb_img_fee
                FROM sms_save a
                    JOIN sms_save_cell sc ON sc.save_idx = a.idx
                    LEFT JOIN board_file b ON b.board_idx = a.idx
                       and b.board_tbname = 'sms_save' AND b.board_code = 'mms'
                    LEFT JOIN member_info_sendinfo mi ON mi.member_idx = a.member_idx
                          WHERE a.idx = :idx";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idx', $idx, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSendListDetailLG($idx=null,$mb_short_fee=null,$mb_long_fee=null,$mb_img_fee=null,$tableName=null)
    {
        $sql=
            "SELECT 
            COUNT(*) AS receive_cnt_tot,
            SUM(CASE WHEN frsltstat = '06' THEN 1 ELSE 0 END) AS receive_cnt_suc,
            SUM(CASE WHEN frsltstat != '06' THEN 1 ELSE 0 END) AS receive_cnt_fail,
            (SUM(CASE WHEN frsltstat = '06' THEN 1 ELSE 0 END) * :mb_short_fee) AS success_sms_cost,
            (SUM(CASE WHEN frsltstat = '06' THEN 1 ELSE 0 END) * :mb_long_fee) AS success_lms_cost,
            (SUM(CASE WHEN frsltstat = '06' THEN 1 ELSE 0 END) * :mb_img_fee) AS success_mms_cost,
            (COUNT(*) * :mb_short_fee) as sms_cost,
            (COUNT(*) * :mb_long_fee) as lms_cost,
            (COUNT(*) * :mb_img_fee) as mms_cost
        FROM $tableName
        JOIN sms_save_cell sc ON sc.idx = $tableName.fetc1
        WHERE sc.save_idx = :idx";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idx', $idx, PDO::PARAM_INT);
        $stmt->bindParam(':mb_short_fee', $mb_short_fee, PDO::PARAM_INT);
        $stmt->bindParam(':mb_long_fee', $mb_long_fee, PDO::PARAM_INT);
        $stmt->bindParam(':mb_img_fee', $mb_img_fee, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSendListDetailJUD1($idx=null,$mb_short_fee=null,$mb_long_fee=null,$mb_img_fee=null,$tableName=null)
    {
        $sql="SELECT 
                COUNT(*) AS receive_cnt_tot,
                SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) AS receive_cnt_suc,
                SUM(CASE WHEN RSTATE != 0 THEN 1 ELSE 0 END) AS receive_cnt_fail,
                (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * :mb_short_fee) AS success_sms_cost,
                (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * :mb_long_fee) AS success_lms_cost,
                (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * :mb_img_fee) AS success_mms_cost,
                (COUNT(*) * :mb_short_fee) as sms_cost,
                (COUNT(*) * :mb_short_fee) as lms_cost,
                (COUNT(*) * :mb_img_fee) as mms_cost
            FROM SMS_BACKUP_AGENT_JUD1
            JOIN sms_save_cell sc ON sc.idx = SMS_BACKUP_AGENT_JUD1.S_ETC1
            WHERE sc.save_idx = :idx";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idx', $idx, PDO::PARAM_INT);
        $stmt->bindParam(':mb_short_fee', $mb_short_fee, PDO::PARAM_INT);
        $stmt->bindParam(':mb_long_fee', $mb_long_fee, PDO::PARAM_INT);
        $stmt->bindParam(':mb_img_fee', $mb_img_fee, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSendListDetailJUD2($idx=null,$mb_short_fee=null,$mb_long_fee=null,$mb_img_fee=null,$tableName=null)
    {
        $sql="SELECT 
                COUNT(*) AS receive_cnt_tot,
                SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) AS receive_cnt_suc,
                SUM(CASE WHEN RSTATE != 0 THEN 1 ELSE 0 END) AS receive_cnt_fail,
                (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * :mb_short_fee) AS success_sms_cost,
                (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * :mb_long_fee) AS success_lms_cost,
                (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * :mb_img_fee) AS success_mms_cost,
                (COUNT(*) * :mb_short_fee) as sms_cost,
                (COUNT(*) * :mb_short_fee) as lms_cost,
                (COUNT(*) * :mb_img_fee) as mms_cost
            FROM SMS_BACKUP_AGENT_JUD2
            JOIN sms_save_cell sc ON sc.idx = SMS_BACKUP_AGENT_JUD1.S_ETC1
            WHERE sc.save_idx = :idx";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idx', $idx, PDO::PARAM_INT);
        $stmt->bindParam(':mb_short_fee', $mb_short_fee, PDO::PARAM_INT);
        $stmt->bindParam(':mb_long_fee', $mb_long_fee, PDO::PARAM_INT);
        $stmt->bindParam(':mb_img_fee', $mb_img_fee, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSendListDetailSaveCall($group_key=null,$tablename=null,$filed=null)
    {
        $sql=
            "SELECT a.*,b.cell_send,log.fmobilecomp as isp,log.frsltstat as status,log.fsenddate as work_date
                     FROM sms_save_cell a 
                         join $tablename log on log.$filed=a.idx 
                         join sms_save b on b.idx=a.save_idx
                          where 1";

        if ($group_key !== null) {
            $sql .= " AND a.save_idx = :save_idx";
        }
        $sql .= " ORDER BY a.idx DESC";
        $stmt = $this->conn->prepare($sql);

        if ($group_key !== null) {
            $stmt->bindParam(':save_idx', $group_key, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSendListDetailSaveCallExcel($group_key=null,$tablename=null,$filed=null,$downloadSuccess=false,$statusFiled=null,$statusValue=null,$telecomFiled=null,$module_type)
    {
        $sql=
            "SELECT log.fsenddate as work_date,b.cell_send,a.cell,log.$telecomFiled as isp,log.$statusFiled as status,code.code_description
                     FROM sms_save_cell a 
                         join $tablename log on log.$filed=a.idx 
                         join report_code code on code.code=log.$statusFiled and code.code_type = :code_type
                         join sms_save b on b.idx=a.save_idx
                          where 1";

        if ($group_key !== null) {
            $sql .= " AND a.save_idx = :save_idx";
        }
        if ($downloadSuccess) {
            $sql .= " AND log.$statusFiled != :status_value";
        }else{
            $sql .= " AND log.$statusFiled = :status_value";
        }
        $sql .= " ORDER BY a.idx DESC";
        $stmt = $this->conn->prepare($sql);

        if ($group_key !== null) {
            $stmt->bindParam(':save_idx', $group_key, PDO::PARAM_STR);
        }
        $stmt->bindParam(':status_value', $statusValue, PDO::PARAM_STR);
        $stmt->bindParam(':code_type', $module_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSendListDetailSaveCallExcelKaKao($group_key=null,$downloadSuccess,$statusValue="0000")
    {
        $sql=
            "SELECT a.fsenddate,a.fsendstat,a.fcallback,a.fdestine,a.fetc3,a.fetc4
                     FROM TBL_SEND_TRAN_KKO a                          
                          where 1";

        if ($group_key !== null) {
            $sql .= " AND a.fetc7 = :group_key";
        }
        if ($downloadSuccess) {
            $sql .= " AND a.fetc3 != :status_value";
        }else{
            $sql .= " AND a.fetc3 = :status_value";
        }
        $sql .= " ORDER BY a.fseq DESC";
        $stmt = $this->conn->prepare($sql);

        if ($group_key !== null) {
            $stmt->bindParam(':group_key', $group_key, PDO::PARAM_STR);
        }
        $stmt->bindParam(':status_value', $statusValue, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalSendListDetailSaveCall($group_key=null)
    {
        $sql=
            "SELECT count(*) cnt FROM sms_save_cell a where 1";

        if ($group_key !== null) {
            $sql .= " AND a.save_idx = :save_idx";
        }

        $stmt = $this->conn->prepare($sql);

        if ($group_key !== null) {
            $stmt->bindParam(':save_idx', $group_key, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
