<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/kakao/public/head.php"; ?>

<body>

<!--header-->
<div><? require_once $_SERVER["DOCUMENT_ROOT"] ."/common/header.php"; ?></div>

<div class="container-kko">
    <div class="containerW wrap_pc">

        <div class="kakao-box">


            <div class="fm-wrap w-100">
<!--                <div class="rezCon2">-->
<!--                    <div class="guide ty2">-->
<!--                        <p><i class="exclamationI"></i>등록된템플릿 목록을 확인하기위해 발신프로필 선택 후 템플릿 메세지 유형, 템플릿 강조 유형 을 선택하세요.</p>-->
<!--                    </div>-->
<!---->
<!--                </div>-->


                <div class="templatelist">
                    <table class="board-list" id="userAlimSendList">


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
            </div>
        </div>
    </div>
</div>

<script src="/kakao/public/js/kakao.js"></script>
<script>

</script>
<!--footer-->
<div>