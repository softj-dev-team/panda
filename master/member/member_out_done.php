<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/patin_left.php"; // 사이트설정 좌측메뉴?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 등급별 검색
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 정회원,우수회원,셀러회원 등 검색
$v_sect = sqlfilter($_REQUEST['v_sect']); // 회원, 제휴회원 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&v_sect='.$v_sect.'&s_gender='.$s_gender;

if(!$pageNo){
	$pageNo = 1;
}

$where .= " and memout_yn = 'Y' and ad_mem_sect != 'S' ";
$where .= " and member_type = '".$v_sect."' ";

if($s_gender){
	$where .= " and gender = '".$s_gender."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select *,(select level_name from member_level_set where 1 and level_code=member_info.user_level) as user_level_nm from member_info where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_info where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
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
			탈퇴처리 완료된 회원리스트
		</h3>
		<div class="cont">
			<!-- srch_bar -->
			<form name="s_mem" method="post" action="member_out_done.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
			<input type="hidden" name="smenu" value="<?=$smenu?>"/>
			<dl class="srch_bar">
				<!--<dt>회원 구분</dt>
				<dd>
				<?if($v_sect == "GEN"){ // 일반 회원일때 ?>
					<select name="s_gubun" size="1" style="vertical-align:middle;" >
						<option value="">회원구분</option>
						<option value="GEN_M" <?=$s_gubun=="GEN_M"?"selected":""?>>정회원</option>
						<option value="GEN_S" <?=$s_gubun=="GEN_S"?"selected":""?>>우수회원</option>
						<option value="GEN_V" <?=$s_gubun=="GEN_V"?"selected":""?>>VIP 회원</option>
					</select>
				<? } elseif($v_sect == "PAT"){ // 제휴회원 일때 ?>
					<select name="s_gubun" size="1" style="vertical-align:middle;" >
						<option value="">회원구분</option>
						<option value="PAT_B" <?=$s_gubun=="PAT_B"?"selected":""?>>게시판운영 회원</option>
						<option value="PAT_S" <?=$s_gubun=="PAT_S"?"selected":""?>>셀러회원</option>
						<option value="PAT_SS" <?=$s_gubun=="PAT_SS"?"selected":""?>>파워셀러 회원</option>
					</select>
				<? } // 회원 제휴회원 모두 종료?>
				&nbsp;&nbsp;
						<select name="s_level" size="1" style="vertical-align:middle;" >
							<option value="">등급별 검색</option>
							<?
								$sub_sql = "select idx,level_code,level_name from member_level_set where 1=1 and is_del = 'N' order by level_align asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[level_code]?>" <?=$s_level==$sub_row[level_code]?"selected":""?>><?=$sub_row[level_name]?></option>
							<?}?>
						</select>
						&nbsp;&nbsp;

				</dd>-->

				<dt>조건 검색</dt>
				<dd>
						
						<select name="field" size="1" style="vertical-align:middle;">
							<option value="">검색기준</option>
							<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
							<option value="user_name" <?=$field=="user_name"?"selected":""?>>성 명</option>
						</select>
					
					<input type="text" name="keyword" id="keyword" style="width:200px;" value="<?=$keyword?>" >

					<input type="image" src="/manage/img/btn_search.gif" alt="검색" align="absmiddle"/>
				</dd>
			</dl>
			</form>
			<!-- //srch_bar -->
			<div class="clear"></div>
					
			<!-- //button -->			
			<!-- Goods List -->
			<br>
			<table class="t_list">
				<thead>
				
					<tr>
						<th width="5%">번호</th>
						<th width="10%">이메일</th>
						<th width="10%">성 명</th>
						<th width="25%">회원등록 일시</th>
						<th width="25%">탈퇴신청 일시</th>
						<th width="25%">탈퇴처리 일시</th>
					</tr>
				
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>탈퇴처리한 회원이 없습니다.</strong></td>
					</tr>
				<? } ?>

			<?
			for ($i=0; $i<mysqli_num_rows($result); $i++){
				$row = mysqli_fetch_array($result);

				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				
				if($row[gender] == "M"){
					$gender = "남성";
				} elseif($row[gender] == "F"){
					$gender = "여성";
				} else {
					$gender = "";
				}

		?>
		
					<tr>
						<td><?=$listnum?></td>
						<td><?=$row[user_id]?></td>
						<td><?=$row[user_name]?></td>
						<td><?=$row[wdate]?></td>
						<td><?=$row[out_s_date]?></td>
						<td><?=$row[out_m_date]?></td>
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