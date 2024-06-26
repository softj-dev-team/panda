		<aside id="lnb">
			<h2 class="tit"><span>상금지급 관리</span></h2>
			<ul class="menu">
				<li <?php if($smenu==1){?>class="on"<?php }?>>
					<a href="../calcurate/calcurate_list.php?bmenu=<?php echo $bmenu?>&smenu=1">지급대기 리스트</a>
				</li>
				<li <?php if($smenu==2){?>class="on"<?php }?>>
					<a href="../calcurate/calcurate_complete_list.php?bmenu=<?php echo $bmenu?>&smenu=2">지급완료 리스트</a>
				</li>
			</ul>
		</aside>