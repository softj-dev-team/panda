<!--<div class="popup kakao_popup" style="display:none;">-->
<div class="popup kakao_popup">
	<div class="popup_title">
		<p></p>
		<span class="btn_close" onClick="popup_close();"></span>
	</div>
	<div class="popup_con">
<!--		<img src="../images/common/kakao.jpg" alt="kakao">-->
		<form action="http://info.sweettracker.co.kr/tracking/1" method="post">
            <div class="form-group">
<!--              <label for="t_key">API key</label>-->
              <input type="hidden" class="form-control" id="t_key" name="t_key" value="iKZDiwXLflnl08Vfe2QE2Q">
            </div>
            <div class="form-group">
              <label for="t_code">택배사 코드</label>
              <input type="text" class="form-control" name="t_code" id="t_code" value="06">
            </div>
            <div class="form-group">
              <label for="t_invoice">운송장 번호</label>
              <input type="text" class="form-control" name="t_invoice" id="t_invoice" placeholder="운송장 번호">
            </div>
            <button type="submit" class="btn btn-default">조회하기</button>
        </form>
	</div>
	<div>
		<iframe name ="iframe1" width="740" height="800" scrolling="auto" frameborder="0" src = "https://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceView.aspx?gubun=slipno&id=&slipno=91102048332"></iframe>
	</div>
</div>