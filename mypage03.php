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
            <h2>패스워드변경</h2>
        </div>

        <form action="./mypage_pw_edit.php" method="post">
            <input type="hidden" name="idx" value="<?= $member_idx ?>">
            <div class="mybox">
                <h2>"회원님의 개인정보 보호를 위해 쉬운 비밀번호는 자제해 주세요."</h2>
                <ul>
                    <li>
                        <div class="flex_my">
                            <span>현재 비밀번호</span>
                            <input type="password" name="now_pw" value="">
                        </div>
                    </li>
                    <li>
                        <div class="flex_my">
                            <span>새 비밀번호</span>
                            <input type="password" name="new_pw" value="" placeholder="">
                        </div>
                        <p>* 영문, 숫자, 특수문자를 조합하여 8~20자로 입력해 주세요.</p>
                    </li>
                    <li>
                        <div class="flex_my">
                            <span>비밀번호 확인</span>
                            <input type="password" name="new_pw_confirm" value="">
                        </div>
                    </li>
                </ul>

            </div>




            <div class="btn_pry">
                <!--a href="#" class="btn01 btn">취소</a-->
                <button type="submit" class="btn02 btn">변경하기</button>
            </div>
        </form>

        <div class="emt30"></div>
        <div class="point_pop">
            <h2>
                <span><img src="images/popup/point.svg"></span>
                알아두세요!
            </h2>
            <ul class="list_ul">
                <li>현재 비밀번호가 3회 이상 틀릴 경우 자동 로그아웃 됩니다.</li>
                <li>주기적인 비밀번호 변경을 권장합니다.</li>

            </ul>

        </div>


    </section>


    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>


</body>

</html>