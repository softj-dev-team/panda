<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<?php
################## 받는값  #########################

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$mem_idx = trim(sqlfilter($_REQUEST['mem_idx']));

################## 파라미터 조합 #####################
$total_param = 'field='.$field.'&keyword='.$keyword.'&mem_idx='.$mem_idx;

############### 만료기간이 지난 쿠폰은 기간만료로 처리한다 ######################

$bis_sdate = date("Y-m-d");

$coupon_sql = "update member_coupon set coupon_sect = 'C' where 1 and member_idx='".$mem_idx."' and expire_date < '".$bis_sdate."' and coupon_sect='A'";
$coupon_result = mysqli_query($gconnet,$coupon_sql);

############### 만료기간이 지난 쿠폰은 기간만료로 처리한다 종료 ######################

$sql_sub1 = "select user_id,user_name,user_level,gender,cell FROM member_info where 1=1 and idx='".$mem_idx."' ";
//echo $sql_sub1;
$query_sub1 = mysqli_query($gconnet,$sql_sub1);
$row_sub1 = mysqli_fetch_array($query_sub1);

$user_id = $row_sub1['user_id'];
$user_name = $row_sub1['user_name'];

if($row_sub1['gender'] == "M"){
	$gender = "남성";
} elseif($row_sub1['gender'] == "F"){
	$gender = "여성";
}

$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row_sub1['user_level']."' ";   
$member_level_query = mysqli_query($gconnet,$member_level_sql);
$member_level_row = mysqli_fetch_array($member_level_query);
$user_level_str = $member_level_row['level_name'];

if(!$pageNo){
	$pageNo = 1;
}

$where .= " and member_idx='".$mem_idx."' and is_del='N'";

if($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%' ";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc";

$query = "select * from member_coupon where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_coupon where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $query_cnt;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>회원별 쿠폰 히스토리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$user_name?> (<?=$user_id?>)  쿠폰 히스토리 내역보기</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="member_coupon_history.php">
						<input type="hidden" name="mem_idx" value="<?=$mem_idx?>"/>
						<caption>검색</caption>
						<colgroup>
							<col style="width:20%;">
							<col style="width:14%;">
							<col style="width:13%;">
							<col style="width:20%;">
							<col style="width:13%;">
							<col style="width:20%;">
						</colgroup>
						<tr>
							<th scope="row">적립/차감 구분</th>
							<td colspan="5">
								<select name="v_sect" size="1" style="vertical-align:middle;width:20%;" onchange="s_mem.submit();">
									<option value=""> 전체보기</option>
									<option value="A" <?=$v_sect=="A"?"selected":""?>>적립내역만 보기</option>
									<option value="M" <?=$v_sect=="M"?"selected":""?>>차감내역만 보기</option>
								</select>
							</td>
						</tr>
				</form>
				</table>-->
				<!-- 검색창 종료 -->

					<div class="align_r mt20">
						<!--<button class="btn_search" onclick="s_mem.submit();">검색</button>-->
					</div>
					<ul class="list_tab" style="height:20px;">
					
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
							<div class="btn_wrap">
								
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
				
			<table class="search_list">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<th width="25%">쿠폰 간략설명</th>
						<th width="10%">할인종류</th>
						<th width="10%">할인혜택</th>
						<th width="10%">만료일</th>
						<th width="10%">구 분</th>
						<th width="15%">발급일자</th>
						<th width="15%">사용일자</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong><?=$user_name?> 회원의 쿠폰 내역이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

					if($row['coupon_sect'] == "A"){
						$coupon_sect = "<font style='color:blue;'>발급</font>";
					} elseif($row['coupon_sect'] == "M"){
						$coupon_sect = "<font style='color:red;'>사용</font>";
					} elseif($row['coupon_sect'] == "C"){
						$coupon_sect = "<font style='color:gray;'>기간만료</font>";
					}

					$expire_date_arr = explode("-",$row['expire_date']);

				?>
					<tr>
						<td><?=$listnum?></td>
						<td><?=$row['coupon_title']?></td>
						<td>
						<?if($row['dis_type'] == "1"){?>
							정액쿠폰
						<?}elseif($row['dis_type'] == "2"){?>
							정률쿠폰
						<?}?>
						</td>
						<td>
						<?if($row['dis_type'] == "1"){?>
							<?=number_format($row['coupon_price'],0)?> 원 할인
						<?}elseif($row['dis_type'] == "2"){?>
							<?=number_format($row['coupon_per'],0)?> % 할인
						<?}?>
						</td>
						<td><?=$row['expire_date']?></td>
						<td><?=$coupon_sect?></td>
						<td><?=substr($row['wdate'],0,10)?></td>
						<td><?=substr($row['mdate'],0,10)?></td>
					</tr>
				<?}?>	
			
			</tbody>
			</table>
			<div class="pagination mt0">
				<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
			</div>
		</div>			
	</div>
	<!-- content 종료 -->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
 	