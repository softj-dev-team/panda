<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
	
	$section = trim(sqlfilter($_REQUEST['section']));
	$pro_name = trim(sqlfilter($_REQUEST['pro_name']));
	$pro_idx = trim(sqlfilter($_REQUEST['pro_idx']));
	$main_sect = trim(sqlfilter($_REQUEST['main_sect']));
	$align = trim(sqlfilter($_REQUEST['align']));
	$main_title = trim(sqlfilter($_REQUEST['main_title']));
	$main_memo = trim(sqlfilter($_REQUEST['main_memo']));
	$view_ok = trim(sqlfilter($_REQUEST['view_ok']));
	$link_sect = trim(sqlfilter($_REQUEST['link_sect']));
	$link_target = trim(sqlfilter($_REQUEST['link_target']));
	if($section == "movie"){
		$link_url = trim(sqlfilter($_REQUEST['m_link_url']));
	} elseif($section == "img"){
		$link_url = trim(sqlfilter($_REQUEST['link_url']));
	} 
		
	if($section == "movie"){
		if(!$link_url){
			error_frame("유튜브 재생코드를 입력하세요.");
		}
	} elseif($section == "img"){
		if(!$link_sect){
			error_frame("링크유형을 선택하세요.");
		}
		if($link_sect == "U"){
			if(!$link_url){
				error_frame("링크 URL 주소를 입력하세요.");
			}
			if(!$link_target){
				error_frame("링크시 연결할 페이지 새창/현재창을 선택하세요.");
			}
		}
		if ($_FILES['file1']['size'] == 0){
			error_frame("배너로 등록할 이미지를 입력하세요.");
		}	
	}
	
	if($link_sect == "N"){
		$link_target = "";
		$link_url = "";
	}	

	$bbs = "main_banner";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

	if ($_FILES['file1']['size']>0){
		$file_o = $_FILES['file1']['name']; 
		
		/*if($s_sect1 == "pc"){
			if($main_sect == "메인화면 탑"){
				$i_width = "1100";
				$i_height = "";
			} elseif($main_sect == "메인화면 상단롤링"){
				$i_width = "1920";
				$i_height = "";
			} elseif($main_sect == "메인화면 중단롤링"){
				$i_width = "1920";
				$i_height = "";
			} 
		} elseif($s_sect1 == "mobile"){
			if($main_sect == "좌측메뉴 상단 롤링"){
				$i_width = "503";
				$i_height = "159";
			} elseif($main_sect == "메인화면 중앙롤링"){
				$i_width = "520";
				$i_height = "570";
			} elseif($main_sect == "메인하단 롤링"){
				$i_width = "503";
				$i_height = "159";
			}
		}*/
		$i_width = "1920";
		$i_height = "899";
		$file_c = uploadFileThumb_1($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2);
	}
	
	$wdate = date("Y-m-d H:i:s");
	
	/*$query = "insert into mainban_info (pro_idx,main_sect,file_o,file_c,main_memo,view_ok,align,wdate,link_sect,link_target,link_url) values  (N'".$pro_idx."',N'".$main_sect."',N'".$file_o."',N'".$file_c."',N'".$main_memo."',N'".$view_ok."',N'".$align."',N'".$wdate."',N'".$link_sect."',N'".$link_target."',N'".$link_url."')";*/

	$query = " insert into mainban_info set "; 
	$query .= " section = '".$section."', ";
	$query .= " banner_type = '".$s_sect1."', ";
	$query .= " main_sect = '".$main_sect."', ";
	//$query .= " pro_idx = '".$pro_idx."', ";
	$query .= " file_o = '".$file_o."', ";
	$query .= " file_c = '".$file_c."', ";
	$query .= " main_title = '".$main_title."', ";
	$query .= " main_memo = '".$main_memo."', ";
	$query .= " view_ok = '".$view_ok."', ";
	$query .= " align = '".$align."', ";
	$query .= " link_sect = '".$link_sect."', ";
	$query .= " link_target = '".$link_target."', ";
	$query .= " link_url = '".$link_url."', ";
	$query .= " wdate = now() ";	
	$result = mysqli_query($gconnet,$query);
	
	//echo $query;
	
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('배너 등록이 정상적으로 완료 되었습니다.');
	parent.location.href =  "mainban_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('배너 등록중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>