<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
<title>팝업스터디</title>
<link rel="stylesheet" href="http://popupstudy2.cafe24.com/css/common.css" type="text/css" />
<link rel="stylesheet" href="http://popupstudy2.cafe24.com/css/main.css" type="text/css" />
<link rel="stylesheet" href="http://popupstudy2.cafe24.com/css/layout.css" type="text/css" />
<link rel="stylesheet" href="http://popupstudy2.cafe24.com/css/popup.css" type="text/css" />
<link rel="stylesheet" href="http://popupstudy2.cafe24.com/css/membership.css" type="text/css" />
<link rel="stylesheet" href="http://popupstudy2.cafe24.com/css/community.css" type="text/css" />
<link rel="stylesheet" href="http://popupstudy2.cafe24.com/css/subpage.css" type="text/css" />
<link rel="stylesheet" href="http://popupstudy2.cafe24.com/css/vacabulary.css" type="text/css" />

<script type="text/javascript" src="http://popupstudy2.cafe24.com/js/common_js.js"></script>
<script type="text/javascript">
<!--
function default_setCookie( name, value, expiredays ){
   var todayDate = new Date();
    todayDate.setDate( todayDate.getDate() + expiredays );
   document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}


function default_closeWin() { 
    default_setCookie( "default_main", "done" , 1); // 1=하룻동안 공지창 열지 않음
     self.close(); 
}
//-->
</script>

<script type="text/javascript">
<!--
function go_opener_url(recv) {
	opener.location.href =  recv;
	self.close();
}

function go_top_login_submit() {
	var check = chkFrm('top_login_frm1');
	if(check) {
		/*if(document.top_login_frm1.kml.checked){
			 toMem(top_login_frm1);
		}*/
		top_login_frm1.submit();
	} else {
		return false;
	}
}
// -->
</script>

<style type="text/css">  
	html {overflow:hidden;}  
</style>  

<body>
<div class="popup_common">
	<div class="popup_common2">
		<p class="pmain_text">
			책보다 모니터를 더 많이 보는 디지털 세대들을 위해<br />
			<span>간편하게 테스트</span>하고, <strong>다른 일을 하면서도 복습</strong>을 하는<br />
			<span>발명특허의 인터넷 학습방법</span>인 <strong>팝업스터디</strong>입니다.
		</p>
		<div class="popup_com_inner">
			<p>팝업스터디외 다른 사이트를 통해 보시는 분은 홈페이지 바로가기를 통해<br />
			이용안내, 학습방법 등을 둘러 본 후, 회원 가입하고 무료로 이용할 수 있습니다.<br />
			<strong>개인정보보호를 위해 회원 가입시 주민등록번호, 전화번호, 상세주소 등은 기입하지 않습니다.</strong></p>
			<p class="head_home"><a href="javascript:go_opener_url('http://popupstudy2.cafe24.com/');">홈페이지 바로가기</a></p>
			<p>팝업스터디 회원이면 로그인 후 무료로 이용할 수 있습니다.<br />
			<strong>급수별 한자, 영단어·숙어, 한자단어, 한자성어, 속담, 구구단·19단을 <br /> 테스트 또는 학습할 수 있습니다.</strong></p>
			<div class="form_area">
				<form name="top_login_frm1" id="top_login_frm1" action="http://popupstudy2.cafe24.com/popup/popup_login_action.php" target="_self" method="post">
					<input type="text" placeholder="아이디" name="pops_id" id="pops_id" onKeyup="checkNumber()" tabindex = "1" required="yes" message="아이디">
					<input type="password" placeholder="비밀번호" name="pops_pass" id="pops_pass" tabindex = "2"  required="yes" message="비밀번호">
					<span class="login_btn"><a href="javascript:go_top_login_submit();">로그인</a></span>
					<a href="javascript:go_opener_url('http://popupstudy2.cafe24.com/membership/id_pw.php');">아이디 · 비밀번호 찾기</a>
				</form>
			</div>
			<div class="popup_com_inner2">팝업창에서 로그인할 때에는 키보드의 Enter키를 누르지 말고 <br>로그인을 클릭하세요</div>
		</div>
	</div>
	<div class="p_footer">
		<p class="fl" style="width:75%;"><input type="checkbox" name="checkbox" id="checkbox" onclick="javascript:default_closeWin();"><img src="http://popupstudy2.cafe24.com/img/popup/btn_nomore.png"class="ml5" alt="오늘은 그만보기" /></p>
		<p class="fr" style="width:25%;"><a href="javascript:self.close();"><img src="http://popupstudy2.cafe24.com/img/popup/btn_close_x.png" alt="close" /></a></p>
	</div>
</body>
</html>