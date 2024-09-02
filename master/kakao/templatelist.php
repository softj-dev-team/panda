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
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
        <aside id="lnb">
            <h2 class="tit"><span>알림톡</span></h2>
            <ul class="menu">
                <ul class="menu">
                    <li <? if ($smenu == 1) { ?>class="on" <? } ?>>
                        <a href="../kakao/list.php?bmenu=<?= $bmenu ?>&smenu=1">발신프로필</a>
                    </li>
                    <li <? if ($smenu == 2) { ?>class="on" <? } ?>>
                        <a href="../kakao/templatelist.php?bmenu=<?= $bmenu ?>&smenu=2">알림톡템플릿</a>
                    </li>
                    <li <? if ($smenu == 2) { ?>class="on" <? } ?>>
                        <a href="../kakao/sendList.php?bmenu=<?= $bmenu ?>&smenu=3">알림톡 발송내역</a>
                    </li>
                </ul>

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

				    </table>
				<!-- 검색창 종료 -->
				<div class="align_r mt20">
					<!--<button class="btn_down">엑셀다운로드</button>-->
<!--					<button class="btn_search" onclick="s_mem.submit();">검색</button>-->
				</div>

				<!-- 리스트 시작 -->
				<div class="search_wrap">

                    <table class="search_list" id="profilesTable">
                        <thead>
                        <tr>
                            <th>NO</th>
                            <th>신청자</th>
                            <th>발신프로필</th>
                            <th>템플릿명</th>
                            <th>템플릿키</th>
                            <th>카테고리</th>
                            <th>메세지</th>
                            <th>템플릿 유형</th>
                            <th>강조표기 유형</th>
                            <th>강조표기문구</th>
                            <th>강조표기보조문구</th>

                            <th>부가정보</th>
                            <th>파일</th>
                            <th>이용상태</th>
                            <th>검수상태</th>

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
<script>
    loadProfiles();
    const statusMapping = {
        '01': '승인',
        '02': '승인대기',
        'R': '승인대기',
        'A': '정상',
        'S': '중단'
    };
    const inspectionStatusMapping = {
        // REG : 등록, REQ : 심사요청, APR : 승인,
        // REJ : 반려
        'REG': '등록',
        'REQ': '검수요청',
        'APR': '승인',
        'REJ': '반려'
    };
    const templateTypeMapping = {
        'BA': '기본형',
        'EX': '부가정보형',
        'AD': '채널추가형',
        'MI': '복합형',
        'ITEM_LIST': '아이템리스트형',
        'TEXT': '강조표기형'
    };
    const template_emphasize_type = {
        'NONE': '선택안함',
        'ITEM_LIST': '아이템리스트형',
        'TEXT': '강조표기형'
    };
    function loadProfiles(page = 1) {
        $.ajax({
            url: '/kakao/index.php?route=getTemplate',
            type: 'GET',
            data: { page: page },
            dataType: 'json',
            success: function(response) {
                if (response.success) {

                    var profilesTable = $('#profilesTable tbody');
                    profilesTable.empty();
                    response.template.forEach(function(template) {
                        var statusText = statusMapping[template.status];
                        var templateTypeText = templateTypeMapping[template.template_type];
                        var inspectionStatusText = inspectionStatusMapping[template.inspection_status];
                        var templateEmphasizeTypeText = template_emphasize_type[template.template_emphasize_type];
                        var row = `
                            <tr">
                                <td>${template.id}</td>
                                <td>${template.user_id}<br>${template.user_name}</td>
                                <td>${template.profile_key}<br>${template.chananel_name}</td>
                                <td>${template.template_name}</td>
                                <td>${template.template_key}</td>
                                <td>${template.category_id}</td>
                                <td class="truncated-message" title="${template.template_title}">
                                    ${template.template_title}
                                </td>
                                <td>${templateTypeText}</td>
                                <td>${templateEmphasizeTypeText}</td>
                                <td>${template.strong_title}</td>
                                <td>${template.strong_sub_title}</td>
                                <td>${template.template_subtitle}</td>
                                <td>${template.image_path ? `<a href="${template.image_path}" target="_blank">파일 열람</a>` : '없음'}</td>
                                <td>${statusText}</td>
                                <td>${inspectionStatusText}</td>
                            </tr>

                            `;
                        profilesTable.append(row);
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
                console.error('Error: ' + error);
                console.error('Status: ' + status);
                console.dir(xhr);
            }
        });
    }

    $(document).on('click', '.page-link', function(event) {
        event.preventDefault();
        var page = $(this).data('page');
        loadProfiles(page);
    });
    $(document).on('change', '.status-select', function() {
        var id = $(this).data('id');
        var status = $(this).val();
        var template_key = $(`input.template-key[data-id="${id}"]`).val();
        $.ajax({
            url: '/kakao/index.php?route=updateTemplateStatus',
            type: 'POST',
            data: { id: id, status: status ,template_key: template_key},
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

</script>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>