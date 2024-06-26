<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 회원, 지점
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 일반, VIP
$s_level = sqlfilter($_REQUEST['s_level']); // 회원등급
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); // 로그인 구분
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); // 추천인 (지점) 별
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

$where = " and memout_yn in ('Y') and del_yn='N' and member_type in ('GEN')";

$member_sect_str = "탈퇴";

if(!$pageNo){
	$pageNo = 1;
}
if(!$s_cnt){
	$s_cnt = 10; // 기본목록 10개
}
if(!$s_order){
	$s_order = 1; 
}

if($v_sect){ 
	$where .= " and master_ok = '".$v_sect."'";
}
if($s_gender){ 
	$where .= " and member_gubun = '".$s_gender."'";
}
if($s_gubun){ // 가입시작일
	$where .= " and substring(out_m_date,1,10) >= '".$s_gubun."'";
}
if($s_level){ // 가입종료일
	$where .= " and substring(out_m_date,1,10) <= '".$s_level."'";
}

$pageScale = $s_cnt;  
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == 1){
	$order_by = " order by wdate desc ";
} elseif($s_order == 2){
	$order_by = " order by wdate asc ";
} elseif($s_order == 3){
	$order_by = " order by user_name asc ";
} elseif($s_order == 4){
	$order_by = " order by user_name desc ";
}

$query = "select *,(select com_name from member_info_company where 1 and is_del='N' and idx=a.partner_idx order by idx desc limit 0,1) as com_name from member_info a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<script type="text/javascript">
<!--	 
	function go_view(no){
		location.href = "member_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "member_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "member_write.php?<?=$total_param?>";
	}

	function go_regist_add(no){
		location.href = "member_write_add.php?member_idx="+no+"&<?=$total_param?>";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}
	
	function go_excel() {
	var check = chkFrm('order_excel_frm');
		if(check) {
			order_excel_frm.submit();
		} else {
			false;
		}
	}

	function go_cnt_set(z){
		var tmp = z.options[z.selectedIndex].value; 
		$("#s_cnt").val(tmp);
		$("#s_mem").submit();
	}

	function go_order_set(z){
		var tmp = z.options[z.selectedIndex].value; 
		$("#s_order").val(tmp);
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

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="cate_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect1";
	}

	 function cate_sel_2(z){
		var ktmp = document.s_mem.s_gender.value;
		if(z){
			var tmp = z.options[z.selectedIndex].value; 
			_fra_admin.location.href="cate_select_2.php?cate_code1="+ktmp+"&cate_code2="+tmp+"&fm=s_mem&fname=s_sect2";
		} else {
			_fra_admin2.location.href="cate_select_2.php?cate_code1="+ktmp+"&fm=s_mem&fname=s_sect2";
		}
	}
	
//-->
</script>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/partner_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>탈퇴 회원 리스트</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>탈퇴 회원 리스트</h3>
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
							<th scope="row">탈퇴일</th>
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
				
			<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="member_excel_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
			<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
			<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
			<input type="hidden" name="s_sect2" value="<?=$s_sect2?>"/>
			<input type="hidden" name="s_level" value="<?=$s_level?>"/>
			<input type="hidden" name="field" value="<?=$field?>"/>
			<input type="hidden" name="keyword" value="<?=htmlspecialchars($keyword)?>"/>
			</form>
			<!-- 엑셀 출력을 위한 전송 폼 종료 -->
					
					<ul class="list_tab" style="height:20px;">
						<!--<li class="on"><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>-->
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
							<div class="btn_wrap">
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
				
				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
						
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:22.5%;">
								<col style="width:22.5%;">
								<col style="width:25%;">
								<col style="width:25%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">순번</th>
									<th scope="col">아이디</th>
									<th scope="col">회원명</th>
									<th scope="col">가입일시</th>
									<th scope="col">탈퇴일시</th>
								</tr>
							</thead>
							<tbody>
							<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">탈퇴한 회원이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($i == mysqli_num_rows($result)-1){
									$member_idx .= $row['idx'];
								} else {
									$member_idx .= $row['idx'].",";
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

								if($row['gender'] == "M"){
									$gender = "남성";
								} elseif($row['gender'] == "F"){
									$gender = "여성";
								} else {
									$gender = "";
								}

						?>
						<tr>
							<!--<td><input type="checkbox" name="member_idx[]" id="member_idx[]" value="<?=$row["idx"]?>" required="yes"  message="회원"/></td>-->
							<td><?=$listnum?></td>
							<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['user_id']?></a></td>
							<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['user_name']?></a></td>
							<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['wdate']?></a></td>
							<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['out_m_date']?></a></td>
						</tr>
					<?}?>	
						</tbody>
						</table>

					</form>
						<!--<div class="table_btn align_l mt20 pl20">
							<button>선택 가입승인</button>
							<button>선택 탈퇴처리</button>
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
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>