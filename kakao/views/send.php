<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드?>
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
                                <div id="previewHighlightTitle">123</div>
                                <div id="previewHighlightSubtitle">123</div>
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
                    <button class="addChild" id="goTamplate"><i class="plusI"></i>발신프로필등록</button>
                </div>
                <div class="fm-row">
                    <div class="fm-box w-100">
                        <input type="radio" id="f-chk-all-2" class="fm-rad" name="template_type" value="일반형"><label for="f-chk-all-2" class="fm-rad-i"><strong>일반형</strong></label>
                        &nbsp;&nbsp;
                        <input type="radio" id="f-chk-all-3" class="fm-rad" name="template_type" value="강조표기형"><label for="f-chk-all-3" class="fm-rad-i"><strong>강조표기형</strong></label>
                        &nbsp;&nbsp;
                        <input type="radio" id="f-chk-all-4" class="fm-rad" name="template_type" value="이미지형"><label for="f-chk-all-4" class="fm-rad-i"><strong>이미지형</strong></label>
                        &nbsp;&nbsp;
                        <input type="radio" id="f-chk-all-5" class="fm-rad" name="template_type" value="리스트형"><label for="f-chk-all-5" class="fm-rad-i"><strong>리스트형</strong></label>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="tamplatelist flex-c">
                    <table class="board-list" id="profilesTable">

                        <colgroup><col class="tb-col-1" /><col class="tb-col-2" /><col class="tb-col-3" /><col class="tb-col-4" /><col class="tb-col-5" /></colgroup>
                        <thead>
                        <tr>
                            <th scope="col">No</th>
<!--                            <th scope="col">검색용아이디</th>-->
                            <th scope="col">템플릿명</th>
                            <th scope="col">유형</th>
                            <th scope="col">등록일자</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <form id="template-send-form" method="post" action="index.php?route=sendMessage">
                    <div class="fm-row">
                        <div class="fm-box flex-c">

                                <input type="text" name="fcallback" placeholder="발신번호">
                                <input type="text" name="fdestine" placeholder="수신자번호">
                                <input type="text" name="system" placeholder="#{서비스명}">
                                <input type="text" name="name" placeholder="#{고객명}">
                                <input type="text" name="date" placeholder="#{년월일}">

                        </div>
                        <div class="fm-box">
                            <input type="checkbox" id="f-chk-all" class="fm-chk" onchange="ui.checkedToggle(this, {view:['case1'], hide:['case1'], closest:'div'});"><label for="f-chk-all" class="fm-chk-i"><strong>대체문자 사용</strong></label>
                            <p>알림톡 발송이 실패 된 경우, 해당 내용을 문자로 대체 발송하여 누락을 방하는 기능입니다.</p>
                        </div>
                    </div>
                </form>
                <div class="fm-row">
                    <div class="fm-box">
                        <textarea name="smsmemo" placeholder="내용을 입력해 주세요." id="f-des" class="fm-ta" data-chkarea="case1" class="guide-tab-cont" "></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-wrap flex-c">
            <span class="btn-in"><a href="#none" id="formSubmit" class="btn-t btn-c">발송하기</a></span>
        </div>
    </div>
</div>

<script src="/kakao/public/js/kakao.js"></script>
<script>
    $(document).ready(function() {
        $('input[name="template_type"]').on('change', function (event) {
            // event.preventDefault();
            var selectedValue = $('#f-sel').val();

            if (selectedValue === "") {
                alert('발신 프로필 키 를 선택하세요');
                $('#f-sel').focus();
                $(this).prop('checked', false);
            }else{
                var templateType = $('input[name="template_type"]:checked').val();
                loadProfiles(page = 1,selectedValue,templateType)
            }
        });

    });


    function loadProfiles(page = 1,profile_id,template_type) {
        $.ajax({
            url: '/kakao/index.php?route=getUserTemplate',
            type: 'GET',
            data: { page: page,profile_id:profile_id,template_type:template_type},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var profilesTable = $('#profilesTable tbody');
                    profilesTable.empty();
                    response.template.forEach(function(template) {
                        var row = `<tr>
                            <td>${template.id}</td>

                            <td><a href="#" id="templateSelect" data-title="${template.template_title}" data-subtitle="${template.template_subtitle}"> ${template.template_name}<a></td>
                            <td>${template.template_type}</td>
                            <td>${template.created_at}</td>
                        </tr>`;
                        profilesTable.append(row);
                        $('#templateSelect').on('click',function(event) {
                            var templateTitle = $(this).data('title');
                            var templateSubTitle = $(this).data('subtitle');
                            $('#previewHighlightTitle').text('');
                            $('#previewHighlightSubtitle').text('');
                            $('#previewHighlightTitle').text(templateTitle)
                            $('#previewHighlightSubtitle').text(templateSubTitle)
                        });
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
</script>
<!--footer-->
<div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/footer.php"; ?></div>
</body>

<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/index_layer.php"; ?>

</html>