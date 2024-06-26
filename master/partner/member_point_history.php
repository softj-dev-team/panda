<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<?php
################## 받는값  #########################
$bmenu = $_REQUEST['bmenu'];
$smenu = $_REQUEST['smenu'];
$code = sqlfilter($_REQUEST['code']);

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
$s_cate = trim(sqlfilter($_REQUEST['s_cate']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));

$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
$point_sect = trim(sqlfilter($_REQUEST['point_sect']));

if($point_sect == "smspay"){
	$point_str = "사용료";
} elseif($point_sect == "refund"){
	$point_str = "적립금";
} elseif($point_sect == "stamp"){
	$point_str = "G 스탬프";
} elseif($point_sect == "badp"){
	$point_str = "패널티";
} elseif($point_sect == "mp"){
	$point_str = "매너포인트";
} 

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&code='.$code.'&v_sect='.$v_sect.'&s_cate='.$s_cate.'&field='.$field.'&keyword='.$keyword.'&s_sect2='.$s_sect2.'&member_idx='.$member_idx.'&point_sect='.$point_sect;
################## 디비 연결 ########################

$sql_sub1 = "select *,(select cur_mile from member_point where 1 and point_sect='smspay' and mile_sect != 'P' and member_idx=a.idx order by idx desc limit 0,1) as current_point,(select com_name from member_info_company where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as com_name FROM member_info a where 1 and idx='".$member_idx."' ";
//echo $sql_sub1;
$query_sub1 = mysqli_query($gconnet,$sql_sub1);
$row_sub1 = mysqli_fetch_array($query_sub1);

if(!$pageNo){
	$pageNo = 1;
}

$where .= " and member_idx='".$member_idx."' and point_sect='".$point_sect."' and mile_sect != 'P' ";

if($v_sect){
	$where .= " and mile_sect = '".$v_sect."' ";
}

if($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%' ";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select * from member_point where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_point where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

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
						<li>가맹점관리</li>
						<li>가맹점별 <?=$point_str?> 히스토리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$point_str?> 지급/감소 히스토리 내역보기</h3>
				</div>

				<div class="write" style="padding: 1px 1px 1px 1px;margin-top:10px;">
					<p class="tit" style="background-image:url(../images/common/play.png);background-repeat:no-repeat;background-position:left center;font-size:16px;color:#454545;padding-left:22px;">가맹점 정보</p>
					<table style="padding: 1px 1px 1px 1px;">
						<caption>회원 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">가맹점 ID</th>
							<td><?=$row_sub1['user_id']?></td>
							<th scope="row">담당자명</th>
							<td><?=$row_sub1['user_name']?></td>
						</tr>
						<tr>
							<th scope="row">회사명</th>
							<td><?=$row_sub1['com_name']?></td>
							<th scope="row">현재 <?=$point_str?></th>
							<td><?=number_format($row_sub1['current_point'],0)?></td>
						</tr>
					</table>
				</div>

				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="member_point_history.php">
						<input type="hidden" name="member_idx" value="<?=$member_idx?>"/>
						<input type="hidden" name="point_sect" value="<?=$point_sect?>"/>
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
				</table>
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
						<th width="31%">적용내역</th>
						<th width="10%">이전 <?=$point_str?></th>
						<th width="5%">구 분</th>
						<th width="12%">적용할 <?=$point_str?></th>
						<th width="12%">적용된 <?=$point_str?></th>
						<th width="15%">적용일자</th>
						<th width="10%">적용구분</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong><?=$user_name?> 회원의 <?=$point_str?> 적립/차감 내역이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

					if($row[mile_sect] == "A"){
						$mile_sect = "<font style='color:blue;'>적립</font>";
					} elseif($row[mile_sect] == "M"){
						$mile_sect = "<font style='color:red;'>차감</font>";
					}

				?>
					<tr>
						<td><?=$listnum?></td>
						<td style="text-align:left;padding-left:10px;"><?=$row[mile_title]?></td>	
						<td><?=number_format($row[mile_pre])?> 점</td>	
						<td><?=$mile_sect?></td>	
						<td><?=number_format($row[chg_mile],0)?> 점</td>
						<td><?=number_format($row[cur_mile],0)?> 점</td>
						<td><?=$row[wdate]?></td>
						<td>
							<?if($row[ad_sect] !="" && $row[ad_sect] != "levup" && $row[ad_sect] != "levdown" && $row[ad_sect] !=" "){?>
								관리자조정 <br> (<?=$row[ad_sect]?>)
							<?}else{?>
								시스템
							<?}?>
						</td>	
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
 	