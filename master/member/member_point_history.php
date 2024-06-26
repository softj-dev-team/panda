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

if($point_sect == "point"){
	$point_str = "포인트";
} elseif($point_sect == "refund"){
	$point_str = "포인트";
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

$sql_sub1 = "select user_id,user_name,user_nick,member_type,member_gubun,user_level,gender FROM member_info where 1 and idx='".$member_idx."' ";
//echo $sql_sub1;
$query_sub1 = mysqli_query($gconnet,$sql_sub1);
$row_sub1 = mysqli_fetch_array($query_sub1);

$user_id = $row_sub1[user_id];
$user_name = $row_sub1[user_name];
$user_nick = $row_sub1[user_nick];

if($row_sub1[gender] == "M"){
	$gender = "남성";
} elseif($row_sub1[gender] == "F"){
	$gender = "여성";
}

$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row_sub1[user_level]."' ";   
$member_level_query = mysqli_query($gconnet,$member_level_sql);
$member_level_row = mysqli_fetch_array($member_level_query);
$user_level_str = $member_level_row['level_name'];

if($row_sub1[user_gubun] == "GEN_M"){
	$user_gubun = "정회원";
} elseif($row_sub1[user_gubun] == "GEN_S"){
	$user_gubun = "우수회원";
} elseif($row_sub1[user_gubun] == "GEN_V"){
	$user_gubun = "VIP 회원";
}  elseif($row_sub1[user_gubun] == "PAT_B"){
	$user_gubun = "게시판운영 회원";
}  elseif($row_sub1[user_gubun] == "PAT_S"){
	$user_gubun = "셀러 회원";
}  elseif($row_sub1[user_gubun] == "PAT_SS"){
	$user_gubun = "파워셀러 회원";
}  else {
	$user_gubun = "";
}

if($row_sub1[member_type] == "GEN"){
	$member_sect_str = "일반회원";
} elseif($row_sub1[member_type] == "PAT"){
	$member_sect_str = "제휴회원";
}

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
						<li>회원관리</li>
						<li>회원별 <?=$point_str?> 히스토리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$user_name?> (<?=$user_id?>) <?=$point_str?> 지급/감소 히스토리 내역보기</h3>
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
						<td><?=number_format($row[mile_pre],0)?> 점</td>	
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
 	