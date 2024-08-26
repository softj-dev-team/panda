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

$sql = "select * from send_msg a where 1 and idx = '".$idx."' and del_yn='N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('발송한 메시지가 없습니다.');
	location.href =  "msg_send_list_manual.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

$sql_mem = "SELECT *,(select user_name from member_info where 1 and del_yn='N' and idx=a.member_idx) as user_name,(select user_id from member_info where 1 and del_yn='N' and idx=a.member_idx) as user_id FROM send_msg_member a where 1 and msg_idx = '".$row['idx']."' and del_yn='N'";
$query_mem = mysqli_query($gconnet,$sql_mem);
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
					<h3>발송한 메시지 보기</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
				
					<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<tr>
							<th scope="row">구분</th>
							<td colspan="3">
								<?=$arr_push_type[$row['msg_cate']]?>
							</td>
						</tr>
						<!--<tr>
							<th scope="row">대상자</th>
							<td colspan="3">
							<?
								for($mi=0; $mi<mysqli_num_rows($query_mem); $mi++){
									$row_mi = mysqli_fetch_array($query_mem);
							?>
								<?=$row_mi['user_name']?> (<?=$row_mi['user_id']?>) <?if($mi < mysqli_num_rows($query_mem)-1){?> , <?}?>
							<?
								}
							?>	
							</td>
						</tr>-->
						<tr>
							<th scope="row">내용</th>
							<td colspan="3">
								<?=nl2br($row['msg_content'])?>
							</td>
						</tr>
						
					</table>
				</form>

					<div class="write_btn align_r mt35">
						<a href="javascript:go_list();" class="btn_list">목록</a>
					</div>
					
				</div>
			</div>
		</div>

<script>
	
	function go_list(){
		location.href = "msg_send_list_manual.php?<?=$total_param?>";
	}

</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>