	  <aside id="lnb">
			<h2 class="tit"><span>의뢰내용 관리</span></h2>
			<ul class="menu">
				<li <?if($smenu==1){?>class="on"<?}?>>
					<a href="../request/board_list.php?bmenu=<?=$bmenu?>&smenu=1&bbs_code=request&v_sect=market">마케팅 의뢰</a>
				</li>
				<li <?if($smenu==2){?>class="on"<?}?>>
					<a href="../request/board_list.php?bmenu=<?=$bmenu?>&smenu=2&bbs_code=request&v_sect=design">디자인 의뢰</a>
				</li>
				<li <?if($smenu==3){?>class="on"<?}?>>
					<a href="../request/board_list.php?bmenu=<?=$bmenu?>&smenu=3&bbs_code=request&v_sect=makem">제조/OEM/ODM 의뢰</a>
				</li>
			</ul>
		</aside>