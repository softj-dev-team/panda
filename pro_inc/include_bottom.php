<?
mysqli_close($gconnet);
//echo $_SERVER['REMOTE_ADDR'];

if($_SERVER['REMOTE_ADDR'] == "220.116.84.234" || $_SERVER['REMOTE_ADDR'] == "59.9.33.3"){
	//$show_iframe = true;	
}
?>
<iframe name="_fra" id="_fra" width="800" height="400" style="display:<?=$show_iframe==TRUE?"block":"none"?>;"></iframe>
