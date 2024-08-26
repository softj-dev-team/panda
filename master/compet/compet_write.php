<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/compet_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>공모전 관리</li>
						<li>공모전 등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>공모전 등록</h3>
				</div>
				<div class="write">

				<form name="frm" action="compet_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="write_method" id="write_method" value="manual"/>
					<table>
						<caption>공모전 수동등록</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<tr>
						<th>의뢰자회원</th>
						<td colspan="3">
							<select name="member_idx" size="1" style="vertical-align:middle;" required="yes" message="의뢰자회원" onchange="select_mem_value(this)">
								<option value="">의뢰자회원 선택</option>
								<?
								$sub_sql = "select idx,user_name,user_id from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='PAT' order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$row['member_idx']==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">의뢰자 성명</th>
						<td><input type="text" style="width:80%;" name="member_name" id="member_name" required="yes" message="의뢰자 성명" value="<?=$row['member_name']?>"></td>
						<th scope="row">의뢰자 연락처</th>
						<td><input type="text" style="width:60%;" name="member_cell" id="member_cell" required="yes" message="의뢰자 연락처" is_num="yes" value="<?=$row['member_cell']?>"> - 제외하고 입력</td>
					</tr>
					<tr>
						<th scope="row">의뢰자 이메일</th>
						<td colspan="3"><input type="text" style="width:80%;" name="member_email" id="member_email" required="yes" message="의뢰자 이메일" is_email="yes" value="<?=$row['member_email']?>"></td>
					</tr>
					<tr>
						<th scope="row">회사 및 단체명</th>
						<td colspan="3"><input type="text" style="width:50%;" name="com_name" id="com_name" required="yes" message="회사 및 단체명" value="<?=$row['com_name']?>"></td>
					</tr>
					<tr>
						<th scope="row">회사 및 단체 소개</th>
						<td colspan="3">
							<textarea style="width:90%;height:80px;" name="com_info" id="com_info" required="yes" message="회사 및 단체소개" value=""><?=$row['com_info']?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">업종선택</th>
						<td colspan="3"><?=get_code_list_radio("and type='section' and cate_level='1'","cate_code1","cate_name1","com_section","yes","업종")?></td>
					</tr>
					<tr>
						<th scope="row">공모전 제목</th>
						<td colspan="3"><input type="text" style="width:50%;" name="compet_title" id="compet_title" required="yes" message="공모전 제목" value="<?=$row['compet_title']?>"></td>
					</tr>
					<tr>
						<th scope="row">공모전 기간</th>
						<td colspan="3"><input type="text" style="width:30%;" name="compet_sdate" id="compet_sdate" class="datepicker" readonly required="yes" message="공모전 시작일" value="<?=$row['compet_sdate']?>"> ~ <input type="text" style="width:30%;" name="compet_edate" id="compet_edate" class="datepicker" readonly required="no" message="공모전 종료일" value="<?=$row['compet_edate']?>"></td>
					</tr>
					<tr>
						<th scope="row">공모전 상세정보</th>
						<td colspan="3">
							<textarea style="width:90%;height:80px;" name="compet_detail" id="compet_detail" required="no" message="공모전 상세정보" value=""><?=$row['compet_detail']?></textarea>
						</td>
					</tr>
				<?for($i=0; $i<6; $i++){?>
					<tr>
						<th scope="row">참고자료 <?=($i+1)?></th>
						<td colspan="3">
							<input type="file" style="width:400px;" required="no" message="참고자료" name="docu_<?=$i?>" id="docu_<?=$i?>"> 
						</td>
					</tr>
				<?}?>
					<tr>
						<th scope="row">기본옵션</th>
						<td colspan="3"><?=get_code_list_radio("and type='basic' and cate_level='1'","cate_code1","cate_name1","com_basic","no","기본옵션")?></td>
					</tr>
					<tr>
						<th scope="row">패키지 옵션</th>
						<td colspan="3">
							<input type="radio" name="com_package" value="premium" required="no"  message="패키지 옵션" id="com_package_0"> <label for="com_package_0">프리미엄</label>
							<input type="radio" name="com_package" value="gold" required="no"  message="패키지 옵션" id="com_package_1"> <label for="com_package_1">골드</label>
							<input type="radio" name="com_package" value="silver" required="no"  message="패키지 옵션" id="com_package_2"> <label for="com_package_2">실버</label>
							<input type="radio" name="com_package" value="bronze" required="no"  message="패키지 옵션" id="com_package_3"> <label for="com_package_3">브론즈</label>
						</td>
					</tr>
					<tr>
						<th scope="row">1위 상금</th>
						<td colspan="3">
							<input type="text" style="width:30%;" name="compet_first_price" id="compet_first_price" required="yes" message="1위 상금" is_num="yes" value="<?=$row['compet_first_price']?>"> , 제외하고 입력
						</td>
					</tr>
					<tr>
						<th scope="row">2,3 위 추가선정</th>
						<td colspan="3">
							<input type="radio" name="compet_second_ok" value="Y" required="yes"  message="2,3 위 추가선정" id="compet_second_ok_0" onclick="set_second_ok('Y');"> <label for="compet_second_ok_0">추가선정 하겠습니다.</label>
							<input type="radio" name="compet_second_ok" value="N" required="yes"  message="2,3 위 추가선정" id="compet_second_ok_1" onclick="set_second_ok('N');"> <label for="compet_second_ok_1">추가선정 하지 않겠습니다.</label>
						</td>
					</tr>

					<tr id="price_2_area" style="display:none;">
						<th scope="row">2위 상금</th>
						<td>
							<input type="text" style="width:60%;" name="compet_second_price" id="compet_second_price" required="no" message="2위 상금" is_num="yes" value="<?=$row['compet_second_price']?>"> , 제외하고 입력
						</td>
						<th scope="row">3위 상금</th>
						<td>
							<input type="text" style="width:60%;" name="compet_third_price" id="compet_third_price" required="no" message="3위 상금" is_num="yes" value="<?=$row['compet_third_price']?>"> , 제외하고 입력
						</td>
					</tr>

					<tr>
						<th scope="row">공모전 진행기간</th>
						<td colspan="3"><?=get_code_list_radio("and type='period' and cate_level='1'","cate_code1","cate_name1","com_period","yes","공모전 진행기간")?></td>
					</tr>

					<tr>
						<th scope="row">노출옵션</th>
						<td colspan="3"><?=get_code_list_checkb("and type='display' and cate_level='1'","cate_code1","cate_name1","com_display","yes","노출옵션")?></td>
					</tr>
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:frm.reset();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">등록</button>
					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript">
$(function() {
	$(".datepicker").datepicker({
		changeYear:true,
		changeMonth:true,
		minDate: '-90y',
		yearRange: 'c-90:c',
		dateFormat:'yy-mm-dd',
		showMonthAfterYear:true,
		constrainInput: true,
		dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
	});
});

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}
	
function go_list(){
	location.href = "compet_list.php";
}

function set_second_ok(str){
	if(str == "Y"){
		$("#price_2_area").show();
	} else if(str == "N"){
		$("#price_2_area").hide();
		$("#compet_second_price").val("");
		$("#compet_third_price").val("");
	}
}

function select_mem_value(z){
	var tmp = z.options[z.selectedIndex].value; 
	//alert(tmp);
	_fra_admin.location.href="select_mem_value.php?member_idx="+tmp+"";
}
//-->
</script>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>