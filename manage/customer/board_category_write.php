<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code'])); 
$bbs_sect = trim(sqlfilter($_REQUEST['bbs_sect'])); 
$lang = trim(sqlfilter($_REQUEST['lang']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&bbs_sect='.$bbs_sect.'&lang='.$lang;

if(!$lang){
	$lang = 'kor';
}
if ($bbs_code == "faq"){
	$bbs_code_str = "FAQ";
}
?>

<body>
	<div id="wrap" class="skin_type01">
		<? include $_SERVER["DOCUMENT_ROOT"] . "/manage/include/admin_top.php"; // 상단메뉴?>
		<div class="sub_wrap">
			<? include $_SERVER["DOCUMENT_ROOT"] . "/manage/include/customer_left.php"; // 좌측메뉴?>
			<!-- content 시작 -->
			<div class="container clearfix">
				<div class="content">
					<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
					<div class="navi">
						<ul class="clearfix">
							<li>HOME</li>
							<li>고객센터</li>
							<li><?=$bbs_code_str?> 카테고리 관리</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3><?=$bbs_code_str?> 카테고리 등록<?=$lang==='kor'?'':' (영문)'?></h3>
					</div>
					<div class="write">

						<form name="frm" action="board_category_write_action.php" target="_fra_admin" method="post"
							  enctype="multipart/form-data">
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<input type="hidden" name="board_code" id="board_code" value="<?=$bbs_code?>"/>
							<input type="hidden" name="board_sect" id="board_sect" value="<?=$bbs_sect?>"/>
							<input type="hidden" name="lang" id="lang" value="<?=$lang?>"/>
							<table>
								<caption></caption>
								<colgroup>
									<col style="width:15%">
									<col style="width:35%">
									<col style="width:15%">
									<col style="width:35%">
								</colgroup>

								<tr>
									<th scope="row">카테고리명</th>
									<td colspan="3"><input type="text" name="category"
														   required="yes" message="카테고리명"></td>
								</tr>

							</table>
						</form>
						<div class="write_btn align_r">
							<a href="javascript:go_list();" class="btn_gray">목록보기</a>
							<a href="javascript:go_submit();" class="btn_blue">등록하기</a>
						</div>
					</div>
				</div>
			</div>
			<!-- content 종료 -->
		</div>
	</div>

	<script type="text/javascript">
        function go_submit() {
            var check = chkFrm('frm');
            if (check) {
                frm.submit();
            } else {
                false;
            }
        }

        function go_list() {			
            // location.href = "fna_category_list.php?<?=$total_param?>";
			// 2023-05-04 jwc 수정
			location.href = "board_category_list.php?<?=$total_param?>";

        }

	</script>
	<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>