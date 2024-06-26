<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/patin_left.php"; // 사이트설정 좌측메뉴?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']);  // 내용확인 여부
$site_sect = sqlfilter($_REQUEST['site_sect']); //  Contact Us / 광고문의 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 답변완료 여부
$s_gender2 = sqlfilter($_REQUEST['s_gender2']); // 접수상태
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&site_sect='.$site_sect.'&s_gender='.$s_gender.'&s_gender2='.$s_gender2;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and member_type = 'PATIN'";

if($site_sect){
	$member_sect_str = $site_sect;
	$where .= " and ad_type = '".$site_sect."' ";
} else {
	$member_sect_str = "제휴문의 및 상담신청";
}

if($s_gubun){
	$where .= " and read_ok = '".$s_gubun."' ";
}

if($s_gender){
	$where .= " and reply_ok = '".$s_gender."' ";
}

if($s_gender2){
	$where .= " and view_ok = '".$s_gender2."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select * from pat_member_ad where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from pat_member_ad where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "con_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "con_list.php?<?=$total_param?>";
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

<!-- content -->
<section id="content">
	<div class="inner">
		<h3>
			<?=$member_sect_str?> 리스트
		</h3>
		<div class="cont">
			<!-- srch_bar -->
			<form name="s_mem" method="post" action="con_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="site_sect" value="<?=$site_sect?>"/>
			<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
			<input type="hidden" name="smenu" value="<?=$smenu?>"/>
			<dl class="srch_bar">
				<dt>검색하기</dt>
				<dd>
						<select name="s_gubun" size="1" style="vertical-align:middle;" >
							<option value="">내용확인 여부</option>
							<option value="Y" <?=$s_gubun=="Y"?"selected":""?>>확인된 내용 검색</option>
							<option value="N" <?=$s_gubun=="N"?"selected":""?>>확인안된 내용 검색</option>
						</select>
						&nbsp;&nbsp;
						<select name="s_gender" size="1" style="vertical-align:middle;" >
							<option value="">답변완료 여부</option>
							<option value="Y" <?=$s_gender=="Y"?"selected":""?>>답변완료 내용</option>
							<option value="N" <?=$s_gender=="N"?"selected":""?>>답변미완 내용</option>
						</select>
						&nbsp;&nbsp;
						<select name="s_gender2" size="1" style="vertical-align:middle;" >
							<option value="">신청상태</option>
							<option value="P" <?=$s_gender2=="P"?"selected":""?>>접수</option>
							<option value="S" <?=$s_gender2=="S"?"selected":""?>>심사중</option>
							<option value="N" <?=$s_gender2=="N"?"selected":""?>>거부</option>
							<option value="Y" <?=$s_gender2=="Y"?"selected":""?>>승인 (전시중)</option>
						</select>
						&nbsp;&nbsp;
						<select name="field" size="1" style="vertical-align:middle;">
							<option value="">검색기준</option>
							<option value="com_name" <?=$field=="com_name"?"selected":""?>>업체명</option>
							<option value="dam_name" <?=$field=="dam_name"?"selected":""?>>담당자 이름</option>
							<option value="dam_cell" <?=$field=="dam_cell"?"selected":""?>>담당자 연락처</option>
							<option value="dam_email" <?=$field=="dam_email"?"selected":""?>>담당자 이메일</option>
							<option value="ad_memo" <?=$field=="ad_memo"?"selected":""?>>남기실 글</option>
						</select>
					
					<input type="text" name="keyword" id="keyword" style="width:200px;" value="<?=$keyword?>" >

					<input type="image" src="/manage/img/btn_search.gif" alt="검색" align="absmiddle"/>
				</dd>
			</dl>
			</form>
			<!-- //srch_bar -->
			<div class="clear"><?//=$query?></div>
			<br>
			<!-- button -->
		<?if($site_sect == "M"){?>	
			<!--<table width="100%" align="center">
				<tr>
					<td align="right">
						<a href="javascript:go_regist();" class="btn_blue2">등록하기</a>
					</td>
				</tr>
			</table>
			<br>-->
		<?}?>
			<!-- //button -->			
			<!-- Goods List -->
			
			<table class="t_list">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<th width="10%">위치</th>
						<th width="10%">업체명</th>
						<th width="10%">담당자</th>
						<th width="10%">담당자 연락처</th>
						<th width="15%">담당자 이메일</th>
						<th width="10%">등록일자</th>
						<th width="10%">확인여부</th>
						<th width="10%">답변여부</th>
						<th width="10%">신청상태</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 <?=$member_sect_str?> 내용이 없습니다.</strong></td>
					</tr>
				<? } ?>

			<?
			for ($i=0; $i<mysqli_num_rows($result); $i++){
				$row = mysqli_fetch_array($result);

				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				
				if($row[read_ok] == "Y"){
					$read_ok = "<font style='color:blue;'>내용확인</font>";
				}elseif($row[read_ok] == "N"){
					$read_ok = "<font style='color:red;'>확인전</font>";
				}

				if($row[reply_ok] == "Y"){
					$reply_ok = "<font style='color:blue;'>답변완료</font>";
				}elseif($row[reply_ok] == "N"){
					$reply_ok = "<font style='color:red;'>답변전</font>";
				}

				if($row[view_ok] == "P"){
					$view_ok = "<font style='color:black'><b>접수</b></font>";
				} elseif ($row[view_ok]=="S"){
					$view_ok = "<font style='color:green'><b>심사중</b></font>";
				} elseif ($row[view_ok]=="Y"){
					$view_ok = "<font style='color:blue'><b>승인 (전시중)</b></font>";
				} elseif ($row[view_ok]=="N"){
					$view_ok = "<font style='color:red'><b>거부</b></font>";
				}
							
			?>
			
					<tr>
						<td><?=$listnum?></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[ad_location]?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[com_name]?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[dam_name]?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[dam_cell]?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[dam_email]?></a></td>
						<td><?=substr($row[wdate],0,10)?></td>
						<td><?=$read_ok?></td>
						<td><?=$reply_ok?></td>
						<td><?=$view_ok?></td>	
					</tr>
			<?}?>	
			
			</tbody>
			</table>
			
			<!-- //Goods List -->
			<!-- paginate -->
			<div class="paginate">
			<?
					$prev_img_path="../img/btn_pre.gif";
				  	$next_img_path="../img/btn_next.gif";
					$first_img_path="../img/btn_next_end.gif";
					$last_img_path="../img/btn_pre_end.gif";
					include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";
			?>
			</div>
			<!-- //paginate -->
		</div>
	</div>
</section>
<!-- //content -->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>