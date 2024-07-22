<?php
include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루
include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 샘플문자페이지 헤더
include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 샘플문자 로그인여부 확인
?>

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
							<th scope="row"></th>
							<td colspan="2">

							</td>
							<th scope="row"></th>
							<td colspan="2">

							</td>
						</tr>
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
                            <th>발신프로필</th>
                            <th>템플릿명</th>
                            <th>카테고리</th>
                            <th>유형</th>
                            <td>강조표기문구</td>
                            <td>강조표기보조문구</td>
                            <th>내용</th>
                            <th>부가정보</th>
                            <th>파일</th>
                            <th>템플릿키</th>
                            <th>상태</th>
                            <th>상태 변경</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div id="pagination" class="pagination"></div>
				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>
<script>
    loadProfiles();
    const statusMapping = {
        '01': '승인',
        '02': '승인대기'
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
                        var row = `<tr>
                            <td>${template.id}</td>
                            <td>${template.profile_key}<br>${template.business_name}</td>
                            <td>${template.template_name}</td>
                            <td>${template.category_name}</td>
                            <td>${template.template_type}</td>
                            <td>${template.strong_title}</td>
                            <td>${template.strong_sub_title}</td>
                            <td style="white-space: pre-line; ">${template.template_title}</td>
                            <td>${template.template_subtitle}</td>
                            <td>${template.image_path ? `<a href="${template.image_path}" target="_blank">파일 열람</a>` : '없음'}</td>
                            <td><input type="text" name="template_key" value="${template.template_key}" class="template-key" data-id="${template.id}" style="width: 90%"></td>
                            <td>${statusMapping[template.status]}</td>
                            <td>
                                <select class="status-select" data-id="${template.id}">
                                    <option value="02" ${template.status === '02' ? 'selected' : ''}>승인대기</option>
                                    <option value="01" ${template.status === '01' ? 'selected' : ''}>승인</option>
                                </select>
                            </td>
                        </tr>`;
                        profilesTable.append(row);
                    });

                    // 페이징 처리
                    var totalPages = Math.ceil(response.total / 10);
                    var pagination = $('#pagination');
                    pagination.empty();
                    for (var i = 1; i <= totalPages; i++) {
                        var pageLink = `<a href="#" class="page-link ${i === page ? 'on' : ''}" data-page="${i}">${i}</a>`;
                        pagination.append(pageLink);
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