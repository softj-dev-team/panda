<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
//$idx = trim(sqlfilter($_REQUEST['idx']));
$idx = $_SESSION['manage_coinc_idx'];
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_group='.$s_group.'&pageNo='.$pageNo;

//echo $total_param;

$sql = "SELECT * FROM member_info where 1=1 and idx = '".$idx."' and member_type='AD' ";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<script type="text/javascript">
	<!--
	alert('해당하는 강좌가 없습니다.');
	location.href =  "adminm_list.php?<?=$total_param?>";
	//-->
</script>
<?
exit;
}

$row = mysqli_fetch_array($query);

$cell_arr = explode("-",$row[cell]);
$email_arr = explode("@",$row[email]);
?>

<script type="text/javascript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			/*if(document.frm.level.value == "3"){
				if(!document.frm.ma_idx.value){
				alert("매입처를 선택하세요.");
				return;
				}
			}*/
			frm.submit();
		} else {
			false;
		}
	}
	
	function go_list(){
		//location.href = "adminm_view.php?idx=<?=$idx?>&<?=$total_param?>";
		history.back();
	}

	 function user_level(z){
			
		var tmp = z.options[z.selectedIndex].value; 
		
			/*if(tmp == "3"){
			level_text1.style.display = 'block';
			} else {
			level_text1.style.display = 'none';
			}*/
	}

	function emailChg(){
		frm.email2.value = frm.email_select.value;
	}
	
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>관리자 관리</li>
						<li>관리자 정보</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>관리자 비밀번호 변경</h3>
				</div>
				<div class="write">

				<form name="frm" action="adminm_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<!--<tr>
						<th >관리레벨</th>
						<td >
						<select name="user_level"  required="yes" message="관리레벨">
						<option value="">선택하세요</option>
						<option value="004">전체관리자</option>
						<option value="005">육아용품 관리자</option>
						</select>
						</td>
					</tr>-->
					
					<tr>
						<th >운영자 ID</th>
						<td colspan="3"><?=$row[user_id]?></td>
					</tr>
					<tr>
						<th >운영자 비밀번호</th>
						<td colspan="3"><input type="password" style="width:150px;" name="user_pwd" required="yes" message="비밀번호" is_engnum = "yes">  * 비번 변경시에만 입력</td>
					</tr>
					<!--<tr>
						<th >운영자 성명</th>
						<td colspan="3"><input type="text" style="width:100px;" name="user_name" value="<?=$row[user_name]?>" required="yes" message="성 명" ></td>
					</tr>
					
					<tr>
						<th >연락처</th>
						<td colspan="3"><input type="text" style="width:50;" name="cell1" required="no"  size="3" maxlength="3" value="<?=$cell_arr[0]?>" message="연락처1" is_num="yes" value="">-<input type="text" style="width:50;" name="cell2" required="no" size="4" maxlength="4" value="<?=$cell_arr[1]?>" message="연락처2" is_num="yes" value="">-<input type="text" style="width:50;" name="cell3" required="no" size="4" maxlength="4" value="<?=$cell_arr[2]?>" message="연락처3" is_num="yes" value=""></td>
					</tr>

					<tr>
						<th >이메일</th>
						<td colspan="3">
						<input type="text"  style="width:120px;" name="email1" value="<?=$email_arr[0]?>" required="no" message="이메일계정">&nbsp;
							@&nbsp;<input type="text"  style="width:120px;" name="email2" value="<?=$email_arr[1]?>" required="no" message="이메일주소"> 
							<select name="email_select" id="email_select" onchange='javascript:emailChg();'>
								<option value="">직접입력</option>
								<option value="paran.com"  >paran.com</option>
								<option value="chollian.net" >chollian.net</option>
								<option value="empal.com" >empal.com</option>
								<option value="freechal.com" >freechal.com</option>
								<option value="hotmail.com"	 >hotmail.com</option>
								<option value="lycos.co.kr" >lycos.co.kr</option>
								<option value="korea.com" >korea.com</option>
								<option value="nate.com" >nate.com</option>
								<option value="naver.com" >naver.com</option>
								<option value="netian.com" >netian.com</option>
								<option value="unitel.co.kr" >unitel.co.kr</option>
								<option value="yahoo.co.kr"	 >yahoo.co.kr</option>
								<option value="hanmail.net" >hanmail.net</option>
								<option value="daum.net" >daum.net</option>
							 </select>
						</td>
					</tr>-->

					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_gray">수정취소</a>
						<a href="javascript:go_submit();" class="btn_blue">수정하기</a>
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