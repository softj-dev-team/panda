<div class="popup order_popup" style="display:none">
	<div class="popup_title">
		<p>주문 정보 입력</p>
		<span class="btn_close" onClick="popup_close();"></span>
	</div>
	<div class="popup_con">
		<ul class="popup_tab">
			<li data-tab="tab1" class="on">직접 업로드</li>
			<li data-tab="tab2">웹하드 업로드</li>
		</ul>
		<div class="tab_con">
			<div id="tab1" class="tab1 on">
				<div class="file_box">
					<label for="btn_file1">파일 찾기</label>
					<input type="file" id="btn_file1">
					<input type="text" id="" name="" value="">
				</div>
				<span class="txt">
					* 데이터가 여러개인 경우, 폴더 하나로 압축해서 업로드 해주세요.<br>
					직접 업로드의 최대 파일 용량은 200MB 입니다. 초과시 웹하드 업로드를 이용해주세요.
				</span>
				<textarea name="" id="" placeholder="작업 메모"></textarea>
			</div>
			<div id="tab2" class="tab2">
				<span class="txt">* Webhard.co.kr 접수 후 아이디 : indigoworld / 비밀번호 : 1234</span>
				<span class="txt">* 웹하드 게스트 폴더 내에 “성함 + 핸드폰 번호 (예 : 조현우 010-3424-7022)”로 폴더 생성 후 데이터를 업로드해주세요.</span>
				<div class="file_box">
					<label for="btn_file2">파일 찾기</label>
					<input type="file" id="btn_file2">
					<input type="text" id="" name="" value="">
				</div>
				<textarea name="" id="" placeholder="작업 메모"></textarea>
			</div>
		</div>
		<div class="pop_radio">
			<ul>
				<li>
					<input type="radio" id="type_1" name="popup_type">
					<label for="type_1">시안 확인 후 인쇄</label>
				</li>
				<li>
					<input type="radio" id="type_2" name="popup_type">
					<label for="type_2">시안 확인 안하고 인쇄</label>
				</li>
			</ul>
		</div>
		<div class="pop_btn">
			<button type="button" class="btn_red">장바구니 담기</button>
			<button type="button" class="btn_gray">계속 주문</button>
		</div>
	</div>
</div>