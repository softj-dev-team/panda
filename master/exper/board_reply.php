<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
/*if(!$_AUTH_REPLY){
	error_back("덧글작성 권한이 없습니다.");
	exit;
}*/
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&s_cate_code='.$s_cate_code.'&v_sect='.$v_sect.'&pageNo='.$pageNo;

$sql = "SELECT * FROM board_content where 1=1 and idx = '".$idx."' and bbs_code='".$bbs_code."' ;";
$query = mysqli_query($gconnet,$sql);

//echo $sql; exit;

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 게시물이 없습니다.');
	location.href =  "board_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($s_cate_code) {
	$sql_sub1 = "select cate_name1 from board_cate where cate_code1='".$s_cate_code."' and cate_level='1' ";
	$query_sub1 = mysqli_query($gconnet,$sql_sub1);
	$row_sub1 = mysqli_fetch_array($query_sub1);
	$bbs_cate_name = $row_sub1['cate_name1'];
}

if($bbs_code){
	$bbs_str = $_include_board_board_title;
} elseif($s_cate_code) {
	$bbs_str = $bbs_cate_name." 카테고리에 해당하는 ";
}

?>

<script type="text/javascript" src="/Cheditor/cheditor.js"></script> <!-- CHEditor 관련 스크립트  -->

<script language="JavaScript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			//myeditor.outputBodyHTML(); // fm_post 에 작성한 내용 입력.
			frm.submit();
		} else {
			false;
		}
	}
	
	function go_list(){
		location.href = "board_view.php?idx=<?=$idx?>&<?=$total_param?>";
	}
	
</script>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/customer_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li><?=$bbs_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$bbs_str?> 답글</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
					<p class="tit"><?=$bbs_str?> 답글달기 <!--<span>&#40;&#42; 필수입력&#41;</span>--></p>
			
			<form name="frm" action="board_reply_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="idx" value="<?=$idx?>"/>
			<input type="hidden" name="bbs_code" value="<?=$bbs_code?>"/>
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>
			
			<input type="hidden" name="member_idx" value="0">
			<input type="hidden" name="view_idx" value="<?=$row['member_idx']?>">
			<input type="hidden" name="p_no" value="<?=$row[idx]?>">

			<input type="hidden" name="is_html" value="Y">
			<input type="hidden" name="bbs_sect" value="<?=$row['bbs_sect']?>">
			
			<?if($row[is_secure] == "Y"){?>
				<input type="hidden" name="passwd" value="<?=$_SESSION['admin_gosm_password']?>">
			<?} else { ?>
				<input type="hidden" name="passwd" value="<?=$passwd?>">
			<? } ?>

			<input type="hidden" name="ip" value="<?= $_SERVER['REMOTE_ADDR']?>">
			
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				
				<!--<?if($row[is_secure] == "Y"){?>
					<input type="hidden" name="is_secure" value="Y">
				<?} else {?>
					<tr>
						<th>비밀글 여부</th>
						<td width="*">
							<input type="radio" name="is_secure" value="Y"> 비밀글
							<input type="radio" name="is_secure" value="N" checked> 공개글
						</td>
					</tr>
				<?}?>-->
					<tr>
						<th >제 목</th>
						<td colspan="3"><input type="text" style="width:50%;" name="subject" required="yes"  message="게시물제목" value="Re: >> <?=$row[subject]?>"></td>
					</tr>
				<?if($_include_board_write_auth != "AD"){?>
					<tr>
						<th >작성자</th>
						<td colspan="3"><input type="text" style="width:10%;" name="writer" required="yes"  message="작성자" value="<?=$_SESSION['admin_gosm_name']?>"></td>
					</tr>
				<?}?>						
											
					<tr>
						<th >내 용</th>
						<td colspan="3">
						<?
							include $_SERVER["DOCUMENT_ROOT"].$_P_DIR_FCKeditor."fckeditor.php" ;
							$oFCKeditor = new FCKeditor('fm_write') ;
							$oFCKeditor->BasePath	= $_P_DIR_FCKeditor;
							$oFCKeditor->Config['SkinPath'] =  '/PROGRAM_FCKeditor/editor/skins/office2003/';
							$oFCKeditor->Height = 300;
							$oFCKeditor->Value		= '';
							$oFCKeditor->Create() ;
						?>
						</td>
					</tr>
		
				<?
				for($file_i=0; $file_i<$_include_board_file_cnt; $file_i++){
					$file_k = $file_i+1;
				?>
					<tr>
						<th >첨부파일 <?=$file_k?></th>
						<td colspan="3"><input type="file" style="width:400px;" required="no" message="첨부파일" name="file_<?=$file_i?>"></td>
					</tr>
			 <?}?>			
		
			</table>
			</form>

			<div class="write_btn align_r mt35">
				<button class="btn_modify" onclick="go_submit();">답글등록</button>
				<a href="javascript:go_list();" class="btn_list">취소</a>
				<!--<button class="btn_del">취소</button>-->
			</div>
				
				</div>
			</div>
		</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
