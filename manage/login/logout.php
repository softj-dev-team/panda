<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; ?>

<html>
<head>

</head>
<?
session_destroy();
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
alert('로그아웃하셨습니다.');
top.location.href = "/manage/index.php";
//-->
</SCRIPT>

