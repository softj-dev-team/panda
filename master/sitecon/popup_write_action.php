<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
	$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$is_use = trim(sqlfilter($_REQUEST['is_use']));
	$subject = trim(sqlfilter($_REQUEST['subject']));
	$link_url = trim(sqlfilter($_REQUEST['link_url']));
	$startdt = trim(sqlfilter($_REQUEST['startdt']));
	$enddt = trim(sqlfilter($_REQUEST['enddt']));
	$x = trim(sqlfilter($_REQUEST['x']));
	$y = trim(sqlfilter($_REQUEST['y']));
	$width = trim(sqlfilter($_REQUEST['width']));
	$height = trim(sqlfilter($_REQUEST['height']));
	$content = trim(sqlfilter($_REQUEST['fm_write']));

	$wdate = date("Y-m-d H:i:s");
	$section = $_SESSION['admin_gabor_section'];
		
	/*$query = "insert into popup_div (subject, content, link_url, startdt,enddt,x,y,width,height,is_use,wdate) values (N'".$subject."',N'".$content."',N'".$link_url."',N'".$startdt."',N'".$enddt."',N'".$x."',N'".$y."',N'".$width."',N'".$height."',N'".$is_use."',N'".$wdate."')"; */

	$query = " insert into popup_div set "; 
	//$query .= " section = '".$section."', ";
	$query .= " subject = '".$subject."', ";
	$query .= " content = '".$content."', ";
	$query .= " link_url = '".$link_url."', ";
	$query .= " startdt = '".$startdt."', ";
	$query .= " enddt = '".$enddt."', ";
	$query .= " x = '".$x."', ";
	$query .= " y = '".$y."', ";
	$query .= " width = '".$width."', ";
	$query .= " height = '".$height."', ";
	$query .= " is_use = '".$is_use."', ";
	$query .= " admin_idx = '".$_SESSION['admin_coinc_idx']."', ";
	$query .= " wdate = now() ";
	$result = mysqli_query($gconnet,$query);
	
	//echo $query;
		

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('팝업 등록이 정상적으로 완료 되었습니다.');
	parent.location.href =  "popup_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('팝업 등록중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
