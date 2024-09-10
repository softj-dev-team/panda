$(document).ready(function() {
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


    $('#highlightTitle').on('input', function() {
        updatePreview();
        // showGuide();
    });
    // $('#highlightTitle').on('input', updatePreview);
    $('#highlightSubtitle').on('input', updatePreview);


    function updatePreview() {
        var titleContent = $('#highlightTitle').val().replace(/\n/g, '<br>');
        var subtitleContent = $('#highlightSubtitle').val().replace(/\n/g, '<br>');
        $('#previewHighlightTitle').html(titleContent);
        $('#previewHighlightSubtitle').html(subtitleContent);
    }
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

});
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
$(document).ready(function() {

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
});

// 모달
$(document).ready(function() {
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

    const statusMapping = {
        'A': 'activated',
        'C': 'deactivated',
        'B': 'block',
        'E': 'deleting',
        'D': 'deleted',
        '01': '승인',
        '02': '승인대기'
    };
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
});
$(document).on('click', '#templateSelect', function(event) {
    event.preventDefault();
    var templateId = $(this).data('id');
    loadTemplateDetails(templateId);
});
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
                var templateTitle = template.template_title;
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
                    $('#previewHighlightSubtitle').before(generatedButton);
                });

                $('#previewHighlightTitle').html(convertToHtml(templateTitle));
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
        'S': '중단'
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
$(document).on('click', '#templatePagination .page-link', function(event) {
    event.preventDefault();
    var page = $(this).data('page');
    loadTemplate(page);
});
// 검색 버튼 클릭 이벤트 핸들러 추가

$(document).ready(function() {

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
});
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

function showGuide() {
    console.log(11)
    const emphasisType = $('input[name="template_type"]:checked').val();
    const guide = $('#guide');
    guide.empty();  // 초기화

    if (emphasisType === '01') {

    } else if (emphasisType === '02') {
        guide.html(`
                    <div>가이드: <a href="https://kakaobusiness.gitbook.io/main/ad/bizmessage/notice-friend/content-guide#id-2-2" target="_blank">강조표기형 가이드</a></div>
                    <div><label>강조 제목: </label><input type="text" name="strong_title" id="strongTitle" maxlength="23"></div>
                    <div><label>강조 보조: </label><input type="text" name="strong_sub_title" id="strongSubTitle" maxlength="18"></div>
                `);
        $('#strongSubTitle').on('input',function (){
            $('#previewStrongSubTitle').text($('#strongSubTitle').val())
        });
        $('#strongTitle').on('input',function (){
            $('#previewStrongTitle').text($('#strongTitle').val())
        });
        $('#previewHighlightTitle').css('border-top', '1px solid #bbb');
    } else if (emphasisType === '03') {
        guide.html(`
                    <div class="flex-space-around kakao-image">
                        <div class="flex-c-c image-container selected" data-value="01"><img src="/kakao/public/images/kakao/image_template_1.a9803fc2.png"><a href="https://kakaobusiness.gitbook.io/main/ad/bizmessage/notice-friend/content-guide/image#1." target="_blank">로고형가이드 </a></div>
                        <div class="flex-c-c image-container" data-value="02"><img src="/kakao/public/images/kakao/image_template_2.554c4247.png"><a href="https://kakaobusiness.gitbook.io/main/ad/bizmessage/notice-friend/content-guide/image#2." target="_blank">텍스트 혼합형 가이드</a></div>
                        <div class="flex-c-c image-container" data-value="03"><img src="/kakao/public/images/kakao/image_template_3.501b82ec.png"><a href="https://kakaobusiness.gitbook.io/main/ad/bizmessage/notice-friend/content-guide/image#3." target="_blank">아이콘형 가이드</a></div>
                    </div>
                    <div class="upload-guidelines">
                        <p> 가로 너비 500px 이상 (권장 800px * 400px)</p>
                        <p> 가로:세로 비율이 2:1</p>
                        <p> JPEG, JPG, PNG 확장자</p>
                        <p> 파일 사이즈 최대 500KB</p>
                    </div>
                    <input type="hidden" name="selectedImage" id="selectedImage">
                    <div><label>알림톡 이미지 업로드: </label> <input type="file" name="file" id="imageInput" accept=".png, .jpg, .jpeg"></div>
                `);
        $('.image-container').each(function() {
            if ($(this).data('value') !== "01") {
                $(this).find('.status').show();
            }
        });

        $('.image-container').on('click', function() {
            $('.image-container').removeClass('selected');
            $(this).addClass('selected');
            $('#selectedImage').val($(this).data('value'));
        });

        $('#imageForm').on('submit', function(e) {
            e.preventDefault();
            alert('선택된 값: ' + $('#selectedImage').val());
            // 여기서 폼 데이터를 서버로 전송하는 코드를 추가할 수 있습니다.
        });

        $('#f-attach').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgElement = $('#uploadedImage');
                    imgElement.attr('src', e.target.result);
                    console.log(e.target.result);
                    imgElement.show();

                    // 이미지 조건 확인
                    const img = new Image();
                    img.onload = function() {
                        if ((img.width <= 800 && img.height <= 400) && (file.type === 'image/png' || file.type === 'image/jpeg')) {
                            imgElement.attr('src', e.target.result);
                            imgElement.show();
                        } else {
                            alert('이미지는 png 또는 jpg 형식이어야 하며, 크기는 800x400px 이하여야 합니다.');
                            imgElement.hide();
                            $('#imageInput').val(''); // 파일 입력 필드 초기화
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    } else if (emphasisType === '04') {
        guide.html(`
                    <div>가이드: <a href="https://kakaobusiness.gitbook.io/main/ad/bizmessage/notice-friend/content-guide#id-2-3" target="_blank">아이템리스트형 가이드</a></div>
                    <div><label>리스트형 입력항목: </label>
                        <input type="checkbox" name="item_input[]" value="image"> 이미지
                        <input type="checkbox" name="item_input[]" value="header"> 헤더
                        <input type="checkbox" name="item_input[]" value="highlight"> 아이템 하이라이트
                        <input type="checkbox" name="item_input[]" value="summary"> 아이템요약정보
                    </div>
                    <div>
                        <label>아이템 리스트: </label>
                        <div><input type="text" name="item_list[]" placeholder="아이템명 (0~23자)">
                        <textarea name="item_description[]" placeholder="내용 (0~23자)"></textarea></div>
                        <button type="button" onclick="addItem()">추가</button>
                    </div>
                `);
    }
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

$(document).ready(function() {
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
        if (variableName) {
            var currentContent = $('#highlightTitle').val();
            $('#highlightTitle').val(currentContent + ' #{' + variableName + '}');
            $('#previewHighlightTitle').text(currentContent + ' #{' + variableName + '}');

            modal.hide();
        } else {
            alert('변수명을 입력해 주세요.');
        }
    });
});
function convertToHtml(text) {
    if (typeof text === 'string') {
        return text.replace(/\n/g, '<br>');
    }
    return '';
}
$(document).ready(function() {
    $('#goTemplateReg').on('click', function() {
        window.location.href = '/kakao/index.php?route=template'; // 이동할 URL을 여기에 입력하세요.
    });

});

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
