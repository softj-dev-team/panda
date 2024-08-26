<aside id="lnb">
	<h2 class="tit"><span>사이트운영 관리</span></h2>
	<ul class="menu">
		<li <?if($smenu==1){?>class="on"<?}?>>
			<a href="../manage/main_select_list.php?bmenu=<?=$bmenu?>&smenu=1&v_sect=prod">메인 관리</a>
		</li>
		<li <?if($smenu==10){?>class="on"<?}?>>
			<a href="../manage/main_select_top_list.php?bmenu=<?=$bmenu?>&smenu=10&v_sect=topprod">메인상단 작품관리</a>
		</li>
		<li <?if($smenu==2){?>class="on"<?}?>>
			<a href="../manage/msg_send_list_manual.php?bmenu=<?=$bmenu?>&smenu=2">푸시 관리</a>
		</li>
		<li <?if($smenu==3){?>class="on"<?}?>>
			<a href="../manage/board_list.php?bmenu=<?=$bmenu?>&smenu=3&bbs_code=notice">공지사항 관리</a>
		</li>
		<li <?if($smenu==4){?>class="on"<?}?>>
			<a href="../manage/board_list.php?bmenu=<?=$bmenu?>&smenu=4&bbs_code=faq">FAQ 관리</a>
		</li>
		<li <?if($smenu==5){?>class="on"<?}?>>
			<a href="../manage/agreem_list.php?bmenu=<?=$bmenu?>&smenu=5&cr_cate=terms">약관 관리</a>
		</li>
		<li <?if($smenu==6){?>class="on"<?}?>>
			<a href="../manage/agreem_list.php?bmenu=<?=$bmenu?>&smenu=6&cr_cate=person">개인정보처리방침 관리</a>
		</li>
		<li <?if($smenu==7){?>class="on"<?}?>>
			<a href="../manage/decla_list.php?bmenu=<?=$bmenu?>&smenu=7">신고관리</a>
		</li>
		<li <?if($smenu==8){?>class="on"<?}?>>
			<a href="../manage/advert_list.php?bmenu=<?=$bmenu?>&smenu=8">광고관리</a>
		</li>
		<li <?if($smenu==9){?>class="on"<?}?>>
			<a href="../sitecon/common_cate_list.php?bmenu=<?=$bmenu?>&smenu=9&s_type=menu">분류관리</a>
		</li>
	</ul>
</aside>