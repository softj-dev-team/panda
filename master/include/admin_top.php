<header id="header">
	<div class="header_top">
		<div class="inner relative">
			<h1>ADMINISTRATOR</h1>
			<div class="user_name">
				<span class="ico"></span>
				<p class="txt"><?= $_SESSION['admin_coinc_name'] ?>님</p>
				<span class="info">관리자</span>
			</div>
			<ul class="btn_wrap clearfix">
				<li class="homepage"><a href="/" target="_blank">HOMEPAGE</a></li>
				<li class="logout"><a href="../login/logout.php">LOG-OUT</a></li>
			</ul>
		</div>
	</div>
	<nav id="gnb">
		<ul class="clearfix">
			<!--<li <? if ($bmenu == 1) { ?>class="on"<? } ?>>
					<a href="../status/today_stat.php?bmenu=1&smenu=1">금일 현황</a>
				</li>-->
			<li <? if ($bmenu == 1) { ?>class="on" <? } ?>>
				<a href="../member/member_list.php?bmenu=1&smenu=1">회원관리</a>
			</li>
			<li <? if ($bmenu == 2) { ?>class="on" <? } ?>>
				<a href="../partner/member_list.php?bmenu=2&smenu=1">가맹점관리</a>
			</li>
			<li <? if ($bmenu == 3) { ?>class="on" <? } ?>>
				<a href="../sms/sms_sample_list.php?bmenu=3&smenu=1">문자관리</a>
			</li>
			<li <? if ($bmenu == 4) { ?>class="on" <? } ?>>
				<a href="../point/member_point_list.php?bmenu=4&smenu=1&point_sect=smspay">결제 관리</a>
			</li>
			<li <? if ($bmenu == 5) { ?>class="on" <? } ?>>
				<a href="../sitecon/popup_list.php?bmenu=5&smenu=1">디자인 관리</a>
			</li>
			<li <? if ($bmenu == 6) { ?>class="on" <? } ?>>
				<a href="../customer/board_list.php?bmenu=6&smenu=1&bbs_code=notice">게시판 관리</a>
			</li>
			<li <? if ($bmenu == 7) { ?>class="on" <? } ?>>
				<a href="../adcount/site_configure.php?bmenu=7&smenu=1">환경설정</a>
			</li>
		</ul>
	</nav>
</header>