<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/check_login_frame.php"; // 로그인 체크 ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?	
	$tbname = urldecode(sqlfilter($_REQUEST['tbname']));
	$tcode = urldecode(sqlfilter($_REQUEST['tcode']));
	$tidx = urldecode(sqlfilter($_REQUEST['tidx']));
	$mode = urldecode(sqlfilter($_REQUEST['mode']));

	if(get_cnt_zzim($tbname,$tcode,$tidx,$_SESSION['member_modapt_idx']) == 0){ // 찜내역이 없을때 시작 

		############# 찜내역 인서트 시작 ##########
		$query_quiz_ins = " insert into board_reco_cnt set "; 
		$query_quiz_ins .= " board_tbname = '".$tbname."', ";
		$query_quiz_ins .= " board_code = '".$tcode."', ";
		$query_quiz_ins .= " board_idx = '".$tidx."', ";
		$query_quiz_ins .= " member_idx = '".$_SESSION['member_modapt_idx']."', ";
		$query_quiz_ins .= " wdate = now() ";
		$result_quiz_ins = mysqli_query($gconnet,$query_quiz_ins);
		############# 찜내역 인서트 종료 ##########
		
		/*$point_sect = "refund"; // 코인

		$sql = "select add_point_1 from member_point_set where 1=1 and point_sect='".$point_sect."' and coin_type='advert' order by idx desc limit 0,1";
		$query = mysqli_query($gconnet,$sql);
		$row = mysqli_fetch_array($query);
	
		$chg_mile = $row['add_point_1'];
		$order_num = "ad_zzim_".$tidx;
		$pay_price = $tidx;
		$mile_title = $row['ad_title']." 좋아요 코인적립";
		$mile_sect = "A"; // 코인 종류 = A : 적립, P : 대기, M : 차감
		
		$sql_login_pre2 = "select idx from member_point where 1 and member_idx = '".$_SESSION['member_modapt_idx']."' and order_num = '".$order_num."' and pay_price = '".$pay_price."' and point_sect='".$point_sect."' and mile_sect='".$mile_sect."'";
		$query_login_pre2 = mysqli_query($gconnet,$sql_login_pre2);

		if(mysqli_num_rows($query_login_pre2) == 0 ){ // 코인 적립내역이 없을경우 시작
			coin_plus_minus($point_sect,$_SESSION['member_modapt_idx'],$mile_sect,$chg_mile,$mile_title,$order_num,$pay_price,"");

		} // 코인 적립내역이 없을경우 종료*/
			if($tbname == "get_sale_info" || $tbname == "apt_info"){
				?>
					<script>
						$("#hart_img_area_<?=$tbname?>_<?=$tidx?>", parent.document).attr('src','../image/sub/<?if($mode == "detail"){?>small_<?}?>full_heart.png');
					</script>
				<?
				error_frame("찜 등록이 완료되었습니다.");
			} elseif($tbname == "board_content"){
				error_frame("공감 등록이 완료되었습니다.");
			} elseif($tbname == "member_modapt_info"){
				?>
					<?if($mode == "my"){?>
						<script>
							$("#hart_ico_area_<?=$tbname?>_<?=$tidx?>", parent.document).removeClass("heart_icon");
							$("#hart_ico_area_<?=$tbname?>_<?=$tidx?>", parent.document).addClass("heart_icon_on");
						</script>
					<?}else{?>
						<script>
							$("#hart_ico_area_<?=$tbname?>_<?=$tidx?>", parent.document).attr('src','./img/ico_hart_on.png');
							$("#hart_txt_area_<?=$tbname?>_<?=$tidx?>", parent.document).html("<?=get_cnt_zzim('member_modapt_info','like',$tidx,'')?>");
						</script>
					<?}?>
				<?
				error_frame("즐겨찾기가 완료되었습니다.");
			}
		?>
			<!--<script>$("#cnt_zzim_area", parent.document).html("<?=number_format(get_cnt_zzim('get_sale_info','',$tidx,''))?>");</script>-->
		<?
	} else { // 찜내역이 없을때 종료
			
			############# 찜내역 삭제 시작 ##########
				$query_quiz_ins = " delete from board_reco_cnt where 1"; 
				if($tbname){
					$query_quiz_ins .= " and board_tbname = '".$tbname."' ";
				}
				if($tcode){
					$query_quiz_ins .= " and board_code = '".$tcode."' ";
				}
				if($tidx){
					$query_quiz_ins .= " and board_idx = '".$tidx."' ";
				}
				$query_quiz_ins .= " and member_idx = '".$_SESSION['member_modapt_idx']."' ";
				$result_quiz_ins = mysqli_query($gconnet,$query_quiz_ins);
			############# 찜내역 삭제 종료 ##########

			if($tbname == "get_sale_info" || $tbname == "apt_info"){
				?>
					<script>
					<?if($mode == "mypage"){?>
						parent.location.reload();
					<?}else{?>
						$("#hart_img_area_<?=$tbname?>_<?=$tidx?>", parent.document).attr('src','../image/sub/<?if($mode == "detail"){?>small_<?}?>empty_heart.png');
					<?}?>
					</script>
				<?
				error_frame("찜 등록이 취소되었습니다.");
			} elseif($tbname == "board_content"){
				error_frame("이미 공감 등록된 댓글입니다.");
			} elseif($tbname == "member_modapt_info"){
				?>
					<?if($mode == "my"){?>
						<script>
							$("#hart_ico_area_<?=$tbname?>_<?=$tidx?>", parent.document).removeClass("heart_icon_on");
							$("#hart_ico_area_<?=$tbname?>_<?=$tidx?>", parent.document).addClass("heart_icon");
						</script>
					<?}else{?>
						<script>
							$("#hart_ico_area_<?=$tbname?>_<?=$tidx?>", parent.document).attr('src','./img/ico_hart.png');
							$("#hart_txt_area_<?=$tbname?>_<?=$tidx?>", parent.document).html("<?=get_cnt_zzim('member_modapt_info','like',$tidx,'')?>");
						</script>
					<?}?>
				<?
				error_frame("즐겨찾기가 취소되었습니다.");
			}
	}
?>