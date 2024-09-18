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
                            <div class="template-header blind">템플릿 헤더</div>
                            <div class="highlight-box blind">
                                <div>
                                    <div class="highlight-title-view blind" >하이라이트 타이틀</div>
                                    <div class="highlight-description-view blind">하이라이트 설명</div>
                                </div>
                                <div class="highlight-thumbnail">
                                    <img id="HighlightThumbnailImg" src="">
                                </div>
                            </div>
                            <div class="item-list-box blind">

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
                                <option value="ITEM_LIST">아이템리스트</option>
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
                    <div id="itemListSetting" class="blind">
                        <div class="fm-row">
                            <h3 >아이템 리스트 설정</h3>
                        </div>
                        <div class="flex-row fm-row">
                            <div class="fm-box custom-input-container">
                                <label for="templateHeader" class="custom-label">헤더 사용 </label>
                                <input id="templateHeader" type="text" class="fm-ipt custom-input" name="templateHeader" maxlength="16" placeholder="헤더 내용">
                            </div>
                            <div class="fm-box custom-input-container">
                                <label for="itemHighlightTitle" class="custom-label">하이라이트 제목 </label>
                                <input id="itemHighlightTitle" type="text" class="fm-ipt custom-input" name="itemHighlightTitle" maxlength="16" placeholder="하이라이트 제목">
                            </div>
                            <div class="fm-box custom-input-container">
                                <label for="itemHlightDescription" class="custom-label">하이라이트 설명  </label>
                                <input id="itemHlightDescription" type="text" class="fm-ipt custom-input" name="itemHlightDescription" maxlength="16" placeholder="하이라이트 제목">
                            </div>
                            <div class="fm-box " id="templateHighlightThumbnailUploadForm" >
                                <input name="highlightFile" type="file" id="f-attach-highlight" data-fakefile="file" />
                                <label for="f-attach-highlight" class="fm-file-btn ">파일첨부</label>
                                <input type="text" data-fakefile="text" readonly="readonly" placeholder="하이라이트 썸네일" class="fm-ipt fm-file" />
                            </div>
                        </div>

                        <div class="fm-row"></div>
                        <div id="input-container" class="flex-row">
                            <!-- 추가 버튼 -->
                            <div class="flex-c templateItem_list">
                                <div class="fm-box custom-input-container">
                                    <label for="templateItem_list_title" class="custom-label">아이템 리스트 제목 </label>
                                    <input id="templateItem_list_title" type="text" class="fm-ipt custom-input list-title" name="title[]" maxlength="6" placeholder="6자 이내">
                                </div>
                                <div class="fm-box custom-input-container">
                                    <label for="templateItem_list_description" class="custom-label">아이템 리스트 설명 </label>
                                    <input id="templateItem_list_description" type="text" class="fm-ipt custom-input list-description" name="description[]" maxlength="23" placeholder="23자 이내">
                                </div>
                                <div><button id="add-input" type="button" class="btn-c-3 btn-t-ipt">+ 추가</button></div>
                            </div>
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
                            <label for="f-title" class="fm-label custom-label">부가정보 (<span class="charCount">0/1000</span>)</label>
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

    </script>
    <!--footer-->
    <div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/footer.php"; ?></div>
</body>

<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/index_layer.php"; ?>

</html>
