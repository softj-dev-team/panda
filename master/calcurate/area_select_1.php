<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?php
$fm = trim(sqlfilter($_REQUEST['fm']));
$fname = trim(sqlfilter($_REQUEST['fname']));
$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));

$gugun_api_url = "http://13.209.50.228/search/address2?ad1_no=".$cate_code1.""; 
$gugun_api_param = array();
$gugun_api_contents = get_curl_form_get($gugun_api_url, $gugun_api_param);
$gugun_decode_1 = json_decode($gugun_api_contents, true);
?>
<script> 
 parent.<?php echo $fm?>.<?php echo $fname?>.length = <?php echo sizeof($gugun_decode_1['address'])?>+1; 
 parent.<?php echo $fm?>.<?php echo $fname?>.options[0].text = "상세지역 검색"; 
 parent.<?php echo $fm?>.<?php echo $fname?>.options[0].value = ""; 
<?php for($json_i=0; $json_i<sizeof($gugun_decode_1['address']); $json_i++) {?>
   parent.<?php echo $fm?>.<?php echo $fname?>.options[<?php echo $json_i?>+1].text = "<?php echo $gugun_decode_1['address'][$json_i]['ad2Text']?>"; 
   parent.<?php echo $fm?>.<?php echo $fname?>.options[<?php echo $json_i?>+1].value = "<?php echo $gugun_decode_1['address'][$json_i]['ad2No']?>|<?php echo $gugun_decode_1['address'][$json_i]['ad2Text']?>"; 
<?php }?>
</script> 
