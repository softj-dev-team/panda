<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$v_step = trim(sqlfilter($_REQUEST['v_step']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
	
	$cate_level = trim(sqlfilter($_REQUEST['cate_level']));
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$cate_name1 = trim(sqlfilter($_REQUEST['cate_name1']));
	$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
	$cate_name2 = trim(sqlfilter($_REQUEST['cate_name2']));
	$cate_code3 = trim(sqlfilter($_REQUEST['cate_code3']));
	$cate_name3 = trim(sqlfilter($_REQUEST['cate_name3']));
	$cate_code4 = trim(sqlfilter($_REQUEST['cate_code4']));
	$cate_name4 = trim(sqlfilter($_REQUEST['cate_name4']));
	$cate_align = trim(sqlfilter($_REQUEST['cate_align']));

	$head_ok = trim(sqlfilter($_REQUEST['head_ok']));
	$aca_ok = trim(sqlfilter($_REQUEST['aca_ok']));
	$stu_ok = trim(sqlfilter($_REQUEST['stu_ok']));
	$nonmem_ok = trim(sqlfilter($_REQUEST['nonmem_ok']));

	$wdate = date("Y-m-d H:i:s");

	if($cate_level == 1){
		
		$sql_pre1 = "select idx from board_cate where 1=1 and cate_code1 = '".$cate_code1."' "; // 중복 코드 방지
		$result_pre1  = mysqli_query($gconnet,$sql_pre1);

		if(mysqli_num_rows($result_pre1) > 0) {
		?>
			<SCRIPT LANGUAGE="JavaScript">
			<!--	
				alert('입력하신 코드는 이미 등록된 코드입니다.\n\n다시 확인하시고 입력해 주세요.');
			//-->
			</SCRIPT>
		<?
		exit;
		}

		$cate_str = "카테고리";

		//$query = "insert into board_cate (cate_level,cate_code1,cate_name1,cate_code2,cate_name2,cate_code3,cate_name3,cate_code4,cate_name4,cate_align,wdate,head_ok,aca_ok,stu_ok,nonmem_ok) values (N'".$cate_level."',N'".$cate_code1."',N'".$cate_name1."',N'".$cate_code2."',N'".$cate_name2."',N'".$cate_code3."',N'".$cate_name3."',N'".$cate_code4."',N'".$cate_name4."',N'".$cate_align."',N'".$wdate."',N'".$head_ok."',N'".$aca_ok."',N'".$stu_ok."',N'".$nonmem_ok."')";

		$query = " insert into board_cate set "; 
		$query .= " cate_level = '".$cate_level."', ";
		$query .= " cate_code1 = '".$cate_code1."', ";
		$query .= " cate_name1 = '".$cate_name1."', ";
		$query .= " cate_code2 = '".$cate_code2."', ";
		$query .= " cate_name2 = '".$cate_name2."', ";
		$query .= " cate_code3 = '".$cate_code3."', ";
		$query .= " cate_name3 = '".$cate_name3."', ";
		$query .= " cate_code4 = '".$cate_code4."', ";
		$query .= " cate_name4 = '".$cate_name4."', ";
		$query .= " cate_align = '".$cate_align."', ";
		$query .= " wdate = now() ";

		$result = mysqli_query($gconnet,$query);

		?>

		<SCRIPT LANGUAGE="JavaScript">
			<!--
				alert('<?=$cate_str?> 등록이 정상적으로 완료 되었습니다.');
				parent.location.href =  "cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>";
			//-->
		</SCRIPT>
	
	<?

	} elseif($cate_level == 2){
		
		$sql_pre1 = "select idx from board_cate where 1=1 and ( cate_code1 = '".$cate_code2."' or cate_code2 = '".$cate_code2."' ) "; // 중복 코드 방지
		$result_pre1  = mysqli_query($gconnet,$sql_pre1);

		if(mysqli_num_rows($result_pre1) > 0) {
		?>
			<SCRIPT LANGUAGE="JavaScript">
			<!--	
				alert('입력하신 코드는 이미 등록된 코드입니다.\n\n다시 확인하시고 입력해 주세요.');
			//-->
			</SCRIPT>
		<?
		exit;
		}

		$cate_str = "중분류";

		$query = "insert into board_cate (cate_level,cate_code1,cate_name1,cate_code2,cate_name2,cate_code3,cate_name3,cate_code4,cate_name4,cate_align,wdate,head_ok,aca_ok,stu_ok,nonmem_ok) values (N'".$cate_level."',N'".$cate_code1."',N'".$cate_name1."',N'".$cate_code2."',N'".$cate_name2."',N'".$cate_code3."',N'".$cate_name3."',N'".$cate_code4."',N'".$cate_name4."',N'".$cate_align."',N'".$wdate."',N'".$head_ok."',N'".$aca_ok."',N'".$stu_ok."',N'".$nonmem_ok."')";

		$result = mysqli_query($gconnet,$query);

		?>

		<SCRIPT LANGUAGE="JavaScript">
			<!--
				alert('<?=$cate_str?> 등록이 정상적으로 완료 되었습니다.');
				parent.location.href =  "cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1=<?=$cate_code1?>";
			//-->
		</SCRIPT>
	
	<?

	} elseif($cate_level == 3){
		
		$sql_pre1 = "select idx from board_cate where 1=1 and ( cate_code1 = '".$cate_code3."' or cate_code2 = '".$cate_code3."' or cate_code3 = '".$cate_code3."' ) "; // 중복 코드 방지
		$result_pre1  = mysqli_query($gconnet,$sql_pre1);

		if(mysqli_num_rows($result_pre1) > 0) {
		?>
			<SCRIPT LANGUAGE="JavaScript">
			<!--	
				alert('입력하신 코드는 이미 등록된 코드입니다.\n\n다시 확인하시고 입력해 주세요.');
			//-->
			</SCRIPT>
		<?
		exit;
		}

		$cate_str = "소분류";

		$query = "insert into board_cate (cate_level,cate_code1,cate_name1,cate_code2,cate_name2,cate_code3,cate_name3,cate_code4,cate_name4,cate_align,wdate,head_ok,aca_ok,stu_ok,nonmem_ok) values (N'".$cate_level."',N'".$cate_code1."',N'".$cate_name1."',N'".$cate_code2."',N'".$cate_name2."',N'".$cate_code3."',N'".$cate_name3."',N'".$cate_code4."',N'".$cate_name4."',N'".$cate_align."',N'".$wdate."',N'".$head_ok."',N'".$aca_ok."',N'".$stu_ok."',N'".$nonmem_ok."')";

		$result = mysqli_query($gconnet,$query);

		?>

		<SCRIPT LANGUAGE="JavaScript">
			<!--
				alert('<?=$cate_str?> 등록이 정상적으로 완료 되었습니다.');
				parent.location.href =  "cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1=<?=$cate_code1?>&cate_code2=<?=$cate_code2?>";
			//-->
		</SCRIPT>

	<?
	} 
	?>
