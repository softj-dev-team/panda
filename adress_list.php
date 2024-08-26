<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드 
?>
<?
$member_idx = $_SESSION['member_coinc_idx'];

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$group_idx = sqlfilter($_REQUEST['group_idx']);

################## 파라미터 조합 #####################
$total_param = 'group_idx=' . $group_idx . '&field=' . $field . '&keyword=' . $keyword;

if (!$pageNo) {
    $pageNo = 1;
}

$where = " and address_group_num.member_idx='" . $member_idx . "' and address_group_num.group_idx='" . $group_idx . "' ";

if ($keyword) {
    $where .= " and receive_num like '%" . $keyword . "%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo - 1) * $pageScale;

$StarRowNum = (($pageNo - 1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select address_group_num.*, address_group.group_name from address_group_num 
inner join address_group on address_group_num.group_idx = address_group.idx" . $where . $order_by . " limit " . $StarRowNum . " , " . $EndRowNum;
//echo $query;
$result = mysqli_query($gconnet, $query);

$query_cnt = "select address_group_num.idx from address_group_num inner join address_group on address_group_num.group_idx = address_group.idx where 1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage    = ($iTotalSubCnt - 1) / $pageScale  + 1;

$query_group = "select * from address_group where 1 and idx='" . $group_idx . "' order by idx desc";
//echo $query;
$result_group = mysqli_query($gconnet, $query_group);
$row_group = mysqli_fetch_array($result_group);
?>
<link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables/dist/js/tabulator.min.js"></script>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <div class="sub_title">
            <h2>연락처 목록(그룹명 : <?= $row_group['group_name'] ?>)</h2>

        </div>

        <div class="adress_btn">
            <div class="sms_btn_inupt">
                <a href="/pandasms_sample.txt" download>텍스트 파일 샘플 다운로드</a>
                <a href="/pandasms_sample.xlsx">엑셀 파일 샘플 다운로드</a>
            </div>
            <div class="sms_btn_inupt" style="margin-top:10px">
                <a href="javascript:getTextFile();">텍스트 붙여넣기</a>
                <a href="javascript:getExcelFile();">엑셀 붙여넣기</a>
                <a href="#layer3" class="btn-example">직접 붙여넣기</a>
            </div>
            <input type="file" id="text_file" hidden accept=".txt">
            <input type="file" id="excel_file" hidden accept=".xls,.xlsx">
        </div>


        <div class="tab_btn_are">
            <form name="s_mem" id="s_mem" method="post" action="adress.php">
                <div class="input_tab">
                    <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>" placeholder="번호검색">
                    <a href="javascript:s_mem.submit();">
                        <img src="images/search.png">
                    </a>
                </div>
            </form>

            <form method="post" action="adress_action.php" name="frm_1" target="_fra" id="frm_1" enctype="multipart/form-data">
                <input type="hidden" name="mode" id="mode_1" value="ins">
                <div class="btn">
                    <a href="javascript:go_tot_del();">선택 삭제</a>
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
                        <th>이름</th>
                        <th>번호</th>
                    </tr>
                    <?
                    for ($i = 0; $i < mysqli_num_rows($result); $i++) { // 대분류 루프 시작
                        $row = mysqli_fetch_array($result);
                        $listnum    = $iTotalSubCnt - (($pageNo - 1) * $pageScale) - $i;
                    ?>
                        <tr>
                            <td class="check"><input type="checkbox" name="adr_idx[]" id="adr_idx[]" value="<?= $row["idx"] ?>" required="yes" message="주소록 그룹"></td>
                            <td><?= $row['group_name'] ?></td>
                            <td>
                                <div style="display:flex"><input type="text" class="receive_name" value="<?= $row['receive_name'] ?>"><a class="btn-example btn btn-edit-name" data-id="<?= $row["idx"] ?>">수정</a></div>
                            </td>
                            <td>
                                <div style="display:flex">
                                    <input type="text" class="receive_num" value="<?= $row['receive_num'] ?>"><a class="btn-example btn btn-edit-num" data-id="<?= $row["idx"] ?>">수정</a>
                                </div>
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
                <li>중복된 연락처는 추가되지 않습니다.</li>
                <li>한 그룹에 최대 100,000개의 연락처를 등록하실 수 있습니다.</li>
            </ul>
        </div>

    </section>



    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

    <div id="layer3" class="pop-layer">
        <div class="pop-container">
            <div class="popcontent">
                <div class="poptitle">
                    <h2>
                        받는사람 추가하기
                    </h2>
                    <a href="#" class="btn-layerClose close">
                        <img src="images/popup/close.svg">
                    </a>
                </div>

                <ul class="tabs wide">
                    <li class="tab-link current" data-tab="tab-7">직접 붙여넣기</li>
                    <li class="tab-link" data-tab="tab-8">엑셀 붙여넣기</li>
                </ul>
                <div id="tab-7" class="tab-content current">

                    <textarea placeholder="입력방법 : 01000000001,01000000002" class="top25 h200" id="text_add_val"></textarea>

                    <div class="point_pop">
                        <h2>
                            <span><img src="images/popup/point.svg"></span>
                            알림
                        </h2>
                        <ul class="list_ul">
                            <li>최대 50,000개까지 등록할 수 있습니다.</li>
                            <li>핸드폰 번호는 엔터(Enter)또는 콤마(,)로 구분하여 입력해야 합니다.</li>
                        </ul>

                    </div>

                </div>
                <div id="tab-8" class="tab-content">



                    <div class="tlb center xcel">
                        <table id="excel_copy">

                        </table>
                    </div>


                    <div class="point_pop">
                        <h2>
                            <span><img src="images/popup/point.svg"></span>
                            알림
                        </h2>
                        <ul class="list_ul">
                            <li>최대 100,000개까지 등록할 수 있습니다.</li>
                            <li>이름, 전화번호 순으로 등록해 주세요.</li>
                        </ul>

                    </div>


                </div>
                <div class="btn_are_pop">
                    <a href="#" class="btn-layerClose btn btn02" id="text_add_btn">
                        추가
                    </a>
                    <a href="#" class="btn-layerClose btn">
                        닫기
                    </a>
                </div>

            </div>
        </div>
    </div>


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
            });



        });

        var table = new Tabulator("#excel_copy", {
            height: "311px",
            data: [],
            placeholder: "복사(Ctrl+C)한 내용을 여기에 붙여넣기(Ctrl+V) 해주세요.",
            clipboard: true,
            clipboardPasteAction: "replace",
            columns: [{
                    title: "이름",
                    field: "name",
                    width: 104,
                },
                {
                    title: "전화번호",
                    field: "number",
                    width: 104,
                    sorter: "number",
                },

            ],
        });
        table.on("dataLoading", function(data) {
            if (data.length) {
                data.forEach(function(elem, index) {
                    if (elem.number) {
                        elem.number = elem.number.replace(/\D/g, '')
                    } else {
                        data.splice(index, 1);
                    }

                })
            }
        });

        $(".btn-edit-num").click(function() {
            var idx = $(this).data("id");
            var num = $(this).parent().find(".receive_num").val();
            console.log(idx, num);

            $.ajax({
                url: "./address_edit_num.php",
                type: "POST",
                data: {
                    idx: idx,
                    num: num
                },
                async: false,
                dataType: "json",
                success: function(v) {
                    if (v.result == "success") {
                        alert("수정되었습니다.");
                        location.reload();
                    } else if (v.result == "duplicate") {
                        alert("중복된 번호가 있습니다.");
                    } else {
                        alert("수정에 실패하였습니다.");
                    }
                },
            });
        });

        $(".btn-edit-name").click(function() {
            var idx = $(this).data("id");
            var name = $(this).parent().find(".receive_name").val();
            console.log(idx, name);

            $.ajax({
                url: "./address_edit_name.php",
                type: "POST",
                data: {
                    idx: idx,
                    name: name
                },
                async: false,
                dataType: "json",
                success: function(v) {
                    if (v.result == "success") {
                        alert("수정되었습니다.");
                        location.reload();
                    } else {
                        alert("수정에 실패하였습니다.");
                    }
                },
            });
        });



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
                    frm.action = "address_list_delete.php";
                    frm.submit();
                }
            } else {
                false;
            }
        }

        /* 파일붙여넣기 & 엑셀붙여넣기 시작 */
        // 파일붙여넣기
        function getTextFile() {
            $('#text_file').click();
        }
        // 엑셀에 붙여넣기
        function getExcelFile() {
            $('#excel_file').click();
        }

        // 텍스트 불러오기
        $("#text_file").on('change', function() {
            $('#cell_receive_list').html('');
            $('#cell_receive_cnt').text('0');
            let ext = $("#text_file").val().split(".").pop().toLowerCase();
            if ($.inArray(ext, ["txt"]) == -1) {
                alert("텍스트 파일만 첨부 가능합니다.");
                $("#text_file").val("");
                return false;
            } else {
                readText(async function(result) {
                    //alert(result);
                    let list = result.split((/,| |\r\n/));

                    if (list.length > 300000) {
                        alert('최대 300,000개까지 등록할 수 있습니다.');
                    } else {
                        //list = await rejectHpCheck(list, 0);
                        list = await checkDuplicateText(list);
                        if (list != undefined) {
                            $.ajax({
                                url: "./address_add.php",
                                type: "POST",
                                data: {
                                    group_idx: <?= $group_idx ?>,
                                    list: list
                                },
                                async: true,
                                dataType: "jsonp",
                                success: function(v) {
                                    console.log(v);

                                },
                            });
                            alert("연락처가 추가되었습니다.");
                            location.reload();
                        }
                    }
                });
            }
            $(this).val('');
        });

        $("#text_add_btn").click(async function() {
            if ($("#tab-7").hasClass('current')) {
                let list = $("#text_add_val").val().replaceAll('-', '').split(/\,|\s+|\n/);
                if (list.length > 300000) {
                    alert('최대 300,000개까지 등록할 수 있습니다.');
                } else {
                    //list = await rejectHpCheck(list, 0);
                    list = await checkDuplicateText(list);
                    console.log(list);
                    if (list != undefined) {
                        $.ajax({
                            url: "./address_add.php",
                            type: "POST",
                            data: {
                                group_idx: <?= $group_idx ?>,
                                list: list
                            },
                            async: true,
                            dataType: "jsonp",
                            success: function(v) {
                                console.log(v);

                            },
                        });
                        alert("연락처가 추가되었습니다.");
                        $("#text_add_val").val('');
                        location.reload();
                    }
                }
            } else if ($("#tab-8").hasClass('current')) {

                let list = table.getData();
                console.log(list);
                if (list.length > 300000) {
                    alert('최대 300,000개까지 등록할 수 있습니다.');
                } else {
                    //list = await rejectHpCheck(list, 0);
                    list = await checkDuplicateExcelCopy(list);
                    if (list != undefined) {
                        $.ajax({
                            url: "./address_add_excel_copy.php",
                            type: "POST",
                            data: {
                                group_idx: <?= $group_idx ?>,
                                list: JSON.stringify(list)
                            },
                            async: true,
                            dataType: "jsonp",
                            success: function(v) {
                                console.log(v);
                            },
                        });
                        alert("연락처가 추가되었습니다.");
                        $("#text_add_val").val('');
                        location.reload();
                    }
                }
            }

        });

        async function checkDuplicateExcelCopy(result_arr) {
            const newArray = result_arr.filter((item, i) => {
                console.log(item);
                return (
                    result_arr.findIndex((item2, j) => {
                        return item['number'] === item2['number'];
                    }) === i
                );
            });
            return newArray;
        }

        // 엑셀 불러오기
        $("#excel_file").on('change', function() {
            $('#cell_receive_list').html('');
            $('#cell_receive_cnt').text('0');
            let ext = $("#excel_file").val().split(".").pop().toLowerCase();
            if ($.inArray(ext, ["xls", "xlsx"]) == -1) {
                alert("엑셀 파일만 첨부 가능합니다.");
                $("#excel_file").val("");
                return false;
            } else {
                readExcel(async function(result) {
                    // 타이틀 체크
                    if (Object.keys(result[0]).includes('NAME') && Object.keys(result[0]).includes('HP')) {
                        if (result.length > 300000) {
                            alert('최대 300,000개까지 등록할 수 있습니다.');
                        } else {
                            //result = await rejectHpCheck(result, 1);
                            result = await checkDuplicateExcel(result);
                            if (result != undefined) {
                                $.ajax({
                                    url: "./address_add_excel.php",
                                    type: "POST",
                                    data: {
                                        group_idx: <?= $group_idx ?>,
                                        list: JSON.stringify(result)
                                    },
                                    async: true,
                                    dataType: "jsonp",
                                    success: function(v) {
                                        //console.log(v);
                                    },
                                });
                                alert("연락처가 추가되었습니다.");
                                location.reload();
                            }
                        }

                    } else {
                        alert('엑셀 양식을 참고해주세요.\n헤더는 [이름 = NAME, 번호 = HP]이 되어야합니다.');
                    }
                });
            }
            $(this).val('');
        });




        /* 파일붙여넣기 & 엑셀붙여넣기 끝 */

        // 전체 선택 버튼
        function allSelectBtn() {
            if ($('input[name=receive_cell_num]').length > $('input[name=receive_cell_num]:checked').length) {
                $('input[name=receive_cell_num]').prop('checked', true);
            } else {
                $('input[name=receive_cell_num]').prop('checked', false);
            }
        }

        // 연락처 삭제 버튼
        function abDelete() {
            $('input[name=receive_cell_num]:checked').each(function(idx, el) {
                let parentIndex = $(this).parent().index();
                $('#cell_receive_list').find('div').eq(parentIndex).remove();
            });
            $('#cell_receive_cnt').text($('#cell_receive_list').find('div').length);
        }

        async function checkDuplicateExcel(result_arr) {
            const newArray = result_arr.filter((item, i) => {
                console.log(item);
                return (
                    result_arr.findIndex((item2, j) => {
                        return item.HP === item2.HP;
                    }) === i
                );
            });
            return newArray;
        }

        async function checkDuplicateText(result_arr) {
            const set = new Set(result_arr);
            console.log(set);
            const uniqueArr = [...set];
            return uniqueArr;
        }

        function isOnlyNumbersAndHyphen(str) {
            const regex = /^[0-9\-]+$/;
            return regex.test(str);
        }
    </script>

</body>

</html>