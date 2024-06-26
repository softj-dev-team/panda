<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? {include "../inc/head.php";} ?>
 <body>
	<div class="pop_post">
		<h2 class="title">우편번호검색 </h2>
		<div class="check_cont">
			<p class="tip">찾고 싶으신 주소의 동(읍/면) 이름을 입력하세요.<br/>예) 신사동, 청담1동, 교하읍, 한강로1가</p>
			<div class="btn_search">
				<span>지역명</span>
				<input type="text" style="width:178px; height:28px;" class="mr7" />
				<a href="#" ><img src="../img/popup/btn_search.gif" alt="검색" /></a>
			</div>
		</div>
		<div class="check_search">
			<p class="tip">검색결과 중 해당 주소를 클릭 하시면 자동 입력됩니다.</p>
			<h3 class="bg_table"><span class="w20">우편번호</span><span class="w80">주소</span></h3>
			<!-- 검색결과 전 -->
			<p class="cont ">지역명을 입력한 후 검색해 주세요.<p>
			<!-- //검색결과 전 -->
			<!-- 검색결과 후 -->
			<div class="table_roll hide">
				<table>
					<col width="20%;" />
					<col width="80%;" />
					<tbody>
						<tr>
							<td class="text_center">463-030</td>
							<td>경기 성남시 분당구 분당동1</td>
						</tr>
						<tr>
							<td class="text_center">463-030</td>
							<td>경기 성남시 분당구 분당동2</td>
						</tr>
						<tr>
							<td class="text_center">463-030</td>
							<td>경기 성남시 분당구 분당동3</td>
						</tr>
						<tr>
							<td class="text_center">463-030</td>
							<td>경기 성남시 분당구 분당동4</td>
						</tr>
						<tr>
							<td class="text_center">463-030</td>
							<td>경기 성남시 분당구 분당동5</td>
						</tr>
						<tr>
							<td class="text_center">463-030</td>
							<td>경기 성남시 분당구 분당동6</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- //검색결과 후 -->
		</div>
		<a href="#" class="btn_clos"><img src="../img/common/btn_close.gif" alt="닫기"/></a>
	</div>
 </body>
</html>
