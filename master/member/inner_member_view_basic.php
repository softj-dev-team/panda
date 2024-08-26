<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	/*echo "<xmp>";
		print_r($_REQUEST);
	echo "</xmp>";*/

	/*echo "<xmp>";
		print_r($_FILES);
	echo "</xmp>";*/
	//exit;

	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));

	$sql = "SELECT *,(select panalty_type from member_panalty_info where 1 and is_del='N' and member_idx=member_info.idx order by idx desc limit 0,1) as panalty_type FROM member_info where 1 and idx = '".$member_idx."' and del_yn='N'";
	$query = mysqli_query($gconnet,$sql);

	$row = mysqli_fetch_array($query);
?>
		<div class="list_tit" style="margin-top:10px;">
			<h3>개인정보 보기</h3>
		</div>
	
		<div class="write">
			<table>
				<caption>회원 상세보기</caption>
				<colgroup>
					<col style="width:15%">
					<col style="width:35%">
					<col style="width:15%">
					<col style="width:35%">
				</colgroup>
				<tr>
					<th scope="row">개인정보</th>
					<td colspan="3">
					<?if($row['login_ok'] == "Y"){?>
						정상활동 <a href="javascript:set_panalty_open();" class="btn_red">패널티 부여</a>
					<?}else{?>
						패널티 부여 / <?=$arr_panalty_type[$row['panalty_type']]?> &nbsp;&nbsp; <a href="javascript:set_panalty_history();" class="btn_green">패널티 이력</a> &nbsp; <a href="javascript:set_panalty_clear_open();" class="btn_blue">패널티 해제</a>
					<?}?>
					</td>
				</tr>
				<tr>
					<th scope="row">가입일</th>
					<td colspan="3">
						<?=$row['wdate']?>
					</td>
				</tr>
				<tr>
					<th scope="row">메일주소</th>
					<td colspan="3">
						<?=$row['email']?>
					</td>
				</tr>
				<tr>
					<th scope="row">닉네임</th>
					<td colspan="3">
						<?=$row['user_nick']?>
					</td>
				</tr>
				<tr>
					<th scope="row">정산계좌</th>
					<td colspan="3">
						<a href="javascript:set_member_bank();" class="btn_blue">정산계좌 보기</a>
					</td>
				</tr>
			</table>
		</div>