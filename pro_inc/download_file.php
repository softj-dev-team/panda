<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
################  Function  ################  
	
//다운로드 경로 체크 함수
//$dn_dir - 다운로드 디렉토리 경로(path)
//$fname - 다운로드 파일명
//리턴 - true:다운로드 파일 경로, false: "error"

function checkpath($dn_dir,$fname) {
	$dn_dir = str_replace("\\","/",$dn_dir);
	//다운로드 경로에 공격 문자 필터링
	if (strpos($dn_dir,"\.\./") == true) {
		print "허용하지 않는 입력값입니다.";
		return "error";
	}
	
	$dn_file = $dn_dir."/".$fname;
	//$fname에서 파일명만 분리 - 파일명에 공격 위험성 문자 필터링
	//$filename = substr($dn_file, strrpos($dn_file, '/') + 1);
	$filename=basename($fname);
	//분리한 파일명과 절대 경로를 재구성
	$strfname = $dn_dir."/".$filename;
	//사용자 입력값과 재구성한 입력값을 비교하여 공격 위험성이 존재하는지 확인
	if ($strfname == $dn_file)
		return $strfname;
	else
		return "error";
}

#############################################
	
	$fileName = trim(sqlfilter($_GET['nm']));
	$orgfileName = trim(sqlfilter($_GET['on']));
	
	//echo $orgfileName; exit;

	$orgfileName = str_replace(",","_",$orgfileName);

	$orgfileName= iconv("UTF-8","EUC-KR",$orgfileName);//다운로드시 한글 깨짐 현상 해결. (MSIE)
	$dir = sqlfilter($_GET['dir']);
	$dir = $_P_DIR_FILE.$dir;

	//echo $dir." : ".$fileName."<br><br>";

	$completeFilePath = checkpath($dir, $fileName);

	//echo $completeFilePath; exit;

	$size = filesize($completeFilePath);
	header("Content-Type: application/ms-x-download"); 
	header("Content-Type: application/octet-stream"); 
	header('Content-Type: text/html; charset=utf-8'); 
	header("Content-Length: ".$size); 
	header("Content-Disposition: attachment; filename=".$orgfileName); 
	header("Content-Transfer-Encoding: binary");  

	$fh = fopen($completeFilePath, "r"); 
	fpassthru($fh);
	exit; 
?> 