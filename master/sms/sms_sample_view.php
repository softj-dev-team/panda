<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
$s_group = trim(sqlfilter($_REQUEST['s_group']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_sect3 = trim(sqlfilter($_REQUEST['s_sect3']));
$s_sect4 = trim(sqlfilter($_REQUEST['s_sect4']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.$v_sect.'&s_group='.$s_group.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_sect3='.$s_sect3.'&s_sect4='.$s_sect4.'&pageNo='.$pageNo;

//$sql = "SELECT * FROM sms_save where 1=1 and idx = '".$idx."' and section = '".$_SESSION['admin_homest_section']."' ";
$sql = "SELECT *,(select cate_name1 from common_code where 1 and type='smsmenu' and cate_level = '1' and del_ok='N' and cate_code1=a.sms_category) as cate_name,(select file_chg from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_chg FROM sms_save a where 1 and a.idx = '".$idx."' and a.is_del='N' and a.sample_yn='Y'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 메인화면 샘플문자설정 내용이 없습니다.');
	location.href =  "sms_sample_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

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
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "sms_sample_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "sms_sample_modify.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_delete(no){
		if(confirm('정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "sms_sample_delete_action.php?idx="+no+"&<?=$total_param?>";
			}
		}
	}

	function go_list(){
		location.href = "sms_sample_list.php?<?=$total_param?>";
	}

	function view_pic(ref) {
			ref = ref;
			var window_left = (screen.width-1024) / 2;
			var window_top = (screen.height-768) / 2;
			window.open(ref, "pic_window", 'width=600,height=400,status=no,scrollbars=yes,top=' + window_top + ', left=' + window_left +'');
	}

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
}

//-->
</SCRIPT>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sms_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>문자관리</li>
						<li>샘플문자 보기</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>샘플문자 보기</h3>
				</div>
				<div class="write">

			<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
				<tr>
						<th>문자구분</th>
						<td>
							<?=$view_ok?>			
						</td>
						<th>문자타입</th>
						<td>
							<?=$section?>			
						</td>
					</tr>
					<tr>
						<th>카테고리</th>
						<td width="*" colspan="3">
							<?=$row['cate_name']?>
						</td>
					</tr>
				<?if($row['sms_type'] == "mms"){?>
					<tr>
						<th>이미지 (이미지문자 전용)</th>
						<td width="*" colspan="3">
							<img src="<?=$_P_DIR_WEB_FILE?>sms/img_thumb/<?=$row['file_chg']?>" style="max-width:50%;">
						</td>
					</tr>
				<?}?>
					<tr>
						<th>샘플문자 내용</th>
						<td width="*" colspan="3">
							<?=nl2br($row['sms_content'])?>
						</td>
					</tr>	
					<tr>
						<th>등록일시</th>
						<td width="*" colspan="3">
							<?=$row['wdate']?>
						</td>
					</tr>
			</table>

			<div class="write_btn align_r">
						<!-- 목록 -->
						<a href="javascript:go_list();" class="btn_gray">목록</a>
						<!-- 수정 -->
						<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_blue">수정하기</a>
					<?//if($row[idx] != "1"){?>
						<!-- 삭제 -->
						<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제</a>	
					<?//}?>
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