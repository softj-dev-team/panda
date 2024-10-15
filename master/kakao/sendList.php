<?php
include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루
include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 샘플문자페이지 헤더
include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 샘플문자 로그인여부 확인
?>
<style>
    .truncated-message {
        cursor: pointer;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    .truncated-message:hover {
        z-index: 1000;
    }

</style>
<body>
<!-- 팝업 레이어 -->
<div id="sendListPopupLayer" class="popup-layer" style="display:none;">
    <div class="popup-content">
        <div class="poptitle flex-just-end">
            <button type="button" onclick="closeAllPopup()" style="border: none; background: none"><img src="/images/popup/close.svg"></button>
        </div>
        <div class="flex-just-start">
            <select id="filter-field">
                <option value="fcallback">발신번호</option>
                <option value="fdestine">수신번호</option>
                <option value="fetc3">결과코드</option>
                <option value="fetc4">결과메세지</option>
            </select>
            <input id="filter-value" type="text" placeholder="검색조건">
            <button type="button" id="searchBt">검색</button>
        </div>


        <div id="data-table" style="margin-top: 10px"></div>


    </div>
</div>
<div id="wrap" class="skin_type01">
	<?php include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
        <aside id="lnb">
            <h2 class="tit"><span>알림톡</span></h2>
            <ul class="menu">
                <li <? if ($smenu == 1) { ?>class="on" <? } ?>>
                    <a href="../kakao/list.php?bmenu=<?= $bmenu ?>&smenu=1">발신프로필</a>
                </li>
                <li <? if ($smenu == 2) { ?>class="on" <? } ?>>
                    <a href="../kakao/templatelist.php?bmenu=<?= $bmenu ?>&smenu=2">알림톡템플릿</a>
                </li>
                <li <? if ($smenu == 2) { ?>class="on" <? } ?>>
                    <a href="../kakao/sendList.php?bmenu=<?= $bmenu ?>&smenu=3">kakao 전송결과</a>
                </li>
            </ul>
        </aside>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>알림톡</li>
						<li>발신프로필</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>발신프로필</h3>
<!--					<button class="btn_add" onclick="go_regist();" style="width:150px;"><span>샘플문자 등록</span></button>-->
				</div>
				<div class="list">
					<!-- 검색창 시작 -->
					<table class="search">
					    <form name="s_mem" id="s_mem" method="post" action="list.php">
                            <input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
                            <input type="hidden" name="smenu" value="<?=$smenu?>"/>
                            <input type="hidden" name="s_cnt" id="s_cnt" value="<?=$s_cnt?>"/>
                            <input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
                        </form>
						<caption>검색</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:20%;">
							<col style="width:15%;">
							<col style="width:15%;">
							<col style="width:20%;">
							<col style="width:15%;">
						</colgroup>
						<tr>
							<th scope="row">발송일시</th>
                            <td colspan="2">
                                <input type="text" autocomplete="off" readonly="" name="s_date" style="width:40%;" class="datepicker" value="" > ~
                                <input type="text" autocomplete="off" readonly="" name="e_date" style="width:40%;" class="datepicker" value="">
                            </td>
							<th scope="row">검색조건</th>
							<td colspan="2">
                                <input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="">
							</td>
						</tr>

				    </table>
                    <div class="align_r mt20">
                        <button class="btn_search" onclick="loadProfiles();">검색</button>
                        <!--<button class="btn_down" onclick="order_excel_frm.submit();">엑셀다운로드</button>-->
                    </div>

				<!-- 리스트 시작 -->
				<div class="search_wrap">

                    <table class="search_list" id="kakaoSendListTable">
                        <thead>
                        <tr>
                            <th>발송일시</th>
                            <th>발송회원</th>
                            <th>발신번호</th>
                            <th>메세지</th>
                            <th>발신IP</th>
                            <th>발송건수</th>
                            <th>발송내역보기</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

				</div>
                    <div id="pagination" class="pagination"></div>
			</div>

		</div>
		<!-- content 종료 -->
	</div>
</div>
    <link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables/dist/js/tabulator.min.js"></script>
<script>
    let sendListDetail =[];
    $(document).ready(function() {
        loadProfiles();
    });
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

    function loadProfiles(page = 1) {
        var keyword=$('input[name=keyword]').val()
        var s_date=$('input[name=s_date]').val()
        var e_date=$('input[name=e_date]').val()
        $.ajax({
            url: '/kakao/index.php?route=getKakaoSendList',
            type: 'GET',
            data: {
                page: page,
                keyword:keyword,
                s_date:s_date,
                e_date:e_date
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var table = $('#kakaoSendListTable tbody');
                    console.log(response.data)
                    table.empty();
                    response.data.forEach(function(data) {

                        var truncatedMessage = data.fmessage.length > 10 ? data.fmessage.substring(0, 10) + '...' : data.fmessage;
                        var row = `<tr>
                        <td>${data.frsltdate}</td>
                        <td>${data.user_name}<br>(${data.user_id})</td>
                        <td>${data.fdestine}</td>
                        <td class="truncated-message" title="${data.fmessage}">
                            ${truncatedMessage}
                        </td>
                        <td>${data.fetc1}</td>
                        <td>${data.tot_cnt}</td>
                        <td><button type="button" class="sendListDetail" style="padding:3px 5px; border:1px solid gray;" data-id="${data.fetc7}">발송내역보기</button></td>
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
    $(document).on('change', '.status-select', function() {
        var id = $(this).data('id');
        var status = $(this).val();
        var profile_key = $(`input.profile-key[data-id="${id}"]`).val();
        $.ajax({
            url: '/kakao/index.php?route=updateStatus',
            type: 'POST',
            data: { id: id, status: status ,profile_key :profile_key},
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (!response.success) {
                    loadProfiles();
                }
            },
            error: function(xhr, status, error) {
                alert('상태 업데이트에 실패했습니다. 다시 시도해 주세요.');
                console.error('Error: ' + error);
                console.error('Status: ' + status);
                console.dir(xhr);
            }
        });
    });
    $(function() {
        var today = new Date();
        var oneDayAgo = new Date();
        var oneMonthAgo = new Date();
        oneMonthAgo.setMonth(today.getMonth() - 1);
        oneDayAgo.setDate(today.getDate() - 1);  // 하루 전
        // 날짜 형식을 YYYY-MM-DD로 변환하는 함수
        function formatDate(date) {
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            return date.getFullYear() + "-" + month + "-" + day;
        }
        // Datepicker 초기화 및 날짜 기본값 설정
        $("input[name=s_date]").datepicker({
            dateFormat: 'yy-mm-dd'
        }).val(formatDate(oneDayAgo)); // 한 달 전 날짜 기본값

        $("input[name=e_date]").datepicker({
            dateFormat: 'yy-mm-dd'
        }).val(formatDate(today)); // 오늘 날짜 기본값
        $(".datepicker").datepicker({
            changeYear: true,
            changeMonth: true,
            minDate: '-90y',
            yearRange: 'c-90:c',
            dateFormat: 'yy-mm-dd',
            showMonthAfterYear: true,
            constrainInput: true,
            dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
            monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월']
        });
    });
    $(document).on('click','.sendListDetail',function() {
        var group_key = $(this).attr('data-id');//큰숫자가 주입되는경우 변환될수 있으니 그대로 출력한다.
        var fieldEl = document.getElementById("filter-field");
        var valueEl = document.getElementById("filter-value");
        var filterVal = fieldEl.options[fieldEl.selectedIndex].value;
        var filter = filterVal == "function" ? customFilter : filterVal;
        console.log(group_key)
        $.ajax({
            url: '/kakao/index.php?route=getKakaoSendListDetail',
            type: 'GET',
            data: {
                // page: page,
                // keyword:keyword,
                // s_date:s_date,
                // e_date:e_date,
                group_key:group_key
            },
            dataType: 'json',
            success: function(response) {
                console.log(response.data)

                $('#sendListPopupLayer').show()
                var popupTable = new Tabulator("#data-table", {
                    data: response.data, // table.getData()로 가져온 데이터를 사용
                    layout: "fitColumns",
                    pagination: "true",  // 로컬 페이징
                    paginationSize: 20,   // 20건씩 표시
                    selectable: true,  // 다중 선택 가능
                    selectableRangeMode: "drag",  // 마우스 드래그로 범위 선택
                    selectablePersistence: false,  // 페이징 변경 후에도 선택 상태 유지
                    selectableRollingSelection:true,
                    selectableCheck: function(row) {
                        // 모든 행을 선택 가능하게 설정
                        return true;
                    },
                    columns: [
                        // { title: "선택", field: "select", formatter: "rowSelection", width: 50, hozAlign: "center", headerSort: false, cellClick: function(e, cell) {
                        //         // cellClick 이벤트는 필요 없을 수 있습니다.
                        //     }},
                        { title: "전송일시", field: "finsertdate", sorter: "string" },
                        { title: "발신번호", field: "fcallback", sorter: "string" },
                        { title: "수신번호", field: "fdestine", sorter: "string" },
                        { title: "결과코드", field: "fetc3", sorter: "string" },
                        { title: "결과메세지", field: "fetc4", sorter: "string" },
                        // { title: "결과코드", field: "fetc2", formatter: function(cell, formatterParams) {
                        //         // 발송여부 값을 텍스트로 변환하여 표시
                        //         let value = cell.getValue();
                        //         return value === "AS" ? "발송" : (value === "AS" ? "발송완료" : value);
                        //     }}
                    ],
                    // dataChanged: function(data) {
                    //     checkAllStatusAndMoveToNextPage();  // 데이터 변경 시 자동 페이지 이동 체크
                    // },
                    // tableBuilt: function() {
                    //     checkAllStatusAndMoveToNextPage();  // 테이블이 처음 렌더링될 때 자동 페이지 이동 체크
                    // },
                });
                $(document).on('click','#searchBt',function (){
                    popupTable.setFilter(filter,'like', valueEl.value);
                })

            },
            error: function(xhr, status, error) {
                alert('목록을 불러오는 데 실패했습니다. 다시 시도해 주세요.');
                console.log('Error: ' + error);
                console.log('Status: ' + status);
                console.dir(xhr);
            }
        });
        // let tableData = table.getData();
        // if(tableData.length <= 0){
        //     alert("수신번호를 입력해 주세요.");
        // }else{

        //     // 기본값 00 설정
        //     tableData.forEach((item, index) => {
        //         if (!item.id) {
        //             item.id = index + 1;  // index 기반으로 id 필드를 추가
        //         }
        //         if (!item.status) {
        //             item.status = "00";  // 발송여부 기본값 00 설정
        //         }
        //     });

        // }
    });
</script>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>