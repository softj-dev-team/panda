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
                <li <? if ($smenu == 1) { ?>class="on" <? } ?>>
                    <a href="../kakao/list.php?bmenu=<?= $bmenu ?>&smenu=1">발신프로필</a>
                </li>
                <li <? if ($smenu == 2) { ?>class="on" <? } ?>>
                    <a href="../kakao/templatelist.php?bmenu=<?= $bmenu ?>&smenu=2">알림톡템플릿</a>
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
                            <th>채널명</th>
                            <th>사업자명</th>
                            <th>등록번호</th>
                            <th>업종</th>
                            <th>고객센터 번호(발신번호)</th>
                            <th>파일</th>
                            <th>프로필키</th>
                            <th>상태</th>
                            <th>상태 변경</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

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
            url: '/kakao/index.php?route=getProfilesForMaster',
            type: 'GET',
            data: { page: page },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var profilesTable = $('#profilesTable tbody');
                    profilesTable.empty();
                    response.profiles.forEach(function(profile) {
                        var row = `<tr>
                            <td>${profile.id}</td>
                            <td>${profile.chananel_name}</td>
                            <td>${profile.business_name}</td>
                            <td>${profile.registration_number}</td>
                            <td>${profile.industry}</td>
                            <td>${profile.cs_phone_number}</td>
                            <td>${profile.file_path ? `<a href="${profile.file_path}" target="_blank">파일 열람</a>` : '없음'}</td>
                            <td><input type="text" name="profile_key" value="${profile.profile_key}" class="profile-key" data-id="${profile.id}" style="width: 90%"></td>
                            <td>${statusMapping[profile.status]}</td>
                            <td>
                                <select class="status-select" data-id="${profile.id}">
                                    <option value="02" ${profile.status === '02' ? 'selected' : ''}>승인대기</option>
                                    <option value="01" ${profile.status === '01' ? 'selected' : ''}>승인</option>
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
                        var pageLink = `<a href="#" class="page-link ${i === page ? 'active' : ''}" data-page="${i}">${i}</a>`;
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
</script>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>