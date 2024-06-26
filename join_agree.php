<? include "./common/head.php"; ?>	
    <body>
        
        <!--header-->
         <div><? include "./common/header.php"; ?></div>   
        
        <!--content-->
    <form name="join_frm" id="join_frm" action="join_detail.php" target="_self" method="post" enctype="multipart/form-data">             
    <section class="sub">
        <div class="sub_title">
            <h2>회원가입 동의</h2>
        </div>
        
        <div class="agree_form">
            <h2>서비스 이용약관</h2>
            <div class="text_pre">
                <?=get_yakkwan("host1")?>
            </div>
            <div class="cheack_form">
                <input type="checkbox" id="agree1" value="Y" name="agree_check_1"><label>이용약관에 동의(필수)</label>
            </div>
        </div>
        
        <div class="agree_form">
            <h2>개인정보 수집 및 이용에 대한 안내</h2>
            <div class="text_pre">
               <?=get_yakkwan("host2")?>
            </div>
            <div class="cheack_form">
                <input type="checkbox" id="agree2" value="Y" name="agree_check_2"><label>개인정보 수집 및 이용에 동의 (필수)</label>
            </div>
        </div>
        
        <div class="agree_form">
            <h2>스팸(SPAM)메시지 관리 약관</h2>
            <div class="text_pre">
               <?=get_yakkwan("host4")?>
            </div>
            <div class="cheack_form">
                <input type="checkbox" id="agree3" value="Y" name="agree_check_3"><label>스팸메세지 관리약관에 동의 (필수)</label>
            </div>
        </div>
        
        
        <div class="btn_pry">
            <a href="/" class="btn01 btn">취소</a>
            <a href="javascript:go_submit();" class="btn02 btn">다음단계</a>
        </div>
        
    </section>
	</form>

	<script>
		function go_submit() {
			if(document.getElementById("agree1").checked == false){
				alert("서비스 이용약관에 동의하셔야 합니다.");		
				return;
			}

			if(document.getElementById("agree2").checked == false){
				alert("개인정보 수집 및 이용에 대한 안내에 동의하셔야 합니다.");		
				return;
			}

			if(document.getElementById("agree3").checked == false){
				alert("스팸(SPAM)메시지 관리 약관에 동의하셔야 합니다.");		
				return;
			}

			var check = chkFrm('join_frm');
			if(check) {
				join_frm.submit();
			} else {
				false;
			}
		}
	
		function allchk(){
			if(document.getElementById("agreeAll").checked == true){
				document.getElementById("agree1").checked = true;
				document.getElementById("agree2").checked = true;
				document.getElementById("agree3").checked = true;
				/*document.getElementById("agree1").parentNode.classList.add("agreeChk");
				document.getElementById("agree2").parentNode.classList.add("agreeChk");
				document.getElementById("agree3").parentNode.classList.add("agreeChk");*/

			} else {
				document.getElementById("agree1").checked = false;
				document.getElementById("agree2").checked = false;
				document.getElementById("agree3").checked = false;
				/*document.getElementById("agree1").parentNode.classList.remove("agreeChk");
				document.getElementById("agree2").parentNode.classList.remove("agreeChk");
				document.getElementById("agree3").parentNode.classList.remove("agreeChk");*/
			}
			
		}
	</script>

       <!--footer-->
        <div><? include "./common/footer.php"; ?></div>   

    </body>
</html>
