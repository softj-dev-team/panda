<aside id="lnb">
	<h2 class="tit"><span>가맹점관리</span></h2>
	<ul class="menu">
		<li <?if($smenu==1){?>class="on"<?}?>>
			<a href="../partner/member_list.php?bmenu=<?=$bmenu?>&smenu=1">가맹점 리스트</a>
		</li>
		<li <?if($smenu==2){?>class="on"<?}?>>
			<a href="../partner/member_point_list.php?bmenu=<?=$bmenu?>&smenu=2&point_sect=smspay">가맹점 사용료관리</a>
		</li>
		<li <?if($smenu==3){?>class="on"<?}?>>
			<a href="../partner/member_payment.php?bmenu=<?=$bmenu?>&smenu=3">가맹점 충전관리</a>
		</li>
	</ul>
</aside>