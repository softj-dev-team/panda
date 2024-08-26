<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_uid = sqlfilter($_REQUEST['s_uid']);
$s_uname = sqlfilter($_REQUEST['s_uname']); 
$cr_cate = sqlfilter($_REQUEST['cr_cate']); // 구분
$cr_s_date = sqlfilter($_REQUEST['cr_s_date']); // 기간1
$cr_e_date = sqlfilter($_REQUEST['cr_e_date']); // 기간2
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&keyword='.$keyword.'&s_uid='.$s_uid.'&s_uname='.$s_uname.'&cr_s_date='.$cr_s_date.'&cr_e_date='.$cr_e_date.'&cr_cate='.$cr_cate.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

$where = " and a.is_del='N'"; // 수동발송 메시지 

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_cnt){
	$s_cnt = 10; // 기본목록 20개
}

if(!$s_order){
	$s_order = 1; 
}

$cr_cate_str = "신고";

if($cr_cate){
	$where .= " and a.type = '".$cr_cate."'";
}
if($s_uname){
	$where .= " and a.status = '".$s_uname."'";
}

if($cr_s_date){ // 기간1 
	$where .= " and substring(a.wdate,1,10) >= '".$cr_s_date."' ";
}
if($cr_e_date){ // 기간2 
	$where .= " and substring(a.wdate,1,10) <= '".$cr_e_date."' ";
}

if ($field && $keyword){
	if($field == "subtent"){
		$where .= "and (a.title like '%".$keyword."%' or a.content like '%".$keyword."%')";
	} else {
		$where .= "and ".$field." like '%".$keyword."%'";
	}
}

$pageScale = $s_cnt;  
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == 1){
	$order_by = " order by a.idx desc ";
} elseif($s_order == 2){
	$order_by = " order by wdate asc ";
} elseif($s_order == 3){
	$order_by = " order by user_name asc ";
} elseif($s_order == 4){
	$order_by = " order by user_name desc ";
}

$query = "select *,(select user_nick from member_info where 1 and del_yn='N' and idx=a.member_idx) as user_nick,(select email from member_info where 1 and del_yn='N' and idx=a.member_idx) as user_email,(select user_name from member_info where 1 and del_yn='N' and idx=a.admin_idx) as admin_name,(select user_id from member_info where 1 and del_yn='N' and idx=a.admin_idx) as admin_id,(select product_title from product_info where 1 and is_del='N' and idx=a.product_idx) as product_title from declaration_info a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
$result = mysqli_query($gconnet,$query);

$query_cnt = "select a.idx from declaration_info a where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$query_cnt_tot1 = "select idx from declaration_info where 1 and is_del='N'";
$result_cnt_tot1 = mysqli_query($gconnet,$query_cnt_tot1);
$num_tot1 = mysqli_num_rows($result_cnt_tot1);

$query_cnt_tot2 = "select idx from declaration_info where 1 and is_del='N' and status='N'";
$result_cnt_tot2 = mysqli_query($gconnet,$query_cnt_tot2);
$num_tot2 = mysqli_num_rows($result_cnt_tot2);

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
						<li><?=$cr_cate_str?> 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$cr_cate_str?> 리스트</h3>
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
							<th scope="row">신고유형</th>
							<td colspan="2">
								<select name="cr_cate" id="cr_cate" style="vertical-align:middle;width:45%;">
									<option value="">분류</option>
								<?
									$ik = 0;
									foreach ($arr_decla_type as $key=>$val) {
								?>
									<option value="<?=$key?>" <?=$cr_cate==$key?"selected":""?>><?=$val?></option>
								<?
									$ik= $ik+1;
									}
								?>		
								</select>
							</td>
							<th scope="row">답변상태</th>
							<td colspan="2">
								<input type="radio" name="s_uname" id="s_uname" value="N" <?=$s_uname=="N"?"checked":""?>> 대기중
								<input type="radio" name="s_uname" id="s_uname" value="Y" <?=$s_uname=="Y"?"checked":""?>> 처리완료 
							</td>
						</tr>
						<tr>
							<th scope="row">기간</th>
							<td colspan="5">
								<input type="text" title="검색" name="cr_s_date" id="cr_s_date" class="datepicker" style="width:10%;" value="<?=$cr_s_date?>"> ~ 
								<input type="text" title="검색" name="cr_e_date" id="cr_e_date" class="datepicker" style="width:10%;" value="<?=$cr_e_date?>">
							</td>
						</tr>
					</form>
				</table>
				<!-- 검색창 종료 -->


			<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="member_excel_list.php">
				<input type="hidden" name="keyword" value="<?=$keyword?>"/>
				<input type="hidden" name="cr_s_date" value="<?=$cr_s_date?>"/>
				<input type="hidden" name="cr_e_date" value="<?=$cr_e_date?>"/>
				<input type="hidden" name="s_uid" value="<?=$s_uid?>"/>
				<input type="hidden" name="s_uname" value="<?=$s_uname?>"/>
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
							<p class="txt">미조치 <?=number_format($num_tot2)?> / 총 <span><?=number_format($num_tot1)?></span>건</p>
							<div class="btn_wrap">
								<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="20" <?=$s_cnt=="20"?"selected":""?>>20개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="40" <?=$s_cnt=="40"?"selected":""?>>40개보기</option>
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
				
				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="s_education" value="<?=$s_education?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:33%;">
								<col style="width:8%;">
								<col style="width:12%;">
								<col style="width:12%;">
								<col style="width:8%;">
								<col style="width:12%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">번호</th>
									<th scope="col">작품명</th>
									<th scope="col">등록자</th>
									<th scope="col">신고일</th>
									<th scope="col">등록유형</th>
									<th scope="col">답변상태</th>
									<th scope="col">조치일</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">신고내역이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum = $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;	
						?>
						<tr>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$listnum?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['product_title']?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['user_nick']?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['wdate']?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$arr_decla_type[$row['type']]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');">
							<?if($row['status'] == "Y"){?>
								<font style="color:blue;">처리완료</font>
							<?}elseif($row['status'] == "N"){?>
								<font style="color:red;">대기중</font>
							<?}?>
							</a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['addate']?></a></td>
						</tr>
					<?}?>	
						</tbody>
						</table>
					</form>
						
						<!--<div style="text-align:right;margin-top:10px;padding-right:10px;">
							<a href="javascript:go_tot_del();" class="btn_red">선택삭제</a>
						</div>-->

						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>

					</div>
				</div>
			</div>
		</div>
	<!-- content 종료 -->
	</div>
</div>

<script>
	$(".datepicker").datepicker({
		dateFormat: "yy-mm-dd",
		prevText: '이전 달',
		nextText: '다음 달',
		minDate: '-100y',
		yearRange: 'c-99:c+1',
		monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		dayNames: ['일', '월', '화', '수', '목', '금', '토'],
		dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
		dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
		showMonthAfterYear: true,
		yearSuffix: '년',
		changeYear: true,
		changeMonth: false
	});

	function go_view(no){
		location.href = "decla_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "decla_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "decla_write.php?<?=$total_param?>";
	}

	function go_cnt_set(z){
		var tmp = z.options[z.selectedIndex].value; 
		$("#s_cnt").val(tmp);
		$("#s_mem").submit();
	}

	var check  = 0;                                                                            //체크 여부 확인
	function CheckAll(){                
		var boolchk;                                                                              //boolean형 변수 
		var chk = document.getElementsByName("member_idx[]")                 //체크박스의 name값
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
		for(i=0; i<chk.length;i++){                                                                    
			chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
		}
	}

	function go_tot_del() {
		var check = chkFrm('frm');
		if(check) {
			if(confirm('선택하신 회원을 삭제 하시겠습니까?')){
				if(confirm('삭제하신 회원정보는 복구할 수 없습니다. 정말 삭제 하시겠습니까?')){
					frm.action = "member_list_action_del.php";
					frm.submit();
				}
			}
		} else {
			false;
		}
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>