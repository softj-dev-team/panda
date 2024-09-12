<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<style>

</style>
<body>

<!--header-->
<div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<div class="wrap">
    <div class="containerW wrap_pc">


        <div class="kakao-box">
            <div class="preview-section">
                <h2>알림톡 발송</h2>
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
                                <div id="previewChButtonList">
                                </div>
                                <div id="previeButtonList"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <p class="preview-note">미리보기는 실제 단말기와 차이가 있을 수 있습니다.</p>
            </div>
            <div class="fm-wrap w-100">
                <div class="rezCon2">
                    <div class="guide ty2">
                        <p><i class="exclamationI"></i>등록된템플릿 목록을 확인하기위해 발신프로필 선택 후 신청유형, 템플릿 강조 유형 을 선택하세요.</p>
                    </div>

                </div>
                <div class="fm-row flex">
                    <div class="fm-box w-100">
                        <select id="f-sel" class="fm-sel">
                            <option value="">발신프로필 선택 *</option>
                        </select>
                        <span class="fm-error-txt">항목을 선택해 주세요.</span><!-- 에러일 경우 class="active" 추가 -->
                    </div>
                    <button class="addChild"><i class="plusI"></i>발신프로필등록</button><button id="goTemplateReg" class="btn-t-5 btn-c-5" style="margin-left: 5px" type="button">템플릿등록</button>
                </div>

                <form name="searchForm">
                    <div class="board-sch bs-col-3 flex-between">
                        <div class="sch-sel">
                            <label for="f-search-sel-2" class="blind">검색 카테고리</label>
                            <select id="f-search-sel-2" class="fm-sel-2" name="template_type">
                                <option value="">메세지 유형</option>
                                <option value="BA">기본형</option>
                                <!--                            <option value="">NEWS</option>-->
                            </select>
                        </div>
                        <div class="sch-sel">
                            <label for="f-search-sel-3" class="blind">검색 카테고리 2</label>
                            <select id="f-search-sel-3" class="fm-sel-2" name="template_emphasize_type">
                                <option value="">강조 유형</option>
                                <option value="NONE">선택안함</option>
                                <option value="TEXT">강조표기형</option>
                            </select>
                        </div>
                        <div class="sch-sel">
                            <label for="f-search-sel-3" class="blind">검수상태</label>
                            <select id="f-search-sel-3" class="fm-sel-2" name="inspection_status">
                                <option value="">검수상태</option>
                                <option value="REG">등록</option>
                                <option value="REQ">검수요청</option>
                                <option value="APR">승인</option>
                                <option value="REJ">반려</option>
                            </select>
                        </div>
                        <div class="sch-sel">
                            <label for="f-search-sel-3" class="blind">승인상태</label>
                            <select id="f-search-sel-3" class="fm-sel-2" name="status">
                                <option value="">사용상태</option>
                                <option value="R">승인대기</option>
                                <option value="A">정상</option>
                                <option value="S">중단</option>
                            </select>
                        </div>
                        <div class="sch-ipt">
                            <label for="f-search-ipt" class="blind">검색어 입력</label>
                            <input type="text" name="template_title" id="f-search-ipt" class="fm-ipt-2" placeholder="검색어를 입력해 주세요." />
                            <button type="button" class="btn-sch" id="searchB"><span class="ir i-sch">검색</span></button>
                        </div>
                    </div>
                </form>
                <div class="tlb center border">
                    <table id="templatelistTable">

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
                            <th scope="col">다운로드</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div id="templatePagination" class="pagenation"></div>
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

        // $(document).on('click', '#templateSelect', function(event) {
        //     event.preventDefault();
        //     var templateId = $(this).data('id');
        //     loadTemplateDetails(templateId);
        // });

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