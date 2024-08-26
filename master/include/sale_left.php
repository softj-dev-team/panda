<aside id="lnb">
	<h2 class="tit"><span>판매/정산 관리</span></h2>
	<ul class="menu">
		<li <?if($smenu==1){?>class="on"<?}?>>
			<a href="../sale/sale_list.php?bmenu=<?=$bmenu?>&smenu=1&v_sect=general">일반 판매현황</a>
		</li>
		<li <?if($smenu==2){?>class="on"<?}?>>
			<a href="../sale/sale_list.php?bmenu=<?=$bmenu?>&smenu=2&v_sect=auction">경매 판매현황</a>
		</li>
		<li <?if($smenu==3){?>class="on"<?}?>>
			<a href="../sale/sale_calc_list.php?bmenu=<?=$bmenu?>&smenu=3">판매 정산관리</a>
		</li>
		<li <?if($smenu==4){?>class="on"<?}?>>
			<a href="../sale/sale_can_list.php?bmenu=<?=$bmenu?>&smenu=4">판매 취소관리</a>
		</li>
	</ul>
</aside>