<aside id="lnb">
	<h2 class="tit"><span>회원관리</span></h2>
	<ul class="menu">
		<li <?if($smenu==1){?>class="on"<?}?>>
			<a href="../member/member_list.php?bmenu=<?=$bmenu?>&smenu=1">회원 조회</a>
		</li>
		<li <?if($smenu==2){?>class="on"<?}?>>
			<a href="../member/member_list_out.php?bmenu=<?=$bmenu?>&smenu=2">탈퇴 회원</a>
		</li>
	</ul>
</aside>