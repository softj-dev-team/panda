<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
		$temple_idx = trim(sqlfilter($_REQUEST['pro_idx']));

		$sql_name = "select idx,temple_title,member_idx from temple_info where 1=1 and idx = '".$temple_idx."' ";
		$query_name = mysqli_query($gconnet,$sql_name);
		$row_name = mysqli_fetch_array($query_name);

		$max_query = "select max(align) as max from temple_info_new_list where 1 ";
		$max_result = mysqli_query($gconnet,$max_query);
		$max_row = mysqli_fetch_array($max_result);
		if ($max_row['max']){
			$align = $max_row['max']+1;
		} else{
			$align = 1;
		}

		$query_in = "insert into temple_info_new_list set"; 
		$query_in .= " temple_info_idx = '".$row_name['idx']."', ";
		$query_in .= " member_temple_idx = '".$row_name['member_idx']."', ";
		$query_in .= " align = '".$align."', ";
		$query_in .= " wdate = now() ";
		//echo $query_in; exit;
		$result_in = mysqli_query($gconnet,$query_in);
	
	if($result_in){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 완료 되었습니다.');
	parent.location.reload();
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
