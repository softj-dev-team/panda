<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>판다문자</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <link rel="stylesheet" type="text/css" href="/css/common.css?ver=240126">
    <link rel="stylesheet" type="text/css" href="/css/main.css?ver=240126">
    <? if ($_SERVER['SCRIPT_NAME'] != "/index.php") { ?>
        <link rel="stylesheet" type="text/css" href="/css/sub.css">
    <? } ?>
    <? if ($_SERVER['SCRIPT_NAME'] == "/board_list.php" || $_SERVER['SCRIPT_NAME'] == "/board_detail.php") { ?>
        <link rel="stylesheet" type="text/css" href="/css/board.css">
    <? } ?>
    <link rel="stylesheet" type="text/css" href="/css/slick.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
    <script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
    <script type="text/javascript"  src="/js/pandasub.js"></script>
</head>