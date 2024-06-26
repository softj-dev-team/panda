<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 전송내역페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 전송내역 로그인여부 확인?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = urldecode(sqlfilter($_REQUEST['v_sect']));
$s_group = trim(sqlfilter($_REQUEST['s_group']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_sect3 = trim(sqlfilter($_REQUEST['s_sect3']));
$s_sect4 = trim(sqlfilter($_REQUEST['s_sect4']));

$s_cate = trim(sqlfilter($_REQUEST['s_cate'])); 
if($s_cate){
	$s_sect3 = "";
	$s_sect4 = "";
}

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.urlencode($v_sect).'&s_group='.$s_group.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_sect3='.$s_sect3.'&s_sect4='.$s_sect4.'&s_cate='.$s_cate;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and transmit_type='send' and is_del='N' and (case when reserv_yn = 'Y' then CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) <= '".date("Y-m-d H:i")."' else idx > 0 end) and a.member_idx in (select idx from member_info where 1 and memout_yn not in ('Y','S') and del_yn='N' and member_type in ('GEN') and partner_idx=(select idx from member_info_company where 1 and is_del='N' and member_idx='".$_SESSION['manage_coinc_idx']."'))";

if($s_cate == "d"){ // 당일 
	$where .= " and substring(wdate,1,10) = '".date("Y-m-d")."' ";
	$s_sect3 = date("Y-m-d");
	$s_sect4 = date("Y-m-d");
} elseif($s_cate == "1"){ // 하루전 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-1 day", strtotime($s_date)));
	$where .= " and substring(wdate,1,10) >= '".$e_date."' ";
	$s_sect3 = $e_date;
	$s_sect4 = $e_date;
} elseif($s_cate == "7"){ // 이틀전 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-7 day", strtotime($s_date)));
	$where .= " and substring(wdate,1,10) >= '".$e_date."' ";
	$s_sect3 = $e_date;
	$s_sect4 = $e_date;
} elseif($s_cate == "30"){ // 3일전 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-30 day", strtotime($s_date)));
	$where .= " and substring(wdate,1,10) >= '".$e_date."' ";
	$s_sect3 = $e_date;
	$s_sect4 = $e_date;
} elseif($s_cate == "1m"){ // 11일 누적 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-1 month", strtotime($s_date)));
	$where .= " and substring(wdate,1,10) >= '".$e_date."' ";
	$s_sect3 = $e_date;
	$s_sect4 = $s_date;
} elseif($s_cate == "3m"){ // 11일 누적 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-3 month", strtotime($s_date)));
	$where .= " and substring(wdate,1,10) >= '".$e_date."' ";
	$s_sect3 = $e_date;
	$s_sect4 = $s_date;
} elseif($s_cate == "6m"){ // 11일 누적 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-6 month", strtotime($s_date)));
	$where .= " and substring(wdate,1,10) >= '".$e_date."' ";
	$s_sect3 = $e_date;
	$s_sect4 = $s_date;
}

if($v_sect){
	$where .= " and a.send_type = '".$v_sect."' ";
}
if($s_sect2){
	$where .= " and a.sms_type = '".$s_sect2."' ";
}
if($s_group){
	$where .= " and a.member_idx = '".$s_group."' ";
}

/*if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}*/

if($keyword){
	$where .= " and (a.sms_content like '%".$keyword."%' or a.sms_title like '%".$keyword."%')";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select *,(select cate_name1 from common_code where 1 and type='smsmenu' and cate_level = '1' and del_ok='N' and cate_code1=a.sms_category) as cate_name,(select file_chg from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_chg,CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) as reserv,(select user_name from member_info where 1 and del_yn='N' and member_type in ('GEN','PAT') and memout_yn='N' and idx=a.member_idx) as user_name from sms_save a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx FROM sms_save a where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;	

?>
<SCRIPT LANGUAGE="JavaScript">
<!--

	function view_pic(ref) {
			ref = ref;
			var window_left = (screen.width-1024) / 2;
			var window_top = (screen.height-768) / 2;
			window.open(ref, "pic_window", 'width=600,height=400,status=no,scrollbars=yes,top=' + window_top + ', left=' + window_left +'');
	}

	function go_view(no){
		location.href = "sms_send_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "sms_send_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "sms_send_write.php?<?=$total_param?>";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

	function go_align(no,mode,align){
		_fra_admin.location.href = "align_reset.php?idx="+no+"&mode="+mode+"&align="+align+"&<?=$total_param?>&tbn=sms_save&ret_url=/Shop/manage/sitecon/sms_send_list.php";
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}
	
//-->
</SCRIPT>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sms_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>문자관리</li>
						<li>전송내역 리스트</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>전송내역 리스트</h3>
				</div>
				<div class="list">
					<!-- 검색창 시작 -->
					<table class="search">
					<form name="s_mem" id="s_mem" method="post" action="sms_send_list.php">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<input type="hidden" name="s_cnt" id="s_cnt" value="<?=$s_cnt?>"/>
						<input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
						
						<input type="hidden" name="s_cate" id="s_cate" value="<?=$s_cate?>"/>
						
						<caption>검색</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:20%;">
							<col style="width:15%;">
							<col style="width:15%;">
							<col style="width:20%;">
							<col style="width:15%;">
						</colgroup>
						<tr>
							<th scope="row">문자구분</th>
							<td colspan="2">
								<select name="v_sect" size="1" style="vertical-align:middle;width:80%;" >
									<option value="">문자구분</option>
									<option value="gen" <?=$v_sect=="gen"?"selected":""?>>문자</option>
									<option value="adv" <?=$v_sect=="adv"?"selected":""?>>광고문자</option>
									<option value="elc" <?=$v_sect=="elc"?"selected":""?>>선거문자</option>
									<option value="pht" <?=$v_sect=="pht"?"selected":""?>>포토문자</option>
									<!--<option value="test" <?=$v_sect=="test"?"selected":""?>>테스트문자</option>-->
								</select>
							</td>
							<th scope="row">문자타입</th>
							<td colspan="2">
								<select name="s_sect2" size="1" style="vertical-align:middle;width:80%;" >
									<option value="">문자타입</option>
									<option value="sms" <?=$s_sect2=="sms"?"selected":""?>>단문</option>
									<option value="lms" <?=$s_sect2=="lms"?"selected":""?>>장문</option>
									<option value="mms" <?=$s_sect2=="mms"?"selected":""?>>이미지문자</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">발송회원</th>
							<td colspan="2">
								<select name="s_group" size="1" style="vertical-align:middle;width:80%;" >
									<option value="">카테고리</option>
								<?
								$sub_sql = "select idx,user_name from member_info where 1 and del_yn='N' and member_type in ('GEN','PAT') and memout_yn='N'  order by user_name asc"; 
								$sub_query = mysqli_query($gconnet,$sub_sql);
				
								$sub_k = 0;
								for($sub_i=0; $sub_i<mysqli_num_rows($sub_query); $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
									$sub_k = $sub_k+1;
								?>
									<option value="<?=$sub_row['idx']?>" <?=$s_group==$sub_row['idx']?"selected":""?>><?=$sub_row['user_name']?></option>
								<?}?>	
								</select>
							</td>
							<th scope="row">문자내용</th>
							<td colspan="2">
								<!--<select name="field" size="1" style="vertical-align:middle;width:30%;">
									<option value="">검색기준</option>
									<option value="main_title" <?=$field=="main_title"?"selected":""?>>전송내역 타이틀</option>
									<option value="main_memo" <?=$field=="main_memo"?"selected":""?>>메인 텍스트</option>
								</select>-->
								<input type="hidden" name="field" value="sms_content"/>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:95%;" value="<?=$keyword?>">
							</td>
						</tr>
						
						<!--<tr>
							<th scope="row">기관구분</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
							<th scope="row">공연장소</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
							<th scope="row">장르</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">공연명</th>
							<td colspan="5">
								<input type="text" title="공연명" placeholder="공연명 입력" style="width:300px">
							</td>
						</tr>-->
				</form>
				</table>
				
				<div style="text-align:left;margin-top:5px;margin-bottom:5px;padding-left:10px;">
					<a href="javascript:set_s_cate('d')" id="btn_date_d" class="date_btn btn_<?=$s_cate=="d"?"blue":"green"?>">오늘</a>&nbsp;
					<a href="javascript:set_s_cate('1')" id="btn_date_1" class="date_btn btn_<?=$s_cate=="1"?"blue":"green"?>">어제</a>&nbsp;
					<a href="javascript:set_s_cate('7')" id="btn_date_7" class="date_btn btn_<?=$s_cate=="7"?"blue":"green"?>">일주일</a>&nbsp;
					<a href="javascript:set_s_cate('30')" id="btn_date_30" class="date_btn btn_<?=$s_cate=="30"?"blue":"green"?>">지난달</a>&nbsp;
					<a href="javascript:set_s_cate('1m')" id="btn_date_1m" class="date_btn btn_<?=$s_cate=="1m"?"blue":"green"?>">1개월</a>&nbsp;
					<a href="javascript:set_s_cate('3m')" id="btn_date_3m" class="date_btn btn_<?=$s_cate=="3m"?"blue":"green"?>">3개월</a>&nbsp;
					<a href="javascript:set_s_cate('6m')" id="btn_date_6m" class="date_btn btn_<?=$s_cate=="6m"?"blue":"green"?>">6개월</a>&nbsp;
					<a href="javascript:set_s_cate('n')" id="btn_date_n" class="date_btn btn_<?=$s_cate==""?"blue":"green"?>">전체</a>&nbsp;
				</div>
				
				<!-- 검색창 종료 -->
				<div class="align_r mt20">
					<!--<button class="btn_down">엑셀다운로드</button>-->
					<button class="btn_search" onclick="s_mem.submit();">검색</button>
				</div>
				<ul class="list_tab" style="height:20px;">
					<!--<li class="on"><a href="#">월단위 결과</a></li>
					<li><a href="#">월단위 결과</a></li>
					<li><a href="#">월단위 결과</a></li>-->
				</ul>
				<!-- 리스트 시작 -->
				<div class="search_wrap">
						<div class="result">
							<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
							<div class="btn_wrap">
								<!--<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="20" <?=$s_cnt=="20"?"selected":""?>>20개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="40" <?=$s_cnt=="40"?"selected":""?>>40개보기</option>
								</select>
								<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?=$s_order=="1"?"selected":""?>>등록일 최신순</option>
									<option value="2" <?=$s_order=="2"?"selected":""?>>등록일 오래된순</option>
									<!--<option value="3" <?=$s_order=="3"?"selected":""?>>회원명 올림차순</option>
									<option value="4" <?=$s_order=="4"?"selected":""?>>회원명 내림차순</option>
								</select>-->
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>
							</div>
						</div>
										
		<form method="post" name="frm" id="frm" target="_fra_admin" >
			<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>		
			<table class="search_list">
			<colgroup>
				<col style="width:4%;">
				<col style="width:9%;">
				<col style="width:9%;">
				<col style="width:6%">
				<col style="width:17%">
				<col style="width:10%">
				<col style="width:20%">
				<col style="width:7%">
				<col style="width:6%">
				<col style="width:6%">
				<col style="width:6%">
			</colgroup>
			<thead>
		       <tr>
                    <th class="check"><input type="checkbox" onclick="javascript:CheckAll()"></th>
                    <th>등록일시</th>
					<th>발송회원</th>
                    <th>구분</th>
					<th>제목</th>
					<th>이미지</th>
                    <th>내용</th>
                    <th>총건수</th>
                    <th>성공</th>
                    <th>실패</th>
                    <th>잔여</th>
                    <!--<th>결과</th>
                    <th>비고</th>-->
                </tr>
			</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>발송내역이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){ // 대분류 루프 시작
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
					
					if($row['send_type'] == "gen"){
						$view_ok = "문자";
					} elseif($row['send_type'] == "adv"){
						$view_ok = "광고문자";
					} elseif($row['send_type'] == "elc"){
						$view_ok = "선거문자";
					} elseif($row['send_type'] == "pht"){
						$view_ok = "포토문자";
					} elseif($row['send_type'] == "test"){
						$view_ok = "테스트문자";
					}

					if($row['sms_type'] == "sms"){
						$section = "단문";
					} elseif($row['sms_type'] == "lms"){
						$section = "장문";
					} elseif($row['sms_type'] == "mms"){
						$section = "이미지문자";
					}
					
					$sql_sub_1 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='".$row['idx']."'";
					$query_sub_1 = mysqli_query($gconnet,$sql_sub_1);
					$row['receive_cnt_tot'] = mysqli_num_rows($query_sub_1);
					
					$sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='".$row['idx']."' and idx in (select fetc1 from TBL_SEND_LOG_".str_replace("-","",substr($row['wdate'],0,7))." where 1 and frsltstat='06')";
					$query_sub_2 = mysqli_query($gconnet,$sql_sub_2);
					$row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);
					
					$sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='".$row['idx']."' and idx in (select fetc1 from TBL_SEND_LOG_".str_replace("-","",substr($row['wdate'],0,7))." where 1 and frsltstat='07')";
					$query_sub_3 = mysqli_query($gconnet,$sql_sub_3);
					$row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
					
				?>
                <tr>
                    <td class="check"><input type="checkbox" name="send_idx[]" id="send_idx[]" value="<?=$row["idx"]?>" required="yes" message="전송결과"></td>
                    <td><?=substr($row['wdate'],0,10)?></td>
					<td><?=$row['user_name']?></td>
                    <td><?=$section?></td>
					<td style="text-align:left;padding-left:5px;padding-right:5px;"><?=$row['sms_title']?></td>
					<td>
					<?if($row['file_chg']){?>
						<img src="<?=$_P_DIR_WEB_FILE?>sms/img_thumb/<?=$row['file_chg']?>" style="max-width:60%;">
					<?}?>
					</td>
                    <td style="text-align:left;padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px;"><?=$row['sms_content']?></td>
                    <td><?=number_format($row['receive_cnt_tot'])?></td>
                    <td><?=number_format($row['receive_cnt_suc'])?></td>
                    <td><?=number_format($row['receive_cnt_fail'])?></td>
                    <td><?=number_format(($row['receive_cnt_tot']-$row['receive_cnt_suc']-$row['receive_cnt_fail']))?></td>
                    <!--<td>전송</td>
                    <td>비고</td>-->
                </tr>
            <?}?>
			
			</tbody>
			</table>
			
			<!-- 페이징 시작 -->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
						<!-- 페이징 종료 -->
					</div>
				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>

<script>
	var check  = 0;                                                                           
	function CheckAll(){                
		var boolchk;                                                                           
		var chk = document.getElementsByName("send_idx[]")                
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    
		for(i=0; i<chk.length;i++){                                                                    		chk[i].checked = boolchk;
		}
	}
	
	function go_tot_del() {
		var check = chkFrm('frm');
		if(check) {
			if(confirm('선택하신 발송결과를 삭제 하시겠습니까?')){
				frm.action = "sms_send_action_delete.php";
				frm.submit();
			}
		} else {
			false;
		}
	}
	
	function set_s_cate(num){
		$(".date_btn").removeClass("btn_blue");
		$(".date_btn").addClass("btn_green");
		$("#btn_date_"+num+"").removeClass("btn_green");
		$("#btn_date_"+num+"").addClass("btn_blue");
		if(num == "n"){
			$("#s_cate").val("");
		} else {
			$("#s_cate").val(num);
		}

		document.s_mem.submit();
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>