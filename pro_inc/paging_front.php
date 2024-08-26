<?
$temple_url_idx = trim(sqlfilter($_REQUEST['temple_idx']));
if($num >= 1){ // DB 추출갯수가 하나라도 있을때 시작 
	
	$page_list_size = $pageScale; // 한 페이지당 출력할 갯수  
	$page_size = 6; // 1 ~ 10 페이지까지 화면에 뿌려준다.
	$total_row = $num; // 게시물의 총 갯수
	$c_page = $pageNo; // 현재 페이지

	$total_page =  ceil($total_row / $page_list_size);		//전체  개수
	$page_per = ceil($c_page/$page_size);	

	$start_page_prev = $c_page%$page_size;
	if($start_page_prev == 1){
		$start_page = $c_page;	//시작  값
	} else {
		$start_page = ceil($c_page / $page_size);	
		$start_plus_num = ($page_per -1)*($page_size-1);
		$start_page = $start_page+$start_plus_num;	//시작  값
	}
	
	$end_page = $start_page + $page_size - 1;	// 끝  값

	// 전체  초기화
	if ($total_page < $end_page){
		$end_page = $total_page;
	}
	
			###처음 버튼
				if ($c_page > $page_size) {
					//마지막
					echo '<a href="'.$_SERVER["PHP_SELF"].'?pageNo=&'.$total_param.'" class="start"><img src="/images/pagenation/ll.png"></a>';
					//이전
					//$prev_list = ($start_page - 1)*$page_list_size;
					$prev_list = $start_page - $page_size;
					if($prev_list == 0){
						$prev_list = "";
					}
					echo '<a href="'.$_SERVER["PHP_SELF"].'?pageNo='.$prev_list.'&'.$total_param.'" class="pre"><img src="/images/pagenation/l.png"></a>';
				}
				//echo '&nbsp;';
			###페이지 출력
				for ($i=$start_page;$i <= $end_page;$i++){

					//$page = $page_list_size * $i;
					$page = $i;

					if($page == 0){
						$page = 1;
					}

					//echo $c_page." :: ".$page."<br><br>";
					
					if($c_page != $page){
						//echo '<a href="'.$_SERVER["PHP_SELF"].'?pageNo='.$page.'&'.$total_param.'&pidx='.$pidx.'"><span>'.($i+1).'</span></a>';
						echo '<a href="'.$_SERVER["PHP_SELF"].'?pageNo='.$page.'&'.$total_param.'">'.$i.'</a>';
					}else{
						//echo "<span class='page'>".($i+1).'</span>';
						echo '<a href="'.$_SERVER["PHP_SELF"].'?pageNo='.$page.'&'.$total_param.'" class="atv">'.$i.'</a>';
					}

					if($i != $end_page){
						//echo '<img src="/img/icon/line.gif" align="absmiddle">';
						//echo '&nbsp';					
					}
				}
				//echo '&nbsp;';
			###다음버튼
				if($total_page > $end_page){
					//다음
					//$next_list = ($end_page + 1)*$page_list_size;
					//$next_list = $end_page*$page_list_size;
					$next_list = $end_page + 1;
					echo '<a href="'.$_SERVER["PHP_SELF"].'?pageNo='.$next_list.'&'.$total_param.'" class="next"><img src="/images/pagenation/r.png"></a>';
					// 처음
					/*$last_page = $total_row - ($total_row % $page_list_size);
					if($last_page == $total_row){
						$last_page = $total_row - $page_list_size;
					}*/
					$last_page = $total_page;
					echo ' <a href="'.$_SERVER["PHP_SELF"].'?pageNo='.$last_page.'&'.$total_param.'" class="end"><img src="/images/pagenation/rr.png"></a>';
				}

} // DB 추출갯수가 하나라도 있을때 종료 
?>
