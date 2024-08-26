<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu;
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
			<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 관리</li>
						<li>팝업관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>팝업등록</h3>
				</div>
				<div class="write">
<!-- content -->
	
			<form name="frm" action="popup_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>
			
			<table class="t_view">
				<colgroup>
					<col width="15%;" />
					<col width="85%;"/>
				</colgroup>
				
				<tr>
						<th >사용여부</th>
						<td ><input type="radio" name="is_use" value="Y" required="yes" message="팝업사용여부"> 사용
						<input type="radio" name="is_use" value="N" required="yes" message="팝업사용여부"> 중지</td>
					</tr>
					
					<tr>
						<th >팝업제목</th>
						<td ><input type="text" style="width:50%;" name="subject" required="yes"  message="팝업제목" value=""></td>
					</tr>
					
					<tr>
						<th >팝업 시작일</th>
						<td ><input type="text" name="startdt" style="width:10%;" id="startdt" value="" required="yes" message="팝업 시작일" readonly autocomplete="off" class="datepicker"></td>
					</tr>
					<tr>
						<th >팝업 종료일</th>
						<td ><input type="text" name="enddt" style="width:10%;" id="enddt" value="" required="yes" message="팝업 종료일" readonly autocomplete="off" class="datepicker"></td>
					</tr>
					
					<!--<tr>
						<th >팝업 위치(X)</th>
						<td ><input type="text" name="x" style="width:10%;" required="yes" message="팝업 위치(X)" is_num="yes"> 픽셀</td>
					</tr>

					<tr>
						<th >팝업 위치(Y)</th>
						<td ><input type="text" name="y" style="width:10%;" required="yes" message="팝업 위치(Y)" is_num="yes"> 픽셀</td>
					</tr>-->

					<tr>
						<th >팝업 크기(가로)</th>
						<td ><input type="text"  name="width" value="318" style="width:20%;" required="yes" message="팝업 크기(가로)" is_num="yes"> 픽셀</td>
					</tr>

					<tr>
						<th >팝업 크기(세로)</th>
						<td ><input type="text"  name="height" value="350" style="width:20%;" required="yes" message="팝업 크기(세로)" is_num="yes"> 픽셀</td>
					</tr>

					<tr>
						<th >링크 주소</th>
						<td ><input type="text" style="width:40%;" name="link_url" required="no"  message="링크주소" value=""> * 팝업창에 이미지 등록시, 이미지 클릭하면 이동할 URL 입니다.</td>
					</tr>
					
					<tr>
						<th >팝업내용</th>
						<td >
							<textarea name="fm_write" id="editor" style="width:80%;height:300px;"></textarea>
						</td>
					</tr>
				
			</table>
			</form>

					<div class="write_btn align_r">
						<a href="javascript:go_submit();" class="btn_blue">등록하기</a>
						<a href="javascript:go_list();" class="btn_gray">등록취소</a>
					</div>
				</div>
			<!-- content 종료 -->
	</div>
</div>
<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">

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
		location.href = "popup_list.php?<?=$total_param?>";
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>