<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/cert/cfg/cert_conf.php";  // 본인인증 환경설정
?>
<?php
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);

$query = "select * from member_info_sendinfo where member_idx = $member_idx";
//echo $query;
$result = mysqli_query($gconnet, $query);
$result_info = mysqli_fetch_assoc($result);

$call_num = json_decode($result_info["call_num"], true);
$call_memo = json_decode($result_info["call_memo"], true);
$use_yn = json_decode($result_info["use_yn"], true);

?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <div class="sub_title">
            <h2>발신번호추가/삭제</h2>

        </div>




        <div class="tab_btn_are">

            <div class="input_tab">

            </div>

            <div class="btn">
                <a href="#" class="" id="add_btn">발신번호 추가</a>
                <a href="#" class="" id="del_btn">발신번호 삭제</a>
            </div>



        </div>

        <div class="tlb center border">
            <table>
                <tr>

                    <th><input type="checkbox" id="check_all"></th>
                    <th>발신번호</th>
                    <th>메모</th>
                    <th>등록일</th>
                    <th>상태</th>
                    <th>메모변경</th>
                </tr>

                <?php for ($i = 0; $i < sizeof($call_num); $i++) { ?>
                    <tr>

                        <td><input type="checkbox" class="phone_check" data-id="<?= $i ?>"></td>
                        <td><?= $call_num[$i] ?></td>
                        <td><?= $call_memo[$i] ?></td>
                        <td><?= $result_info['wdate'] ?></td>
                        <td><span style="<?= $use_yn[$i] == "Y" ? "color: #127701" : "color: #FF0000" ?>"><?= $use_yn[$i] == "Y" ? "사용가능" : "사용불가" ?></span></td>
                        <td>
                            <div class="tlb_flex">
                                <input type="text"><button class="btn memo_edit_btn" data-id="<?= $i ?>">변경</button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>

        </div>


        <!--
        <div class="pagenation">
            <a href="#none" class="start">
                <img src="images/pagenation/ll.png">
            </a>
            <a href="#none" class="pre">
                <img src="images/pagenation/l.png">
            </a>

            <a href="#" class="atv">1</a>
            <a href="#" class="">2</a>
            <a href="#" class="">3</a>
            <a href="#" class="">4</a>
            <a href="#" class="">5</a>

            <a href="#none" class="next">
                <img src="images/pagenation/r.png">
            </a>
            <a href="#none" class="end">
                <img src="images/pagenation/rr.png">
            </a>
        </div>
                -->

        <div class="sub_title" style="margin-top:30px;">
            <h2>발신번호 추가등록</h2>
        </div>




        <div class="tab_btn_are">

            <div class="input_tab">

            </div>

            <div class="btn">
                <span style="color: #FF7A00; font-weight: bold; margin-right: 10px">통신가입증명원 확인 후 등록</span>
                <a href="#" class="">통신사 고객센터 확인</a>
            </div>

        </div>


        <div class="tlb  border">
            <table>
                <tr>

                    <th>개인</th>
                    <th>기업</th>
                </tr>
                <tr>

                    <td>본인명의 통신가입증명원</td>
                    <td>
                        <ul class="list_ul samll">
                            <li>회원명의 재직증명서</li>
                            <li>사업자 등록증</li>
                            <li>기업명의 통신가입증명원</li>

                        </ul>
                    </td>
                </tr>



            </table>

        </div>



        <div class="point_pop">
            <h2>
                <span><img src="images/popup/point.svg"></span>
                알아두세요!
            </h2>
            <ul class="list_ul">
                <li>이메일 (@naver.cp,) 또는 팩스 (02-1234-5647) 로 보내주세요.</li>

            </ul>

        </div>








    </section>

    <form name="form_auth">
        <input type="hidden" name="ordr_idxx" />
        <!-- 요청종류 -->
        <input type="hidden" name="req_tx" value="cert" />
        <!-- 요청구분 -->
        <input type="hidden" name="cert_method" value="01" />
        <!-- 웹사이트아이디 : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
        <input type="hidden" name="web_siteid" value="<?= $g_conf_web_siteid ?>" />
        <!-- 노출 통신사 default 처리시 아래의 주석을 해제하고 사용하십시요 
                     SKT : SKT , KT : KTF , LGU+ : LGT
                <input type="hidden" name="fix_commid"      value="KTF"/>
                -->
        <!-- 사이트코드 : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
        <input type="hidden" name="site_cd" value="<?= $g_conf_site_cd ?>" />
        <!-- Ret_URL : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
        <input type="hidden" name="Ret_URL" value="<?= $g_conf_Ret_URL ?>" />
        <!-- cert_otp_use 필수 ( 메뉴얼 참고)
                     Y : 실명 확인 + OTP 점유 확인 , N : 실명 확인 only
                -->
        <input type="hidden" name="cert_otp_use" value="Y" />
        <!-- 리턴 암호화 고도화 -->
        <input type="hidden" name="cert_enc_use_ext" value="Y" />

        <input type="hidden" name="res_cd" value="" />
        <input type="hidden" name="res_msg" value="" />

        <!-- up_hash 검증 을 위한 필드 -->
        <input type="hidden" name="veri_up_hash" value="" />

        <!-- 본인확인 input 비활성화 -->
        <input type="hidden" name="cert_able_yn" value="" />

        <!-- web_siteid 을 위한 필드 -->
        <input type="hidden" name="web_siteid_hashYN" value="Y" />

        <!-- 가맹점 사용 필드 (인증완료시 리턴)-->
        <input type="hidden" name="param_opt_1" value="opt1" />
        <input type="hidden" name="param_opt_2" value="opt2" />
        <input type="hidden" name="param_opt_3" value="opt3" />
    </form>


    <div id="layer1" class="pop-layer">
        <div class="pop-container">
            <div class="popcontent">
                <div class="poptitle">
                    <h2>
                        연락처 추가
                    </h2>
                    <a href="#" class="btn-layerClose close">
                        <img src="images/popup/close.svg">
                    </a>
                </div>

                <div class="adress_pop">

                    <div class="point_pop">
                        <h2>
                            <span><img src="images/popup/point.svg"></span>
                            주소록 등록 안내
                        </h2>
                        <ul class="number_list">
                            <li>최대 50,000개 까지 등록가능</li>
                            <li>문서파일 [복사], [붙여넣기] 가능</li>
                            <li>핸드폰번호, 이름 순으로 입력</li>
                            <li>입력 예시<br>
                                <img src="images/ex_adress.png" style="margin-top: 8px">
                            </li>
                            <li>문의사항 또는 등록대행을 원하시면 고객센터로 연락주세요.</li>


                        </ul>

                    </div>


                    <div class="adress_go">
                        <h2>그룹명 : 가족</h2>
                        <ul>
                            <li>
                                <input type="text" value="010-8888-1234">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                        </ul>

                        <div class="pagenation">
                            <a href="#none" class="start">
                                <img src="images/pagenation/ll.png">
                            </a>
                            <a href="#none" class="pre">
                                <img src="images/pagenation/l.png">
                            </a>

                            <a href="#" class="atv">1</a>
                            <a href="#" class="">2</a>
                            <a href="#" class="">3</a>
                            <a href="#" class="">4</a>
                            <a href="#" class="">5</a>

                            <a href="#none" class="next">
                                <img src="images/pagenation/r.png">
                            </a>
                            <a href="#none" class="end">
                                <img src="images/pagenation/rr.png">
                            </a>
                        </div>

                        <p>· 이름은 입력하지 않으셔도 됩니다 <span>총<b>8</b>명</span></p>

                    </div>




                </div>

                <div class="btn_are_pop">
                    <a href="#" class=" btn btn02">
                        등록
                    </a>
                    <a href="#" class="btn-layerClose btn">
                        닫기
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

    <script>
        $(document).ready(function() {
            $('#sms').on('keyup', function() {
                $('#test_cnt').html("" + $(this).val().length + "");

                if ($(this).val().length > 100) {
                    $(this).val($(this).val().substring(0, 90));
                    $('#test_cnt').html("(90 / 90)");
                }
            });
        });


        $(document).ready(function() {

            $('ul.tabs li').click(function() {
                var tab_id = $(this).attr('data-tab');

                $('ul.tabs li').removeClass('current');
                $('.tab-content').removeClass('current');

                $(this).addClass('current');
                $("#" + tab_id).addClass('current');
            })

            $("#check_all").click(function() {
                if ($("#check_all").is(":checked")) $(".phone_check").prop("checked", true);
                else $(".phone_check").prop("checked", false);
            });

            $("#add_btn").click(function() {
                var master_ok = '<?= $my_member_row['master_ok'] ?>';
                if (master_ok == "Y") {
                    auth_type_check();
                } else {
                    alert("관리자에게 인증받은 회원만 발신번호 추가가 가능합니다.");
                }

            });

            $("#del_btn").click(function() {
                var checked_array = [];
                $(".phone_check").each(function() {
                    console.log($(this).is(":checked"));
                    if ($(this).is(":checked") == true) {
                        checked_array.push($(this).data("id"));
                    }
                });
                console.log(checked_array);
                if (checked_array.length == 0) {
                    alert("삭제할 번호를 체크해주세요.");
                } else {
                    $.ajax({
                        url: "./mypage_phone_del.php",
                        type: "GET",
                        data: {
                            "checked_array": checked_array,
                            "member_idx": "<?= $member_idx ?>"
                        },
                        async: false,
                        success: function(v) {
                            alert("성공적으로 삭제 되었습니다.");
                            location.reload(true);
                        }
                    });
                }

            });

            $(".memo_edit_btn").click(function() {
                var edit = $(this).prev("input").val();
                var memo_idx = $(this).data("id");
                $.ajax({
                    url: "./mypage_memo_edit.php",
                    type: "GET",
                    data: {
                        "memo_val": edit,
                        "memo_idx": memo_idx,
                        "member_idx": "<?= $member_idx ?>"
                    },
                    async: false,
                    dataType: "json",
                    success: function(v) {
                        alert("성공적으로 변경 되었습니다.");
                        location.reload(true);
                    }
                });
            });

        })
    </script>

    <!-- 본인인증 관련 스크립트 -->
    <script type="text/javascript">
        // 인증창 종료후 인증데이터 리턴 함수
        function auth_data(frm) {
            var auth_form = document.form_auth;
            var nField = frm.elements.length;
            var response_data = "";

            // up_hash 검증 
            if (frm.up_hash.value != auth_form.veri_up_hash.value) {
                alert("up_hash 변조 위험있음");

            }


            /* 리턴 값 모두 찍어보기 (테스트 시에만 사용) */
            var form_value = "";

            for (i = 0; i < frm.length; i++) {
                form_value += "[" + frm.elements[i].name + "] = [" + frm.elements[i].value + "]\n";

                if (frm.elements[i].name == "res_cd") {
                    var res_cd = frm.elements[i].value; // 인증결과 코드
                }
                if (frm.elements[i].name == "res_msg") {
                    var res_msg = frm.elements[i].value; // 인증결과 메시지
                }
                //if(frm.elements[i].name == "user_name"){
                if (frm.elements[i].name == "user_name_real") {
                    var user_name = frm.elements[i].value; // 인증받은 성명
                }
                //if(frm.elements[i].name == "phone_no"){
                if (frm.elements[i].name == "phone_no_real") {
                    var phone_no = frm.elements[i].value; // 인증받은 전화번호
                }
                if (frm.elements[i].name == "comm_id") {
                    var comm_id = frm.elements[i].value; // 인증받은 통신사
                }
                if (frm.elements[i].name == "birth_day") {
                    var birth_day = frm.elements[i].value; // 인증받은 생년월일
                }
                if (frm.elements[i].name == "sex_code") {
                    var sex_code = frm.elements[i].value; // 인증받은 성별
                }
                if (frm.elements[i].name == "di_url") {
                    var di_url = frm.elements[i].value; // 인증받은 인증코드
                }
                if (frm.elements[i].name == "dn_hash") {
                    var dn_hash = frm.elements[i].value; // 인증받은 인증코드
                }
                if (frm.elements[i].name == "user_ci") {
                    var user_ci = frm.elements[i].value; // 인증받은 인증코드
                }
            }

            if (res_cd != "0000") {
                alert("인증에 실패 하였습니다.");
                return;
            } else {
                alert("인증이 완료 되었습니다.");
                $.ajax({
                    url: "./mypage_phone_add.php",
                    type: "GET",
                    data: {
                        "phone_no": phone_no,
                        "member_idx": "<?= $member_idx ?>",
                        "user_ci": user_ci
                    },
                    async: false,
                    dataType: "json",
                    success: function(v) {
                        console.log(v);
                        if (v.result_code == "8888") {
                            alert("중복된 발신번호는 등록이 불가합니다.");
                        } else if (v.result_code == "7777") {
                            alert("타인 명의의 발신번호는 등록이 불가합니다.");
                        } else {
                            location.reload(true);
                        }
                        //
                    }
                });
            }

            //document.write(form_value);
            /*
                [phone_no] = [] 
                [res_msg] = [정상처리] 
                [DI] = [] 
                [user_name_url_yn] = [] 
                [cert_otp_use] = [] 
                [b2b_yn] = [] 
                [comm_id] = [LGT] 
                [sex_code] = [] 
                [safe_guard_yn] = [] 
                [van_tx_id] = [] 
                [tx_type] = [3300] 
                [good_code] = [] 
                [site_key] = [] 
                [web_siteid_hashYN] = [Y] 
                [dn_hash] = [977C3B3CB3D7C336D979A3447F4D067C4338DB72] 
                [res_cd] = [0000] 
                [DI_URL] = [] 
                [app_time] = [] 
                [site_name] = [] 
                [Ret_URL] = [https://pandasms.co.kr/cert/kcpcert_proc_res.php] 
                [shopInfo_require_yn] = [] 
                [auth_tx_id] = [] 
                [kcp_merchant_time] = [] 
                [info_code] = [] 
                [cert_type] = [] 
                [user_name] = [최창환] 
                [mvno_code] = [] 
                [cert_02_yn] = [Y] 
                [log_trace_no] = [AJQNCbF6BdYoa5eu] 
                [param_opt_1] = [opt1] 
                [Ret_Noti] = [] 
                [param_opt_3] = [opt3] 
                [param_opt_2] = [opt2] 
                [cert_enc_use] = [Y] 
                [enc_cert_data2] = [.1.3D99C5BA4761DEAA7080CB1D7D5E9F7400C134BBC3F5C25CE45E0752A6EF5C412B730B180B0A6A5E138F67BE9D5342737C2E6ABE9283B448F1F27539AFF0B7A10EC3B1141EC4AA9865FA8F3F8922957C058386FCE00FD81E9A73270F8D4C7BD7905DBBB50F8B9812C01C4FA3976FAFD9835745934F8038AFEFE368A9BAA4B89ACC2D2F16F106667BD6F8B8C037C41410A393578AC399D58598786DEBD0E0DDE00AFEDE9370DC60FF2ABFA189FD5ADD79B368E653925AFE03EA9B4C73575114FE1D06C79105368C1C2B43C40DFD1177DBF16954286A87CD44FDC53597D81B0FC2B7504DBEE6AE23EB4C4036738BD204A92758387B92D00F780F1AE4AECB6B37B1EA928A78A1EFA4C226D821E765F3725C441A0BD4F98752DC79721CE3EAE9D32FD6483C4419B0DF171F8B33FA79EE547CFF2C8D3CA9BA7D228BC135AF53E5A0BAB6AFBF17795F23E44F012D4B33E2CDEE411BABA3FDCB844D59BE81715EF1D9AAC0A76B451230A9FF4D94094B82A1AABA1ADE6F087F3709730B4353431A5DFFF0236BCF2C875E0EEAF5C01ECA0E651CDB21BBB442737B8966E24F1F72B0B8E189B53C813F33DDF6ADAB1B42C0621996FF5551490D46FF70C8B0DCE1779F78F76111CE9D51E8B6DC56E410335A95AE013C7AC4D32F79A7D89AEAEA186AC62311E5AA84D8CE0D9609105FB0087D20EB9729262C62EF2B99B0AEC754F3895D698FE1089B6BEC916BE72504A056C02AF9342253EEDBAA09F088D5A277FBA03BD4509A320A9AD6D40C7CD8FCBD206FEF02CB2F9F1575C4806374350B8E07CDA6A21CDE6C1F659E4507ECCF1B9F55AA93FF724048788BCFB8D1A145C50D4EE2F7D1637314E1595FF40077A72597271AB492AEFB46F686A9BEDA3538C06BBE606D1449B5339F555BBA731CDC5E5BF6C61496DB9DF041E6946F2ECBF6D64A05440507FB82CA9042C85C986267E25ADFEED87C8F21.kt_xjcRNtejQREKVWo9AnhL9m8lBMMa1DMHS_BvLBxQVwTLyr-Ka3jneBOU2v6jGuJ_DHkr7BdJ3njKG6i1PFpY2t79d-lVkDd_pVsTg_pKz87VV1F9NPCBd92XkijGqKyPAfGOnGLOAsYIN2Yvc6je9t97XVeQ6CZzZYZFVtxAfuPCXKj7eTdRqH18-bvTLon7ZNFZGX9omv-tIub7NuS2yStevMBFY9ZlRcR9Rs6TSMfZ4kaxH1Yvad0Ehq4r_JG9leIVP3WPod77Z2j6rtMe5ZCykkLwk7Tt62DLiVMhJTqUXfdDxgdo61luaYTEH4zXtvdIZlT0Xjepzg8CSwQ.MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmO92ZYkJNYMn80Q982_psNFMIS3Zm_oCtEQMfKbap7y3YE9TxCxGWiTlCpsMmmy0WdiJQVJoMLec2Z7O436hSN2praZAQzXVNYq50Y4bhrVZhNlN3KPyM4YjLIldqTuyvgY1xxfrpLLwk1KuDufm5jJf05UcRGsnkWRM6GxhyAqZjTdDYkaP1XXlkMHKi39fPqCoyyF6tlW4byS_mjeVopbFXoCH4DcrVJiO7j9V8oE4ZQiztNPRYr5SoCDOzhGK9zlD4Cz2DMl-XRqrSrvY0-8wLr9bL6UfS9yJPoexXDzcWfrQXIruvCZghg7uwicEqeE6koz2ZXJd62JfbmaQKQIDAQAB.cLQ_4EB-bzFFRvVXIhmK_OuBH3q74UMCuMT0rpV8pnN_lmzvixWZDmnJBa-0gIplzaezeowWVFIrNQHpB8gBrMhYc-9L9fdjPwA9DQEnDts5d6dwlbnwiqmeGUfXxCQRo1VUCOMDIth8CHnHj5MIIj7N9ztGag1ELgmgLWOxHP23S-R1VpVE21M0PPikFDRD2PWsuLrzsb6WBicf4fKwl-mwm9tIhY7wJKBPeWrNSXCqDAS8YscBaNt1jbm6BdLIPlouNvudp6Ta_H3AIonb6m5uepqb65I2gLnvmbPxcRxwzu00Wc4gOa9ebCd6wEas8KX8pcsPUX_yen0mw12G6g] 
                [cc] = [] 
                [Logo_URL] = [] 
                [cert_enc_use_ext] = [Y] 
                [cert_no] = [23534228812325] 
                [CI] = [] 
                [session_id] = [] 
                [local_code] = [] 
                [web_siteid] = [J23100409787] 
                [cp_code] = [] 
                [cert_01_yn] = [Y] 
                [birth_day] = [] 
                [up_hash] = [B32C2912C8FE00CF3B9F860B22B9EAF308ECBB95] 
                [site_cd] = [AJQNC] 
                [CI_URL] = [] 
                [per_cert_no] = [23534228812325] 
                [ordr_idxx] = [202310111696993659429] 
                [cert_method] = [01] 
                [req_tx] = [otp_auth]
            */


        }

        // 인증창 호출 함수
        function auth_type_check() {
            var auth_form = document.form_auth;

            if (auth_form.ordr_idxx.value == "") {
                alert("요청번호는 필수 입니다.");
                return;
            } else {
                if ((navigator.userAgent.indexOf("Android") > -1 || navigator.userAgent.indexOf("iPhone") > -1) == false) // 스마트폰이 아닌경우
                {
                    var return_gubun;
                    var width = 410;
                    var height = 500;

                    var leftpos = screen.width / 2 - (width / 2);
                    var toppos = screen.height / 2 - (height / 2);

                    var winopts = "width=" + width + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
                    var position = ",left=" + leftpos + ", top=" + toppos;
                    var AUTH_POP = window.open('/cert/kcpcert_proc_req.php', 'auth_popup', winopts + position);

                    //alert(winopts);
                }

                auth_form.method = "post";
                auth_form.target = "auth_popup"; // !!주의 고정값 ( 리턴받을때 사용되는 타겟명입니다.)
                auth_form.action = "/cert/kcpcert_proc_req.php"; // 인증창 호출 및 결과값 리턴 페이지 주소
                auth_form.submit();
                //return true;
            }
        }

        /* 예제 */
        /*window.onload=function()
        {
            var today            = new Date();
            var year             = today.getFullYear();
            var month            = today.getMonth() + 1;
            var date             = today.getDate();
            var time             = today.getTime();
            var year_select_box  = "<option value=''>선택 (년)</option>";
            var month_select_box = "<option value=''>선택 (월)</option>";
            var day_select_box   = "<option value=''>선택 (일)</option>";
            
            if(parseInt(month) < 10) {
                month = "0" + month;
            }

            if(parseInt(date) < 10) {
                date = "0" + date;
            }

            year_select_box = "<select name='year' class='frmselect' id='year_select'>";
            year_select_box += "<option value=''>선택 (년)</option>";

            for(i=year;i>(year-100);i--)
            {
                year_select_box += "<option value='" + i + "'>" + i + " 년</option>";
            }
            
            year_select_box  += "</select>";
            month_select_box  = "<select name=\"month\" class=\"frmselect\" id=\"month_select\">";
            month_select_box += "<option value=''>선택 (월)</option>";
            
            for(i=1;i<13;i++)
            {
                if(i < 10)
                {
                    month_select_box += "<option value='0" + i + "'>" + i + " 월</option>";
                }
                else
                {
                    month_select_box += "<option value='" + i + "'>" + i + " 월</option>";
                }
            }
            
            month_select_box += "</select>";
            day_select_box    = "<select name=\"day\"   class=\"frmselect\" id=\"day_select\"  >";
            day_select_box   += "<option value=''>선택 (일)</option>";
            for(i=1;i<32;i++)
            {
                if(i < 10)
                {
                    day_select_box += "<option value='0" + i + "'>" + i + " 일</option>";
                }
                else
                {
                    day_select_box += "<option value='" + i + "'>" + i + " 일</option>";
                }
            }
            
            day_select_box += "</select>";
            
            document.getElementById( "year_month_day"  ).innerHTML = year_select_box + month_select_box + day_select_box;
            
            init_orderid(); // 요청번호 샘플 생성
        }*/

        window.onload = function() {
            init_orderid();
        }

        // 요청번호 생성 예제 ( up_hash 생성시 필요 ) 
        function init_orderid() {
            var today = new Date();
            var year = today.getFullYear();
            var month = today.getMonth() + 1;
            var date = today.getDate();
            var time = today.getTime();

            if (parseInt(month) < 10) {
                month = "0" + month;
            }

            var vOrderID = year + "" + month + "" + date + "" + time;

            document.form_auth.ordr_idxx.value = vOrderID;
        }
    </script>

</body>

</html>