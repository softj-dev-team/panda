<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));

$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&pageNo='.$pageNo;

$query = "select * from member_coupon_set where 1 and idx='".$idx."' and is_del='N'";
$result = mysqli_query($gconnet,$query);

if(mysqli_num_rows($result) == 0){
	error_go("발급된 쿠폰이 없습니다.","mcoupon_list.php?".$total_param);
}

$row = mysqli_fetch_array($result);

$expire_date_arr = explode("-",$row['expire_date']);

/*if($row[member_sect] == "general"){
	$member_sect = "일반회원";
} elseif($row[member_sect] == "group"){
	$member_sect = "단체회원";
}*/

$rcnt_sql = "select idx from member_coupon where 1 and coupon_idx='".$row['idx']."' and is_del='N'";
$rcnt_query = mysqli_query($gconnet,$rcnt_sql);
$rcnt = mysqli_num_rows($rcnt_query);

if($rcnt > 0){
	error_go("다운받은 회원이 존재하기 때문에 수정할 수 없습니다.","mcoupon_view.php?idx=".$idx."&".$total_param);
}
?>

<script language="JavaScript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
		/*	if (document.frm.coupon_sect.coupon_sect_1.checked) { // 자동발행 선택시
				if(!document.frm.expire_date_auto.value){
					alert("자동발행 쿠폰의 유효기간을 입력하세요.");
					return;
				}
			} else if (document.frm.coupon_sect.coupon_sect_2.checked) { // 일반발행 선택시
				if(!document.frm.expire_date.value){
					alert("쿠폰 만료일을 입력하세요.");
					return;
				}
			} */
			frm.submit();
		} else {
			false;
		}
	}

	function go_view(no){
		location.href = "mcoupon_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	
	function coupon_ck() { 
		if (document.frm.coupon_sect.coupon_sect_1.checked) { 
			coupon_text1.style.display = 'block';
			coupon_text2.style.display = 'none';
		} else if (document.frm.coupon_sect.coupon_sect_2.checked) {
			coupon_text1.style.display = 'none';
			coupon_text2.style.display = 'block';
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
					<h3>쿠폰수정</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
				<p class="tit">발급한 쿠폰수정</p>
		<form name="frm" action="mcoupon_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="idx" value="<?=$idx?>"/>
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>
								
		<table class="t_view">
				<colgroup>
					<col width="170" />
					<col  />
					<col width="170" />
					<col  />
				</colgroup>	
					<!--<tr>
						<th width="120px">쿠폰 종류</th>
						<td colspan="3">
						<input type="radio" name="coupon_sect" id="coupon_sect_1" value="auto" required="yes" message="쿠폰 종류" onclick="coupon_ck();"> 회원가입시 자동발행 <input type="radio" name="coupon_sect" id="coupon_sect_2" value="normal" required="yes" message="쿠폰 종류" onclick="coupon_ck();"> 회원조회 일반발행
						</td>
				  </tr>-->
				  <input type="hidden" name="coupon_sect" value="normal">

				   <tr>
						<th>쿠폰번호</th>
						<td colspan="3">
							<input type="text" style="width:20%;" name="coupon_num" id="coupon_num" required="yes" message="쿠폰번호" is_engnum="yes" value="<?=$row['coupon_num']?>">
						</td>
				  </tr>

				  <tr id="coupon_text1" style="display:none;">
						<th >쿠폰 유효기간</th>
						<td width="*" colspan="3">
							가입일로부터 <input type="text" style="width:70px;" name="expire_date_auto" required="no" message="쿠폰 유효기간" is_num="yes"> 일 간
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
						<td width="*" colspan="3"><input type="text" style="width:80%;" name="coupon_title" required="yes"  message="쿠폰 간략설명" value="<?=$row['coupon_title']?>"></td>
					</tr>

					<tr>
						<th >할인종류</th>
						<td width="*" colspan="3"><input type="radio" name="dis_type" id="dis_type_1" value="1" <?=$row['dis_type']=="1"?"checked":""?> required="yes" message="할인종류" onclick="dis_ck();"> 정액쿠폰 <input type="radio" name="dis_type" id="dis_type_2" value="2" <?=$row['dis_type']=="2"?"checked":""?> required="yes" message="할인종류" onclick="dis_ck();"> 정률쿠폰</td>
					</tr>
					<tr id="coupon_dis_txt1" style="display:<?=$row['dis_type']=="1"?"":"none"?>;">
						<th >쿠폰 액면가</th>
						<td width="*" colspan="3">결제금액에서 <input type="text" style="width:20%;" name="coupon_price" required="no"  message="쿠폰 액면가" is_num="yes" value="<?=$row['coupon_price']?>"> 원 할인.</td>
					</tr>
					<tr id="coupon_dis_txt2" style="display:<?=$row['dis_type']=="2"?"":"none"?>;">
						<th >쿠폰 할인율</th>
						<td width="*" colspan="3">결제금액에서 <input type="text" style="width:20%;" name="coupon_per" required="no"  message="쿠폰 할인율" is_num="yes" value="<?=$row['coupon_per']?>"> % 할인</td>
					</tr>

											
				</table>
			
				<div class="align_c margin_t20">
					<!-- 등록 -->
					<a href="javascript:go_submit();" class="btn_blue">수정</a>
					<!-- 목록 -->
					<a href="javascript:go_view('<?=$row[idx]?>');" class="btn_red">취소</a>
				</div>
		</table>
		
		</form>
			</div>
			</div>
		</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>