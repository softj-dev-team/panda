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

$cr_cate_str = "신고";

$sql = "select *,(select user_nick from member_info where 1 and del_yn='N' and idx=a.member_idx) as user_nick,(select email from member_info where 1 and del_yn='N' and idx=a.member_idx) as user_email,(select user_name from member_info where 1 and del_yn='N' and idx=a.admin_idx) as admin_name,(select user_id from member_info where 1 and del_yn='N' and idx=a.admin_idx) as admin_id,(select product_title from product_info where 1 and is_del='N' and idx=a.product_idx) as product_title from declaration_info a where 1 and idx='".$idx."' and is_del='N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록한 신고내역이 없습니다.');
	location.href =  "decla_list.php?<?=$total_param?>";
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
					<h3>신고내역 보기</h3>
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
							<th scope="row">작품명</th>
							<td>
								<?=$row['product_title']?>
							</td>
							<th scope="row">등록일시</th>
							<td>
								<?=$row['wdate']?>
							</td>
						</tr>
						<tr>
							<th scope="row">등록자</th>
							<td>
								<?=$row['user_nick']?> (<?=$row['user_email']?>)
							</td>
							<th scope="row">신고유형</th>
							<td>
								<?=$arr_decla_type[$row['type']]?>
							</td>
						</tr>
					</table>
				
					<div class="write_btn align_r mt35">
						<a href="javascript:go_list();" class="btn_gray">목록</a>
						<!--<a href="javascript:go_modify('<?=$idx?>');" class="btn_blue">수정</a>-->
						<a href="javascript:go_delete('<?=$idx?>');" class="btn_red">삭제</a>
					</div>

					<p class="tit">관리자 답변설정</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
					<form name="set_frm" id="set_frm" action="decla_view_action.php" target="_fra_admin" method="post" >
						<input type="hidden" name="idx" value="<?=$idx?>"/>
						<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<tr>
								<th scope="row">답변상태</th>
								<td colspan="3">
									<select name="status" required="yes" message="답변상태" size="1" style="vertical-align:middle;" >
										<option value="">선택하세요</option>
										<option value="Y" <?=$row[status]=="Y"?"selected":""?>>처리완료</option>
										<option value="N" <?=$row[status]=="N"?"selected":""?>>대기중</option>
									</select>
								</td>
								</tr>
								<tr>
								<th scope="row">답변</th>
								<td colspan="3">
									<textarea style="width:90%;height:100px;" name="content_answ" required="no"  message="PM메모" value=""><?=$row[content_answ]?></textarea>
								</td>
							</tr>
						<?if($row[status] == "Y"){?>
							<tr>
								<th scope="row">조치일</th>
								<td>
									<?=$row['addate']?>
								</td>
								<th scope="row">조치 관리자</th>
								<td>
									<?=$row['admin_name']?> (<?=$row['admin_id']?>)
								</td>
							</tr>
						<?}?>
					</form>
					</table>
			
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_set_submit();" class="btn_blue">설정변경</a></div>
					
				</div>
			</div>
		</div>

<script>
	
	function go_list(){
		location.href = "decla_list.php?<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "decla_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "decla_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}

	function go_set_submit() {
		var check = chkFrm('set_frm');
		if(check) {
			set_frm.submit();
		} else {
			false;
		}
	}

</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>