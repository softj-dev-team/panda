<?php
 	$sFileInfo = '';
	$headers = array(); 
	$time = time();
	foreach ($_SERVER as $k => $v){   
  	
		if(substr($k, 0, 9) == "HTTP_FILE"){ 
			$k = substr(strtolower($k), 5); 
			$headers[$k] = $v; 
		} 
	}
	
	$mend = explode(" ", microtime());//마이크로타임 뒷자리를 구한다.중복방지로 붙이려고..				
	//파일 확장자 가져오고 파일명 변경;
	$path = pathinfo($headers['file_name']);
	$ext = strtolower($path['extension']);	
	$name = $mend[1].".".strtoupper($ext);

	$file = new stdClass; 
	//$file->name = rawurldecode($headers['file_name']);	
	$file->name = $mend[1].".".strtoupper($ext);
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input"); 
	
	$newPath = '../../upload/'.iconv("utf-8", "cp949", $file->name);
	
	if(file_put_contents($newPath, $file->content)) {
		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$file->name;
		$sFileInfo .= "&sFileURL=/smarteditor2/upload/".$file->name;
	}
	echo $sFileInfo;
 ?>
