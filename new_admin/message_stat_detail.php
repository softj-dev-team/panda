<?php
header('Content-Type: text/html; charset=UTF-8');
include_once('./login_check.php');
require './db.class.php';

$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date("Y-m-d");
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date("Y-m-d");
$sms_type = isset($_GET['sms_type']) ? $_GET['sms_type'] : 'sms';

try {
  $result = new stdClass();

  $db = new DB();

  $stmt = $db->prepare("SELECT DATE_FORMAT(wdate, '%Y-%m-%d') AS date, count(*) AS cnt, sms_type, (SELECT user_id FROM member_info WHERE sms_save.member_idx = member_info.idx) AS user_id
FROM sms_save
WHERE wdate >= date(:date_start) AND wdate <= DATE(:date_end) AND transmit_type = 'send' AND sms_type=:sms_type
GROUP BY DATE_FORMAT(wdate, '%Y%m%d'), member_idx
ORDER BY sms_type ASC");

  $stmt->bindParam(":date_start", $date_start);
  $stmt->bindParam(":date_end", $date_end);
  $stmt->bindParam(":sms_type", $sms_type);
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
                <h6 class="font-weight-bold text-primary" style="line-height:20px;">문자전송통계[개별]</h6>
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
                        <th>종류</th>
                        <th>시도</th>
                        <th>성공</th>
                        <th>실패</th>
                        <th>미수신</th>
                        <th>성공률</th>
                        <th>실패율</th>
                        <th>진행률</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $total_cnt = 0;
                      for ($i = 0; $i < sizeof($col); $i++) {
                        $total_cnt += $col[$i]['cnt'];
                      ?>
                        <tr>
                          <td><?= $col[$i]['date'] ?></td>
                          <td><?= $col[$i]['user_id'] ?></td>
                          <td>SMS</td>
                          <td><?= $col[$i]['cnt'] ?></td>
                          <td><?= $col[$i]['cnt'] ?></td>
                          <td>0</td>
                          <td>0</td>
                          <td>100.00%</td>
                          <td>0.00%</td>
                          <td>100.00%</td>
                        </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="3"><b>총합계</b></td>
                        <td><b><?= $total_cnt ?></b></td>
                        <td><b><?= $total_cnt ?></b></td>
                        <td><b>0</b></td>
                        <td><b>0</b></td>
                        <td><b>100.00%</b></td>
                        <td><b>0.00%</b></td>
                        <td><b>100.00%</b></td>
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
        location.href = './message_stat_detail.php?date_start=' + picker.startDate.format('YYYY-MM-DD') + '&date_end=' +
          picker.endDate.format('YYYY-MM-DD') + "&sms_type=<?= $sms_type ?>";
      });

    });
  </script>

</body>

</html>