<?php
header('Content-Type: text/html; charset=UTF-8');
include_once('./login_check.php');
require './db.class.php';

$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date("Y-m-d");
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date("Y-m-d");

try {
  $result = new stdClass();

  $db = new DB();


  $stmt = $db->prepare("SELECT member_point.*, member_info.user_id FROM member_point INNER JOIN member_info ON member_point.member_idx = member_info.idx
  WHERE member_point.wdate >= date(:date_start) AND member_point.wdate <= DATE(:date_end) ORDER BY member_point.wdate ASC");
  $stmt->bindParam(":date_start", $date_start);
  $stmt->bindParam(":date_end", $date_end);
  $stmt->execute();
  $col = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  var_dump($e);
}

$db = null;
/*
var_dump($date_start);
var_dump($date_end);
var_dump($col_sms);
var_dump($col_lms);
var_dump($col_mms);
*/
//var_dump($col);
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
            <h1 class="h3 mb-0 text-gray-800"></h1>
          </div>

          <!-- Content Row -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="float-left">
                <h6 class="font-weight-bold text-primary" style="line-height:20px;">충전내역</h6>
              </div>
              <div class="float-right">
                <!--
                <a href="./popup_detail.php?w=r" class="btn btn-success btn-sm">신규 등록</a>
                -->
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <div id="datepicker" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
              </div>
              <?php if (count($col) == 0) { ?>
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%" cellspacing="0">
                    <tbody>
                      <tr>
                        <td>데이터가 없습니다.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              <?php } else { ?>
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>날짜</th>
                        <th>ID</th>
                        <th>충전금액</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $total = 0;
                      for ($i = 0; $i < sizeof($col); $i++) {
                        $total += $col[$i]['pay_price'];
                      ?>
                        <tr>
                          <td><?= $col[$i]['wdate'] ?></td>
                          <td><?= $col[$i]['user_id'] ?></td>
                          <td><?= $col[$i]['pay_price'] ?></td>
                        </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="2"><b>총합계</b></td>
                        <td><b><?= $total ?></b></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              <?php } ?>

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

  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <script type="text/javascript">
    $(function() {

      var start = moment("<?= $date_start ?>", "YYYY-MM-DD");
      var end = moment("<?= $date_end ?>", "YYYY-MM-DD");

      function cb(start, end) {
        $('#datepicker span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
      }

      $('#datepicker').daterangepicker({
        startDate: start,
        endDate: end,
        locale: {
          format: "YYYY-MM-DD",
          daysOfWeek: ["일", "월", "화", "수", "목", "금", "토"],
          monthNames: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"]
        },
        ranges: {
          '오늘': [moment(), moment()],
          '어제': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          '최근 1주일': [moment().subtract(6, 'days'), moment()],
          '최근 30일': [moment().subtract(29, 'days'), moment()],
          '이번달': [moment().startOf('month'), moment().endOf('month')],
          '지난달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);

      cb(start, end);

      $('#datepicker').on('apply.daterangepicker', function(ev, picker) {
        location.href = './order_point_list.php?date_start=' + picker.startDate.format('YYYY-MM-DD') + '&date_end=' + picker.endDate.format('YYYY-MM-DD');
      });

    });
  </script>

</body>

</html>