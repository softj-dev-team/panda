<?php
//기본 리다이렉트
echo $_REQUEST["htImageInfo"];

$url = $_REQUEST["callback"] .'?callback_func='. $_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);
if (bSuccessUpload) { //성공 시 파일 사이즈와 URL 전송
	$mend = explode(" ", microtime());//마이크로타임 뒷자리를 구한다.중복방지로 붙이려고..				
	//파일 확장자 가져오고 파일명 변경;
	$path = pathinfo($_FILES['Filedata']['name']);
	$ext = strtolower($path['extension']);	
	

	$tmp_name = $_FILES['Filedata']['tmp_name'];
	//$name = $_FILES['Filedata']['name'];
	$name = $mend[1].".".strtoupper($ext);
	$new_path = "../../upload/".urlencode($name);

	@move_uploaded_file($tmp_name, $new_path);
	$url .= "&bNewLine=true";
	$url .= "&sFileName=".urlencode(urlencode($name));
	//$url .= "&size=". $_FILES['Filedata']['size'];
	//아래 URL을 변경하시면 됩니다.
	$url .= "&sFileURL=http://115.68.114.114/smarteditor2/upload/".urlencode(urlencode($name));
} else { //실패시 errstr=error 전송
	$url .= '&errstr=error';
}
header('Location: '. $url);
?>