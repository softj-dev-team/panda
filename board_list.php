<? include "./common/head.php"; ?>	
    <body>
        
        <!--header-->
         <div><? include "./common/header.php"; ?></div>   
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<?
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code'])); // 게시판 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); // 지역 시,도
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); // 지역 구,군
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 계급별 검색
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색

################## 파라미터 조합 #####################
$total_param = 'field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&v_sect='.$v_sect.'&s_cate_code='.$s_cate_code.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_gender='.$s_gender.'&s_level='.$s_level;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and a.is_del='N' and a.bbs_code = '".$bbs_code."' and a.step='0'";

if ($v_sect){
	$where .= " and a.bbs_sect = '".$v_sect."'";
}

if($s_sect1){
	$where .= " and a.sido = '".$s_sect1."' ";
}

if($s_sect2){
	$where .= " and a.gugun = '".$s_sect2."' ";
}

if($s_gender){
	$where .= " and b.gender = '".$s_gender."' ";
}

if($s_level){
	$where .= " and b.user_level = '".$s_level."' ";
}

if ($field && $keyword){
	if($field == "subtent"){
		$where .= "and (a.subject like '%".$keyword."%' or a.content like '%".$keyword."%')";
	} else {
		$where .= "and ".$field." like '%".$keyword."%'";
	}
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " ORDER BY a.ref desc, a.step asc, a.depth asc ";

$query_cnt = "select idx from board_content a where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$query = "select * from board_content a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
$result = mysqli_query($gconnet,$query);
//echo $query;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale;

$bbs_str = $_include_board_board_title;
?>
        <!--content-->
        

        
    <section class="sub">
        
        
        
 
        
        
        <div class="sub_title">
            <h2><?=$bbs_str?></h2>

        </div>
        
        
        <div class="board_list">
            
            <table>
                
                <tr>
                    <th>No</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>날짜</th>
                </tr>
			<?
			for ($i=0; $i<mysqli_num_rows($result); $i++){
				$row = mysqli_fetch_array($result);
				
				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				$reg_time3 = to_time(substr($row['write_time'],0,10));
			?>
                <tr>
                    <td class="num">
                        <?=$listnum?>
                    </td>
                    <td>
                       <a href="javascript:go_view('<?=$row['idx']?>','<?=$row['bbs_code']?>');"><?=string_cut2(stripslashes($row['subject']),40)?> <?=now_date($reg_time3)?></a>
                    </td>
                    <td class="name">
                        <?=$row['writer']?>
                    </td>
                    <td class="date">
                       <?=substr($row['write_time'],0,10)?>
                    </td>
                </tr>
			<?}?>
            </table>
            
            
            <div class="pagenation">
				<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_front.php";?>	
			</div>
            
            
        </div>
    

        

        
        
        <div class="tab_btn_are">
            
            <div class="btn">
              
            </div>
        <?if($bbs_code != "notice" && $bbs_code != "event"){?>    
            <div class="btn">
               <a href="javascript:go_regist();">글쓰기</a>
	       </div>
        <?}?>    
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

        
                <script>
 
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
        
	function go_view(no,bcode){
		location.href = "board_detail.php?idx="+no+"&bbs_code="+bcode+"&<?=$total_param?>&pageNo=<?=$pageNo?>";
	}
	
	function go_regist(){
		location.href = "board_write.php?<?=$total_param?>";
	}
    </script>

    </body>
</html>
