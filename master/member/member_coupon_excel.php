<? session_start();
include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/db_conn.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/function_query.php"; ?><?// include $_SERVER["DOCUMENT_ROOT"]."/plsone_master/inc/check_login2_sub.php"; ?>
<?
if($_SESSION['admin_supma_id'] != "admin"){ 
?>
<script type="text/javascript">
<!--
history.back();
//-->
</script>
<?
exit;
}

$field = trim($_REQUEST['field']);
$keyword = urldecode($_REQUEST['keyword']);

$pageNo = 1;

$query_cnt = "select a.level_name,b.user_id,b.username,c.* from member_level_set a INNER JOIN member_info b ON a.level_code = b.user_level INNER JOIN member_coupon c ON b.idx = c.member_idx where 1=1 ";
$num = joinlistcount($query_cnt, $field, $keyword, $where);

$pageScale = $num;

$query = "select a.level_name,b.user_id,b.username,c.* from member_level_set a INNER JOIN member_info b ON a.level_code = b.user_level INNER JOIN member_coupon c ON b.idx = c.member_idx where 1=1 ";

$orderBy = " c.idx desc";

$result = joinlist($query, $orderBy, $field, $keyword, $pageNo, $pageScale, $where);

$display = $num - ($pageNo-1)*$pageScale;
	
	$filename = "���۸���_ȸ������������Ʈ"."(".date("Y-m-d").")".".xls";
	//$filename = iconv("UTF-8","EUC-KR",$filename);
	
	Header( "Content-type: application/vnd.ms-excel" ); 
	Header( "Content-Disposition: attachment; filename=".$filename ); 
	Header( "Content-Description: PHP4 Generated Data" ); 
	

	//echo "����? ".$filename;
	
?>
	<head>
			<meta http-equiv=Content-Type content="text/html; charset=ks_c_5601-1987">
		</head>
		<table border width="100%">
		<!--
		<tr bgcolor=#CCCCCC align="center">
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","��ȣ")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","���̵�")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","����")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","ȸ�����")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","�߱޹��� ������")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","���� �׸鰡")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","���� ������")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","���� ������")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","����")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","���� �����")?></strong></font></td>
		</tr>
		-->
		<tr bgcolor=#CCCCCC align="center">
			<td><font color="#669900"><strong>��ȣ</strong></font></td>
			<td><font color="#669900"><strong>���̵�</strong></font></td>
			<td><font color="#669900"><strong>�� ��</strong></font></td>
			<td><font color="#669900"><strong>ȸ�����</strong></font></td>
			<td><font color="#669900"><strong>�߱޹��� ������</strong></font></td>
			<td><font color="#669900"><strong>���� �׸鰡</strong></font></td>
			<td><font color="#669900"><strong>���� ������</strong></font></td>
			<td><font color="#669900"><strong>���� ������</strong></font></td>
			<td><font color="#669900"><strong>����</strong></font></td>
			<td><font color="#669900"><strong>���� �����</strong></font></td>
		</tr>
		<? if(mysqli_num_rows($result)==0) { ?>
				<tr>
					<td colspan="53" height="40"><strong><?=iconv("UTF-8","EUC-KR","������ �߱޹��� ȸ���� �����ϴ�.")?></strong></td>
				</tr>
			<? } ?>
			<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);

					if($row[coupon_sect] == "A"){
						$coupon_sect = "�߱�";
					} elseif($row[coupon_sect] == "M"){
						$coupon_sect = "���";
					} elseif($row[coupon_sect] == "C"){
						$coupon_sect = "�Ⱓ����";
					}
			?>
			<tr bgcolor=#ffffff align="center" height="22">
			<td><?=$display--?></td>
			<td><?=$row[user_id]?></td>
			<td><?=iconv("UTF-8","EUC-KR",$row[username])?></td>
			<td><?=iconv("UTF-8","EUC-KR",$row[level_name])?></td>
			<td><?=iconv("UTF-8","EUC-KR",$row[coupon_title])?></td>
			<td><?=number_format($row[coupon_price],0)?></td>
			<td><?=$row[wdate]?></td>
			<td><?=$row[expire_date]?></td>
			<td><?=iconv("UTF-8","EUC-KR",$coupon_sect)?></td>
			<td><?=$row[mdate]?></td>
			</tr>
			<? } ?>
		</table>
