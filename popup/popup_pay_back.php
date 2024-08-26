<div class="popup pay_popup" style="display:none;">
	<div class="popup_title">
		<p>결제정보</p>
		<span class="btn_close" onClick="popup_close();"></span>
	</div>
	<div class="popup_con">
		<table>
			<caption>결제정보</caption>
			<colgroup>
				<col style="width:26%;">
				<col style="width:74%;">
			</colgroup>
			<tr>
				<th scope="row">주문번호</th>
				<td>
					<span class="num">13322548</span>
				</td>
			</tr>
			<tr>
				<th scope="row">주문일자</th>
				<td>
					<span class="date">2017-11-28</span>
				</td>
			</tr>
			<tr>
				<th scope="row">총결제금액</th>
				<td>
					<span class="pay">147,000원 / VAT 13,437원 (10%) 포함</span>
				</td>
			</tr>
			<tr>
				<th scope="row">결제수단</th>
				<td>
					<!--계좌이체 -->
					<span class="way">무통장입금</span>
					<!--//계좌이체 -->

					<!-- 신용카드 -->
					<!-- <span class="way">신용카드</span>
					<button type="button" class="btn_pay">영수증 출력</button> -->
					<!-- //신용카드 -->
				</td>
			</tr>
			<!-- 계좌이체 -->
			<tr>
				<th scope="row">입금자명</th>
				<td>
					<span class="name">조현우</span>
				</td>
			</tr>
			<tr>
				<th scope="row">입금은행</th>
				<td>
					<span class="bank">국민은행 003101-04-120207 (예금주 : (주) 가나씨앤피)</span>
				</td>
			</tr>
			<!-- //계좌이체 -->
		</table>
		<div class="pay_btn">
			<button type="button" class="btn_gray">인쇄하기</button>
			<button type="button" onClick="popup_close();" class="btn_white">창 닫기</button>
		</div>
	</div>
</div>