<?php
//$dir = "../../../../..";
//include_once("$dir/common.php");
// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

//$ym = date('ym', TB_SERVER_TIME);

$data_dir = $_SERVER["DOCUMENT_ROOT"].'/smarteditor2/upload/';
$data_url = '/smarteditor2/upload/';

@mkdir($data_dir, 0777);
@chmod($data_dir, 0777);

// SUCCESSFUL
if(bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	
	$filename_ext = strtolower(array_pop(explode('.',$name)));
	
	if (!preg_match("/(jpe?g|gif|bmp|png)$/i", $filename_ext)) {
		$url .= '&errstr='.$name;
	} else {
		
        $file_name = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])).'_'.get_microtime().".".$filename_ext;
		$save_dir = sprintf('%s/%s', $data_dir, $file_name);
        $save_url = sprintf('%s/%s', $data_url, $file_name);
		
		@move_uploaded_file($tmp_name, $save_dir);
		
		$url .= "&bNewLine=true";
		$url .= "&sFileName=".$name;
		$url .= "&sFileURL=".$save_url;
	}
}
// FAILED
else {
	$url .= '&errstr=error';
}
	
header('Location: '. $url);
?>