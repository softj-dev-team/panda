<?php
require_once 'Database.php';
class PointModel
{
    private $conn;
    private $table = 'member_point';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }
    public function mem_current_point($member_idx,$point_sect = '') {
        $sql = "SELECT cur_mile FROM {$this->table} WHERE member_idx = :member_idx AND point_sect = :point_sect AND mile_sect != 'P' ORDER BY idx DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':member_idx', $member_idx);
        $stmt->bindParam(':point_sect', $point_sect);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['cur_mile'] : 0;
    }

    public function coin_plus_minus($point_sect, $member_idx, $mile_sect, $chg_mile, $mile_title, $order_num = '', $pay_price = '', $ad_sect = '', $board_tbname = '', $board_code = '', $board_idx = '') {
        try {
            if ($chg_mile <= 0) {
                return null;
            }

            $mile_pre = $this->mem_current_point($member_idx,$point_sect); // 현재 적립금 금액

            $cur_mile = ($mile_sect === 'A') ? $mile_pre + $chg_mile : $mile_pre - $chg_mile;
            $cur_mile = max($cur_mile, 0);

            $sql = "INSERT INTO {$this->table} 
                    (order_num, member_idx, pay_price, mile_title, mile_sect, mile_pre, chg_mile, cur_mile, point_sect, board_tbname, board_code, board_idx, ad_sect, wdate)
                    VALUES 
                    (:order_num, :member_idx, :pay_price, :mile_title, :mile_sect, :mile_pre, :chg_mile, :cur_mile, :point_sect, :board_tbname, :board_code, :board_idx, :ad_sect, NOW())";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':order_num', $order_num);
            $stmt->bindParam(':member_idx', $member_idx);
            $stmt->bindParam(':pay_price', $pay_price);
            $stmt->bindParam(':mile_title', $mile_title);
            $stmt->bindParam(':mile_sect', $mile_sect);
            $stmt->bindParam(':mile_pre', $mile_pre);
            $stmt->bindParam(':chg_mile', $chg_mile);
            $stmt->bindParam(':cur_mile', $cur_mile);
            $stmt->bindParam(':point_sect', $point_sect);
            $stmt->bindParam(':board_tbname', $board_tbname);
            $stmt->bindParam(':board_code', $board_code);
            $stmt->bindParam(':board_idx', $board_idx);
            $stmt->bindParam(':ad_sect', $ad_sect);

            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception('Failed to : ' . $e->getMessage());
        }
    }
}