<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));

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
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&keyword='.$keyword.'&s_uid='.$s_uid.'&s_uname='.$s_uname.'&cr_s_date='.$cr_s_date.'&cr_e_date='.$cr_e_date.'&cr_cate='.$cr_cate.'&s_cnt='.$s_cnt.'&s_order='.$s_order.'&pageNo='.$pageNo;

$cr_cate_str = "광고";

$sql = "select *,(select user_name from member_info where 1 and del_yn='N' and idx=a.admin_idx) as admin_name,(select user_id from member_info where 1 and del_yn='N' and idx=a.admin_idx) as admin_id from advertising_info a where 1 and idx='".$idx."' and is_del='N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록한 <?=$cr_cate_str?>이 없습니다.');
	location.href =  "advert_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

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
					<h3><?=$cr_cate_str?> 수정</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
				
				<form name="frm" id="frm" action="advert_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="idx" id="idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" id="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="type" id="type" value="<?=$cr_cate?>"/>
					<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<tr>
							<th scope="row">등록한 광고 URL</th>
							<td>
								<input type="text" style="width:90%;" name="ad_url" id="ad_url" required="yes" message="광고 URL" value="<?=$row['ad_url']?>">
								<br> * 해당 유튜브 "공유" 버튼 클릭시 나타나는 URL
							</td>
							<th scope="row">기간</th>
							<td>
								<input type="text" autocomplete="off" readonly name="sdate" id="sdate" style="width:45%;" class="datepicker" value="<?=$row['sdate']?>"> ~ <input type="text" autocomplete="off" readonly name="edate" id="edate" style="width:45%;" class="datepicker" value="<?=$row['edate']?>">
							</td>
						</tr>
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
						<a href="javascript:go_list();" class="btn_list">취소</a>
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
		location.href = "advert_view.php?idx=<?=$idx?>&<?=$total_param?>";
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>