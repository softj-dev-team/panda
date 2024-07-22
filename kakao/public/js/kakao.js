$(document).ready(function() {
    // 서버에서 사용자 프로필 가져오기
    $.ajax({
        url: 'index.php?route=getUserProfiles',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var select = $('#f-sel');
            select.empty(); // 기존 옵션 제거
            select.append('<option value="">발신프로필 선택 *</option>');

            if (response.success && response.data.length > 0) {
                $.each(response.data, function(index, profile) {
                    select.append('<option value="' + profile.id + '">' + profile.business_name + '</option>');
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

    $('#previewHighlightTitle').text('');
    $('#previewHighlightSubtitle').text('');
    function updatePreview() {
        var titleContent = $('#highlightTitle').val().replace(/\n/g, '<br>');
        var subtitleContent = $('#highlightSubtitle').val().replace(/\n/g, '<br>');
        $('#previewHighlightTitle').html(titleContent);
        $('#previewHighlightSubtitle').html(subtitleContent);
    }
    $('#formSubmit').on('click', function(event) {
        event.preventDefault(); // 기본 동작을 막습니다.

        let isValid = true;
        const requiredFields = ['fcallback', 'fdestine', 'system', 'name', 'date'];

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
        console.log(11)
        window.location.href = '/kakao/index.php?route=tamplate'; // 원하는 URL로 변경
    });
});

$(document).ready(function() {
    $('#requestTemplate').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        // 기본 검증
        if (formData.get('profile_id') === "") {
            alert("발신프로필을 선택해 주세요.");
            return;
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
                    // location.reload();
                } else {
                    alert("템플릿 등록에 실패했습니다: " + response.message);
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
        '01': '승인',
        '02': '승인대기'
    };

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
                            <td>${profile.business_name}</td>
                            <td>${profile.registration_number}</td>
                            <td>${profile.industry}</td>
                            <td>${profile.cs_phone_number}</td>
                            <td>${profile.file_path ? `<a href="${profile.file_path}" target="_blank">파일 열람</a>` : '없음'}</td>
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

function loadCategories() {
    $.getJSON('index.php?route=getCategories', function(categories) {
        const categorySelect = $('#category');

        const parentCategories = categories.filter(category => category.parent_id === null);
        parentCategories.forEach(parentCategory => {
            const optgroup = $('<optgroup>').attr('label', parentCategory.name);

            const childCategories = categories.filter(category => category.parent_id === parentCategory.id);
            childCategories.forEach(childCategory => {
                const option = $('<option>').val(childCategory.id).text(childCategory.name);
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

        $('#imageInput').on('change', function(event) {
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

