<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 공모전주
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&pageNo='.$pageNo;

$sql = "SELECT * FROM compet_info where 1 and idx = '".$idx."' and pstatus='com' and is_del = 'N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록된 공모전이 없습니다.');
	location.href =  "compet_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);
$bbs_code = "compet_info";
//if($row['member_idx'] != $_SESSION['manage_coinc_idx']) {
?>
<!--<SCRIPT LANGUAGE="JavaScript">
	
	alert('등록된 공모전가 없습니다.');
	location.href =  "compet_list.php?<?=$total_param?>";
	//
</SCRIPT>-->
<?
//exit;
//}
?>
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
						<li>공모전 수정</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>공모전정보 수정</h3>
				</div>
				<div class="write">

				<form name="frm" action="compet_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="idx" id="compet_idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<table>
						<caption>공모전정보 수정</caption>
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
						<td colspan="3"><?=get_code_list_radio("and type='section' and cate_level='1'","cate_code1","cate_name1","com_section","yes","업종",$row['idx'])?></td>
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
					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='compet_info' and board_code = 'docu' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 6){
							$cnt_file = 6;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="dfile_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="dfile_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="dfile_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
						
						<tr>
							<th>참고자료</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="참고자료" name="docu_<?=$i_file?>">
								<?if($row_file['file_chg']){?>
									<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=compet_info"><?=$row_file['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="ddel_org_<?=$i_file?>" value="Y">)
								<?} else{ ?>
									<input type="hidden" name="ddel_org_<?=$i_file?>" value="">
								<?}?>
							</td>
						</tr>
					<?}?>	
					<tr>
						<th scope="row">기본옵션</th>
						<td colspan="3"><?=get_code_list_radio("and type='basic' and cate_level='1'","cate_code1","cate_name1","com_basic","no","기본옵션",$row['idx'])?></td>
					</tr>
					<tr>
						<th scope="row">패키지 옵션</th>
						<td colspan="3">
							<input type="radio" name="com_package" value="premium" required="no"  message="패키지 옵션" id="com_package_0" <?=$row['com_package']=="premium"?"checked":""?>> <label for="com_package_0">프리미엄</label>
							<input type="radio" name="com_package" value="gold" required="no"  message="패키지 옵션" id="com_package_1" <?=$row['com_package']=="gold"?"checked":""?>> <label for="com_package_1">골드</label>
							<input type="radio" name="com_package" value="silver" required="no"  message="패키지 옵션" id="com_package_2" <?=$row['com_package']=="silver"?"checked":""?>> <label for="com_package_2">실버</label>
							<input type="radio" name="com_package" value="bronze" required="no"  message="패키지 옵션" id="com_package_3" <?=$row['com_package']=="bronze"?"checked":""?>> <label for="com_package_3">브론즈</label>
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
							<input type="radio" name="compet_second_ok" value="Y" required="yes"  message="2,3 위 추가선정" id="compet_second_ok_0" onclick="set_second_ok('Y');" <?=$row['compet_second_ok']=="Y"?"checked":""?>> <label for="compet_second_ok_0">추가선정 하겠습니다.</label>
							<input type="radio" name="compet_second_ok" value="N" required="yes"  message="2,3 위 추가선정" id="compet_second_ok_1" onclick="set_second_ok('N');" <?=$row['compet_second_ok']=="N"?"checked":""?>> <label for="compet_second_ok_1">추가선정 하지 않겠습니다.</label>
						</td>
					</tr>

					<tr id="price_2_area" style="display:<?=$row['compet_second_ok']=="Y"?"":"none"?>;">
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
						<td colspan="3"><?=get_code_list_radio("and type='period' and cate_level='1'","cate_code1","cate_name1","com_period","yes","공모전 진행기간",$row['idx'])?></td>
					</tr>

					<tr>
						<th scope="row">노출옵션</th>
						<td colspan="3"><?=get_code_list_checkb("and type='display' and cate_level='1'","cate_code1","cate_name1","com_display","yes","노출옵션",$row['idx'])?></td>
					</tr>
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">정보수정</button>
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
		location.href = "compet_view.php?idx=<?=$idx?>&<?=$total_param?>";
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
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>