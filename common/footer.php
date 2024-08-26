    <!--footer-->
    <section class="foot_cs">
        <div class="inner">
            <div class="more_text">
                <h2>공지사항</h2>
                <a href="/board_list.php?bbs_code=notice">
                    더보기
                </a>
            </div>
            <ul>
        <?php
        if (!empty($inc_notice_query)) {
            foreach ($inc_notice_query as $inc_notice_row) {
                $inc_notice_reg_time3 = to_time(substr($inc_notice_row->write_time, 0, 10));
                ?>
                <li>
                    <p>
                        <a href="javascript:go_notice_view('<?= $inc_notice_row->idx ?>');"><?= string_cut2(stripslashes($inc_notice_row->subject), 30) ?> <?= now_date($inc_notice_reg_time3) ?></a>
                    </p>
                    <p class="date"><?= substr($inc_notice_row->write_time, 0, 10) ?></p>
                </li>
        <?php
            }
        }else{

            $inc_notice_sql = "select * from board_content a where 1 and a.is_del='N' and a.step='0' and a.bbs_code = 'notice' ORDER BY a.ref desc, a.step asc, a.depth asc limit 0 , 3";
            $inc_notice_query = mysqli_query($gconnet, $inc_notice_sql);
            for ($inc_notice_i = 0; $inc_notice_i < mysqli_num_rows($inc_notice_query); $inc_notice_i++) {
                $inc_notice_row = mysqli_fetch_array($inc_notice_query);
                $inc_notice_reg_time3 = to_time(substr($inc_notice_row['write_time'], 0, 10));
        ?>
                <li>
                    <p><a href="javascript:go_notice_view('<?= $inc_notice_row['idx'] ?>');"><?= string_cut2(stripslashes($inc_notice_row['subject']), 30) ?> <?= now_date($inc_notice_reg_time3) ?></a></p>
                    <p class="date"><?= substr($inc_notice_row['write_time'], 0, 10) ?></p>
                </li>
                <?php
            }
        }
        ?>
            </ul>
        </div>
    </section>

    <section class="foot_link">
        <div class="inner">
            <ul>
                <li>
                    <a href="/guide.php">메뉴얼안내</a>
                </li>
                <li>
                    <a href="/pre.php">개인정보취급방침</a>
                </li>
                <li>
                    <a href="/pre.php">서비스 이용약관</a>
                </li>
            </ul>
        </div>
    </section>


    <footer>
        <div class="inner">

            <h3>고객센터</h3>
            <h2><?= $inc_confg_conf_tel_2 ?></h2>

            <p class="bold">
                평일 <?= $inc_confg_conf_time_s ?> ~ <?= $inc_confg_conf_time_e ?> <? if ($inc_confg_conf_time_s2 || $inc_confg_conf_time_e2) { ?> / 주말 <?= $inc_confg_conf_time_s2 ?> ~ <?= $inc_confg_conf_time_e2 ?><? } ?><br>
                    FAX : <?= $inc_confg_conf_fax ?> | <?= $inc_confg_conf_email_1 ?>
            </p>
            <p><?= $inc_confg_conf_comname ?> 대표이사 : <?= $inc_confg_conf_comowner ?> <? if ($inc_confg_conf_manager) { ?> 개인정보 보호담당자 : <?= $inc_confg_conf_manager ?><? } ?>
                    <br>사업자등록번호 : <?= $inc_confg_conf_comnum_1 ?> 통신판매업신고 : <?= $inc_confg_conf_comnum_2 ?><br>
                    <?= $inc_confg_conf_addr ?> Tel : <?= $inc_confg_conf_tel_1 ?> 전자메일주소 : <?= $inc_confg_conf_email_2 ?><br><br>
                    Copyrightⓒ Korea 판다문자 All Rights Reserved.
            </p>
        </div>


    </footer>

    <div class="floating_nav">
        <a id="top_btn" href="javascript:;">
            <img src="/images/up.png">
        </a>
        <? if ($inc_confg_sns_kakao) { ?>
            <a href="<?= $inc_confg_sns_kakao ?>" target="_blank">
            <? } else { ?>
                <a href="javascript:;">
                <? } ?>
                <img src="/images/kakao.png">
                </a>
                <? if ($inc_confg_sns_teleg) { ?>
                    <a href="<?= $inc_confg_sns_teleg ?>" target="_blank">
                    <? } else { ?>
                        <a href="javascript:;">
                        <? } ?>
                        <img src="/images/twiter.png">
                        </a>
                        <div class="bank">
                            <? if ($inc_confg_bank_num) { ?>
                                <p>계좌안내</p>
                                <p><?= $inc_confg_bank_num ?></p>
                            <? } ?>
                            <? if ($inc_confg_bank_name) { ?>
                                <p><span>(<?= $inc_confg_bank_name ?>)</span></p>
                            <? } ?>
                            <? if ($inc_confg_bank_owner) { ?>
                                <p><span>예금주<br>
                                        <?= $inc_confg_bank_owner ?> </span></p>
                            <? } ?>

                        </div>

                        <?php $main_side_sql = "select * from mainban_info where 1 and main_sect='사이드바' and view_ok='Y' and admin_idx='1' order by align desc limit 0,4";
                        $main_side_query = mysqli_query($gconnet, $main_side_sql);

                        for ($main_side_i = 0; $main_side_i < mysqli_num_rows($main_side_query); $main_side_i++) {
                            $main_side_row = mysqli_fetch_array($main_side_query);
                        ?>
                            <? if ($main_side_row['link_url']) { ?>
                                <? if ($main_side_row['link_target'] == "_self") { ?>
                                    <a class="side-banner" href="<?= $main_side_row['link_url'] ?>" target="<?= $main_side_row['link_target'] ?>"><img src='<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $main_side_row['file_c'] ?>' /></a>
                                <? } elseif ($main_side_row['link_target'] == "_blank") { ?>
                                    <a class="side-banner" href="<?= $main_side_row['link_url'] ?>" target="<?= $main_side_row['link_target'] ?>"><img src='<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $main_side_row['file_c'] ?>' /></a>
                                <? } ?>
                            <? } else { ?>
                                <a class="side-banner" href="<?= $main_side_row['link_url'] ?>" target="<?= $main_side_row['link_target'] ?>"><img src='<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $main_side_row['file_c'] ?>' /></a>
                            <? } ?>

                        <? } ?>


                        <script>
                            function go_notice_view(no) {
                                location.href = "/board_detail.php?idx=" + no + "&bbs_code=notice";
                            }
                        </script>

                        <script type="text/javascript" src="/js/common.js"></script>
                        <script type="text/javascript" src="/js/slick.js"></script>
                        <script type="text/javascript" src="/js/popup.js"></script>
                        <script type="text/javascript" src="/js/common_js.js"></script>

                        <?php include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom.php"; // 공통함수 인클루드
                        ?>