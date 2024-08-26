<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sitecon_left.php"; // 사이트설정 좌측메뉴?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword;

if(!$pageNo){
	$pageNo = 1;
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

/*$query =	" SELECT * ";
$query = $query." FROM ( ";
$query = $query." SELECT	ROW_NUMBER() OVER(ORDER BY idx DESC) AS rowNumber ";
$query = $query.",	idx,set_title, set_keyword, wdate ";
$query = $query." FROM sitetitle_set WITH(NOLOCK) ";
$query = $query." WHERE 1=1  ".$where;
$query = $query."	) AS S ";
$query = $query." WHERE S.rowNumber BETWEEN ".$StarRowNum." AND ".$EndRowNum." ;";
$query = "select * from sitetitle_set limit ".$StarRowNum." , ".$EndRowNum;*/

$order_by = " order by idx desc ";
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$query = "select * from sitetitle_set where 1=1  ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from sitetitle_set where 1=1  ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<SCRIPT LANGUAGE="JavaScript">
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
		location.href = "search_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
	}

	
//-->
</SCRIPT>

<!-- content -->
<section id="content">
	<div class="inner">
		<h3>
			<?//=getHead($code)?> 사이트타이틀 설정관리
		</h3>
		<div class="cont">
						
			<table class="t_list">
				<thead>
					<tr>
						<th width="35%">사이트 타이틀</th>
						<th width="40%">메타검색 키워드</th>
						<!--<th width="15%">일반회원 가입회비</th>-->
						<th width="10%">설정하기</th>
						<th width="15%">마지막 설정일</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 사이트 타이틀이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
				<form name="frm_modify_<?=$i?>" method="post" action="sitetitle_modify_action.php"  target="_fra_admin">
				<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
				<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<tr>
						<td><input type="text" style="width:90%;" name="set_title" required="yes" message="사이트타이틀" value="<?=$row[set_title]?>"></td>
						<td><input type="text" style="width:90%;" name="set_keyword" required="yes" message="검색키워드" value="<?=$row[set_keyword]?>"></td>
						<!--<td><input type="text" style="width:70%;" name="mem_gen_payment" required="yes" message="일반회원 가입회비" is_num="yes" value="<?=$row[mem_gen_payment]?>"> 원</td>-->
						<td><a href="javascript:go_modify('frm_modify_<?=$i?>');" class="btn_blue2">설정</a></td>						
						<td><?=substr($row[wdate],0,10)?></td>
					</tr>
				</form>
				<?}?>	
			
			</tbody>
			</table>
			
			<!-- //Goods List -->
			
	</div>
</section>
<!-- //content -->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>