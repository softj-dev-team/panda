<?php  include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?php  include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<?php  include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<?php 
	$type = trim(sqlfilter($_REQUEST['type']));
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	$s_sido = trim(sqlfilter($_REQUEST['s_sido']));
	$s_gugun_arr = trim(sqlfilter($_REQUEST['s_gugun']));
	
	$total_param = 'keyword='.$keyword.'&s_sido='.$s_sido.'&s_gubun='.$s_gugun_arr;

	$s_gugun_arr2 = explode("|",$s_gugun_arr);
	$s_gugun = $s_gugun_arr2[0];
	$s_gugun_txt = $s_gugun_arr2[1];

	$sido_api_url = "http://13.209.50.228/search/address1"; 
	$sido_api_param = array();
	$sido_api_contents = get_curl_form_get($sido_api_url, $sido_api_param);
	$sido_decode_1 = json_decode($sido_api_contents, true);

	if($s_sido){
		$gugun_api_url = "http://13.209.50.228/search/address2?ad1_no=".$s_sido.""; 
		$gugun_api_param = array();
		$gugun_api_contents = get_curl_form_get($gugun_api_url, $gugun_api_param);
		$gugun_decode_1 = json_decode($gugun_api_contents, true);
	}

	if($s_gugun){
		$shop_api_url = "http://13.209.50.228/search/address/shop?ad2_no=".$s_gugun.""; 
		$shop_api_param = array();
		$shop_api_contents = get_curl_form_get($shop_api_url, $shop_api_param);
		$shop_decode_1 = json_decode($shop_api_contents, true);
	}
?>
<?php  include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>

<script type="text/javascript">

	function view_pic(ref) {
			ref = ref;
			var window_left = (screen.width-1024) / 2;
			var window_top = (screen.height-768) / 2;
			window.open(ref, "pic_window", 'width=600,height=400,status=no,scrollbars=yes,top=' + window_top + ', left=' + window_left +'');
	}

function go_submit() {
		var check = chkFrm('frm2');
		if(check) {
		frm2.submit();
		} else {
			false;
		}
	}

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="cate_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_gender";
	}

	 function cate_sel_2(z){
		var ktmp = document.s_mem.s_sect1.value; 
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="cate_select_2.php?cate_code1="+ktmp+"&cate_code2="+tmp+"&fm=s_mem&fname=s_sect3";
	}

	function cate_sel_3(z){
		var ktmp = document.s_mem.s_sect1.value; 
		var ktmp2 = document.s_mem.s_sect2.value; 
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="cate_select_3.php?cate_code1="+ktmp+"&cate_code2="+ktmp2+"&cate_code3="+tmp+"&fm=s_mem&fname=s_sect4";
	}

	function area_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="area_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_gugun";
	}

</script>

<!-- content -->
<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>정산관리</li>
						<li>키즈카페 찾기</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>정산대상 키즈카페 검색</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="main_product.php">
						<input type="hidden" name="proidx" value="<?php echo $proidx?>"/>
						<input type="hidden" name="type" value="<?php echo $type?>"/>
						<input type="hidden" name="keyword" value="<?=$keyword?>"/>
						<caption>검색</caption>
						<colgroup>
							<col style="width:20%;">
							<col style="width:14%;">
							<col style="width:13%;">
							<col style="width:20%;">
							<col style="width:13%;">
							<col style="width:20%;">
						</colgroup>
						<tr>
							<th scope="row">지역검색</th>
							<td colspan="5">
								<select name="s_sido" id="s_sido" style="width:40%;" onchange="area_sel_1(this)"> 
									<option value="" selected>지역선택</option>
									<?php for($json_i=0; $json_i<sizeof($sido_decode_1['address']); $json_i++) {?>
										<option value="<?php echo $sido_decode_1['address'][$json_i]['ad1No']?>" <?php echo $sido_decode_1['address'][$json_i]['ad1No']==$s_sido?"selected":""?>><?php echo $sido_decode_1['address'][$json_i]['ad1Text']?></option>
									<?php }?>
								</select>
								<select name="s_gugun" id="s_gugun" style="width:40%;"> 
									<option value="" selected>상세지역 검색</option>
									<?php if($s_sido){?>
										<?php for($json_i=0; $json_i<sizeof($gugun_decode_1['address']); $json_i++) {?>
											<option value="<?php echo $gugun_decode_1['address'][$json_i]['ad2No']?>|<?php echo $gugun_decode_1['address'][$json_i]['ad2Text']?>" <?php echo $gugun_decode_1['address'][$json_i]['ad2No']==$s_gugun?"selected":""?>><?php echo $gugun_decode_1['address'][$json_i]['ad2Text']?></option>
										<?php }?>
									<?php }?>
								</select>
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->
				<?php //=$query?>
					<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
					</div>
					<ul class="list_tab" style="height:20px;">
					
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">검색결과 총 <span><?php echo $num?></span>건</p>
							<div class="btn_wrap">
								
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
				
					<table class="search_list">
							<colgroup>
								<col style="width:5%;">
								<col style="width:55%;">
								<col style="width:20%;">
								<col style="width:20%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">선택</th>
									<th scope="col">키즈카페명</th>
									<th scope="col">대표전화</th>
									<th scope="col">휴대전화</th>
								</tr>
							</thead>
				<tbody>
			<form action="main_pro_action.php" method="post" name="frm2" id="frm2" >
				<input type="hidden" name="type" value="<?php echo $type?>"/>
				<input type="hidden" name="product_idx" value=""/>
				
				<?php for($json_i=0; $json_i<sizeof($shop_decode_1['shops']); $json_i++) {
					
					$idx = $shop_decode_1['shops'][$json_i]['no'];
					$api_url = "http://13.209.50.228/shop?no=".$idx."";  
					$api_param = array();
					$api_contents = get_curl_form_get($api_url, $api_param);
					$decode_1 = json_decode($api_contents, true);
	
					$cafe_info_sql = "select * from cafe_add_info where 1 and member_idx='".$idx."'"; 
					$cafe_info_query = mysqli_query($gconnet,$cafe_info_sql);
					$cafe_info_row = mysqli_fetch_array($cafe_info_query);

					if($cafe_info_row['cafe_name']){
						$cafe_name = $cafe_info_row['cafe_name']; 
					} else {
						$cafe_name = $decode_1['name'];
					}
					if($cafe_info_row['cafe_addr']){
						$cafe_addr = $cafe_info_row['cafe_addr'];
					} else {
						$cafe_addr = $decode_1['address'];
					}
					if($cafe_info_row['cafe_tel']){
						$cafe_tel = $cafe_info_row['cafe_tel'];
					} else {
						$cafe_tel = $decode_1['phone'];
					}
					if($cafe_info_row['cafe_hp']){
						$cafe_hp = $cafe_info_row['cafe_hp'];
					} else {
						$cafe_hp = $decode_1['phone'];
					}
				
				  ?>
					<tr>
						<td><input type="radio" name="product_idx" value="<?php echo $idx?>|<?php echo $cafe_name?>" required="yes"  message="키즈카페" <?php if($idx == $proidx){?>checked<?php }?>></td>
						<td><?php echo $cafe_name?></td>
						<td><?php echo $cafe_tel?></td>
						<td><?php echo $cafe_hp?></td>
					</tr>
				<?php }?>	
			</form>
			</tbody>
			</table>

			<div style="text-align:right;padding-right:10px;padding-top:10px;"><a href="javascript:go_submit();" class="btn_blue">선택한 키즈카페로 검색</a></div>

			<div class="pagination mt0">
				<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/paging.php";?>
			</div>
		</div>			
	</div>
	<!-- content 종료 -->
<?php  include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

 	 