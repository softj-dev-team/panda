<?php
header('Content-Type: text/html; charset=UTF-8');
include_once('./login_check.php');
require './db.class.php';

try {
  $result = new stdClass();

  $db = new DB();

  $stmt = $db->prepare("SELECT member_info.*,
  (select com_name from member_info_company where 1 and is_del='N' and idx=member_info.partner_idx order by idx desc limit 0,1) as com_name,
  (select mb_short_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=member_info.idx order by idx desc limit 0,1) as mb_short_fee,
  (select mb_long_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=member_info.idx order by idx desc limit 0,1) as mb_long_fee,
  (select mb_img_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=member_info.idx order by idx desc limit 0,1) as mb_img_fee,
  (select cur_mile from member_point where 1 and point_sect='smspay' and mile_sect != 'P' and member_idx=member_info.idx order by idx desc limit 0,1) as current_point 
  FROM member_info ORDER BY wdate DESC");
  $stmt->execute();
  $col = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  var_dump($e);
}

$db = null;

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <?php include_once('./head.php'); ?>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include_once('./left_menu.php') ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php include_once('./top_bar.php') ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">회원목록</h1>
          </div>

          <!-- Content Row -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="float-left">
                <h6 class="font-weight-bold text-primary" style="line-height:20px;">회원 리스트</h6>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="card-body">

              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <td>회원명(아이디)</td>
                      <td>가맹점</td>
                      <td>SMS단가</td>
                      <td>LMS단가</td>
                      <td>MMS단가</td>
                      <td>잔액</td>
                      <td>가입일</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php for ($i = 0; $i < sizeof($col); $i++) { ?>
                      <tr>
                        <td><?= $col[$i]['user_name'] . "(" . $col[$i]['user_id'] . ")" ?></td>
                        <td><?= $col[$i]['com_name'] ?></td>
                        <td><?= $col[$i]['mb_short_fee'] ?></td>
                        <td><?= $col[$i]['mb_long_fee'] ?></td>
                        <td><?= $col[$i]['mb_img_fee'] ?></td>
                        <td><?= number_format($col[$i]['current_point']) ?></td>
                        <td><?= $col[$i]['wdate'] ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>


            </div>
          </div>


          <!-- Content Row -->


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->


    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <?php include_once('./footer.php') ?>

</body>

</html>