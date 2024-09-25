<?php
if ($_SESSION['member_coinc_idx']) {
    if (!isset($inc_member_row) || empty($inc_member_row)) {
        // 컨트롤러에서 상속되지 않았거나 빈 배열인 경우에만 get_member_data를 호출
        $inc_member_row = get_member_data($_SESSION['member_coinc_idx']);
    }
}
?>
<div class="spinner-background" style="display: none;"></div>

<div class="loadingio-spinner-spin-2by998twmg8" style="display: none;">
    <div class="ldio-yzaezf3dcmj">
        <div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div>
        <div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div>
    </div>
</div>
<!--header-->
<header>
    <div class="header">
        <a href="/" class="logo">
            <h2 class="prefont">
                <? if ($inc_confg_file_chg) { ?>
                    <img src="<?= $_P_DIR_WEB_FILE ?>siteconf/img_thumb/<?= $inc_confg_file_chg ?>">
                <? } else { ?>
                    <img src="/images/logo.png">
                <? } ?>
            </h2>
        </a>
        <div class="right_nav">
            <? if ($_SESSION['member_coinc_idx']) { ?>
                <div class="user">
                    <p>
                        <b><?= $inc_member_row['member_gubun']=='4'?$inc_member_row['company_name'] : $inc_member_row['user_name'] ?></b> 님의 충전잔액은<span><?= number_format($inc_member_row['current_point']) ?></span>원입니다.
                    </p>
                </div>
                <button class="coin" type="button" onclick="location.href='./pay.php'">
                    충전하기
                </button>
                <a href="/logout_action.php" class="login">
                    로그아웃
                </a>
            <? } else { ?>
                <button class="coin login_btn" type="button">
                    로그인
                </button>

                <div class="login_box">
                    <form name="log_frm" id="log_frm" action="/login_action.php" target="_fra" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="reurl_go" id="reurl_go" value="<?= $_SERVER['SCRIPT_NAME'] ?>?<?= $_SERVER['QUERY_STRING'] ?>" />
                        <input type="hidden" id="push_key" name="push_key">
                        <div class="form_login">
                            <input type="text" placeholder="아이디" name="lms_id" id="lms_id" required="yes" message="아이디">
                            <input type="password" placeholder="비밀번호" name="lms_pass" id="lms_pass" required="yes" message="비밀번호" onKeypress="if(event.keyCode ==13){go_member_login();return;}">
                            <div class="summit">
                                <a href="#" class="nomember">
                                    아이디 / 비밀번호 찾기
                                </a>
                                <button class="btn" type="button" onclick="go_member_login();">
                                    로그인
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <a href="join.php" class="login">
                    회원가입
                </a>

                <!-- SNS 로그인 시 리턴 데이터 수신 및 전달을 위한 폼 시작 -->
                <form name="pub_login_form" id="pub_login_form" method="post">
                    <input type="hidden" name="sns_code">
                    <input type="hidden" name="member_code">
                    <input type="hidden" name="user_name">
                    <input type="hidden" name="user_email">
                    <input type="hidden" name="reurl_go" value="<?= $_SERVER['SCRIPT_NAME'] ?>?<?= $_SERVER['QUERY_STRING'] ?>" />
                </form>
                <!-- SNS 로그인 시 리턴 데이터 수신 및 전달을 위한 폼 종료 -->
            <? } ?>
            <img src="/images/hamburger.png" id="mob_menu_btn" onclick="mob_menu()">
        </div>
    </div>

    <div class="center_nav">
        <ul>
            <li class="dep1">
                <a href="/sms.php?send_type=gen">
                    문자
                </a>
                <ul class="subdeps subdeps01">
                    <li>
                        <a href="/sms.php?send_type=gen">
                            <!--단·장문 보내기-->
                            문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_img.php?send_type=gen">
                            이미지문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_mug.php?send_type=gen">
                            머지문자 보내기
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep2">
                <a href="/sms.php?send_type=adv">
                    광고문자
                </a>
                <ul class="subdeps subdeps02">
                    <li>
                        <a href="/sms.php?send_type=adv">
                            <!--단·장문 보내기-->
                            광고문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_img.php?send_type=adv">
                            이미지문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_mug.php?send_type=adv">
                            머지문자 보내기
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep3">
                <a href="/sms.php?send_type=elc">
                    선거문자
                </a>
                <ul class="subdeps subdeps03">
                    <li>
                        <a href="/sms.php?send_type=elc">
                            <!--단·장문 보내기-->
                            문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_img.php?send_type=elc">
                            이미지문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_mug.php?send_type=elc">
                            머지문자 보내기
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep4">
                <a href="/sms_test.php?send_type=test">
                    통신사 3사 테스트
                </a>
            </li>

            <li class="dep8">
                <a href="/kakao">
                    kakao
                </a>
                <ul class="subdeps subdeps08">
                    <li>
                        <a href="/kakao/index.php?route=send">
                            <!--단·장문 보내기-->
                           알림톡
                        </a>
                    </li>
                    <li>
                        <a href="/kakao/index.php?route=userAlimTalkSendList">
                            <!--단·장문 보내기-->
                            알림톡 발송내역
                        </a>
                    </li>
                    <li>
                        <a href="/kakao/index.php?route=templateList">
                           알림톡 템플릿 관리
                        </a>
                    </li>

                </ul>
            </li>

            <li class="dep5">
                <a href="/board_list.php?bbs_code=event">
                    이벤트
                </a>

            </li>

            <li class="dep6">
                <a href="/mypage.php">
                    마이페이지
                </a>
                <ul class="subdeps subdeps06">
                    <li>
                        <a href="/mypage.php">
                            내정보변경
                        </a>
                    </li>
                    <!--<li>
                                 <a href="#">
                                    환경설정
                                 </a>
                             </li>-->
                    <li>
                        <a href="/mypage03.php">
                            패스워드변경
                        </a>
                    </li>
                    <li>
                        <a href="/mypage04.php">
                            발신번호추가/삭제
                        </a>
                    </li>
                    <li>
                        <a href="/mypage05.php">
                            회원탈퇴
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
        <ul>
            <li class="dep5">
                <a href="/board_list.php?bbs_code=notice">
                    공지사항
                </a>

            </li>

            <li class="dep5">
                <a href="/pay.php">
                    충전
                </a>
                <ul class="subdeps subdeps05">
                    <li>
                        <a href="/pay.php">
                            문자단가
                        </a>
                    </li>
                    <li>
                        <a href="/pay02.php">
                            충전내역
                        </a>
                    </li>
                    <li>
                        <a href="/pay03.php">
                            발송(금액차감)내역
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep4">
                <a href="/adress.php">
                    주소록
                </a>
                <!--
                <ul class="subdeps subdeps04">
                    
                    <li>
                        <a href="/adress.php">
                            주소록
                        </a>
                    </li>
                    
                    <li>
                        <a href="/adress02.php">
                            수신거부
                        </a>
                    </li>
                    <li>
                        <a href="/adress03.php">
                            080수신거부목록 (자동등록)
                        </a>
                    </li>
            
                </ul>
                -->
            </li>
            <li class="dep7">
                <a href="/send.php">
                    발송결과
                </a>
                <ul class="subdeps subdeps07">
                    <li>
                        <a href="/send.php">
                            전송결과
                        </a>
                    </li>
                    <li>
                        <a href="/send02.php">
                            예약전송
                        </a>
                    </li>
                    <li>
                        <a href="/send03.php">
                            전송통계
                        </a>
                    </li>
                </ul>
            </li>


        </ul>
    </div>
</header>

<div id="mobile_menu_wrap">
    <div id="mobile_menu">
        <ul>
            <li class="dep1">
                <a href="/sms.php?send_type=gen">
                    문자
                </a>
                <ul class="subdeps subdeps01">
                    <li>
                        <a href="/sms.php?send_type=gen">
                            <!--단·장문 보내기-->
                            문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_img.php?send_type=gen">
                            이미지문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_mug.php?send_type=gen">
                            머지문자 보내기
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep2">
                <a href="/sms.php?send_type=adv">
                    광고문자
                </a>
                <ul class="subdeps subdeps02">
                    <li>
                        <a href="/sms.php?send_type=adv">
                            <!--단·장문 보내기-->
                            광고문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_img.php?send_type=adv">
                            이미지문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_mug.php?send_type=adv">
                            머지문자 보내기
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep3">
                <a href="/sms.php?send_type=elc">
                    선거문자
                </a>
                <ul class="subdeps subdeps03">
                    <li>
                        <a href="/sms.php?send_type=elc">
                            <!--단·장문 보내기-->
                            문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_img.php?send_type=elc">
                            이미지문자 보내기
                        </a>
                    </li>
                    <li>
                        <a href="/sms_mug.php?send_type=elc">
                            머지문자 보내기
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep4">
                <a href="/sms_test.php?send_type=test">
                    통신사 3사 테스트
                </a>
            </li>

            <!--<li class="dep5">
                <a href="/sms.php?send_type=test">
                    통신사 3사 테스트
                </a>
            </li>-->

            <li class="dep5">
                <a href="/board_list.php?bbs_code=event">
                    이벤트
                </a>

            </li>

            <li class="dep6">
                <a href="/mypage.php">
                    마이페이지
                </a>
                <ul class="subdeps subdeps06">
                    <li>
                        <a href="/mypage.php">
                            내정보변경
                        </a>
                    </li>
                    <li>
                        <a href="/mypage03.php">
                            패스워드변경
                        </a>
                    </li>
                    <li>
                        <a href="/mypage04.php">
                            발신번호추가/삭제
                        </a>
                    </li>
                    <li>
                        <a href="/mypage05.php">
                            회원탈퇴
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep5">
                <a href="/board_list.php?bbs_code=notice">
                    공지사항
                </a>

            </li>

            <li class="dep5">
                <a href="/pay.php">
                    충전
                </a>
                <ul class="subdeps subdeps05">
                    <li>
                        <a href="/pay.php">
                            문자단가
                        </a>
                    </li>
                    <li>
                        <a href="/pay02.php">
                            충전내역
                        </a>
                    </li>
                    <li>
                        <a href="/pay03.php">
                            발송(금액차감)내역
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dep4">
                <a href="/adress.php">
                    주소록
                </a>
            </li>
            <li class="dep7">
                <a href="/send.php">
                    발송결과
                </a>
                <ul class="subdeps subdeps07">
                    <li>
                        <a href="/send.php">
                            전송결과
                        </a>
                    </li>
                    <li>
                        <a href="/send02.php">
                            예약전송
                        </a>
                    </li>
                    <li>
                        <a href="/send03.php">
                            전송통계
                        </a>
                    </li>
                </ul>
            </li>


        </ul>
    </div>
</div>

<script>
    /* 폰갭 푸시 키 받기 시작 */
    $(function() {
        if (typeof Android != "undefined") {
            Android.get_fcm_token();
        }
    });

    function androidCallJSgcm(push_key) {
        var frm = document.forms["log_frm"];
        frm.elements["push_key"].value = push_key;
    }

    $(document).ready(function() {
        if (typeof Android != "undefined") {
            Android.get_fcm_token();
        }

        login_default();
    });
    /* 폰갭 푸시 키 받기 종료 */

    function go_member_login() {
        var check = chkFrm('log_frm');
        if (check) {
            /*if($("#m-checkid").prop("checked") == true) {
            	set_cookie("userid",$("#lms_id").val(),1,"*");
            } else {
            	set_cookie("userid","",1,"");
            }*/
            log_frm.submit();
        } else {
            return;
        }
    }

    function login_default() {
        /*if(get_cookie("userid") != "undifined" && get_cookie("userid") ) {
        	var saveid = getCookie("userid");
        	$("#lms_id").val(saveid);
        	$("#m-checkid").prop("checked",true);
        }*/
        $("#lms_id").focus();
    }

    function mob_menu() {
        if ($("#mobile_menu_wrap").css("display") == "none") {
            $("#mobile_menu_wrap").css("display", "block");
            $("#mobile_menu").css("display", "block");
        } else {
            $("#mobile_menu_wrap").css("display", "none");
            $("#mobile_menu").css("display", "none");
        }
    }
</script>