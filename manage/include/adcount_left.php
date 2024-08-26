	   <aside id="lnb">
			<h2 class="tit"><span>환경설정</span></h2>
			<ul class="menu">
				<li <?if($smenu==1){?>class="on"<?}?>>
					<a href="../adcount/site_configure.php?bmenu=<?=$bmenu?>&smenu=1">사이트 환경설정</a>
				</li>
				<li <?if($smenu==2){?>class="on"<?}?>>
					<a href="../adcount/sms_configure.php?bmenu=<?=$bmenu?>&smenu=2">SMS 환경설정</a>
				</li>
				<li <?if($smenu==3){?>class="on"<?}?>>
					<a href="../adcount/member_view.php?bmenu=<?=$bmenu?>&smenu=3">내 정보관리</a>
				</li>
				<!--<li <?if($smenu==2){?>class="on"<?}?>>
					<a href="../adcount/adminm_list.php?bmenu=<?=$bmenu?>&smenu=2&s_group=SUB">운영자 계정 관리</a>
				</li>-->
			</ul>
		</aside>
		