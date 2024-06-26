<aside id="lnb">
	<h2 class="tit"><span>체험관리</span></h2>
	<ul class="menu">
		<li <?if($smenu==1){?>class="on"<?}?>>
			<a href="../exper/exp_list.php?bmenu=<?=$bmenu?>&smenu=1">체험 리스트</a>
		</li>
		<li <?if($smenu==2){?>class="on"<?}?>>
			<a href="../exper/cate_list.php?bmenu=<?=$bmenu?>&smenu=2">체험 카테고리 관리</a>
		</li>
		<li <?if($smenu==3){?>class="on"<?}?>>
			<a href="../exper/mainban_list.php?bmenu=<?=$bmenu?>&smenu=3">메인화면 체험배치</a>
		</li>
		<li <?if($smenu ==4){?>class="on"<?}?>>
			<a href="../exper/point_set.php?bmenu=<?=$bmenu?>&smenu=4&point_sect=refund">코인 적립설정</a>
		</li>
		<li <?if($smenu==5){?>class="on"<?}?>>
			<a href="../exper/answer_list.php?bmenu=<?=$bmenu?>&smenu=5">체험신청 관리</a>
		</li>
		<li <?if($smenu==6){?>class="on"<?}?>>
			<a href="../exper/board_list.php?bmenu=<?=$bmenu?>&smenu=6&bbs_code=exprv1">기대리뷰</a>
		</li>
		<li <?if($smenu==7){?>class="on"<?}?>>
			<a href="../exper/board_list.php?bmenu=<?=$bmenu?>&smenu=7&bbs_code=exprv2">체험리뷰</a>
		</li>
		<li <?if($smenu==8){?>class="on"<?}?>>
			<a href="../exper/share_list.php?bmenu=<?=$bmenu?>&smenu=8">체험 공유내역</a>
		</li>
	</ul>
</aside>