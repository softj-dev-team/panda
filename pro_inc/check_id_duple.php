<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$user_id = trim(sqlfilter($_REQUEST['user_id']));
$idx = trim(sqlfilter($_REQUEST['idx']));
$resJSON = array("success"=>"false", "msg"=>"");
$user_id = str_replace("-","",$user_id);
$type = trim(sqlfilter($_REQUEST['type']));

if(!$type){
	$type = "kr";
}

if($type == "AD"){
	//$type_str = "이메일";
	$type_str = "아이디";
} else {
	$type_str = "아이디";
	/*if($type == "kr"){
		$type_str = "아이디";
	} elseif($type == "en"){
		$type_str = "ID";
	}*/
}

if($user_id){
	if(!$idx){
		$query_id = "select idx from member_info where user_id = '".$user_id."' and del_yn='N' and memout_yn != 'Y'";
	} else { 
		$query_id = "select idx from member_info where user_id = '".$user_id."' and idx != '".$idx."' and del_yn='N' and memout_yn != 'Y'";
	}
	$result_id = mysqli_query($gconnet,$query_id);
	
	if(mysqli_num_rows($result_id)==0){
		$resJSON["success"] = "true";
		if($type == "AD"){
			$resJSON["msg"] = '<font style="color:green;">사용 가능한 '.$type_str.' 입니다.</font>';
		} else {
			if($type == "en"){
				$resJSON["msg"] = '<font style="color:green;">Available ID</font>';
			} elseif($type == "kr"){	
				$resJSON["msg"] = '<font style="color:green;">사용 가능한 '.$type_str.' 입니다.</font>';
			}
		}
		//echo $resJSON; exit;
		echo json_encode($resJSON);
	} else {
		if($type == "AD"){
			$resJSON["msg"] = '<font style="color:red;">이미 등록된 '.$type_str.' 입니다.</font>';
		} else {
			if($type == "en"){
				$resJSON["msg"] = '<font style="color:red;">Duplicate ID</font>';
			} elseif($type == "kr"){	
				$resJSON["msg"] = '<font style="color:red;">이미 등록된 '.$type_str.' 입니다.</font>';
			}
		}
		echo json_encode($resJSON);
		exit;
	}
}
?>
