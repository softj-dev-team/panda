<? include "./common/head.php"; ?>	
    <body>
        
        <!--header-->
         <div><? include "./common/header.php"; ?></div>   
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$bbs_code = sqlfilter($_REQUEST['bbs_code']);
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
################## 파라미터 조합 #####################
$total_param = 'field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&v_sect='.$v_sect.'&s_cate_code='.$s_cate_code.'&pageNo='.$pageNo;

$sql = "select * from board_content a where 1 and a.idx = '".$idx."' and a.bbs_code='".$bbs_code."'";
$query = mysqli_query($gconnet,$sql);

//echo $sql; exit;

if(mysqli_num_rows($query) == 0){
	error_go("해당하는 게시물이 없습니다.","board_list.php?".$total_param);
}

$row = mysqli_fetch_array($query);

set_vcnt_up("board_content",$row['bbs_code'],$row['idx'],$row['member_idx'],$_SESSION['member_coinc_idx'],"board_content","cnt"); // 조회수 증가

$bbs_str = $_include_board_board_title;
?>
  <!--content-->
       
    <section class="sub">
        
    <!-- 글쓰이일경우 -->
        
        <!--div class="tlb center border board">
            <table>
                <tr>
                    <th class="check">제목</th>
                    <td><input type="text"></td>

                </tr>
                <tr>
                    <th class="check">이름</th>
                    <td><input type="text"></td>

                </tr>
                <tr>
                    <th class="check">홈페이지</th>
                    <td><input type="text"></td>

                </tr>
                
                 <tr>
                    <th class="check">내용</th>
                    <td><textarea></textarea></td>

                </tr>

            
            </table>
        
        </div-->    
        
        
        <div class="sub_title">
            <h2><?=$row['subject']?></h2>

        </div>
        
        
        <div class="board_detail">
            
            <div class="content_are">
                <?if($row['is_html'] == "Y"){?>
					<?=stripslashes($row[content])?>
				<?}else{?>
					<?=nl2br(stripslashes($row[content]))?>
				<?}?>
            </div>
            
            
        </div>
    
        <div class="tab_btn_are">
            
            <div class="btn">
                <a href="javascript:go_list();">목록</a>
            </div>
            
		<?if($bbs_code != "notice" && $bbs_code != "event"){?>    
            <div class="btn">
				 <?if($row['member_idx'] == $_SESSION['member_coinc_idx']){?>
					<a href="javascript:go_modify('<?=$row['idx']?>');">수정</a>
				 <?}?>
				 <?if($row['member_idx'] != $_SESSION['member_coinc_idx']){?>
					<a href="javascript:go_reply('<?=$row['idx']?>');">답변</a>
				 <?}?>
				 <?if($row['member_idx'] == $_SESSION['member_coinc_idx']){?>
					<a href="javascript:go_delete('<?=$row['idx']?>');">삭제</a>
				 <?}?>
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
      
	function go_list(){
		location.href = "board_list.php?<?=$total_param?>";
	}

	function go_modify(no){
		location.href = "board_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('정말 삭제 하시겠습니까?')){
			_fra.location.href = "board_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}

    </script>

    </body>
</html>

        

        







        