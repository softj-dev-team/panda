<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']); 
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

if($v_sect == "SEL"){
	$member_sect_str = "셀러";
} elseif($v_sect == "BUY"){
	$member_sect_str = "바이어";
}

?>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">

function go_submit() {
		
		var check = chkFrm('frm');
		if(check) {
			
			if (document.frm.id_ok.value != "Y"){
				alert('아이디 중복검색을 해주세요.');
				return;	
			}

			if(fnCheckId(document.frm.member_password.value,"비밀번호")){
				if (document.frm.member_password.value != document.frm.member_password2.value){
					alert('비밀번호와 비밀번호 확인이 맞지 않습니다 ! ');
					return;	
				}
				frm.submit();
			} else {
				return;
			}

		} else {
			false;
		}
}

function vender_num(num1,num2,num3){
	//alert(num1);
	var num=(num1 + num2 + num3) 

	var w_c,w_e,w_f,w_tot
	w_c=num.charAt(8)*5       
	w_e=parseInt((w_c/10),10) 
	w_f=w_c % 10              
	w_tot=num.charAt(0)*1
	w_tot+=num.charAt(1)*3 
	w_tot+=num.charAt(2)*7
	w_tot+=num.charAt(3)*1 
	w_tot+=num.charAt(4)*3 
	w_tot+=num.charAt(5)*7 
	w_tot+=num.charAt(6)*1 
	w_tot+=num.charAt(7)*3 
	w_tot+=num.charAt(9)*1 
	w_tot+=(w_e+w_f)		 
	if (!(w_tot % 10)) 
	 {
		return(true);
	 }
	  else
	 {
	  alert("사업자 등록 번호가 규격에 맞지 않습니다.")
		return(false);
	 }  
}

function ch_id(){
	var chkid = $("#member_id").val();
	if(chkid == ""){
		alert("아이디를 입력하세요.");
		$("#member_id").focus();
		return;
	}
	if (chkid.length !=chkid.replace(/[^a-zA-Z0-9]/gi, "").length ){
		alert("사용자 아이디는 영문과 숫자로만 작성하십시오.");
		$("#member_id").focus();
		return;
	}
	
	var vurl = "/pro_inc/check_id_duple.php";
	$.ajax({
		url		: vurl,
		type	: "GET",
		data	: { idx:"", user_id:$("#member_id").val() },
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

function ch_nick(){
	var chknick = $("#user_nick").val();
	if(chknick == ""){
		alert("닉네임을 입력하세요.");
		$("#user_nick").focus();
		return;
	}
	/*if (chknick.length !=chknick.replace(/[^a-zA-Z0-9]/gi, "").length ){
		alert("사용자 아이디는 영문과 숫자로만 작성하십시오.");
		$("#member_nick").focus();
		return;
	}*/
	
	var vurl = "/pro_inc/check_nick_duple.php";
	$.ajax({
		url		: vurl,
		type	: "GET",
		data	: { idx:"", user_nick:$("#user_nick").val() },
		async	: false,
		dataType	: "json",
		success		: function(v){
			if ( v.success == "true" ){
				$("#nick_ok").val("Y");
				$("#check_nick").html( v.msg );
			} else if ( v.success == "false" ){
				$("#nick_ok").val("N");
				$("#check_nick").html( v.msg );
			} else {
				alert( "오류 발생!" );
			}
		}
	});
}

function openDaumPostcode() {
   new daum.Postcode({
   oncomplete: function(data) {
   // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
   // 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
  document.getElementById('zip_code1').value = data.zonecode;
   //document.getElementById('zip_code2').value = data.postcode2;
   document.getElementById('member_address').value = data.address;
   document.getElementById('member_address2').focus();
   }
   }).open();
}

//숫자,영문 조합검사
function fnCheckId(uid,str){
	if(!/^[a-z0-9]{6,12}$/.test(uid)) { 
		alert(str+'는 숫자와 영(소)문자 조합으로 6~12자리를 사용해야 합니다.'); 
		return false;
	}
  
	var chk_num = uid.search(/[0-9]/g); 
	var chk_eng = uid.search(/[a-z]/ig); 

	if(chk_num < 0 || chk_eng < 0){ 
		alert(str+'는 숫자와 영문자를 혼용하여야 합니다.'); 
		return false;
	}
    
	if(/(\w)\1\1\1/.test(uid)){
		alert(str+'에 같은 문자를 4번 이상 사용하실 수 없습니다.'); 
		return false;
	}
	return true;
}

function go_list(){
		location.href = "member_list.php?<?=$total_param?>&pageNo=<?=$pageNo?>";
}

function user_type_select(){
	if($("#user_type_2").prop("checked") == true) {
		$("#sect_user_level").css("display","");
		$("#sect_area_sect_idx").css("display","");
	} else {
		$("#sect_user_level").css("display","none");
		$("#sect_area_sect_idx").css("display","none");
	}
}

function go_area_pop(){
	window.open("area_list_pop.php","areaview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
}

function cate_sel_1(z){
	var tmp = z.options[z.selectedIndex].value; 
	//alert(tmp);
	_fra_admin.location.href="cate_select_3.php?cate_code1="+tmp+"&fm=frm&fname=gugun";
}
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/member_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>가맹점 관리</li>
						<li>가맹점 등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>가맹점 등록</h3>
				</div>
				<div class="modify">

				<form name="frm" action="member_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="id_ok" id="id_ok" value=""/>
					<input type="hidden" name="nick_ok" id="nick_ok" value=""/>
					<input type="hidden" name="member_type" id="member_type" value="<?=$v_sect?>"/>
					<input type="hidden" name="member_gubun" id="member_gubun" value="<?=$s_gubun?>"/>
					<table>
						<caption>가맹점 등록</caption>
						<colgroup>
							<col style="width:10%">
							<col style="width:40%">
							<col style="width:10%">
							<col style="width:40%">
						</colgroup>
						<tr>
							<th scope="row">아이디</th>
							<td colspan="3">
								<input type="text" style="width:10%;" name="member_id" id="member_id" required="yes"  message="아이디"/>&nbsp;<a href="javascript:ch_id();" class="btn_red">중복확인</a>
								<div id="check_id" style="paddig-top:10px;"></div>
							</td>
						</tr>
						<tr>
							<th scope="row">비밀번호</th>
							<td>
								<input type="password" maxlength="16" name="member_password" required="yes"  message="비밀번호" style="width:30%; ime-mode:disabled"> <span style="display:inline-block; padding-top:4px;">(영문과 숫자조합으로 6-12자 사이) </span>
							</td>
							<th scope="row">비밀번호 확인</th>
							<td>
								<input type="password" maxlength="16" name="member_password2" required="yes"  message="비밀번호 확인" style="width:30%; ime-mode:disabled">
							</td>
						</tr>
						<tr>
							<th scope="row">가맹점명</th>
							<td>
								<input type="text" style="width:30%;" name="com_name" required="yes"  message="가맹점명">
							</td>
							<th scope="row">점주님명</th>
							<td>
								<input type="text" style="width:30%;" name="presi_name" required="yes"  message="점주님명">
							</td>
						</tr>
						<tr>
							<th scope="row">우편번호</th>
							<td colspan="3">
								<!--<input type="radio" name="addr_sect" value="H" required="no"  message="주소구분"/> 자택 
								<input type="radio" name="addr_sect" value="J" required="no"  message="주소구분"/> 직장-->
								<input type="hidden" name="addr_sect" value="H"/>
								<input type="text" style="width:10%;" name="zip_code1" id="zip_code1" readonly required="no"  message="우편번호" is_num="yes"><!--<input type="text" style="width:50px;" name="zip_code2" readonly required="no"  message="우편번호" is_num="yes">-->&nbsp;<a href="javascript:openDaumPostcode();" class="btn_green">우편번호 찾기</a>
							</td>
						</tr>
						<tr>
							<th scope="row">주 소</th>
							<td colspan="3">
								<input type="text" style="width:30%;" name="member_address" id="member_address" required="no"  message="주소" >
							</td>
						</tr>
						<tr>
							<th scope="row">상세주소</th>
							<td colspan="3">
								 <input type="text" style="width:30%;" name="member_address2" id="member_address2" required="no"  message="상세주소" >
							</td>
						</tr>
						<tr>
							<th scope="row">이메일</th>
							<td colspan="3">
								 <input type="text" style="width:30%;" name="member_email" required="yes"  message="이메일" is_email="yes">
							</td>
						</tr>
						<tr>
							<th scope="row">대표전화</th>
							<td>
								<input type="text" style="width:10%;" name="com_tel1" required="no"  size="3" maxlength="3" message="대표전화1" is_num="yes" value="">-<input type="text" style="width:10%;" name="com_tel2" required="no" size="4" maxlength="4" message="대표전화2" is_num="yes" value="">-<input type="text" style="width:10%;" name="com_tel3" required="no" size="4" maxlength="4" message="대표전화3" is_num="yes" value="">
							</td>
							<th scope="row">휴대전화</th>
							<td>
								<input type="text" style="width:10%;" name="cell1" required="yes"  size="3" maxlength="3" message="휴대전화1" is_num="yes" value="">-<input type="text" style="width:10%;" name="cell2" required="yes" size="4" maxlength="4" message="휴대전화2" is_num="yes" value="">-<input type="text" style="width:10%;" name="cell3" required="yes" size="4" maxlength="4" message="휴대전화3" is_num="yes" value="">
							</td>
						</tr>
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_list">목록</a>
						<button class="btn_modify" onclick="go_submit();">등록</button>
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