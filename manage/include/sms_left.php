	   <aside id="lnb">
			<h2 class="tit"><span>문자관리</span></h2>
			<ul class="menu">
				<li <?if($smenu==1){?>class="on"<?}?>>
					<a href="../sms/sms_sample_list.php?bmenu=<?=$bmenu?>&smenu=1">샘플문자 관리</a>
				</li>
				<li <?if($smenu==2){?>class="on"<?}?>>
					<a href="../sms/sms_send_list.php?bmenu=<?=$bmenu?>&smenu=2">발송내역 관리</a>
				</li>
				<li <?if($smenu==3){?>class="on"<?}?>>
					<a href="../sms/common_cate_list.php?bmenu=<?=$bmenu?>&smenu=3&s_type=smsmenu">문자 카테고리 관리</a>
				</li>
			</ul>
		</aside>
		