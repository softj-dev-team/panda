<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$sql = "select *,(select cate_name1 from common_code where 1 and del_ok='N' and type='menu' and cate_level='1' and cate_code1=product_info.cate_code1) as cate_name1,(select cate_name2 from common_code where 1 and del_ok='N' and type='menu' and cate_level='2' and cate_code2=product_info.cate_code2) as cate_name2,(select sale_method from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_method,(select resale_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as resale_yn,(select sale_auth_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_auth_yn,(select sale_price from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_price,(select sale_cnt from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cnt,(select sale_ok from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_ok,(select sale_cancel_memo from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cancel_memo,(select date_cancel from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as date_cancel,(select email from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_email,(select user_nick from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_nick from product_info where 1 and idx='".$idx."' and is_del='N'";
	//echo $sql; exit;
	$query = mysqli_query($gconnet,$sql);

	if(mysqli_num_rows($query) == 0){
		error_popup("등록된 작품이 없습니다.");
	}

	$row = mysqli_fetch_array($query);
	$bbs_code = "product_info";

	$sql_sale = "select idx from product_info_sale where 1 and is_del='N' and sale_ok='1' and product_idx='".$row['idx']."'";
	$query_sale = mysqli_query($gconnet,$sql_sale);
	$sale_ing_cnt = mysqli_num_rows($query_sale);

	$product_tag_arr = json_decode($row['product_tag'],true);
?>
<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>

				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>등록작품현황</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>등록작품 보기</h3>
				</div>

				<ul class="list_tab" style="margin-top:10px;margin-bottom:10px;padding-left:10px;">
					<li class="on">
						<a href="popup_product_view.php?idx=<?=$idx?>">콘텐츠 정보</a>
					</li>
					<li>
						<a href="popup_product_sale.php?idx=<?=$idx?>">판매이력</a>
					</li>
				</ul>

				<div class="write">

					<table>
						<caption>콘텐츠 정보</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">작품명</th>
							<td colspan="3" style="background-color:#ffffff;">
								<span <?if($row['sale_ok'] == "1"){?>style="color:blue;"<?}elseif($row['sale_ok'] == "3"){?>style="color:red;"<?}?>>[<?=$arr_sale_status[$row['sale_ok']]?>]</span> <?=$row['product_title']?>
							</td>
						</tr>
					<?if($row['sale_ok'] == "3"){?>
						<tr>
							<th scope="row">판매취소일</th>
							<td>
								<?=$row['date_cancel']?>
							</td>
							<th scope="row">취소사유</th>
							<td>
								<?=nl2br($row['sale_cancel_memo'])?>
							</td>
						</tr>
					<?}?>
						<tr>
							<th scope="row">등록회원</th>
							<td>
								<?=$row['user_nick']?> ( <?=$row['user_email']?> )
							</td>
							<th scope="row">판매가</th>
							<td>
								$<?=$row['sale_price']?>
							</td>
						</tr>
						<tr>
							<th scope="row">카테고리</th>
							<td>
								<?=$row['cate_name1']?><?if($row['cate_name2']){?> > <?=$row['cate_name2']?> <?}?>
							</td>
							<th scope="row">콘텐츠</th>
							<td>
								<?=$arr_product_type[$row['product_type']]?>
							</td>
						</tr>
						<tr>
							<th scope="row">판매형태</th>
							<td>
								소유권<?if($row['sale_auth_yn'] == "Y"){?>,저작권<?}?>
							</td>
							<th scope="row">재판매여부</th>
							<td>
								<?if($row['resale_yn'] == "Y"){?>O<?}?>
							</td>
						</tr>
						<tr>
							<th scope="row">최초 등록일</th>
							<td>
								<?=$row['wdate']?>
							</td>
							<th scope="row">수정일</th>
							<td>
								<?=$row['mdate']?>
							</td>
						</tr>
						<tr>
							<td colspan="4" style="text-align:left;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;background-color:#ffffff;" id="area_artwork_content">
								
							</td>
						</tr>
						<tr>
					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='product_info' and board_code='preview' and board_idx='".$row['idx']."' order by idx asc";
						$query_file = mysqli_query($gconnet,$sql_file);
						
						for($i_file=0; $i_file<mysqli_num_rows($query_file); $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;

							if($i_file == 0){
								$default_confile_num = $row_file['idx'];
							}
					?>
							<th>썸네일 <?=$k_file?></th>
							<td>
								<!--<a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=product">-->
								<!--<a href="javascript:show_contents('<?=$k_file?>');">-->
								<a href="javascript:show_contents('<?=$row_file['idx']?>');">
									<img src="<?=$_P_DIR_WEB_FILE?>product/img_thumb/<?=$row_file['file_chg']?>" style="max-width:90%;">
								</a>
							</td>
							<?if($k_file % 2 == 0){?></tr><tr><?}?>
					<?}?>
						</tr>
					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='product_info' and board_code='artwork' and board_idx='".$row['idx']."' order by idx asc";
						$query_file = mysqli_query($gconnet,$sql_file);
						
						for($i_file=0; $i_file<mysqli_num_rows($query_file); $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
							
							/*if($i_file == 0){
								$default_confile_num = $k_file;
							}*/
					?>
						<tr>
							<th>포함된 파일 <?=$k_file?></th>
							<td colspan="3">
								<a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=product"><?=$row_file['file_org']?></a>
							</td>
						</tr>
					<?}?>
						<tr>
							<th>태그</th>
							<td colspan="3">
							<?for($tag_i=0; $tag_i<sizeof($product_tag_arr); $tag_i++){?>
								#<?=$product_tag_arr[$tag_i]['value']?>
							<?}?>
							</td>
						</tr>
						<tr>
							<th scope="row">소유권</th>
							<td>
								<?=get_now_owner($row['idx'])?>
							</td>
							<th scope="row">저작권</th>
							<td>
								<?=get_now_copyright($row['idx'])?>
							</td>
						</tr>
						<tr>
							<th>작품소개</th>
							<td colspan="3" style="text-align:left;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;background-color:#ffffff;">
								<?=nl2br($row['product_desc'])?>
							</td>
						</tr>
					</table>
					<div class="write_btn align_r">
						<a href="javascript:self.close();" class="btn_gray">닫기</a>
						<?if($row['sale_ok'] == "1"){ // 판매중일때 ?>
							<?if($sale_ing_cnt >= 2){?>
								<a href="javascript:cancel_1_open();" class="btn_red">판매취소</a>
							<?}else{?>
								<a href="javascript:set_cancel_mode('one');cancel_3_open();" class="btn_red">판매취소</a>
							<?}?>
						<?} elseif($row['sale_ok'] == "3"){ // 판매취소중일때?>
							<a href="javascript:cancel_5_open();" class="btn_blue">판매재개</a>
						<?}?>
					</div>

					<!-- 모달팝업 배경레이어 시작 -->
						<div id="modal_auth_mark_back" style="width:100%;height:100%;position:absolute;left:0;top:0;display:none;background:rgba(255, 255, 255, 0.25);"></div>
					<!-- 모달팝업 배경레이어 종료 -->

					<!-- 동일 작품 판매 안내 팝업 시작 -->
						<div id="modal_cancel_1" style="display:none; position:fixed; top:5%; left:5%; width:80%; max-width:800px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3>동일 작품 판매 안내</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									현재 동일한 작품이 판매되고 있습니다. 
									<br>함께 판매취소를 하시겠습니까?
								</td>
							</tr>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:cancel_1_close();cancel_2_open();" class="btn_blue">예</a>
								<a href="javascript:cancel_1_close();set_cancel_mode('one');cancel_3_open();" class="btn_green">아니오. 이 작품만 취소합니다.</a>
								<a href="javascript:cancel_1_close();" class="btn_gray">닫기</a>
							</div>
						</div>
					<!-- 동일 작품 판매 안내 팝업 종료 -->

					<!-- 동일작품 목록 안내 팝업 시작 -->
						<div id="modal_cancel_2" style="display:none; position:fixed; top:5%; left:5%; width:80%; max-width:800px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
						<form name="frm_check" id="frm_check" action="product_view_check_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
							<div class="list_tit">
								<h3>동일작품 목록 안내</h3>
							</div>
							<div style="padding-top:10px;padding-left:20px;">
								판매취소를 하게 되면, 선택된 모든 작품들은 사용자 화면에서 해당 작품은 숨김 처리가 되며, <br> 거래 중인 경우에 거래가 중지됩니다.
							</div>
							<span id="product_view_sale_list">
								<!-- 동일작품 목록 ajax 로 불러옴 -->
							</span>
							<div class="write_btn align_r mt35">
								<a href="javascript:sale_list_cancel();" class="btn_blue">예</a>
								<a href="javascript:cancel_2_close();" class="btn_gray">아니오</a>
							</div>
						</form>
						</div>
					<!-- 동일작품 목록 안내 팝업 종료 -->

					<!-- 판매취소 팝업 시작 -->
						<div id="modal_cancel_3" style="display:none; position:fixed; top:5%; left:5%; width:80%; max-width:800px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
						<form name="frm_cancel" id="frm_cancel" action="product_view_cancel_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
							<input type="hidden" name="mode" id="cancel_mode"/>
							<input type="hidden" name="cancel_product" id="cancel_product" value="<?=$row['idx']?>"/>
							<input type="hidden" name="cancel_member" id="cancel_member"/>
							<div class="list_tit">
								<h3>판매 취소</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									패스워드와 판매취소사유를 입력하세요
								</td>
							</tr>
							<tr>
								<th scope="row">패스워드</th>
								<td><input type="password" name="lms_pass" id="lms_pass" required="yes" message="관리자 패스워드" style="width:80%;"></td>
							</tr>
							<tr>
								<th scope="row">취소사유</th>
								<td><textarea style="width:90%;height:80px;" name="sale_cancel_memo" required="yes" message="취소사유" value=""></textarea></td>
							</tr>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:go_sale_cancel();" class="btn_blue">예, 판매를 취소합니다.</a>
								<a href="javascript:cancel_3_close();" class="btn_gray">닫기</a>
							</div>
						</form>
						</div>
					<!-- 판매취소 팝업 종료 -->

					<!-- 판매 취소 완료 팝업 시작 -->
						<div id="modal_cancel_4" style="display:none; position:fixed; top:5%; left:5%; width:80%; max-width:800px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3>취소완료</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									판매가 취소되었습니다
								</td>
							</tr>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:cancel_4_close();" class="btn_gray">확인</a>
							</div>
						</div>
					<!-- 판매 취소 완료 팝업 종료 -->

					<!-- 판매재개 팝업 시작 -->
						<div id="modal_cancel_5" style="display:none; position:fixed; top:5%; left:5%; width:80%; max-width:800px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
						<form name="frm_resell" id="frm_resell" action="product_view_cancel_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
							<input type="hidden" name="mode" id="resell_mode"/>
							<input type="hidden" name="cancel_product" id="resell_product" value="<?=$row['idx']?>"/>
							<input type="hidden" name="cancel_member" id="resell_member"/>
							<div class="list_tit">
								<h3>판매 재개</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									현재 판매가 취소 중입니다. 판매를 재개하겠습니까? 
								</td>
							</tr>
							<tr id="show_panalty_pwd_1" style="display:none;">
								<th scope="row" colspan="2">관리자 패스워드를 입력하세요</th>
							</tr>
							<tr id="show_panalty_pwd_2" style="display:none;">
								<th scope="row">패스워드 입력</th>
								<td><input type="password" name="lms_pass" id="lms_pass" required="yes" message="관리자 패스워드" style="width:80%;"></td>
							</tr>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_panalty_pwd();" class="btn_green" id="btn_panalty_1">예, 판매를 재개합니다</a>
								<a href="javascript:go_submit_resell();" class="btn_blue" id="btn_panalty_2" style="display:none;">확인</a>
								<a href="javascript:cancel_5_close();" class="btn_gray" id="btn_panalty_3">아니오</a>
							</div>
						</form>
						</div>
					<!-- 판매재개 팝업 종료 -->

					<!-- 판매 재개 완료 팝업 시작 -->
						<div id="modal_cancel_6" style="display:none; position:fixed; top:5%; left:5%; width:80%; max-width:800px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3>재개완료</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									판매가 재개되었습니다
								</td>
							</tr>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:cancel_6_close();" class="btn_gray">확인</a>
							</div>
						</div>
					<!-- 판매 재개 완료 팝업 종료 -->

				</div>
			<!-- content 종료 -->
	</div>
</div>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>

<script>

	function cancel_1_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_cancel_1").show();
	}
	function cancel_1_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_cancel_1").hide();
	}

	function cancel_2_open(){
		get_data("inner_product_view_sale_list.php","product_view_sale_list","product_idx=<?=$row['idx']?>");
		$("#modal_auth_mark_back").show();
		$("#modal_cancel_2").show();
	}
	function cancel_2_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_cancel_2").hide();
	}

	function cancel_3_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_cancel_3").show();
	}
	function cancel_3_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_cancel_3").hide();
	}

	function cancel_4_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_cancel_4").show();
	}
	function cancel_4_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_cancel_4").hide();
		opener.location.reload();
		location.reload();
	}

	function set_cancel_mode(type){
		if(type == "one"){
			$("#cancel_mode").val(type);
			$("#cancel_member").val("<?=$row['member_idx']?>");
		}
	}

	function sale_list_cancel() {
		var check = chkFrm('frm_check');
		if(check) {
			frm_check.submit();
		} else {
			false;
		}
	}

	function go_sale_cancel() {
		var check = chkFrm('frm_cancel');
		if(check) {
			frm_cancel.submit();
		} else {
			false;
		}
	}

	function cancel_5_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_cancel_5").show();
	}
	function cancel_5_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_cancel_5").hide();
	}

	function set_panalty_pwd(){
		$("#show_panalty_pwd_1").show();
		$("#show_panalty_pwd_2").show();
		$("#btn_panalty_1").hide();
		$("#btn_panalty_2").show();

		$("#resell_mode").val("resell");
		$("#resell_member").val("<?=$row['member_idx']?>");
	}

	function go_submit_resell() {
		var check = chkFrm('frm_resell');
		if(check) {
			frm_resell.submit();
		} else {
			false;
		}
	}

	function cancel_6_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_cancel_6").show();
	}
	function cancel_6_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_cancel_6").hide();
		opener.location.reload();
		location.reload();
	}

	<?if($default_confile_num){?>
		$(document).ready(function() {
			show_contents('<?=$default_confile_num?>');
		});
	<?}?>

	function show_contents(num){
		get_data("/pro_inc/inner_product_artwork_file.php","area_artwork_content","product_idx=<?=$row['idx']?>&confile_num="+num+"");
	}

</script>

</body>
</html>