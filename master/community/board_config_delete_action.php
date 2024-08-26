<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = sqlfilter($_REQUEST['smenu']);
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&pageNo='.$pageNo;

	$file_sql = "select file1_chg,file2_chg from board_config where 1=1 and idx = '".$idx."' ";
	$file_query = mysqli_query($gconnet,$file_sql);
	$file_row = mysqli_fetch_array($file_query);
	$file_old_name1 = $file_row['file1_chg'];
	$file_old_name2 = $file_row['file2_chg'];

	$bbs = "board_config";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	//$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	//$_P_DIR_FILE3 = $_P_DIR_FILE."img_thumb2/";
	
	if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 
		//unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
		//unlink($_P_DIR_FILE3.$file_old_name1); // 원본 중간 섬네일 파일 삭제
	}

	if($file_old_name2){
		unlink($_P_DIR_FILE.$file_old_name2); // 
		//unlink($_P_DIR_FILE2.$file_old_name2); // 원본 작은 섬네일 파일 삭제
		//unlink($_P_DIR_FILE3.$file_old_name2); // 원본 중간 섬네일 파일 삭제
	}

	//echo $file_sql." :: ".$file_old_name1." : ".$file_old_name2; exit;
	
	$is_del = "Y";

	$query = " update board_config set "; 
	$query .= " is_del = '".$is_del."' ";
	$query .= " where 1=1 and idx = '".$idx."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시판 삭제가 정상적으로 완료 되었습니다.');
	parent.location.href =  "board_config_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시판 삭제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>