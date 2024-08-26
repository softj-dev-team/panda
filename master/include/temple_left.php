<aside id="lnb">
	<h2 class="tit"><span>사찰관리</span></h2>
	<ul class="menu">
		<li <?if($smenu==1){?>class="on"<?}?>>
			<a href="../temple/temple_list.php?bmenu=<?=$bmenu?>&smenu=1">사찰 리스트</a>
		</li>
		<li <?if($smenu==2){?>class="on"<?}?>>
			<a href="../temple/newpro_set.php?bmenu=<?=$bmenu?>&smenu=2">추천사찰 설정</a>
		</li>
		<li <?if($smenu==3){?>class="on"<?}?>>
			<a href="../temple/temple_popup_list.php?bmenu=<?=$bmenu?>&smenu=3">사찰 팝업 관리</a>
		</li>
	</ul>
</aside>