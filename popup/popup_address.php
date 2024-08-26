<? 
	include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 
	include $_SERVER['DOCUMENT_ROOT']."/pro_inc/check_login_popup.php"; // 로그인 체크

	$member_idx = $_SESSION['member_ganaf_idx'];

	$where .= " and member_idx='".$member_idx."'";
	$order_by = " order by idx asc ";

	$query = "select * from member_address_set where 1 ".$where.$order_by;
	$result = mysqli_query($gconnet,$query);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>배송지 정보</title>
 <link rel="stylesheet" href="../css/style.css" type="text/css">
 <script type="text/javascript" src="../js/common_js.js"></script>
<META HTTP-EQUIV="imagetoolbar" CONTENT="no">
<style type="text/css">

.style1 {
	color:#333333; 
	font-size:14px;
	font-weight:bold;
}
.normal {
	color:#333333; 
	font-size:12px;
}
body {
	margin-left: 5px;
	margin-top: 5px;
	background-color: #F6F6F6;
}

table{
	width:100%;
	font-size:12px;
	border-collapse: collapse;
	border-right:1px solid #dcdee2;
    text-align: center;
    line-height: 1.5;
	font-size:12px;
}
table th {
	background:#4a4a4a;
	color:#fff;
	border-top:1px solid #222;
	border-right:1px solid #222;
	border-bottom:1px solid #222;
	height:25px;
}
table td {
	border:1px solid #dcdee2;
	font-size:11px;
	height:25px;
}

</style>

<script type="text/javascript">
<!--
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}
//-->
</script>

</head>

<body>
	<form method="post" action="popup_address_action.php" name="frm" id="frm" target="_self">
	<div style="display:none;"><input type="radio" name="addr_idx" id="addr_idx"/></div>
	<table>
		<colgroup>
			<col width="5%">
			<col width="15%">
			<col width="15%">
			<col width="15%">
			<col width="15%">
			<col width="35%">
		</colgroup>
		<tr>
			<th>선택</th>
			<th>배송지</th>
			<th>수령인</th>
			<th>휴대전화</th>
			<th>일반전화</th>
			<th>주소</th>
		</tr>
	<?
		for ($i=0; $i<mysqli_num_rows($result); $i++){
			$row = mysqli_fetch_array($result);
			$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
	?>
		<tr>
			<td><input type="radio" name="addr_idx" id="addr_idx" value="<?=$row[idx]?>" required="yes" message="배송지" onclick="go_submit();"/></td>
			<td class="destination">
				<?=$row[dev_name]?>
			</td>
			<td><?=$row[user_name]?></td>
			<td><?=$row[cell]?></td>
			<td><?=$row[tel]?></td>
			<td>[<?=$row[post]?>]&nbsp;<?=$row[addr1]?>&nbsp;<?=$row[addr2]?></td>
		</tr>
	<?}?>
	</table>
	</form>
</body>