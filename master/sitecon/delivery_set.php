<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'field='.$field.'&keyword='.$keyword;

if(!$pageNo){
	$pageNo = 1;
}

$where .= "";

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$query = "select * from delivery_set where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from delivery_set where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<script type="text/javascript">
<!--
	function go_delete(id){
		if(confirm('정말로 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){
				_fra_admin.location.href = "search_delete_action.php?idx="+id+"&<?=$total_param?>";
			}
		}
	}

	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			return;
		}
	}
	
	function go_list(){
		location.href = "search_list.php";
	}
	
//-->
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 관리</li>
						<li>배송비 설정</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>배송비 설정관리</h3>
				</div>
				<div class="list">
					<div class="search_wrap">
					<!-- 검색창 시작 -->
					<form name="s_mem" method="post" action="delivery_set.php">
						<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<div class="search_area">
							<!--<select name="s_sect1" size="1" style="vertical-align:middle;" >
								<option value="">로그인여부</option>
								<option value="Y" <?=$s_sect1=="Y"?"selected":""?>>로그인 가능</option>
								<option value="N" <?=$s_sect1=="N"?"selected":""?>>로그인 차단</option>
							</select>
							&nbsp;&nbsp;
							<select name="field" size="1" style="vertical-align:middle;">
								<option value="">검색기준</option>
								<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
								<option value="com_name" <?=$field=="com_name"?"selected":""?>>가맹점명</option>
								<option value="presi_name" <?=$field=="presi_name"?"selected":""?>>점주님명</option>
								<option value="cell" <?=$field=="cell"?"selected":""?>>휴대전화</option>
								<option value="email" <?=$field=="email"?"selected":""?>>이메일</option>
							</select>
							<input type="text" title="검색" name="keyword" id="keyword" value="<?=$keyword?>">
							<button onclick="s_mem.submit();">검색</button>-->
						</div>
						<div class="result">
							<div class="btn_wrap" style="height:36px;">
								<!--<select>
									<option>전체</option>
								</select>-->
							</div>
						</div>
					</form>
					<!-- 검색창 종료 -->
					<!-- 리스트 시작 -->
						<div class="list_tit" style="margin-top:-50px;">
							<h3>배송비 등록 관리</h3>
						</div>
						
						<table class="search_list">
							<caption>카테고리등록</caption>
							<thead>
					<tr>
						<th width="30%">무료배송 기준금액</th>
						<!--<th width="50%">배송료 할인적용</th>-->
						<th>기본배송료</th>
						<th width="10%">마지막 설정일</th>
						<th width="10%">설정하기</th>
					</tr>
				</thead>
				<tbody>
				
				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					//$row = htmlspecialchars_array($row);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
				<form name="frm_modify_<?=$i?>" method="post" action="delivery_set_action.php"  target="_fra_admin">
				<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
				<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<tr>
						<td>총 결제금액이 <input type="text" style="width:20%;" name="set_price1" required="yes" message="무료배송 기준금액" is_num="yes" value="<?=$row[set_price1]?>"> 원 이상이면 무료배송</td>
						<!--
						<td>총 결제금액이 <input type="text" style="width:20%;" name="set_price2" required="yes" message="배송료 할인적용 기준금액" is_num="yes" value="<?=$row[set_price2]?>"> 원 이상이면 배송료 <input type="text" style="width:10%;" name="set_price3" required="yes" message="할인적용할 배송료" is_num="yes" value="<?=$row[set_price3]?>"> 원 적용</td>
						-->
						<td>총 결제금액이 <?=number_format($row[set_price1])?> 원 미만이면 배송료 <input type="text" style="width:10%;" name="set_price4" required="yes" message="기본배송료" is_num="yes" value="<?=$row[set_price4]?>"> 원 적용</td>
						<td><?=substr($row[wdate],0,10)?></td>
						<td><a href="#" onclick="go_modify('frm_modify_<?=$i?>');" class="btn_blue">설정</a></td>						
					</tr>
				</form>
				<?}?>	
			
			</tbody>
						</table>

						<!-- <div class="list_tit" style="margin-top:20px;">
							<h3>추가 배송비 설정</h3>
						</div>
						<!--카테고리코드를 클릭하시면 하위 카테고리 목록이 나옵니다.
						<table class="search_list">
							<caption>검색결과</caption>
							<thead>
				<tr>
					<th>지역주소 (우편번호 검색시 나타나는 주소 입력)</th>
					<th style="width:15%;">추가운임 (숫자만)</th>
					<th style="width:20%;">수정</th>
				</tr>
			</thead>
			<tbody>
			<?
			$query = "select * from ".NS."delivery_charge order by address asc";
			if($result = mysqli_query($gconnet,$query)){
				while($row = mysqli_fetch_array($result)){
					$row = htmlspecialchars_array($row);
			?>
				<tr>
					<td><input type="text" class="address" name="address" value="<?=$row["address"]?>" style="width:98%;" /></td>
					<td><input type="text" class="charge" name="charge" value="<?=$row["charge"]?>" style="width:70px;" /> 원</td>
					<td>
						<a href="#" class="btn_blue2 modify_btn" data-idx="<?=$row["idx"]?>">수정</a>
						<a href="#" class="btn_blue2 delete_btn" data-idx="<?=$row["idx"]?>">삭제</a>
					</td>
				</tr>
			<?
				}
			}
			?>
				<tr>
					<td><input type="text" id="address" name="address" value="" style="width:98%;" /></td>
					<td><input type="text" id="charge" name="charge" value="" style="width:70px;" /> 원</td>
					<td><a href="#" id="add_btn" class="btn_blue2" />추가</a></td>
				</tr>
			</tbody>
						</table>
						<!-- 페이징 시작 
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
						<!-- 페이징 종료 -->

					</div>
				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>

		<form id="charge_form" method="post" action="delivery_charge_action.php" target="_fra_admin">
			<input type="hidden" id="charge_proc_type" name="proc_type" value="" />
			<input type="hidden" id="charge_idx" name="idx" value="" />
			<input type="hidden" id="charge_address" name="address" value="" />
			<input type="hidden" id="charge_charge" name="charge" value="" />
		</form>

			<script type="text/javascript">
			$("#add_btn").click(function(){
				var $address = $("#address");
				var $charge = $("#charge");

				if(!check_charge_address($address)){
					return false;
				}

				if(!check_charge_charge($charge)){
					return false;
				}

				$("#charge_proc_type").val("write");
				$("#charge_idx").val("");
				$("#charge_address").val($address.val());
				$("#charge_charge").val($charge.val());
				$("#charge_form").submit();
			});

			$(".modify_btn").click(function(){
				var $address = $(this).parent().parent().find(".address");
				var $charge = $(this).parent().parent().find(".charge");
				if(!check_charge_address($address)){
					return false;
				}

				if(!check_charge_charge($charge)){
					return false;
				}

				$("#charge_proc_type").val("modify");
				$("#charge_idx").val($(this).data("idx"));
				$("#charge_address").val($address.val());
				$("#charge_charge").val($charge.val());
				$("#charge_form").submit();
			});

			$(".delete_btn").click(function(){
				if(confirm("정말 삭제하시겠습니까?")){
					$("#charge_proc_type").val("delete");
					$("#charge_idx").val($(this).data("idx"));
					$("#charge_form").submit();
				}
			});

			function check_charge_address($address){
				if($address.val() == ""){
					alert("주소를 입력해주세요.");
					$address.focus();
					return false;
				}

				return true;
			}

			function check_charge_charge($charge){
				if($charge.val() == ""){
					alert("추가운임을 입력해주세요.");
					$charge.focus();
					return false;
				}else{
					if(!/^[0-9]+$/.test($charge.val())){
						alert("추가운임은 숫자만 입력해주세요.");
						$charge.focus();
						return false;
					}
				}

				return true;
			}
			</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
