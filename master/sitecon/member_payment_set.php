<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu;

$point_sql = "select  * from member_payment_set where 1 order by idx desc limit 0,1"; // 가장 최근에 설정된 적립비율 설정내용을 가져온다.
$point_query = mysqli_query($gconnet,$point_sql);
$point_row = mysqli_fetch_array($point_query);
?>

<script type="text/javascript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}
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
						<li>결제금액 설정</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>결제금액 설정</h3>
				</div>
				<div class="write">

				<form name="frm" action="payment_set_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<tr>
						<th scope="row">VIPⅠ 추천방</th>
						<td colspan="3">VIPⅠ 추천방 글 클릭시 한건 당 <input type="text" name="payment_v1" id="payment_v1" class="input_txt" style="width:100px;" value="<?=$point_row[payment_v1]?>"  required="yes" message="VIPⅠ 추천방 결제금액" is_num="yes"/> 원을 결제함으로 설정. <font style="color:red;">반드시 숫자만 입력. 결제금액 없을경우 0 을 입력하세요.</font>
						</td>
					</tr>
					<tr>
						<th scope="row">VIPⅡ 추천방</th>
						<td colspan="3">VIPⅡ 추천방 글 클릭시 한건 당 <input type="text" name="payment_v2" id="payment_v2" class="input_txt" style="width:100px;" value="<?=$point_row[payment_v2]?>"  required="yes" message="VIPⅡ 추천방 결제금액" is_num="yes"/> 원을 결제함으로 설정. <font style="color:red;">반드시 숫자만 입력. 결제금액 없을경우 0 을 입력하세요.</font>
						</td>
					</tr>
					<tr>
						<th scope="row">VIPⅢ 추천방</th>
						<td colspan="3">VIPⅢ 추천방 글 클릭시 한건 당 <input type="text" name="payment_v3" id="payment_v3" class="input_txt" style="width:100px;" value="<?=$point_row[payment_v3]?>"  required="yes" message="VIPⅢ 추천방 결제금액" is_num="yes"/> 원을 결제함으로 설정. <font style="color:red;">반드시 숫자만 입력. 결제금액 없을경우 0 을 입력하세요.</font>
						</td>
					</tr>
					<tr>
						<th scope="row">VVIP 멤버십 1 개월</th>
						<td colspan="3">VVIP 멤버십 가입 시 <input type="text" name="payment_vip_1" id="payment_vip_1" class="input_txt" style="width:100px;" value="<?=$point_row[payment_vip_1]?>"  required="yes" message="VVIP 멤버십 가입 결제금액" is_num="yes"/> 원을 결제하면 1개월간 모든 유료게시판 열람가능토록 설정 <font style="color:red;">반드시 숫자만 입력. 결제금액 없을경우 0 을 입력하세요.</font>
						</td>
					</tr>
					<tr>
						<th scope="row">VVIP 멤버십 2 개월</th>
						<td colspan="3">VVIP 멤버십 가입 시 <input type="text" name="payment_vip_2" id="payment_vip_2" class="input_txt" style="width:100px;" value="<?=$point_row[payment_vip_2]?>"  required="yes" message="VVIP 멤버십 가입 결제금액" is_num="yes"/> 원을 결제하면 2개월간 모든 유료게시판 열람가능토록 설정 <font style="color:red;">반드시 숫자만 입력. 결제금액 없을경우 0 을 입력하세요.</font>
						</td>
					</tr>
					<tr>
						<th scope="row">VVIP 멤버십 3 개월</th>
						<td colspan="3">VVIP 멤버십 가입 시 <input type="text" name="payment_vip_3" id="payment_vip_3" class="input_txt" style="width:100px;" value="<?=$point_row[payment_vip_3]?>"  required="yes" message="VVIP 멤버십 가입 결제금액" is_num="yes"/> 원을 결제하면 3개월간 모든 유료게시판 열람가능토록 설정 <font style="color:red;">반드시 숫자만 입력. 결제금액 없을경우 0 을 입력하세요.</font>
						</td>
					</tr>
					<tr>
						<th scope="row">최근 설정일시</th>
						<td colspan="3"><?=$point_row[wdate]?></td>
					</tr>
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:go_submit();" class="btn_blue">설정등록</a>
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