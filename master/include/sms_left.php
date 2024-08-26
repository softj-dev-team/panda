	   <aside id="lnb">
	   	<h2 class="tit"><span>문자관리</span></h2>
	   	<ul class="menu">
	   		<li <? if ($smenu == 1) { ?>class="on" <? } ?>>
	   			<a href="../sms/sms_sample_list.php?bmenu=<?= $bmenu ?>&smenu=1">샘플문자 관리</a>
	   		</li>
	   		<li <? if ($smenu == 2) { ?>class="on" <? } ?>>
	   			<a href="../sms/sms_send_list.php?bmenu=<?= $bmenu ?>&smenu=2">회원별 발송내역 관리</a>
	   		</li>
	   		<li <? if ($smenu == 8) { ?>class="on" <? } ?>>
	   			<a href="../sms/sms_telecom_send_list.php?bmenu=<?= $bmenu ?>&smenu=8">3사 테스트 발송내역 관리</a>
	   		</li>
	   		<li <? if ($smenu == 4) { ?>class="on" <? } ?>>
	   			<a href="../sms/total_sms_send_list.php?bmenu=<?= $bmenu ?>&smenu=4">전체 발송내역 관리</a>
	   		</li>
	   		<li <? if ($smenu == 3) { ?>class="on" <? } ?>>
	   			<a href="../sms/common_cate_list.php?bmenu=<?= $bmenu ?>&smenu=3&s_type=smsmenu">문자 카테고리 관리</a>
	   		</li>
	   		<li <? if ($smenu == 5) { ?>class="on" <? } ?>>
	   			<a href="../sms/sms_080_list.php?bmenu=<?= $bmenu ?>&smenu=5&s_type=smsmenu">080 차단번호 관리</a>
	   		</li>
	   		<li <? if ($smenu == 6) { ?>class="on" <? } ?>>
	   			<a href="../sms/sms_filtering_write.php?bmenu=<?= $bmenu ?>&smenu=6&s_type=smsmenu">필터링 관리</a>
	   		</li>
	   		<li <? if ($smenu == 7) { ?>class="on" <? } ?>>
	   			<a href="../sms/sms_spam_list.php?bmenu=<?= $bmenu ?>&smenu=7&s_type=smsmenu">발신번호(차단) 관리</a>
	   		</li>
	   	</ul>
	   </aside>