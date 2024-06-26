<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));

$s_cate = trim(sqlfilter($_REQUEST['s_cate']));
$s_professor = trim(sqlfilter($_REQUEST['s_professor']));

?>

<script language="JavaScript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {

			/*if (document.frm.coupon_sect.coupon_sect_1.checked) { // 자동발행 선택시
				if(!document.frm.expire_date_auto.value){
					alert("자동발행 쿠폰의 유효기간을 입력하세요.");
					return;
				}
			} else if (document.frm.coupon_sect.coupon_sect_2.checked) { // 일반발행 선택시
				if(!document.frm.expire_date.value){
					alert("쿠폰 만료일을 입력하세요.");
					return;
				}
			}*/

			if(!document.frm.expire_date.value){
				alert("쿠폰 만료일을 입력하세요.");
				return;
			}

			if (document.frm.dis_type[0].checked){ 
				if(!document.frm.coupon_price.value){
					alert("쿠폰 할인 금액을 입력하세요.");
					return;
				}
			} else if (document.frm.dis_type[1].checked){ 
				if(!document.frm.coupon_per.value){
					alert("쿠폰 할인율을 입력하세요.");
					return;
				}
			}

		frm.submit();
		} else {
			false;
		}
	}
	
	function go_list(){
		location.href = "mcoupon_list.php?bmenu=<?=$bmenu?>&smenu=10&v_sect=<?=$v_sect?>";
	}

	function coupon_ck() { 
		if (document.frm.coupon_sect.coupon_sect_1.checked) { 
			document.getElementById("coupon_text1").style.display = "";
			document.getElementById("coupon_text2").style.display = "none";
		} else if (document.frm.coupon_sect.coupon_sect_2.checked) {
			document.getElementById("coupon_text1").style.display = "none";
			document.getElementById("coupon_text2").style.display = "";
		}
	}

	function dis_ck(){
		if (document.frm.dis_type[0].checked){
			document.all.coupon_dis_txt1.style.display= "";
			document.all.coupon_dis_txt2.style.display= "none";
		} else if (document.frm.dis_type[1].checked){
			document.all.coupon_dis_txt1.style.display= "none";
			document.all.coupon_dis_txt2.style.display= "";
		}
	}
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/member_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>쿠폰발급</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>쿠폰발급</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
				<p class="tit">쿠폰발급</p>
		<form name="frm" id="frm" action="mcoupon_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
		<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
		<input type="hidden" name="smenu" value="<?=$smenu?>"/>
		<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
						
		<table class="t_view">
				<colgroup>
					<col width="15%" />
					<col width="35%" />
					<col width="15%" />
					<col width="35%" />
				</colgroup>
					<!--
					<tr>
						<th width="120px">쿠폰 종류</th>
						<td colspan="3">
						<input type="radio" name="coupon_sect" id="coupon_sect_1" value="auto" required="yes" message="쿠폰 종류" onclick="coupon_ck();"> 회원가입시 자동발행 <input type="radio" name="coupon_sect" id="coupon_sect_2" value="normal" required="yes" message="쿠폰 종류" onclick="coupon_ck();"> 회원조회 일반발행
						</td>
				  </tr>-->

				  <input type="hidden" name="coupon_sect" value="normal">

				  <tr>
						<th>쿠폰번호</th>
						<td colspan="3">
							<input type="text" style="width:20%;" name="coupon_num" id="coupon_num" required="yes" message="쿠폰번호" is_engnum="yes">
						</td>
				  </tr>

				  <tr id="coupon_text1" style="display:none;">
						<th >쿠폰 유효기간</th>
						<td width="*" colspan="3">
							가입일로부터 <input type="text" style="width:20%;" name="expire_date_auto" id="expire_date_auto" required="no" message="쿠폰 유효기간" is_num="yes"> 일 간 &nbsp;<font style="color:red;">회원가입 자동발행 쿠폰의 경우 입력하세요.</font>
						</td>
				  </tr>
				  <!--<tr id="coupon_text2" style="display:none;">-->
				  <tr id="coupon_text2">
						<th >쿠폰 만료일</th>
						<td width="*" colspan="3">
							<input type="text" autocomplete="off" readonly name="expire_date" id="expire_date" style="width:10%;" class="datepicker" value="<?=substr($row['expire_date'],0,10)?>">
						</td>
				  </tr>
				
					<tr>
						<th >쿠폰 간략설명</th>
						<td width="*" colspan="3"><input type="text" style="width:80%;" name="coupon_title" id="coupon_title" required="yes"  message="쿠폰 간략설명" value=""></td>
					</tr>
					<tr>
						<th >할인종류</th>
						<td width="*" colspan="3"><input type="radio" name="dis_type" id="dis_type_1" value="1" required="yes" message="할인종류" onclick="dis_ck();"> 정액쿠폰 <input type="radio" name="dis_type" id="dis_type_2" value="2" required="yes" message="할인종류" onclick="dis_ck();"> 정률쿠폰</td>
					</tr>
					<tr id="coupon_dis_txt1" style="display:none;">
						<th >쿠폰 액면가</th>
						<td width="*" colspan="3">결제금액에서 <input type="text" style="width:20%;" name="coupon_price" id="coupon_price" required="no"  message="쿠폰 액면가" is_num="yes" value=""> 원 할인.</td>
					</tr>
					<tr id="coupon_dis_txt2" style="display:none;">
						<th >쿠폰 할인율</th>
						<td width="*" colspan="3">결제금액에서 <input type="text" style="width:10%;" name="coupon_per" id="coupon_per" required="no"  message="쿠폰 할인율" value=""> % 할인</td>
					</tr>
																
				</table>
			</form>
				<div class="write_btn align_r mt35">
						<button class="btn_modify" onclick="go_submit();">등록</button>
						<a href="javascript:go_list();" class="btn_list">취소</a>
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
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>