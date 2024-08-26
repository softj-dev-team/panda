<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
if(!$_AUTH_WRITE){
	error_back("본문작성 권한이 없습니다.");
	exit;
}

$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code'])); // 게시판 코드
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&s_cate_code='.$s_cate_code;

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
						<li>HOME</li><li>고객센터</li>
						<li><?=$bbs_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$bbs_str?> 등록</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
					<p class="tit"><?=$bbs_str?> 등록 <!--<span>&#40;&#42; 필수입력&#41;</span>--></p>
			
			<form name="frm" action="board_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="bbs_code" value="<?=$bbs_code?>">
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>
			<input type="hidden" name="member_idx" value="0">
			<input type="hidden" name="view_idx" value="0">
			<input type="hidden" name="is_html" value="Y">
			<input type="hidden" name="passwd" value="<?=$_SESSION['admin_coinc_password']?>">
			<input type="hidden" name="ip" value="<?= $_SERVER['REMOTE_ADDR']?>">
			
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
		
			<?if($_include_board_is_notice == "Y"){ // 리스트 공지사항 보기가 가능한 게시판에 한하여 시작 ?>	
					<tr>
						<th >공지글 지정</th>
						<td colspan="3"><input type="checkbox" name="is_popup" value="Y"> 상단 공지글로 지정함</td>
					</tr>
			<?}?>
			<?if($bbs_code == "faq"){?>
				<tr>	
					<th >분류</th>
						<td colspan="3">
							<select name="bbs_sect" size="1" style="vertical-align:middle;" required="yes"  message="분류">
								<option value="">분류</option>
							<?
							$sect1_sql = "select * from board_category where 1 and is_del='N' and board_code='faq' and lang='kor' ORDER BY priority ASC";
							$sect1_result = mysqli_query($gconnet,$sect1_sql);
								for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
									$row1 = mysqli_fetch_array($sect1_result);
							?>
								<option value="<?=$row1['subject']?>" <?=$row['bbs_sect']==$row1['subject']?"selected":""?>><?=$row1['subject']?></option>
							<?}?>
							</select>
						</td>
				</tr>
			<?}?>
					<tr>
						<th ><?if($bbs_code == "faq"){?>질 문<?}else{?>제 목<?}?></th>
						<td colspan="3"><input type="text" style="width:50%;" name="subject" required="yes"  message="<?if($bbs_code == "faq"){?>질 문<?}else{?>제 목<?}?>" value=""></td>
					</tr>
			<?if($_include_board_write_auth != "AD"){?>
					<tr>
						<th>작성자</th>
						<td colspan="3"><input type="text" style="width:20%;" name="writer" required="yes"  message="작성자" value="<?=$_SESSION['admin_coinc_name']?>"></td>
						<!--<th >분류</th>
						<td >
							<select name="bbs_sect" size="1" style="vertical-align:middle;" required="yes"  message="게시판 분류">
								<option value="">게시판 분류</option>
								<option value="news" <?=$bbs_sect=="news"?"selected":""?>>뉴스</option>
								<option value="event" <?=$bbs_sect=="event"?"selected":""?>>이벤트</option>
								<option value="info" <?=$bbs_sect=="info"?"selected":""?>>이용방법</option>
								<option value="ad" <?=$bbs_sect=="ad"?"selected":""?>>광고</option>
							</select>
						</td>-->
					</tr>
			<?}?>
					<tr>
						<th ><?if($bbs_code == "faq"){?>답 변<?}else{?>내 용<?}?> <?//=$_SERVER["DOCUMENT_ROOT"].$_P_DIR_FCKeditor."fckeditor.php"?></th>
						<td colspan="3">
							<textarea placeholder="내용" name="ir2" id="editor"><?=stripslashes($row[content])?></textarea>
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
				<button class="btn_modify" onclick="go_submit();">등록하기</button>
				<a href="javascript:go_list();" class="btn_list">취소</a>
				<!--<button class="btn_del">취소</button>-->
			</div>
				
				</div>
			</div>
		</div>

<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script language="JavaScript"> 

	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "editor",
		sSkinURI: "/smarteditor2/SmartEditor2Skin.html",	
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
				//alert("아싸!");	
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			oEditors.getById["editor"].exec("UPDATE_CONTENTS_FIELD", []);
			frm.submit();
		} else {
			false;
		}
	}

	function go_list(){
		location.href = "board_list.php?<?=$total_param?>";
	}

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="../partner/cate_select_1.php?cate_code1="+tmp+"&fm=frm&fname=gugun";
	}

	function cate_sel_2(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="cate_select_2.php?cate_code1="+tmp+"&fm=frm&fname=sing";
	}

</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

		