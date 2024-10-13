<? include "./common/head.php"; ?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->

    <!--banner-->

    <section class="banner">
        <div class="banner_slide">
            <?
            if ($inc_partner_idx != "1") {
                $main_ad_1_sql = "select * from mainban_info where 1 and main_sect='메인화면 상단롤링' and view_ok='Y' and admin_idx='" . $inc_partner_idx . "' order by align desc limit 0,4";
                $main_ad_1_query = mysqli_query($gconnet, $main_ad_1_sql);
                if (mysqli_num_rows($main_ad_1_query) == 0) {
                    $main_ad_1_sql = "select * from mainban_info where 1 and main_sect='메인화면 상단롤링' and view_ok='Y' and admin_idx='1' order by align desc limit 0,4";
                    $main_ad_1_query = mysqli_query($gconnet, $main_ad_1_sql);
                }
            } else {
                $main_ad_1_sql = "select * from mainban_info where 1 and main_sect='메인화면 상단롤링' and view_ok='Y' and admin_idx='1' order by align desc limit 0,4";
                $main_ad_1_query = mysqli_query($gconnet, $main_ad_1_sql);
            }
            for ($main_ad_1_i = 0; $main_ad_1_i < mysqli_num_rows($main_ad_1_query); $main_ad_1_i++) {
                $main_ad_1_row = mysqli_fetch_array($main_ad_1_query);

                if ($row['link_url']) {
                    $link_css = "cursor:pointer;";
                } else {
                    $link_css = "";
                }
            ?>
                <? if ($row['link_url']) { ?>
                    <? if ($row['link_target'] == "_self") { ?>
                        <div class="box">
                            <a href="<?= $row['link_url'] ?>"><img src='<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $main_ad_1_row['file_c'] ?>' /></a>
                        </div>
                    <? } elseif ($row['link_target'] == "_blank") { ?>
                        <div class="box">
                            <a href="<?= $row['link_url'] ?>"><img src='<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $main_ad_1_row['file_c'] ?>' target="_blank" /></a>
                        </div>
                    <? } ?>
                <? } else { ?>
                    <div class="box">
                        <a href="#"><img src='<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $main_ad_1_row['file_c'] ?>' /></a>
                    </div>
                <? } ?>

            <? } ?>
        </div>
        </div>
    </section>



    <section class="go_main">
        <?
        if ($inc_partner_idx != "1") {
            $main_ad_2_sql = "select * from mainban_info where 1 and main_sect='메인화면 중단아이콘' and view_ok='Y' and admin_idx='" . $inc_partner_idx . "' order by align desc limit 0,4";
            $main_ad_2_query = mysqli_query($gconnet, $main_ad_2_sql);
            if (mysqli_num_rows($main_ad_2_query) == 0) {
                $main_ad_2_sql = "select * from mainban_info where 1 and main_sect='메인화면 중단아이콘' and view_ok='Y' and admin_idx='1' order by align desc limit 0,4";
                $main_ad_2_query = mysqli_query($gconnet, $main_ad_2_sql);
            }
        } else {
            $main_ad_2_sql = "select * from mainban_info where 1 and main_sect='메인화면 중단아이콘' and view_ok='Y' and admin_idx='1' order by align desc limit 0,4";
            $main_ad_2_query = mysqli_query($gconnet, $main_ad_2_sql);
        }
        for ($main_ad_2_i = 0; $main_ad_2_i < mysqli_num_rows($main_ad_2_query); $main_ad_2_i++) {
            $main_ad_2_row = mysqli_fetch_array($main_ad_2_query);
        ?>
            <div class="box">
                <img src="<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $main_ad_2_row['file_c'] ?>">
                <p><?= nl2br($main_ad_2_row['main_memo']) ?></p>
                <? if ($row['link_url']) { ?>
                    <a href="<?= $row['link_url'] ?>" target="<?= $row['link_target'] ?>">
                    <? } else { ?>
                        <a href="javascript:;">
                        <? } ?>
                        <img src="images/main/go.png">
                        </a>
            </div>
        <? } ?>
    </section>


    <section class="main_bg">
        <?
        if ($inc_partner_idx != "1") {
            $main_ad_3_sql = "select * from mainban_info where 1 and main_sect='메인화면 가격표시' and view_ok='Y' and admin_idx='" . $inc_partner_idx . "' order by align desc limit 0,1";
            $main_ad_3_query = mysqli_query($gconnet, $main_ad_3_sql);
            if (mysqli_num_rows($main_ad_3_query) == 0) {
                $main_ad_3_sql = "select * from mainban_info where 1 and main_sect='메인화면 가격표시' and view_ok='Y' and admin_idx='1' order by align desc limit 0,1";
                $main_ad_3_query = mysqli_query($gconnet, $main_ad_3_sql);
            }
        } else {
            $main_ad_3_sql = "select * from mainban_info where 1 and main_sect='메인화면 가격표시' and view_ok='Y' and admin_idx='1' order by align desc limit 0,1";
            $main_ad_3_query = mysqli_query($gconnet, $main_ad_3_sql);
        }
        for ($main_ad_3_i = 0; $main_ad_3_i < mysqli_num_rows($main_ad_3_query); $main_ad_3_i++) {
            $main_ad_3_row = mysqli_fetch_array($main_ad_3_query);

            if ($row['link_url']) {
                $link_css = "cursor:pointer;";
            } else {
                $link_css = "";
            }
        ?>
            <? if ($row['link_url']) { ?>
                <? if ($row['link_target'] == "_self") { ?>
                    <div class="inner" style="<?= $link_css ?>" onclick="location.href='<?= $row['link_url'] ?>';">
                    <? } elseif ($row['link_target'] == "_blank") { ?>
                        <div class="inner" style="<?= $link_css ?>" onclick="window.open('<?= $row['link_url'] ?>');">
                        <? } ?>
                    <? } else { ?>
                        <div class="inner" <?= $link_css ?>>
                        <? } ?>
                        <div class="title_main">
                            <h2><b>지금 가입하면 이 가격으로</b></h2>
                            <p><?= nl2br($main_ad_3_row['main_memo']) ?></p>
                        </div>
                        <img src="<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $main_ad_3_row['file_c'] ?>">
                        </div>
                    <? } ?>
    </section>


    <!--system-->

    <section class="system">
        <div class="inner">
            <div class="box">
                <div class="text">
                    <img src="images/main/1.png">
                    <h2>맞춤형 메세지</h2>
                    <p>저장된 다양한 맞춤형 메세지로<br>
                        쉽고 빠르게</p>
                </div>
                <div class="img">
                    <img src="images/main/01.png">
                </div>
            </div>

            <div class="box">

                <div class="img">
                    <img src="images/main/02.png">
                </div>

                <div class="text">
                    <img src="images/main/2.png">
                    <h2>통신사 테스트 발송 가능</h2>
                    <p>발송 전 통신사 3사(LG/SKT/KT)로 <br>
                        테스트 발송으로 안전하게</p>
                </div>

            </div>

            <div class="box">
                <div class="text">
                    <img src="images/main/3.png">
                    <h2>중복 메세지 방지</h2>
                    <p>동일번호 중복 메세지 방지로<br>
                        효율적인 캐쉬 관리</p>
                </div>
                <div class="img">
                    <img src="images/main/03.png">
                </div>
            </div>

            <div class="box">

                <div class="img">
                    <img src="images/main/04.png">
                </div>

                <div class="text">
                    <img src="images/main/4.png">
                    <h2>자동환불</h2>
                    <p>발송 실패시<br>
                        자동 포인트 환불</p>
                </div>

            </div>

            <div class="box">
                <div class="text">
                    <img src="images/main/5.png">
                    <h2>080수신거부 무료 제공</h2>
                    <p>080 무료거부 번호를 무료 제공<br>
                        수신거부번호는 발송목록에서 자동제거</p>
                </div>
                <div class="img">
                    <img src="images/main/05.png">
                </div>
            </div>

            <div class="box">

                <div class="img">
                    <img src="images/main/06.png">
                </div>

                <div class="text">
                    <img src="images/main/6.png">
                    <h2>개인정보 보안</h2>
                    <p>SSL 보안인증<br>
                        암호화 보안 시스템</p>
                </div>

            </div>

        </div>
    </section>


    <section class="partner">
        <div class="inner">
            <div class="title_main">
                <h2><b>판다고객사</b></h2>
            </div>

            <img src="images/main/partner.png">

        </div>
    </section>

    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

    <script>
        $('.banner_slide').slick({
            dots: true,
            infinite: true,
            speed: 300,
            autoplay: true,
            autoplaySpeed: 5000,
            slidesToShow: 1,
            arrows: false,
            customPaging: function(slider, i) {
                return '<a href="#"></a>';
            },
        });
    </script>

</body>

<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/index_layer.php"; ?>

</html>