<? include "./common/head.php"; ?>	
    <body>
        
        <!--header-->
         <div><? include "./common/header.php"; ?></div>   

        <!--content-->
                
		<section class="sub">
			<div class="sub_title">
				<h2>서비스 제약관</h2>
			</div>
                
			<div class="goods">

              <ul class="tabs wide">
				<li class="tab-link current" data-tab="tab-1">서비스 이용약관</li>
				<li class="tab-link" data-tab="tab-2">개인정보처리방침</li>
				<li class="tab-link" data-tab="tab-3">스팸메세지 관리약관</li>
			  </ul>

			  <div id="tab-1" class="tab-content current pre_box">
				 <?=get_yakkwan("host1")?>
			  </div>

			  <div id="tab-2" class="tab-content pre_box">
				 <?=get_yakkwan("host2")?>
			  </div>

			  <div id="tab-3" class="tab-content pre_box">
			     <?=get_yakkwan("host4")?>
			  </div>
          
		  </div>
            
       </section>
      
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
