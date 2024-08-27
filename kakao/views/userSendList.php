<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<body>

<!--header-->
<div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<!--content-->

<section class="sub">
    <div class="sub_title">
        <h2>전송결과</h2>

    </div>

    <div class="adress_btn">
    </div>


    <div class="tab_btn_are">

        <form name="s_mem" id="s_mem" method="post" action="send.php">
            <div class="input_tab">
                <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>">
                <a href="javascript:s_mem.submit();">
                    <img src="images/search.png">
                </a>
            </div>
        </form>

        <div class="btn">
            <a href="./send_success_down.php">성공내역 엑셀다운로드</a>
            <a href="./send_fail_down.php">실패내역 엑셀다운로드</a>
            <a href="javascript:go_tot_del();">내역삭제</a>
        </div>

    </div>

    <form method="post" name="frm" id="frm" target="_fra">
        <input type="hidden" name="pageNo" value="<?= $pageNo ?>" />
        <input type="hidden" name="total_param" value="<?= $total_param ?>" />
        <div class="tlb center border">
            <table>
                <colgroup>
                    <col style="width:4%;">
                    <col style="width:10%;">
                    <col style="width:6%">
                    <col style="width:20%">
                    <col style="width:10%">
                    <col style="width:25%">
                    <col style="width:7%">
                    <col style="width:6%">
                    <col style="width:6%">
                    <col style="width:6%">
                </colgroup>
                <tr>
                    <th class="check"><input type="checkbox" onclick="javascript:CheckAll()"></th>
                    <th>등록일시</th>
                    <th>구분</th>
                    <th>제목</th>
                    <th>이미지</th>
                    <th>내용</th>
                    <th>총건수</th>
                    <th>성공</th>
                    <th>실패</th>
                    <th>잔여</th>
                    <!--<th>결과</th>
                <th>비고</th>-->
                </tr>
            </table>
        </div>
    </form>
</section>

<script src="/kakao/public/js/kakao.js"></script>
<script>

</script>
<!--footer-->
<div>