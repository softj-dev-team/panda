<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
if(!$_AUTH_WRITE){
	error_frame("본문작성 권한이 없습니다.");
	exit;
}

$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));										//bbs_code
$total_param = trim(sqlfilter($_REQUEST['total_param']));

$member_idx = trim(sqlfilter($_SESSION['manage_coinc_idx']));								//user_id
$view_idx = trim(sqlfilter($_SESSION['manage_coinc_idx']));	 //view_id
$subject = trim(sqlfilter($_REQUEST['subject']));								//제목
$writer = trim(sqlfilter($_REQUEST['writer']));									//글쓴이
$passwd = sqlfilter($_REQUEST['passwd']);	

$bbs_sect = sqlfilter($_REQUEST['bbs_sect']);	
$bbs_tag = sqlfilter($_REQUEST['bbs_tag']);	
$scrap_ok = sqlfilter($_REQUEST['scrap_ok']);	
$ccl_ok = sqlfilter($_REQUEST['ccl_ok']);	

$content = trim(sqlfilter($_REQUEST['ir2']));										//내용
$ip = trim(sqlfilter($_REQUEST['ip']));											//ip
$wdate = date("Y-m-d H:i:s");

//echo "내용 = ".$content; exit;

if ($passwd==""){
	$passwd = md5(sqlfilter($_SESSION['manage_coinc_password']));	//비밀번호
} else {
	$passwd = md5($passwd);	//비밀번호
}

if ($writer==""){
	if($_SESSION['manage_coinc_idx']){ // 관리자 로그인 
	$writer = $_SESSION['manage_coinc_name'];	
	} 
}

$is_secure = trim(sqlfilter($_REQUEST['is_secure']));						//비밀글여부
$is_html =  trim(sqlfilter($_REQUEST['is_html']));												//is_html
$is_popup = trim(sqlfilter($_REQUEST['is_popup']));						// 공지사항 여부

//1:1문의용
$cate_1vs1 = trim(sqlfilter($_REQUEST['1vs1_cate']));					//유형선택
$email = trim(sqlfilter($_REQUEST['email']));									//이메일
$cell_1vs1 = trim(sqlfilter($_REQUEST['1vs1_cell']));						//휴대전화

$product_idx = trim(sqlfilter($_REQUEST['product_idx']));
$sing = trim(sqlfilter($_REQUEST['sing']));
$sido = trim(sqlfilter($_REQUEST['sido']));
$gugun = trim(sqlfilter($_REQUEST['gugun']));
$weplaza = trim(sqlfilter($_REQUEST['weplaza']));
$after_point = trim(sqlfilter($_REQUEST['after_point']));

//에디터 사용 안할때
if($is_html != "Y"){
	$content = strip_tags($content);
	$content = addslashes($content);
}

	$max_query = "select max(ref) as max from board_content where 1=1 ";
	$max_result = mysqli_query($gconnet,$max_query);
	$max_row = mysqli_fetch_array($max_result);
	if ($max_row['max']){
		$max = $max_row['max']+1;
	} else{
		$max = 1;
	}
	
	$step = 0;
	$depth = 0;

	$user_id = $_SESSION['manage_coinc_id'];
	$view_id = $_SESSION['manage_coinc_id'];

	$query = " insert into board_content set "; 
	$query .= " p_no = '".$p_no."', ";
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " product_idx = '".$product_idx."', ";
	$query .= " after_point = '".$after_point."', ";
	$query .= " view_idx = '".$view_idx."', ";
	$query .= " user_id = '".$user_id."', ";
	$query .= " view_id = '".$view_id."', ";
	$query .= " bbs_code = '".$bbs_code."', ";

	$query .= " bbs_sect = '".$bbs_sect."', ";
	$query .= " bbs_tag = '".$bbs_tag."', ";
	$query .= " scrap_ok = '".$scrap_ok."', ";
	
	$query .= " ref = '".$max."', ";
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
	
	//echo $query; exit;
	
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

	################# 과금 게시판 글 등록시 문자발송 시작 #######################
	/*if($_include_board_close_ok == "Y"){ // 과금 게시판
		$sms_member_sql = "select cell from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type = 'GEN' order by idx asc";
		$sms_member_query = mysqli_query($gconnet,$sms_member_sql);
		for($sms_member_i=0; $sms_member_i<mysqli_num_rows($sms_member_query); $sms_member_i++){
			$sms_member_row = mysqli_fetch_array($sms_member_query);
			if($sms_member_i == mysqli_num_rows($sms_member_query)-1){
				$total_sms_receive .= $sms_member_row['cell'];
			} else {
				$total_sms_receive .= $sms_member_row['cell'].",";
			}
		}

		//echo $total_sms_receive; exit;

		$msg = "[코인코잉] ".$_include_board_board_title." 게시판에 새 글이 등록되었습니다.";
		$smsType = "S";

		$sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
        // $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
        $sms['user_id'] = base64_encode("modufreeinfo"); //SMS 아이디.
        $sms['secure'] = base64_encode("e3933698937dae20baf98e2734635756") ;//인증키
        $sms['msg'] = base64_encode(stripslashes($msg));
        if($smsType == "L"){
              $sms['subject'] =  base64_encode($_POST['subject']);
        }
       	$sms['rphone'] = base64_encode($total_sms_receive); 
        $sms['sphone1'] = base64_encode($sms_send_num1); 
        $sms['sphone2'] = base64_encode($sms_send_num2); 
        $sms['sphone3'] = base64_encode($sms_send_num3); 
        $sms['rdate'] = base64_encode($_POST['rdate']);
        $sms['rtime'] = base64_encode($_POST['rtime']);
        $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
        $sms['returnurl'] = base64_encode($_POST['returnurl']);
        $sms['testflag'] = base64_encode($_POST['testflag']);
        $sms['destination'] = strtr(base64_encode($POST['destination']), '+/=', '-,');
        $returnurl = $_POST['returnurl'];
        $sms['repeatFlag'] = base64_encode($_POST['repeatFlag']);
        $sms['repeatNum'] = base64_encode($_POST['repeatNum']);
        $sms['repeatTime'] = base64_encode($_POST['repeatTime']);
        $sms['smsType'] = base64_encode($smsType); // LMS일경우 L
        $nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

		$host_info = explode("/", $sms_url);
        $host = $host_info[2];
        $path = $host_info[3]."/".$host_info[4];

        srand((double)microtime()*1000000);
        $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
        //print_r($sms);

        // 헤더 생성
        $header = "POST /".$path ." HTTP/1.0\r\n";
        $header .= "Host: ".$host."\r\n";
        $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

        // 본문 생성
        foreach($sms AS $index => $value){
            $data .="--$boundary\r\n";
            $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
            $data .= "\r\n".$value."\r\n";
            $data .="--$boundary\r\n";
        }
        $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

        $fp = fsockopen($host, 80);

		if ($fp) {
            fputs($fp, $header.$data);
            $rsp = '';
            while(!feof($fp)) {
                $rsp .= fgets($fp,8192);
            }
            fclose($fp);
            $msg = explode("\r\n\r\n",trim($rsp));
            $rMsg = explode(",", $msg[1]);
            $Result= $rMsg[0]; //발송결과
            $Count= $rMsg[1]; //잔여건수

            //발송결과 알림
            if($Result=="success") {
                $alert = "성공";
                $alert .= " 잔여건수는 ".$Count."건 입니다.";
            }
            else if($Result=="reserved") {
                $alert = "성공적으로 예약되었습니다.";
                $alert .= " 잔여건수는 ".$Count."건 입니다.";
            }
            else if($Result=="3205") {
                $alert = "잘못된 번호형식입니다.";
            }

            else if($Result=="0044") {
                $alert = "스팸문자는발송되지 않습니다.";
            }

            else {
                $alert = "[Error]".$Result;
            }
        }
        else {
            $alert = "Connection Failed";
        }

		if($nointeractive=="1" && ($Result!="success" && $Result!="Test Success!" && $Result!="reserved") ) {
            echo "<script>alert('".$alert ."')</script>";
        }
        else if($nointeractive!="1") {
            echo "<script>alert('".$alert ."')</script>";
        }
        echo "<script>location.href='".$returnurl."';</script>";

		//echo $Result." : ".$alert;

		if($Result != "success"){
			error_frame("인증문자가 발송되지 않았습니다. 관리자에게 문의해 주세요.");
		}

	}*/
	################# 과금 게시판 글 등록시 문자발송 종료 #######################

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('게시물 등록이 정상적으로 완료 되었습니다.');
		parent.location.href =  "board_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{
		echo $query; //exit;
		?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시물 등록중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>