<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
	<script src="//code.jquery.com/jquery-latest.min.js"></script>
<? //2022-0922 deep ?>
<html>
<head>

</head>
<?
session_destroy();
?>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script>
	$(document).ready(function(){
		Kakao.init('ad9dbbac8505bc5318f8f3f034230b1f');
		Kakao.Auth.logout();
		kakaoLogout();
		alert('로그아웃하셨습니다.');
		top.location.href = "/";
	})
</script>

<script>
//카카오로그아웃  
function kakaoLogout() {
    if (Kakao.Auth.getAccessToken()) {
      Kakao.API.request({
        url: '/v1/user/unlink',
        success: function (response) {
        	console.log(response)
        },
        fail: function (error) {
          console.log(error)
        },
      })
      Kakao.Auth.setAccessToken(undefined)
    }
  }  
</script>