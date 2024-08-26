<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$point_sect = sqlfilter($_REQUEST['point_sect']);

if($point_sect == "cash"){
	$point_str = "캐쉬전환 수수료 설정";
} elseif($point_sect == "refund"){
	$point_str = "포인트지급 포인트지급액 설정";
} elseif($point_sect == "ap"){
	$point_str = "활동포인트 지급/감소 설정";
} elseif($point_sect == "mp"){
	$point_str = "매너포인트 지급설정";
} 

$point_sql = "select  * from member_point_set where 1=1 and coin_type='member' and point_sect='".$point_sect."' order by idx desc limit 0,1"; // 가장 최근에 설정된 적립비율 설정내용을 가져온다.
$point_query = mysqli_query($gconnet,$point_sql);
$point_row = mysqli_fetch_array($point_query);
?>

<script type="text/javascript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
			<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 설정</li>
						<li>포인트지급 금액 설정</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>포인트지급 금액 설정</h3>
				</div>
				<div class="write">

					<div class="btn_wrap" style="margin-bottom:10px;">
						<b>회원가입, 로그인시 지급할 포인트가 있을경우 설정하고, 주차권 관련 포인트는 별도 설정없이 수수료를 제외한 포인트가 자동 적립됩니다.</b>
					</div>

		<form name="frm" id="frm" action="point_set_action.php"  target="_fra_admin" method="post"><!-- /////////폼시작 -->
			<input type="hidden" name="bmenu" value="<?=$bmenu?>">
			<input type="hidden" name="smenu" value="<?=$smenu?>">
			<input type="hidden" name="code" value="<?=$code?>">
			<input type="hidden" name="point_sect" value="<?=$point_sect?>"/>
		
			<table class="t_view">
				
		<?if($point_sect == "cash"){ // 캐쉬일 경우 시작 ?>
				<colgroup>
					<col width="10%" />
					<col width="90%" />
				</colgroup>
				<tr>
					<th>현금인출 수수료</th>
					<td>캐쉬를 현금으로 인출할때 <input type="text" name="minus_point_1" id="minus_point_1" class="input_txt" style="width:30px;" value="<?= $point_row[minus_point_1]?>"  required="yes" message="현금인출 수수료율" /> % 의 수수료를 제외하고 현금으로 인출 (수수료 없을 경우 0 입력) </td>
				</tr>
				<tr>
					<th>1 일 최대 인출신청금액</th>
					<td>캐쉬를 현금으로 인출할때 회원 개별 로 1 일 최대 <input type="text" name="add_point_6_1" id="add_point_6_1" class="input_txt" style="width:100px;" value="<?= $point_row[add_point_6_1]?>"  required="yes" message="1 일 최대 인출신청금액" is_num="yes"/> 까지 신청이 가능합니다. (제한 없을 경우 0 입력)  </td>
				</tr>
				<tr>
					<th>캐시 -> 박 충전시</th>
					<td>박 1 개당 <input type="text" name="add_point_6" id="add_point_6" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_6]?>"  required="yes" message="박 충전 소요캐쉬" is_num="yes"/> 캐쉬가 사용됩니다. </td>
				</tr>
				<tr>
					<th>박 -> 캐쉬 전환시</th>
					<td>사이버머니 (박) 을 캐쉬로 전환할때 <input type="text" name="payment_mile_gen" id="payment_mile_gen" class="input_txt" style="width:30px;" value="<?= $point_row[payment_mile_gen]?>"  required="yes" message="캐쉬 전환시 수수료율" /> % 의 수수료를 제외하고 캐쉬로 충전 (수수료 없을 경우 0 입력) </td>
				</tr>
				<tr>
					<th>1 일 최대 캐시 전환금액</th>
					<td>박 을 캐시로 전환할때 회원 개별 로 1 일 최대 <input type="text" name="minus_point_2" id="minus_point_2" class="input_txt" style="width:100px;" value="<?= $point_row[minus_point_2]?>"  required="yes" message="1 일 최대 캐시전환금액" is_num="yes"/> 까지 신청이 가능합니다. (제한 없을 경우 0 입력)  </td>
				</tr>

		<?} elseif($point_sect == "refund"){ // 포인트지급일 경우 시작 ?>
				<colgroup>
					<col width="20%" />
					<col width="80%" />
				</colgroup>
				<tr>
					<th>회원 가입시</th>
					<td>회원가입시 <input type="text" name="member_in_gen" id="member_in_gen" class="input_txt" style="width:100px;" value="<?= $point_row[member_in_gen]?>"  required="yes" message="회원가입시 적립포인트지급" is_num="yes"/> 포인트지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<!--<tr>
					<th>추천된 회원 지급포인트지급</th>
					<td>회원가입시 추천된 회원에게 <input type="text" name="member_chuchun_recev" id="member_chuchun_recev" class="input_txt" style="width:100px;" value="<?= $point_row[member_chuchun_recev]?>"  required="yes" message="추천된 회원에게 지급할 적립포인트지급" is_num="yes"/> 포인트지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>추천한 회원 지급포인트지급</th>
					<td>회원가입시 추천인을 입력한 회원에게 <input type="text" name="member_chuchun_send" id="member_chuchun_send" class="input_txt" style="width:100px;" value="<?= $point_row[member_chuchun_send]?>"  required="yes" message="추천한 회원에게 지급할 적립포인트지급" is_num="yes"/> 포인트지급 (지급하지 않을경우 0 입력)</td>
				</tr>	
				<tr>
					<th>상세회원 정보입력</th>
					<td>회원가입 후 마이페이지 개인정보 모두 입력시 <input type="text" name="member_special_add" id="member_special_add" class="input_txt" style="width:100px;" value="<?= $point_row[member_special_add]?>"  required="yes" message="상세회원 정보입력" is_num="yes"/> 포인트지급 (지급하지 않을경우 0 입력)</td>
				</tr>-->	
				
				<tr>
					<th>회원 로그인시</th>
					<td>회원 로그인시 <input type="text" name="member_login_gen" id="member_login_gen" class="input_txt" style="width:100px;" value="<?= $point_row[member_login_gen]?>"  required="yes" message="회원 로그인시 적립포인트지급" is_num="yes"/> 포인트지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				
				<!--<tr>
					<th>결제시</th>
					<td>결제시 결제금액 기준으로 <input type="text" name="payment_mile_gen" id="payment_mile_gen" class="input_txt" style="width:5%;" value="<?= $point_row[payment_mile_gen]?>"  required="yes" message="캐쉬 구매시 포인트지급 적립률" /> % 의 포인트지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>강사평 등록시</th>
					<td>강사평 등록시 <input type="text" name="review_mile" id="review_mile" class="input_txt" style="width:100px;" value="<?= $point_row[review_mile]?>"  required="yes" message="강사평 등록시 적립포인트지급" is_num="yes"/> 포인트지급 적립 (지급하지 않을경우 0 입력)</td>
				</tr>-->
		<?} elseif($point_sect == "ap"){ // 활동포인트일 경우 시작 ?>
				<colgroup>
					<col width="10%" />
					<col width="90%" />
				</colgroup>
				<tr>
					<th>글쓰기</th>
					<td>글쓰기 시 <input type="text" name="add_point_1" id="add_point_1" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_1]?>"  required="yes" message="글쓰기 지급 활동포인트" is_num="yes"/> 활동포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>댓글달기</th>
					<td>댓글달기 시 <input type="text" name="add_point_2" id="add_point_2" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_2]?>"  required="yes" message="댓글달기 지급 활동포인트" is_num="yes"/> 활동포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>추천받음</th>
					<td>추천받을 시 <input type="text" name="add_point_3" id="add_point_3" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_3]?>"  required="yes" message="추천받음 지급 활동포인트" is_num="yes"/> 활동포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>출석시</th>
					<td> 1 일 1 회 출석시 <input type="text" name="member_login_gen" id="member_login_gen" class="input_txt" style="width:100px;" value="<?= $point_row[member_login_gen]?>"  required="yes" message="출석시 적립활동포인트" is_num="yes"/> 활동포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>쇼핑시</th>
					<td>쇼핑시 결제금액 기준으로 <input type="text" name="payment_mile_gen" id="payment_mile_gen" class="input_txt" style="width:30px;" value="<?= $point_row[payment_mile_gen]?>"  required="yes" message="쇼핑시 활동포인트 적립률" /> % 의 활동포인트 지급 (지급하지 않을경우 0 입력) <font style="color:blue;">[EX : 1,000 원 당 10 포인트를 지급한다면 1% 를 입력한다]</font> </td>
				</tr>
				<tr>
					<th>회원인증</th>
					<td>회원인증 시 <input type="text" name="add_point_4" id="add_point_4" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_4]?>"  required="yes" message="회원인증 지급 활동포인트" is_num="yes"/> 활동포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>우수회원</th>
					<td>회원가입 후 우수회원으로 승격될때 <input type="text" name="member_special_add" id="member_special_add" class="input_txt" style="width:100px;" value="<?= $point_row[member_special_add]?>"  required="yes" message="우수회원 정보입력 지급활동포인트" is_num="yes"/> 활동포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>	
				<tr>
					<th>VIP 회원</th>
					<td>VIP 회원 승격시 <input type="text" name="add_point_5" id="add_point_5" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_5]?>"  required="yes" message="VIP 회원 활동포인트" is_num="yes"/> 활동포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>캐쉬로 구매시</th>
					<td>캐쉬를 이용하여 활동포인트 구매시 <input type="text" name="add_point_6" id="add_point_6" class="input_txt" style="width:30px;" value="<?= $point_row[add_point_6]?>"  required="yes" message="쇼핑시 활동포인트 적립률" /> % 로 포인트 구매 가능 (구매불가시 0 입력) <font style="color:blue;">[EX : 10 원 당 1 포인트를 구매할 수 있다면 10% 를 입력한다]</font> </td>
				</tr>
		<?} elseif($point_sect == "mp"){ // 매너포인트일 경우 시작 ?>
				<colgroup>
					<col width="10%" />
					<col width="90%" />
				</colgroup>
				<tr>
					<th>내 프로필 열람</th>
					<td>내 프로필 열람시마다 <input type="text" name="add_point_1" id="add_point_1" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_1]?>"  required="yes" message="내프로필열람 지급 매너포인트" is_num="yes"/> 매너포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>쪽지받음</th>
					<td>쪽지받음시마다 <input type="text" name="add_point_2" id="add_point_2" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_2]?>"  required="yes" message="쪽지받음 지급 매너포인트" is_num="yes"/> 매너포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>메일받음</th>
					<td>메일받음시마다 <input type="text" name="add_point_3" id="add_point_3" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_3]?>"  required="yes" message="메일받음 지급 매너포인트" is_num="yes"/> 매너포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>채팅 신청 받음</th>
					<td>채팅 신청 받음시마다 <input type="text" name="add_point_4" id="add_point_4" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_4]?>"  required="yes" message="채팅신청받음 지급 매너포인트" is_num="yes"/> 매너포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>선물받음</th>
					<td>선물받음시마다 <input type="text" name="add_point_5" id="add_point_5" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_5]?>"  required="yes" message="선물받음 지급 매너포인트" is_num="yes"/> 매너포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>박 받음</th>
					<td>박 <input type="text" name="add_point_6_1" id="add_point_6_1" class="input_txt" style="width:70px;" value="<?=$point_row[add_point_6_1]?>"  required="yes" message="박 기준갯수" is_num="yes"/> 개 당 <input type="text" name="add_point_6" id="add_point_6" class="input_txt" style="width:100px;" value="<?=$point_row[add_point_6]?>"  required="yes" message="박 받음 지급 매너포인트" is_num="yes"/> 매너포인트 지급 (지급하지 않을경우 0 입력)</td>
				</tr>
		<?} ?>

			</table>

		<?if($point_sect == "ap"){  ?>
		
		<div style="padding-top:10px;padding-bottom:10px;"><font style="color:blue;"><b>감소금액/비율 설정</b></font></div>

		<table class="t_view">
			<?if($point_sect == "ap"){ // 활동포인트일 경우 시작 ?>
				<colgroup>
					<col width="10%" />
					<col width="90%" />
				</colgroup>
				<tr>
					<th>쪽지보내기</th>
					<td>쪽지 보낼때 마다 <input type="text" name="minus_point_1" id="minus_point_1" class="input_txt" style="width:100px;" value="<?=$point_row[minus_point_1]?>"  required="yes" message="쪽지보내기 감소 활동포인트" is_num="yes"/> 활동포인트 감소 (감소하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>메일보내기</th>
					<td>메일 보낼따 마다 <input type="text" name="minus_point_2" id="minus_point_2" class="input_txt" style="width:100px;" value="<?=$point_row[minus_point_2]?>"  required="yes" message="메일보내기 감소 활동포인트" is_num="yes"/> 활동포인트 감소 (감소하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>채팅방 만들기</th>
					<td>채팅방 만들기 마다 <input type="text" name="minus_point_3" id="minus_point_3" class="input_txt" style="width:100px;" value="<?=$point_row[minus_point_3]?>"  required="yes" message="채팅방 만들기 감소 활동포인트" is_num="yes"/> 활동포인트 감소 (감소하지 않을경우 0 입력)</td>
				</tr>
				<tr>
					<th>채팅하기</th>
					<td>채팅할때 마다 <input type="text" name="minus_point_4" id="minus_point_4" class="input_txt" style="width:100px;" value="<?=$point_row[minus_point_4]?>"  required="yes" message="채팅하기 감소 활동포인트" is_num="yes"/> 활동포인트 감소 (감소하지 않을경우 0 입력)</td>
			  </tr>
			<?}?>
		</table>
		<?}?>

			</form>
			
			<div class="write_btn align_r">
				<a href="javascript:go_submit();" class="btn_blue">설정하기</a>
			</div>

		</div>
	</div>
</section>
<!-- //content -->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>