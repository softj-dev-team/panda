<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드
?>
<style>
    /* 모달 스타일 정의 */
    .modal {
        display: none; /* 처음 로딩 시 숨김 */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4); /* 어두운 배경 */
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background-color: #fff; /* 흰색 배경 */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* 너비를 80%로 설정 */
        max-height: 90%;
        overflow-y: auto;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        animation-name: animatetop;
        animation-duration: 0.4s;
    }
    @keyframes animatetop {
        from {top: -300px; opacity: 0}
        to {top: 0; opacity: 1}
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
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
                            <option value="52">기타 </option>
                        </select>
                    </div>
                    <div class="template-type fm-row">
                        <label>템플릿 유형</label>
                        <div class="fm-box-row">
                            <input type="radio" class="fm-rad" id="basic" name="template_type" value="01" checked>
                            <label for="basic" class="fm-rad-i">기본형</label>

                            <input type="radio" class="fm-rad" id="highlight" name="template_type" value="02">
                            <label for="highlight" class="fm-rad-i">강조표기형</label>

                            <input type="radio" class="fm-rad" id="image" name="template_type" value="03">
                            <label for="image" class="fm-rad-i">이미지첨부형</label>

                            <input type="radio" class="fm-rad" id="list" name="template_type" value="04">
                            <label for="list" class="fm-rad-i">리스트형</label>
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
                    <button class="btn-t btn-c">템플릿 등록 완료</button>
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
            <form id="profileForm" enctype="multipart/form-data" >
                <label for="chananel_name">채널명:</label>
                <input type="text" id="chananel_name" name="chananel_name" required><br>
                <label for="business_name">사업자명:</label>
                <input type="text" id="business_name" name="business_name" required><br>
                <label for="registration_number">사업자 등록번호:</label>
                <input type="text" id="registration_number" name="registration_number" required><br>
                <label for="industry">업종:</label>
                <input type="text" id="industry" name="industry" required><br>
                <label for="cs_phone_number">고객센터 전화번호:</label>
                <input type="text" id="cs_phone_number" name="cs_phone_number" required><br>
                <label for="file">파일 업로드:</label>
                <input type="file" id="file" name="file"><br>
                <button type="submit">저장</button>
                <div class="flex-c"><button type="submit" class="btn-t btn-c">저장</button></div>
            </form>
            <h2>신청 목록</h2>
            <table id="profilesTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>채널명</th>
                    <th>사업자명</th>
                    <th>등록번호</th>
                    <th>업종</th>
                    <th>고객센터 번호(발신번호)</th>
                    <th>파일</th>
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
