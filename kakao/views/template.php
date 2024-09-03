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
                            <span class="fm-error-txt">항목을 선택해 주세요.</span>
                        </div>
                        <button class="addChild" type="button"><i class="plusI"></i>발신프로필등록</button>
                    </div>

                    <div class="channel-link">

                        <select id="category" name="category_id" class="fm-sel">
                            <option value="999999">기타 </option>
                        </select>
                    </div>
                    <div class="template-type fm-row">
                        <label class="fm-label">템플릿 메세지 유형</label>
                        <div class="fm-box-row">
                            <input type="radio" class="fm-rad" id="basic" name="template_type" value="BA" checked>
                            <label for="basic" class="fm-rad-i">기본형</label>
                        </div>
                        <label class="fm-label">템플릿 강조 유형</label>
                        <div class="fm-box-row">
                            <input type="radio" class="fm-rad" id="basic" name="template_emphasize_type" value="NONE" checked>
                            <label for="basic" class="fm-rad-i">선택안함</label>

                            <input type="radio" class="fm-rad" id="highlight" name="template_emphasize_type" value="TEXT">
                            <label for="highlight" class="fm-rad-i">강조표기형</label>

                            <!--                            <input type="radio" class="fm-rad" id="image" name="template_type" value="03">-->
                            <!--                            <label for="image" class="fm-rad-i">이미지첨부형</label>-->

                            <!--                            <input type="radio" class="fm-rad" id="list" name="template_type" value="ITEM_LIST">-->
                            <!--                            <label for="list" class="fm-rad-i">리스트형</label>-->
                        </div>
                    </div>
                    <div class="template-content">
                        <label for="templateName">템플릿 이름 (선택사항)</label>
                        <input type="text" id="templateName" name="template_name">
                        <div id="guide">



                        </div>
                        <div>
                            <label>내용:</label><textarea name="template_title" id="highlightTitle"></textarea>
                        </div>
                        <div>
                            <button type="button" class="btn-c-4 btn-t" id="addVariableBtn">변수추가</button>
                        </div>
                        <div>
                            <label>부가정보: </label><textarea name="template_subtitle" id="highlightSubtitle"></textarea>
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
                <label for="auth_token" class="fm-auth_token fm-label">인증토큰</label>
                <div class="flex-between">
                    <input type="text" id="auth_token" name="auth_token" required class="fm-ipt"><button type="button" id="requestProfileKey" class="btn-t-2 btn-c-3">채널 연동</button>
                </div>
                <br>
                <label for="chananel_name" class="fm-label flex">카테고리 </label>
                <div class="fm-col-3">
                    <div class="fm-box fm-col-in-full">
                        <select id="category1" class="fm-sel-2">
                            <option value="">대분류 선택</option>
                        </select>
                    </div>
                    <div class="fm-box fm-col-in-half">
                        <select id="category2" class="fm-sel-2">
                            <option value="">중분류 선택</option>
                        </select>
                    </div>
                    <div class="fm-box fm-col-in-half">
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
        $('input[name="template_type"]').change(function() {
            showGuide();
        });
    </script>
    <!--footer-->
    <div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/footer.php"; ?></div>
</body>

<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/index_layer.php"; ?>

</html>
