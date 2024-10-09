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
    <div class="spinner-background" style="display: none;"></div>

    <div class="loadingio-spinner-spin-2by998twmg8" style="display: none;">
        <div class="ldio-yzaezf3dcmj">
            <div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div>
            <div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div>
        </div>
    </div>
    <!-- 모달 -->
    <div id="profileModal" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="profileForm"  class="flex-column">

                <label for="chananel_name" class="fm-label">채널 이름: 예) @채널명 (검색용아이디) </label>
                <input type="text" class="fm-ipt" id="chananel_name" name="chananel_name" required><br>

                <label for="cs_phone_number" class="fm-label">담당자 휴대폰 번호</label>
                <div class="flex-between">
                    <input type="text" id="cs_phone_number" name="cs_phone_number" required class="fm-ipt"><button type="button" id="authenticationRequest" class="btn-t-2 btn-c-3">인증요청</button>
                </div>
                <br>
                <label for="auth_token" class="fm-auth_token fm-label" >인증토큰</label>
                <div class="flex-between">
                    <input type="text" id="auth_token" name="auth_token" required class="fm-ipt"><button type="button" id="requestProfileKey" class="btn-t-2 btn-c-3">채널 연동</button>
                </div>
                <br>
                <label for="chananel_name" class="fm-label flex">카테고리 </label>
                <div class="fm-col-3">
                    <div class="fm-box fm-col-in-full">
                        <select id="category1" class="fm-sel-2">
                            <option value="">대분류 선택</option>
                        </select>
                    </div>
                    <div class="fm-box fm-col-in-half">
                        <select id="category2" class="fm-sel-2">
                            <option value="">중분류 선택</option>
                        </select>
                    </div>
                    <div class="fm-box fm-col-in-half">
                        <select id="category3" class="fm-sel-2">
                            <option value="">소분류 선택</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" id="industry" name="industry">
            </form>
            <h2>신청 목록</h2>
            <table id="profilesTable">
                <thead>
                <tr>
                    <th>NO</th>
                    <th>채널명</th>
                    <th>발신프로필키</th>
                    <th>카테고리</th>
                    <th>고객센터 번호(발신번호)</th>
                    <th>상태</th>
                    <th>삭제</th>
                </tr>
                </thead>
                <tbody>
                <!-- 데이터가 동적으로 추가됩니다 -->
                </tbody>
            </table>
        </div>
    </div>
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



