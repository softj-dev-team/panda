<? include "./common/head.php"; ?>
<?
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <div class="sub_title">
            <h2>환경설정</h2>
        </div>


        <form action="./mypage_edit.php" method="post">
            <input type="hidden" name="idx" value="<?= $member_idx ?>">
            <div class="mybox adit">
                <ul>
                    <li>
                        <div class="flex_my">
                            <span>이름</span>
                            <input type="text" name="user_name" value="<?= $my_member_row['user_name'] ?>" placeholder="홍길동">
                        </div>
                    </li>
                    <li>
                        <div class="flex_my">
                            <span>이메일</span>
                            <input type="text" name="email" value="<?= $my_member_row['email'] ?>" placeholder="00@naver.com">
                        </div>
                    </li>
                </ul>

            </div>






            <div class="btn_pry">
                <!--a href="#" class="btn01 btn">취소</a-->
                <button href="#" class="btn02 btn">변경하기</button>
            </div>
        </form>

        <div class="emt30"></div>

        <div class="point_pop flexbtnare">
            <h2>
                개인으로 가입되어 있습니다.<br>
                사업자로 변경을 원하시는 경우 사업자 인증을 해주세요.
            </h2>
            <div class="btn_pry">
                <!--a href="#" class="btn01 btn">취소</a-->
                <a href="#" class="btn02 btn">사업자인증하기</a>
            </div>

        </div>

    </section>

    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

</body>

</html>