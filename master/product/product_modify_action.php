<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	/*echo "<xmp>";
		print_r($_REQUEST);
	echo "</xmp>";*/
	
	$_FILES['file_artwork'] = reArrayFiles($_FILES['file_artwork']);
	$_FILES['file_preview'] = reArrayFiles($_FILES['file_preview']);

	/*echo "<xmp>";
		print_r($_FILES);
	echo "</xmp>";*/

	//exit;

	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$sale_idx = trim(sqlfilter($_REQUEST['sale_idx']));
	
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
	$product_title = trim(sqlfilter($_REQUEST['product_title']));
	$product_type = trim(sqlfilter($_REQUEST['product_type']));
	$product_desc = trim(sqlfilter($_REQUEST['product_desc']));
	$product_tag = trim($_REQUEST['product_tag']);
	$product_auth = trim(sqlfilter($_REQUEST['product_auth']));

	$query = "update product_info set";
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " cate_code1 = '".$cate_code1."', ";
	$query .= " cate_code2 = '".$cate_code2."', ";
	$query .= " product_title = '".$product_title."', ";
	$query .= " product_type = '".$product_type."', ";
	$query .= " product_desc = '".$product_desc."', ";
	$query .= " product_tag = '".$product_tag."', ";	
	$query .= " product_auth = '".$product_auth."', ";
	$query .= " mdate = now() ";
	$query .= " where 1 and idx='".$idx."'";
	$result = mysqli_query($gconnet,$query);

	$product_idx = $idx;

	$bbs = "product";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";

	################### Artwork 등록시작 ###############
	$old_file_art_list = $_REQUEST['old_file_art_list'];

	$sql_file = "select idx,file_chg from board_file where 1 and board_tbname='product_info' and board_code='artwork' and board_idx='".$idx."' order by idx asc";
	$query_file = mysqli_query($gconnet,$sql_file);
	for($i_file=0; $i_file<mysqli_num_rows($query_file); $i_file++){
		$row_file = mysqli_fetch_array($query_file);
		if(in_array($row_file['file_chg'], $old_file_art_list)){ 
		} else { 
			$sql_file_del = "delete from board_file where 1 and idx='".$row_file['idx']."'";
			$query_file_del = mysqli_query($gconnet,$sql_file_del);
		}
	}

	$board_tbname = "product_info";
	$board_code = "artwork";
	$up_file_art_list = $_REQUEST['up_file_art_list'];
	//echo "file_artwork cnt = ".sizeof($_FILES['file_artwork'])."<br><br>";

	for($file_artwork_i=0; $file_artwork_i<sizeof($_FILES['file_artwork']); $file_artwork_i++){
		if ($_FILES['file_artwork'][$file_artwork_i]['size']>0){
			//echo "artwork 파일명 = ".$_FILES['file_artwork'][$file_artwork_i]['name']."<br>";
			//echo "artwork 파일사이즈 = ".$_FILES['file_artwork'][$file_artwork_i]['size']."<br>";
			if(in_array($_FILES['file_artwork'][$file_artwork_i]['name'], $up_file_art_list)){
				//echo "Artwork 업로드 대상 = Y <br><br>";

				$file_o = $_FILES['file_artwork'][$file_artwork_i]['name']; 
				$file_c = uploadFile($_FILES, "file_artwork", $_FILES['file_artwork'][$file_artwork_i], $_P_DIR_FILE,"","Y"); // 파일 업로드후 변형된 파일이름 리턴.
				$file_type = $_FILES['file_artwork'][$file_artwork_i]['type']; 
				$file_size = $_FILES['file_artwork'][$file_artwork_i]['size']; 
				
				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$product_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."', ";
				$query_file .= " file_type = '".$file_type."', ";
				$query_file .= " file_size = '".$file_size."' ";
				$result_file = mysqli_query($gconnet,$query_file);

			} else {
				//echo "Artwork 업로드 대상 = N <br><br>";
			}
		}
	}
	################### Artwork 등록종료 ###############
	//exit;

	################### Preview 등록시작 ###############
	$old_file_prv_list = $_REQUEST['old_file_prv_list'];

	$sql_file = "select idx,file_chg from board_file where 1 and board_tbname='product_info' and board_code='preview' and board_idx='".$idx."' order by idx asc";
	$query_file = mysqli_query($gconnet,$sql_file);
	for($i_file=0; $i_file<mysqli_num_rows($query_file); $i_file++){
		$row_file = mysqli_fetch_array($query_file);
		if(in_array($row_file['file_chg'], $old_file_prv_list)){ 
		} else { 
			$sql_file_del = "delete from board_file where 1 and idx='".$row_file['idx']."'";
			$query_file_del = mysqli_query($gconnet,$sql_file_del);
		}
	}

	$board_tbname = "product_info";
	$board_code = "preview";
	$up_file_preview_list = $_REQUEST['up_file_preview_list'];
	//echo "file_preview cnt = ".sizeof($_FILES['file_preview'])."<br><br>";
	for($file_preview_i=0; $file_preview_i<sizeof($_FILES['file_preview']); $file_preview_i++){
		if ($_FILES['file_preview'][$file_preview_i]['size']>0){
			//echo "preview 파일명 = ".$_FILES['file_preview'][$file_preview_i]['name']."<br>";
			//echo "preview 파일사이즈 = ".$_FILES['file_preview'][$file_preview_i]['size']."<br>";
			if(in_array($_FILES['file_preview'][$file_preview_i]['name'], $up_file_preview_list)){
				//echo "preview 업로드 대상 = Y <br><br>";
				$file_type = $_FILES['file_preview'][$file_preview_i]['type']; 
				$file_size = $_FILES['file_preview'][$file_preview_i]['size']; 

				if(substr($file_type,0,5) == "image"){
					$file_o = $_FILES['file_preview'][$file_preview_i]['name']; 
					$i_width = "280";
					$i_height = "280";
					$i_width2 = "910";
					$i_height2 = "640";
					$file_c = uploadFileThumb_1($_FILES,"file_preview",$_FILES['file_preview'][$file_preview_i],$_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,"","","","Y");
				} else {
					$file_o = $_FILES['file_preview'][$file_preview_i]['name']; 
					$file_c = uploadFile($_FILES,"file_preview",$_FILES['file_preview'][$file_preview_i],$_P_DIR_FILE,"","Y"); 
				}

				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$product_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."', ";
				$query_file .= " file_type = '".$file_type."', ";
				$query_file .= " file_size = '".$file_size."' ";
				$result_file = mysqli_query($gconnet,$query_file);

			} else {
				//echo "preview 업로드 대상 = N <br><br>";
			}
		}
	}
	################### Preview 등록종료 ###############

	$json_url ="https://quotation-api-cdn.dunamu.com/v1/forex/recent?codes=FRX.KRWUSD";
	$param = json_decode(file_get_contents($json_url), true);
	//echo "basePrice = ".$param[0]['basePrice']."<br>"; // 기본 환율 

	################### 작품 판매정보 등록시작 ###############
	
	$sale_auth_yn = trim(sqlfilter($_REQUEST['sale_auth_yn']));
	$sale_method = trim(sqlfilter($_REQUEST['sale_method']));
	$sale_price_std = trim(sqlfilter($_REQUEST['sale_price']));
	$sale_price_auc = trim(sqlfilter($_REQUEST['sale_price_auc']));
	if($sale_method == "1"){
		$sale_price = $sale_price_std;
	} else {
		$sale_price = $sale_price_auc;
	}
	$sale_cnt = trim(sqlfilter($_REQUEST['sale_cnt']));
	$auc_sdate = trim(sqlfilter($_REQUEST['auc_sdate']));
	$auc_edate = trim(sqlfilter($_REQUEST['auc_edate']));
	$sale_price_won = $sale_price*$param[0]['basePrice']; // 등록당시 환율 적용 
	$resale_yn = trim(sqlfilter($_REQUEST['resale_yn']));

	$query_sale = "update product_info_sale set";
	$query_sale .= " member_idx = '".$member_idx."', ";
	$query_sale .= " sale_method = '".$sale_method."', ";
	$query_sale .= " auc_sdate = '".$auc_sdate."', ";
	$query_sale .= " auc_edate = '".$auc_edate."', ";
	//$query_sale .= " resale_yn = '".$resale_yn."', ";
	$query_sale .= " sale_auth_yn = '".$sale_auth_yn."', ";
	$query_sale .= " sale_price = '".$sale_price."', ";
	$query_sale .= " sale_price_won = '".$sale_price_won."', ";	
	$query_sale .= " sale_cnt = '".$sale_cnt."', ";
	$query_sale .= " wdate = now() ";
	$query_sale .= " where 1 and idx='".$sale_idx."'";
	$result_sale = mysqli_query($gconnet,$query_sale);
	
	################### 작품 판매정보 등록종료 ###############
	
	//exit;
	error_frame_go("수정되었습니다.","product_list.php?".$total_param."&pageNo=".$pageNo."");
?>