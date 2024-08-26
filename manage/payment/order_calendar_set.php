<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$order_num =  trim(sqlfilter($_REQUEST['order_num']));

	$sql = "SELECT * FROM lecture_regist where 1=1 and order_num = '".$order_num."' ";
	//echo $detail_sql."<br>";
	$query = mysqli_query($gconnet,$sql);
	$row = mysqli_fetch_array($query);

	//echo "start = ".$row['s_date']."<br>";
	if($row['curri_level'] == "c0002"){
		$curri_period = 3;
	} else {
		$curri_period = $row['curri_period'];
	}

	$total_week = $curri_period*4;
	$calcu_date =  date("Y-m-d", strtotime("+".$total_week." week", strtotime($row['s_date'])));
	//echo "end = ".$calcu_date."<br>"; 
	$total_day = $total_week*7;

	$yoil = array("일","월","화","수","목","금","토");

	$member_idx = $row['member_idx'];
	$m_time_s = $row['m_time_s'];
	$admin_idx = $_SESSION['manage_coinc_idx'];
	$admin_name = $_SESSION['manage_coinc_name'];

	$calendar_in_cnt = 0;
 
	for($i=0; $i<$total_day; $i++){ // 기간동안 루프 시작
		$m_date = date("Y-m-d", strtotime("+".$i." day", strtotime($row['s_date'])));
		$week_str = $yoil[date('w', strtotime($m_date))];
		
		/*$time_cal = $row['curri_orgin_time'] - $row['m_time_s'];
		$time_cal = str_replace("-","",$time_cal);
		if($time_cal == "11"){
			$p_date = date("Y-m-d", strtotime("+1 day", strtotime($m_date)));
		} else {
			$p_date = $m_date;
		}*/

		if(time_date_calc($row['curri_local_idx'],$row['m_time_s']) == "Y"){
			$p_date = date("Y-m-d", strtotime("+1 day", strtotime($m_date)));
		} else {
			$p_date = $m_date;
		}

		//echo "일자 ".$i." = ".$m_date." (".$week_str.") || ".$p_date."<br>";

		if($row['curri_level'] == "c0002"){ // travel course 일때 시작  
			if ($calendar_in_cnt < 21){ // 21번 까지만 시작  
				if($row['week_type'] == "2"){ // 주 2 회 : 화, 목
					if($week_str == "화" || $week_str == "목"){
						$set_query = " insert into lecture_calendar set "; 
						$set_query .= " member_idx = '".$member_idx."', ";
						$set_query .= " order_num = '".$order_num."', ";
						$set_query .= " partner_idx = '".$partner_idx."', ";
						$set_query .= " p_date = '".$p_date."', ";
						$set_query .= " m_date = '".$m_date."', ";
						$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
						$set_query .= " m_time_s = '".$m_time_s."', ";
						$set_query .= " admin_idx = '".$admin_idx."', ";
						$set_query .= " admin_name = '".$admin_name."', ";
						$set_query .= " admin_wdate = now() ";
						$set_result = mysqli_query($gconnet,$set_query);
						$calendar_in_cnt = $calendar_in_cnt+1;
						$in_date = $m_date;
						$in_week_str = $yoil[date('w', strtotime($in_date))];
						// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} elseif($row['week_type'] == "3"){ // 주 3 회 : 월, 수, 금
				if($week_str == "월" || $week_str == "수" || $week_str == "금"){
					$set_query = " insert into lecture_calendar set "; 
					$set_query .= " member_idx = '".$member_idx."', ";
					$set_query .= " order_num = '".$order_num."', ";
					$set_query .= " partner_idx = '".$partner_idx."', ";
					$set_query .= " p_date = '".$p_date."', ";
					$set_query .= " m_date = '".$m_date."', ";
					$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
					$set_query .= " m_time_s = '".$m_time_s."', ";
					$set_query .= " admin_idx = '".$admin_idx."', ";
					$set_query .= " admin_name = '".$admin_name."', ";
					$set_query .= " admin_wdate = now() ";
					$set_result = mysqli_query($gconnet,$set_query);
					$calendar_in_cnt = $calendar_in_cnt+1;
					$in_date = $m_date;
					$in_week_str = $yoil[date('w', strtotime($in_date))];
					// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} elseif($row['week_type'] == "5"){ // 주 5 회 : 월, 화, 수, 목, 금
				if($week_str == "월" || $week_str == "화" || $week_str == "수" || $week_str == "목" || $week_str == "금"){
					$set_query = " insert into lecture_calendar set "; 
					$set_query .= " member_idx = '".$member_idx."', ";
					$set_query .= " order_num = '".$order_num."', ";
					$set_query .= " partner_idx = '".$partner_idx."', ";
					$set_query .= " p_date = '".$p_date."', ";
					$set_query .= " m_date = '".$m_date."', ";
					$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
					$set_query .= " m_time_s = '".$m_time_s."', ";
					$set_query .= " admin_idx = '".$admin_idx."', ";
					$set_query .= " admin_name = '".$admin_name."', ";
					$set_query .= " admin_wdate = now() ";
					$set_result = mysqli_query($gconnet,$set_query);
					$calendar_in_cnt = $calendar_in_cnt+1;
					$in_date = $m_date;
					$in_week_str = $yoil[date('w', strtotime($in_date))];
					// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} elseif($row['week_type'] == "7"){ // 주 7 회 : 일, 월, 화, 수, 목, 금, 토
				if($week_str == "일" || $week_str == "월" || $week_str == "화" || $week_str == "수" || $week_str == "목" || $week_str == "금" || $week_str == "토"){
					$set_query = " insert into lecture_calendar set "; 
					$set_query .= " member_idx = '".$member_idx."', ";
					$set_query .= " order_num = '".$order_num."', ";
					$set_query .= " partner_idx = '".$partner_idx."', ";
					$set_query .= " p_date = '".$p_date."', ";
					$set_query .= " m_date = '".$m_date."', ";
					$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
					$set_query .= " m_time_s = '".$m_time_s."', ";
					$set_query .= " admin_idx = '".$admin_idx."', ";
					$set_query .= " admin_name = '".$admin_name."', ";
					$set_query .= " admin_wdate = now() ";
					$set_result = mysqli_query($gconnet,$set_query);
					$calendar_in_cnt = $calendar_in_cnt+1;
					$in_date = $m_date;
					$in_week_str = $yoil[date('w', strtotime($in_date))];
					// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} elseif($row['week_type'] == "w2"){ // 주말 2 회 : 일, 토
				if($week_str == "일" || $week_str == "토"){
					$set_query = " insert into lecture_calendar set "; 
					$set_query .= " member_idx = '".$member_idx."', ";
					$set_query .= " order_num = '".$order_num."', ";
					$set_query .= " partner_idx = '".$partner_idx."', ";
					$set_query .= " p_date = '".$p_date."', ";
					$set_query .= " m_date = '".$m_date."', ";
					$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
					$set_query .= " m_time_s = '".$m_time_s."', ";
					$set_query .= " admin_idx = '".$admin_idx."', ";
					$set_query .= " admin_name = '".$admin_name."', ";
					$set_query .= " admin_wdate = now() ";
					$set_result = mysqli_query($gconnet,$set_query);
					$calendar_in_cnt = $calendar_in_cnt+1;
					$in_date = $m_date;
					$in_week_str = $yoil[date('w', strtotime($in_date))];
					// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} // 횟수별 구분 종료 
		}  // 21번 까지만 시작
	} else { // travel course 가 아닐때
		if($row['week_type'] == "2"){ // 주 2 회 : 화, 목
					if($week_str == "화" || $week_str == "목"){
						$set_query = " insert into lecture_calendar set "; 
						$set_query .= " member_idx = '".$member_idx."', ";
						$set_query .= " order_num = '".$order_num."', ";
						$set_query .= " partner_idx = '".$partner_idx."', ";
						$set_query .= " p_date = '".$p_date."', ";
						$set_query .= " m_date = '".$m_date."', ";
						$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
						$set_query .= " m_time_s = '".$m_time_s."', ";
						$set_query .= " admin_idx = '".$admin_idx."', ";
						$set_query .= " admin_name = '".$admin_name."', ";
						$set_query .= " admin_wdate = now() ";
						$set_result = mysqli_query($gconnet,$set_query);
						$calendar_in_cnt = $calendar_in_cnt+1;
						$in_date = $m_date;
						$in_week_str = $yoil[date('w', strtotime($in_date))];
						// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} elseif($row['week_type'] == "3"){ // 주 3 회 : 월, 수, 금
				if($week_str == "월" || $week_str == "수" || $week_str == "금"){
					$set_query = " insert into lecture_calendar set "; 
					$set_query .= " member_idx = '".$member_idx."', ";
					$set_query .= " order_num = '".$order_num."', ";
					$set_query .= " partner_idx = '".$partner_idx."', ";
					$set_query .= " p_date = '".$p_date."', ";
					$set_query .= " m_date = '".$m_date."', ";
					$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
					$set_query .= " m_time_s = '".$m_time_s."', ";
					$set_query .= " admin_idx = '".$admin_idx."', ";
					$set_query .= " admin_name = '".$admin_name."', ";
					$set_query .= " admin_wdate = now() ";
					$set_result = mysqli_query($gconnet,$set_query);
					$calendar_in_cnt = $calendar_in_cnt+1;
					$in_date = $m_date;
					$in_week_str = $yoil[date('w', strtotime($in_date))];
					// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} elseif($row['week_type'] == "5"){ // 주 5 회 : 월, 화, 수, 목, 금
				if($week_str == "월" || $week_str == "화" || $week_str == "수" || $week_str == "목" || $week_str == "금"){
					$set_query = " insert into lecture_calendar set "; 
					$set_query .= " member_idx = '".$member_idx."', ";
					$set_query .= " order_num = '".$order_num."', ";
					$set_query .= " partner_idx = '".$partner_idx."', ";
					$set_query .= " p_date = '".$p_date."', ";
					$set_query .= " m_date = '".$m_date."', ";
					$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
					$set_query .= " m_time_s = '".$m_time_s."', ";
					$set_query .= " admin_idx = '".$admin_idx."', ";
					$set_query .= " admin_name = '".$admin_name."', ";
					$set_query .= " admin_wdate = now() ";
					$set_result = mysqli_query($gconnet,$set_query);
					$calendar_in_cnt = $calendar_in_cnt+1;
					$in_date = $m_date;
					$in_week_str = $yoil[date('w', strtotime($in_date))];
					// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} elseif($row['week_type'] == "7"){ // 주 7 회 : 일, 월, 화, 수, 목, 금, 토
				if($week_str == "일" || $week_str == "월" || $week_str == "화" || $week_str == "수" || $week_str == "목" || $week_str == "금" || $week_str == "토"){
					$set_query = " insert into lecture_calendar set "; 
					$set_query .= " member_idx = '".$member_idx."', ";
					$set_query .= " order_num = '".$order_num."', ";
					$set_query .= " partner_idx = '".$partner_idx."', ";
					$set_query .= " p_date = '".$p_date."', ";
					$set_query .= " m_date = '".$m_date."', ";
					$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
					$set_query .= " m_time_s = '".$m_time_s."', ";
					$set_query .= " admin_idx = '".$admin_idx."', ";
					$set_query .= " admin_name = '".$admin_name."', ";
					$set_query .= " admin_wdate = now() ";
					$set_result = mysqli_query($gconnet,$set_query);
					$calendar_in_cnt = $calendar_in_cnt+1;
					$in_date = $m_date;
					$in_week_str = $yoil[date('w', strtotime($in_date))];
					// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} elseif($row['week_type'] == "w2"){ // 주말 2 회 : 일, 토
				if($week_str == "일" || $week_str == "토"){
					$set_query = " insert into lecture_calendar set "; 
					$set_query .= " member_idx = '".$member_idx."', ";
					$set_query .= " order_num = '".$order_num."', ";
					$set_query .= " partner_idx = '".$partner_idx."', ";
					$set_query .= " p_date = '".$p_date."', ";
					$set_query .= " m_date = '".$m_date."', ";
					$set_query .= " p_time_s = '".$row['curri_orgin_time']."', ";
					$set_query .= " m_time_s = '".$m_time_s."', ";
					$set_query .= " admin_idx = '".$admin_idx."', ";
					$set_query .= " admin_name = '".$admin_name."', ";
					$set_query .= " admin_wdate = now() ";
					$set_result = mysqli_query($gconnet,$set_query);
					$calendar_in_cnt = $calendar_in_cnt+1;
					$in_date = $m_date;
					$in_week_str = $yoil[date('w', strtotime($in_date))];
					// echo "입력일자  = ".$in_date." (".$in_week_str.") || ".$p_date."<br>";
				}
			} // 횟수별 구분 종료 
	} // travel course  인지 여부 종료

		if($i == $total_day-1){
			$calcu_date = $in_date;
		}

	}  // 기간동안 루프 종료
	
	//echo "총 세팅건수 = ".$calendar_in_cnt."<br>";

	$end_sql = "update lecture_regist set e_date='".$calcu_date."' where 1 and order_num = '".$order_num."' ";
	$end_query = mysqli_query($gconnet,$end_sql);
	//echo $end_sql."<br>";
	//exit;
	
?>

<script>
	alert("강의시간표에 일괄 업데이트 되었습니다.");
	parent.location.href="../lecture/lecture_resolve.php?bmenu=5&smenu=2";
</script>