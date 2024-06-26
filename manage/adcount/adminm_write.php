<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&s_group='.$s_group;

if($s_group == "MAIN"){
	$s_gubun_str = "관리자";
} elseif($s_group == "SUB"){
	$s_gubun_str = "운영자";
} 
?>

<script type="text/javascript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {

			if (document.frm.id_ok.value != "Y"){
				alert('아이디 중복체크를 해주세요 ! ');
				return;	
			}

			if(fnCheckId(document.frm.user_pwd.value,"비밀번호")){
				if (document.frm.user_pwd.value != document.frm.user_pwd2.value){
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
	
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/adcount_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
			<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>환경설정</li>
						<li><?=$s_gubun_str?> 계정 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$s_gubun_str?> 계정 등록</h3>
				</div>
				<div class="write">

				<form name="frm" id="frm" action="adminm_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="member_gubun" id="member_gubun" value="<?=$s_group?>"/>
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
						<th scope="row"><?=$s_gubun_str?> ID</th>
						<td colspan="3">
							<input type="text" style="width:25%;" name="user_id" id="user_id" required="yes" message="아이디" is_engnum="yes" > &nbsp; <a href="javascript:ch_id();" class="btn_green">중복검색</a> 
							<div id="check_id" style="paddig-top:10px;"></div>
						</td>
					</tr>
					<tr>
						<th ><?=$s_gubun_str?> 비밀번호</th>
						<td colspan="3">
							<input type="password" style="width:20%;" name="user_pwd" id="user_pwd" required="yes" message="비밀번호" is_engnum="yes">
						</td>
					</tr>
					<tr>
						<th ><?=$s_gubun_str?> 비밀번호 확인</th>
						<td colspan="3">
							<input type="password" style="width:20%;" name="user_pwd2" id="user_pwd2" required="yes" message="비밀번호 확인" is_engnum="yes">
						</td>
					</tr>
					<tr>
						<th ><?=$s_gubun_str?> 성명</th>
						<td colspan="3">
							<input type="text" style="width:20%;" name="user_name" id="user_name" required="yes" message="성 명">
						</td>
					</tr>
			<?if($s_group == "SUB"){ // 운영자일 경우에만 시작 ?>
					<tr>
						<th >지역설정</th>
						<td colspan="3">
						<?
							for($file_i=0; $file_i<5; $file_i++){
								$file_k = $file_i+1;
						?>
							<div <?if($file_i > 0){?>style="margin-top:10px;"<?}?>> <?=$file_k?>.
								<select name="sido_<?=$file_i?>" id="sido_<?=$file_i?>" style="vertical-align:middle;width:30%;" onchange="area_sel_1('<?=$file_i?>',this)">
									<option value="">시/도</option>
									<?
									$sect1_sql = "select bjd_code,k_name from code_bjd where 1 and filter='SIDO' and del_yn='N' order by k_name asc";
									$sect1_result = mysqli_query($gconnet,$sect1_sql);
										for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
											$row1 = mysqli_fetch_array($sect1_result);
									?>
										<option value="<?=$row1['bjd_code']?>" <?=$sido==$row1['bjd_code']?"selected":""?>><?=$row1['k_name']?></option>
									<?}?>
									</select>
								&nbsp;
								<select name="gugun_<?=$file_i?>" id="gugun_<?=$file_i?>" style="vertical-align:middle;width:30%;">
									<option value="">구/군</option>
								<?if($sido){?>
									<?
									$sect1_sql = "select bjd_code,k_name from code_bjd where 1 and filter='SGG' and del_yn='N' and pre_code='".$sido."' order by k_name asc";
									$sect1_result = mysqli_query($gconnet,$sect1_sql);
										for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
											$row1 = mysqli_fetch_array($sect1_result);
									?>
										<option value="<?=$row1['bjd_code']?>" <?=$gugun==$row1['bjd_code']?"selected":""?>><?=$row1['k_name']?></option>
									<?}?>
								<?}?>
								</select>
							</div>
						<?}?>
						</td>
					</tr>
			<?} // 운영자일 경우에만 종료?>
			
					<!--<tr>
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
					</tr>-->
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

<script>
	function area_sel_1(num,z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/area_select_1.php?cate_code1="+tmp+"&fm=frm&fname=gugun_"+num+"";
	}

	function area_sel_2(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/area_select_2.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect3";
	} 
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>