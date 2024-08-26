<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$product_idx = trim(sqlfilter($_REQUEST['product_idx']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	
	$target_param = "product_idx=".$product_idx;

	$where = " and is_del='N' and sale_ok='1' and product_idx='".$product_idx."'";

	if(!$pageNo){
		$pageNo = 1;
	}

	$s_cnt = 5; // 기본목록 5개

	$pageScale = $s_cnt;  
	$start = ($pageNo-1)*$pageScale;

	$StarRowNum = (($pageNo-1) * $pageScale);
	$EndRowNum = $pageScale;

	$order_by = " order by idx desc";

	$query = "select *,(select user_nick from member_info where 1 and del_yn='N' and idx=product_info_sale.member_idx) as user_nick,(select product_title from product_info where 1 and is_del='N' and idx=product_info_sale.product_idx) as product_title from product_info_sale where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from product_info_sale where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

	<div class="list">
		<div class="search_wrap">
			<table class="search_list">
				<caption>검색결과</caption>
				<colgroup>
					<col style="width:10%;">
					<col style="width:50%;">
					<col style="width:20%">
					<col style="width:20%;">
				</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>
						<th scope="col">작품명</th>
						<th scope="col">판매자</th>
						<th scope="col">판매방식</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40">판매중인 내역이 없습니다.</strong></td>
					</tr>
				<? } ?>
				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
					<tr>
						<td><input type="checkbox" name="sale_member_idx[]" id="sale_member_idx[]" value="<?=$row["member_idx"]?>" required="yes"  message="판매내역"/></td>
						<td><?=$row['product_title']?></td>
						<td><?=$row['user_nick']?></td>
						<td><?=$arr_sale_method[$row['sale_method']]?></td>
					</tr>
				<?}?>
			</table>

			<div class="pagination mt0">
			<?
				$target_link = "inner_product_view_sale_list.php";
				$target_id = "product_view_sale_list";
				include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_ajax.php";	
			?>
			</div>

		</div>
	</div>

	<script>
		var check  = 0;                                                                            //체크 여부 확인
		function CheckAll(){                
			var boolchk;                                                                              //boolean형 변수 
			var chk = document.getElementsByName("sale_member_idx[]")                 //체크박스의 name값
			if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}
		}
	</script>
