<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_group='.$s_group.'&pageNo='.$pageNo;

$sql = "SELECT * FROM popup_div where 1=1 and idx = '".$idx."'  ";
$query = mysqli_query($gconnet,$sql);

//echo $sql; exit;

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 팝업이 없습니다.');
	location.href =  "popup_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($row[is_use] == "Y"){
	$is_use = "<font style='color:blue;'>사용중</font>";
} elseif($row[is_use] == "N"){
	$is_use = "<font style='color:red;'>사용안함</font>";
}

$pop_width = $row[width]+20;
$pop_height = $row[height];
?>
<!-- content -->
<script type="text/javascript">
<!--
function go_view(no){
		location.href = "popup_view.php?idx="+no+"&<?=$total_param?>";
}
	
function go_modify(no){
		location.href = "popup_modify.php?idx="+no+"&<?=$total_param?>";
}

function go_delete(no){
	if(confirm('정말 삭제 하시겠습니까?')){

		if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "popup_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}
}

function go_list(){
		location.href = "popup_list.php?<?=$total_param?>";
}

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
}

function pop_pre(){
	window.open('/popup/popup.php?idx=<?=$row[idx]?>&mode=prev','pop_<?=$row[idx]?>','toolbar=no, width=<?=$pop_width?>,height=<?=$pop_height?>, left=<?=$row[x]?>,  top=<?=$row[y]?>, status=no,scrollbars=auto, resize=no');
	}
//-->		
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
			<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 관리</li>
						<li>팝업관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>팝업정보 상세보기</h3>
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
						<th >팝업제목</th>
						<td colspan="3"><?=$row[subject]?> &nbsp;&nbsp; <a href="javascript:pop_pre();" class="btn_blue">미리보기</a></td>
					</tr>
					
					<tr>
						<th >팝업 노출시작일</th>
						<td ><?=$row[startdt]?></td>
						<th >팝업 노출종료일</th>
						<td ><?=$row[enddt]?></td>
					</tr>
					<!--<tr>
						<th >팝업창 세로위치</th>
						<td ><?=$row[y]?> 픽셀</td>
						<th >팝업창 가로위치</th>
						<td ><?=$row[x]?> 픽셀</td>
					</tr>-->
					
					<tr>
						<th >팝업창 너비</th>
						<td ><?=$row[width]?> 픽셀</td>
						<th >팝업창 높이</th>
						<td ><?=$row[height]?> 픽셀</td>
					</tr>

					<tr>
						<th >링크 주소</th>
						<td colspan="3"><a href="<?=$row[link_url]?>" target="_blank"><?=$row[link_url]?></a></td>
					</tr>
					
					<tr>
						<th >팝업창 사용여부</th>
						<td colspan="3"><?=$is_use?></td>
					</tr>
					<tr>
						<th >등록일</th>
						<td colspan="3"><?=$row[wdate]?></td>
					</tr>
					<?php 
						$product_detail = stripslashes($row['content']);
						$product_detail = preg_replace("/ style=(\"|\')?([^\"\']+)(\"|\')?/","",$product_detail);
						$product_detail = preg_replace("/ style=([^\"\']+) /"," ",$product_detail); 
						$product_detail = str_replace("<img","<img style='max-width:90%;'",$product_detail);
					?>
					<tr>
						<th >팝업내용</th>
						<td colspan="3"><?=stripslashes($product_detail)?></td>
					</tr>

					<form name="frm" action="popup_view_action.php" target="_fra_admin" method="post" >
					<input type="hidden" name="idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					
					<tr>
						
						<th >팝업 사용여부</th>
						<td colspan="3">
						<select name="is_use" size="1" style="vertical-align:middle;" required="yes" message="팝업 사용여부">
						<option value="">선택하세요</option>
						<option value="Y" <?=$row[is_use]=="Y"?"selected":""?>>사용함</option>
						<option value="N" <?=$row[is_use]=="N"?"selected":""?>>사용안함</option>
						</select>
						&nbsp; <a href="javascript:go_submit();" class="btn_green">사용설정</a>
						</td>
					</tr>
							
					
					</form>

			</table>

			<div class="write_btn align_r">
				<!-- 목록 -->
				<a href="javascript:go_list();" class="btn_blue">목록</a>
				<!-- 수정 -->
				<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_green">수정하기</a>
				<!-- 삭제 -->
				<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제</a>	
			</div>
		
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>