<?php

include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더
include $_SERVER["DOCUMENT_ROOT"] . "/manage/include/check_login.php"; // 관리자 로그인여부 확인

$target_idx = trim(sqlfilter($_REQUEST['target_idx']));

$query = " SELECT subject FROM board_category WHERE idx='$target_idx' LIMIT 1 ";
$result = mysqli_query($gconnet, $query);
$keyword = mysqli_fetch_array($result)['subject'];

?>
<body>
	<!-- content 시작 -->
	<div class="content" style="position:relative; padding:0 10px 0 10px;">
		<!-- 네비게이션 시작 -->
		<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
		<div class="navi">
			<ul class="clearfix">
				<li>HOME</li>
				<li>고객센터</li>
				<li><?=$bbs_code_str?> 카테고리 관리</li>
			</ul>
		</div>
		<div class="list_tit">
			<h3><?=$bbs_code_str?> 카테고리 수정</h3>
		</div>
		<!-- 네비게이션 종료 -->
		<div class="list">
			<!-- 검색창 시작 -->
			<table class="search">
				<form name="s_mem" id="s_mem" method="post">
					<input type="hidden" name="target_idx" value="<?=$target_idx?>"/>
					<caption>검색</caption>
					<colgroup>
						<col style="width:14%;">
						<col style="width:20%;">
						<col style="width:13%;">
						<col style="width:20%;">
						<col style="width:13%;">
						<col style="width:20%;">
					</colgroup>
					<tr>
						<th scope="row">PM</th>
						<td colspan="5">
							<input type="text" name="keyword" value="<?=$keyword?>" style="width: 100%">
						</td>
					</tr>
				</form>
			</table>

			<div class="align_r mt20">
				<button class="btn_search" onclick="on_submit()">수정하기</button>
			</div>
		</div>

		<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom_admin_tail.php"; ?>

		<script>
			function on_submit(){
                $.ajax({
					url: "./board_category_modify_popup_action.php",
					data: {
                        target_idx: "<?=$target_idx?>",
						keyword: s_mem.keyword.value
					},
					dataType: "json",
					type: "POST",
					success: function (data){
                        alert(data.message)
						if(data.code === 1){
                            opener.location.reload();
                            self.close();
						}
					}
				})
			}
		</script>
	</div>
</body>
</html>