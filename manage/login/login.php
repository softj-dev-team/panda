<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? 
$reurl_go = trim($_REQUEST['reurl_go']);
?>
<script type="text/javascript">
<!--	 
	function init_Onload(){
		document.frm.lms_id.focus();
	}
	
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			return;
		}
	}
//-->
</script>

<body>
<div id="wrap" class="skin_type01">
	<div class="login_bg"></div>
	<div class="login_box">
		<div class="logo">
			<h1><img src="/images/logo.png" alt="판다문자" style="height:50px;"></h1>
			<h2>ADMINISTRATOR</h2>
		</div>
		<div class="login">
			<p class="txt">가맹점 관리자 페이지 입니다. <br>아이디와 패스워드를 입력해주세요</p>
			<form name="frm" method="post" target="_fra_admin" action="login_action.php">
			<input type="hidden" name="reurl_go" value="<?=$reurl_go?>">
				<fieldset>
					<legend>아이디, 비밀번호</legend>
					<div class="input_id">
						<span><input type="text" title="id" placeholder="아이디" name="lms_id" id="lms_id" tabindex = "1" required="yes" message="아이디"></span>
					</div>
					<div class="input_pw mt10">
						<span><input type="password" title="password" placeholder="비밀번호" name="lms_pass" id="lms_pass" tabindex = "2"  required="yes" message="비밀번호" onKeypress="if(event.keyCode ==13){go_submit();return;}"></span>
					</div>
					<button class="btn_login" onclick="go_submit();"><!--<input type="submit" class="btn_login">--><span>LOGIN</span></button>
<!-- 					<p class="btn_find">
						<a href="#" class="id_find"><span>ID 확인</span></a>
						<a href="#" class="pw_find"><span>암호확인</span></a>
					</p> -->
				</fieldset>
			</form>
		</div>
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>