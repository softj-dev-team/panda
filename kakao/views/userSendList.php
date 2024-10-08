<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<body>

<!--header-->
<div><?php require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<!--content-->
<section class="sub sub_min">
    <div class="sub_title">
        <h2>알림톡 전송결과</h2>
    </div>
    <div class="flex-just-start">
        <div class="sendContentBox p35">
            <h3>알림톡 내용</h3>
            <div id="preview">

                <div id="previewChannelName">채널명</div>
                <div id="previewTemplate">
                    <div class="highlight-container">
                        <div class="highlight-header">
                            <span>알림톡 도착</span>
                            <div class="jss1382">kakako</div>
                        </div>
                        <div class="highlight-body">
                            <div class="image-wrapper">
                                <img id="uploadedImage" src="" alt="Uploaded Logo">
                            </div>
                            <div class="template-header blind">템플릿 헤더</div>
                            <div class="highlight-box blind">
                                <div>
                                    <div class="highlight-title-view blind" >하이라이트 타이틀</div>
                                    <div class="highlight-description-view blind">하이라이트 설명</div>
                                </div>
                                <div class="highlight-thumbnail">
                                    <img id="HighlightThumbnailImg" src="">
                                </div>
                            </div>
                            <div class="item-list-box blind">

                            </div>
                            <div id="previewStrongSubTitle" class="previewStrongSubTitle"></div>
                            <div id="previewStrongTitle" class="previewStrongTitle"></div>
                            <div id="previewHighlightTitle"></div>
                            <div id="previewHighlightSubtitle"></div>
                            <div id="previewChButtonList">

                            </div>
                            <div id="previeButtonList"></div>
                        </div>
                    </div>
                    <div class="quickLinkList"></div>
                </div>
            </div>
            <p class="preview-note">미리보기는 실제 단말기와 차이가 있을 수 있습니다.</p>
        </div>
        <div class="sendContentDetailBox w100">
            <table>
                <thead>

                </thead>
                <tbody>
                <tr><th>발송방법</th><td class="rowDataSmsType"></td></tr>
                <tr><th>실사용금액</th><td ><span class="rowDataUseSumPoint">00.00</span> 원</td></tr>
                <tr><th rowspan="4">발송내역</th><td>발송 시도건수 <span class="rowDataTotSendCnt">0</span> 건</td></tr>
                <tr><td>발송 성공 <span class="rowDataSuccesSendCnt">0</span> 건</td></tr>
                <tr><td>발송 실패 <span class="rowDataFaileTotSendCnt">0</span> 건</td></tr>
                </tbody>
            </table>
            <div class="tab_btn_are">
                <div class="btn ">

                    <input type="text" style="width:120px;" class="datepicker" id="s_date" name="s_date" value="">
                    <span> ~ </span>
                    <input type="text" style="width:120px;" class="datepicker" id="e_date" name="e_date" value="">
                    <a href="javascript:loadDataList();" class="blue">조회</a>
                </div>
                <div class="input_tab">
                    <input type="text" name="keyword" id="keyword" >
                    <a href="#" id="goSearch">
                        <img src="/images/search.png">
                    </a>
                </div>

            </div>

            <form method="post" name="frm" id="frm" target="_fra">

                <div class="tlb center border">
                    <table id="listTable">
                        <colgroup>

                            <col style="width:20%;">
                            <col style="width:25%">
                            <col style="width:30%">
                            <col style="width:20%">

                        </colgroup>
                        <thead>
                        <tr>

                            <th>등록일시</th>
                            <th>구분</th>
                            <th>발신 번호</th>
                            <th>총건수</th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                    <div id="pagination" class="pagenation"></div>
                </div>
            </form>
        </div>
    </div>

</section>
<section class="sub sub_min">



</section>


<script>
    loadDataList();
    const sendStatusMapping = {
        'AS': '알림톡/친구톡 발송 성공',
        'AF': '알림톡/친구톡 발송 실패',
        'SS': '문자 발송 성공',
        'SF': '문자 발송 실패',
        'EW': 'SMS/LMS 발송 중, 내부 처리 중',
        'EL': '발송결과 조회 데이터 없음',
        'EF': '시스템 실패 처리 (공백, 크기 초과, 고객사 검증 등)',
        'EE': '시스템 오류',
        'EO': '시스템 타임아웃'
    };

    function loadDataList(page = 1) {
        var keyword = $('#keyword').val().trim(); // #keyword 요소의 값을 가져오고 공백 제거
        var s_date = $('input[name=s_date]').val();
        var e_date = $('input[name=e_date]').val();
        var requestData = {
            page: page,
            s_date:s_date,
            e_date:e_date
        };

        if (keyword) {
            requestData.keyword = keyword.replace(/[^0-9]/g, ''); // 특수문자 제거
        }

        $.ajax({
            url: '/kakao/index.php?route=getUserAlimTalkSendList',
            type: 'GET',
            data: requestData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var table = $('#listTable tbody');
                    table.empty();
                    response.data.forEach(function(data) {
                        var formattedDate = formatDate14(data.fetc5);  // 함수 호출
                        var truncatedMessage = data.fmessage.length > 25 ? data.fmessage.substring(0, 25) + '...' : data.fmessage;
                        var row = `<tr class="sendResultDataRow" data-id="${data.fetc7}">
                        <td>${formattedDate}</td>
                        <td>알림톡</td>
                        <td>
                           ${data.fdestine}
                        </td>
                        <td>${data.tot_cnt}</td>
                    </tr>`;
                        table.append(row);
                    });

                    var pageSize = 10;
                    var totalRow = response.total;
                    var totalPages = Math.ceil(totalRow / pageSize);
                    var currentPage = page;
                    var pageSizeGroup = 10;

                    var startPage = Math.floor((currentPage - 1) / pageSizeGroup) * pageSizeGroup + 1;
                    var endPage = startPage + pageSizeGroup - 1;

                    if (endPage > totalPages) {
                        endPage = totalPages;
                    }

                    var pagination = $('#pagination');
                    pagination.empty();

                    if (currentPage > 1) {
                        var prevPage = startPage - pageSizeGroup;
                        pagination.append(`<a href="#" class="page-link pre" data-page="${prevPage > 0 ? prevPage : 1}"> <img src="/images/pagenation/l.png"></a>`);
                    }

                    for (var i = startPage; i <= endPage; i++) {
                        var pageLink = `<a href="#" class="page-link ${i === currentPage ? 'atv' : ''}" data-page="${i}">${i}</a>`;
                        pagination.append(pageLink);
                    }

                    if (endPage < totalPages) {
                        var nextPage = endPage + 1;
                        pagination.append(`<a href="#" class="page-link next" data-page="${nextPage}"><img src="/images/pagenation/r.png"></a>`);
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('신청 목록을 불러오는 데 실패했습니다. 다시 시도해 주세요.');
                console.log('Error: ' + error);
                console.log('Status: ' + status);
                console.dir(xhr);
            }
        });
    }

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        loadDataList(page);
    });

    $(document).on('click', '#goSearch', function(e) {
        e.preventDefault();
        loadDataList();
    });
    $(function() {
        var today = new Date();
        var oneMonthAgo = new Date();
        oneMonthAgo.setMonth(today.getMonth() - 1);
        // 날짜 형식을 YYYY-MM-DD로 변환하는 함수
        function formatDate(date) {
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            return date.getFullYear() + "-" + month + "-" + day;
        }
        // Datepicker 초기화 및 날짜 기본값 설정
        $("#s_date").datepicker({
            dateFormat: 'yy-mm-dd'
        }).val(formatDate(oneMonthAgo)); // 한 달 전 날짜 기본값

        $("#e_date").datepicker({
            dateFormat: 'yy-mm-dd'
        }).val(formatDate(today)); // 오늘 날짜 기본값
        $(".datepicker").datepicker({
            changeYear:true,
            changeMonth:true,
            minDate: '-90y',
            yearRange: 'c-90:c',
            dateFormat:'yy-mm-dd',
            showMonthAfterYear:true,
            constrainInput: true,
            dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
            monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
        });
    });
</script>
<!--footer-->
<div>