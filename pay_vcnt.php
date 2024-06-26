<? include "./common/head.php";
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
extract($_REQUEST);
$query = "select * from purchase_info where idx=$idx";
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$result = mysqli_query($gconnet, $query);
$row = mysqli_fetch_assoc($result);
?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->
    <section class="sub">
        <h2>가상계좌 입금해주셔야할 정보</h2>
        <p>입금자명 : <?= $row['va_name'] ?></p>
        <p>받는사람 : <?= $row['depositor'] ?></p>
        <p>은행 : <?= $row['bankname'] ?></p>
        <p>계좌번호 : <?= $row['account'] ?></p>

    </section>

    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

</body>

</html>