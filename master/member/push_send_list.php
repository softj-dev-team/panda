<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$mail_gubun = sqlfilter($_REQUEST['mail_gubun']);
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 등급별 검색
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 정회원,우수회원,셀러회원 등 검색
$v_sect = sqlfilter($_REQUEST['v_sect']); // 회원, 제휴회원 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&v_sect='.$v_sect.'&s_gender='.$s_gender.'&mail_gubun='.$mail_gubun;

if(!$pageNo){
	$pageNo = 1;
}

//$where .= " and mail_method = '".$_SESSION['admin_homest_section']."' and mail_gubun = '".$mail_gubun."' ";  
$where .= " and bbs_code = 'push' ";  

if($v_sect){
	$where .= " and user_sect = '".$v_sect."' ";
}

if($s_gubun){
	$where .= " and user_gubun = '".$s_gubun."' ";
}

if($s_level){
	$where .= " and user_level = '".$s_level."' ";
}

if($s_gender){
	$where .= " and user_gender = '".$s_gender."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 20; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

/*$query =	" SELECT * ";
$query = $query." FROM ( ";
$query = $query." SELECT	ROW_NUMBER() OVER(ORDER BY idx DESC) AS rowNumber ";
$query = $query.",	idx,mail_key,mail_gubun,send_sect,fromname,fromemail,subject,wdate ";
$query = $query." FROM push_send_msg WITH(NOLOCK) ";
$query = $query." WHERE 1=1  ".$where;
$query = $query."	) AS S ";
$query = $query." WHERE S.rowNumber BETWEEN ".$StarRowNum." AND ".$EndRowNum." ";*/

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$order_by = " order by idx desc ";

$query = "select * from board_content where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from board_content where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

if($mail_gubun == "mail"){
	$mail_gubun_str = "메일";
} elseif($mail_gubun == "sms"){
	$mail_gubun_str = "문자";
} elseif($mail_gubun == "memo"){
	$mail_gubun_str = "쪽지";
}  elseif($mail_gubun == "push"){
	$mail_gubun_str = "푸쉬";
} 
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "push_send_view.php?mail_key="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
//-->
</SCRIPT>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/member_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>전체<?=$mail_gubun_str?> 발송내역</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>발송한 전체<?=$mail_gubun_str?> 리스트</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" method="post" action="push_send_list.php">
					<input type="hidden" name="mode" value="ser">
					<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
					<input type="hidden" name="smenu" value="<?=$smenu?>"/>
					<input type="hidden" name="mail_gubun" value="<?=$mail_gubun?>"/>
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
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<?if($mail_gubun == "mail"){?>
										<option value="subject" <?=$field=="subject"?"selected":""?>>메일제목</option>
										<option value="content" <?=$field=="content"?"selected":""?>>메일내용</option>
									<?}elseif($mail_gubun == "push"){?>
										<option value="subject" <?=$field=="subject"?"selected":""?>>제목</option>
										<option value="content" <?=$field=="content"?"selected":""?>>내용</option>
									<?} elseif($mail_gubun == "sms" || $mail_gubun == "memo"){?>
										<option value="content" <?=$field=="content"?"selected":""?>>발송내용</option>
									<?}?>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
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
							<p class="txt">총 <span><?=$num?></span>건의 발송내역이 있습니다.</p>
							<div class="btn_wrap">
								
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
			
			<table class="search_list">
				<thead>
					<tr>
						<th width="5%">번호</th>
					<?if($mail_gubun == "mail"){?>
						<th width="55%">메일제목</th>
						<th width="10%">발송자 성명</th>
						<th width="15%">발송자 이메일</th>
					<?}elseif($mail_gubun == "push"){?>
						<th width="65%">제목</th>
						<th width="15%">발송자 성명</th>
					<?} elseif($mail_gubun == "sms"){?>
						<th width="65%">발송내용</th>
						<th width="15%">발송번호</th>
					<?} elseif($mail_gubun == "memo"){?>
						<th width="80%">발송내용</th>
					<?}?>
						<th width="15%">발송일시</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>전체<?=$mail_gubun_str?> 발송내역이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
										

					if($row[user_gender] == "Y"){
						$gender = "관리자 승인";
					} elseif($row[user_gender] == "N"){
						$gender = "관리자 미승인";
					} else {
						$gender = "";
					}

					$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row[user_level]."' ";   
					$member_level_query = mysqli_query($gconnet,$member_level_sql);
					$member_level_row = mysqli_fetch_array($member_level_query);
					$user_level_str = $member_level_row['level_name'];


				?>
					<tr>
						<td><?=$listnum?></td>
					<?if($mail_gubun == "mail"){?>
						<td style="text-align:left;padding-left:10px;"><a href="javascript:go_view('<?=$row[mail_key]?>');"><?=string_cut2(stripslashes($row[subject]),40)?></a></td>
						<td><?=$row[fromname]?></td>
						<td><?=$row[fromemail]?></td>
					<?}elseif($mail_gubun == "push"){?>
						<td style="text-align:left;padding-left:10px;"><a href="javascript:go_view('<?=$row[bbs_sect]?>');"><?=string_cut2(stripslashes($row[subject]),40)?></a></td>
						<td><?=$row[writer]?></td>
					<?} elseif($mail_gubun == "sms"){?>
						<td style="text-align:left;padding-left:10px;"><a href="javascript:go_view('<?=$row[mail_key]?>');"><?=string_cut2(stripslashes($row[content]),60)?></a></td>
						<td><?=$row[fromemail]?></td>	
					<?} elseif($mail_gubun == "memo"){?>
						<td style="text-align:left;padding-left:10px;"><a href="javascript:go_view('<?=$row[mail_key]?>');"><?=string_cut2(stripslashes($row[content]),80)?></a></td>
					<?}?>
						<td><?=$row[write_time]?></td>
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
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>