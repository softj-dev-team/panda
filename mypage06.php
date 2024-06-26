<? include "./common/head.php"; ?>	
    <body>
        
        <!--header-->
         <div><? include "./common/header.php"; ?></div>   

        <!--content-->
        

        
    <section class="sub">
        <div class="sub_title">
            <h2>사업자 인증</h2>
        </div>
        
        
        <div class="mybox adit">
            <ul>
                <li>
                    <div class="flex_my">
                    <span>사업자등록번호</span>
                   <div class="tlb_flex">
                            <input type="text"><button class="btn">검색</button>
                            </div>
                    </div>
                </li>
                
                <li>
                    <div class="flex_my">
                    <span>신청자 선택</span>
                   <div class="tlb_flex">
                           <input type="radio" id="rd01" name="rd" onclick="show2();" checked>
                            <label for="rd01">대표자</label>
                        <input type="radio" id="rd02" name="rd" onclick="show1();">
                            <label for="rd02">재직자</label>
                            </div>
                    </div>
                </li>
                
                
              
                
                <li id="div1" class="">
                    <div class="flex_my">
                    <span>대표자 본인인증</span>
                    <div class="tlb_flex noneflex">
                           <button class="btn">본인인증하기</button>
                        <p>*자사 대표자 명의 핸드폰으로 인증 가능합니다.</p>
                    </div>
                        
                        
                 
                     </div>
                </li>
                
                
                <li id="div2" class="hide">
                    <div class="flex_my">
                    <span>재직자 본인인증</span>
                    <div class="tlb_flex noneflex">
                           <button class="btn">본인인증하기</button>
                    </div>
                        
                        
                 
                     </div>
                </li>
                
                
                <li id="div3" class="hide">
                    <div class="flex_my">
                    <span>재직증명서</span>
                    <div class="file_are">
                          <div class="file_flex">
                               <button class="btn">파일</button> <span>파일첨부</span>
                        </div>
                        <p>*발급일자 최근 1개월 이내 및 주민번호 뒷자리는 반드시 가려(마스킹) 주세요.</p>
                    </div>
                        
                        
                 
                     </div>
                </li>
                
                
                
                <li>
                    <div class="flex_my">
                    <span>사업자등록증</span>
                    <div class="file_are">
                          <div class="file_flex">
                               <button class="btn">파일</button> <span>파일첨부</span>
                        </div>
                        <p>*등록가능 파일 : pdf, zip, jpg, gif, png, bmp, tif, tiff (최대 10MB)</p>
                    </div>
                        
                        
                 
                     </div>
                </li>
                
                
   
                <li>
                    <div class="flex_my">
                    <span>계산서이메일</span>
                    <input type="text" value="" placeholder="00@naver.com">
                        </div>
                </li>
       
            </ul>

        </div>
        
       
        
        
        <div class="btn_pry">
            <!--a href="#" class="btn01 btn">취소</a-->
            <a href="#" class="btn02 btn">변경하기</a>
        </div>
   
        
        
    </section>
        
     
     <!--footer-->
        <div><? include "./common/footer.php"; ?></div>        
        
        
        <script>
       function show1(){
            document.getElementById('div1').style.display ='none';
           document.getElementById('div2').style.display ='block';
           document.getElementById('div3').style.display ='block';
}
function show2(){
    document.getElementById('div1').style.display = 'block';
    document.getElementById('div2').style.display ='none';
    document.getElementById('div3').style.display ='none';
}

        </script>

    </body>
</html>

        

        







        