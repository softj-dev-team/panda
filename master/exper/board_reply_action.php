.<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
/*if(!$_AUTH_REPLY){
	error_frame("덧글작성 권한이 없습니다.");
	exit;
}*/
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));										//bbs_code
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$idx = trim(sqlfilter($_REQUEST['idx']));

$member_idx = trim(sqlfilter($_SESSION['admin_gosm_idx']));								//user_id
$view_idx = trim(sqlfilter($_REQUEST['view_idx']));	 //view_id
$p_no = trim(sqlfilter($_REQUEST['p_no']));	 

$is_secure = trim(sqlfilter($_REQUEST['is_secure']));						//비밀글여부
$is_html =  trim(sqlfilter($_REQUEST['is_html']));												//is_html
$is_popup = trim(sqlfilter($_REQUEST['is_popup']));						// 공지사항 여부

$passwd = sqlfilter($_REQUEST['passwd']);	
if ($passwd==""){
	$passwd = md5(sqlfilter($_SESSION['admin_gosm_password']));	//비밀번호
} else {
	$passwd = md5($passwd);	//비밀번호
}

$bbs_sect = sqlfilter($_REQUEST['bbs_sect']);	
$bbs_tag = sqlfilter($_REQUEST['bbs_tag']);	
$scrap_ok = sqlfilter($_REQUEST['scrap_ok']);	
$ccl_ok = sqlfilter($_REQUEST['ccl_ok']);	

$ip = trim(sqlfilter($_REQUEST['ip']));											//ip

$subject = trim(sqlfilter($_REQUEST['subject']));								//제목
$writer = trim(sqlfilter($_REQUEST['writer']));									//글쓴이
$content = trim(sqlfilter($_REQUEST['fm_write']));										//내용
$wdate = date("Y-m-d H:i:s");

if ($writer==""){
	if($_SESSION['admin_gosm_idx']){ // 관리자 로그인 
	$writer = $_SESSION['admin_gosm_name'];	
	} 
}

//1:1문의용
$cate_1vs1 = trim(sqlfilter($_REQUEST['1vs1_cate']));					//유형선택
$email = trim(sqlfilter($_REQUEST['email']));									//이메일
$cell_1vs1 = trim(sqlfilter($_REQUEST['1vs1_cell']));						//휴대전화

//에디터 사용 안할때
if($is_html != "Y"){
	$content = strip_tags($content);
	$content = addslashes($content);
}

$max_query = "select ref,step,depth from board_content where 1=1 and idx = '".$idx."' ";
$max_result = mysqli_query($gconnet,$max_query);
$max_row = mysqli_fetch_array($max_result);

$ref = $max_row[ref];
$step = $max_row[step];
$depth = $max_row[depth];

$reply_query = "update board_content set step=(step+1) where bbs_code='".$bbs_code."' and ref=".$ref." and step>".$step;
mysqli_query($gconnet,$reply_query);

if ($step ==0){
	$step = 1;
} else {
	$step = $step+1;
}

$depth = $depth +1;
		
	/*$query = "insert into board_content (p_no,member_idx,view_idx,bbs_code,ref,step,depth,subject,writer,passwd,content,file_o,file_c,file_o2,file_c2,ip,write_time,is_html,email,is_secure,is_popup,status,cate_1vs1,cell_1vs1)  values  (N'".$p_no."',N'".$member_idx."',N'".$view_idx."',N'".$bbs_code."',N'".$ref."',N'".$step."',N'".$depth."',N'".$subject."',N'".$writer."',N'".$passwd."',N'".$content."',N'".$file_o."',N'".$file_c."',N'".$file_o2."',N'".$file_c2."',N'".$ip."',N'".$wdate."',N'".$is_html."',N'".$email."',N'".$is_secure."',N'".$is_popup."',N'".$status."',N'".$cate_1vs1."',N'".$cell_1vs1."');";*/
	
	//echo $query; exit;

	$user_id = $_SESSION['admin_gosm_id'];
	$view_id = $_SESSION['admin_gosm_id'];

	$query = " insert into board_content set "; 
	$query .= " p_no = '".$p_no."', ";
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " view_idx = '".$view_idx."', ";
	$query .= " user_id = '".$user_id."', ";
	$query .= " view_id = '".$view_id."', ";
	$query .= " bbs_code = '".$bbs_code."', ";

	$query .= " bbs_sect = '".$bbs_sect."', ";
	$query .= " bbs_tag = '".$bbs_tag."', ";
	$query .= " scrap_ok = '".$scrap_ok."', ";
	$query .= " ccl_ok = '".$ccl_ok."', ";

	$query .= " ref = '".$ref."', ";
	$query .= " step = '".$step."', ";
	$query .= " depth = '".$depth."', ";
	$query .= " subject = '".$subject."', ";
	$query .= " writer = '".$writer."', ";
	$query .= " passwd = '".$passwd."', ";
	$query .= " content = '".$content."', ";
	$query .= " ip = '".$ip."', ";
	$query .= " is_html = '".$is_html."', ";
	$query .= " email = '".$email."', ";
	$query .= " is_secure = '".$is_secure."', ";
	$query .= " is_popup = '".$is_popup."', ";
	$query .= " 1vs1_cate = '".$cate_1vs1."', ";
	$query .= " 1vs1_cell = '".$cell_1vs1."', ";
	$query .= " write_time = now() ";
	
	$result = mysqli_query($gconnet,$query);

	$sql_pre2 = " select idx from board_content where 1=1 and bbs_code = '".$bbs_code."' order by idx desc limit 0,1"; 
	$result_pre2  = mysqli_query($gconnet,$sql_pre2);
	$mem_row2 = mysqli_fetch_array($result_pre2);
	$board_idx = $mem_row2[idx]; 

	################# 첨부파일 업로드 시작 #######################
	
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs_code."/";

	$board_tbname = "board_content";
	$board_code = $bbs_code;

	for($file_i=0; $file_i<$_include_board_file_cnt; $file_i++){ // 설정된 갯수만큼 루프 시작

		if ($_FILES['file_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작

			$file_o = $_FILES['file_'.$file_i]['name']; 
			$file_c = uploadFile($_FILES, "file_".$file_i, $_FILES['file_'.$file_i], $_P_DIR_FILE); // 파일 업로드후 변형된 파일이름 리턴.

			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$board_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";

			$result_file = mysqli_query($gconnet,$query_file);
		
		} // 파일이 있다면 업로드한다 종료 

	} // 설정된 갯수만큼 루프 종료

	################# 첨부파일 업로드 종료 #######################

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('답글 등록이 정상적으로 완료 되었습니다.');
	//parent.location.href =  "board_view.php?idx=<?=$p_no?>&<?=$total_param?>";
	parent.location.href =  "board_list.php?&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('답글 등록중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
