<aside id="lnb">
	<h2 class="tit"><span>컨텐츠 관리</span></h2>
	<ul class="menu">
	<?
		$master_cleft_sql = "select cate_code1,cate_name1 from common_code where 1 and type='menu' and cate_level = '1' and is_del='N' and del_ok='N' order by cate_align desc"; 
		$master_cleft_query = mysqli_query($gconnet,$master_cleft_sql);
				
		$master_cleft_k = 0;
		for($master_cleft_i=0; $master_cleft_i<mysqli_num_rows($master_cleft_query); $master_cleft_i++){
			$master_cleft_row = mysqli_fetch_array($master_cleft_query);
			$master_cleft_k = $master_cleft_k+1;
	?>
		<li <?if($smenu==$master_cleft_k){?>class="on"<?}?>>
			<a href="../curri/curri_list.php?bmenu=<?=$bmenu?>&smenu=<?=$master_cleft_k?>&v_sect=<?=$master_cleft_row['cate_code1']?>"><?=$master_cleft_row['cate_name1']?></a>
		</li>
	<?
		}
		$master_cleft_left = $master_cleft_k+1;
	?>
	<!--<li <?if($smenu==$master_cleft_left){?>class="on"<?}?>>
		<a href="../partner/yakkwan_set.php?bmenu=<?=$bmenu?>&smenu=<?=$master_cleft_left?>&cate_code1=host1">메인화면 인사말</a>
	</li>-->
	</ul>
</aside>