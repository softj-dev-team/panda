<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드

$pay_str = "캐릭터포유 테이블내역서";

$query = "SELECT TABLE_NAME,TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'jungy0311'";
//echo "<br><br>쿼리 = ".$query."<br><Br>"; exit;
$result = mysqli_query($gconnet,$query);

$pay_str =  iconv("UTF-8","EUC-KR",$pay_str);

$filename = $pay_str."_".date("Y-m-d").".xls";
//$filename = iconv("UTF-8","EUC-KR",$filename);
//if($_SERVER['REMOTE_ADDR'] != "121.167.147.150"){	
Header( "Content-type: application/vnd.ms-excel" ); 
Header( "Content-Disposition: attachment; filename=".$filename ); 
Header( "Content-Description: PHP4 Generated Data" );
//}
?>

		<head>
			<meta http-equiv=Content-Type content="text/html; charset=ks_c_5601-1987">
		</head>
		
		<table width="100%">
		<?
			for ($ikm=0; $ikm<mysqli_num_rows($result); $ikm++){
				$row = mysqli_fetch_array($result);
		?>
		<tr>
			<td>
				<table border width="100%">
				<tr align="center">
					<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","테이블 이름")?></strong></font></td>
					<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","테이블 설명")?></strong></font></td>
				</tr>
				<tr align="center">
					<td><?=iconv("UTF-8","EUC-KR",$row['TABLE_NAME'])?></td>
					<td><?=iconv("UTF-8","EUC-KR",$row['TABLE_COMMENT'])?></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td>
				<table border width="100%">
					<tr align="center">
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","번호")?></strong></font></td>
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","필드명")?></strong></font></td>
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","데이터 타입")?></strong></font></td>
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","데이터 길이")?></strong></font></td>
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","KEY 여부")?></strong></font></td>
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","NULL 여부")?></strong></font></td>
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","자동증가 여부")?></strong></font></td>
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","기본값")?></strong></font></td>
						<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","설명")?></strong></font></td>
					</tr>
					<?
						$query2 = "SELECT ORDINAL_POSITION '필드순번',COLUMN_NAME '필드명',DATA_TYPE '데이터 TYPE',COLUMN_TYPE '데이터 LENGTH',COLUMN_KEY 'KEY',IS_NULLABLE 'NULL값여부',EXTRA '자동여부',COLUMN_DEFAULT '디폴트값',COLUMN_COMMENT '필드설명' FROM `information_schema`.COLUMNS WHERE TABLE_SCHEMA = 'jungy0311' AND TABLE_NAME = '".$row['TABLE_NAME']."' ORDER BY TABLE_NAME, ORDINAL_POSITION ";
						$result2 = mysqli_query($gconnet,$query2);

						for ($ikm2=0; $ikm2<mysqli_num_rows($result2); $ikm2++){
							$row2 = mysqli_fetch_array($result2);
					?>
						<tr align="center">
							<td><?=iconv("UTF-8","EUC-KR",$row2['필드순번'])?></td>
							<td><?=iconv("UTF-8","EUC-KR",$row2['필드명'])?></td>
							<td><?=iconv("UTF-8","EUC-KR",$row2['데이터 TYPE'])?></td>
							<td><?=iconv("UTF-8","EUC-KR",$row2['데이터 LENGTH'])?></td>
							<td><?=iconv("UTF-8","EUC-KR",$row2['KEY'])?></td>
							<td><?=iconv("UTF-8","EUC-KR",$row2['NULL값여부'])?></td>
							<td><?=iconv("UTF-8","EUC-KR",$row2['자동여부'])?></td>
							<td><?=iconv("UTF-8","EUC-KR",$row2['디폴트값'])?></td>
							<td><?=iconv("UTF-8","EUC-KR",$row2['필드설명'])?></td>
						</tr>
					<?}?>
				</table>
			</td>
		<tr>
		<tr><td></td></tr>
		<?}?>	
		</table>