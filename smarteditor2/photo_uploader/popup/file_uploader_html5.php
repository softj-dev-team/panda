<?php
/*$dir = "../../../../..";
include_once("$dir/common.php");

$ym = date('ym', TB_SERVER_TIME);

$data_dir = TB_DATA_PATH.'/editor/'.$ym;
$data_url = TB_DATA_URL.'/editor/'.$ym;

@mkdir($data_dir, TB_DIR_PERMISSION);
@chmod($data_dir, TB_DIR_PERMISSION);*/

$data_dir = $_SERVER["DOCUMENT_ROOT"].'/smarteditor2/upload/';
$data_url = '/smarteditor2/upload/';

@mkdir($data_dir, 0777);
@chmod($data_dir, 0777);

 	$sFileInfo = '';
	$headers = array();
	 
	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		} 
	}
	
	$file = new stdClass;
	//$file->name = str_replace("\0", "", rawurldecode($headers['file_name']));
    $file->name = str_replace("\0", "", rawurldecode($headers['file_name']));
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");
	
	$filename_ext = strtolower(array_pop(explode('.',$file->name)));

    if (!preg_match("/(jpe?g|gif|bmp|png)$/i", $filename_ext)) {
        echo "NOTALLOW_".$file->name;
        exit;
    }
    
	//$file_name = iconv("utf-8", "cp949", $file->name);
    $file_name = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])).'_'.get_microtime().".".$filename_ext;
    $newPath = $data_dir."/".$file_name;
    $save_url = sprintf('%s/%s', $data_url, $file_name);

    if(file_put_contents($newPath, $file->content)) {
        $sFileInfo .= "&bNewLine=true";
        $sFileInfo .= "&sFileName=".$file->name;
        $sFileInfo .= "&sFileURL=".$save_url;
    }
    
    echo $sFileInfo;
?>