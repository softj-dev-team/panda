<div class="popup pay_popup" style="display:none;" id="popup_pay_area">
	<!-- popup_pay_area.php 에서 불러옴 -->
</div>

<script>
	function kgmob_card_rec(trd_no, vat) {
        var url = "https://www.mcash.co.kr/cp/sales_detail/PrintReceipt_CN.php?trd_no="+trd_no +"&vat="+vat;
        var win = window.open(url, "mcashBill", "toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=no,resizable=no,width=620,height=700,top=0,left=150");
        if(win.focus) win.focus();
	}
</script>
