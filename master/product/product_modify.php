<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	
	$date_s = trim(sqlfilter($_REQUEST['date_s'])); 
	$date_e = trim(sqlfilter($_REQUEST['date_e']));
	$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
	$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
	$s_protype_arr = urldecode($_REQUEST['s_protype']);
	$s_salemtd = trim(sqlfilter($_REQUEST['s_salemtd'])); 
	$s_salests = trim(sqlfilter($_REQUEST['s_salests']));
	$field = "product_title";
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

	$total_param = "bmenu=".$bmenu."&smenu=".$smenu."&date_s=".$date_s."&date_e=".$date_e."&s_sect1=".$s_sect1."&s_sect2=".$s_sect2."&s_protype=".urlencode($s_protype_arr)."&s_salemtd=".$s_salemtd."&s_salests=".$s_salests."&keyword=".$keyword."&s_cnt=".$s_cnt."&s_order=".$s_order;

	//$product_tag = "2222222222,33333333333,999999999999999999999";

	$sql = "select *,(select cate_name1 from common_code where 1 and del_ok='N' and type='menu' and cate_level='1' and cate_code1=product_info.cate_code1) as cate_name1,(select cate_name2 from common_code where 1 and del_ok='N' and type='menu' and cate_level='2' and cate_code2=product_info.cate_code2) as cate_name2,(select sale_method from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_method,(select resale_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as resale_yn,(select sale_auth_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_auth_yn,(select sale_price from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_price,(select sale_cnt from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cnt,(select sale_ok from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_ok,(select sale_cancel_memo from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cancel_memo,(select date_cancel from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as date_cancel,(select email from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_email,(select user_nick from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_nick from product_info where 1 and idx='".$idx."' and is_del='N'";
	//echo $sql; exit;
	$query = mysqli_query($gconnet,$sql);

	if(mysqli_num_rows($query) == 0){
		error_go("등록된 작품이 없습니다.","product_list.php?".$total_param);
	}

	$row = mysqli_fetch_array($query);
	$bbs_code = "product_info";

	$product_tag_arr = json_decode($row['product_tag'],true);
	$product_tag = "";
	for($tag_i=0; $tag_i<sizeof($product_tag_arr); $tag_i++){
		if($tag_i == sizeof($product_tag_arr)-1){
			$product_tag .= $product_tag_arr[$tag_i]['value'];
		} else {
			$product_tag .= $product_tag_arr[$tag_i]['value'].",";
		}
	}

	$sql_sale = "select * from product_info_sale where 1 and product_idx='".$row['idx']."' and member_idx='".$row['member_idx']."' and is_del='N'";
	$query_sale = mysqli_query($gconnet,$sql_sale);
	$row_sale = mysqli_fetch_array($query_sale);

?>
<!-- 해시태그 라이브러리 -->
<script src="https://unpkg.com/@yaireo/tagify"></script>
<script src="https://unpkg.com/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/product_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>작품 등록 관리</li>
						<li>작품 등록관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>작품 수정</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<form name="frm" id="frm" action="product_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
				<input type="hidden" name="idx" id="idx" value="<?=$idx?>"/>
				<input type="hidden" name="total_param" id="total_param" value="<?=$total_param?>"/>
				<input type="hidden" name="pageNo" id="pageNo" value="<?=$pageNo?>"/>
				<input type="hidden" name="sale_idx" id="sale_idx" value="<?=$row_sale['idx']?>"/>
				<div class="write">
					<p class="tit">기본 정보</p>
					<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<tr>
							<th scope="row">등록자</th>
							<td>
								<select name="member_idx" id="member_idx" style="vertical-align:middle;width:80%;" required="yes" message="등록자">
									<option value="">선택하세요</option>
								<?
								$sect1_sql = "select idx,user_nick,email from member_info where 1 and del_yn='N' and member_type='GEN' and memout_yn='N' order by user_nick asc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['idx']?>" <?=$row['member_idx']==$row1['idx']?"selected":""?>><?=$row1['user_nick']?> (<?=$row1['email']?>)</option>
								<?}?>
								</select>
							</td>
							<th scope="row">카테고리</th>
							<td>
								<select name="cate_code1" id="cate_code1" style="vertical-align:middle;width:45%;" onchange="product_menu_sel_1(this)" required="yes" message="대분류">
									<option value="">대분류</option>
								<?
								$sect1_sql = "select cate_code1,cate_name1 from common_code where 1 and is_del='N' and del_ok='N' and type='menu' and cate_level='1' order by cate_align desc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['cate_code1']?>" <?=$row['cate_code1']==$row1['cate_code1']?"selected":""?>><?=$row1['cate_name1']?></option>
								<?}?>
								</select>
								&nbsp;
								<select name="cate_code2" id="cate_code2" style="vertical-align:middle;width:45%;" required="no" message="중분류">
									<option value="">중분류</option>
								<?
								$sect1_sql = "select cate_code2,cate_name2 from common_code where 1 and is_del='N' and del_ok='N' and type='menu' and cate_level='2' and cate_code1='".$row['cate_code1']."' order by cate_align desc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['cate_code2']?>" <?=$row['cate_code2']==$row1['cate_code2']?"selected":""?>><?=$row1['cate_name2']?></option>
								<?}?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">작품명</th>
							<td colspan="3">
								<input type="text" style="width:60%;" name="product_title" id="product_title" value="<?=$row['product_title']?>" required="yes" message="작품명"/>
							</td>
						</tr>
						<tr>
							<th scope="row">작품유형</th>
							<td colspan="3">
							<? foreach ($arr_product_type as $key=>$val) {
								//if(in_array($key, $row['product_type'])){
								if($key == $row['product_type']){
									$check = "checked";
								} else {
									$check = "";
								}
							?>
								<input type="radio" id="product_type_<?=$key?>" name="product_type" <?=$check?> value="<?=$key?>" required="yes" message="작품유형"/> <?=$val?> &nbsp; 
							<?}?>
							</td>
						</tr>
						<tr>
							<th scope="row">작품설명</th>
							<td colspan="3">
								<textarea style="width:90%;height:100px;" name="product_desc" id="product_desc" required="no" message="작품설명" value=""><?=$row['product_desc']?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">해시태그</th>
							<td colspan="3">
								<input type="text" name="product_tag" id="product_tag" required="no" message="해시태그" style="width:90%;background-color:#ffffff;" value="<?=$product_tag?>">
							</td>
						</tr>
						<tr>
							<th scope="row">Artwork 파일</th>
							<td colspan="3">
								<input type="file" id="file_add" name="file_artwork[]" class="upload_art" multiple="multiple">
								 <div class="file_upload_name">
									<ul>
										<?
										$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='product_info' and board_code='artwork' and board_idx='".$row['idx']."' order by idx asc";
										$query_file = mysqli_query($gconnet,$sql_file);
										
										for($i_file=0; $i_file<mysqli_num_rows($query_file); $i_file++){
											$row_file = mysqli_fetch_array($query_file);
											$k_file = $i_file+1;
										?>
											<li id="old_artwork_<?=$row_file['idx']?>">
												<p><?=$row_file['file_org']?></p>
												<button type='button' class='btn_typeH btn_black' onclick="old_del_artwork('<?=$row_file['idx']?>');">Delete</button><input type='hidden' name='old_file_art_list[]' value='<?=$row_file['file_chg']?>'/>
											</li>
										<?}?>
									</ul>
                                    <ul class="file_upload_ul">
                                
                                    </ul>
                                </div>
							</td>
						</tr>
						<tr>
							<th scope="row">Preview 파일</th>
							<td colspan="3">
								<input type="file" id="file_add_pre" name="file_preview[]" class="upload_preview" multiple="multiple" accept="image/*,video/*">
								<div class="file_upload_name" style="margin-top:10px;">
									<ul>
										<?
										$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='product_info' and board_code='preview' and board_idx='".$row['idx']."' order by idx asc";
										$query_file = mysqli_query($gconnet,$sql_file);
										
										for($i_file=0; $i_file<mysqli_num_rows($query_file); $i_file++){
											$row_file = mysqli_fetch_array($query_file);
											$k_file = $i_file+1;
										?>
											<li id="old_preview_<?=$row_file['idx']?>">
												<img src='<?=$_P_DIR_WEB_FILE?>product/img_thumb/<?=$row_file['file_chg']?>' style='max-width:100px;'>
												<button type='button' class='btn_typeH btn_black' onclick="old_del_preview('<?=$row_file['idx']?>');">Delete</button><input type='hidden' name='old_file_prv_list[]' value='<?=$row_file['file_chg']?>'/>
											</li>
										<?}?>
									</ul>
                                    <ul class="file_preview_ul">
                                        
                                    </ul>
                                </div>
							</td>
						</tr>
						<tr>
							<th scope="row">작품 Disclosure</th>
							<td colspan="3">
							<? foreach ($arr_product_disc as $key=>$val) {
								//if(in_array($key, $row['product_type'])){
								if($key == $row['product_type']){
									$check = "checked";
								} else {
									$check = "";
								}
							?>
								<input type="radio" id="product_auth_<?=$key?>" name="product_auth" <?=$check?> value="<?=$key?>" required="yes" message="작품 Disclosure"/> <?=$val?> &nbsp; 
							<?}?>
							</td>
						</tr>
					</table>
					

					<p class="tit">판매 정보</p>
					<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<tr>
							<th scope="row">저작권</th>
							<td>
								<input type="radio" id="sale_auth_yn_1" name="sale_auth_yn" <?=$row_sale['sale_auth_yn']=="Y"?"checked":""?> value="Y" required="yes" message="저작권"/> 저작권 포함 &nbsp; 
								<input type="radio" id="sale_auth_yn_2" name="sale_auth_yn" <?=$row_sale['sale_auth_yn']=="N"?"checked":""?> value="N" required="yes" message="저작권"/> 저작권 미포함 &nbsp; 	
							</td>
							<th scope="row">판매방식</th>
							<td>
							<? foreach ($arr_sale_method as $key=>$val) {
								if($key == $row_sale['sale_method']){
									$check = "checked";
								} else {
									$check = "";
								}
							?>
								<input type="radio" id="sale_method_<?=$key?>" name="sale_method" <?=$check?> value="<?=$key?>" required="yes" message="판매방식" onclick="show_sale_method('<?=$key?>');"/> <?=$val?> &nbsp; 
							<?}?>
							</td>
						</tr>
						<tr id="area_sale_method_1" style="display:<?=$row_sale['sale_method']=="1"?"":"none"?>;">
							<th scope="row">판매금액</th>
							<td>
								<input type="text" style="width:50%;" name="sale_price" id="sale_price" value="<?=$row_sale['sale_price']?>" required="no" message="판매금액"/> USD
							</td>
							<th scope="row">판매수량</th>
							<td>
								<input type="text" style="width:50%;" name="sale_cnt" id="sale_cnt" value="<?=$row_sale['sale_cnt']?>" required="no" message="판매수량"/> Quantity
							</td>
						</tr>
						<tr id="area_sale_method_2" style="display:<?=$row_sale['sale_method']=="2"?"":"none"?>;">
							<th scope="row">경매 시작금액</th>
							<td>
								<input type="text" style="width:50%;" name="sale_price_auc" id="sale_price_auc" value="<?=$row_sale['sale_price']?>" 
								required="no" message="판매금액"/> USD
							</td>
							<th scope="row">경매 기간</th>
							<td>
								<input type="text" autocomplete="off" readonly name="auc_sdate" id="auc_sdate" style="width:45%;" class="datepicker" value="<?=$row_sale['auc_sdate']?>"> ~ <input type="text" autocomplete="off" readonly name="auc_edate" id="auc_edate" style="width:45%;" class="datepicker" value="<?=$row_sale['auc_edate']?>">
							</td>
						</tr>
					</table>
				</form>

					<div class="write_btn align_r mt35">
						<button class="btn_modify" onclick="go_submit();">저장</button>
						<a href="javascript:go_list();" class="btn_list">목록</a>
						<!--<button class="btn_del">취소</button>-->
					</div>
				</div>
			</div>
		</div>

<script>
	$(function() {
		$(".datepicker").datepicker({
			changeYear:true,
			changeMonth:true,
			minDate: '-90y',
			yearRange: 'c-90:c',
			dateFormat:'yy-mm-dd',
			showMonthAfterYear:true,
			constrainInput: true,
			dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
		});
	});
	
	function product_menu_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/product_menu_select_1.php?cate_code1="+tmp+"&fm=frm&fname=cate_code2";
	}

	function product_menu_sel_2(z){
		var cate_code1 = $("#s_sect1").val();
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/product_menu_select_2.php?cate_code1="+cate_code1+"&cate_code2="+tmp+"&fm=s_mem&fname=v_sect";
	}

	const input = document.querySelector('input[name=product_tag]');
	tagify = new Tagify(input, {
		maxTags: 15, // 최대 허용 태그 갯수
		trim: true,
	})

	function show_sale_method(type){
		if(type == "1"){
			$("#area_sale_method_1").show();
			$("#area_sale_method_2").hide();
		} else if(type == "2"){
			$("#area_sale_method_1").hide();
			$("#area_sale_method_2").show();
		}
	}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

	function go_list(){
		location.href="product_list.php?<?=$total_param?>&pageNo=<?=$pageNo?>";
	}

// register artwork 파일 업로드
targetFile = document.querySelector(".upload_art");
targetFile2 = document.querySelector(".upload_preview");
if (targetFile || targetFile2) {
  targetFile.addEventListener("change", fileName);
  targetFile2.addEventListener("change", fileName2);
}
/* Upload Artwork Files */
function fileName() {
  var fileMax = 100 * 1024 * 1024; // 100M
 
  if (targetFile.files.length > 10) {
    modalNumber();
    targetFile.value = "";
    targetFileBox.innerHTML = "";
    //함수 종료
    return;
  } else {
    fileList = "";
    for (i = 0; i < targetFile.files.length; i++) {
      //파일의 개수만큼 리스트를 추가적으로 생성
      fileList +=
        "<li id='file_list_"+i+"'><p>" +
        targetFile.files[i].name +
        "</p><button type='button' class='btn_typeH btn_black btn_del_regi file'>Delete</button><input type='hidden' name='up_file_art_list[]' id='up_file_art_list_"+i+"' value='"+targetFile.files[i].name+"'/></li>";

      //파일 용량 제한
      var fileSize = targetFile.files[i].size;

      //100M 넘으면 alert 발생 후 값을 반영하지 않음
      if (fileSize > fileMax) {
        alert("Only files less than 100M can be attached.");
        targetFile.value = "";
        targetFileBox.innerHTML = "";
      }
    }
    targetFileBox = document.querySelector(".file_upload_ul");
    targetFileBox.innerHTML = fileList;
  }

  //Upload Artwork Files 파일 삭제
  for (j = 0; j < targetFile.files.length; j++) {
    let btn_fileDel = document.querySelectorAll(
      ".file_upload_ul .btn_del_regi"
    );
    btn_fileDel[j].addEventListener("click", function (e) {
      var target = e.target;
      var nodeLi = target.closest("li");
	  nodeLi.remove();
     //실제 파일 삭제는 아님. 리스트 이름만 삭제됨
    });
  }
}

/* Uploaded Artwork Preview */
function fileName2() {
  var fileMax = 50 * 1024 * 1024; // 50M

  if (targetFile2.files.length > 10) {
    modalNumber();
    targetFile2.value = "";
    targetFileBox.innerHTML = "";
    //함수 종료
    return;
  } else {
    
	fileList = "";
	
    for (i = 0; i < targetFile2.files.length; i++) {
				
		const imageSrc = URL.createObjectURL(targetFile2.files[i]);
		//alert (imageSrc);
      //파일의 개수만큼 리스트를 추가적으로 생성
      fileList +=
        "<li><img src='"+imageSrc+"' style='max-width:100px;'> <button type='button' class='btn_typeH btn_black btn_del_regi file'>Delete</button><input type='hidden' name='up_file_preview_list[]' id='up_file_preview_list_"+i+"' value='"+targetFile2.files[i].name+"'/></li>";

      //파일 용량 제한
      var fileSize = targetFile2.files[i].size;

      //100M 넘으면 alert 발생 후 값을 반영하지 않음
      if (fileSize > fileMax) {
        alert("Only files less than 100M can be attached.");
        targetFile2.value = "";
        targetFileBox.innerHTML = "";
      }
    }
    targetFileBox = document.querySelector(".file_preview_ul");
    targetFileBox.innerHTML = fileList;
  }

  // Uploaded Artwork Preview 파일 삭제
  for (j = 0; j < targetFile2.files.length; j++) {
    let btn_fileDel2 = document.querySelectorAll(
      ".file_preview_ul .btn_del_regi"
    );
    btn_fileDel2[j].addEventListener("click", function (e) {
      var target = e.target;
      var nodeLi = target.closest("li");
      nodeLi.remove();
      //실제 파일 삭제는 아님. 리스트 이름만 삭제됨
    });
  }
}

function old_del_artwork(delnum){
	$("#old_artwork_"+delnum+"").remove();
}
function old_del_preview(delnum){
	$("#old_preview_"+delnum+"").remove();
}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>