<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$idx = trim(sqlfilter($_REQUEST['idx']));
	//$idx = $_SESSION['manage_coinc_idx'];
	$member_gubun = trim(sqlfilter($_REQUEST['member_gubun']));

	$user_pwd = trim(sqlfilter($_REQUEST['user_pwd']));
	if($user_pwd){
		$user_pwd = md5($user_pwd);
	}
	$user_name = trim(sqlfilter($_REQUEST['user_name']));
	$user_level = trim(sqlfilter($_REQUEST['user_level']));
	
	$cell1 = trim(sqlfilter($_REQUEST['cell1']));
	$cell2 = trim(sqlfilter($_REQUEST['cell2']));
	$cell3 = trim(sqlfilter($_REQUEST['cell3']));
	
	$cell = $cell1;
	if($cell2){
	$cell .= "-".$cell2;
	}
	if($cell3){
	$cell .= "-".$cell3;
	}

	$email1 = trim(sqlfilter($_REQUEST['email1']));
	$email2 = trim(sqlfilter($_REQUEST['email2']));

	$email = $email1;
	if($email2){
	$email .= "@".$email2;
	}

	$ma_idx = trim(sqlfilter($_REQUEST['ma_idx']));
	
	if($level != "3"){
		$ma_idx = "";
	}
	
	$query = " update member_info set "; 
	if($_REQUEST['user_pwd']){
		$query .= " user_pwd = '".$user_pwd."', ";
	}
	$query .= " user_name = '".$user_name."', ";
	/*$query .= " user_level = '".$user_level."', ";
	$query .= " cell = '".$cell."', ";
	$query .= " email = '".$email."' ";*/
	$query .= " mdate = now() ";
	$query .= " where idx = '".$idx."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	$admin_idx = $idx;
	
	if($member_gubun == "SUB"){ // 운영자일 경우에만 시작 

		$sql_file = "select * from admin_account_auth where 1 and admin_idx='".$admin_idx."'";
		$query_file = mysqli_query($gconnet,$sql_file);
		$cnt_file = mysqli_num_rows($query_file);

		if($cnt_file < 5){
			$cnt_file = 5;
		}

		for($file_i=0; $file_i<$cnt_file; $file_i++){
			
			$idx = trim(sqlfilter($_REQUEST['file_idx_'.$file_i])); 
			$del_yn = trim(sqlfilter($_REQUEST['del_org_'.$file_i])); 
			$sido = trim(sqlfilter($_REQUEST['sido_'.$file_i])); 
			$gugun = trim(sqlfilter($_REQUEST['gugun_'.$file_i])); 
			
			if($sido){
				if($idx){
					if($del_yn == "Y"){
						$query_sub = "delete from admin_account_auth where 1 and idx='".$idx."'"; 
						$result_sub = mysqli_query($gconnet,$query_sub);
					} else {
						$query_sub = "update admin_account_auth set"; 
						$query_sub .= " sido = '".$sido."', ";
						$query_sub .= " gugun = '".$gugun."', ";
						$query_sub .= " wdate = now() ";
						$query_sub .= " where 1 and idx='".$idx."'";
						$result_sub = mysqli_query($gconnet,$query_sub);
					}
				} else {
					$query_sub = "insert into admin_account_auth set"; 
					$query_sub .= " admin_idx = '".$admin_idx."', ";
					$query_sub .= " sido = '".$sido."', ";
					$query_sub .= " gugun = '".$gugun."', ";
					$query_sub .= " wdate = now() ";
					$result_sub = mysqli_query($gconnet,$query_sub);
				}
			}

		}

	} // 운영자일 경우에만 종료 

	if($result){
	?>
	<script type="text/javascript">
	<!--
	alert('수정이 정상적으로 완료 되었습니다.');
	//alert('비밀번호 변경이 정상적으로 완료 되었습니다.');
	parent.location.href =  "adminm_view.php?idx=<?=$admin_idx?>&<?=$total_param?>";
	//parent.location.href =  "adminm_modify.php?<?=$total_param?>";
	//-->
	</script>
	<?}else{?>
	<script type="text/javascript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</script>
	<?}?>
