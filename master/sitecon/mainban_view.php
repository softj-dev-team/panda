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

//$sql = "SELECT * FROM mainban_info where 1=1 and idx = '".$idx."' and section = '".$_SESSION['admin_homest_section']."' ";
$sql = "SELECT * FROM mainban_info where 1=1 and idx = '".$idx."' ";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 메인화면 배너설정 내용이 없습니다.');
	location.href =  "mainban_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($row[view_ok] == "Y"){
		$view_ok = "사용함";
} elseif($row[view_ok] == "N"){
		$view_ok = "사용안함";
}

if($row[pro_idx]){
	
	$sql_name = "select pro_name from product_info where 1=1 and idx = '".$row[pro_idx]."' ";
	$query_name = mysqli_query($gconnet,$sql_name);
	$row_name = mysqli_fetch_array($query_name);
	$pro_name = $row_name[pro_name];

}

if($row[link_sect] == "P"){
	$link_sect = "상품 정보로 링크";
} elseif($row[link_sect] == "U"){
	$link_sect = "별도 URL 링크";
} elseif($row[link_sect] == "N"){
	$link_sect = "링크없음";
} 

if($s_sect1 == "pc"){
	$sect_title = "PC";
} elseif($s_sect1 == "mobile"){
	$sect_title = "앱";
}

if($row[section] == "movie"){
	$section = "동영상";
} elseif($row[section] == "img"){
	$section = "이미지";
}
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "mainban_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "mainban_modify.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_delete(no){
		if(confirm('정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "mainban_delete_action.php?idx="+no+"&<?=$total_param?>";
			}
		}
	}

	function go_list(){
		location.href = "mainban_list.php?<?=$total_param?>";
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
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 관리</li>
						<li>배너 보기</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>배너 보기</h3>
				</div>
				<div class="write">

			<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
			<?if($row[section] == "img"){?>
					<tr>
						<th >링크유형</th>
						<td colspan="3"><?=$link_sect?></td>
					</tr>			
					<tr>
						<th >링크주소</th>
						<td colspan="3">
						<?if($row[link_sect] == "P"){?>
							<!--<a href="/Shop/si/product/product.php?s_divname=<?=$s_divname?>&s_sect1=<?=$s_sect1?>&s_sect2=<?=$s_sect2?>&s_sect3=<?=$s_sect3?>&s_sect4=<?=$s_sect4?>&ItemSeq=<?=$ItemSeq?>" target="_blank">--><?=$pro_name?><!--</a>-->
						<?} elseif($row[link_sect] == "U"){?>
							<a href="<?=$row[link_url]?>" target="_blank"><?=$row[link_url]?></a>
						<?}?>
						</td>
					</tr>
					<tr>
						<th >배너 이미지</th>
						<td colspan="3" style="padding-left:10px;padding-top:10px;padding-bottom:10px;">
							<?if($row[file_c] != "" && $row[file_c] != " "){?>
								<img src="<?=$_P_DIR_WEB_FILE?>main_banner/<?=$row[file_c]?>" style="border:0;max-width:90%;"></a>
							<?}?>
						</td>
					</tr>
			<?}elseif($row[section] == "movie"){?>
					<tr>
						<th >동영상</th>
						<td colspan="3" style="padding-left:10px;padding-top:10px;padding-bottom:10px;">
							<iframe  src="https://www.youtube.com/embed/<?=str_replace("https://youtu.be/","",$row['link_url'])?>" frameborder="0" width="560" height="315" allowfullscreen></iframe>
						</td>
					</tr>
			<?}?>
					<tr>
						<th>배너위치</th>
						<td><?=$row['main_sect']?></td>
						<th>사용여부</th>
						<td><?=$view_ok?></td>
					</tr>
					<!--<tr>
						<th>배너 타이틀</th>
						<td width="*" colspan="3">
							<?=$row['main_title']?>
						</td>
					</tr>-->
					<tr>
						<th >배너 텍스트</th>
						<td colspan="3"><?=nl2br(stripslashes($row[main_memo]))?></td>
					</tr>
					<tr>
						<th>등록일</th>
						<td colspan="3"><?=$row[wdate]?></td>
					</tr>

					<form name="frm" action="mainban_view_action.php" target="_fra_admin" method="post" >
					<input type="hidden" name="idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					
					<tr>
						
						<th >배너 사용여부</th>
						<td colspan="3">
						<select name="view_ok" size="1" style="vertical-align:middle;" required="yes" message="배너 사용여부">
						<option value="">선택하세요</option>
						<option value="Y" <?=$row[view_ok]=="Y"?"selected":""?>>사용함</option>
						<option value="N" <?=$row[view_ok]=="N"?"selected":""?>>사용안함</option>
						</select>
						&nbsp; <a href="javascript:go_submit();" class="btn_blue">사용설정</a>
						</td>
					</tr>
							
					
					</form>

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