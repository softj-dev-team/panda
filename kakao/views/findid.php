<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php";

?>
<body>
<div><?php include $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>
    <!-- 레이어 팝업 -->
    <div id="popupLayer" class="popup-layer" style="display:none;">
        <div class="popup-content popcontent">
            <div class="poptitle flex-just-end">
                <button onclick="document.getElementById('popupLayer').style.display = 'none'"><img src="/images/popup/close.svg"></button>
            </div>
            <h2>메세지 전송 결과</h2>
        </div>
    </div>

    <section class="sub">
        <div class="sub_title">
            <h2>아이디 / 비밀번호 찾기</h2>
        </div>
        <form name="find_frm" id="find_frm" method="post" target="_fra" enctype="multipart/form-data">
            <div class="myPage-find">
                <div class="flex-c">
                    <div class="fm-box custom-input-container w540">
                        <label for="template_title" class="fm-label custom-label">이메일</label>
                        <input name="email" type="text" class="fm-ipt">
                    </div>
                    <button type="button" id="findMail" class="btn-c-3 btn-t-ipt">비밀번호 초기화</button>
                </div>
                <div class="flex-c">
                    <span class="fm-error-txt errorMsg" >* 이메일 은(는) 필수 입력 항목 입니다.</span>
                </div>
            </div>

        </form>
    </section>



    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

    <script>


    </script>

</body>

</html>