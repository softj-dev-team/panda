<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/check_login.php"; // 공통함수 인클루드 ?>
<?
	$member_idx = $_SESSION['member_coinc_idx'];
	$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
	
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	$s_date = trim(sqlfilter($_REQUEST['s_date']));
	$e_date = trim(sqlfilter($_REQUEST['e_date']));
	
	$where_p = " and member_idx='".$member_idx."' and transmit_type='send' and is_del='N' and (case when reserv_yn = 'Y' then CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) <= '".date("Y-m-d H:i")."' else idx > 0 end)";

    // 예약 발송에 대한 조건을 추가
    $where_p .= " and (CASE WHEN reserv_yn = 'Y' THEN CONCAT(reserv_date, ' ', reserv_time, ':', reserv_minute) <= '".date("Y-m-d H:i")."' ELSE idx > 0 END)";

    // 키워드 검색 조건 추가
    if ($keyword) {
        $where_p .= " and (sms_content like '%" . $keyword . "%' or sms_title like '%" . $keyword . "%'or cell_send like '%" . $keyword . "%')";
    }

    // 종료일을 다음 날로 설정하여 00:00까지 포함
    if ($e_date) {
        // 종료일을 하루 더한 값으로 설정
        $e_date_plus_one = date('Y-m-d', strtotime($e_date . ' +1 day'));
    }

    // 날짜 검색 조건 추가
    if ($s_date && $e_date) {
        // 시작일과 종료일(종료일 + 1)을 사용한 날짜 범위 조회
        $where_p .= " and wdate >= '" . $s_date . " 00:00:00' and wdate < '" . $e_date_plus_one . " 00:00:00'";
    } elseif ($s_date) {
        // 시작일만 있을 경우, 해당 일 이후의 데이터를 조회
        $where_p .= " and wdate >= '" . $s_date . " 00:00:00'";
    } elseif ($e_date) {
        // 종료일만 있을 경우, 해당 일 이전의 데이터를 조회 (종료일 + 1일 전까지)
        $where_p .= " and wdate < '" . $e_date_plus_one . " 00:00:00'";
    } else {
        // 날짜가 없으면 오늘 날짜를 기준으로 조회
        $where_p .= " and wdate >= '" . date("Y-m-d") . " 00:00:00' and wdate < '" . date("Y-m-d", strtotime('+1 day')) . " 00:00:00'";
    }
?>
    <body>
        
        <!--header-->
         <div><? include "./common/header.php"; ?></div>   

        <!--content-->
        

        
    <section class="sub">
        <div class="sub_title">
            <h2>전송통계</h2>

        </div>
        
        <div class="adress_btn">
        </div>

        
        <div class="tab_btn_are">
            
			<form name="s_mem" id="s_mem" method="post" action="send03.php">
				<div class="input_tab">
					<input type="text" name="keyword" id="keyword" value="<?=$keyword?>">
					<a href="javascript:s_mem.submit();">
						<img src="images/search.png">
					</a>
				</div>
				
				<div class="btn">
					<span>기간선택 </span>
					<input type="text" style="width:120px;" class="datepicker" id="s_date" name="s_date" value="<?=$s_date?>">
					<span> ~ </span>
					<input type="text" style="width:120px;" class="datepicker" id="e_date" name="e_date" value="<?=$e_date?>">
					<a href="javascript:s_mem.submit();" class="blue">조회</a>
				</div>
            </form>
			
        </div>
        
        <div class="tlb center border">
            <table>
                <tr>
                    <th>상품구분</th>
                    <th>전송시도</th>
                    <th>전송성공</th>
                    <th>전송실패</th>
                    <th>사용금액</th>
                </tr>
			<?
			$tot_1 = 0;
			$tot_2 = 0;
			$tot_3 = 0;
			$tot_4 = 0;
			
				$sql_sub_1 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='sms')";
				$query_sub_1 = mysqli_query($gconnet,$sql_sub_1);
				$row['receive_cnt_tot'] = mysqli_num_rows($query_sub_1);
				$tot_1 = $tot_1+$row['receive_cnt_tot'];
				
				$sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='sms') and idx in (select fetc1 from TBL_SEND_LOG_".date("Ym")." where 1 and frsltstat='06')";
				$query_sub_2 = mysqli_query($gconnet,$sql_sub_2);
				$row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);
				$tot_2 = $tot_2+$row['receive_cnt_suc'];
				
				$sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='sms') and idx in (select fetc1 from TBL_SEND_LOG_".date("Ym")." where 1 and frsltstat!='06')";
				$query_sub_3 = mysqli_query($gconnet,$sql_sub_3);
				$row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
				$tot_3 = $tot_3+$row['receive_cnt_fail'];
				
				$use_sms_point = $row['receive_cnt_suc']*$my_member_row['mb_short_fee'];
				$tot_4 = $tot_4+$use_sms_point;
			?>
                <tr>
                    <td>단문</td>
                    <td><?=number_format($row['receive_cnt_tot'])?></td>
                    <td><?=number_format($row['receive_cnt_suc'])?></td>
                    <td><?=number_format($row['receive_cnt_fail'])?></td>
                    <td><?=number_format($use_sms_point)?></td>
                </tr>
			<?
				$sql_sub_1 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='lms')";
				$query_sub_1 = mysqli_query($gconnet,$sql_sub_1);
				$row['receive_cnt_tot'] = mysqli_num_rows($query_sub_1);
				$tot_1 = $tot_1+$row['receive_cnt_tot'];
				
				$sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='lms') and idx in (select fetc1 from TBL_SEND_LOG_".date("Ym")." where 1 and frsltstat='06')";
				$query_sub_2 = mysqli_query($gconnet,$sql_sub_2);
				$row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);
				$tot_2 = $tot_2+$row['receive_cnt_suc'];
				
				$sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='lms') and idx in (select fetc1 from TBL_SEND_LOG_".date("Ym")." where 1 and frsltstat!='06')";
				$query_sub_3 = mysqli_query($gconnet,$sql_sub_3);
				$row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
				$tot_3 = $tot_3+$row['receive_cnt_fail'];
				
				$use_sms_point = $row['receive_cnt_suc']*$my_member_row['mb_long_fee'];
				$tot_4 = $tot_4+$use_sms_point;
			?>
                <tr>
                    <td>장문</td>
                    <td><?=number_format($row['receive_cnt_tot'])?></td>
                    <td><?=number_format($row['receive_cnt_suc'])?></td>
                    <td><?=number_format($row['receive_cnt_fail'])?></td>
                    <td><?=number_format($use_sms_point)?></td>
                </tr>
			<?
				$sql_sub_1 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='mms')";
				$query_sub_1 = mysqli_query($gconnet,$sql_sub_1);
				$row['receive_cnt_tot'] = mysqli_num_rows($query_sub_1);
				$tot_1 = $tot_1+$row['receive_cnt_tot'];
				
				$sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='mms') and idx in (select fetc1 from TBL_SEND_LOG_".date("Ym")." where 1 and frsltstat='06')";
				$query_sub_2 = mysqli_query($gconnet,$sql_sub_2);
				$row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);
				$tot_2 = $tot_2+$row['receive_cnt_suc'];
				
				$sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx in (select idx from sms_save where 1 ".$where_p." and sms_type='mms') and idx in (select fetc1 from TBL_SEND_LOG_".date("Ym")." where 1 and frsltstat!='06')";
				$query_sub_3 = mysqli_query($gconnet,$sql_sub_3);
				$row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
				$tot_3 = $tot_3+$row['receive_cnt_fail'];
				
				$use_sms_point = $row['receive_cnt_suc']*$my_member_row['mb_img_fee'];
				$tot_4 = $tot_4+$use_sms_point;
			?>
                <tr>
                    <td>포토</td>
                    <td><?=number_format($row['receive_cnt_tot'])?></td>
                    <td><?=number_format($row['receive_cnt_suc'])?></td>
                    <td><?=number_format($row['receive_cnt_fail'])?></td>
                    <td><?=number_format($use_sms_point)?></td>
                </tr>
                
                <tr>
                    <td class="bgy">합계</td>
                    <td class="bgy"><?=number_format($tot_1)?></td>
                    <td class="bgy"><?=number_format($tot_2)?></td>
                    <td class="bgy"><?=number_format($tot_3)?></td>
                    <td class="bgy"><?=number_format($tot_4)?></td>
                </tr>
            
            </table>
        
        </div>
               
		<div class="point_pop">
                <h2>
                    <span><img src="images/popup/point.svg"></span>
                   알아두세요!
                </h2>
                <ul class="list_ul">
                    <li>현재 전송이 진행중인 건(예약발송 대기 등)은 포함되어 있지 않습니다.</li>
                    <li>전송통계는 최근 6개월까지 조회 가능합니다.</li>

                </ul>

        </div>
           
    </section>
   
   <div id="layer1" class="pop-layer">
    <div class="pop-container">
        <div class="popcontent">
            <div class="poptitle">
                <h2>
                연락처 추가
                </h2>
                <a href="#" class="btn-layerClose close">
                    <img src="images/popup/close.svg">
                </a>    
            </div>
            
            <div class="adress_pop">
                
                <div class="point_pop">
                <h2>
                    <span><img src="images/popup/point.svg"></span>
                  주소록 등록 안내
                </h2>
                <ul class="number_list">
                    <li>최대 50,000개 까지 등록가능</li>
                    <li>문서파일 [복사], [붙여넣기] 가능</li>
                    <li>핸드폰번호, 이름 순으로 입력</li>
                    <li>입력 예시<br>
                        <img src="images/ex_adress.png" style="margin-top: 8px">
                    </li>
                    <li>문의사항 또는 등록대행을 원하시면 고객센터로 연락주세요.</li>
 

                </ul>

                </div>
                
                
                <div class="adress_go">
                    <h2>그룹명 : 가족</h2>
                    <ul>
                        <li>
                            <input type="text" value="010-8888-1234">
                        </li>
                        <li>
                            <input type="text">
                        </li>
                        <li>
                            <input type="text">
                        </li>
                        <li>
                            <input type="text">
                        </li>
                        <li>
                            <input type="text">
                        </li>
                        <li>
                            <input type="text">
                        </li>
                    </ul>
                    
                     <div class="pagenation">
            <a href="#none" class="start">
            <img src="images/pagenation/ll.png">
            </a>
            <a href="#none" class="pre">
            <img src="images/pagenation/l.png">
            </a>
            
            <a href="#" class="atv">1</a>
            <a href="#" class="">2</a>
            <a href="#" class="">3</a>
            <a href="#" class="">4</a>
            <a href="#" class="">5</a>
            
            <a href="#none" class="next">
            <img src="images/pagenation/r.png">
            </a>
            <a href="#none" class="end">
            <img src="images/pagenation/rr.png">
            </a>
        </div>
                    
                    <p>· 이름은 입력하지 않으셔도 됩니다 <span>총<b>8</b>명</span></p>
                
                </div>
                
                
                
                
            </div>
            
            <div class="btn_are_pop">
                <a href="#" class=" btn btn02">
                    등록
                </a>
                <a href="#" class="btn-layerClose btn">
                    닫기
                </a>
            </div>
            
        </div>
    </div>
</div>           

<!--footer-->
        <div><? include "./common/footer.php"; ?></div>        

 <link rel="stylesheet" href="https://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>               
 <script>
	
	$(function() {
		$(".datepicker").datepicker({
			changeYear:true,
			changeMonth:true,
			minDate: '-90y',
			yearRange: 'c-90:c',
			dateFormat:'yy-mm-dd',
			showMonthAfterYear:true,
			constrainInput: true,
			dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
		});
	});
	
    $(document).ready(function() {
        $('#sms').on('keyup', function() {
            $('#test_cnt').html(""+$(this).val().length+"");
 
            if($(this).val().length > 100) {
                $(this).val($(this).val().substring(0, 90));
                $('#test_cnt').html("(90 / 90)");
            }
        });
    });
                    
                    
    $(document).ready(function(){
	
		$('ul.tabs li').click(function(){
			var tab_id = $(this).attr('data-tab');

			$('ul.tabs li').removeClass('current');
			$('.tab-content').removeClass('current');

			$(this).addClass('current');
			$("#"+tab_id).addClass('current');
		})

	})                
        
 </script>

    </body>
</html>

        

        







        