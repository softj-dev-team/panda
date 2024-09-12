let iconMap = {};
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
const statusMapping = {
    'A': 'activated',
    'C': 'deactivated',
    'B': 'block',
    'E': 'deleting',
    'D': 'deleted',
    '01': '승인',
    '02': '승인대기'
};
let buttons = [];
let quickReplies = [];
const maxButtons = 5;
const maxQuickReplies = 5;
<!-- 버튼이 추가될 영역 -->
let  newButton={};
let  newQuickReplies={};
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
function changeStrongTitle(){
    if($('#strong_sub_title').val() || $('#strong_title').val()){
        $('#previewHighlightTitle').css('border-top', '1px solid #bbb');
        $('#previewHighlightTitle').css('font-weight', '300');
    }else{
        $('#previewHighlightTitle').css('border-top', 'none');
    }
}
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
    let generatedButton='';
    $('.generated-button').remove();
    $('.button-item').remove();
    $('.kko-sub-text').remove();
    $.each(buttons, function (index, item) {
        let inputFields = generateInputFields(item, index, false);

        const buttonItem = $(`
                        <div class=" ${item.linkType === 'AC' ? 'button-item-ch' : 'button-item'}">
                            <div>
                                <strong>[버튼]${item.name}</strong>
                                <span>${buttonLinkTypes[item.linkType]}</span>
                                ${inputFields}
                            </div>
                            <div class="button-actions">
                                <button class="deleteButton" type="button" data-index="${index}" data-type="buttons">삭제</button>
                            </div>
                        </div>
                    `);

        // 버튼 생성 (조건에 따라 <span> 추가)
        if (item.linkType === 'AC') {

            $('#previeButtonList').prepend(
                $(`
                                <span class="kko-sub-text">채널 추가하고 이 채널의 광고와 마케팅 메시지를 카카오톡으로 받기</span>
                                <button class="generated-button addCh">${item.name}</button>
                            `)
            )
            buttonList.prepend(buttonItem);
        } else {
            $('#previeButtonList').append(
                $(`
                                <button class="generated-button jss2034">${item.name}</button>
                            `)
            )
            buttonList.append(buttonItem);
        }

        // AC인 경우 버튼을 리스트 맨 앞에 추가, 아니면 뒤에 추가
        if (item.linkType === 'AC') {
            $('#previeButtonList').prepend(generatedButton); // AC인 경우 리스트 맨 앞에 추가
            buttonList.prepend(buttonItem); // AC인 경우 buttonItem도 맨 앞에 추가
        } else {
            $('#previeButtonList').append(generatedButton); // 일반 버튼은 뒤에 추가
            buttonList.append(buttonItem); // 일반 buttonItem도 뒤에 추가
        }

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
                                <input type="hidden" name="name[${index}]" value="${item.name}">
                                <input type="hidden" name="postLinkType[${index}]" value="${item.linkType}">
                                <input type="hidden" name="ordering[${index}]" value="${index}">
                                <input type="hidden" name="linkMo[${index}]" value="${item.linkMo}">
                                <input type="hidden" name="linkPc[${index}]" value="${item.linkPc}">
                            `;
            case 'AL':  // 앱링크
                return `
                                <input type="hidden" name="name[${index}]" value="${item.name}">
                                <input type="hidden" name="postLinkType[${index}]" value="${item.linkType}">
                                <input type="hidden" name="ordering[${index}]" value="${index}">
                                <input type="hidden" name="linkAnd[${index}]" value="${item.linkAnd}">
                                <input type="hidden" name="linkIos[${index}]" value="${item.linkIos}">
                            `;
            case 'MD':
                return `
                                    <input type="hidden" name="name[${index}]" value="${item.name}">
                                    <input type="hidden" name="postLinkType[${index}]" value="${item.linkType}">
                                    <input type="hidden" name="ordering[${index}]" value="${index}">
                                `;
            case 'BF':
                return `
                                    <input type="hidden" name="name[${index}]" value="${item.name}">
                                    <input type="hidden" name="postLinkType[${index}]" value="${item.linkType}">
                                    <input type="hidden" name="ordering[${index}]" value="${index}">
                                   <input type="hidden" name="bizFormId[${index}]" value="${item.bizFormId}">
                                `;
            case 'P1':
                return `
                                    <input type="hidden" name="name[${index}]" value="${item.name}">
                                    <input type="hidden" name="postLinkType[${index}]" value="${item.linkType}">
                                    <input type="hidden" name="ordering[${index}]" value="${index}">
                                   <input type="hidden" name="pluginId[${index}]" value="${item.bizFormId}">
                                `;
            case 'P2':
                return `
                                    <input type="hidden" name="name[${index}]" value="${item.name}">
                                    <input type="hidden" name="postLinkType[${index}]" value="${item.linkType}">
                                    <input type="hidden" name="ordering[${index}]" value="${index}">
                                   <input type="hidden" name="pluginId[${index}]" value="${item.bizFormId}">
                                `;
            default:

                return `
                                <input type="hidden" name="name[${index}]" value="${item.name}">
                                <input type="hidden" name="postLinkType[${index}]" value="${item.linkType}">
                                <input type="hidden" name="ordering[${index}]" value="${index}">
                            `;
        }
    }
}
// 팝업 숨기기 함수
function hidePopup() {
    $('#buttonPopup').hide();
}
function updatePreview(inputValue=null) {
    var content = $('#previewHighlightTitle').html();
    $('#previewHighlightTitle').html(content + inputValue);

}
function validateForm(form) {
    var isValid = true; // 폼이 유효한지 여부를 저장하는 변수
    var form = form

    // fm-error-txt 클래스가 있는 모든 span 요소를 찾음
    var errorTexts = form.querySelectorAll('.fm-error-txt');

    errorTexts.forEach(function(errorText) {
        // span 요소의 상위 요소에 blind 클래스가 없는지 확인
        var hasBlindClass = errorText.closest('.blind') !== null;

        if (!hasBlindClass) { // blind 클래스가 없는 경우에만 검증
            // span 요소의 이전 형제 요소를 찾음 (input, select 등)
            var inputField = errorText.previousElementSibling;

            // inputField가 select나 input인지 확인
            if (inputField && (inputField.tagName === 'SELECT' || inputField.tagName === 'INPUT' || inputField.tagName === 'TEXTAREA')) {
                // 필수 입력 필드가 비어 있는지 확인
                if (inputField.value.trim() === "") {
                    // 오류 상태 표시
                    inputField.classList.add('fm-error');
                    errorText.classList.add('active');
                    errorText.textContent = "항목을 입력해 주세요."; // 에러 메시지 설정
                    isValid = false; // 유효하지 않은 상태로 설정
                } else {
                    // 오류 상태 제거
                    inputField.classList.remove('fm-error');
                    errorText.classList.remove('active');
                    errorText.textContent = ""; // 에러 메시지 초기화
                }
            }
        }
    });

    return isValid; // 폼이 유효하면 true, 아니면 false를 반환
}
function loadProfileCategory(){
    $.ajax({
        url: '/kakao/index.php?route=getKakaoProfileCategory',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.code === "200") {
                // 3차원 배열로 변환
                var categories = {};

                response.data.forEach(function(item) {
                    var parts = item.name.split(',');
                    if (!categories[parts[0]]) {
                        categories[parts[0]] = {};
                    }
                    if (!categories[parts[0]][parts[1]]) {
                        categories[parts[0]][parts[1]] = [];
                    }
                    categories[parts[0]][parts[1]].push({ name: parts[2], code: item.code });
                });

                // 대분류 select 박스 채우기
                var category1Select = $('#category1');
                $.each(categories, function(key) {
                    category1Select.append('<option value="' + key + '">' + key + '</option>');
                });

                // 대분류 선택 시 중분류 옵션 채우기
                category1Select.on('change', function() {
                    var category1 = $(this).val();
                    var category2Select = $('#category2').empty().append('<option value="">중분류 선택</option>').prop('disabled', false);
                    var category3Select = $('#category3').empty().append('<option value="">소분류 선택</option>').prop('disabled', true);

                    if (category1) {
                        $.each(categories[category1], function(key) {
                            category2Select.append('<option value="' + key + '">' + key + '</option>');
                        });
                    }
                });

                // 중분류 선택 시 소분류 옵션 채우기
                $('#category2').on('change', function() {
                    var category1 = $('#category1').val();
                    var category2 = $(this).val();
                    var category3Select = $('#category3').empty().append('<option value="">소분류 선택</option>').prop('disabled', false);

                    if (category2) {
                        $.each(categories[category1][category2], function(index, item) {
                            category3Select.append('<option value="' + item.code + '">' + item.name + '</option>');
                        });
                    }
                });

                // 소분류 선택 시 code 값을 hidden input에 주입
                $('#category3').on('change', function() {
                    var selectedCode = $(this).val();
                    $('#industry').val(selectedCode);
                });
            } else {
                alert('카테고리 데이터를 가져오는 데 실패했습니다.');
            }
        },
        error: function(xhr, status, error) {
            alert('카테고리 데이터를 가져오는 중 오류가 발생했습니다.');
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });
}
function loadProfiles(page = 1) {
    $.ajax({
        url: 'index.php?route=getProfiles',
        type: 'GET',
        data: { page: page },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var profilesTable = $('#profilesTable tbody');
                profilesTable.empty();
                response.profiles.forEach(function(profile) {
                    var row = `<tr>
                            <td>${profile.id}</td>
                            <td>${profile.chananel_name}</td>         
                            <td>${profile.profile_key}</td>
                            <td>${profile.industry}</td>
                            <td>${profile.cs_phone_number}</td>                           
                            <td>${statusMapping[profile.status]}</td>
                            
                        </tr>`;
                    profilesTable.append(row);
                });

                // 페이징 처리
                var totalPages = Math.ceil(response.total / 10);
                var pagination = $('#pagination');
                pagination.empty();
                for (var i = 1; i <= totalPages; i++) {
                    var pageLink = `<a href="#" class="page-link ${i === page ? 'active' : ''}" data-page="${i}">${i}</a>`;
                    pagination.append(pageLink);
                }
            }
        },
        error: function(xhr, status, error) {
            alert('신청 목록을 불러오는 데 실패했습니다. 다시 시도해 주세요.');
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });
}
function authenticationRequest(){
    var formData = new FormData($('#profileForm')[0]); // FormData 객체 생성
    $.ajax({
        url: '/kakao/index.php?route=authenticationRequest',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert("오류: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert("실패했습니다. 다시 시도해 주세요.");
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });
}
function requestProfileKey(){
    var formData = new FormData($('#profileForm')[0]); // FormData 객체 생성
    $.ajax({
        url: '/kakao/index.php?route=requestProfileKey',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert("오류: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert("실패했습니다. 다시 시도해 주세요.");
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });
}

function loadTemplateDetails(templateId) {
    const imgElement = $('#uploadedImage');
    $.ajax({
        url: '/kakao/index.php?route=getTemplateDetails',
        type: 'GET',
        data: { id: templateId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var template = response.template;
                var fcallback = template.cs_phone_number;
                var templateTitle = template.apiRespone.convContent;
                var templateSubTitle = template.template_subtitle;
                var strongTitle = template.strong_title;
                var strongSubTitle = template.strong_sub_title;
                var profile_key = template.profile_key;
                var template_key = template.template_key;
                var template_emphasize_type = template.template_emphasize_type;
                var template_id = template.id;
                var img_path = template.image_path;
                if (strongTitle) {
                    $('#previewHighlightTitle').css('border-top', '1px solid #bbb');
                } else {
                    $('#previewHighlightTitle').css('border-top', 'none');
                }

                $('.generated-button').remove();

                // 버튼 배열을 순회하여 각 버튼을 생성하고 추가
                $.each(template.apiRespone.buttons, function(index, button) {
                    // 각 버튼의 이름을 사용하여 버튼을 생성
                    const generatedButton = $(`<button class="generated-button jss2034">${button.name}</button>`);

                    // #previewHighlightSubtitle 이전에 버튼 추가
                    $('#previewHighlightSubtitle').after(generatedButton);
                });

                $('#previewHighlightTitle').html(templateTitle);
                $('#previewHighlightSubtitle').text(templateSubTitle);
                $('#previewStrongTitle').text(strongTitle);
                $('#previewStrongSubTitle').text(strongSubTitle);
                imgElement.hide();
                if(template_emphasize_type == "IMAGE"){
                    imgElement.attr('src', img_path);
                    imgElement.show();
                }

                // template_title의 변수를 추출하여 동적으로 input 생성
                var regex = /#\{(.*?)\}/g;
                var matches;
                var inputFields = '' +
                    '<input type="hidden" name="template_id" value="' + template_id + '">' +
                    '<input type="hidden" name="template_key" value="' + template_key + '">' +
                    '<input type="hidden" name="profile_key" value="' + profile_key + '">' +
                    '<input type="hidden" name="fcallback" value="' + fcallback + '">' +
                    '<input type="text" name="fdestine" placeholder="수신자번호">' +
                    '<input name="message" type="hidden" >';
                while ((matches = regex.exec(templateTitle)) !== null) {
                    inputFields += '<input type="text" name="variables[]" placeholder="' + matches[1] + '" data-varname="' + matches[1] + '">';
                }
                $('#template-send-form .fm-box.flex-c').html(inputFields);

                // 각 변수 필드에 이벤트 리스너 추가
                $('input[name="variables[]"]').on('input', function() {
                    updatePreview(templateTitle);
                });
            } else {
                alert('템플릿 정보를 불러오는 데 실패했습니다.');
            }
        },
        error: function(xhr, status, error) {
            alert('템플릿 정보를 불러오는 데 실패했습니다.');
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });
}
function showLoadingSpinner() {
    $('.spinner-background').show();
    $('.loadingio-spinner-spin-2by998twmg8').show();
}

function hideLoadingSpinner() {
    $('.spinner-background').hide();
    $('.loadingio-spinner-spin-2by998twmg8').hide();
}
function loadTemplate(page = 1) {
    const profile_id = $('#f-sel').val();
    const template_type = $('select[name="template_type"]').val();
    const template_emphasize_type = $('select[name="template_emphasize_type"]').val();
    const inspection_status = $('select[name="inspection_status"]').val();
    const status = $('select[name="status"]').val();
    const template_title = $('input[name="template_title"]').val();

    const statusMapping = {
        '01': '승인',
        '02': '승인대기',
        'R': '승인대기',
        'A': '정상',
        'S': '중단',
        'D': '삭제'
    };
    const inspectionStatusMapping = {
        // REG : 등록, REQ : 심사요청, APR : 승인,
        // REJ : 반려
        'REG': '등록',
        'REQ': '검수결과대기',
        'APR': '승인',
        'REJ': '반려'
    };
    const templateTypeMapping = {
        'BA': '기본형',
        'EX': '부가정보형',
        'AD': '채널추가형',
        'MI': '복합형',
        'ITEM_LIST': '아이템리스트형',
        'TEXT': '강조표기형'
    };
    showLoadingSpinner(); // 스피너 표시
    $.ajax({
        url: '/kakao/index.php?route=getUserTemplate',
        type: 'GET',
        data: {
            page: page,
            profile_id: profile_id,
            template_type: template_type,
            template_emphasize_type: template_emphasize_type,
            inspection_status: inspection_status,
            status: status,
            template_title: template_title
        },
        dataType: 'json',
        success: function(response) {
            hideLoadingSpinner(); // 스피너 숨기기
            if (response.success) {
                var profilesTable = $('#templatelistTable tbody');
                profilesTable.empty();
                response.template.forEach(function(template) {
                    var statusText = statusMapping[template.status];
                    var templateText = templateTypeMapping[template.template_type]
                    var inspectionStatusText = inspectionStatusMapping[template.inspection_status]

                    // 검수요청 버튼 조건에 따라 추가
                    var inspectionRequestButton = '';
                    var editButton = '';
                    var deleteButton='';
                    if (template.inspection_status === 'REG' || template.inspection_status === 'R') {
                        inspectionStatusText = `<button type="button" class="btn-t-3 btn-c-3" onclick="requestInspection(${template.id})">검수요청</button>`;
                        editButton = `<button class="fa fa-edit tooltip" onclick="window.location.href='index.php?route=editTemplate&id=${template.id}'">  <span class="tooltiptext">수정</span></button>`;
                        deleteButton = `<button class="fa fa-trash-can tooltip" onclick="deleteTemplate('${template.id}')"> <span class="tooltiptext">삭제</span></button>`;
                    }
                    var row = `<tr>
                            <td>${template.id}</td>
                            <td><a href="#" id="templateSelect" data-id="${template.id}">${template.template_name}</a></td>
                            <td>${templateText}</td>
                            <td>${formatDate(template.created_at)}</td>
                            <td>${inspectionStatusText} ${inspectionRequestButton}</td>
                            <td>${statusText}</td>
                            
                            <td>${editButton}&nbsp;${deleteButton}&nbsp;<button class="fa fa-file-excel tooltip" onclick="window.location.href='index.php?route=downloadSample&template_id=${template.id}'"> <span class="tooltiptext">엑셀셈플 다운로드</span></button></td>
                        </tr>`;
                    profilesTable.append(row);
                });

                // 페이징 처리
                var pageSize = 10;
                var totalRow = response.total;
                var totalPages = Math.ceil(totalRow / pageSize);
                var currentPage = page;
                var pageSizeGroup = 10;

                var startPage = Math.floor((currentPage - 1) / pageSizeGroup) * pageSizeGroup + 1;
                var endPage = startPage + pageSizeGroup - 1;

                if (endPage > totalPages) {
                    endPage = totalPages;
                }

                var pagination = $('#templatePagination');
                pagination.empty();
                if (currentPage > 1) {
                    var prevPage = startPage - pageSizeGroup;
                    pagination.append(`<a href="#" class="page-link pre" data-page="${prevPage > 0 ? prevPage : 1}"> <img src="/images/pagenation/l.png"></a>`);
                }

                for (var i = startPage; i <= endPage; i++) {
                    var pageLink = `<a href="#" class="page-link ${i === currentPage ? 'atv' : ''}" data-page="${i}">${i}</a>`;
                    pagination.append(pageLink);
                }

                if (endPage < totalPages) {
                    var nextPage = endPage + 1;
                    pagination.append(`<a href="#" class="page-link next" data-page="${nextPage}"><img src="/images/pagenation/r.png"></a>`);
                }
            } else {
                var profilesTable = $('#templatelistTable tbody');
                profilesTable.empty();
                var row = '<tr>'+
                    '<td colspan="7" class="no-data"><span class="ir-b i-nodata">검색 결과가 없습니다.</span></td>'+
                    '</tr>';
                profilesTable.append(row);
            }
        },
        error: function(xhr, status, error) {
            hideLoadingSpinner(); // 스피너 숨기기
            alert('신청 목록을 불러오는 데 실패했습니다. 다시 시도해 주세요.');
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });

}
function deleteTemplate(template_id){
    var id = template_id;
    $.ajax({
        url: '/kakao/index.php?route=deleteTemplate',
        type: 'POST',
        data: { id: id},
        dataType: 'json',
        success: function(response) {
            alert(response.message);
            if (!response.success) {
                loadTemplate(page = 1)
            }
        },
        error: function(xhr, status, error) {
            alert('상태 업데이트에 실패했습니다. 다시 시도해 주세요.');
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });
}
function requestInspection(template_id){
    $.ajax({
        url: 'index.php?route=apiRequestTemplate',
        type: 'POST',
        data: {id:template_id},

        dataType: 'json',
        success: function(response) {
            alert(response.message);
            loadTemplate(1)
        },
        error: function(xhr, status, error) {
            alert('요청에 실패했습니다. 다시 시도해 주세요.');
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });
}
function editTemplate(template_id){
    window.location.href=="index.php?route=editTemplate&id="+template_id;
}
function loadCategories() {
    $.getJSON('index.php?route=getCategories', function(categories) {
        const categorySelect = $('#category');

        const parentCategories = categories.filter(category => category.parent_id === null);
        parentCategories.forEach(parentCategory => {
            const optgroup = $('<optgroup>').attr('label', parentCategory.name);

            const childCategories = categories.filter(category => category.parent_id === parentCategory.id);
            childCategories.forEach(childCategory => {
                const option = $('<option>').val(childCategory.code).text(childCategory.name);
                optgroup.append(option);
            });

            categorySelect.append(optgroup);
        });
    }).fail(function() {
        console.error('카테고리 로드 실패');
    });
}

function addItem() {
    const itemDiv = $('<div>').html(`
                <input type="text" name="item_list[]" placeholder="아이템명 (0~23자)">
                <textarea name="item_description[]" placeholder="내용 (0~23자)"></textarea>
                <button type="button" onclick="removeItem(this)">삭제</button>
            `);
    $('#guide').append(itemDiv);
}

function removeItem(button) {
    $(button).parent().remove();
}

function formatDate(dateString) {
    // 원래 날짜 형식: 2024-09-03 06:47:05
    var date = new Date(dateString);

    var year = date.getFullYear().toString().slice(-2); // 연도에서 뒤 두 자리를 가져옴
    var month = ('0' + (date.getMonth() + 1)).slice(-2); // 월을 2자리로 맞춤
    var day = ('0' + date.getDate()).slice(-2); // 일을 2자리로 맞춤
    var hours = ('0' + date.getHours()).slice(-2); // 시를 2자리로 맞춤
    var minutes = ('0' + date.getMinutes()).slice(-2); // 분을 2자리로 맞춤

    return `${year}.${month}.${day} ${hours}:${minutes}`;
}
function formatDate14(dateStr) {
    if (!dateStr || dateStr.length !== 14) {
        return 'Invalid Date';  // 기본적인 유효성 검사
    }

    var year = dateStr.substring(0, 4);
    var month = dateStr.substring(4, 6);
    var day = dateStr.substring(6, 8);
    var hour = dateStr.substring(8, 10);
    var minute = dateStr.substring(10, 12);
    var second = dateStr.substring(12, 14);

    return `${year}.${month}.${day} ${hour}:${minute}`;
}
function convertToHtml(text) {
    if (typeof text === 'string') {
        return text.replace(/\n/g, '<br>');
    }
    return '';
}
$(document).ready(function() {
    // 저장 버튼 클릭 이벤트 처리
    $('#saveButton').off('click').on('click', function () {
        if (type === 'buttons') {
            saveButton(index);  // 버튼 저장
        } else if (type === 'quickReplies') {
            saveQuickReply(index);  // 바로연결 저장
        }
    });
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

    $('select[name=template_type]').on('change', function() {
        const selectedValue = $(this).val();


        // AC 버튼이 이미 존재하는지 확인
        const isACExists = buttons.some(button => button.linkType === 'AC');

        // AD 또는 MI가 선택되었을 때만 실행, AC 버튼이 없을 때만 추가
        if ((selectedValue === "AD" || selectedValue === "MI") && !isACExists) {
            // 새로운 AC 버튼 추가
            let newButton = {};
            newButton['name'] = "체널추가";
            newButton['linkType'] = "AC";
            newButton['linkMo'] = "";
            newButton['linkPc'] = "";
            newButton['linkAnd'] = "";
            newButton['linkIos'] = "";
            newButton['bizFormId'] = "";
            newButton['pluginId'] = "";

            buttons.push(newButton); // AC 버튼 추가
            updateButtonList('buttons'); // 버튼 리스트 업데이트
        }

        // EX 또는 MI일 때 typeEX를 표시, 그렇지 않으면 숨김 처리
        if (selectedValue === "EX" || selectedValue === "MI") {
            $('#typeEX').removeClass("blind");
        } else {
            $('#typeEX').addClass("blind");
        }
    });
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

    var initialOption = $('#f-sel option:selected').val();
    // 서버에서 사용자 프로필 가져오기
    $.ajax({
        url: 'index.php?route=getUserProfiles',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var select = $('#f-sel');

            if (response.success && response.data.length > 0) {
                $.each(response.data, function(index, profile) {
                    // 기존에 선택된 옵션과 일치하는 경우 selected 속성 추가
                    var selected = profile.id == initialOption ? 'selected' : '';
                    select.append('<option value="' + profile.id + '" ' + selected + '>' + profile.chananel_name + '</option>');
                });
            } else {
                alert('발신프로필이 없습니다.');
            }
        },
        error: function(xhr, status, error) {
            alert('프로필 목록을 불러오는 데 실패했습니다.');
            console.error('Error: ' + error);
            console.error('Status: ' + status);
            console.dir(xhr);
        }
    });

    loadCategories();
    $('#template-form').submit(function(event) {
        event.preventDefault();
        // 폼 제출 처리 로직
    });
    $('#highlightSubtitle').on('keydown', function (e){

        var addContent='';
        // Enter 키가 눌렸을 때만 <br> 태그 추가
        if (e.key === 'Enter') {
            addContent = '<br>';
        }
        if (e.ctrlKey && e.key === 'c') {
            return;  // Ctrl+V 처리 시 일반 키 입력 방지
        }
        // Ctrl+V는 일반 입력으로 처리하지 않고, paste 이벤트에서 처리
        if (e.ctrlKey && e.key === 'v') {
            return;  // Ctrl+V 처리 시 일반 키 입력 방지
        }
        // 백스페이스 키 처리
        else if (e.key === 'Backspace') {
            // #previewHighlightTitle에서 마지막 글자 삭제
            var content = $('#previewHighlightSubtitle').html();  // 현재 콘텐츠 가져오기
            if (content.length > 0) {
                content = content.slice(0, -1);  // 마지막 글자 삭제
            }
            $('#previewHighlightSubtitle').html(content);  // 업데이트된 콘텐츠 적용
            return;  // 백스페이스 처리 후 함수 종료
        }
        // 키 입력을 감지
        else if (e.key.length === 1) {
            addContent = e.key;  // 입력된 키 값
        }

        // 입력된 내용이 있으면 업데이트
        if (addContent) {
            var subContent = $('#previewHighlightSubtitle').html();
            $('#previewHighlightSubtitle').html(subContent + addContent);
        }


    });
    // 텍스트 입력된 값을 iconMap을 참고하여 이미지로 변환
    $('#highlightTitle').on('input', function() {
        var inputValue = $(this).val();  // 입력된 전체 텍스트 가져오기

        // 텍스트에서 (아이콘명) 패턴을 찾아서 해당 패턴을 이미지로 변환
        var updatedContent = inputValue.replace(/\(([^)]+)\)/g, function(match, iconName) {
            // iconMap에 있는 아이콘명일 경우, 해당 이미지를 반환
            if (iconMap[iconName]) {
                return `<img class="view-icon" src="${iconMap[iconName]}" alt="${iconName}" />`;
            } else {
                // iconMap에 없는 경우는 원래 텍스트를 그대로 반환
                return match;
            }
        });

        // 최종 변환된 내용을 previewHighlightTitle에 적용
        $('#previewHighlightTitle').html(updatedContent);
    });
    $('#highlightSubtitle').on('input', function() {
        var inputValue = $(this).val();
        if (inputValue === '') {
            $('#previewHighlightSubtitle').html('');  // 미리보기 영역 비움

        }
    });
    $('#highlightTitle').on('paste', function(e) {
        // 붙여넣기 데이터 가져오기
        var pastedData = (e.originalEvent || e).clipboardData.getData('text');
        // 미리보기 업데이트
        updatePreview(pastedData);
    });
    $('#highlightSubtitle').on('paste', function(e) {
        // 붙여넣기 데이터 가져오기
        var pastedData = (e.originalEvent || e).clipboardData.getData('text');
        // 미리보기 업데이트
        updatePreview(pastedData);
    });
    $('#formSubmit').on('click', function(event) {
        event.preventDefault(); // 기본 동작을 막습니다.

        let isValid = true;
        const requiredFields = ['fcallback', 'fdestine'];

        requiredFields.forEach(function(field) {
            if ($(`input[name="${field}"]`).val().trim() === '') {
                isValid = false;
                alert(`${$(`input[name="${field}"]`).attr('placeholder')}을(를) 입력해 주세요.`);
                return false; // 반복문 중지
            }
        });

        if (isValid) {
            $('#template-send-form').submit();
        }
    });
    $('#goTamplate').on('click', function() {
        window.location.href = '/kakao/index.php?route=tamplate'; // 원하는 URL로 변경
    });

    // 기존 이벤트 리스너를 제거하고 새로 바인딩
    $('#requestTemplate').off('submit').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        // 폼 검증이 실패하면 제출 중단
        if (!validateForm(this)) {
            return; // 폼 제출을 중단하고 함수 종료
        }

        $.ajax({
            url: 'index.php?route=saveTemplate',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert("템플릿이 성공적으로 등록되었습니다.");
                    $('#requestTemplate')[0].reset();
                } else {
                    if (response.code === '505') {
                        alert("오류: " + response.message);
                    } else if (response.code === '404') {
                        alert("오류: 요청한 리소스를 찾을 수 없습니다.");
                    } else {
                        alert("템플릿 등록에 실패했습니다: " + response.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                alert("템플릿 등록에 실패했습니다. 다시 시도해 주세요.");
                console.error('Error: ' + error);
                console.error('Status: ' + status);
                console.dir(xhr);
            }
        });
    });
    $('#requestUpdateTemplate').off('submit').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        // 폼 검증이 실패하면 제출 중단
        if (!validateForm(this)) {
            return; // 폼 제출을 중단하고 함수 종료
        }

        $.ajax({
            url: 'index.php?route=requestUpdateTemplate',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert("템플릿이 성공적으로 등록되었습니다.");
                    $('#requestTemplate')[0].reset();
                } else {
                    if (response.code === '505') {
                        alert("오류: " + response.message);
                    } else if (response.code === '404') {
                        alert("오류: 요청한 리소스를 찾을 수 없습니다.");
                    } else {
                        alert("템플릿 등록에 실패했습니다: " + response.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                alert("템플릿 등록에 실패했습니다. 다시 시도해 주세요.");
                console.error('Error: ' + error);
                console.error('Status: ' + status);
                console.dir(xhr);
            }
        });
    });

    var modal = $('#profileModal');
    var span = $('.close');

    $('.addChild').on('click', function() {
        modal.show();
        loadProfileCategory();
        loadProfiles();
    });

    span.on('click', function() {
        modal.hide();
    });

    $(window).on('click', function(event) {
        if ($(event.target).is(modal)) {
            modal.hide();
        }
    });


    $('#authenticationRequest').on('click', function() {
        // 입력 필드 값 가져오기
        var chananelName = $('#chananel_name').val().trim();
        var csPhoneNumber = $('#cs_phone_number').val().trim();

        // 입력값 검증
        if (chananelName === '') {
            alert('채널 이름을 입력해 주세요.');
            $('#chananel_name').focus();
            return;
        }

        if (!/^@\w+/.test(chananelName)) {
            alert('채널 이름은 @로 시작해야 합니다.');
            $('#chananel_name').focus();
            return;
        }

        if (csPhoneNumber === '') {
            alert('담당자 휴대폰 번호를 입력해 주세요.');
            $('#cs_phone_number').focus();
            return;
        }

        if (!/^\d{10,11}$/.test(csPhoneNumber)) {
            alert('휴대폰 번호는 10자리 또는 11자리 숫자만 가능합니다.');
            $('#cs_phone_number').focus();
            return;
        }
        authenticationRequest();
        loadProfiles();
    });
    // 페이지 로딩 시 프로필 목록을 로드
    $('#requestProfileKey').on('click', function() {
        // 입력 필드 값 가져오기
        var chananelName = $('#chananel_name').val().trim();
        var csPhoneNumber = $('#cs_phone_number').val().trim();
        var auth_token = $('#auth_token').val().trim();
        var industry = $('#industry').val().trim();
        // 입력값 검증
        if (chananelName === '') {
            alert('채널 이름을 입력해 주세요.');
            $('#chananel_name').focus();
            return;
        }

        if (!/^@\w+/.test(chananelName)) {
            alert('채널 이름은 @로 시작해야 합니다.');
            $('#chananel_name').focus();
            return;
        }

        if (csPhoneNumber === '') {
            alert('담당자 휴대폰 번호를 입력해 주세요.');
            $('#cs_phone_number').focus();
            return;
        }

        if (!/^\d{10,11}$/.test(csPhoneNumber)) {
            alert('휴대폰 번호는 10자리 또는 11자리 숫자만 가능합니다.');
            $('#cs_phone_number').focus();
            return;
        }

        if (auth_token === '') {
            alert('인증 토큰 을 입력해 주세요.');
            $('#auth_token').focus();
            return;
        }
        if (industry === '') {
            alert('카테고리를 선택 해주세요.');
            $('#industry').focus();
            return;
        }
        requestProfileKey();
        loadProfiles();
    });
    $(document).on('change', '.status-select', function() {
        var id = $(this).data('id');
        var status = $(this).val();

        $.ajax({
            url: 'index.php?route=updateStatus',
            type: 'POST',
            data: { id: id, status: status },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (!response.success) {
                    loadProfiles();
                }
            },
            error: function(xhr, status, error) {
                alert('상태 업데이트에 실패했습니다. 다시 시도해 주세요.');
                console.error('Error: ' + error);
                console.error('Status: ' + status);
                console.dir(xhr);
            }
        });
    });
    $('#profileForm').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this); // FormData 객체 생성

        // FormData 내용을 출력
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ': ' + pair[1]);
        }

        $.ajax({
            url: 'index.php?route=saveProfile',
            type: 'POST',
            data: formData,
            processData: false, // 파일 데이터를 처리하지 않음
            contentType: false, // 콘텐츠 타입을 설정하지 않음
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    $('#profileModal').hide();
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('프로필 저장에 실패했습니다. 다시 시도해 주세요.');
                console.error('Error: ' + error);
                console.error('Status: ' + status);
                console.dir(xhr);
            }
        });
    });
    $(document).on('click', '#templateSelect', function(event) {
        event.preventDefault();
        var templateId = $(this).data('id');
        loadTemplateDetails(templateId);
    });
    $(document).on('click', '#templatePagination .page-link', function(event) {
        event.preventDefault();
        var page = $(this).data('page');
        loadTemplate(page);
    });

    // 검색 버튼 클릭 이벤트 처리
    $('#searchB').on('click', function () {
        var selectedValue = $('#f-sel').val();

        if (selectedValue === "") {
            alert('1 발신 프로필 키를 선택하세요');
            $('#f-sel').focus();
        } else {
            loadTemplate(1);
        }
    });

    var modal = $('#variableModal');
    var span = $('.close');

    // 모달 열기
    $('#addVariableBtn').on('click', function() {
        modal.show();
    });

    // 모달 닫기
    span.on('click', function() {
        modal.hide();
    });

    // 모달 외부 클릭 시 닫기
    $(window).on('click', function(event) {
        if (event.target == modal[0]) {
            modal.hide();
        }
    });

    // 변수 추가
    $('#insertVariableBtn').on('click', function() {
        var variableName = $('#variableName').val().trim();
        var content = $('#previewHighlightTitle').html();
        if (variableName) {
            var currentContent = $('#highlightTitle').val();
            $('#highlightTitle').val(currentContent + ' #{' + variableName + '}');
            $('#previewHighlightTitle').html(content + ' #{' + variableName + '}');

            modal.hide();
        } else {
            alert('변수명을 입력해 주세요.');
        }
    });
    $('#goTemplateReg').on('click', function() {
        window.location.href = '/kakao/index.php?route=template'; // 이동할 URL을 여기에 입력하세요.
    });

    $(document).on('click', '.kko_icon_button', function() {
        // 1. 버튼에서 아이콘 이름과 URL 가져오기
        var iconName = $(this).data("id");
        var url = $(this).find('i').css('background-image');

        // 2. background-image가 'url("...")' 형태로 반환되므로, 이를 추출
        url = url.replace(/^url\(["']?([^"']*)["']?\)$/, '$1');

        // 3. iconMap에 아이콘을 저장 (이미 존재하는 아이콘이면 덮어쓰기)
        iconMap[iconName] = url;

        // 4. 현재 highlightTitle의 텍스트 업데이트
        var currentText = $('#highlightTitle').val();
        $('#highlightTitle').val(currentText + ' (' + iconName + ')');

        // 5. previewHighlightTitle에 이미지 추가
        var content = $('#previewHighlightTitle').html();
        var img = `<img class="view-icon" src="${url}" alt="${iconName}" />`;
        $('#previewHighlightTitle').html(content + img);

        // 6. 팝업 닫기
        $('#kkoIconPopup').hide();
    });

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
        var content = $('#previewHighlightTitle').html();
        $('#previewHighlightTitle').html(content+selectedChar);
        $('#specialCharPopup').hide(); // 선택 후 팝업 닫기
    });

});