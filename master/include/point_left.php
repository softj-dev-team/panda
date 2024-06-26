	  <aside id="lnb">
	  	<h2 class="tit"><span>결제 관리</span></h2>
	  	<ul class="menu">
	  		<li <? if ($smenu == 1) { ?>class="on" <? } ?>>
	  			<a href="../point/member_point_list.php?bmenu=<?= $bmenu ?>&smenu=1&point_sect=smspay">충전금액 내역</a>
	  		</li>
	  		<li <? if ($smenu == 2) { ?>class="on" <? } ?>>
	  			<a href="../point/member_point_charge_list.php?bmenu=<?= $bmenu ?>&smenu=2&point_sect=smspay">입출금 관리 / 결제내역</a>
	  		</li>
	  		<!--<li <? if ($smenu == 3) { ?>class="on"<? } ?>>
					<a href="../point/member_coupon_list.php?bmenu=<?= $bmenu ?>&smenu=3">쿠폰 내역 조회</a>
				</li>-->
	  	</ul>
	  </aside>