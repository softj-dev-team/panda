<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
 $imgurl = str_replace("data:image/png;base64,","",$_REQUEST['imgurl']);
 $imgurl = base64_decode($imgurl);
 
 $mail_orgnum = $_REQUEST['mail_orgnum'];
	
  $_P_DIR_FILE = $_P_DIR_FILE."estimate_attach_file"."/";
  $_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE."estimate_attach_file"."/";
  
  $org_filename = $mail_orgnum.".png";
  $filename =  $_P_DIR_FILE.$org_filename; 
  $web_filename = $_P_DIR_WEB_FILE.$org_filename; 
 
	//echo $filename;
	//파일로 저장!
	file_put_contents($filename,$imgurl);
  
?>

	<script>
		alert("견적서 보내기에 성공했습니다.");
		parent.parent.self.close();
	</script>