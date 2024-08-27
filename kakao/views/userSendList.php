<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<body>

<!--header-->
<div><?php require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<!--content-->

<section class="sub">
    <div class="sub_title">
        <h2>전송결과</h2>

    </div>

    <div class="adress_btn">
    </div>


    <div class="tab_btn_are">


    </div>

    <form method="post" name="frm" id="frm" target="_fra">

        <div class="tlb center border">
            <table id="listTable">
                <colgroup>

                    <col style="width:12%;">
                    <col style="width:8%">
                    <col style="width:70%">
                    <col style="width:12%">

                </colgroup>
                <thead>
                    <tr>

                        <th>등록일시</th>
                        <th>구분</th>
                        <th>내용</th>
                        <th>결과</th>

                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </form>
</section>


<script>
    loadDataList();
    const statusMapping = {
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
        $.ajax({
            url: '/kakao/index.php?route=getUserAlimTalkSendList',
            type: 'GET',
            data: { page: page },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var table = $('#listTable tbody');
                    table.empty();
                    response.data.forEach(function(data) {
                        var formattedDate = formatDate(data.fetc5);  // 함수 호출
                        var truncatedMessage = data.fmessage.length > 25 ? data.fmessage.substring(0, 25) + '...' : data.fmessage;
                        var row = `<tr>
                        <td>${formattedDate}</td>
                        <td>알림톡</td>
                        <td class="truncated-message" title="${data.fmessage}">
                            ${truncatedMessage}
                        </td>
                        <td>${statusMapping[data.fetc2]}</td>
                    </tr>`;
                        table.append(row);
                    });

                    // 페이징 처리
                    var pageSize = 10; // 한 페이지에 표시할 항목 수
                    var totalRow = response.total; // 총 항목 수
                    var totalPages = Math.ceil(totalRow / pageSize); // 총 페이지 수
                    var currentPage = page; // 현재 페이지
                    var pageSizeGroup = 10; // 페이지 그룹 크기

                    var startPage = Math.floor((currentPage - 1) / pageSizeGroup) * pageSizeGroup + 1;
                    var endPage = startPage + pageSizeGroup - 1;

                    if (endPage > totalPages) {
                        endPage = totalPages;
                    }

                    var pagination = $('#pagination');
                    pagination.empty();

                    // 이전 페이지 링크
                    if (currentPage > 1) {
                        var prevPage = startPage - pageSizeGroup;
                        pagination.append(`<a href="#" class="page-link" data-page="${prevPage > 0 ? prevPage : 1}"> &lt; </a>`);
                    }

                    // 페이지 번호 링크
                    for (var i = startPage; i <= endPage; i++) {
                        var pageLink = `<a href="#" class="page-link ${i === currentPage ? 'on' : ''}" data-page="${i}">${i}</a>`;
                        pagination.append(pageLink);
                    }

                    // 다음 페이지 링크
                    if (endPage < totalPages) {
                        var nextPage = endPage + 1;
                        pagination.append(`<a href="#" class="page-link" data-page="${nextPage}"> &gt; </a>`);
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
    // 페이지 링크 클릭 이벤트 핸들러 추가
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        loadProfiles(page);
    });
</script>
<!--footer-->
<div>