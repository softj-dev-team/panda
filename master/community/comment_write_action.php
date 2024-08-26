<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
$board_tbname = "board_content";
$board_code = trim(sqlfilter($_REQUEST['board_code']));										//bbs_code
$board_idx = trim(sqlfilter($_REQUEST['board_idx']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$member_idx = trim(sqlfilter($_REQUEST['member_idx']));								//user_id
$passwd = sqlfilter($_REQUEST['passwd']);	

if ($passwd==""){
	$passwd = md5(sqlfilter($_SESSION['admin_homest_password']));	//비밀번호
} else {
	$passwd = md5($passwd);	//비밀번호
}

$subject = trim(sqlfilter($_REQUEST['subject']));								//제목
$writer = trim(sqlfilter($_REQUEST['writer']));									//글쓴이
$content = trim(sqlfilter($_REQUEST['content']));												//내용

	/*$wdate = date("Y-m-d H:i:s");
	$subject = "한줄댓글";

	$query = "insert into _shop_bbs_comment (bbs,bbs_no,member_idx,writer,passwd,subject,content,write_time)  values  (N'".$bbs_code."',N'".$bbs_no."',N'".$member_idx."',N'".$writer."',N'".$passwd."',N'".$subject."',N'".$content."',N'".$wdate."')";*/
	
	//echo $query; 
	
	$_P_DIR_FILE = $_P_DIR_FILE.$board_code."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

	################ 사진 이미지 업로드 ##############
	if ($_FILES['file1']['size']>0){
		$file_o = $_FILES['file1']['name']; 
		$i_width = "715";
		$i_height = "400";
		$i_width2 = "";
		$i_height2 = "";
		//$watermark_sect = "imgw";
		$watermark_sect = "";
		$file_c = uploadFileThumb_1($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);
		
		//echo "file_c = ".$file_c."<br>";
		
		$image_true = upload_img_type($_P_DIR_FILE.$file_c);
		
		if(!$image_true){
			
			//echo "file_c = ".$file_c."<br>";
			if(is_file($_P_DIR_FILE.$file_c)) {

				unlink($_P_DIR_FILE.$file_c); // 이미지 파일이 아니면 원본파일을 삭제한다. 
				unlink($_P_DIR_FILE2.$file_c); // 원본 섬네일 파일도 삭제한다.
			
			}

			error_frame("이미지 파일만 업로드 가능합니다.");
			exit;
		}
		
	}

	$max_query = "select max(ref) as max from board_comment where 1=1 ";
	$max_result = mysqli_query($gconnet,$max_query);
	$max_row = mysqli_fetch_array($max_result);
	if ($max_row['max']){
		$max = $max_row['max']+1;
	} else{
		$max = 1;
	}

	$query = " insert into board_comment set "; 
	$query .= " board_tbname = '".$board_tbname."', ";
	$query .= " board_code = '".$board_code."', ";
	$query .= " board_idx = '".$board_idx."', ";
	$query .= " member_idx = '".$member_idx."', ";
	
	$query .= " p_no = '".$p_no."', ";
	$query .= " ref = '".$max."', ";
	$query .= " step = '".$step."', ";
	$query .= " depth = '".$depth."', ";

	$query .= " writer = '".$writer."', ";
	$query .= " passwd = '".$passwd."', ";
	$query .= " subject = '".$subject."', ";
	$query .= " file_org = '".$file_o."', ";
	$query .= " file_chg = '".$file_c."', ";
	$query .= " content = '".$content."', ";
	$query .= " write_time = now() ";
	
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('한줄댓글 등록이 정상적으로 완료 되었습니다.');
	parent.location.href =  "board_view.php?idx=<?=$board_idx?>&bbs_code=<?=$board_code?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('한줄댓글 등록중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>