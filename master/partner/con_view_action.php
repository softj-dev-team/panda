<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$site_sect = trim(sqlfilter($_REQUEST['site_sect']));
	$prev_reply_ok = trim(sqlfilter($_REQUEST['prev_reply_ok']));
	
	$view_ok = trim(sqlfilter($_REQUEST['view_ok']));
	$admin_memo = trim(sqlfilter($_REQUEST['admin_memo']));

	$ad_sect_id = $_SESSION['admin_homest_id'];
	$ad_sect_name = $_SESSION['admin_homest_name'];

	$prev_sql = "select idx from pat_member_ad where 1=1 and idx = '".$idx."' ";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	$prev_cnt = mysqli_num_rows($prev_query);
	
	if($prev_cnt == 0){
		error_frame("답변 설정할 내용이 없습니다.");
		exit;
	}

	$query1 = " update pat_member_ad set ";
	$query1 .= " reply_ok = 'Y', ";
	$query1 .= " replydate = now(), ";
	$query1 .= " ad_sect_id = '".$ad_sect_id."', ";
	$query1 .= " ad_sect_name = '".$ad_sect_name."' ";
	$query1 .= " where idx = '".$idx."' and reply_ok = 'N' ";
	$result1 = mysqli_query($gconnet,$query1);

	$query2 = " update pat_member_ad set ";
	$query2 .= " view_ok = '".$view_ok."', ";
	$query2 .= " admin_memo = '".$admin_memo."' ";
	$query2 .= " where idx = '".$idx."' ";
	$result2 = mysqli_query($gconnet,$query2);

	######### 최초 답변 저장시 메일로 발송 시작 ########
	if($prev_reply_ok == "N"){
		$prev_sql = "SELECT * FROM pat_member_ad where 1=1 and idx = '".$idx."' ";
		$prev_query = mysqli_query($gconnet,$prev_sql);
		$prev_row = mysqli_fetch_array($prev_query);

		$FROMNAME = "에디마켓";
		$FROMEMAIL = "no-reply@edimarket.com"; 
		$SUBJECT = "[에디마켓] 신청하신 ".$prev_row[ad_type]." 에 답변드립니다.";
		$tomail = $prev_row[dam_email];

		//$tomail = "gelila2@naver.com";
		$m_content = nl2br($prev_row[ad_memo]);
		
		$mail_content = "
		<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:846px;border:1px solid #dddddd;border-top:3px solid c62828;\">
		<tr>
			<td colspan=\"3\" style=\"height:23px;\"></td>
		</tr>
		<tr>
		<td style=\"width:56px;\"></td>
		<td style=\"width:733px;\">
			<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:733px;\">
				<tr>
					<td style=\"height:43px;border-bottom:2px solid #333;font-weight:bold;font-size:20px;font-family:'맑은 고딕','Malgun Gothic';color:#333333;\">다음 질문하신 내용에 관리자 답변이 등록 되었습니다. </td>
				</tr>
				
				<tr>
					<td style=\"height:45px;\"></td>
				</tr>
				<tr>
					<td style=\"border:1px solid #dddddd;background-color:#f7f7f7;padding:12px 0 12px 39px;\">
						<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:px;\">
							<tr>
								<th style=\"width:103px;height:25px;text-align:left;font-size:13px;border-right:1px solid #dddddd; font-family:'돋움','Dotum';color:#333333;\">위치</th>
								<td style=\"font-size:15px;font-family:'돋움','Dotum';color:#666; padding-left:12px;\">".$prev_row[ad_location]."</td>
							</tr>
							<tr>
								<th style=\"width:103px;height:25px;text-align:left;font-size:13px;border-right:1px solid #dddddd; font-family:'돋움','Dotum';color:#333333;\">집행기간</th>
								<td style=\"font-size:15px;font-family:'돋움','Dotum';color:#666; padding-left:12px;\">".$prev_row[ad_period]." 개월</td>
							</tr>
							<tr>
								<th style=\"width:103px;height:25px;text-align:left;font-size:13px;border-right:1px solid #dddddd; font-family:'돋움','Dotum';color:#333333;\">광고문구</th>
								<td style=\";font-size:15px;font-family:'돋움','Dotum';color:#666; padding-left:12px;\">".$prev_row[ad_yakinfo]."</td>
							</tr>
							<tr>
								<th style=\"width:103px;height:25px;text-align:left;font-size:13px;border-right:1px solid #dddddd; font-family:'돋움','Dotum';color:#333333;\">내용</th>
								<td style=\"font-size:15px;font-family:'돋움','Dotum';color:#666; padding-left:12px;\">".$m_content."</td>
							</tr>
							<tr>
								<td colspan=\"2\" style=\"height:50px;\"></td>
							</tr>
							<tr>
								<th style=\"width:103px;height:25px;text-align:left;font-size:13px;border-right:1px solid #dddddd; font-family:'돋움','Dotum';color:#333333;\">관리자 답변</th>
								<td style=\"font-size:15px;font-family:'돋움','Dotum';color:#666; padding-left:12px;\">".nl2br($prev_row[admin_memo])."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style=\"height:30px\"></td>
				</tr>
			</table>
		</td>
		<td style=\"width:55px;\"></td>
	</tr>
	<tr>
		<td colspan=\"3\" style=\"height:50px;\"></td>
	</tr>
	</table>
	";

	$mail_content = $mail_content;

	$pwd_mail = mail_utf($FROMEMAIL,$FROMNAME,$tomail, $SUBJECT, $mail_content); // 메일을 발송한다.

	}
	######### 최초 답변 저장시 메일로 발송 종료 ########
		
	if($result2){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 완료 되었습니다.');
	parent.location.href =  "con_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
