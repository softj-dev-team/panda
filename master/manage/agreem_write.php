<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_uid = sqlfilter($_REQUEST['s_uid']); // 아이디
$s_uname = sqlfilter($_REQUEST['s_uname']); // 성명
$cr_cate = sqlfilter($_REQUEST['cr_cate']); // 구분
$cr_s_date = sqlfilter($_REQUEST['cr_s_date']); // 기간1
$cr_e_date = sqlfilter($_REQUEST['cr_e_date']); // 기간2
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&keyword='.$keyword.'&s_uid='.$s_uid.'&s_uname='.$s_uname.'&cr_s_date='.$cr_s_date.'&cr_e_date='.$cr_e_date.'&cr_cate='.$cr_cate.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

if($cr_cate == "terms"){
	$cr_cate_str = "약관";
} elseif($cr_cate == "person"){
	$cr_cate_str = "개인정보 취급방침";
}	
?>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/manage_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트운영 관리</li>
						<li><?=$cr_cate_str?> 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$cr_cate_str?> 등록</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
				
				<form name="frm" id="frm" action="agreem_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="bmenu" id="bmenu" value="<?=$bmenu?>"/>
					<input type="hidden" name="smenu" id="smenu" value="<?=$smenu?>"/>
					<input type="hidden" name="type" id="type" value="<?=$cr_cate?>"/>
					<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<!--<tr>
							<th scope="row">메시지 창</th>
							<td colspan="3">
								<input type="radio" name="msg_title_chk" id="msg_title_1" onclick="set_msg_title();"> 더클레버스 &nbsp; 
								<input type="radio" name="msg_title_chk" id="msg_title_2" onclick="set_msg_title();"> 강연이름 &nbsp; 
								<a href="javascript:go_lecture_search();" class="btn_green">강연찾기</a>
							</td>
						</tr>
						<tr>
							<th scope="row">대상자</th>
							<td colspan="3">
								<div id="schedule_mem_area">
									<div style="width:90%;height:250px;background-color:#fff;padding-top:10px;padding-left:10px;padding-bottom:10px;" id="schedule_user_txt"></div>
									<!-- ajax_schedule_mem.php 에서 불러옴
								</div>
								<div style="text-align:right;padding-right:11%;margin-top:10px;">
									<a href="javascript:set_mem_all();" class="btn_green">전체</a>
									<a href="javascript:go_mem_search();" class="btn_blue">추가</a>
								</div>
							</td>
						</tr>-->
						<tr>
							<th scope="row">제목</th>
							<td colspan="3">
								<input type="text" style="width:50%;" name="title" id="title" required="yes" message="제 목" value="<?=$row['title']?>">
							</td>
						</tr>
						<tr>
							<th scope="row">내용</th>
							<td colspan="3">
								<textarea style="width:90%;height:300px;" name="content" id="content" required="yes" message="내용"><?=$row['content']?></textarea>
							</td>
						</tr>
						
					</table>
				</form>

					<div class="write_btn align_r mt35">
						<button class="btn_modify" onclick="go_submit();">저장</button>
						<a href="javascript:go_list();" class="btn_list">목록</a>
						<!--<button class="btn_del">취소</button>-->
					</div>
					
				</div>
			</div>
		</div>

<script>
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

	function go_list(){
		location.href = "agreem_list.php?<?=$total_param?>";
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>