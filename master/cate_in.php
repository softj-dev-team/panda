<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$sql = "select * from product_cate where 1=1 and cate_level='3' and cate_code1 = 'ns0001' and cate_code2 = 'ns0004' and del_ok = 'N' ORDER BY cate_align ASC";
	$query = mysql_query($sql);
	for($i=0; $i<mysql_num_rows($query); $i++){
		$row = mysql_fetch_array($query);

		$cate_code_sql = "select idx from product_cate where 1";
		$cate_code_query = mysql_query($cate_code_sql);
		$cate_code_num = mysql_num_rows($cate_code_query);
		$cate_code_num = $cate_code_num+1;

		if($cate_code_num < 10){
			$cate_code_ran = "ns000".$cate_code_num;
		} elseif($cate_code_num >= 10 && $cate_code_num < 100){
			$cate_code_ran = "ns00".$cate_code_num;
		} elseif($cate_code_num >= 100 && $cate_code_num < 1000){
			$cate_code_ran = "ns0".$cate_code_num;
		} elseif($cate_code_num >= 1000){
			$cate_code_ran = "ns".$cate_code_num;
		}

		$cate_align = $i+1;

		$query_in = " insert into product_cate set "; 
		$query_in .= " cate_level = '3', ";
		$query_in .= " cate_code1 = '".$row[cate_code1]."', ";
		$query_in .= " cate_name1 = '".$row[cate_name1]."', ";
		$query_in .= " cate_code2 = 'ns0005', ";
		$query_in .= " cate_name2 = 'Woven', ";
		$query_in .= " cate_code3 = '".$cate_code_ran."', ";
		$query_in .= " cate_name3 = '".$row[cate_name3]."', ";
		$query_in .= " cate_align = '".$cate_align."', ";
		$query_in .= " wdate = now() ";
		//echo $query_in; 
		$result_in = mysql_query($query_in);

	}
?>