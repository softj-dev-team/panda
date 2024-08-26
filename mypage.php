<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드 
?>
<?php
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <div class="sub_title">
            <h2>내정보변경</h2>
        </div>


        <div class="mybox">
            <h2>"회원님의 정보보호를 위해 비밀번호를 확인합니다."</h2>
            <ul>
                <li>
                    <div class="flex_my">
                        <span>비밀번호</span>
                        <input type="password" id="pw" value="">
                    </div>
                </li>
            </ul>
            <div class="lost">
                <span>비밀번호를 잊으셨나요?</span>
                <a href="#">비밀번호 찾기</a>
            </div>
        </div>




        <div class="btn_pry">
            <!--a href="#" class="btn01 btn">취소</a-->
            <a href="#" id="confirm_btn" class="btn02 btn">확인</a>
        </div>


    </section>



    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

    <script>
        $("#confirm_btn").click(function() {
            if ($("#pw").val() == "" || $("#pw").val() == "undefined") {
                alert("비밀번호를 입력해주세요.");
            } else {
                location.href = "./mypage_password_confirm.php?pw=" + $("#pw").val();
            }
        });
    </script>


</body>

</html>