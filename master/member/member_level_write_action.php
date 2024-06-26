<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

	<?
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$level_code = trim(sqlfilter($_REQUEST['level_code']));
	$level_name = trim(sqlfilter($_REQUEST['level_name']));
	$level_gijun = trim(sqlfilter($_REQUEST['level_gijun']));
	$level_price = trim(sqlfilter($_REQUEST['level_price']));
	$level_align = trim(sqlfilter($_REQUEST['level_align']));

	$sql_pre1 = "select idx from member_level_set where 1=1 and level_code = '".$level_code."' "; // 중복 코드 방지
	$result_pre1  = mysqli_query($gconnet,$sql_pre1);

	if(mysqli_num_rows($result_pre1) > 0) {
	?>
		<SCRIPT LANGUAGE="JavaScript">
		<!--	
			alert('입력하신 코드는 이미 등록된 코드입니다.\n\n다시 확인하시고 입력해 주세요.');
		//-->
		</SCRIPT>
	<?
	exit;
	}
	
	$bbs = "level";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";
	if ($_FILES['file1']['size']>0){
		$file_o = $_FILES['file1']['name']; 
		$i_width = "10";
		$i_height = "10";
		$i_width2 = "";
		$i_height2 = "";
		//$watermark_sect = "imgw";
		$watermark_sect = "";
		$file_c = uploadFileThumb_1($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);
	}
	
	$query = " insert into member_level_set set "; 
	$query .= " level_code = '".$level_code."', ";
	$query .= " level_name = '".$level_name."', ";
	$query .= " file_org = '".$file_o."', ";
	$query .= " file_chg = '".$file_c."', ";
	$query .= " level_align = '".$level_align."', ";
	$query .= " level_sect = 'PAT', ";
	$query .= " level_gijun = '".$level_gijun."', ";
	$query .= " level_price = '".$level_price."', ";
	$query .= " wdate = now() ";

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('회원등급 생성이 정상적으로 완료 되었습니다.');
	parent.location.href =  "member_level_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('회원등급 생성중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
