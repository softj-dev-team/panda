<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php";

?>
<style>

</style>
<body>
<!--header-->
<div><?php include $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>
    <div class="container-kko">
        <div class="preview-section">
            <h2>템플릿 등록</h2>
            <h2>&nbsp;</h2>
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
                            </div>
                            <div id="previewStrongSubTitle" class="previewStrongSubTitle"></div>
                            <div id="previewStrongTitle" class="previewStrongTitle"></div>
                            <div id="previewHighlightTitle"></div>
                            <div id="previewHighlightSubtitle"></div>
                        </div>
                    </div>
                    <div class="quickLinkList"></div>
                </div>
            </div>
            <p class="preview-note">미리보기는 실제 단말기와 차이가 있을 수 있습니다.</p>
        </div>

        <div class="form-section">
            <form id="requestTemplate" enctype="multipart/form-data" >
                <div class="fm-wrap w-100">
                    <div class="fm-row flex">
                        <div class="fm-box w-100">

                            <select id="f-sel" class="fm-sel" name="profile_id">
                                <option value="">발신프로필 선택 *</option>
                            </select>
                            <span class="fm-error-txt">* 항목을 선택 또는 작성 해 주세요.</span>
                        </div>
                        <button class="addChild" type="button"><i class="plusI"></i>발신프로필등록</button>
                    </div>

                    <div class="channel-link">

                        <select id="category" name="category_id" class="fm-sel">
                            <option value="">카테고리 * </option>
                        </select>
                        <span class="fm-error-txt">* 항목을 선택 또는 작성 해 주세요.</span>
                    </div>

                    <div class="flex-just-start fm-row">

                        <div class="fm-box w-fm-title">
                            <select id="template_type" class="fm-sel" name="template_type">
                                <option value="">메세지 유형</option>
                                <option value="BA">기본형</option>
                                <option value="EX">부가정보형</option>
                                <option value="AD">채널추가형</option>
                                <option value="MI">복합형</option>
                                <!--                            <option value="">NEWS</option>-->
                            </select>
                            <span class="fm-error-txt">* 항목을 선택 또는 작성 해 주세요.</span>
                        </div>
                        <div class="fm-box custom-input-container mgl-5">
                            <label for="templateName" class="custom-label">템플릿 이름 *</label>
                            <input type="text" id="templateName" name="template_name" class="fm-ipt custom-input" placeholder="템플릿 명 *">
                            <span class="fm-error-txt">* 항목을 선택 또는 작성 해 주세요.</span>
                        </div>

                    </div>
                    <div class="flex-just-start fm-row">
                        <div class="fm-box w-fm-title"">
                            <select id="f-search-sel-3" class="fm-sel" name="template_emphasize_type">
                                <option value="">강조 유형</option>
                                <option value="NONE">선택안함</option>
                                <option value="TEXT">강조표기형</option>
                                <option value="IMAGE">이미지형</option>
                            </select>
                            <span class="fm-error-txt">* 항목을 선택 또는 작성 해 주세요.</span>
                        </div>
                        <div id="viewStrongMessage" class="blind flex w-100">
                            <div class="fm-box custom-input-container mgl-5">
                                <label for="strong_title" class="custom-label">강조 타이틀 *</label>
                                <input type="text" id="strong_title" name="strong_title" class="fm-ipt custom-input" placeholder="강조 타이틀">
                                <span class="fm-error-txt ">* 항목을 선택 또는 작성 해 주세요.</span>
                            </div>
                            <div class="fm-box custom-input-container mgl-5">
                                <label for="strong_sub_title" class="custom-label">강조 보조 문구 *</label>
                                <input type="text" id="strong_sub_title" name="strong_sub_title" class="fm-ipt custom-input" placeholder="강조 보조 문구 *">
                                <span class="fm-error-txt ">* 항목을 선택 또는 작성 해 주세요.</span>
                            </div>
                        </div>

                        <div class="fm-box mgl-5 w-100 blind" id="templateImageUploadForm" >
                            <input name="file" type="file" id="f-attach" data-fakefile="file" />
                            <label for="f-attach" class="fm-file-btn ">파일첨부</label>
<!--                            <input type="hidden" name="selectedImage" id="selectedImage">-->
                            <input type="text" data-fakefile="text" readonly="readonly" placeholder="파일 사이즈 최대 500KB" class="fm-ipt fm-file" />

                        </div>

                    </div>
                    <div class="fm-box-row">
                        <input type="checkbox" id="f-chk-all" class="fm-chk" checked="checked" name="securityFlag">
                        <label for="f-chk-all" class="fm-chk-i"><strong>보안 템플릿 설정</strong> 체크 시, 메인 디바이스 모바일 외 모든 서브 디바이스에서는 메시지 내용이 노출되지 않습니다</label>
                    </div>
                    <div class="fm-row">
                        <div id="guide">
                        </div>
                        <div class="custom-input-container">
                            <label for="template_title" class="fm-label custom-label">메세지 내용 * (<span id="charCount">0/1000</span>)</label>
                            <textarea name="template_title" id="highlightTitle" class="fm-ta" placeholder="템플릿내용은 한/영 구분없이 1,000자까지 입력 가능합니다. 변수에 들어갈 내용의 최대 길이를 감안하여 작성해 주세요."></textarea>

                            <span id="errorMsg" class="fm-error-txt blind" >* 1000자를 초과할 수 없습니다.</span>
                        </div>
                        <div class="flex-just-start">
                            <button type="button" class="btn-t-3 btn-c-4" id="addVariableBtn">변수추가</button>
                            <button type="button" class="btn-t-3 btn-c-4" id="openSpecialCharPopup">특수문자</button>
                            <button type="button" class="btn-t-3 btn-c-4" id="openKkoIconPopup">이모티콘</button>
                            <button type="button" class="btn-t-3 btn-c-4 addButton" data-id="buttons">버튼추가 (0/5)</button>
                            <button type="button" class="btn-t-3 btn-c-4 addButton" data-id="quickReplies">바로연결 추가 (0/5)</button>
                        </div>
                    </div>
                    <div class="fm-row">
                        <div id="typeEX" class="blind custom-input-container">
                            <label for="f-title" class="fm-label custom-label">부가정보 </label>
                            <textarea name="template_subtitle" id="highlightSubtitle" class="fm-ta"></textarea>
                            <span class="fm-error-txt">* 항목을 선택 또는 작성 해 주세요.</span>
                        </div>
                    </div>
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

                    <!-- 버튼 추가 레이어 팝업 -->
                    <div id="buttonPopup" style="display: none;">
                        <div class="popup-content">
                            <h3>새 버튼 추가</h3>
                            <div class="custom-input-container">
                                <select class="fm-sel" name="linkType" id="linkType" >
                                    <option value="">버튼종류선택 * </option>
                                    <option value="WL">웹링크</option>
                                    <option value="AL">앱링크</option>
                                    <option value="MD">메시지전달</option>
                                    <option value="BK">봇키워드</option>
                                    <option value="DS">배송조회</option>
                                    <option value="BT">봇전환</option>
                                    <option value="BC">상담톡전환</option>
                                    <option value="AC" style="display: none;" disabled="">채널 추가</option>
                                    <option value="P1">이미지 보안 전송 플러그인</option>
                                    <option value="P2">개인정보이용 플러그인</option>
                                    <option value="P3" disabled="" style="display: none;">원클릭결제 플러그인</option>
                                    <option value="BF" >비즈니스폼</option>
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
                            <div class="flex-just-start">
                                <button id="saveButton" type="button" class="btn-t-3 btn-c-3">버튼 만들기</button>
                                <button id="cancelButton" type="button" class="btn-t-3 btn-c-3">취소</button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="flex-c">
                    <button class="btn-t btn-c" type="submit">템플릿 등록 완료</button>
                </div>
            </form>
        </div>


    </div>
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

<!--    <script src="/kakao/public/js/kakao.js"></script>-->
    <script>
        $('input[name="template_type"]').change(function() {
            showGuide();
        });
        $(document).ready(function() {
            $('#highlightTitle').on('input', function() {
                var currentLength = $(this).val().length;

                if(currentLength <= 1000){
                    $('#charCount').text(currentLength + "/1000");
                }
                if (currentLength > 1000) {
                    $('#errorMsg').removeClass("blind");
                    $('#errorMsg').addClass("active");
                    $(this).val($(this).val().substring(0, 1000));  // 글자수 제한
                } else {
                    $('#errorMsg').addClass("blind");
                }
            });
            $('#buttonName').on('input', function() {
                var currentLength = $(this).val().length;
                if(currentLength <= 14){
                    $(this).prev('label').find('span').text(currentLength + "/14");
                }
                if (currentLength > 14) {
                    $(this).next('span').removeClass("blind");
                    $(this).next('span').addClass("active");
                    $(this).val($(this).val().substring(0, 14));  // 글자수 제한
                } else {
                    $(this).next('span').addClass("blind");
                }
            });
            $('#f-attach').on('change', function(event) {
                const file = event.target.files[0];
                const inputForm = $('#templateImageUploadForm');
                if (file) {
                    // 이미지 형식 확인
                    if (file.type !== 'image/png' && file.type !== 'image/jpeg') {
                        alert('이미지는 png 또는 jpg 형식이어야 합니다.');
                        // inputForm.addClass('fm-error');
                        // inputForm.nextAll('span').addClass('active');
                        inputForm.placeholder.text('이미지는 png 또는 jpg 형식이어야 합니다.')
                        $('#uploadedImage').hide();
                        $('#f-attach').val(''); // 파일 입력 필드 초기화
                        return; // 이미지 처리를 중단합니다.
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgElement = $('#uploadedImage');

                        // 이미지 조건 확인
                        const img = new Image();
                        img.onload = function() {
                            if (img.width <= 800 && img.height <= 400) {
                                imgElement.attr('src', e.target.result);
                                imgElement.show();
                            } else {
                                alert('이미지 크기는 800x400px 이하여야 합니다.');
                                imgElement.hide();
                                $('#f-attach').val(''); // 파일 입력 필드 초기화
                                // inputForm.addClass('fm-error');
                                // inputForm.nextAll('span').addClass('active');
                                // inputForm.nextAll('span').text('이미지 크기는 800x400px 이하여야 합니다.')
                            }
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
            $('select[name=template_type]').on('change', function() {
                if($(this).val()=="EX"){
                    $('#typeEX').removeClass("blind");
                }else{
                    $('#typeEX').addClass("blind");
                }
            });
            $('select[name=template_emphasize_type]').on('change', function() {
                var emphasizeType = $(this).val();
                var $viewStrongMessage = $('#viewStrongMessage');
                var $strongTitle = $('#strong_title');
                var $strongSubTitle = $('#strong_sub_title');
                var $templateImageUploadForm = $('#templateImageUploadForm');
                var imgElement = $('#uploadedImage');
                switch(emphasizeType) {
                    case "TEXT":
                        // Show strong message elements and hide image upload form
                        $viewStrongMessage.removeClass("blind");
                        $strongTitle.removeClass("blind");
                        $strongSubTitle.removeClass("blind");
                        $templateImageUploadForm.addClass("blind");
                        imgElement.hide();
                        break;

                    case "IMAGE":
                        // Show image upload form and hide strong message elements
                        $templateImageUploadForm.removeClass("blind");
                        $viewStrongMessage.addClass("blind");
                        $strongTitle.addClass("blind");
                        $strongSubTitle.addClass("blind");
                        break;

                    default:
                        // Hide all elements
                        $viewStrongMessage.addClass("blind");
                        $strongTitle.addClass("blind");
                        $strongSubTitle.addClass("blind");
                        $templateImageUploadForm.addClass("blind");
                        break;
                }
            });
            $('#strong_sub_title,#strong_title').on('input',function (){
                $('#previewStrongSubTitle').text($('#strong_sub_title').val())
                $('#previewStrongTitle').text($('#strong_title').val())
                changeStrongTitle()
            });

            function changeStrongTitle(){
                if($('#strong_sub_title').val() || $('#strong_title').val()){
                    $('#previewHighlightTitle').css('border-top', '1px solid #bbb');
                    $('#previewHighlightTitle').css('font-weight', '300');
                }else{
                    $('#previewHighlightTitle').css('border-top', 'none');
                }
            }
        });
        /**
         * 버튼 추가
         * */
        $(document).ready(function () {
            let buttons = [];
            let quickReplies = [];
            const maxButtons = 5;
            const maxQuickReplies = 5;

            const buttonLinkTypes = {
                'WL': '웹링크',
                'AL': '앱링크',
                'MD': '메시지전달',
                'DS': '배송조회',
                'BT': '봇전환',
                'BC': '상담톡전환',
                'AC': '채널 추가',
                'P1': '이미지 보안 전송 플러그인',
                'P2': '개인정보이용 플러그인',
                'P3': '원클릭결제 플러그인',
            };

            const quickReplyLinkTypes = {
                'WL': '웹링크',
                'AL': '앱링크',
                'MD': '메시지전달',
                'BT': '봇전환',
                'BK': '봇키워드',
            };

            // 버튼 추가 클릭 이벤트 (linkType, quickReplies 구분)
            $('.addButton').on('click', function () {
                const targetType = $(this).data('id'); // linkType 또는 quickReplies 구분
                if (targetType === 'buttons') {
                    if (buttons.length >= maxButtons) {
                        alert('최대 5개의 버튼만 추가할 수 있습니다.');
                        return;
                    }
                    showPopup(targetType); // 버튼 추가 팝업
                } else if (targetType === 'quickReplies') {
                    if (quickReplies.length >= maxQuickReplies) {
                        alert('최대 5개의 바로연결만 추가할 수 있습니다.');
                        return;
                    }
                    showPopup(targetType); // 바로연결 추가 팝업
                }
            });

            // 팝업을 열 때 resetPopupFields()로 필드 초기화
            function showPopup(type, index = null, update = false, linkType= null) {
                const popup = $('#buttonPopup');
                let statusText='';
                if(update){
                    statusText='수정';
                }else{
                    statusText='추가';
                }
                if(type==='buttons'){
                    $('.popup-content h3').html('버튼 '+statusText);
                }else{
                    $('.popup-content h3').html('바로가기 '+statusText);
                }
                if(!update){
                    resetPopupFields();  // 필드 초기화
                }

                populateLinkTypeOptions(type,linkType); // 셀렉트박스 옵션 설정
                popup.show();

                // 저장 버튼 클릭 이벤트 처리
                $('#saveButton').off('click').on('click', function () {
                    if (type === 'buttons') {
                        saveButton(index);  // 버튼 저장
                    } else if (type === 'quickReplies') {
                        saveQuickReply(index);  // 바로연결 저장
                    }
                });
            }

            // linkType 셀렉트 박스에 옵션을 동적으로 채우는 함수
            function populateLinkTypeOptions(type,linkType=null) {
                const linkTypeSelect = $('#linkType');
                linkTypeSelect.empty();  // 기존 옵션 제거

                const linkTypes = type === 'buttons' ? buttonLinkTypes : quickReplyLinkTypes;

                linkTypeSelect.append('<option value="">버튼종류선택 *</option>');  // 기본 선택 옵션 추가
                $.each(linkTypes, function (value, label) {
                    linkTypeSelect.append(`<option value="${value}">${label}</option>`);
                });
                if(linkType){
                    $('#linkType').val(linkType).trigger('change');
                }
            }

            // 팝업 초기화 시 입력 필드를 기본값으로 설정
            function resetPopupFields() {
                // $('#linkType').val('');  // linkType 초기화
                $('#buttonName').val('');  // 버튼명 초기화
                $('#linkMo').val('');  // 모바일링크 초기화
                $('#linkPc').val('');  // PC링크 초기화
                $('#linkAnd').val('');  // Android 링크 초기화
                $('#linkIos').val('');  // iOS 링크 초기화
                $('#bizFormId').val('');  // 비즈니스폼ID 초기화
                $('#pluginId').val('');  // 플러그인ID 초기화

                var linkType = $("#linkType option:selected").val();
                $('.popup-content [data-id]').each(function() {
                    var dataId = $(this).data('id');
                    // linkType이 "P1" 또는 "P2"일 때 data-id="P1"인 요소를 표시
                    if ((linkType === "P1" || linkType === "P2") && dataId === "P1") {
                        $(this).removeClass('blind');
                        $(this).addClass('custom-input-container');
                    } else if (dataId === linkType) {
                        $(this).removeClass('blind');
                        $(this).addClass('custom-input-container');
                    } else {
                        $(this).addClass('blind');
                        $(this).removeClass('custom-input-container');
                    }
                });
            }

            // linkType 셀렉트 박스 변경 시 동적으로 입력 필드를 변경
            $('#linkType').on('change', function () {
                var linkType = $("#linkType option:selected").val();
                $('.popup-content [data-id]').each(function() {
                    var dataId = $(this).data('id');
                    // linkType이 "P1" 또는 "P2"일 때 data-id="P1"인 요소를 표시
                    if ((linkType === "P1" || linkType === "P2") && dataId === "P1") {
                        $(this).removeClass('blind');
                        $(this).addClass('custom-input-container');
                    } else if (dataId === linkType) {
                        $(this).removeClass('blind');
                        $(this).addClass('custom-input-container');
                    } else {
                        $(this).addClass('blind');
                        $(this).removeClass('custom-input-container');
                    }
                });
            });

            // 버튼 저장 함수
            function saveButton(index = null) {
                const buttonData = getFormData();
                if (!buttonData.name) {
                    alert('버튼명을 입력해주세요.');
                    return;
                }

                if (index !== null) {
                    // 기존 버튼 수정
                    buttons[index] = buttonData;
                } else {
                    // 새 버튼 추가
                    buttons.push(buttonData);
                }

                updateButtonList('buttons');
                hidePopup();
            }

            // 바로연결 저장 함수
            function saveQuickReply(index = null) {
                const quickReplyData = getFormData();
                if (!quickReplyData.name) {
                    alert('바로연결명을 입력해주세요.');
                    return;
                }

                if (index !== null) {
                    quickReplies[index] = quickReplyData;
                } else {
                    quickReplies.push(quickReplyData);
                }

                updateButtonList('quickReplies');
                hidePopup();
            }

            // 폼 데이터를 가져오는 함수
            function getFormData() {
                return {
                    name: $('#buttonName').val(),
                    linkType: $('#linkType').val(),
                    linkMo: $('#linkMo').val(),
                    linkPc: $('#linkPc').val(),
                    linkAnd: $('#linkAnd').val(),
                    linkIos: $('#linkIos').val(),
                    bizFormId: $('#bizFormId').val(),
                    pluginId: $('#pluginId').val()
                };
            }

            // 버튼 리스트 업데이트 함수 (linkType, quickReplies 구분)
            function updateButtonList(type) {
                const buttonList = $('#buttonList');
                const quickList = $('#quickList');
                buttonList.empty(); // 기존 리스트 초기화

                const isQuickReply = type === 'quickReplies';
                const items = isQuickReply ? quickReplies : buttons;
                const linkTypeMapping = isQuickReply ? quickReplyLinkTypes : buttonLinkTypes;
                $('.generated-button').remove();
                $('.button-item').remove();
                $.each(buttons, function (index, item) {
                    let inputFields = generateInputFields(item, index, false);

                    const buttonItem = $(`
                        <div class="button-item">
                            <div>
                                <strong>[버튼]${item.name}</strong>
                                <span>${buttonLinkTypes[item.linkType]}</span>
                                ${inputFields}
                            </div>
                            <div class="button-actions">
                                <button class="editButton" type="button" data-index="${index}" data-type="buttons">수정</button>
                                <button class="deleteButton" type="button" data-index="${index}" data-type="buttons">삭제</button>
                            </div>
                        </div>
                    `);

                    $('#previewHighlightSubtitle').after($(`<button class="generated-button jss2034">${item.name}</button>`));
                    buttonList.append(buttonItem);

                });
                $.each(quickReplies, function (index, item) {
                    let inputFields = generateInputFields(item, index, true);

                    const buttonItem = $(`
                        <div class="button-item">
                            <div>
                                <strong>[바로연결]${item.name}</strong>
                                <span>${quickReplyLinkTypes[item.linkType]}</span>
                                ${inputFields}
                            </div>
                            <div class="button-actions">
                                <button class="editButton" type="button" data-index="${index}" data-type="quickReplies">수정</button>
                                <button class="deleteButton" type="button" data-index="${index}" data-type="quickReplies">삭제</button>
                            </div>
                        </div>
                    `);

                    $('.quickLinkList').append($(`<button class="generated-button jss877">${item.name}</button>`));
                    quickList.append(buttonItem);

                });
                // 버튼 추가 상태 업데이트
                console.log(buttons.length)
                $('.addButton[data-id="buttons"]').text(`+ 버튼추가 (${buttons.length}/${maxButtons})`);
                $('.addButton[data-id="quickReplies"]').text(`+ 바로연결 추가 (${quickReplies.length}/${maxQuickReplies})`);
            }

            // 버튼 입력 필드 생성 함수
            function generateInputFields(item, index, isQuickReply) {
                if (isQuickReply) {
                    switch (item.linkType) {
                        case 'WL':  // 웹링크
                            return `
                                  <input type="hidden" name="quickReplies_name[]" value="${item.name}">
                                <input type="hidden" name="quickReplies_linkType[]" value="${item.linkType}">
                                <input type="hidden" name="quickReplies_ordering[]" value="${index}">
                                <input type="hidden" name="quickReplies_linkMo[]" value="${item.linkMo}">
                                <input type="hidden" name="quickReplies_linkPc[]" value="${item.linkPc}">
                            `;
                        case 'AL':  // 앱링크
                            return `
                                <input type="hidden" name="quickReplies_name[]" value="${item.name}">
                                <input type="hidden" name="quickReplies_linkType[]" value="${item.linkType}">
                                <input type="hidden" name="quickReplies_ordering[]" value="${index}">
                                <input type="hidden" name="quickReplies_linkAnd[]" value="${item.linkAnd}">
                                <input type="hidden" name="quickReplies_linkIos[]" value="${item.linkIos}">
                            `;
                        default:
                            return `
                               <input type="hidden" name="quickReplies_name[]" value="${item.name}">
                                <input type="hidden" name="quickReplies_linkType[]" value="${item.linkType}">
                                <input type="hidden" name="quickReplies_ordering[]" value="${index}">
                            `;
                    }
                } else {
                    switch (item.linkType) {
                        case 'WL':  // 웹링크
                            return `
                                <input type="hidden" name="name[]" value="${item.name}">
                                <input type="hidden" name="postLinkType[]" value="${item.linkType}">
                                <input type="hidden" name="ordering[]" value="${index}">
                                <input type="hidden" name="linkMo[]" value="${item.linkMo}">
                                <input type="hidden" name="linkPc[]" value="${item.linkPc}">
                            `;
                        case 'AL':  // 앱링크
                            return `
                                <input type="hidden" name="name[]" value="${item.name}">
                                <input type="hidden" name="postLinkType[]" value="${item.linkType}">
                                <input type="hidden" name="ordering[]" value="${index}">
                                <input type="hidden" name="linkAnd[]" value="${item.linkAnd}">
                                <input type="hidden" name="linkIos[]" value="${item.linkIos}">
                            `;
                        case 'MD':
                            return `
                                    <input type="hidden" name="name[]" value="${item.name}">
                                    <input type="hidden" name="postLinkType[]" value="${item.linkType}">
                                    <input type="hidden" name="ordering[]" value="${index}">
                                `;
                        case 'BF':
                            return `
                                    <input type="hidden" name="name[]" value="${item.name}">
                                    <input type="hidden" name="postLinkType[]" value="${item.linkType}">
                                    <input type="hidden" name="ordering[]" value="${index}">
                                   <input type="hidden" name="bizFormId[]" value="${item.bizFormId}">
                                `;
                        case 'P1':
                            return `
                                    <input type="hidden" name="name[]" value="${item.name}">
                                    <input type="hidden" name="postLinkType[]" value="${item.linkType}">
                                    <input type="hidden" name="ordering[]" value="${index}">
                                   <input type="hidden" name="pluginId[]" value="${item.bizFormId}">
                                `;
                        case 'P2':
                            return `
                                    <input type="hidden" name="name[]" value="${item.name}">
                                    <input type="hidden" name="postLinkType[]" value="${item.linkType}">
                                    <input type="hidden" name="ordering[]" value="${index}">
                                   <input type="hidden" name="pluginId[]" value="${item.bizFormId}">
                                `;
                        default:

                            return `
                                <input type="hidden" name="name[]" value="${item.name}">
                                <input type="hidden" name="postLinkType[]" value="${item.linkType}">
                                <input type="hidden" name="ordering[]" value="${index}">
                            `;
                    }
                }
            }

            // 수정 버튼 클릭 시 처리
            $(document).on('click', '.editButton', function () {
                const index = $(this).data('index');
                const type = $(this).data('type');
                const item = type === 'buttons' ? buttons[index] : quickReplies[index];
                console.log(item)
                // $('#linkType').val(item.linkType).trigger('change');
                // $('#linkType').val(item.linkType);
                $('#buttonName').val(item.name);
                $('#linkMo').val(item.linkMo);
                $('#linkPc').val(item.linkPc);
                $('#linkAnd').val(item.linkAnd);
                $('#linkIos').val(item.linkIos);
                $('#bizFormId').val(item.bizFormId);
                $('#pluginId').val(item.pluginId);

                showPopup(type, index, true, item.linkType ); // 수정 팝업 열기
            });

            // 삭제 버튼 클릭 시 처리
            $(document).on('click', '.deleteButton', function () {
                const index = $(this).data('index');
                const type = $(this).data('type');

                if (type === 'buttons') {
                    buttons.splice(index, 1); // 버튼 삭제
                } else {
                    quickReplies.splice(index, 1); // 바로연결 삭제
                }
                updateButtonList(type); // 리스트 업데이트
            });

            // 팝업 취소 버튼 처리
            $(document).on('click', '#cancelButton', function () {
                hidePopup();
            });

            // 팝업 숨기기 함수
            function hidePopup() {
                $('#buttonPopup').hide();
            }
        });

        $(document).ready(function() {
            var specialChars = [
                "~", "!", "@", "#", "$", "%", "^", "&", "*", "\\", "\"", "'", "+", "=", "`", "|", "(", ")", "[", "]", "{", "}", ":", ";", "-", "_", "＃", "＆", "＠",
                "§", "※", "☆", "★", "○", "●", "◎", "◇", "◆", "□", "■", "△", "▲", "▽", "▼", "→", "←", "↑", "↓", "↔", "〓", "◁", "◀", "▷", "▶", "♤", "♠", "♡",
                "♥", "♧", "♣", "⊙", "◈", "▣", "◐", "◑", "▒", "▤", "▥", "▨", "▧", "▦", "▩", "♨", "☏", "☎", "☜", "☞", "¶", "†", "‡", "↕", "↗", "↙", "↖",
                "↘", "♭", "♩", "♪", "♬", "㉿", "㈜", "№", "㏇", "™", "㏂", "㏘", "℡", "®", "ª", "º", "─", "│", "┌", "┐", "┘", "└", "├", "┬", "┤", "┴", "┼",
                "━", "┃", "┏", "┓", "┛", "┗", "┣", "┳", "┫", "┻", "╋", "┠", "┯", "┨", "┷", "┿", "┝", "┰", "┥", "┸", "╂", "┒", "┑", "┚", "┙", "┖", "┕", "┎",
                "┍", "┞", "┟", "┡", "┢", "┦", "┧", "┩", "┪", "┭", "┮", "┱", "┲", "┵", "┶", "┹", "┺", "┽", "┾", "╀", "╁", "╃", "╄", "╅", "╆", "╇", "╈", "╉",
                "╊", "＋", "－", "＜", "＝", "＞", "±", "×", "÷", "≠", "≤", "≥", "∞", "∴", "♂", "♀", "∠", "⊥", "⌒", "∂", "∇", "≡", "≒", "≪", "≫", "√", "∽",
                "∝", "∵", "∫", "∬", "∈", "∋", "⊆", "⊇", "⊂", "⊃", "∪", "∩", "∧", "∨", "￢", "⇒", "⇔", "∀", "∃", "∮", "∑", "∏", "！", "＇", "，", "．", "／",
                "：", "；", "？", "＾", "＿", "｀", "｜", "￣", "、", "。", "·", "‥", "…", "¨", "〃", "‐", "―", "∥", "＼", "∼", "´", "～", "ˇ", "˘", "˝", "˚",
                "˙", "¸", "˛", "¡", "¿", "ː", "＂", "”", "〔", "〕", "｛", "｝", "‘", "’", "“", "”", "〔", "〕", "〈", "〉", "《", "》", "「", "」", "『", "』",
                "【", "】", "㉠", "㉡", "㉢", "㉣", "㉤", "㉥", "㉦", "㉧", "㉨", "㉩", "㉪", "㉫", "㉬", "㉭", "㉮", "㉯", "㉰", "㉱", "㉲", "㉳", "㉴", "㉵",
                "㉶", "㉷", "㉸", "㉹", "㉺", "㉻", "㈀", "㈁", "㈂", "㈃", "㈄", "㈅", "㈆", "㈇", "㈈", "㈉", "㈊", "㈋", "㈌", "㈍", "㈎", "㈏", "㈐", "㈑",
                "㈒", "㈓", "㈔", "㈕", "㈖", "㈗", "㈘", "㈙", "㈚", "㈛", "ⓐ", "ⓑ", "ⓒ", "ⓓ", "ⓔ", "ⓕ", "ⓖ", "ⓗ", "ⓘ", "ⓙ", "ⓚ", "ⓛ", "ⓜ", "ⓝ", "ⓞ",
                "ⓟ", "ⓠ", "ⓡ", "ⓢ", "ⓣ", "ⓤ", "ⓥ", "ⓦ", "ⓧ", "ⓨ", "ⓩ", "⒜", "⒝", "⒞", "⒟", "⒠", "⒡", "⒢", "⒣", "⒤", "⒥", "⒦", "⒧", "⒨", "⒩",
                "⒪", "⒫", "⒬", "⒭", "⒮", "⒯", "⒰", "⒱", "⒲", "⒳", "⒴", "⒵", "①", "②", "③", "④", "⑤", "⑥", "⑦", "⑧", "⑨", "⑩", "⑪", "⑫", "⑬",
                "⑭", "⑮", "＄", "％", "￦", "Ｆ", "′", "″", "℃", "Å", "￠", "￡", "￥", "¤", "℉", "‰", "€", "㎕", "㎖", "㎗", "ℓ", "㎘", "㏄", "㎣", "㎤", "㎥",
                "㎦", "㎙", "㎚", "㎛", "㎜", "㎝", "㎞", "㎟", "㎠", "㎡", "㎢", "㏊", "㎍", "㎎", "㎏", "㏏", "㎈", "㎉", "㏈", "㎧", "㎨", "㎰", "㎱", "㎲",
                "㎳", "㎴", "㎵", "㎶", "㎷", "㎸", "㎹", "㎀", "㎁", "㎂", "㎃", "㎄", "㎺", "㎻", "㎼", "㎽", "㎾", "㎿", "㎐", "㎑", "㎒", "㎓", "㎔", "Ω",
                "㏀", "㏁", "㎊", "㎋", "㎌", "㏖", "㏅", "㎭", "㎮", "㎯", "㏛", "㎩", "㎪", "㎫", "㎬", "㏝", "㏐", "㏓", "㏃", "㏉", "㏜", "㏆", "ㆍ", "½",
                "⅓", "⅔", "¼", "¾", "⅛", "⅜", "⅝", "⅞", "¹", "²", "³", "⁴", "ⁿ", "₁", "₂", "₃", "₄", "║", "╒", "╓", "╔", "╕", "╖", "╗", "╘", "╙", "╚", "╛",
                "╜", "╝", "╞", "╟", "╠", "╡", "╢", "╣", "╤", "╥", "╦", "╧", "╨", "╩", "╪", "╫", "╬", "←", "↑", "→", "↓", "↔", "↕", "↖", "↗", "↘", "↙", "▣",
                "▤", "▥", "▦", "▧", "▨", "▩", "♩", "♪", "♫", "♬", "♭", "ꁇ", "܀", "܊", "܋", "܌", "܍", "¤", "፨", "₪", "ꂇ", "◘", "◙", "⌂", "☺", "☻", "♀",
                "♂", "ꋭ", "ꋯ", "ާ", "ި", "ީ", "ު", "ޫ", "ެ", "ޭ", "ޮ", "ᚗ", "ᚘ", "፡", "።", "፣", "፤", "፥", "፦", "፧", "‘", "’", "‚", "‛", "“", "”", "„",
                "‥", "…", "‧", "′", "″", "〝", "〞", "〟"
            ];

            // 특수문자 목록 생성
            var $specialCharList = $('#specialCharList');
            specialChars.forEach(function(char) {
                $specialCharList.append('<span class="special-char btn-t-4 btn-c-4">' + char + '</span>');
            });

            // 팝업 열기
            $('#openSpecialCharPopup').on('click', function() {
                $('#specialCharPopup').show();
            });

            // 팝업 닫기
            $('.closePopup').on('click', function() {
                $('#specialCharPopup').hide();
                $('#kkoIconPopup').hide();
            });
            // 이모티콘 열기
            $('#openKkoIconPopup').on('click', function() {
                $('#kkoIconPopup').show();
                // JSON 파일 로드

                $.ajax({
                    url: '/kakao/index.php?route=getKakaoIcon',
                    type: 'GET',

                    dataType: 'json',
                    success: function(response) {
                        // 응답이 성공적으로 반환되었을 때
                        if (response.success) {

                            $('#kko_icon_list').empty();
                            // 데이터를 순회하며 아이콘을 HTML로 변환
                            response.data.forEach(function(icon) {
                                var htmlContent = `
                                    <button class="btn-t-4 btn-c-4 kko_icon_button" type="button" data-id="${icon.name}">
                                        <i class="kko_icon" style="background-image: url('${icon.image}');">&nbsp;</i>
                                        ${icon.name}
                                    </button>`;
                                // 아이콘을 '#iconList' 안에 추가
                                $('#kko_icon_list').append(htmlContent);
                            });
                        } else {
                            // 성공 상태지만 오류 메시지가 있는 경우 처리
                            alert(response.message || '데이터를 가져오지 못했습니다.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('실패했습니다. 다시 시도해 주세요.');
                        console.error('Error: ' + error);
                        console.error('Status: ' + status);
                        console.dir(xhr);
                    }
                });
            });

            // 특수문자 클릭 시 입력 필드에 삽입
            $(document).on('click', '.special-char', function() {
                var selectedChar = $(this).text();
                var currentText = $('#highlightTitle').val();
                $('#highlightTitle').val(currentText + selectedChar);
                $('#highlightTitle').trigger('input')
                $('#specialCharPopup').hide(); // 선택 후 팝업 닫기

            });

            // 특수문자 클릭 시 입력 필드에 삽입
            $(document).on('click', '.kko_icon_button', function() {
                var iconName = $(this).data("id");
                var currentText = $('#highlightTitle').val();
                $('#highlightTitle').val(currentText +'('+iconName+')');
                $('#highlightTitle').trigger('input')
                $('#kkoIconPopup').hide(); // 선택 후 팝업 닫기
            });

        });


    </script>
    <!--footer-->
    <div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/footer.php"; ?></div>
</body>

<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/index_layer.php"; ?>

</html>
