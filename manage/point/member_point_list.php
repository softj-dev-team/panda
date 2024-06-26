<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$point_sect = sqlfilter($_REQUEST['point_sect']);
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 계급별 검색
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 정회원,우수회원,셀러회원 등 검색
$v_sect = sqlfilter($_REQUEST['v_sect']); // 일반회원, 제휴회원 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&point_sect='.$point_sect.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&v_sect='.$v_sect.'&s_gender='.$s_gender;

if($point_sect == "smspay"){
	$point_str = "포인트";
} elseif($point_sect == "refund"){
	$point_str = "적립금";
} elseif($point_sect == "stamp"){
	$point_str = "G 스탬프";
} elseif($point_sect == "badp"){
	$point_str = "패널티";
} elseif($point_sect == "mp"){
	$point_str = "매너포인트";
} 

if(!$pageNo){
	$pageNo = 1;
}

$where = " and memout_yn not in ('Y','S') and del_yn='N' and member_type in ('GEN') and partner_idx=(select idx from member_info_company where 1 and is_del='N' and member_idx='".$_SESSION['manage_coinc_idx']."')";

if($s_gender){ 
	$where .= " and member_gubun = '".$s_gender."'";
}
if($s_gubun){ // 가입시작일
	$where .= " and idx in (select member_idx from member_point where 1 and point_sect='".$point_sect."' and mile_sect != 'P' and substring(wdate,1,10) >= '".$s_gubun."')";
}
if($s_level){ // 가입종료일
	$where .= " and idx in (select member_idx from member_point where 1 and point_sect='".$point_sect."' and mile_sect != 'P' and substring(wdate,1,10) <= '".$s_level."')";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select *,(select com_name from member_info_company where 1 and is_del='N' and idx=a.partner_idx order by idx desc limit 0,1) as com_name,(select mb_short_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_short_fee,(select mb_long_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_long_fee,(select mb_img_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_img_fee,(select cur_mile from member_point where 1 and point_sect='smspay' and mile_sect != 'P' and member_idx=a.idx order by idx desc limit 0,1) as current_point from member_info a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_info a where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function search_send(){
		var frm = document.s_mem;
		//if(frm.keyword.value==""){
		//	alert("검색어를 입력해 주십시오.");
		//	frm.keyword.focus();
		//	return false;
		//}
		frm.submit();
	}
	
	function go_list(){
		location.href = "member_point_list.php?<?=$total_param?>";
	}
	
	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}
	
	function go_mile_pop(midx){
		//location.href = 
		window.open("member_point_history.php?member_idx="+midx+"&point_sect=<?=$point_sect?>","mileview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}
	
//-->
</SCRIPT>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/point_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>포인트 관리</li>
						<li>포인트 내역 조회</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>포인트 내역 조회</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>">
					<input type="hidden" name="mode" value="ser">
					<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
					<input type="hidden" name="smenu" value="<?=$smenu?>"/>
					<input type="hidden" name="point_sect" value="<?=$point_sect?>"/>
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
							<th scope="row">회원구분</th>
							<td colspan="5">
								<select name="s_gender" size="1" style="vertical-align:middle;width:50%;" >
									<option value="">선택하세요</option>
									<option value="1" <?=$s_gender=="1"?"selected":""?>>일반회원</option>
									<option value="2" <?=$s_gender=="2"?"selected":""?>>광고회원</option>
									<option value="3" <?=$s_gender=="3"?"selected":""?>>휴면회원</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">포인트기간</th>
							<td colspan="2">
								<input type="text" autocomplete="off" readonly name="s_gubun" id="s_gubun" style="width:40%;" class="datepicker"  value="<?=$s_gubun?>"> ~ <input type="text" autocomplete="off" readonly name="s_level" id="s_level" style="width:40%;" class="datepicker" value="<?=$s_level?>">
							</td>
							<th scope="row">조건검색</th>
							<td colspan="2">
								<select name="field" size="1" style="vertical-align:middle;width:40%;">
									<option value="">검색기준</option>
									<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
									<option value="user_name" <?=$field=="user_name"?"selected":""?>>이름</option>
									<option value="cell" <?=$field=="cell"?"selected":""?>>연락처</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="<?=$keyword?>">
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->

					<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
					</div>
					<ul class="list_tab" style="height:20px;">
						
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">총 <span><?=$num?></span>명의 <?=$point_str?> 지급대상 회원이 있습니다.</p>
							<div class="btn_wrap">
								<b>아이디나 닉네임을 클릭하시면 해당회원의 포인트 적립/차감 내역이 나옵니다.</b>
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
					
			<table class="search_list">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<th width="9%">ID</th>
						<th width="9%">회원명</th>
						<th width="9%">회원구분</th>
						<th width="10%">가맹점</th>
						<th width="9%">이용건수</th>
						<th width="10%">현재 <?=$point_str?></th>
						<th width="9%">적립/차감</th>
						<th width="10%">적용할 <?=$point_str?></th>
						<th width="10%"><?=$point_str?> 적용사유</th>
						<th width="10%">설정</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>적립대상 회원이 없습니다.</strong></td>
					</tr>
				<? } ?>

		<?
			for ($i=0; $i<mysqli_num_rows($result); $i++){
				$row = mysqli_fetch_array($result);

				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
						
					$sql_sub = "select cur_mile from member_point where 1=1 and member_idx='".$row['idx']."' and point_sect='".$point_sect."' and mile_sect != 'P' order by idx desc limit 0,1";
					//echo $sql_sub."<br><br>";
					$query_sub = mysqli_query($gconnet,$sql_sub);
					
					if(mysqli_num_rows($query_sub)==0) {
						$current_point = 0; // 회원 데이터가 있어도 기존에 포인트내역이 없으면 현재 누적 포인트 0
					} else {
						$row_sub = mysqli_fetch_array($query_sub); 
						$current_point = $row_sub['cur_mile'];
					}

					if($row['master_ok'] == "Y"){
						//$master_ok = "<font style='color:blue;'>정상</font>";
						$master_ok = "<font style='color:blue;'>승인</font>";
					}elseif($row['master_ok'] == "N"){
						//$master_ok = "<font style='color:red;'>패널티 / ".$arr_panalty_type[$row['panalty_type']]."</font>";
						$master_ok = "<font style='color:red;'>미승인</font>";
					}

					if($row['member_gubun'] == "1"){
						$member_gubun = "일반회원";
					}elseif($row['member_gubun'] == "2"){
						$member_gubun = "광고회원";
					}elseif($row['member_gubun'] == "3"){
						$member_gubun = "휴면회원";
					}

				?>

				<form name="frm_modify_<?=$i?>" method="post" action="member_point_modify_action.php"  target="_fra_admin">
				<input type="hidden" name="total_param" value="<?=$total_param?>"/>
				<input type="hidden" name="pageNo" value="<?=$pageNo?>"/>
				<input type="hidden" name="point_sect" value="<?=$point_sect?>"/>
				<input type="hidden" name="member_idx" value="<?=$row['idx']?>"/>
				<input type="hidden" name="mile_pre" value="<?=$current_point?>"/>

					<tr>
						<td><?=$listnum?></td>
						<td><a href="javascript:go_mile_pop('<?=$row['idx']?>');"><?=$row['user_id']?></a></td>
						<td><a href="javascript:go_mile_pop('<?=$row['idx']?>');"><?=$row['user_name']?></a></td>
						<td><a href="javascript:go_mile_pop('<?=$row['idx']?>');"><?=$member_gubun?></a></td>
						<td><a href="javascript:go_mile_pop('<?=$row['idx']?>');"><?=$row['com_name']?></a></td>
						<td><?=number_format($send_msg_cnt,0)?></td>
						<td><?=number_format($current_point,0)?> 원</td>
						<td>
							<input type="radio" name="mile_sect" required="yes" message="<?=$point_str?> 적립/차감" value="A"> 적립 <input type="radio" name="mile_sect" required="yes" message="<?=$point_str?> 적립/차감" value="M"> 차감
						</td>
						<td><input type="text" style="width:40%;" name="chg_mile" required="yes" message="변경할 <?=$point_str?>" is_num = "yes" value=""> 원</td>
						<td>
							<input type="text" style="width:90%;" name="mile_title" required="no" message="<?=$point_str?> 수정사유" value="">
						</td>
						<td><a href="javascript:go_modify('frm_modify_<?=$i?>');" class="btn_blue">설정하기</a></td>
				</tr>

				</form>
			<?}?>	
				</tbody>
			</table>
			
			<div class="pagination mt0">
				<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
			</div>
						
		</div>
	</div>
	<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

	