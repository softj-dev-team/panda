<?
mysqli_close($gconnet);
//echo $_SERVER['REMOTE_ADDR'];  183.96.82.136
//echo "현재 아이피 = ".$_SERVER['REMOTE_ADDR'];

if($_SERVER['REMOTE_ADDR'] == "220.116.84.234" || $_SERVER['REMOTE_ADDR'] == "59.9.33.3"){
	$show_iframe = true;	
} 
?>
<iframe name="_fra_admin" width="800" height="500" style="display:<?=$show_iframe==TRUE?"":"none"?>"></iframe>
<iframe name="_fra_admin2" width="800" height="500" style="display:<?=$show_iframe==TRUE?"":"none"?>"></iframe>
<div id="CalendarLayer" style="display:none; width:172px; height:250px; z-index:100;">
	<iframe name="CalendarFrame" src="/pro_inc/include_calendar.php" width="172" height="250" border="0" frameborder="0" scrolling="no"></iframe>
</div>
