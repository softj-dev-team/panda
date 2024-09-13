<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<body>

<!--header-->
<div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<div class="container-kko">
    <div class="containerW wrap_pc">

        <div class="kakao-box">
            <div class="preview-section">
                <h2>템플릿 목록</h2>
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
                    <table  id="templatelistTable">

                        <colgroup><col class="tb-col-1" /><col class="tb-col-3" /><col class="tb-col-1" /><col class="tb-col-3" /><col class="tb-col-3" /></colgroup>
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

            </div>
        </div>
    </div>
</div>


<script>

</script>
<!--footer-->
<div>