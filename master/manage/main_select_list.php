<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
		
	$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // prod : 관리자 선정작품 / arts : new artist
	$date_s = trim(sqlfilter($_REQUEST['date_s'])); 
	$date_e = trim(sqlfilter($_REQUEST['date_e']));
	$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
	$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 

	if($_POST['s_protype']){ 
		$s_protype = $_POST['s_protype'];
		for($si=0; $si<sizeof($s_protype); $si++){
			if($si == sizeof($s_protype)-1){
				$s_protype_arr .= $s_protype[$si];
			} else {
				$s_protype_arr .= $s_protype[$si].",";
			}
		}
	} else { 
		if($_GET['s_protype']){
			$s_protype_arr = urldecode($_GET['s_protype']);
			$s_protype = explode(",",$s_protype_arr);
		} 
	}

	$s_salemtd = trim(sqlfilter($_REQUEST['s_salemtd'])); 
	$s_salests = trim(sqlfilter($_REQUEST['s_salests']));
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

	$total_param = "bmenu=".$bmenu."&smenu=".$smenu."&v_sect=".$v_sect."&date_s=".$date_s."&date_e=".$date_e."&s_sect1=".$s_sect1."&s_sect2=".$s_sect2."&s_protype=".urlencode($s_protype_arr)."&s_salemtd=".$s_salemtd."&s_salests=".$s_salests."&keyword=".$keyword."&s_cnt=".$s_cnt."&s_order=".$s_order;

	if(!$v_sect){
		$v_sect = "prod";
	}
	
	if($v_sect == "prod"){
		$v_sect_str = "관리자 선정작품";
	} elseif($v_sect == "arts"){
		$v_sect_str = "New Artist";
	}

	$where = " and is_del='N' and type='".$v_sect."'";
	
	if($v_sect == "prod"){
		$field = "product_title";
		if($s_sect1){
			$where .= " and target_idx in (select idx from product_info where 1 and is_del='N' and cate_code1 = '".$s_sect1."')";
		}
		if($s_sect2){
			$where .= " and target_idx in (select idx from product_info where 1 and is_del='N' and cate_code2 = '".$s_sect2."')";
		}
		if ($field && $keyword){
			$where .= " and target_idx in (select idx from product_info where 1 and is_del='N' and ".$field." like '%".$keyword."%')";
		}
	} elseif($v_sect == "arts"){
		$field = "user_nick";
		if ($field && $keyword){
			$where .= " and target_idx in (select idx from member_info where 1 and del_yn='N' and ".$field." like '%".$keyword."%')";
		}
	}

	if($date_s){ 
		$where .= " and substring(sdate,1,10) >= '".$date_s."'";
	}
	if($date_e){ 
		$where .= " and substring(edate,1,10) <= '".$date_e."'";
	}
	

	if(!$pageNo){
		$pageNo = 1;
	}
	if(!$s_cnt){
		$s_cnt = 10; // 기본목록 10개
	}
	if(!$s_order){
		$s_order = 1; 
	}

	$pageScale = $s_cnt;  
	$start = ($pageNo-1)*$pageScale;

	$StarRowNum = (($pageNo-1) * $pageScale);
	$EndRowNum = $pageScale;
	
	if($s_order == 1){
		$order_by = " order by idx desc";
	}

	$query = "select *,(select product_title from product_info where 1 and is_del='N' and idx=main_select_info.target_idx) as product_title from 
	main_select_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
	//echo "query = ".$query."<br>";
	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from main_select_info where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/manage_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트운영 관리</li>
						<li>메인 관리</li>
						<li><?=$v_sect_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$v_sect_str?> 리스트</h3>
					<button class="btn_add" onclick="go_regist();" style="width:10%;"><span>신규등록</span></button>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<input type="hidden" name="s_cnt" id="s_cnt" value="<?=$s_cnt?>"/>
						<input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
						<input type="hidden" name="v_sect" id="v_sect" value="<?=$v_sect?>"/>
						<caption>검색</caption>
						<colgroup>
							<col style="width:14%;">
							<col style="width:20%;">
							<col style="width:13%;">
							<col style="width:20%;">
							<col style="width:13%;">
							<col style="width:20%;">
						</colgroup>
					<?if($v_sect == "prod"){?>
						<tr>
							<th scope="row">작품분류</th>
							<td colspan="5">
								<select name="s_sect1" id="s_sect1" style="vertical-align:middle;width:45%;" onchange="product_menu_sel_1(this)">
									<option value="">대분류</option>
								<?
								$sect1_sql = "select cate_code1,cate_name1 from common_code where 1 and is_del='N' and del_ok='N' and type='menu' and cate_level='1' order by cate_align desc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['cate_code1']?>" <?=$s_sect1==$row1['cate_code1']?"selected":""?>><?=$row1['cate_name1']?></option>
								<?}?>
								</select>
								&nbsp;
								<select name="s_sect2" id="s_sect2" style="vertical-align:middle;width:45%;">
									<option value="">중분류</option>
								<?
								$sect1_sql = "select cate_code2,cate_name2 from common_code where 1 and is_del='N' and del_ok='N' and type='menu' and cate_level='2' and cate_code1='".$s_sect1."' order by cate_align desc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['cate_code2']?>" <?=$s_sect2==$row1['cate_code2']?"selected":""?>><?=$row1['cate_name2']?></option>
								<?}?>
								</select>
							</td>
						</tr>
					<?}?>
						<tr>
							<th scope="row">노출기간</th>
							<td colspan="5">
								<input type="text" autocomplete="off" readonly name="date_s" id="date_s" style="width:10%;" class="datepicker" value="<?=$date_s?>"> ~ <input type="text" autocomplete="off" readonly name="date_e" id="date_e" style="width:10%;" class="datepicker" value="<?=$date_e?>">
							</td>
						</tr>
						<tr>
							<th scope="row">작품명</th>
							<td colspan="5">
								<input type="text" title="검색" name="keyword" id="keyword" style="width:30%"  value="<?=$keyword?>">
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->
		
			<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="product_excel_list.php">
			<input type="hidden" name="field" value="<?=$field?>"/>
			<input type="hidden" name="keyword" value="<?=htmlspecialchars($keyword)?>"/>
			<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
			<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
			<input type="hidden" name="s_level" value="<?=$s_level?>"/>
			<input type="hidden" name="s_gender" value="<?=$s_gender?>"/>
			<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
			<input type="hidden" name="s_sect2" value="<?=$s_sect2?>"/>
			<input type="hidden" name="s_cnt" value="<?=$s_cnt?>"/>
			<input type="hidden" name="s_order" value="<?=$s_order?>"/>
			</form>
			<!-- 엑셀 출력을 위한 전송 폼 종료 -->

					<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
						<!--<button class="btn_down" onclick="order_excel_frm.submit();">엑셀다운로드</button>-->
					</div>
					<ul class="list_tab" style="height:20px;">
						<!--<li class="on"><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>-->
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">총 <span><?=number_format($num)?></span>건</p>
							<div class="btn_wrap">
								<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="50" <?=$s_cnt=="50"?"selected":""?>>50개보기</option>
									<option value="100" <?=$s_cnt=="100"?"selected":""?>>100개보기</option>
								</select>
								<!--<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?=$s_order=="1"?"selected":""?>>회원가입일 최신순</option>
									<option value="2" <?=$s_order=="2"?"selected":""?>>회원가입일 오래된순</option>
									<option value="3" <?=$s_order=="3"?"selected":""?>>회원명 올림차순</option>
									<option value="4" <?=$s_order=="4"?"selected":""?>>회원명 내림차순</option>
								</select>
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>-->
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
				
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:20%;">
								<col style="width:13%">
								<col style="width:19%;">
								<col style="width:8%;">
								<col style="width:20%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">작품명</th>
									<th scope="col">등록일</th>
									<th scope="col">노출기간</th>
									<th scope="col">공개여부</th>
									<th scope="col">정렬순서</th>
									<th scope="col">관리</th>
								</tr>
							</thead>
							<tbody>
							<? if($num==0) { ?>
								<tr>
									<td colspan="10" height="40">등록된 <?=$v_sect_str?> 이 없습니다.</strong></td>
								</tr>
							<? } ?>
							<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
							?>
								<form name="frm_cate1_<?=$row['idx']?>" method="post" action="main_select_list_action.php" target="_fra_admin" enctype="multipart/form-data">
									<input type="hidden" name="idx" id="idx" value="<?=$row['idx']?>"/>
									<input type="hidden" name="total_param" id="total_param" value="<?=$total_param?>"/>
									<input type="hidden" name="pageNo" id="pageNo" value="<?=$pageNo?>"/>
									<input type="hidden" name="is_del" id="is_del" value="N">
									<tr>
										<td><?=$listnum?></td>
										<td><?=$row['product_title']?></td>
										<td><?=$row['wdate']?></td>
										<td> 
											<input type="text" autocomplete="off" readonly name="sdate" id="sdate" style="width:40%;" class="datepicker" required="yes" message="노출시작일" value="<?=$row['sdate']?>"> ~ <input type="text" autocomplete="off" readonly name="edate" id="edate" style="width:40%;" class="datepicker" required="yes" message="노출종료일" value="<?=$row['edate']?>">
										</td>
										<td>
											<select name="view_ok" id="view_ok" required="yes" message="공개여부">
												<option value="Y" <?=$row['view_ok']=="Y"?"selected":""?>>공개</option> 
												<option value="N" <?=$row['view_ok']=="N"?"selected":""?>>비공개</option> 
											</select>
										</td>
										<td>
											<input type="text" style="width:20%;" name="align" required="yes" message="정렬순서" is_num="yes" value="<?=$row['align']?>"> 숫자만 입력, 높은숫자 우선
										</td>
										<td>
											<a href="javascript:go_modify('frm_cate1_<?=$row['idx']?>');" class="btn_green" >수정</a>&nbsp;<a href="javascript:go_delete('frm_cate1_<?=$row['idx']?>');" class="btn_red">삭제</a>
										</td>	
									</tr>
								</form>
							<?}?>
						</table>
						
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>

<script>
	
	$(function() {
		$(".datepicker").datepicker({
			changeYear:true,
			changeMonth:true,
			minDate: '-90y',
			yearRange: 'c-90:c',
			dateFormat:'yy-mm-dd',
			showMonthAfterYear:true,
			constrainInput: true,
			dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
		});
	});
	
	function product_menu_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/product_menu_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect2";
	}

	function go_cnt_set(z){
		var tmp = z.options[z.selectedIndex].value; 
		$("#s_cnt").val(tmp);
		$("#s_mem").submit();
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	function go_list(){
		location.href = "main_select_list.php?<?=$total_param?>";
	}

	function go_regist(){
		//location.href = "product_write.php?<?=$total_param?>";
		window.open("main_product.php","pro_pro_view", "top=100,left=100,scrollbars=yes,resizable=no,width=1280,height=500");
	}

	function go_delete(frm_name){
		if(confirm('정말로 삭제 하시겠습니까?')){	
			document.forms[frm_name].is_del.value="Y";
			document.forms[frm_name].submit();		
		}
	}

</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
