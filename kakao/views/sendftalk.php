<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php";
if ($data['my_member_row']['master_ok'] == "N") {
    echo "<script>alert('관리자의 승인 후에 이용이 가능합니다. 관리자 또는 고객센터에 연락 주세요.');history.back();</script>";
}
if ($data['my_member_row']['member_gubun'] == "3") {
    echo "<script>alert('휴면회원은 이용이 불가능합니다. 관리자 또는 고객센터에 연락 주세요.');history.back();</script>";
}
if ($data['my_member_row']['member_gubun'] == "2" && $_REQUEST['send_type'] != "adv") {
    echo "<script>alert('광고문자 회원은 광고문자만 이용 가능합니다.');history.back();</script>";
}
?>
<body>
<div><?php include $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>
    <!-- 레이어 팝업 -->
    <div id="popupLayer" class="popup-layer" style="display:none;">
        <div class="popup-content popcontent">
            <div class="poptitle flex-just-end">
                <button onclick="document.getElementById('popupLayer').style.display = 'none'"><img src="/images/popup/close.svg"></button>
            </div>
            <h2>메세지 전송 결과</h2>
            <div class="tlb center">
                <table>
                    <thead>
                    <tr>
                        <th>메세지 형식</th>
                        <th>전송요청 수</th>
                        <th>전체발송 수</th>
                        <th>중복번호 수</th>
<!--                        <th>수신거부 수</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span id="resultMsgType">단문</span></td>
                        <td><span id="resultSendCnt">0</span> 건</td>
                        <td><span id="resultSendOkCnt">0</span> 건</td>
                        <td><span id="resultSendDupCnt">0</span> 건</td>
<!--                        <td>6명</td>-->
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- 레이어 팝업 -->
    <div id="listResaultPopupLayer" class="popup-layer" style="display:none;">
        <div class="popup-content popcontent">
            <div class="poptitle flex-just-end">
                <button onclick="document.getElementById('listResaultPopupLayer').style.display = 'none'"><img src="/images/popup/close.svg"></button>
            </div>
            <h2>메세지 전송 결과</h2>
            <div class="tlb center">
                <table>
                    <thead>
                    <tr>
                        <th>메세지 형식</th>
                        <th>전송요청 수</th>
                        <th>전체발송 수</th>
                        <th>중복번호 수</th>
                        <!--                        <th>수신거부 수</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span id="listResultMsgType">단문</span></td>
                        <td><span id="listResultSendCnt">0</span> 건</td>
                        <td><span id="listResultSendOkCnt">0</span> 건</td>
                        <td><span id="listResultSendDupCnt">0</span> 건</td>
                        <!--                        <td>6명</td>-->
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- 팝업 레이어 -->
    <div id="sendListPopupLayerList" class="popup-layer" style="display:none;">
        <div class="popup-content">
            <div class="poptitle flex-just-end">
                <button type="button" onclick="closeAllPopup()"><img src="/images/popup/close.svg"></button>
            </div>
            <h2>20건씩 발송하기</h2>
            <h4>체크박스를 선택(Shift + 드래그) 하신 후 선택 발송하셔야 합니다.</h4>
            <div class="flex-just-end">
                <span><span id="sendDoneCnt">0</span>(발송건수) / <span id="sendTotCnt">0</span>(총 발송예정건수)</span>
            </div>
            <div id="data-table"></div>
            <div class="flex-c">
                <button type="button" id="sendSelected" class="btn-t-3 btn-c-3">선택된 항목 발송</button>
            </div>

        </div>
    </div>
    <section class="sub">
        <div class="sub_title">
            <h2>친구톡 보내기</h2>
            <a href="#layer1" class="btn btn-example">
                발송가능건수 확인
            </a>
        </div>

        <form name="sms_frm" id="template-send-form" method="post" target="_fra" enctype="multipart/form-data">
            <input name="message" type="hidden">
            <div class="sms_flex">
                <div class="preview-section">
                    <div id="preview">

                        <div id="previewChannelName">채널명</div>
                        <div id="previewTemplate">
                            <div class="highlight-container">
                                <div class="highlight-header">
                                    <span>알림톡 도착</span>
                                    <div class="jss1382">kakako</div>
                                </div>
                                <div class="highlight-body">
                                    <div class="image-wrapper">
                                        <img id="uploadedImage" src="" alt="Uploaded Logo">
                                        <div class="circle blind" id="removeUploadImageX">
                                            <div class="file-list">
                                                <span>1234444.png</span>
                                                <span>500kb</span>
                                            </div>
                                            <i class="fa-regular fa-circle-xmark"></i>
                                        </div>
                                    </div>
                                    <div class="template-header blind">템플릿 헤더</div>
                                    <div class="highlight-box blind">
                                        <div>
                                            <div class="highlight-title-view blind" >하이라이트 타이틀</div>
                                            <div class="highlight-description-view blind">하이라이트 설명</div>
                                        </div>
                                        <div class="highlight-thumbnail">
                                            <img id="HighlightThumbnailImg" src="">
                                        </div>
                                    </div>
                                    <div class="item-list-box blind">

                                    </div>
                                    <div id="previewStrongSubTitle" class="previewStrongSubTitle"></div>
                                    <div id="previewStrongTitle" class="previewStrongTitle"></div>
                                    <div id="previewHighlightTitle"></div>
                                    <div id="previewHighlightSubtitle"></div>
                                    <div id="previewChButtonList">

                                    </div>
                                    <div id="previeButtonList"></div>
                                </div>
                            </div>
                            <div class="quickLinkList"></div>
                        </div>
                    </div>
                    <p class="preview-note">미리보기는 실제 단말기와 차이가 있을 수 있습니다.</p>
                </div>

                <div class="tlb">

                    <table>
                        <tr>
                            <th>발신프로필</th>
                            <td>
                                <div class="">
                                    <div class="flex-c">
                                        <div class="fm-box w-100">
                                            <select id="f-sel" class="fm-sel" name="profile_id">
                                                <option value="">발신프로필 선택 *</option>
                                            </select>
                                            <span class="fm-error-txt">* 항목을 선택 또는 작성 해 주세요.</span>
                                        </div>
                                        <button class="addChild" type="button"><i class="plusI"></i>발신프로필등록</button>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <th>수신번호</th>
                            <td>
                                <div class="flex-c">
                                    <input type="text" id="cell_receive_dan" class="fm-ipt"><button class="btn" type="button" onclick="add_receive_dan();">추가</button>
                                </div>
                                <div class="sms_are">
                                    <div class="sms_btn_inupt">
                                        <a href="/pandasms_sample.txt" download>텍스트 파일 샘플 다운로드</a>
                                        <a href="/pandasms_sample.xlsx">엑셀 파일 샘플 다운로드</a>
                                    </div>
                                    <div class="sms_btn_inupt" style="margin-top:10px">
                                        <a href="javascript:getTextFile();">텍스트 붙여넣기</a>
                                        <a href="javascript:getExcelFile();">엑셀 붙여넣기</a>
                                        <a href="#layer3" class="btn-example">주소록/직접 붙여넣기</a>
                                    </div>
                                    <input type="file" id="text_file" hidden accept=".txt">
                                    <input type="file" id="excel_file" hidden accept=".xls,.xlsx">
                                    <div class="number_info" id="cell_receive_list">
                                        <!-- 수신번호 리스트 -->
                                    </div>
                                    <div class="number_info_bottom">
                                        총<span id="cell_receive_cnt">0</span>명
<!--                                        <div>-->
<!--                                            직접 추가 총<span id="directPaste">1</span>명 <buttont type="button" data-id="directPaste">삭제</buttont>-->
<!--                                        </div>-->
                                        <div class="addressCount" style="display: none">
                                            수신 번호 추가 <span id="directAdd">0</span> 명 <i type="button" class="fa-solid fa-circle-minus countDelBtn" data-id="directAdd"></i>
                                        </div>
                                        <div class="addressCount" style="display: none">
                                            텍스트 파일 불러오기 <span id="textFileAdd">0</span> 명 <i type="button" class="fa-solid fa-circle-minus countDelBtn" data-id="textFileAdd"></i>
                                        </div>
                                        <div class="addressCount" style="display: none">
                                            엑셀 파일 불러오기 <span id="excelFileAdd">0</span> 명 <i class="fa-solid fa-circle-minus countDelBtn" data-id="excelFileAdd"></i>
                                        </div>
                                        <div class="addressCount" style="display: none">
                                            엑셀 직접 붙여넣기 총 <span id="excelDirectPaste">0</span>명 <i type="button" class="fa-solid fa-circle-minus countDelBtn" data-id="excelDirectPaste"></i>
                                        </div>
                                        <div class="addressCount" style="display: none">
                                            주소록 불러오기 총 <span id="callAddress">0</span>명 <i type="button" class="fa-solid fa-circle-minus countDelBtn" data-id="callAddress"></i>
                                        </div>
                                        <div class="addressCount" style="display: none">
                                            직접 붙여넣기 총 <span id="singleNumberDirectPaste">0</span>명 <i type="button" class="fa-solid fa-circle-minus countDelBtn" data-id="singleNumberDirectPaste"></i>
                                        </div>
                                    </div>

                                </div>

                            </td>
                        </tr>


                        <?
                        $call_num_arr_before = json_decode($data['my_member_row']['call_num'], true);
                        $use_yn_arr = json_decode($data['my_member_row']['use_yn'], true);
                        $call_num_arr = array();
                        for ($i_num = 0; $i_num < sizeof($use_yn_arr); $i_num++) {
                            if ($use_yn_arr[$i_num] == "Y") {
                                array_push($call_num_arr, $call_num_arr_before[$i_num]);
                            }
                        }
                        ?>

                        <tr>
                            <th>발신번호</th>
                            <td>
                                <?php if (sizeof($call_num_arr) == 1) { ?>
                                    <input type="text" id="cell_send" name="cell_send" value="<?= $call_num_arr[0] ?>" required="yes" message="발신번호" readonly>
                                <?php } else { ?>
                                    <select id="cell_send" name="cell_send" required="yes" message="발신번호" class="fm-sel">
                                        <option value="">선택</option>
                                        <? for ($i_num = 0; $i_num < sizeof($call_num_arr); $i_num++) { ?>
                                            <option value="<?= $call_num_arr[$i_num] ?>"><?= $call_num_arr[$i_num] ?></option>
                                        <? } ?>
                                    </select>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="flex-column">
                                    <div class="flex-between">
                                        <div class="flex-just-start">
                                            <div class="flex-c">
                                                <input type="radio" class="fm-radio" name="msgType" value="FT" id="msgTypeFT" checked>
                                                <label class="fm-radio-label" for="msgTypeFT">첨부안함</label>
                                            </div>
                                            <div class="flex-c">
                                                <input type="radio" class="fm-radio" name="msgType" value="FI" id="msgTypeFI">
                                                <label class="fm-radio-label" for="msgTypeFI">기본 이미지</label>
                                            </div>
                                            <div class="flex-c">
                                                <input type="radio" class="fm-radio" name="msgType" value="FW" id="msgTypeFW">
                                                <label class="fm-radio-label" for="msgTypeFW">와이드 이미지</label>
                                            </div>
                                        </div>
                                        <div class="flex-just-end">
                                            <div class="flex-c">
                                                <input type="checkbox" id="adFlag" class="fm-chk" name="adFlag"><label for="adFlag" class="fm-chk-i"><strong>광고성 정보가 포함되어 있습니다.</strong></label>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="custom-input-container">
                                        <label for="template_title" class="fm-label custom-label">메세지 내용 * (<span id="charCount">0/1000</span>)</label>
                                        <textarea name="template_title" id="highlightTitle" class="fm-ta" placeholder="템플릿내용은 한/영 구분없이 1,000자까지 입력 가능합니다. 변수에 들어갈 내용의 최대 길이를 감안하여 작성해 주세요."></textarea>
                                        <span id="errorMsg" class="fm-error-txt" >* <span class="currentLength">1000</span>자를 초과할 수 없습니다.</span>
                                    </div>
                                    <div class="w-100" id="templateImageUploadForm" >
                                        <div class="fm-box">
                                            <input name="file" type="file" id="f-attach" data-fakefile="file" />
                                            <label for="f-attach" class="fm-file-btn ">파일첨부</label>
                                            <input type="text" data-fakefile="text" readonly="readonly" placeholder="" class="fm-ipt fm-file" />
                                        </div>
                                        <div class="image-valid">
                                            <p>- 파일형식 및 크기 : jpg, png / 최대 500KB</p>
                                            <p></p>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="flex-just-start">
                                        <button type="button" class="btn-t-3 btn-c-4" id="addVariableBtn">변수추가</button>
                                        <button type="button" class="btn-t-3 btn-c-4" id="openSpecialCharPopup">특수문자</button>
                                        <button type="button" class="btn-t-3 btn-c-4" id="openKkoIconPopup">이모티콘</button>
                                        <button type="button" class="btn-t-3 btn-c-4 addButton" data-id="buttons">버튼추가 (0/5)</button>
                                    </div>
                                    <div class="fm-box flex-c regexToMessageBind">

                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="flex-column">
                                    <div class="flex-just-start">
                                        <span><input type="checkbox" id="f-chk-all" class="fm-chk" name="smssendyn"><label for="f-chk-all" class="fm-chk-i"><strong>대체문자 사용</strong></label></span>
                                        <p>알림톡 발송이 실패 된 경우, 해당 내용을 문자로 대체 발송하여 누락을 방하는 기능입니다.</p>
                                    </div>
                                    <div class="custom-input-container">
                                        <div class="fm-box blind">
                                            <label for="template_title" class="fm-label custom-label">메세지 제목 * (<span class="charCount">0/40</span>)</label>
                                            <input class="fm-ipt" name="subject" >
                                            <span class="fm-error-txt errorMsg" >* 40자를 초과할 수 없습니다.</span>
                                        </div>
                                    </div>
                                    <div class="custom-input-container">
                                        <div class="fm-box">
                                            <label for="template_title" class="fm-label custom-label ">메세지 내용 * (<span class="charCount">0/2000</span>)</label>
                                            <textarea name="smsmemo" placeholder="내용을 입력해 주세요." id="f-des" class="fm-ta messageInput guide-tab-cont" data-chkarea="case1" "></textarea>
                                            <span class="fm-error-txt errorMsg" >* 2000자를 초과할 수 없습니다.</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div id="buttonListContainer">
                        <!-- 버튼 목록 -->
                        <div id="buttonList" class="buttonList">
                            <!-- 버튼이 추가될 영역 -->
                        </div>
                        <!-- 버튼 목록 -->
                        <div id="quickList" class="buttonList">
                            <!-- 버튼이 추가될 영역 -->
                        </div>
                    </div>
                    <div class="flex-just-end">

                        <button type="button" class="btn-t-2 btn-c-2" id="sendFtalkForm">발송하기</button>
                    </div>

                </div>
            </div>

        </form>
        <!-- 모달 레이어 -->
        <div id="variableModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <label for="variableName" class="fm-label">변수명:</label>

                <input type="text" id="variableName" class="fm-ipt">
                <p>&nbsp;</p>
                <div class="flex-c">
                    <button type="button" id="insertVariableBtn" class="btn-t-2 btn-c-4">변수추가</button>
                </div>
            </div>
        </div>
        <div id="specialCharPopup" class="templatePopup" style="display: none;">
            <div class="popup-content">
                <span class="close closePopup">&times;</span>
                <div id="specialCharList"></div>
            </div>
        </div>
        <div id="kkoIconPopup" class="templatePopup" style="display: none;">
            <div class="popup-content">
                <span class="close closePopup">&times;</span>
                <div id="kko_icon_list"></div>
            </div>
        </div>
        <!-- 버튼 추가 레이어 팝업 -->
        <div id="buttonPopup" style="display: none;">
            <div class="popup-content kakao-button-popup-box">
                <h3>새 버튼 추가</h3>
                <div class="custom-input-container">
                    <select class="fm-sel" name="linkType" id="linkType" >
                        <option value="">버튼종류선택 * </option>
                        <option value="WL">웹링크</option>
                        <option value="AL">앱링크</option>
                        <option value="MD">메시지전달</option>
<!--                        <option value="BK">봇키워드</option>-->
<!--                        <option value="DS">배송조회</option>-->
<!--                        <option value="BT">봇전환</option>-->
<!--                        <option value="BC">상담톡전환</option>-->
<!--                   -->
<!--                        <option value="P1">이미지 보안 전송 플러그인</option>-->
<!--                        <option value="P2">개인정보이용 플러그인</option>-->
<!--                        <option value="P3" disabled="" style="display: none;">원클릭결제 플러그인</option>-->
<!--                        <option value="BF" >비즈니스폼</option>-->
                    </select>
                </div>
                <div class="fm-box custom-input-container">
                    <label for="buttonName" class="custom-label">버튼명(<span>0/14</span>)</label>
                    <input type="text" id="buttonName" placeholder="버튼명을 입력하세요" class="fm-ipt custom-input">
                    <span class="fm-error-txt blind" >* 14자를 초과할 수 없습니다.</span>
                </div>

                <div class="fm-box custom-input-container blind" data-id="AL">
                    <label for="linkAnd" class="custom-label">android 링크</label>
                    <input type="text" id="linkAnd" placeholder="https://" class="fm-ipt custom-input">
                </div>
                <div class="fm-box custom-input-container blind" data-id="AL">
                    <label for="linkIos" class="custom-label">ios 링크</label>
                    <input type="text" id="linkIos" placeholder="https://" class="fm-ipt custom-input">
                </div>


                <div class="fm-box custom-input-container blind" data-id="WL">
                    <label for="linkMo" class="custom-label">모바일링크</label>
                    <input type="text" id="linkMo" placeholder="https://" class="fm-ipt custom-input">
                </div>
                <div class="fm-box custom-input-container blind" data-id="WL">
                    <label for="linkPc" class="custom-label">PC링크 (선택 사항)</label>
                    <input type="text" id="linkPc" placeholder="https://" class="fm-ipt custom-input">
                </div>
                <div class="fm-box custom-input-container blind" data-id="BF">
                    <label for="bizFormId" class="custom-label">비지니스폼ID</label>
                    <input type="text" id="bizFormId" placeholder="" class="fm-ipt custom-input">
                </div>
                <div class="fm-box custom-input-container blind" data-id="P1">
                    <label for="pluginId" class="custom-label">플러그인ID</label>
                    <input type="text" id="pluginId" placeholder="" class="fm-ipt custom-input">
                </div>
                <div class="flex-just-start" style="flex: 1">
                    <button id="saveButton" type="button" class="btn-t-3 btn-c-3">버튼 만들기</button>
                    <button id="cancelButton" type="button" class="btn-t-3 btn-c-3">취소</button>
                </div>

            </div>
        </div>
        <div class="point_pop">
            <h2>
                <span><img src="/images/popup/point.svg"></span>
                불법스팸안내
            </h2>
            <ul class="list_ul">
                <li>불법스팸을 발송하는 경우 문자 발송이 곧바로 중지되며 발송금액 및 충전금액은 환불되지 않습니다</li>

            </ul>

            <div class="emt30"></div>


            <h2>
                <span><img src="/images/popup/point.svg"></span>
                불법스팸이란
            </h2>
            <ul class="list_ul">
                <li>도박, 불법대출, 음란성인물, 불법의약품 등의 내용을 보내는것을 말합니다.
                </li>

            </ul>

            <div class="emt30"></div>


            <h2>
                <span><img src="/images/popup/point.svg"></span>
                알아두세요!
            </h2>
            <ul class="list_ul">
                <li>수신번호 추가 시 중복번호, 형식에 맞지 않는 번호는 자동 제거됩니다.</li>
                <li>발신번호는 사전에 등록된 번호중에서만 이용자의 선택이가능며,<br>
                    거짓으로 표시된 발신번호로 전송하는 경우 "변작번호로 판별되어 관련 법령에 따라 문자 발송 차단" 이 됨을 알려드립니다.</li>
                <li>발송되는 모든 번호는 중복 체크되며, 중복된 번호는 자동적으로 제거 됩니다.</li>

            </ul>
        </div>

    </section>

    <!--발송건수확인-->

    <div id="layer1" class="pop-layer">
        <div class="pop-container samll">
            <div class="popcontent">
                <div class="poptitle">
                    <h2>
                        발송가능건수
                    </h2>
                    <a href="#" class="btn-layerClose close">
                        <img src="/images/popup/close.svg">
                    </a>
                </div>

                <div class="pop_flex">
                    <ul>
                        <li>
                            <p class="left">단문문자<span>단가 <?= number_format($data['my_member_row']['mb_short_fee'],2) ?>원</span></p>
                            <p class="right"><?= number_format($data['my_member_row']['current_point'] / $data['my_member_row']['mb_short_fee']) ?>건</p>
                        </li>
                        <li>
                            <p class="left">장문문자<span>단가 <?= number_format($data['my_member_row']['mb_long_fee'],2) ?>원</span></p>
                            <p class="right"><?= number_format($data['my_member_row']['current_point'] / $data['my_member_row']['mb_long_fee']) ?>건</p>
                        </li>
                        <li>
                            <p class="left">이미지문자<span>단가 <?= number_format($data['my_member_row']['mb_img_fee'],2) ?>원</span></p>
                            <p class="right"><?= number_format($data['my_member_row']['current_point'] / $data['my_member_row']['mb_img_fee']) ?>건</p>
                        </li>
                        <li>
                            <p class="left">알림톡</b><span>단가 <?= number_format($data['my_member_row']['mb_kko_fee'],2) ?>원</span></p>
                            <p class="right"><?= number_format($data['my_member_row']['current_point'] / $data['my_member_row']['mb_kko_fee']) ?>건</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div id="layer3" class="pop-layer">
        <div class="pop-container">
            <div class="popcontent">
                <div class="poptitle">
                    <h2>
                        받는사람 추가하기
                    </h2>
                    <a href="#" class="btn-layerClose close">
                        <img src="/images/popup/close.svg">
                    </a>
                </div>

                <ul class="tabs wide">
                    <li class="tab-link current" data-tab="tab-6">주소록 불러오기</li>
                    <li class="tab-link" data-tab="tab-7">직접 붙여넣기</li>
                    <li class="tab-link" data-tab="tab-8">엑셀 붙여넣기</li>
                </ul>

                <div id="tab-6" class="tab-content current">

                    <div class="point_pop samll">

                        <ul class="list_ul">
                            <li>수정 및 변경은 주소록 메뉴에서 가능합니다.</li>

                        </ul>

                    </div>

                    <div class="tab_btn_are">
                        <div class="btn">
                            <a href="#" style="background: #666; color: #fff">그룹</a>
                            <!--<a href="#">개인</a>-->
                        </div>
                        <div class="input_tab">
                            <input type="text">
                            <a href="#none">
                                <img src="/images/search.png">
                            </a>
                        </div>
                    </div>


                    <div class="tlb center">
                        <table>
                            <thead>
                                <tr>
                                    <th class="check"><input type="checkbox" name="checkNum" id="checkNum"></th>
                                    <th>그룹명</th>
                                    <th>그룹 인원수</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php forEach ($data['result_group'] as $row_group) { // 대분류 루프 시작?>
                                    <tr>
                                        <td class="check"><input type="checkbox" name="check_group" value="<?= $row_group["idx"] ?>"></td>
                                        <td><?= $row_group["group_name"] ?></td>
                                        <td><?= $row_group["group_cnt"] ?>명</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>


                </div>

                <div id="tab-7" class="tab-content">

                    <textarea placeholder="입력방법 : 01000000001,01000000002" class="top25 h200" id="text_add_val"></textarea>

                    <div class="point_pop">
                        <h2>
                            <span><img src="/images/popup/point.svg"></span>
                            알림
                        </h2>
                        <ul class="list_ul">
                            <li>최대 50,000개까지 등록할 수 있습니다.</li>
                            <li>핸드폰 번호는 엔터(Enter)또는 콤마(,)로 구분하여 입력해야 합니다.</li>
                        </ul>

                    </div>

                </div>
                <div id="tab-8" class="tab-content">

                    <div class="tlb center xcel">
                        <table id="excel_copy">

                        </table>
                    </div>

                    <div class="point_pop">
                        <h2>
                            <span><img src="/images/popup/point.svg"></span>
                            알림
                        </h2>
                        <ul class="list_ul">
                            <li>최대 100,000개까지 등록할 수 있습니다.</li>
                            <li>이름, 전화번호 순으로 등록해 주세요.</li>
                        </ul>
                    </div>
                </div>
                <div class="btn_are_pop">
                    <a href="#" class="btn-layerClose btn btn02" id="text_add_btn">
                        추가
                    </a>
                    <a href="#" class="btn-layerClose btn">
                        닫기
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

    <script>

        $(document).ready(function() {
            $('ul.tabs li').click(function() {
                var tab_id = $(this).attr('data-tab');

                $('ul.tabs li').removeClass('current');
                $('.tab-content').removeClass('current');

                $(this).addClass('current');
                $("#" + tab_id).addClass('current');
            });

        });

        var table = new Tabulator("#excel_copy", {
            height: "311px",
            data: [],
            placeholder: "복사(Ctrl+C)한 내용을 여기에 붙여넣기(Ctrl+V) 해주세요.",
            clipboard: true,
            clipboardPasteAction: "update",
            columns: [{
                    title: "이름",
                    field: "name",
                    width: 104,
                },
                {
                    title: "전화번호",
                    field: "number",
                    width: 104,
                    sorter: "number",
                },
                {
                    title: "key",
                    field: "key",
                    visible: false,  // key 열을 숨김
                    defaultValue: "excelDirectPaste",  // key 열에 기본값 설정
                },
            ],

            clipboardPasteParser: function(pasteData) {
                // 붙여넣기 데이터를 가공하는 부분
                // pasteData는 텍스트로 붙여넣어진 데이터를 가공하는 과정입니다.

                let parsedData = pasteData.split("\n").map(function(row) {
                    let values = row.split("\t");  // 붙여넣기에서 각 열의 값을 탭으로 구분

                    // 데이터가 올바르게 분할되었을 때만 처리
                    if (values.length > 1) {
                        return {
                            name: values[0],
                            number: values[1],
                            key: "excelDirectPaste",  // key 값을 수동으로 추가
                        };
                    }
                });

                // 유효한 데이터만 필터링 (빈 데이터 제외)
                return parsedData.filter(Boolean);
            }
        });

        let isReplacingData = false;  // 중복 실행 방지를 위한 플래그

        table.on("dataLoading", function(data) {
            if (isReplacingData) return;  // 이미 데이터가 로드 중이면 중단

            if (data.length) {
                // 데이터 배열을 필터링하여 number가 있는 요소만 남기고, 숫자로 정제함
                let cleanedData = data.filter(function(elem, index) {
                    if (elem.number) {
                        // number가 문자열인지 확인하고, 문자열이 아닌 경우 문자열로 변환
                        if (typeof elem.number !== 'string') {
                            elem.number = String(elem.number);  // 문자열로 변환
                        }

                        // 숫자 이외의 문자를 모두 제거
                        elem.number = elem.number.replace(/\D/g, '');
                        return true;  // number가 있으면 유지
                    } else {
                        return false;  // number가 없으면 필터링하여 제외
                    }
                });

                // 중복 실행 방지 플래그 설정
                isReplacingData = true;
                // 정제된 데이터를 다시 테이블에 반영
                table.replaceData(cleanedData).then(function() {
                    // 데이터가 성공적으로 대체된 후, 플래그를 해제
                    isReplacingData = false;
                }).catch(function(err) {
                    // 오류 발생 시 플래그 해제
                    isReplacingData = false;
                    console.error("Error replacing data:", err);
                });
            }
        });


        function go_sendinfo_view() {
            $("#layer1").show();
        }

        function go_msg_save() {
            var form = document.getElementById('sms_frm');
            var formData = new FormData(form);
            var check = chkFrm('sms_frm');
            if (check) {
                var result = confirm("문자내용을 저장 합니다.하겠습니까?" );
                if (result) {
                    formData.append('transmit_type', 'save');
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 'error') {
                                // 예외 처리 메시지 표시
                                alert(response.message);
                            } else if (response.status === 'success') {
                                alert(response.message);
                            }
                        } else {
                            alert('서버와의 통신 중 오류가 발생했습니다.');
                        }
                    };
                    xhr.send(formData);
                }else {
                    return;
                }
            }
        }



        function validate() {
            var list = table.getData();

            if (list.length == 0) {
                alert("수신번호를 입력해 주세요.");
                return false;
            }

            if ($('input[name=reserv_yn]:checked').val() == "Y") {
                if ($("#reserv_date").val() == "") {
                    alert("예약날짜를 입력해 주세요.");
                    return false;
                }
                if ($("#reserv_time").val() == "") {
                    alert("예약시간을 입력해 주세요.");
                    return false;
                }
                if ($("#reserv_minute").val() == "") {
                    alert("예약분을 입력해 주세요.");
                    return false;
                }
            }
            return true;
        }


        function add_receive_dan() {
            var cell_receive_dan = $("#cell_receive_dan").val();

            if (cell_receive_dan == "") {
                alert("추가할 번호를 입력해 주세요.");
                return;
            } else {
                // 숫자만 포함되도록 정규표현식을 사용하여 검사합니다.
                var numericPattern = /^[0-9]+$/;
                if (!numericPattern.test(cell_receive_dan)) {
                    alert("숫자만 입력해 주세요.");
                    return;
                }

                // 자리수 확인
                if (cell_receive_dan.length == 10 || cell_receive_dan.length == 11) {
                } else {
                    //alert("변작번호로 판별되어 관련 법령에 따라 문자 발송이 차단됩니다.");
                    alert("자리수가 맞지 않습니다.");
                    return;
                }

                // "011" 또는 "017" 로 시작하는지 확인
                if (cell_receive_dan.startsWith("010") || cell_receive_dan.startsWith("017")) {
                } else {
                    alert("변작번호로 판별되어 관련 법령에 따라 문자 발송이 차단됩니다.");
                    return;
                }

                let newData = {

                    number: cell_receive_dan,
                    key: "directAdd"
                };
                let list = table.getData();

                let isDuplicate = list.some(function (item) {
                    return item.number === newData.number;
                });
                if(!isDuplicate){
                    table.addData([newData], true);
                }else{
                    alert("수신 목록에 이미 존재하는 번호입니다.");
                    return;
                }
                let tableList = table.getData();
                let count = tableList.filter(function(item) {
                    return item.key === 'directAdd';  // key가 'textFileAdd'인 항목만 필터링
                }).length;
                //table.replaceData(list);//데이터 비우고 다시추고
                if (tableList.length > 300000) {
                    alert('최대 300,000개까지 등록할 수 있습니다.');
                } else {

                    $('#directAdd').text(count);
                    $('#directAdd').parent().show();
                    $('#cell_receive_cnt').text(tableList.length);
                }

            }
        }



        function getTextFile() {
            $('#text_file').click();
        }
        // 엑셀에 붙여넣기
        function getExcelFile() {
            $('#excel_file').click();
        }

        // 텍스트 불러오기
        $("#text_file").on('change', function() {
            showLoadingSpinner();
            $('#cell_receive_list').html('');
            $('#cell_receive_cnt').text('0');
            let ext = $("#text_file").val().split(".").pop().toLowerCase();
            if ($.inArray(ext, ["txt"]) == -1) {
                alert("텍스트 파일만 첨부 가능합니다.");
                $("#text_file").val("");
                return false;
            } else {
                readText(async function(result) {
                    //alert(result);
                    let list = result.split((/,| |\r\n/));
                    let listCount = 0
                    let newData = {};
                    if (list.length > 300000) {
                        alert('최대 300,000개까지 등록할 수 있습니다.');
                    } else {
                        list.forEach(item => {
                            newData = {
                                number: item,
                                key: "textFileAdd"
                            };
                            let isDuplicate = list.some(function (item) {
                                return item.number === newData.number;
                            });
                            if(!isDuplicate){
                                listCount = listCount + 1;
                                table.addData([newData], true);
                            }
                        });

                        let tableList = table.getData();
                        let count = tableList.filter(function(item) {
                            return item.key === 'textFileAdd';  // key가 'textFileAdd'인 항목만 필터링
                        }).length;
                        hideLoadingSpinner()
                        $('#textFileAdd').text(count);
                        $('#textFileAdd').parent().show();

                        $('#cell_receive_cnt').text(tableList.length);
                    }
                });
            }
            $(this).val('');

        });

        $("#text_add_btn").click(async function() {
            if ($("#tab-7").hasClass('current')) {

                let list = $("#text_add_val").val().replaceAll('-', '').split(/\,|\s+|\n/);
                let listCount = 0
                let newData = {};
                if (list.length > 300000) {
                    alert('최대 300,000개까지 등록할 수 있습니다.');
                } else {
                    console.log(list)
                    list.forEach(item => {
                        newData = {
                            number: item,
                            key: "singleNumberDirectPaste"
                        };
                        let isDuplicate = list.some(function (item) {
                            return item.number === newData.number;
                        });
                        if(!isDuplicate){
                            listCount = listCount + 1;
                            table.addData([newData], true);
                        }
                    });
                    let tableList = table.getData();
                    let count = tableList.filter(function(item) {
                        return item.key === 'singleNumberDirectPaste';
                    }).length;
                    $('#singleNumberDirectPaste').text(count);
                    $('#singleNumberDirectPaste').parent().show();
                    $('#cell_receive_cnt').text(tableList.length);

                }
            } else if ($("#tab-6").hasClass('current')) {
                const arr = [];
                // 체크한 항목만 취득
                var check_group = $("input[name='check_group']:checked");
                $(check_group).each(function() {
                    arr.push($(this).val());
                });
                if (arr.length == 0) {
                    alert('그룹을 선택해주세요.');
                    return false;
                } else {
                    var list_test = [];

                    $.ajax({
                        url: "/kakao/index.php?route=getAddressSendNumber",
                        type: "GET",
                        data: {
                            group_idx: arr.join(","),
                        },
                        async: true,
                        dataType: "json",
                        beforeSend: function() {
                            // AJAX 요청이 시작되기 전에 로딩 스피너를 보여줌
                            showLoadingSpinner();
                        },
                        success: function(response) {
                            var data = response.data
                            let listCount = 0;
                            let newDataList = [];  // 새로운 데이터를 담을 배열
                            let existingNumbers = new Set();  // 중복 체크를 위한 Set

                            // 기존 테이블 데이터에서 이미 추가된 번호를 Set에 저장
                            let tableList = table.getData();
                            tableList.forEach(item => {
                                if (item.key === 'callAddress') {
                                    existingNumbers.add(item.number);
                                }
                            });

                            // 새로운 데이터를 처리
                            data.forEach(item => {
                                let newNumber = item.receive_num;
                                // 중복된 전화번호가 없을 때만 처리
                                if (!existingNumbers.has(newNumber)) {
                                    let newData = {
                                        name: item.receive_name,
                                        number: newNumber,
                                        key: "callAddress"
                                    };
                                    newDataList.push(newData);
                                    listCount++;
                                    existingNumbers.add(newNumber);  // 중복 방지를 위해 Set에 추가
                                }
                            });

                            if (newDataList.length > 0) {

                                var tableData =table.getData();
                                if(tableData.length > 0){
                                    tableData.forEach(item=>{
                                        let newData = {
                                            name: item.name,
                                            number: item.number,
                                            key:item.key
                                        };
                                        newDataList.push(newData);
                                    })
                                }
                                table.setData(newDataList);
                                console.log(table.getData());
                            }

                            // 카운트 계산 및 업데이트
                            let count = table.getData().filter(function(item) {
                                return item.key === 'callAddress';
                            }).length;

                            $('#callAddress').text(count);
                            $('#callAddress').parent().show();
                            $('#cell_receive_cnt').text(table.getData().length);
                            // 로딩 스피너 숨김
                            hideLoadingSpinner();
                        },

                        error: function(xhr, status, error) {
                            hideLoadingSpinner();
                            alert('데이터를 가져오는 중 오류가 발생했습니다.');
                            console.error('Error: ' + error);
                            console.error('Status: ' + status);
                            console.dir(xhr);
                        }
                    });
                }

            } else if ($("#tab-8").hasClass('current')) {

                let list = table.getData();

                if (list.length > 300000) {
                    alert('최대 300,000개까지 등록할 수 있습니다.');
                } else {

                    let tableList = table.getData();
                    let count = tableList.filter(function(item) {
                        return item.key === 'excelDirectPaste';  // key가 'textFileAdd'인 항목만 필터링
                    }).length;
                    $('#excelDirectPaste').text(count );
                    $('#excelDirectPaste').parent().show();


                    $('#cell_receive_cnt').text(tableList.length);
                }
            }

        });
        function deleteDataByKey(key) {
            // 현재 테이블의 데이터를 가져옴
            let currentData = table.getData();

            // 해당 key를 가진 데이터를 제외한 나머지 데이터를 필터링
            let filteredData = currentData.filter(function (item) {
                return item.key !== key;  // 특정 key가 아닌 데이터만 남김
            });

            // 필터링된 데이터를 테이블에 반영
            table.replaceData(filteredData);
            $(''+key).text(table.getData().length)
            $(''+key).parent().hide();
        }
        $(".countDelBtn").on('click',function (){
            // 현재 테이블의 데이터를 가져옴
            let currentData = table.getData();
            var key = $(this).data('id');
            let filteredData = currentData.filter(function (item) {
                return item.key !== key;  // 특정 key가 아닌 데이터만 남김
            });
            // 필터링된 데이터를 테이블에 반영
            table.replaceData(filteredData);
            $('#'+key).parent().hide();
            currentData = table.getData();
            $('#cell_receive_cnt').text(currentData.length);
        })
        // 엑셀 불러오기
        $("#excel_file").on('change', function() {
            showLoadingSpinner();
            $('#cell_receive_list').html('');
            $('#cell_receive_cnt').text('0');
            let ext = $("#excel_file").val().split(".").pop().toLowerCase();

            // 파일 확장자 체크
            if ($.inArray(ext, ["xls", "xlsx"]) == -1) {
                alert("엑셀 파일만 첨부 가능합니다.");
                $("#excel_file").val("");
                hideLoadingSpinner();
                return false;
            } else {
                readExcel(function(result) {
                    let listCount = 0;
                    let newDataList = [];
                    let existingNumbers = new Set();

                    // 타이틀 체크
                    if (Object.keys(result[0]).includes('HP')) {
                        if (result.length > 300000) {
                            alert('최대 300,000개까지 등록할 수 있습니다.');
                            hideLoadingSpinner();
                            return;
                        }

                        // 데이터 청크 처리 함수
                        function processDataInChunks(data, processChunk, onComplete) {
                            let index = 0;
                            let chunkSize = 1000; // 청크 크기 조절

                            function nextChunk() {
                                if (index < data.length) {
                                    let end = Math.min(index + chunkSize, data.length);
                                    let chunk = data.slice(index, end);
                                    processChunk(chunk);
                                    index = end;
                                    setTimeout(nextChunk, 0); // 다음 청크 스케줄링
                                } else {
                                    onComplete();
                                }
                            }
                            nextChunk();
                        }

                        // 청크 처리 함수
                        function processChunk(chunk) {
                            chunk.forEach(item => {
                                let newData = {
                                    name: item.NAME,
                                    number: item.HP,
                                    key: "excelFileAdd"
                                };

                                // 중복 체크
                                if (!existingNumbers.has(newData.number)) {
                                    newDataList.push(newData);
                                    existingNumbers.add(newData.number);
                                    listCount++;
                                }
                            });
                        }

                        // 완료 후 호출 함수
                        function onComplete() {

                            if (newDataList.length > 0) {

                                var tableData =table.getData();
                                if(tableData.length > 0){
                                    tableData.forEach(item=>{
                                        let newData = {
                                            name: item.name,
                                            number: item.number,
                                            key:item.key
                                        };
                                        newDataList.push(newData);
                                    })
                                }
                                table.setData(newDataList);
                            }
                            // 카운트 업데이트
                            let tableList = table.getData();
                            let count = tableList.filter(function(item) {
                                return item.key === 'excelFileAdd';
                            }).length;

                            $('#excelFileAdd').text(count);
                            $('#excelFileAdd').parent().show();
                            $('#cell_receive_cnt').text(tableList.length);

                            hideLoadingSpinner();
                        }

                        // 청크 단위로 데이터 처리 시작
                        processDataInChunks(result, processChunk, onComplete);

                    } else {
                        alert('엑셀 양식을 참고해주세요.\n헤더는 [이름 = NAME, 번호 = HP]이 되어야합니다.');
                        hideLoadingSpinner();
                    }
                });
            }

            $(this).val('');
        });

        // 전체 선택 버튼
        function allSelectBtn() {
            if ($('input[name=receive_cell_num]').length > $('input[name=receive_cell_num]:checked').length) {
                $('input[name=receive_cell_num]').prop('checked', true);
            } else {
                $('input[name=receive_cell_num]').prop('checked', false);
            }
        }

        // 연락처 삭제 버튼
        function abDelete() {
            $('input[name=receive_cell_num]:checked').each(function(idx, el) {
                let parentIndex = $(this).parent().index();
                $('#cell_receive_list').find('div').eq(parentIndex).remove();
            });
            $('#cell_receive_cnt').text($('#cell_receive_list').find('div').length);
        }

        async function checkDuplicateExcel(result_arr) {
            const newArray = result_arr.filter((item, i) => {
                console.log(item);
                return (
                    result_arr.findIndex((item2, j) => {
                        return item.HP === item2.HP;
                    }) === i
                );
            });
            return newArray;
        }

        async function checkDuplicateExcelCopy(result_arr) {
            const newArray = result_arr.filter((item, i) => {
                console.log(item);
                return (
                    result_arr.findIndex((item2, j) => {
                        return item['number'] === item2['number'];
                    }) === i
                );
            });
            return newArray;
        }

        async function checkDuplicateText(result_arr) {
            const set = new Set(result_arr);
            console.log(set);
            const uniqueArr = [...set];
            return uniqueArr;
        }

        function cleanNumber(number) {
            // number가 undefined, null, 또는 숫자형인 경우를 처리
            if (typeof number !== 'string') {
                number = String(number); // 숫자형이나 다른 타입이면 문자열로 변환
            }

            // 특수문자 및 공백 제거
            let cleanedNumber = number.replace(/[^\d]/g, '').trim();  // 숫자만 남기고 공백 제거

            // 만약 번호가 '010'으로 시작하지 않고 10자리라면 '010'을 앞에 붙여줌
            if (cleanedNumber.length === 10 && cleanedNumber.startsWith('1')) {
                cleanedNumber = '0' + cleanedNumber;
            }

            return cleanedNumber;
        }
        function deleteDuplicateTable(){
            // 모든 데이터를 가져옴
            let data = table.getData();

            // 중복된 number 값을 확인하기 위한 객체
            let seenNumbers = {};
            // 특수문자 및 공백을 제거하는 함수
            let duplicateCount = 0;

            // 중복된 데이터를 필터링
            let filteredData = data.filter(function(item) {

                let cleanedNumber = cleanNumber(item.number);

                // 만약 이미 seenNumbers에 해당 number가 있으면 중복으로 간주
                if (seenNumbers[cleanedNumber]) {
                    duplicateCount++;
                    return false;  // 중복된 데이터는 필터링해서 제외
                } else {
                    seenNumbers[cleanedNumber] = true;  // 중복되지 않은 number는 기록
                    return true;  // 중복되지 않은 데이터만 남김
                }
            });

            // 중복이 제거된 데이터를 테이블에 다시 반영
            table.replaceData(filteredData);
            return duplicateCount;
        }
        function deleteDuplicate() {
            var boxes = $('#cell_receive_list .box');

            // 중복된 data-hp 값을 찾기 위한 빈 객체를 생성합니다.
            var seen = {};

            // 각 .box 요소를 순회하면서 data-hp 값을 확인합니다.
            boxes.each(function() {
                var dataHp = $(this).find('span').data('hp');

                // 이미 동일한 data-hp 값을 가진 요소가 있는 경우 .box를 제거합니다.
                if (seen[dataHp]) {
                    $(this).remove();
                } else {
                    // 중복되지 않은 경우, seen 객체에 해당 data-hp 값을 추가합니다.
                    seen[dataHp] = true;
                }
            });
        }

        function ban() {
            var titleContents = $("#sms").val();
            var ban_word_list = [];
            var word_list = <?= json_encode($filteringArray) ?>;
            for (var i = 0; i < word_list.length; i++) {
                if (titleContents.indexOf(word_list[i]) > -1) {
                    if (ban_word_list.indexOf('"' + word_list[i] + '"') < 0) {
                        ban_word_list.push('"' + word_list[i] + '"');
                    }
                }
            }

            if (ban_word_list.length > 0) {
                alert("입력하신 제목과 내용에 금칙어인 " + ban_word_list.join(", ") + "를 포함하고 있습니다.");
                return false;
            } else {
                return true;
            }
        }

        // 현재 페이지의 모든 행의 status가 01인지 확인하는 함수
        function checkAllStatusAndMoveToNextPage() {
            let currentData = popupTable.getSelectedData();  // 현재 페이지의 데이터만 가져옴
            let allStatusUpdated = currentData.every(item => item.status === "01");
            // 모든 status가 01인 경우 다음 페이지로 이동
            if (allStatusUpdated) {
                let currentPage = popupTable.getPage();
                let totalPages = popupTable.getPageMax();

                if (currentPage < totalPages) {
                    popupTable.setPage(currentPage + 1);  // 다음 페이지로 이동
                }
            }
        }
        let popupTable;
        // 팝업 열기 함수
        $('#openSendListPopupButton').click(function() {

            let tableData = table.getData();

            if(tableData.length <= 0){
                alert("수신번호를 입력해 주세요.");
            }else{
                $('#sendListPopupLayerList').show()
                // 기본값 00 설정
                if(!popupTable) {
                    tableData.forEach((item, index) => {
                        if (!item.id) {
                            item.id = index + 1;  // index 기반으로 id 필드를 추가
                        }
                        if (!item.status) {
                            item.status = "00";  // 발송여부 기본값 00 설정
                        }
                    });
                }else{
                    tableData = popupTable.getData();
                }

                $('#sendTotCnt').text(Number(tableData.length).toLocaleString());

                popupTable = new Tabulator("#data-table", {
                    data: tableData, // table.getData()로 가져온 데이터를 사용
                    layout: "fitColumns",
                    pagination: "true",  // 로컬 페이징
                    paginationSize: 20,   // 20건씩 표시
                    selectable: true,  // 다중 선택 가능
                    selectableRangeMode: "drag",  // 마우스 드래그로 범위 선택
                    selectablePersistence: false,  // 페이징 변경 후에도 선택 상태 유지
                    selectableRollingSelection:true,
                    selectableCheck: function(row) {
                        // 모든 행을 선택 가능하게 설정
                        return true;
                    },
                    columns: [
                        { title: "선택", field: "select", formatter: "rowSelection", width: 50, hozAlign: "center", headerSort: false, cellClick: function(e, cell) {
                                // cellClick 이벤트는 필요 없을 수 있습니다.
                            }},
                        { title: "전화번호", field: "number", sorter: "string" },
                        { title: "이름", field: "name", sorter: "string" },
                        { title: "발송여부", field: "status", formatter: function(cell, formatterParams) {
                                // 발송여부 값을 텍스트로 변환하여 표시
                                let value = cell.getValue();
                                return value === "00" ? "미발송" : (value === "01" ? "발송완료" : value);
                            }}
                    ],
                    dataChanged: function(data) {
                        checkAllStatusAndMoveToNextPage();  // 데이터 변경 시 자동 페이지 이동 체크
                        console.log(123)
                    },
                    tableBuilt: function() {
                        checkAllStatusAndMoveToNextPage();  // 테이블이 처음 렌더링될 때 자동 페이지 이동 체크
                    },
                });
            }
        });
    </script>

</body>

</html>