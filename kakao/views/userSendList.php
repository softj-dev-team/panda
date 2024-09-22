<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<body>

<!--header-->
<div><?php require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<!--content-->

<section class="sub">
    <div class="sub_title">
        <h2>알림톡 전송결과</h2>

    </div>

    <div class="adress_btn">
    </div>


    <div class="tab_btn_are">
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
                        <th>수신자 번호</th>
                        <th>내용</th>
                        <th>결과</th>

                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
            <div id="pagination" class="pagenation"></div>
        </div>
    </form>
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

        var requestData = {
            page: page
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
                        var row = `<tr>
                        <td>${formattedDate}</td>
                        <td>${data.fdestine}</td>
                        <td class="truncated-message" title="${data.fmessage}">
                            ${truncatedMessage}
                        </td>
                        <td>${sendStatusMapping[data.fetc2]}</td>
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
</script>
<!--footer-->
<div>