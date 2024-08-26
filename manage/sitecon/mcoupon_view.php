<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));

$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&pageNo='.$pageNo;

$query = "select * from member_coupon_set where 1 and idx='".$idx."' and is_del='N'";
$result = mysqli_query($gconnet,$query);

if(mysqli_num_rows($result) == 0){
	error_go("발급된 쿠폰이 없습니다.","mcoupon_list.php?".$total_param);
}

$row = mysqli_fetch_array($result);

$expire_date_arr = explode("-",$row['expire_date']);

/*if($row[member_sect] == "general"){
	$member_sect = "일반회원";
} elseif($row[member_sect] == "group"){
	$member_sect = "단체회원";
}*/

$rcnt_sql = "select idx from member_coupon where 1 and coupon_idx='".$row['idx']."' and is_del='N'";
$rcnt_query = mysqli_query($gconnet,$rcnt_sql);
$rcnt = mysqli_num_rows($rcnt_query);			

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "mcoupon_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "mcoupon_modify.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_delete(no){
		if(confirm('정말 삭제 하시겠습니까?')){
			//if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "mcoupon_delete_action.php?idx="+no+"&<?=$total_param?>";
			//}
		}
	}

	function go_list(){
		location.href = "mcoupon_list.php?<?=$total_param?>";
	}

	function go_coupon_pop(no){
		//location.href = 
		window.open("member_coupon_history.php?mem_idx="+no+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>","couponview", "top=100,left=100,scrollbars=yes,resizable=no,width=870,height=500");
	}

//-->
</SCRIPT>

<script type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 설정</li>
						<li>쿠폰발급</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>발급한 쿠폰 상세보기</h3>
				</div>
				<div class="write">
					<p class="tit">발급한 쿠폰 상세보기</p>
			<table>
				<colgroup>
					<col width="15%" />
					<col width="35%" />
					<col width="15%" />
					<col width="35%" />
				</colgroup>

					<!--<tr>
						<th >쿠폰 종류</th>
						<td colspan="3">
						<?if($row['coupon_sect'] == "auto"){?>
							회원가입 자동발행쿠폰
						<?}elseif($row['coupon_sect'] == "normal"){?>
							회원조회 일반쿠폰
						<?}?>
						</td>
					</tr>-->

					 <tr>
						<th>쿠폰번호</th>
						<td colspan="3">
							<?=$row['coupon_num']?>
						</td>
					</tr>

					<?if($row['coupon_sect'] == "auto"){?>
					<tr>
						<th >쿠폰 유효기간</th>
						<td colspan="3">가입일로 부터 <?=$row['expire_date_auto']?> 일간</td>
					</tr>
					<?}elseif($row['coupon_sect'] == "normal"){?>
					<tr>
						<th >쿠폰 만료일</th>
						<td colspan="3"><?=$row['expire_date']?></td>
					</tr>
					<?}?>

					<tr>
						<th >쿠폰 간략설명</th>
						<td colspan="3"><?=$row['coupon_title']?></td>
					</tr>

					<tr>
						<th >할인종류</th>
						<td width="*" colspan="3">
						<?if($row['dis_type'] == "1"){?>
							정액쿠폰
						<?}elseif($row['dis_type'] == "2"){?>
							정률쿠폰
						<?}?>
						</td>
					</tr>
					
					<?if($row['dis_type'] == "1"){?>
					<tr>
						<th>쿠폰 액면가</th>
						<td colspan="3"><?=number_format($row['coupon_price'],0)?> 원</td>
					</tr>
					<?}elseif($row['dis_type'] == "2"){?>
					<tr>
						<th>쿠폰 할인율</th>
						<td colspan="3"><?=number_format($row['coupon_per'],0)?> %</td>
					</tr>
					<?}?>
					
					<tr>
						<th>쿠폰생성 관리자 ID</th>
						<td><?=$row['ad_sect_id']?></td>
						<th>쿠폰생성 관리자 이름</th>
						<td><?=$row['ad_sect_name']?></td>
					</tr>
					
					<tr>
						<th >생성일</th>
						<td colspan="3"><?=$row['wdate']?></td>
					</tr>

				</table>

				<div class="align_c margin_t20">
					<a href="javascript:go_list();" class="btn_blue">목록</a>
				<?if($rcnt == 0){?>
					<a href="javascript:go_modify('<?=$row['idx']?>');"  class="btn_green">수정</a>
					<a href="javascript:go_delete('<?=$row['idx']?>');" class="btn_red">삭제</a>	
				<?}?>
				<div>

				<div id="coupon_reg_area" class="search_wrap" style="margin-top:10px;">
					<!-- coupon_reg_list.php 에서 불러옴 -->
				</div>
				
			</div>
		<!-- content 종료 -->
	</div>
</div>

<script>
	$(document).ready(function() {
		set_coupon_reg_list();
	});

	function set_coupon_reg_list(){
		get_data("coupon_reg_list.php","coupon_reg_area","coupon_idx=<?=$row['idx']?>");
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>