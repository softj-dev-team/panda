<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드 
?>
<?
$member_idx = $_SESSION['member_coinc_idx'];

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'field=' . $field . '&keyword=' . $keyword;

if (!$pageNo) {
    $pageNo = 1;
}

$where = " and member_idx='" . $member_idx . "'";

if ($keyword) {
    $where .= " and group_name like '%" . $keyword . "%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo - 1) * $pageScale;

$StarRowNum = (($pageNo - 1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select *,(select count(idx) from address_group_num where 1 and is_del!='Y' and group_idx=address_group.idx) as group_cnt from address_group where 1 and is_del != 'Y' " . $where . $order_by . " limit " . $StarRowNum . " , " . $EndRowNum;
//echo $query;
$result = mysqli_query($gconnet, $query);

$query_cnt = "select idx from address_group where 1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage    = ($iTotalSubCnt - 1) / $pageScale  + 1;
?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <div class="sub_title">
            <h2>주소록</h2>

        </div>

        <div class="adress_btn">
        </div>


        <div class="tab_btn_are">
            <form name="s_mem" id="s_mem" method="post" action="adress.php">
                <div class="input_tab">
                    <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>">
                    <a href="javascript:s_mem.submit();">
                        <img src="images/search.png">
                    </a>
                </div>
            </form>

            <form method="post" action="adress_action.php" name="frm_1" target="_fra" id="frm_1" enctype="multipart/form-data">
                <input type="hidden" name="mode" id="mode_1" value="ins">
                <div class="btn">
                    <input type="text" name="group_name" id="group_name" required="yes" message="그룹명" placeholder="그룹명">
                    <a href="javascript:go_submit_1();">그룹추가</a>
                    <a href="javascript:go_tot_del();">그룹삭제</a>
                </div>
            </form>
        </div>

        <form method="post" name="frm" id="frm" target="_fra">
            <input type="hidden" name="pageNo" value="<?= $pageNo ?>" />
            <input type="hidden" name="total_param" value="<?= $total_param ?>" />
            <input type="hidden" name="mode" id="mode_2" value="totdel">
            <div class="tlb center border">
                <table>
                    <tr>
                        <th class="check"><input type="checkbox" name="checkNum" onclick="javascript:CheckAll()"></th>
                        <th>그룹명</th>
                        <th>소속인원</th>
                        <th>그룹명 변경</th>
                        <th>추가</th>
                    </tr>
                    <?
                    for ($i = 0; $i < mysqli_num_rows($result); $i++) { // 대분류 루프 시작
                        $row = mysqli_fetch_array($result);

                        $listnum    = $iTotalSubCnt - (($pageNo - 1) * $pageScale) - $i;
                    ?>
                        <tr>
                            <td class="check"><input type="checkbox" name="adr_idx[]" id="adr_idx[]" value="<?= $row["idx"] ?>" required="yes" message="주소록 그룹"></td>
                            <td><?= $row['group_name'] ?></td>
                            <td><?= number_format($row['group_cnt']) ?>명</td>
                            <td>
                                <div class="tlb_flex">
                                    <input type="text" name="group_name" id="group_name_<?= $row['idx'] ?>" required="yes" message="그룹명" value="<?= $row['group_name'] ?>"><button type="button" class="btn" onclick="go_modify('<?= $row['idx'] ?>');">변경</button>
                                </div>
                            </td>
                            <td>
                                <a href="./adress_list.php?group_idx=<?= $row['idx'] ?>" class="btn-example btn">연락처 관리</a>
                            </td>
                        </tr>
                    <? } ?>
                </table>
            </div>
        </form>

        <div class="pagenation">
            <? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/paging_front.php"; ?>
        </div>

        <div class="point_pop">
            <h2>
                <span><img src="images/popup/point.svg"></span>
                알아두세요!
            </h2>
            <ul class="list_ul">
                <li>한 그룹에 최대 50,000개의 연락처를 등록하실 수 있습니다.</li>
                <li>연락처 관리를 클릭하시면 개별주소 리스트를 확인하실 수 있습니다.</li>

            </ul>
        </div>

    </section>

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
                        <!-- 추가버튼 시작 -->
                        <div class="btn_are">
                            <div class="btn">
                                <label for="excel_file" style="cursor: pointer;">엑셀 불러오기</label>
                                <input type="file" class="pad4" id="excel_file" hidden accept=".xls,.xlsx">
                            </div>
                            <div class="btn">
                                <a href="/excel/sample.xlsx">양식 다운로드</a>
                            </div>
                            <div class="btn">
                                <div><button class="btn" onclick="addAbBtn()">추가</button></div>
                            </div>
                        </div>
                        <!-- 추가버튼 종료 -->

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

        })

        function go_submit_1() {
            var check = chkFrm('frm_1');
            if (check) {
                frm_1.submit();
            } else {
                return;
            }
        }

        function go_modify(idxk) {
            var group_name = $("#group_name_" + idxk).val();
            if (group_name == "") {
                alert("변경할 그룹명을 입력해 주세요.");
                return;
            }
            _fra.location.href = "adress_action.php?idx=" + idxk + "&mode=udt&group_name=" + group_name + "";
        }

        var check = 0;

        function CheckAll() {
            var boolchk;
            var chk = document.getElementsByName("adr_idx[]")
            if (check) {
                check = 0;
                boolchk = false;
            } else {
                check = 1;
                boolchk = true;
            }
            for (i = 0; i < chk.length; i++) {
                chk[i].checked = boolchk;
            }
        }

        function go_tot_del() {
            var check = chkFrm('frm');
            if (check) {
                if (confirm('선택하신 주소록을 삭제 하시겠습니까?')) {
                    frm.action = "adress_action.php";
                    frm.submit();
                }
            } else {
                false;
            }
        }
    </script>

</body>

</html>