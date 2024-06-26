<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<script type="text/javascript" src="../js/coupon.js"></script>
<style type="text/css">
.adr_area {float:left;width:570px;}
.adr_area .adr_sel {width:120px;}
.adr_area .btn_sch {display:inline-block;background:#666;color:#fff;border-radius:4px;padding:4px 10px;vertical-align:middle;font-weight: bold;}

.adr_area .tbl_wrap {margin-top:12px;border:1px solid #cdcdcd;height:401px;width:550px;}
.adr_area table {width:100%;}
.adr_area table thead th {background:#444842;height:35px;border-left:1px solid #575a56;color:#fff;}
.adr_area table thead th:first-child {border-left:none;}
.adr_area table tbody td {padding:6px 5px;text-align:center;}

.adr_area table thead {float:left;width:550px;}
.adr_area table tbody {float:left;width:550px;height:360px;overflow-x:hidden;overflow-y:scroll;padding:3px;}
.adr_area table thead:after, .adr_area table tbody:after {content:"";display:block;clear:both;}
/*.adr_area table td.chk {width:20px;}
.adr_area table td.name {width:60px;}
.adr_area table td.date {width:95px;}
.adr_area table td.phn {width:110px;}*/
.align_r {text-align:right;}
</style>

<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$mode = trim(sqlfilter($_REQUEST['mode']));
$mode_1 = trim(sqlfilter($_REQUEST['mode_1']));
$mem_idx = trim(sqlfilter($_REQUEST['mem_idx']));

/*$pkColumn = "idx";
$value = $mem_idx;
$tableName = "member_coupon_set";
$result = MgrGeneralView($tableName, $pkColumn, $value);*/

$query = "select * from member_coupon_set where 1 and idx='".$mem_idx."'";
$result = mysqli_query($gconnet,$query);
$row = mysqli_fetch_array($result);

$expire_date_arr = explode("-",$row[expire_date]);

?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/member_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>쿠폰발급</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>생성된 쿠폰 회원에게 발급</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
				<p class="tit">생성된 쿠폰 회원에게 발급</p>
				<form id="theForm" name="theForm" method="post" action="mcoupon_savel_action.php" target="_fra_admin" enctype="multipart/form-data">
				<input type="hidden" name="mem_idx" value="<?=$mem_idx?>"/>
				
			<table width="100%">
			<tr><td width="60%" valign="top">		
			<table class="t_view">
				<colgroup>
					<col width="25%" />
					<col  />
				</colgroup>

		
			<tr id="mail_select_txt1">
				<th>쿠폰 수령 아이디</th>
				<td colspan="3">
					<textarea id="txt_receiver" name="txt_receiver" style="width:100%;height:100px;" required="yes" message="쿠폰수령 아이디"></textarea>
					<p class="mt10 notice">* 여러 명에게 보내려면 아이디 다음에(,) 콤마로 구분 예) choi,choi2 (공백없이)</p>
				</td>
			</tr>
		
			
					<?if($row[coupon_sect] == "auto"){?>
					<tr>
						<th>쿠폰 유효기간</th>
						<td colspan="3">가입일로 부터 <?=$row[expire_date_auto]?> 일간</td>
					</tr>
					<?}elseif($row[coupon_sect] == "normal"){?>
					<tr>
						<th>쿠폰 만료일</th>
						<td colspan="3"><?=$row[expire_date]?></td>
					</tr>
					<?}?>

					<tr>
						<th>쿠폰 간략설명</th>
						<td colspan="3"><?=$row[coupon_title]?></td>
					</tr>

					<tr>
						<th >할인종류</th>
						<td width="*" colspan="3">
						<?if($row[dis_type] == "1"){?>
							정액쿠폰
						<?}elseif($row[dis_type] == "2"){?>
							정률쿠폰
						<?}?>
						</td>
					</tr>
					
					<?if($row[dis_type] == "1"){?>
					<tr>
						<th>쿠폰 액면가</th>
						<td colspan="3"><?=number_format($row[coupon_price],0)?> 원</td>
					</tr>
					<?}elseif($row[dis_type] == "2"){?>
					<tr>
						<th>쿠폰 할인율</th>
						<td colspan="3"><?=number_format($row[coupon_per],0)?> %</td>
					</tr>
					<?}?>

					<tr>
						<th>생성일</th>
						<td colspan="3"><?=$row[wdate]?></td>
					</tr>
				
			</table>
			</td>
			<td width="40%" valign="top">
			<!-- 주소록 -->
				<div class="adr_area">
						<select name="inr_s_level" id="inr_s_level" size="1" required="yes"  message="국적" style="vertical-align:middle;" >
							<option value="">국가선택</option>
							<option value="United States of America" <?=$s_level=="United States of America"?"selected":""?>>United States of America</option>
							<option value="China" <?=$s_level=="China"?"selected":""?>>China</option>
							<option value="Canada" <?=$s_level=="Canada"?"selected":""?>>Canada</option>
							<option value="Austrailia" <?=$s_level=="Austrailia"?"selected":""?>>Austrailia</option>
							<option value="Japan" <?=$s_level=="Japan"?"selected":""?>>Japan</option>
							<option value="Hong Kong" <?=$s_level=="Hong Kong"?"selected":""?>>Hong Kong</option>
							<option value="Taiwan" <?=$s_level=="Taiwan"?"selected":""?>>Taiwan</option>
							<option value="Singapore" <?=$s_level=="Singapore"?"selected":""?>>Singapore</option>
						</select>

						<select name="inr_field" id="inr_field" size="1" style="vertical-align:middle;">
							<option value="">검색기준</option>
							<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
							<option value="user_name" <?=$field=="user_name"?"selected":""?>>성명</option>
							<option value="cell" <?=$field=="cell"?"selected":""?>>연락처</option>
						</select>
						<input type="text" name="inr_keyword" id="inr_keyword" style="width:50%;" value="<?=$keyword?>" >
						<a href="javascript:go_portpolio_3();" class="btn_sch">조회</a>
					<div class="tbl_wrap">
						<table style="width:550px;">
							<thead>
								<tr>
									<th style="width:100px;"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"> 이름</th>
									<th style="width:150px;">아이디</th>
									<th style="width:150px;">국적</th>
									<th style="width:150px;">스카이프 ID</th>
								</tr>
							</thead>
							<tbody id="port_list_area3">
								<!-- ajax member_select_coupon.php 로 불러오는 영역 -->
							</tbody>
						</table>
					</div>
					<!-- //tbl_wrap -->
				</div>
				<!-- //주소록 -->
			</td></tr></table>
			</form>

			<form name="portfrm_3" id="portfrm_3" method="post" target="_blank">
			<input type="hidden" name="s_gender" id="s_gender">
			<input type="hidden" name="s_level" id="s_level">
			<input type="hidden" name="field" id="field">
			<input type="hidden" name="keyword" id="keyword">
			</form>

			<div class="write_btn align_r">
					<button type="button" class="btn_modify" onclick="btn_tran_click();">쿠폰발급</button>
					<a href="javascript:frm.reset();" class="btn_list">취소</a>
			</div>
				
		</div>
		<!-- content 종료 -->
	</div>
</div>
<!-- content 종료 -->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>