<aside id="lnb">
	<h2 class="tit"><span>주차장 관리</span></h2>
	<ul class="menu">
		<li <?if($smenu==1){?>class="on"<?}?>>
			<a href="../parklot/parklot_list.php?bmenu=<?=$bmenu?>&smenu=1">주차장 정보 조회</a>
		</li>
		<li <?if($smenu==2){?>class="on"<?}?>>
			<a href="../parklot/parklot_list.php?bmenu=<?=$bmenu?>&smenu=2&v_sect=auth">인증 주자창 정보 조회</a>
		</li>
		<li <?if($smenu==3){?>class="on"<?}?>>
			<a href="../parklot/parklot_list.php?bmenu=<?=$bmenu?>&smenu=3&v_sect=public">공유 주차장 정보 조회</a>
		</li>
	</ul>
</aside>