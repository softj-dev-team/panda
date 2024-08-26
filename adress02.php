<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/check_login.php"; // 공통함수 인클루드 ?>
    <body>
        
        <!--header-->
         <div><? include "./common/header.php"; ?></div>   

        <!--content-->
        

        
    <section class="sub">
        <div class="sub_title">
            <h2>수신거부</h2>

        </div>
        
        <div class="adress_btn">
        </div>

        
        <div class="tab_btn_are">
            
            <div class="input_tab">
                <input type="text">
                <a href="#none">
                    <img src="images/search.png">
                </a>
            </div>
            
            <div class="btn">
                <a href="#">차단리스트 추가</a>
                <a href="#">삭제하기</a>
                <a href="#">전체삭제</a>
                <a href="#">다운로드</a>
            </div>
            
        </div>
        
        <div class="tlb center border">
            <table>
                <tr>
                    <th class="check"><input type="checkbox"></th>
                    <th>차단번호</th>
                    <th>이름</th>
                    <th>등록일</th>
                </tr>
                <tr>
                    <td class="check"><input type="checkbox"></td>
                    <td>01000000000</td>
                    <td>홍길동</td>
                    <td>2023-01-10 16:44:49</td>
                </tr>
            
            </table>
        
        </div>
        
        

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
    
           
        
        <div class="point_pop">
                <h2>
                    <span><img src="images/popup/point.svg"></span>
                   알아두세요!
                </h2>
                <ul class="list_ul">
                    <li>수신거부번호는 발송시 자동 거부됩니다.</li>

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
        
    </script>

    </body>
</html>

        

        







        