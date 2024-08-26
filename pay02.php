<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/check_login.php"; // 공통함수 인클루드 ?>
<?
$member_idx = $_SESSION['member_coinc_idx'];

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

$keyword = trim(sqlfilter($_REQUEST['keyword']));
$s_date =  trim(sqlfilter($_REQUEST['s_date']));
$e_date =  trim(sqlfilter($_REQUEST['e_date']));
$v_cate =  trim(sqlfilter($_REQUEST['v_cate']));
$field = trim(sqlfilter($_REQUEST['field']));
$s_pay_type =  trim(sqlfilter($_REQUEST['s_pay_type']));
$s_pay_sect =  trim(sqlfilter($_REQUEST['s_pay_sect']));

$v_sect =  trim(sqlfilter($_REQUEST['v_sect']));
$s_receipt_ok =  trim(sqlfilter($_REQUEST['s_receipt_ok']));
$s_taxbill_ok =  trim(sqlfilter($_REQUEST['s_taxbill_ok']));
$s_mem_sect =  trim(sqlfilter($_REQUEST['s_mem_sect'])); // 주문자 구분
$s_group = trim(sqlfilter($_REQUEST['s_group'])); // 입점업체

################## 파라미터 조합 #####################
$total_param = 'keyword='.$keyword.'&s_date='.$s_date.'&e_date='.$e_date.'&v_cate='.$v_cate.'&field='.$field.'&s_pay_type='.$s_pay_type.'&s_pay_sect='.$s_pay_sect.'&v_sect='.$v_sect.'&s_receipt_ok='.$s_receipt_ok.'&s_taxbill_ok='.$s_taxbill_ok.'&s_mem_sect='.$s_mem_sect.'&s_group='.$s_group;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and a.is_del='N' and a.contents_tbname='member_point' and a.member_idx='".$member_idx."'";

if ($keyword){
	$where .= " and ( a.order_num like '%".$keyword."%' or a.order_name like '%".$keyword."%' or a.user_id like '%".$keyword."%' )";
}

if($s_date){
	$where .= " and ( substring(order_date,1,10) >= '".$s_date."' or substring(payment_date,1,10) >= '".$s_date."' )";
}

if($e_date){
	$where .= " and ( substring(order_date,1,10) <= '".$e_date."' or substring(payment_date,1,10) <= '".$e_date."' )";
}

if($v_cate){
	$where .= " and a.orderstat ='".$v_cate."'";
}

if ($field){

	if($s_pay_type){
		$where .= " and ".$field." >= '".$s_pay_type."'";
	}

	if($s_pay_sect){
		$where .= " and ".$field." <= '".$s_pay_sect."'";
	}

} else {

	if($s_pay_type){
		$where .= " and (price_total_org >= '".$s_pay_type."' or price_total >= '".$s_pay_type."')";
	}

	if($s_pay_sect){
		$where .= " and (price_total_org <= '".$s_pay_sect."' or price_total <= '".$s_pay_sect."')";
	}

}

$pageScale = 10;  

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by a.idx desc ";

$query = "select a.* from order_member a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo $query;
$result = mysqli_query($gconnet,$query);

$query_cnt = "select a.idx from order_member a where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

?>
    <body>
        
        <!--header-->
         <div><? include "./common/header.php"; ?></div>   

        <!--content-->
        

        
    <section class="sub">
        <div class="sub_title">
            <h2>충전내역</h2>

        </div>
        
        <div class="adress_btn">
        </div>

        
        <div class="tab_btn_are">
        <form name="s_mem" id="s_mem" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>">    
            <div class="input_tab">
                <input type="text" name="keyword" id="keyword" value="<?=$keyword?>">
                <a href="javascript:s_mem.submit();">
                    <img src="images/search.png">
                </a>
            </div>
            
            <div class="btn">
                <span>기간선택 </span>
                <input type="text" name="s_date" id="s_date" style="width:120px;" class="datepicker" value="<?=$s_date?>" readonly autocomplete="off">
                <span> ~ </span>
                <input type="text" name="e_date" id="e_date" style="width:120px;" class="datepicker" value="<?=$e_date?>" readonly autocomplete="off">
                <a href="javascript:s_mem.submit();" class="blue">조회</a>
            </div>
      </form>     
                 
        </div>
        
        <div class="tlb center border">
            <table>
                <tr>
                    <th>날짜</th>
                    <th>금액</th>
                    <th>결제방식</th>
                    <th>승인여부</th>
                    <th>결제번호</th>
                </tr>
			<? if($num==0) { ?>
				<tr>
					<td colspan="5" height="40"><strong>결제내역이 없습니다.</strong></td>
				</tr>
			<? } ?>

			<?
			for ($ikm=0; $ikm<mysqli_num_rows($result); $ikm++){
				$row = mysqli_fetch_array($result);
				
				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $ikm;
				
			?>
                <tr>
                    <td>
					<?if($row['payment_date']){?>
						<?=substr($row['payment_date'],0,10)?>
					<?}else{?>
						<?=substr($row['order_date'],0,10)?>
					<?}?>
					</td>
                    <td><?=number_format($row['price_total'])?></td>
                    <td><?=get_payment_method($row['pay_sect_1'])?></td>
                    <td><?=get_order_status($row['orderstat'])?></td>
                    <td><?=$row['order_num']?></td>
                </tr>
            <?}?>
            </table>
        
        </div>
        
        <div class="pagenation">
			<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_front.php";?>	
		</div>
    
        <div class="point_pop">
			<h2>
				<span><img src="images/popup/point.svg"></span>
			   알아두세요!
			</h2>
			<ul class="list_ul">
				<li>세금계산서는 월 결제금액을 합산하여 매월 말일자로 익월초 발행됩니다.</li>
				<li>분기 마감된 세금계산서는 발행이 불가능합니다.</li>
				<li>세금계산서 발급은 신청한 회원님에 대해서만 발행되며, 신용카드 결제금액은 제외됩니다.</li>

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

        

        







        