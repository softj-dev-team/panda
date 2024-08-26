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
	alert('등록한 <?=$cr_cate_str?>가 없습니다.');
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
					<h3><?=$cr_cate_str?> 보기</h3>
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
							<th scope="row">작성자</th>
							<td>
								<?=$row['admin_name']?> (<?=$row['admin_id']?>)
							</td>
							<th scope="row">기간</th>
							<td>
								<?=$row['sdate']?> ~ <?=$row['edate']?>
							</td>
						</tr>
						<tr>
							<th scope="row">광고</th>
							<td colspan="3">
							<?
								$ad_url_arr = explode("/",$row['ad_url']);
								$ad_url_arr_2 = explode("?",$ad_url_arr[4]);
							?>
								<iframe  src="https://www.youtube.com/embed/<?=$ad_url_arr_2[0]?>" frameborder="0" width="560" allowfullscreen></iframe>
							</td>
						</tr>
						<tr>
							<th scope="row">제 목</th>
							<td colspan="3">
								<?=$row['title']?>
							</td>
						</tr>
						<tr>
							<th scope="row">내용</th>
							<td colspan="3">
								<?=nl2br($row['content'])?>
							</td>
						</tr>
					</table>
				</form>

					<div class="write_btn align_r mt35">
						<a href="javascript:go_list();" class="btn_gray">목록</a>
						<a href="javascript:go_modify('<?=$idx?>');" class="btn_blue">수정</a>
						<a href="javascript:go_delete('<?=$idx?>');" class="btn_red">삭제</a>
					</div>
					
				</div>
			</div>
		</div>

<script>
	
	function go_list(){
		location.href = "advert_list.php?<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "advert_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "advert_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}

</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>