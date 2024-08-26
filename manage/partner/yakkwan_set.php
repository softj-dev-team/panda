<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	
	$sql = "select idx from delv_guide where 1 and cate_code1 = '".$cate_code1."'";
	$query = mysqli_query($gconnet,$sql);

	if(mysqli_num_rows($query) == 0){
		$mode="write";
	} else {
		$row = mysqli_fetch_array($query);
		$upidx = $row['idx'];
		$mode="update";
	}

	switch($cate_code1){
		case "host1":
		$p_title = "메인화면 인사말 설정";
		break;
		case "host2":
		$p_title = "비슷 TITLE 설정";
		break;
		case "hostin":
		$p_title = "비슷은 누구? 소개글설정";
		break;
		case "mem1":
		$p_title = "회원가입 약관설정";
		break;
		case "mem2":
		$p_title = "개인정보 보호정책설정";
		break;
		case "jehu":
		$p_title = "기업제휴";
		break;
		case "cancelyak":
		$p_title = "구매자 안내사항 및 환불정책";
		break;
		case "fit1":
		$p_title = "핏 소개 안내문구";
		break;
		case "fit2":
		$p_title = "핏 커리큘럼 안내문구";
		break;
		case "fit3":
		$p_title = "핏 커리큘럼 작성팁";
		break;
		case "fit4":
		$p_title = "핏 추천대상 안내문구";
		break;
		case "fit5":
		$p_title = "핏 유의사항 안내문구";
		break;
	}
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
	<?if($cate_code1 == "host1" || $cate_code1 == "host2"){?>
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/curri_left.php"; // 좌측메뉴?>
	<?}elseif($cate_code1 == "hostin" || $cate_code1 == "jehu" || $cate_code1 == "cancelyak"){?>
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sitecon_left.php"; // 좌측메뉴?>
	<?}elseif($cate_code1 == "mem1" || $cate_code1 == "mem2"){?>
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/partner_left.php"; // 좌측메뉴?>
	<?}elseif($cate_code1 == "fit1" || $cate_code1 == "fit2" || $cate_code1 == "fit3" || $cate_code1 == "fit4" || $cate_code1 == "fit5"){?>
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/advert_left.php"; // 좌측메뉴?>
	<?}?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li><?=$p_title?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$p_title?></h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
					<p class="tit"><?=$p_title?> <!--<span>&#40;&#42; 필수입력&#41;</span>--></p>
				<form name="frm" action="yakkwan_set_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="cate_code1" id="cate_code1" value="<?=$cate_code1?>"/>
					<input type="hidden" name="mode" id="mode" value="<?=$mode?>"/>
					<input type="hidden" name="upidx" id="upidx" value="<?=$upidx?>"/>
					<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
			<?if($cate_code1 == "jehu" || $cate_code1 == "hostin" || $cate_code1 == "host1"){?>
					<?if(mysqli_num_rows($query) == 0){?>
						<tr>
							<th scope="row">음성파일</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="이미지" name="photo_0"> 
							</td>
						</tr>
					<?}else{?>
						<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1=1 and board_tbname='delv_guide' and board_code = '".$cate_code1."' and board_idx='".$row['idx']."' order by idx asc";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 1){
							$cnt_file = 1;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
						?>
						
						<input type="hidden" name="pfile_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="pfile_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="pfile_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
						
						<tr>
							<th>음성파일</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="첨부파일" name="photo_<?=$i_file?>">
								<?if($row_file['file_chg']){?>
									<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=delv_guide"><?=$row_file['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="pdel_org_<?=$i_file?>" value="Y">)
								<?} else{ ?>
									<input type="hidden" name="pdel_org_<?=$i_file?>" value="">
								<?}?>
							</td>
						</tr>
						<?}?>
					<?}?>
			<?}?>
						<tr>
							<th scope="row">소개글</th>
							<td colspan="3">
								<textarea name="m_intro" id="editor" style="width:80%;height:300px;"><?=get_yakkwan($cate_code1)?></textarea>
							</td>
						</tr>
					</table>
				</form>

					<div class="write_btn align_r mt35">
						<button class="btn_modify" onclick="go_submit();">저장</button>
					</div>
				</div>
			</div>
		</div>


<?if($cate_code1 == "mem1" || $cate_code1 == "mem2" || $cate_code1 == "cancelyak" || $cate_code1 == "fit1" || $cate_code1 == "fit2" || $cate_code1 == "fit4" || $cate_code1 == "fit5"){?>
<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">
<!--
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
//-->
</script>
<?}else{?>
<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">
<!--
	/*var oEditors = [];
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
	});*/

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		//oEditors.getById["editor"].exec("UPDATE_CONTENTS_FIELD", []);
		frm.submit();	
	} else {
		false;
	}
}
//-->
</script>
<?}?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>