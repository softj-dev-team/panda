<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<body>

<!--header-->
<div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<div class="container-kko">
    <div class="containerW wrap_pc">

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
                <div class="rezCon2">
                    <div class="guide ty2">
                        <p><i class="exclamationI"></i>등록된템플릿 목록을 확인하기위해 발신프로필 선택 후 템플릿 메세지 유형, 템플릿 강조 유형 을 선택하세요.</p>
                    </div>

                </div>
                <div class="fm-row flex">
                    <div class="fm-box w-100">
                        <select id="f-sel" class="fm-sel">
                            <option value="">발신프로필 선택 *</option>
                        </select>
                        <span class="fm-error-txt">항목을 선택해 주세요.</span><!-- 에러일 경우 class="active" 추가 -->
                    </div>
                    <button class="addChild" type="button"><i class="plusI"></i>발신프로필등록</button> <button id="goTemplateReg" class="btn-t-5 btn-c-5" style="margin-left: 5px" type="button">템플릿등록</button>
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
                <div class="templatelist">
                    <table class="board-list" id="templatelistTable">

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
                    <div id="templatePagination" class="pagination"></div>
                </div>

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
                loadTemplate(page = 1,selectedValue,templateType,template_emphasize_type)
            }
        });
    });
</script>
<!--footer-->
<div>