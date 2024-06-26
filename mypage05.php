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
            <h2>회원탈퇴</h2>
        </div>

        <form action="./mypage_withdraw.php" method="post">
            <input type="hidden" name="idx" value="<?= $member_idx ?>">
            <div class="mybox">
                <h2>"회원탈퇴를 원하시면 아래 내용을 확인해 주세요."</h2>
                <ul>
                    <li>
                        <div class="flex_my">
                            <span>이름</span>
                            <input type="text" value="<?= $my_member_row['user_name'] ?>" readonly>
                        </div>
                    </li>
                    <li>
                        <div class="flex_my">
                            <span>아이디</span>
                            <input type="text" value="<?= $my_member_row['user_id'] ?>" readonly>
                        </div>
                    </li>
                    <li>
                        <div class="flex_my">
                            <span>비밀번호 입력</span>
                            <input type="password" name="pw" value="">
                        </div>
                    </li>
                </ul>

            </div>




            <div class="btn_pry">
                <!--a href="#" class="btn01 btn">취소</a-->
                <button type="submit" class="btn02 btn">회원탈퇴</button>
            </div>
        </form>

    </section>

    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>


</body>

</html>