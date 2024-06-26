<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header_pop.php"; // 관리자페이지 헤더
include "../include/admin_top_pop.php"; // 관리자페이지 상단메뉴

################## 받는값  #########################

$member_idx = trim(sqlfilter($_REQUEST['member_idx']));

$sql = "SELECT * FROM ".NS."member_info where 1=1 and idx = '".$member_idx."' ";
$query = mysqli_query($GLOBALS['gconnet'],$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 회원이 없습니다.');
	self.close();
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

$birthday_arr = explode("-",$row[birthday]);

if($row[gender] == "M"){
	$gender = "남성";
} elseif($row[gender] == "F"){
	$gender = "여성";
}

if($row[avatar_idx]){
	$avatar_sql = "select idx,avatar_name,avatar_gender,file_chg,file_org from ".NS."member_avatar where 1=1 and idx = '".$row[avatar_idx]."' ";
	$avatar_query = mysqli_query($GLOBALS['gconnet'],$avatar_sql);
	$avatar_row = mysqli_fetch_array($avatar_query);
}

if($row[addr_sect] == "H"){
	$addr_sect = "자택주소";
} elseif($row[addr_sect] == "J"){
	$addr_sect = "직장주소";
}

$member_level_sql = "select level_name from ".NS."member_level_set where 1=1 and level_code = '".$row[user_level]."' ";   
$member_level_query = mysqli_query($GLOBALS['gconnet'],$member_level_sql);
$member_level_row = mysqli_fetch_array($member_level_query);
$user_level_str = $member_level_row['level_name'];

if($row[user_gubun] == "GEN_M"){
	$user_gubun = "정회원";
} elseif($row[user_gubun] == "GEN_S"){
	$user_gubun = "우수회원";
} elseif($row[user_gubun] == "GEN_V"){
	$user_gubun = "VIP 회원";
}  elseif($row[user_gubun] == "PAT_B"){
	$user_gubun = "게시판운영 회원";
}  elseif($row[user_gubun] == "PAT_S"){
	$user_gubun = "셀러 회원";
}  elseif($row[user_gubun] == "PAT_SS"){
	$user_gubun = "파워셀러 회원";
}  else {
	$user_gubun = "";
}

if($v_sect == "GEN"){
	$member_sect_str = "일반회원";
} elseif($v_sect == "PAT"){
	$member_sect_str = "제휴회원";
}

$member_add_sql = "select * from ".NS."member_info_add where 1=1 and member_idx = '".$idx."' ";	
$member_add_query = mysqli_query($GLOBALS['gconnet'],$member_add_sql);
$member_add_cnt = mysqli_num_rows($member_add_query);

if($member_add_cnt > 0){
	$user_gubun = "우수회원";
}
?>

<!-- content -->
<section id="content">
	<div class="inner">
		<h3>상품등록 제휴회원 기본정보 상세보기</h3>
		<div class="cont">
			
			<table class="t_view">
				<colgroup>
					<col width="15%" />
					<col width="35%" />
					<col width="15%" />
					<col width="35%" />
				</colgroup>
				<?//if($v_sect == "NOR"){ ?>
				
				<?if($row[file_chg]){?>
					<tr>
						<th >회원사진</th>
						<td colspan="3">
							<img src="<?=$_P_DIR_WEB_FILE?>member/img_thumb/<?=$row[file_chg]?>" border="0">
						</td>
					</tr>
				<?}elseif($row[avatar_idx]){?>
					<tr>
						<th >아바타 이미지</th>
						<td colspan="3">
							<img src="<?=$_P_DIR_WEB_FILE?>avatar/img_thumb/<?=$avatar_row[file_chg]?>" border="0">
						</td>
					</tr>
				<?}?>

					<tr>
						<th >아이디 (ID)</th>
						<td ><?=$row[user_id]?></td>
						<th >성 명</th>
						<td ><?=$row[user_name]?></td>
					</tr>

					<tr>
						<th >닉네임</th>
						<td ><?=$row[user_nick]?></td>
						<th >추천인 아이디</th>
						<td ><?=$row[chuchun_id]?></td>
					</tr>

					<tr>
						<th >성 별</th>
						<td ><?=$gender?></td>
						<th >생일</th>
						<td> <?=$birthday_arr[0]?> 년&nbsp;<?=$birthday_arr[1]?> 월&nbsp;<?=$birthday_arr[2]?> 일&nbsp;&nbsp;(<?=$row[birthday_tp]?>)</td>
					</tr>
					
					<tr>
						<th >회원구분</th>
						<td ><?=$user_gubun?></td>
						<th >회원계급</th>
						<td ><?=$user_level_str?></td>
					</tr>

					<tr>
						<th >이메일</th>
						<td colspan="3"><?=$row[email]?></td>
					</tr>

					<tr>
						<th >자택전화</th>
						<td ><?=$row[tel]?></td>
						<th >휴대전화</th>
						<td ><?=$row[cell]?></td>
					</tr>
			<?//} elseif($v_sect == "COM"){?>
					<!--<tr>
						<th >아이디 (ID)</th>
						<td ><?=$row[user_id]?></td>
						<th >업체명</th>
						<td ><?=$row[com_name]?></td>
					</tr>
					<tr>
						<th >대표전화</th>
						<td ><?=$row[com_tel]?></td>
						<th >사업자 등록번호</th>
						<td ><?=$row[com_num]?></td>
					</tr>
					<tr>
						<th >대표자명</th>
						<td ><?=$row[username]?></td>
						<th >대표자 휴대전화</th>
						<td ><?=$row[cell]?></td>
					</tr>-->
			<?//}?>
					
					<tr>
						<th ><?=$addr_sect?></th>
						<td colspan="3">[<?=$row[post]?>] <?=$row[addr1]?>&nbsp;<?=$row[addr2]?> <!--(<?=$addr_sect?>)--></td>
					</tr>

					<tr>
						<th >이메일 수신여부</th>
						<td >
							<?if($row[mail_ok] == "Y"){?>
								동의
							<?} elseif($row[mail_ok] == "N"){?>
								동의안함
							<?}?>
						</td>
						<th >SMS 수신여부</th>
						<td >
							<?if($row[sms_ok] == "Y"){?>
								동의
							<?} elseif($row[sms_ok] == "N"){?>
								동의안함
							<?}?>
						</td>
					</tr>
					<tr>
						<th >쪽지 수신여부</th>
						<td >
							<?if($row[newsletter_ok] == "Y"){?>
								동의
							<?} elseif($row[newsletter_ok] == "N"){?>
								동의안함
							<?}?>
						</td>
						<th >우편물 수신여부</th>
						<td >
							<?if($row[spletter_ok] == "Y"){?>
								동의
							<?} elseif($row[spletter_ok] == "N"){?>
								동의안함
							<?}?>
						</td>
					</tr>

					<tr>
						<th >트위터 계정</th>
						<td colspan="3">
							<?=$row[sns_twitter]?>
							<?if($row[twitter_ok] == "Y"){?>
								<font style="color:blue;">공개</font>
							<?} elseif($row[twitter_ok] == "N"){?>
								<font style="color:red;">비공개</font>
							<?}?>
						</td>
					</tr>
					<tr>
						<th >페이스북 계정</th>
						<td colspan="3">
							<?=$row[sns_facebook]?>
							<?if($row[facebook_ok] == "Y"){?>
								<font style="color:blue;">공개</font>
							<?} elseif($row[facebook_ok] == "N"){?>
								<font style="color:red;">비공개</font>
							<?}?>
						</td>
					</tr>
					<tr>
						<th >미투데이 계정</th>
						<td colspan="3">
							<?=$row[sns_meto]?>
							<?if($row[meto_ok] == "Y"){?>
								<font style="color:blue;">공개</font>
							<?} elseif($row[meto_ok] == "N"){?>
								<font style="color:red;">비공개</font>
							<?}?>
						</td>
					</tr>
					<tr>
						<th >요즘 계정</th>
						<td colspan="3">
							<?=$row[sns_yozm]?>
							<?if($row[yozm_ok] == "Y"){?>
								<font style="color:blue;">공개</font>
							<?} elseif($row[yozm_ok] == "N"){?>
								<font style="color:red;">비공개</font>
							<?}?>
						</td>
					</tr>
					<tr>
						<th >카톡 계정</th>
						<td colspan="3">
							<?=$row[sns_catok]?>
							<?if($row[catok_ok] == "Y"){?>
								<font style="color:blue;">공개</font>
							<?} elseif($row[catok_ok] == "N"){?>
								<font style="color:red;">비공개</font>
							<?}?>
						</td>
					</tr>

					<tr>
						<th >자기소개</th>
						<td colspan="3">
							<?=nl2br($row[m_channel])?>
						</td>
					</tr>
									
					<tr>
						<th >기본정보 등록일시</th>
						<td colspan="3"><?=$row[wdate]?></td>
					</tr>

			</table>
			
	</div>
</section>
<!-- //content -->
<!--footer-->
</div>
</section>
<!-- //container -->
 	