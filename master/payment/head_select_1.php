<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
check_admin_frame(); // 관리자 로그인여부 확인

$fm = trim(sqlfilter($_REQUEST['fm']));
$fname = trim(sqlfilter($_REQUEST['fname']));
$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
?>
<script> 
<? 
	$query = "select S.Server,S.ServerNm FROM ".NS."GCServerM AS S JOIN ".NS."GCUserM AS SU ON (S.Server = SU.Server) LEFT OUTER JOIN ( SELECT D.DHCd, D.DHNm, SD.Server FROM ".NS."GCDeptHead AS D JOIN ".NS."GCServerDeptHead AS SD ON (D.DHCd = SD.DHCd) WHERE SD.isUse='Y' ) AS DF ON (SU.Server = DF.Server) WHERE SU.UserId='000001' and DF.DHCd = '".$cate_code1."' "; 
	$result = mysqli_query($GLOBALS['gconnet'],$query);
 ?> 
 parent.<?=$fm?>.<?=$fname?>.length = <?=mysqli_num_rows($result)?>+1; 
 parent.<?=$fm?>.<?=$fname?>.options[0].text = '학원별 검색'; 
 parent.<?=$fm?>.<?=$fname?>.options[0].value = ''; 

<?      
	for($i=0; $i<mysqli_num_rows($result); $i++){
		$row = mysqli_fetch_array($result);
?> 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+1].text = '<?=$row[ServerNm]?>'; 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+1].value = '<?=$row[Server]?>'; 
<?}?>
</script> 
