<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu;

?>

<script type="text/javascript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {

			if (document.frm.id_ok.value != "Y"){
				alert('아이디 중복체크를 해주세요 ! ');
				return;	
			}

			frm.submit();
		} else {
			false;
		}
	}
	
	function go_list(){
		location.href = "adminm_list.php?<?=$total_param?>";
	}

	 function user_level(z){
			
		var tmp = z.options[z.selectedIndex].value; 
		
			/*if(tmp == "3"){
			level_text1.style.display = 'block';
			} else {
			level_text1.style.display = 'none';
			}*/
	}

	function emailChg(){
	frm.email2.value = frm.email_select.value;
	}

	function ch_id(){
	var chkid = $("#user_id").val();
	if(chkid == ""){
		alert("아이디를 입력하세요.");
		$("#user_id").focus();
		return;
	}
	/*if (chkid.length !=chkid.replace(/[^a-zA-Z0-9]/gi, "").length ){
		alert("사용자 아이디는 영문과 숫자로만 작성하십시오.");
		$("#member_id").focus();
		return;
	}*/
	
	var vurl = "/pro_inc/check_id_duple.php";
	$.ajax({
		url		: vurl,
		type	: "GET",
		data	: { idx:"", user_id:$("#user_id").val(), type:"AD" },
		async	: false,
		dataType	: "json",
		success		: function(v){
			if ( v.success == "true" ){
				$("#id_ok").val("Y");
				$("#check_id").html( v.msg );
			} else if ( v.success == "false" ){
				$("#id_ok").val("N");
				$("#check_id").html( v.msg );
			} else {
				alert( "오류 발생!" );
			}
		}
	});
}
	
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
			<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 관리</li>
						<li>관리자 정보 등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>관리자 정보 등록</h3>
				</div>
				<div class="write">

				<form name="frm" action="adminm_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="id_ok" id="id_ok" value=""/>			
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<!--<tr>
						<th >관리레벨</th>
						<td >
						<select name="user_level"  required="yes" message="관리레벨">
						<option value="">선택하세요</option>
						<option value="004">전체관리자</option>
						<option value="005">육아용품 관리자</option>
						</select>
						</td>
					</tr>-->
					
					<tr>
						<th scope="row">운영자 ID</th>
						<td colspan="3"><input type="text" style="width:100px" name="user_id" id="user_id" required="yes" message="아이디" is_engnum = "yes" > &nbsp; <a href="javascript:ch_id();" class="btn_green">중복검색</a> 
							<div id="check_id" style="paddig-top:10px;"></div>
						</td>
					</tr>
					<tr>
						<th >운영자 비밀번호</th>
						<td colspan="3"><input type="password" style="width:150px;" name="user_pwd" required="yes" message="비밀번호" is_engnum = "yes"></td>
					</tr>
					<tr>
						<th >운영자 성명</th>
						<td colspan="3"><input type="text" style="width:100px;" name="user_name" required="yes" message="성 명" ></td>
					</tr>
					
					<tr>
						<th >연락처</th>
						<td colspan="3"><input type="text" style="width:50;" name="cell1" required="no"  size="3" maxlength="3" message="연락처1" is_num="yes" value="">-<input type="text" style="width:50;" name="cell2" required="no" size="4" maxlength="4" message="연락처2" is_num="yes" value="">-<input type="text" style="width:50;" name="cell3" required="no" size="4" maxlength="4" message="연락처3" is_num="yes" value=""></td>
					</tr>

					<tr>
						<th >이메일</th>
						<td colspan="3">
						<input type="text"  style="width:120px;" name="email1" required="no" message="이메일계정">&nbsp;
							@&nbsp;<input type="text"  style="width:120px;" name="email2" required="no" message="이메일주소"> 
							<select name="email_select" id="email_select" onchange='javascript:emailChg();'>
								<option value="">직접입력</option>
								<option value="paran.com"  >paran.com</option>
								<option value="chollian.net" >chollian.net</option>
								<option value="empal.com" >empal.com</option>
								<option value="freechal.com" >freechal.com</option>
								<option value="hotmail.com"	 >hotmail.com</option>
								<option value="lycos.co.kr" >lycos.co.kr</option>
								<option value="korea.com" >korea.com</option>
								<option value="nate.com" >nate.com</option>
								<option value="naver.com" >naver.com</option>
								<option value="netian.com" >netian.com</option>
								<option value="unitel.co.kr" >unitel.co.kr</option>
								<option value="yahoo.co.kr"	 >yahoo.co.kr</option>
								<option value="hanmail.net" >hanmail.net</option>
								<option value="daum.net" >daum.net</option>
							</select>
						</td>
					</tr>
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_gray">목록보기</a>
						<a href="javascript:go_submit();" class="btn_blue">등록하기</a>
					</div>
				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>