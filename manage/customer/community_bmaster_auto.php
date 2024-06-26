<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$id_code = trim(sqlfilter($_REQUEST['id_code']));

if($id_code){ // 검색란에 검색어가 입력이 되었을때 
	
	$const_search_where .= " and user_id like '%".$id_code."%' ";
	$const_search_sql = "select user_id from member_info where 1=1 and user_sect = 'PAT' and user_gubun in ('PAT_B','PAT_S','PAT_SS') and memout_yn = 'N' ";
	$const_search_orderby = " order by user_id asc ";
	$const_search_sql = $const_search_sql.$const_search_where.$const_search_orderby;

	//echo $const_search_where."<br>";

	$const_search_query = mysqli_query($gconnet,$const_search_sql);
	$const_search_cnt = mysqli_num_rows($const_search_query);

	for($const_search_i=0;$const_search_i<$const_search_cnt;$const_search_i++){
		$const_search_row = mysqli_fetch_array($const_search_query);
		$const_name = $const_search_row['user_id'];
		
		$id_code_chg = "<font color='blue'>".$id_code."</font>";
		$const_name_str = str_replace($id_code, $id_code_chg, $const_name);
	?>
		<a href="javascript:go_auto_select('<?=$const_search_row['user_id']?>');"/><?=$const_name_str?></a><br>
	<?
	}

} // 검색란에 검색어가 입력이 되었을때 종료
?>