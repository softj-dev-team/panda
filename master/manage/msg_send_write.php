<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_uid = sqlfilter($_REQUEST['s_uid']); // 아이디
$s_uname = sqlfilter($_REQUEST['s_uname']); // 성명
$cr_s_date = sqlfilter($_REQUEST['cr_s_date']); // 기간1
$cr_e_date = sqlfilter($_REQUEST['cr_e_date']); // 기간2
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&keyword='.$keyword.'&s_uid='.$s_uid.'&s_uname='.$s_uname.'&cr_s_date='.$cr_s_date.'&cr_e_date='.$cr_e_date.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

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
						<li>푸시 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>푸시 보내기</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
				
				<form name="frm" action="msg_send_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
					<input type="hidden" name="smenu" value="<?=$smenu?>"/>

					<input type="hidden" name="mem_all" id="mem_all" value="Y"/> <!-- 전체회원일때 Y -->
					<input type="hidden" name="member_idx" id="member_idx">
					<input type="hidden" name="delmem" id="delmem" value=""/>

					<input type="hidden" name="msg_title" id="msg_title" value=""/>
					<input type="hidden" name="board_tbname" id="board_tbname" value=""/>
					<input type="hidden" name="board_code" id="board_code" value=""/>
					<input type="hidden" name="board_idx" id="board_idx" value=""/>

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
							<th scope="row">구분</th>
							<td colspan="3">
								<select name="msg_cate" id="msg_cate" required="yes" message="구분" style="vertical-align:middle;width:45%;">
									<option value="">구분선택</option>
								<?
									$ik = 0;
									foreach ($arr_push_type as $key=>$val) {
								?>
									<option value="<?=$key?>"><?=$val?></option>
								<?
									$ik= $ik+1;
									}
								?>		
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">내용</th>
							<td colspan="3">
								<textarea style="width:90%;height:300px;" name="msg_content" id="msg_content" required="yes" message="내용"><?=$row['msg_content']?></textarea>
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
	
	function set_mem_all(){
		get_data("ajax_schedule_mem.php","schedule_mem_area","set_all=Y");
	}

	function set_mem_del(delmem){
		var delmem_cur = $("#delmem").val();
		if(delmem_cur == ""){
			var delmem_arr = delmem;
		} else {
			var delmem_arr = delmem_cur+","+delmem;
		}
		$("#delmem").val(delmem_arr);
		get_data("ajax_schedule_mem.php","schedule_mem_area","delmem="+delmem_arr+"");
	}

	function go_mem_search(){
		window.open("pop_member_list.php","pop_memlist", "top=100,left=100,scrollbars=yes,resizable=no,width=1280,height=600");
	}

	function set_msg_title(){
		if(document.getElementById("msg_title_1").checked == true){
			$("#msg_title").val("더클레버스");
		} else if(document.getElementById("msg_title_2").checked == true){
			$("#msg_title").val("");
		} else {
			$("#msg_title").val("");
		}
	}

	function go_lecture_search(){
		window.open("pop_lecture_list.php","pop_leclist", "top=100,left=100,scrollbars=yes,resizable=no,width=1280,height=600");
	}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>