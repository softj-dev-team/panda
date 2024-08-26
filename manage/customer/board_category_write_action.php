<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<?
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$board_code = trim(sqlfilter($_REQUEST['board_code']));
$board_sect = trim(sqlfilter($_REQUEST['board_sect']));
$lang = trim(sqlfilter($_REQUEST['lang']));

$category = trim(sqlfilter($_REQUEST['category']));

$query = "select idx from board_category where 1 and subject='".$category."' and is_del='N'";
if ($board_code){
	$where .= " and board_code = '".$board_code."'";
}
if ($board_sect){
	$where .= " and board_sect = '".$board_sect."'";
}
if ($lang){
	$where .= " and lang = '".$lang."'";
}
$query = $query.$where;
$result = mysqli_query($gconnet, $query);
if(mysqli_num_rows($result) > 0){
	?>
	<script type="text/javascript">
        alert('입력하신 카테고리는 이미 추가 되어있습니다.\n다른 이름으로 추가해주세요.');
	</script>
	<?php
	exit;
}

$priority = ((int)mysqli_num_rows(mysqli_query($gconnet, "SELECT * FROM board_category WHERE 1 and is_del='N'".$where))) + 1;

$query = "INSERT INTO board_category SET ";
$query .= " board_code = '".$board_code."' , ";
$query .= " board_sect = '".$board_sect."', ";
$query .= " subject = '$category', ";
$query .= " priority = '$priority', ";
$query .= " wdate = NOW(), ";
$query .= " ndate = NOW(), ";
$query .= " lang = '$lang' ";

if(mysqli_query($gconnet, $query)){
	?>
	<script type="text/javascript">
        alert('카테고리 등록이 정상적으로 완료 되었습니다.');
        parent.location.href = "board_category_list.php?<?=$total_param?>";
	</script>
	<?php
}else{
	?>
	<script type="text/javascript">
        alert('카테고리 등록중 오류가 발생했습니다.')
	</script>
	<?php
}