<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<style>

</style>
<body>

<!--header-->
<div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<div class="wrap">
    <div class="containerW wrap_pc">
        <div class="examREZ">
            <div class="rez_tit">
                <h3 class="hTIt"><i class="guide_i"></i>알림톡</h3>
            </div>
            <div class="rezCon2">
                <div class="guide ty2">
                    <p><i class="exclamationI"></i>발송페이지에서는 현재 <span class="textB2">이용가능한</span> 발신프로필만 보여집니다.</p>
<!--                    <p><i class="exclamationI"></i>학원을 선택한 후 입학시험 일정을 <span class="textB2">예약하거나 전화로 문의</span> 바랍니다.</p>-->
<!--                    <p><i class="exclamationI"></i>입학을 원하는 브랜드 선택 후 자세한 <span class="textB2">입학절차를 확인</span> 하실 수 있습니다.</p>-->
                </div>
<!--                <div class="guide_tab">-->
<!--                    <div class="step_guide">-->
<!--                        <p>Grand Slam은 대치, 목동, 마포, 관악, 중계, 평촌, 송도, 일산, 분당, 서초, 잠실에서 운영하고 있습니다. </p>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>

        <div class="kakao-box">
            <div class="preview-section">
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
            <div class="fm-wrap w-100">

                <div class="fm-row flex">
                    <div class="fm-box w-100">
                        <select id="f-sel" class="fm-sel">
                            <option value="">발신프로필 선택 *</option>
                        </select>
                        <span class="fm-error-txt">항목을 선택해 주세요.</span><!-- 에러일 경우 class="active" 추가 -->
                    </div>
                    <button class="addChild"><i class="plusI"></i>발신프로필등록</button><button id="goTemplateReg" class="btn-t-5 btn-c-5" style="margin-left: 5px" type="button">템플릿등록</button>
                </div>
                <div class="rezCon2">
                    <div class="guide ty2">
                        <p><i class="exclamationI"></i>등록된템플릿 목록을 확인하기위해 발신프로필 선택 후 신청유형, 템플릿 강조 유형 을 선택하세요.</p>
                    </div>

                </div>
                <div class="fm-row">
                    <label class="fm-label">템플릿 메세지 유형</label>
                    <div class="fm-box-row">
                        <input type="radio" class="fm-rad" id="basic" name="template_type" value="BA">
                        <label for="basic" class="fm-rad-i">기본형</label>
                    </div>
                    <label class="fm-label">템플릿 강조 유형</label>
                    <div class="fm-box-row">
                        <input type="radio" class="fm-rad" id="NONE" name="template_emphasize_type" value="NONE" checked>
                        <label for="NONE" class="fm-rad-i">선택안함</label>
                        <input type="radio" class="fm-rad" id="TEXT" name="template_emphasize_type" value="TEXT">
                        <label for="TEXT" class="fm-rad-i">강조표기형</label>

                        <!--                            <input type="radio" class="fm-rad" id="image" name="template_type" value="03">-->
                        <!--                            <label for="image" class="fm-rad-i">이미지첨부형</label>-->

                        <!--                            <input type="radio" class="fm-rad" id="list" name="template_type" value="ITEM_LIST">-->
                        <!--                            <label for="list" class="fm-rad-i">리스트형</label>-->
                    </div>
                </div>
                <div class="tamplatelist flex-c">
                    <table class="board-list" id="templatelist">

<!--                        <colgroup><col class="tb-col-1" /><col class="tb-col-2" /><col class="tb-col-3" /><col class="tb-col-4" /><col class="tb-col-5" /></colgroup>-->
                        <thead>
                        <tr>
                            <th scope="col">No</th>
                            <!--                            <th scope="col">검색용아이디</th>-->
                            <th scope="col">템플릿명</th>
                            <th scope="col">유형</th>
                            <th scope="col">등록일</th>
                            <th scope="col">검수결과</th>
                            <th scope="col">상태</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <form id="uploadTemplateForm" enctype="multipart/form-data" method="post" action="index.php?route=uploadTemplate" class="flex-between" style="margin-top:20px;margin-bottom:20px">
                    <input type="file" name="templateFile" id="templateFile">
                    <button type="submit" id="uploadTemplateButton" class="btn-t-3 btn-c-3 ">파일업로드</button>
                </form>

                <form id="template-send-form" method="post" action="index.php?route=sendMessage">
                    <div class="fm-row">
                        <h2>단일 건 발송</h2>
                        <div class="rezCon2">
                            <div class="guide ty2">
                                <p><i class="exclamationI"></i>여러건의 알림톡 발송 시 템플릿 을 선택 후 샘플파일을 다운로드 받아 작성 후 샘플 파일 업로드 기능을 이용하세요.</p>
                                <p><i class="exclamationI red"></i>엑셀 에 수신번호 입력 하기 전 셀서식을 텍스트로 변경하세요 .</p>
                            </div>

                        </div>
                        <div class="fm-box flex-c">

                        </div>

                        <h2>업로드된 파일 데이터</h2>
                        <div class="rezCon2">
                            <div class="guide ty2">
                                <p><i class="exclamationI"></i>등록내역 확인(등록된 상위 10개 내역만 표시 됩니다.)</p>
                            </div>

                        </div>

                        <table id="uploadedDataTable" class="board-list">
                            <thead>
                            <tr>
                                <!-- 여기에 업로드된 데이터의 헤더가 표시됩니다. -->
                            </tr>
                            </thead>
                            <tbody>
                            <!-- 업로드된 데이터가 여기에 표시됩니다. -->
                            </tbody>
                        </table>

                        <div class="fm-box">
                            <input type="checkbox" id="f-chk-all" class="fm-chk"><label for="f-chk-all" class="fm-chk-i"><strong>대체문자 사용</strong></label>
                            <p>알림톡 발송이 실패 된 경우, 해당 내용을 문자로 대체 발송하여 누락을 방하는 기능입니다.</p>
                        </div>
                    </div>
                </form>

                <div class="fm-row">
                    <div class="fm-box">
                        <textarea name="smsmemo" placeholder="내용을 입력해 주세요." id="f-des" class="fm-ta" data-chkarea="case1" class="guide-tab-cont" "></textarea>
                    </div>
                </div>
                <div class="btn-wrap flex-c">
                    <a href="#none" id="sendMessagesButton" class="btn-t-2 btn-c-2">발송하기</a>
                </div>
                <br>
            </div>

        </div>

    </div>
</div>
<!-- 모달 -->
<div id="profileModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="profileForm"  class="flex-column">

            <label for="chananel_name" class="fm-label">채널 이름: 예) @채널명 (검색용아이디) </label>
            <input type="text" class="fm-ipt" id="chananel_name" name="chananel_name" required><br>

            <label for="cs_phone_number" class="fm-label">담당자 휴대폰 번호</label>
            <div class="flex-between">
                <input type="text" id="cs_phone_number" name="cs_phone_number" required class="fm-ipt"><button type="button" id="authenticationRequest" class="btn-t-2 btn-c-3">인증요청</button>
            </div>
            <br>
            <label for="auth_token" class="fm-auth_token fm-label" >인증토큰</label>
            <div class="flex-between">
                <input type="text" id="auth_token" name="auth_token" required class="fm-ipt"><button type="button" id="requestProfileKey" class="btn-t-2 btn-c-3">채널 연동</button>
            </div>
            <br>
            <label for="chananel_name" class="fm-label flex">카테고리 </label>
            <div class="board-sch bs-col-3 flex-between">
                <div class="sch-sel">
                    <select id="category1" class="fm-sel-2">
                        <option value="">대분류 선택</option>
                    </select>
                </div>
                <div class="sch-sel">
                    <select id="category2" class="fm-sel-2">
                        <option value="">중분류 선택</option>
                    </select>
                </div>
                <div class="sch-sel">
                    <select id="category3" class="fm-sel-2">
                        <option value="">소분류 선택</option>
                    </select>
                </div>
            </div>
            <input type="hidden" id="industry" name="industry">
        </form>
        <h2>신청 목록</h2>
        <table id="profilesTable">
            <thead>
            <tr>
                <th>NO</th>
                <th>채널명</th>
                <th>발신프로필키</th>
                <th>카테고리</th>
                <th>고객센터 번호(발신번호)</th>
                <th>상태</th>

            </tr>
            </thead>
            <tbody>
            <!-- 데이터가 동적으로 추가됩니다 -->
            </tbody>
        </table>
    </div>
</div>
<script src="/kakao/public/js/kakao.js"></script>
<script>
    $(document).ready(function() {
        $('#sendMessagesButton').click(function () {
            var formData = new FormData($('#template-send-form')[0]);
            var fileInput = $('#templateFile')[0];

            if (fileInput.files.length > 0) {
                formData.append('templateFile', fileInput.files[0]);
            }

            $.ajax({
                url: 'index.php?route=sendMessage',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        alert(data.message);
                    } else {
                        alert( data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error: ' + error);
                    console.error('Status: ' + status);
                    console.dir(xhr);
                }
            });
        });
        $('input[name="template_type"], input[name="template_emphasize_type"]').on('change', function (event) {
            // event.preventDefault();
            var selectedValue = $('#f-sel').val();

            if (selectedValue === "") {
                alert('발신 프로필 키 를 선택하세요');
                $('#f-sel').focus();
                $(this).prop('checked', false);
            }else{
                var templateType = $('input[name="template_type"]:checked').val();
                var template_emphasize_type = $('input[name="template_emphasize_type"]:checked').val();
                loadProfiles(page = 1,selectedValue,templateType,template_emphasize_type)
                // loadTemplate(page = 1,selectedValue,templateType,template_emphasize_type)
            }
        });

        $(document).on('click', '#templateSelect', function(event) {
            event.preventDefault();
            var templateId = $(this).data('id');
            loadTemplateDetails(templateId);
        });

        $('#uploadTemplateForm').on('submit', function(event) {
            event.preventDefault();

            var selectedTemplateType = $('input[name="template_type"]:checked').val();
            var selectTemplateKey = $('input[name="template_key"]').val();

            if (!selectedTemplateType) {
                alert('템플릿 유형을 선택하세요');
                return;
            }
            if (!selectTemplateKey) {
                alert('전송대상 템플릿 을 선택하세요');
                return;
            }

            var formData = new FormData(this);

            $.ajax({
                url: 'index.php?route=uploadTemplate',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('파일 업로드 성공');
                        drawTable(response.data); // 파일 업로드 후 데이터 테이블 그리기
                    } else {
                        alert('파일 업로드 실패: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                    console.error('Status: ' + status);
                    console.dir(xhr);
                }
            });
        });
    });

    function updatePreview(templateTitle) {
        var filledTemplate = templateTitle;
        $('input[name="variables[]"]').each(function() {
            var varName = $(this).data('varname');
            var varValue = $(this).val();
            var regex = new RegExp('#{' + varName + '}', 'g');
            filledTemplate = filledTemplate.replace(regex, varValue);
        });
        $('#previewHighlightTitle').html(convertToHtml(filledTemplate));
        // $('#previewHighlightTitle').text(filledTemplate);
        $('input[name="message"]').val(filledTemplate);
    }
    function loadTemplateDetails(templateId) {
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
                    var template_id = template.id;
                    if (strongTitle) {
                        $('#previewHighlightTitle').css('border-top', '1px solid #bbb');
                    } else {
                        $('#previewHighlightTitle').css('border-top', 'none');
                    }

                    $('#previewHighlightTitle').html(convertToHtml(templateTitle));
                    $('#previewHighlightSubtitle').text(templateSubTitle);
                    $('#previewStrongTitle').text(strongTitle);
                    $('#previewStrongSubTitle').text(strongSubTitle);

                    // template_title의 변수를 추출하여 동적으로 input 생성
                    var regex = /#\{(.*?)\}/g;
                    var matches;
                    var inputFields = '' +
                        '<input type="hidden" name="template_id" value="' + template_id + '">' +
                        '<input type="hidden" name="template_key" value="' + template_key + '">' +
                        '<input type="hidden" name="profile_key" value="' + profile_key + '">' +
                        '<input type="hidden" name="fcallback" value="' + fcallback + '">' +
                        '<input type="text" name="fdestine" placeholder="수신자번호">' +
                        '<input type="hidden" name="ori_message" value="' + templateTitle + '">' +
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
    function loadProfiles(page = 1,profile_id,template_type,template_emphasize_type) {
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
            'REQ': '심사요청',
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
        $.ajax({
            url: '/kakao/index.php?route=getUserTemplate',
            type: 'GET',
            data: { page: page,profile_id:profile_id,template_type:template_type,template_emphasize_type:template_emphasize_type},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var profilesTable = $('#templatelist tbody');
                    profilesTable.empty();
                    response.template.forEach(function(template) {
                        var statusText = statusMapping[template.status];
                        var templateText = templateTypeMapping[template.template_type]
                        var inspectionStatusText = inspectionStatusMapping[template.inspection_status]
                        var row = `<tr>
                            <td>${template.id}</td>
                            <td><a href="#" id="templateSelect" data-id="${template.id}">${template.template_name}</a></td>
                            <td>${templateText}</td>
                            <td>${template.created_at}</td>
                            <td>${inspectionStatusText}</td>
                            <td>${statusText}</td>
                            <td><button type="button" class="btn-t-3 btn-c-3" onclick="window.location.href='index.php?route=downloadSample&template_id=${template.id}'"><i class="fa fa-file-excel"></i> 샘플다운로드</button></td>
                        </tr>`;
                        profilesTable.append(row);
                    });

                    // 페이징 처리
                    var totalPages = Math.ceil(response.total / 10);
                    var pagination = $('#pagination');
                    pagination.empty();
                    for (var i = 1; i <= totalPages; i++) {
                        var pageLink = `<a href="#" class="page-link ${i === page ? 'on' : ''}" data-page="${i}">${i}</a>`;
                        pagination.append(pageLink);
                    }
                }else{
                    var profilesTable = $('#profilesTable tbody');
                    profilesTable.empty();
                    var row =' ' +
                        '<tr>'+
                        '<td colspan="4" class="no-data"><span class="ir-b i-nodata">검색 결과가 없습니다.</span></td>'+
                        '</tr>'

                    profilesTable.append(row);
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
    function drawTable(data) {
        var tableHead = $('#uploadedDataTable thead tr');
        var tableBody = $('#uploadedDataTable tbody');
        tableHead.empty();
        tableBody.empty();

        if (data.length > 0) {
            // 첫 번째 행을 테이블 헤더로 사용
            var headers = data[0];
            headers.forEach(function(header) {
                tableHead.append('<th>' + header + '</th>');
            });

            // 나머지 행을 테이블 본문으로 사용 (최대 10개 행만)
            var rowsToShow = data.slice(1, 11); // 첫 번째 행을 제외한 최대 10개의 행
            rowsToShow.forEach(function(row) {
                var rowHtml = '<tr>';
                row.forEach(function(cell) {
                    rowHtml += '<td>' + cell + '</td>';
                });
                rowHtml += '</tr>';
                tableBody.append(rowHtml);
            });
        } else {
            tableBody.append('<tr><td colspan="100%">데이터가 없습니다.</td></tr>');
        }
    }
</script>
<!--footer-->
<div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/footer.php"; ?></div>
</body>

<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/index_layer.php"; ?>

</html>