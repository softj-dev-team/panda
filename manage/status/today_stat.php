<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$today_cnt_mem_sql = "select idx from member_info where 1 and del_yn='N' and memout_yn='N' and substring(wdate,1,10)='".date("Y-m-d")."'";
	$today_cnt_mem_query = mysqli_query($gconnet,$today_cnt_mem_sql);
	$today_cnt_mem = mysqli_num_rows($today_cnt_mem_query);

	$today_cnt_prod_sql = "select idx from product_info where 1 and is_del='N' and substring(wdate,1,10)='".date("Y-m-d")."'";
	$today_cnt_prod_query = mysqli_query($gconnet,$today_cnt_prod_sql);
	$today_cnt_prod = mysqli_num_rows($today_cnt_prod_query);

	$today_cnt_sale_1_sql = "select idx from product_sale_history where 1 and is_del='N' and substring(wdate,1,10)='".date("Y-m-d")."' and sale_method='1'";
	$today_cnt_sale_1_query = mysqli_query($gconnet,$today_cnt_sale_1_sql);
	$today_cnt_sale_1 = mysqli_num_rows($today_cnt_sale_1_query);

	$today_cnt_sale_2_sql = "select idx from product_sale_history where 1 and is_del='N' and substring(wdate,1,10)='".date("Y-m-d")."' and sale_method='2'";
	$today_cnt_sale_2_query = mysqli_query($gconnet,$today_cnt_sale_2_sql);
	$today_cnt_sale_2 = mysqli_num_rows($today_cnt_sale_2_query);

	$today_cnt_cancel_sql = "select idx from order_member where 1 and is_del='N' and orderstat='can' and substring(cancel_date,1,10)='".date("Y-m-d")."'";
	$today_cnt_cancel_query = mysqli_query($gconnet,$today_cnt_cancel_sql);
	$today_cnt_cancel = mysqli_num_rows($today_cnt_cancel_query);
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/today_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>금일 현황</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>금일 현황</h3>
				</div>
				<div class="write">
					<table>
						<tbody>
							<tr>
								<td style="width:49.5%;background-color:#ffffff;cursor:pointer;" valign="top" onclick="location.href='../member/member_list.php?bmenu=2&smenu=1';">
									<p style="background-image:url(../images/common/play.png); background-repeat:no-repeat; background-position:left center; font-size:16px; color:#454545; padding-left:22px;">회원가입 현황</p>
									<table class="search_list">
										<thead>
											<tr>
												<th scope="col"><?=date("Y-m-d")?> 회원가입</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="background-color:#ffffff;text-align:center;"><?=number_format($today_cnt_mem)?> 명</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="width:1%;background-color:#ffffff" valign="top"></td>
								<td style="width:49.5%;background-color:#ffffff;cursor:pointer;" valign="top" onclick="location.href='../product/product_list.php?bmenu=3&smenu=1';">
									<p style="background-image:url(../images/common/play.png); background-repeat:no-repeat; background-position:left center; font-size:16px; color:#454545; padding-left:22px;">작품등록 현황</p>
									<table class="search_list">
										<thead>
											<tr>
												<th scope="col"><?=date("Y-m-d")?> 작품등록</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="background-color:#ffffff;text-align:center;"><?=number_format($today_cnt_prod)?> 건</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr>
								<td style="width:49.5%;background-color:#ffffff;cursor:pointer;" valign="top" onclick="location.href='../sale/sale_list.php?bmenu=4&smenu=1&v_sect=general';">
									<p style="background-image:url(../images/common/play.png); background-repeat:no-repeat; background-position:left center; font-size:16px; color:#454545; padding-left:22px;">일반판매 현황</p>
									<table class="search_list">
										<thead>
											<tr>
												<th scope="col"><?=date("Y-m-d")?> 일반판매</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="background-color:#ffffff;text-align:center;"><?=number_format($today_cnt_sale_1)?> 건</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="width:1%;background-color:#ffffff" valign="top"></td>
								<td style="width:49.5%;background-color:#ffffff;cursor:pointer;" valign="top" onclick="location.href='../sale/sale_list.php?bmenu=4&smenu=2&v_sect=auction';">
									<p style="background-image:url(../images/common/play.png); background-repeat:no-repeat; background-position:left center; font-size:16px; color:#454545; padding-left:22px;">경매판매 현황</p>
									<table class="search_list">
										<thead>
											<tr>
												<th scope="col"><?=date("Y-m-d")?> 경매판매</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="background-color:#ffffff;text-align:center;"><?=number_format($today_cnt_sale_2)?> 건</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr>
								<td style="width:49.5%;background-color:#ffffff;cursor:pointer;" valign="top" onclick="location.href='../sale/sale_can_list.php?bmenu=4&smenu=4';">
									<p style="background-image:url(../images/common/play.png); background-repeat:no-repeat; background-position:left center; font-size:16px; color:#454545; padding-left:22px;">취소 현황</p>
									<table class="search_list">
										<thead>
											<tr>
												<th scope="col"><?=date("Y-m-d")?> 취소</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="background-color:#ffffff;text-align:center;"><?=number_format($today_cnt_cancel)?> 건</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="width:1%;background-color:#ffffff" valign="top"></td>
								<td style="width:49.5%;background-color:#ffffff" valign="top">
									
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>