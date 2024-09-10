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
                <div id="previewDate">2024년 07월 11일</div>
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
                    <div class="previewFooter">오전 12:02</div>
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
                            <button type="button" class="btn-c-4 btn-t" id="addVariableBtn">변수추가</button>
                            <button type="button" class="btn-c-4 btn-t" id="addButton">버튼추가 (0/5)</button>
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
                        <div id="buttonList">
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
                                <label for="buttonName" class="custom-label">버튼명</label>
                                <input type="text" id="buttonName" placeholder="버튼명을 입력하세요" class="fm-ipt custom-input">
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
            <label for="variableName">변수명:</label>
            <input type="text" id="variableName">
            <div class="flex-c">
                <button type="button" id="insertVariableBtn" class="btn-t btn-c">변수추가</button>
            </div>
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
                $('#charCount').text(currentLength + "/1000");

                if (currentLength > 1000) {
                    $('#errorMsg').removeClass("blind");
                    $('#errorMsg').addClass("active");
                    $(this).val($(this).val().substring(0, 1000));  // 글자수 제한
                } else {
                    $('#errorMsg').addClass("blind");
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
        $(document).ready(function() {
            let buttons = [];
            const maxButtons = 5;

            $('#addButton').on('click', function() {
                if (buttons.length >= maxButtons) {
                    alert('최대 5개의 버튼만 추가할 수 있습니다.');
                    return;
                }
                showPopup();
            });
            $('#linkType').on('change', function() {
                var linkType = $(this).val();
                // 모든 요소에 대해 반복 처리
                $('[data-id]').each(function() {
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

            function showPopup(index = null) {
                const popup = $('#buttonPopup');
                popup.show();
                var linkType = $("#linkType option:selected").val();
                $('[data-id]').each(function() {
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
                // 기존의 'saveButton' 클릭 이벤트 리스너 제거 후 새로 바인딩
                $('#saveButton').off('click').on('click', function() {

                    const linkType = $('#linkType').val();
                    const buttonName = $('#buttonName').val();
                    const linkMo = $('#linkMo').val();
                    const linkPc = $('#linkPc').val();
                    const linkAnd = $('#linkAnd').val();
                    const linkIos = $('#linkIos').val();
                    const bizFormId = $('#bizFormId').val();
                    const pluginId = $('#pluginId').val();
                    if (!buttonName) {
                        alert('버튼명은 필수 입력 항목입니다.');
                        return;
                    }
                    // Android URL 검증
                    if (linkAnd && !/^market:\/\/|^https:\/\//.test(linkAnd)) {
                        alert('Android 링크는 https:// 또는 market:// 로 시작해야 합니다.');
                        return;
                    }

                    // iOS URL 검증
                    if (linkIos && !/^itms-apps:\/\/|^https:\/\//.test(linkIos)) {
                        alert('iOS 링크는 https:// 또는 itms-apps:// 로 시작해야 합니다.');
                        return;
                    }

                    // 모바일 링크 검증 (https:// 형식이어야 함)
                    if (linkMo && !/^https:\/\//.test(linkMo)) {
                        alert('링크는 https:// 로 시작해야 합니다.');
                        return;
                    }
                    // 모바일 링크 검증 (https:// 형식이어야 함)
                    if (linkPc && !/^https:\/\//.test(linkPc)) {
                        alert('링크는 https:// 로 시작해야 합니다.');
                        return;
                    }
                    if (index !== null) {
                        // 기존 버튼 수정
                        buttons[index].name = buttonName;
                        buttons[index].linkType = linkType;
                        buttons[index].linkMo = linkMo;
                        buttons[index].linkPc = linkPc;
                        buttons[index].linkAnd = linkAnd;
                        buttons[index].linkIos = linkIos;
                        buttons[index].bizFormId = bizFormId;
                        buttons[index].pluginId = pluginId;
                    } else {
                        // 새 버튼 추가
                        const newButton = {
                            name: buttonName,
                            linkType: linkType,
                            linkMo: linkMo,
                            linkPc: linkPc,
                            linkAnd: linkAnd,
                            linkIos: linkIos,
                            bizFormId:bizFormId,
                            pluginId:pluginId,
                            ordering: buttons.length + 1,
                        };
                        buttons.push(newButton);
                    }

                    updateButtonList();
                    hidePopup();
                });
            }

            function hidePopup() {
                $('#buttonPopup').hide();
            }

            function updateButtonList() {
                const buttonList = $('#buttonList');
                buttonList.empty();
                const linkType = {
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
                $// #previewHighlightSubtitle 다음에 있는 기존 버튼 제거
                $('.generated-button').remove();
                $.each(buttons, function (index, button) {
                    let inputFields = '';

                    switch(button.linkType) {
                        case 'WL':
                            inputFields = `
                                            <input type="hidden" name="name[]" value="${button.name}">
                                            <input type="hidden" name="postLinkType[]" value="${button.linkType}">
                                            <input type="hidden" name="ordering[]" value="${index}">

                                            <input type="hidden" name="linkMo[]" value="${button.linkMo}">
                                            <input type="hidden" name="linkPc[]" value="${button.linkPc}">

                                        `;
                            break;
                        case 'AL':
                            inputFields = `
                                            <input type="hidden" name="name[]" value="${button.name}">
                                            <input type="hidden" name="postLinkType[]" value="${button.linkType}">
                                            <input type="hidden" name="ordering[]" value="${index}">
                                             <input type="hidden" name="linkAnd[]" value="${button.linkAnd}">
                                            <input type="hidden" name="linkIos[]" value="${button.linkIos}">
                                        `;
                            break;
                        case 'MD':
                            inputFields = `
                                            <input type="hidden" name="name[]" value="${button.name}">
                                            <input type="hidden" name="postLinkType[]" value="${button.linkType}">
                                            <input type="hidden" name="ordering[]" value="${index}">
                                        `;
                            break;
                        case 'BF':
                            inputFields = `
                                            <input type="hidden" name="name[]" value="${button.name}">
                                            <input type="hidden" name="postLinkType[]" value="${button.linkType}">
                                            <input type="hidden" name="ordering[]" value="${index}">
                                           <input type="hidden" name="bizFormId[]" value="${button.bizFormId}">
                                        `;
                            break;
                        case 'P1':
                            inputFields = `
                                            <input type="hidden" name="name[]" value="${button.name}">
                                            <input type="hidden" name="postLinkType[]" value="${button.linkType}">
                                            <input type="hidden" name="ordering[]" value="${index}">
                                           <input type="hidden" name="pluginId[]" value="${button.pluginId}">
                                        `;
                            break;
                        case 'P2':
                            inputFields = `
                                            <input type="hidden" name="name[]" value="${button.name}">
                                            <input type="hidden" name="postLinkType[]" value="${button.linkType}">
                                            <input type="hidden" name="ordering[]" value="${index}">
                                            <input type="hidden" name="pluginId[]" value="${button.pluginId}">
                                        `;
                            break;
                        // Add more cases as needed
                        default:
                            inputFields = `
                                            <input type="hidden" name="name[]" value="${button.name}">
                                            <input type="hidden" name="postLinkType[]" value="${button.linkType}">
                                            <input type="hidden" name="ordering[]" value="${index}">
                                        `;
                            break;
                    }

                    const buttonItem = $(`
                                            <div class="button-item">
                                                <div>
                                                    <strong>${button.name}</strong>
                                                    <span>${linkType[button.linkType]}</span>
                                                    ${inputFields}
                                                </div>
                                                <div class="button-actions">
                                                    <button class="editButton" type="button" data-index="${index}">수정</button>
                                                    <button class="deleteButton" type="button" data-index="${index}">삭제</button>
                                                </div>
                                            </div>
                                        `);
                    const generatedButton = $(`<button class="generated-button jss2034">${button.name}</button>`);
                    // #previewHighlightSubtitle 다음에 버튼 추가
                    $('#previewHighlightSubtitle').before(generatedButton);
                    buttonList.append(buttonItem);
                });

                $('#addButton').text(`+ 버튼추가 (${buttons.length}/${maxButtons})`);
            }

            $(document).on('click', '.editButton', function() {
                const index = $(this).data('index');
                const button = buttons[index];
                $('#linkType').val(button.linkType);
                $('#buttonName').val(button.name);
                $('#linkMo').val(button.linkMo);
                $('#linkPc').val(button.linkPc);
                $('#linkAnd').val(button.linkAnd);
                $('#linkIos').val(button.linkIos);
                $('#bizFormId').val(button.bizFormId);
                $('#pluginId').val(button.pluginId);
                showPopup(index);
            });

            $(document).on('click', '.deleteButton', function() {
                const index = $(this).data('index');
                buttons.splice(index, 1);
                updateButtonList();
            });
            // 팝업 숨기기
            $(document).on('click', '#cancelButton', function() {
                hidePopup();
            });
        });

    </script>
    <!--footer-->
    <div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/footer.php"; ?></div>
</body>

<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/index_layer.php"; ?>

</html>
