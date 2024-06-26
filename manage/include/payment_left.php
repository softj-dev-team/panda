		<aside id="lnb">
			<h2 class="tit"><span>결제내역 관리</span></h2>
			<ul class="menu">
				<li <?if($smenu == 1){?>class="on""<?}?>>
						<a href="../payment/order_list.php?bmenu=<?=$bmenu?>&smenu=1&v_sect=com">입금완료 리스트</a>
				</li>
				<li <?if($smenu == 2){?>class="on"<?}?>>
						<a href="../payment/order_list.php?bmenu=<?=$bmenu?>&smenu=2&v_sect=pre">입금대기 리스트</a>
				</li>
				<!--<li <?if($smenu == 3){?>class="on"<?}?>>
						<a href="../payment/order_list.php?bmenu=<?=$bmenu?>&smenu=3&v_sect=reing">취소신청 리스트</a>
				</li>-->
				<li <?if($smenu == 4){?>class="on"<?}?>>
						<a href="../payment/order_list.php?bmenu=<?=$bmenu?>&smenu=4&v_sect=can">취소완료 리스트</a>
				</li>
			</ul>
		</aside>