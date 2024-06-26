<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
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

?>

<script type="text/javascript">

function go_submit() {
		
		var check = chkFrm('frm');
		if(check) {
			
			if (document.frm.id_ok.value != "Y"){
				alert('아이디 중복검사를 해주세요.');
				return;	
			}

			/*if (document.frm.nick_ok.value != "Y"){
				alert('닉네임 중복검사를 해주세요.');
				return;	
			}*/

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
		alert("아이디는 영문과 숫자로만 작성하십시오.");
		$("#member_id").focus();
		return;
	}
	/*if(!emailCheck(chkid)){
		alert("이메일 형식이 올바르지 않습니다.");
		$("#member_id").focus();
		return;
	}*/
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

function ch_email(){
	var chkemail = $("#member_email").val();
	if(chkemail == ""){
		alert("이메일을 입력하세요.");
		$("#member_email").focus();
		return;
	}
	/*if (chkemail.length !=chkemail.replace(/[^a-zA-Z0-9]/gi, "").length ){
		alert("아이디는 영문과 숫자로만 작성하십시오.");
		$("#member_email").focus();
		return;
	}*/
	if(!emailCheck(chkemail)){
		alert("이메일 형식이 올바르지 않습니다.");
		$("#member_email").focus();
		return;
	}
	var vurl = "/pro_inc/check_email_duple.php";
	$.ajax({
		url		: vurl,
		type	: "GET",
		data	: {  idx:"", user_email:$("#member_email").val() },
		async	: false,
		dataType	: "json",
		success		: function(v){
			if ( v.success == "true" ){
				$("#email_ok").val("Y");
				$("#check_email").html( v.msg );
			} else if ( v.success == "false" ){
				$("#email_ok").val("N");
				$("#check_email").html( v.msg );
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
	//alert(chknick);
	var vurl = "/pro_inc/check_nick_duple.php";
	$.ajax({
		url		: vurl,
		type	: "GET",
		data	: { idx:"", user_nick:$("#user_nick").val() },
		async	: false,
		dataType	: "json",
		success		: function(v){
			//alert(v.success);
			//alert(v.msg);
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

function cate_code_1(idx){
	$("#cate1_code_in").val(idx);
	get_data('cate_code_2.php', 'cate_code_2_area', 'cate_code1='+idx+'');
	get_data('cate_code_3.php', 'cate_code_3_area', 'cate_code1='+idx+'');
}

function cate_code_2(idx){
	var cate_code1 = $("#cate1_code_in").val(); 
	get_data('cate_code_3.php', 'cate_code_3_area', 'cate_code1='+cate_code1+'&cate_code2='+idx+'');
}

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
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/partner_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>가맹점관리</li>
						<li>가맹점등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>가맹점 등록</h3>
				</div>
				<!-- 네비게이션 종료 -->
				
				<form name="frm" action="member_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
					<input type="hidden" name="smenu" value="<?=$smenu?>"/>
					<input type="hidden" name="id_ok" id="id_ok" value=""/>
					<input type="hidden" name="email_ok" id="email_ok" value=""/>
					<input type="hidden" name="nick_ok" id="nick_ok" value=""/>
					<input type="hidden" name="member_type" id="member_type" value="<?=$v_sect?>"/>
					<input type="hidden" name="cate1_code_in" id="cate1_code_in" value=""/>

					<div class="write">
						<p class="tit">사업자 정보</p>
						<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<tr>
							<th scope="row"> 아이디</th>
							<td colspan="3">
								<input type="text" style="width:30%;" name="member_id" id="member_id" required="yes" message="아이디"/>&nbsp;<a href="javascript:ch_id();" class="btn_green">중복확인</a>
								<div id="check_id" style="paddig-top:10px;"></div>
							</td>
						</tr>
						<tr>
							<th scope="row"> 비밀번호</th>
							<td>
								<input type="password" maxlength="16" name="member_password" required="yes"  message="비밀번호" style="width:80%; ime-mode:disabled"> <span style="display:inline-block; padding-top:4px;">(영문과 숫자조합으로 6-12자 사이) </span>
							</td>
							<th scope="row"> 비밀번호 확인</th>
							<td>
								<input type="password" maxlength="16" name="member_password2" required="yes"  message="비밀번호 확인" style="width:80%; ime-mode:disabled">
							</td>
						</tr>

						<tr>
							<th scope="row">대표자명</th>
							<td>
								<input type="text" style="width:80%;" name="com_chair" id="com_chair" required="no" message="대표자명">
							</td>
							<th scope="row">회사명</th>
							<td>
								<input type="text" style="width:90%;" name="com_name" id="com_name" required="no" message="회사명">
							</td>
						</tr>
						<tr>
							<th scope="row">개별도메인</th>
							<td colspan="3">
								<input type="text" style="width:30%;" name="com_homep" id="com_homep" required="no" message="개별도메인">
							</td>
						</tr>
						<tr>
							<th scope="row">사업자번호</th>
							<td>
								<input type="text" style="width:80%;" name="com_num_1" id="com_num_1" required="no" message="사업자번호">
							</td>
							<th scope="row">통신판매업신고번호</th>
							<td>
								<input type="text" style="width:80%;" name="com_num_2" id="com_num_2" required="no" message="통신판매업신고번호">
							</td>
						</tr>
						<tr>
							<th scope="row">업태</th>
							<td>
								<input type="text" style="width:80%;" name="com_uptae" id="com_uptae" required="no" message="업태">
							</td>
							<th scope="row">종목</th>
							<td>
								<input type="text" style="width:80%;" name="com_jong" id="com_jong" required="no" message="종목">
							</td>
						</tr>
						<tr>
							<th scope="row">대표 전화번호</th>
							<td><input type="text" style="width:80%;" name="com_tel_1" id="com_tel_1" required="no" message="대표 전화번호"></td>
							<th scope="row">Fax</th>
							<td><input type="text" style="width:80%;" name="com_fax" id="com_fax" required="no" message="Fax"></td>
						</tr>
						<tr class="address">
								<th scope="col">주소</th>
								<td scope="col" colspan="3">
								<p>
									<input type="text" name="com_zip_code1" id="com_zip_code1" value="<?=$row[post]?>" readonly required="no"  message="우편번호" is_num="yes">
									<a href="javascript:execDaumPostcode('com_zip_code1', 'com_member_address', 'com_member_address2');" class="btn_green">우편번호검색</a>
								</p>
								<!-- 우편번호 레이어 시작 -->
								<div id="post_wrap_com_zip_code1" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
									<div><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1;width:30px;" onclick="foldDaumPostcode('com_zip_code1')" alt="접기 버튼"></div>
								</div>
								<!-- 우편번호 레이어 종료 -->
								<p>
									<input type="text" name="com_member_address" id="com_member_address" value="<?=$row[addr1]?>" style="width:50%;" required="no"  message="기본주소">
									<span class="info">기본주소</span>
								</p>
								<p>
									<input type="text" name="com_member_address2" id="com_member_address2" value="<?=$row[addr2]?>" style="width:50%;" required="no"  message="상세주소">
									<span class="info">상세주소</span>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row">사업자등록증</th>
							<td colspan="3">
							<?
								$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='member_info_company' and board_code='com_num_1' and board_idx='".$row['company_idx']."' order by idx asc";
								$query_file = mysqli_query($gconnet,$sql_file);
								$cnt_file = mysqli_num_rows($query_file);

								if($cnt_file < 1){
									$cnt_file = 1;
								}
										
								for($i_file=0; $i_file<$cnt_file; $i_file++){
									$row_file = mysqli_fetch_array($query_file);
									$k_file = $i_file+1;
							?>
									<input type="hidden" name="file_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
									<input type="hidden" name="file_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
									<input type="hidden" name="file_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />

									<div <?if($i_file > 0){?>style="margin-top:10px;"<?}?>>
										<input type="file" style="width:50%;" required="no" message="첨부파일" name="file_<?=$i_file?>" id="file_<?=$i_file?>">
										<?if($row_file['file_chg']){?>
											기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=certi"><?=$row_file['file_org']?></a>
											(기존파일 삭제 : <input type="checkbox" name="del_org_<?=$i_file?>" value="Y">)
										<?} else{ ?>
											<input type="hidden" name="del_org_<?=$i_file?>" value="">
										<?}?>
									</div>
							<?}?>
							</td>
						</tr>
						</table>

						<p class="tit">담당자 정보</p>
						<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<tr>
							<th scope="row">담당자 이름</th>
							<td>
								<input type="text" style="width:80%;" name="member_name" id="member_name" required="no" message="담당자 이름">
							</td>
							<th scope="row">담당자 핸드폰</th>
							<td>
								<input type="text" style="width:90%;" name="cell" id="join_member_cell" onblur="set_cell_num('join_member_cell');" required="no" message="담당자 핸드폰">
							</td>
						</tr>
						<tr>
							<th scope="row">담당자 이메일</th>
							<td colspan="3">
								<input type="text" style="width:60%;" name="member_email" id="member_email" required="no" message="담당자 이메일" is_email="yes"><!--&nbsp;<a href="javascript:ch_email();" class="btn_green">중복확인</a>
								<div id="check_email" style="paddig-top:10px;"></div>-->
							</td>
						</tr>
						<tr>
							<th scope="row">담당자 신분증</th>
							<td colspan="3">
							<?
								$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='member_info' and board_code='identi' and board_idx='".$row['idx']."' order by idx asc";
								$query_file = mysqli_query($gconnet,$sql_file);
								$cnt_file = mysqli_num_rows($query_file);

								if($cnt_file < 1){
									$cnt_file = 1;
								}
										
								for($i_file=0; $i_file<$cnt_file; $i_file++){
									$row_file = mysqli_fetch_array($query_file);
									$k_file = $i_file+1;
							?>
									<input type="hidden" name="file2_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
									<input type="hidden" name="file2_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
									<input type="hidden" name="file2_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />

									<div <?if($i_file > 0){?>style="margin-top:10px;"<?}?>>
										<input type="file" style="width:50%;" required="no" message="첨부파일" name="file2_<?=$i_file?>" id="file2_<?=$i_file?>">
										<?if($row_file['file_chg']){?>
											기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=certi"><?=$row_file['file_org']?></a>
											(기존파일 삭제 : <input type="checkbox" name="del_org2_<?=$i_file?>" value="Y">)
										<?} else{ ?>
											<input type="hidden" name="del_org2_<?=$i_file?>" value="">
										<?}?>
									</div>
							<?}?>
							</td>
						</tr>
						</table>
				
					</form>

					<div class="write_btn align_r mt35">
						<button class="btn_modify" onclick="go_submit();">저장</button>
						<a href="javascript:go_list();" class="btn_list">목록</a>
						<!--<button class="btn_del">취소</button>-->
					</div>
				</div>
			</div>
		</div>

<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
function foldDaumPostcode(zip) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

    function execDaumPostcode(zip,ad1,ad2) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
		// 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

              document.getElementById(''+zip+'').value = data.zonecode;
				  //document.getElementById('zip_code2').value = data.postcode2;
				document.getElementById(''+ad1+'').value = fullAddr;
				 document.getElementById(''+ad2+'').focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
    }
</script>

<script>
function set_cell_num(target){
	var cell_inp = $("#"+target+"").val();
	//alert(cell_inp);
	var cell_ninp = cell_inp.replace(/\-/g,"");
	//alert(cell_ninp);
	$("#"+target+"").val(cell_ninp);
}

var count_2 = 1;          

function addForm_2(){
	var addedFormDiv = document.getElementById("addedFormDiv_2");
	var str = "";
	
	str+='<span class="marr5 mnw50 dib">발신번호 : </span> <input type="text" placeholder="" name="call_num[]" maxlength="13">&nbsp;<span class="marr5 marl20">메모 : </span> <input type="text" placeholder="" name="call_memo[]" size="50">&nbsp;<span class="marr5 marl20">상태 : </span> <select name="use_yn[]"><option value="">선택하세요</option><option value="Y">사용가능</option><option value="N">사용불가</option></select>';

	var addedDiv = document.createElement("div"); // 폼 생성
    addedDiv.id = "added_2_"+count_2; // 폼 Div에 ID 부 여 (삭제를 위해)
    addedDiv.innerHTML  = str; // 폼 Div안에 HTML삽입
    addedFormDiv.appendChild(addedDiv); // 삽입할 DIV에 생성한 폼 삽입
    count_2++;
}

function delForm_2(){
  var addedFormDiv = document.getElementById("addedFormDiv_2");
   if(count_2 >1){ // 현재 폼이 두개 이상이면
      var addedDiv = document.getElementById("added_2_"+(--count_2));
         addedFormDiv.removeChild(addedDiv); // 폼 삭제 
    }else{ // 마 지막 폼만 남아있다면
      //  document.baseForm.reset(); // 폼 내용 삭제
     }
}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>