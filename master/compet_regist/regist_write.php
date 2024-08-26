<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$compet_idx = trim(sqlfilter($_REQUEST['compet_idx']));
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/regist_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>참가작 관리</li>
						<li>참가작 등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>참가작 등록</h3>
				</div>
				<div class="write">

				<form name="frm" action="regist_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="compet_idx" id="compet_idx" value="<?=$compet_idx?>"/>
					<table>
						<caption>공모전 수동등록</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<tr>
						<th>디자이너 회원</th>
						<td colspan="3">
							<select name="member_idx" size="1" style="vertical-align:middle;" required="yes" message="디자이너 회원" onchange="select_mem_value(this)">
								<option value="">디자이너 회원 선택</option>
								<?
								$sub_sql = "select idx,user_name,user_id from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='GEN' order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$row['member_idx']==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">디자이너 닉네임</th>
						<td colspan="3">
							<input type="text" style="width:30%;" name="member_name" id="member_name" required="yes" message="디자이너 닉네임" value="<?=$row['member_name']?>">
						</td>
					</tr>
					<tr>
						<th scope="row">작품 제목</th>
						<td colspan="3"><input type="text" style="width:70%;" name="work_title" id="work_title" required="yes" message="작품 제목" value="<?=$row['work_title']?>"></td>
					</tr>
					<tr>
						<th scope="row">스톡컨텐츠 여부</th>
						<td colspan="3">
							<input type="radio" name="stock_ok" value="Y" required="yes"  message="스톡컨텐츠 여부" id="stock_ok_0"> <label for="stock_ok_0">스톡컨텐츠를 사용 하였습니다.</label>
							<input type="radio" name="stock_ok" value="N" required="yes"  message="스톡컨텐츠 여부" id="stock_ok_1"> <label for="stock_ok_1">스톡컨텐츠를 사용하지 않았습니다.</label>
						</td>
					</tr>
					<tr>
						<th scope="row">작품설명</th>
						<td colspan="3">
							<textarea style="width:90%;height:80px;" name="work_detail" id="work_detail" required="yes" message="작품설명" value=""><?=$row['work_detail']?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">미리보기 이미지</th>
						<td colspan="3">
							<input type="file" style="width:400px;" required="yes" message="미리보기 이미지" name="photo_0"> * 정사각형 섬네일 이미지. 권장사이즈 600*600px 
						</td>
					</tr>
					<tr>
						<th scope="row">상세작품 이미지</th>
						<td colspan="3">
							<input type="file" style="width:400px;" required="yes" message="상세작품 이미지" name="addphoto_0"> * RGB 형식의 JPG 파일. 가로 1024px, 세로 자유.
						</td>
					</tr>
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:frm.reset();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">등록</button>
					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript">
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}
	
function go_list(){
	location.href = "regist_list.php";
}

function select_mem_value(z){
	var tmp = z.options[z.selectedIndex].value; 
	//alert(tmp);
	_fra_admin.location.href="select_mem_value.php?member_idx="+tmp+"";
}
//-->
</script>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>