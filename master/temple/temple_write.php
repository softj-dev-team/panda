<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 비슷회원
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order;

$sql_file_1 = "select * from temple_info_add where 1 and temple_info_idx='".$row['idx']."' and cate_type='mem' order by idx asc";
$query_file_1 = mysqli_query($gconnet,$sql_file_1);

?>
<script type="text/javascript">
function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
}

function go_list(){
	location.href = "temple_list.php?<?=$total_param?>";
}

</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/temple_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사찰 관리</li>
						<li>사찰 등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>사찰 등록</h3>
				</div>
				<div class="write">

				<form name="frm" action="temple_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="bmenu" id="bmenu" value="<?=$bmenu?>"/>
					<input type="hidden" name="smenu" id="smenu" value="<?=$smenu?>"/>
					<?if(mysqli_num_rows($query_file_1) == 0){?>
						<input type="hidden" name="attach_count_1" value="1"/>
					<?}else{?>
						<input type="hidden" name="attach_count_1" value="<?=mysqli_num_rows($query_file_1)?>"/>
					<?}?>
					<input type="hidden" name="turl_ok" id="turl_ok" value=""/>
					<input type="hidden" name="temple_hash_id" id="temple_hash_id"/>

					<table>
						<caption>사찰 수동등록</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<tr>
						<th>사찰회원</th>
						<td colspan="3">
							<select name="member_idx" size="1" style="vertical-align:middle;width:30%;" required="yes" message="사찰회원">
								<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,user_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and del_yn='N' and member_type in ('PAT') and member_gubun='temple' and idx not in (select member_idx from temple_info where 1 and is_del='N') order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$row[member_idx]==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
							</select>
						</td>
					</tr>
					<tr>
						<th>레이아웃</th>
						<td colspan="3">
							<input type="radio" name="temple_layout" id="temple_layout_1" value="1" <?=$row['temple_layout']=="1"?"checked":""?> required="yes" message="레이아웃"> <?=get_temple_layout("1")?> &nbsp;
							<input type="radio" name="temple_layout" id="temple_layout_1" value="2" <?=$row['temple_layout']=="2"?"checked":""?> required="yes" message="레이아웃"> <?=get_temple_layout("2")?> &nbsp;
							<input type="radio" name="temple_layout" id="temple_layout_1" value="3" <?=$row['temple_layout']=="3"?"checked":""?> required="yes" message="레이아웃"> <?=get_temple_layout("3")?>
						</td>
					</tr>
					<tr>
						<th scope="row">섬네일 이미지</th>
						<td colspan="3">
							<input type="file" style="width:400px;" required="yes" message="커버사진" name="photo_0"> 
						</td>
					</tr>
					<tr>
						<th scope="row">사찰명</th>
						<td colspan="3">
							<input type="text" style="width:40%;" name="temple_title" required="no" message="사찰명">
							<br><br> 로고파일 (사찰명 없이 로고만 띄울때) : <input type="file" style="width:40%;" required="no" message="커버사진" name="logphoto_0"> 
						</td>
					</tr>
					<tr>
						<th scope="row">배경이미지</th>
						<td colspan="3">
							<input type="file" style="width:400px;" required="no" message="배경이미지" name="addphoto_0"> 
						</td>
					</tr>
					<tr class="address">
								<th scope="col">주소</th>
								<td scope="col" colspan="3">
								<p>
									<input type="text" name="member_address" id="member_address" value="<?=$row[addr1]?>" style="width:50%;" required="yes"  message="기본주소"> <a href="javascript:execDaumPostcode('zip_code1', 'member_address', 'member_address2');" class="btn_green">주소검색</a>
									<span class="info">기본주소</span>
								</p>
								<!-- 우편번호 레이어 시작 -->
								<div id="post_wrap_zip_code1" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
									<div><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1;width:30px;" onclick="foldDaumPostcode('zip_code1')" alt="접기 버튼"></div>
								</div>
								<!-- 우편번호 레이어 종료 -->
								<p>
									<input type="text" name="member_address2" id="member_address2" value="<?=$row[addr2]?>" style="width:50%;" required="yes"  message="상세주소">
									<span class="info">상세주소</span>
								</p>
							</td>
					</tr>
					<tr>
						<th scope="row">대표자/담당자</th>
						<td colspan="3">
							<a href="javascript:addForm_1();" class="btn_green">추가</a> <a href="javascript:delForm_1();" class="btn_red">취소</a><br>	
						<?
							if(mysqli_num_rows($query_file_1) == 0){
								$i_file_cnt = 1;
							} else {
								$i_file_cnt = mysqli_num_rows($query_file_1);
							}
							for($i_file=0; $i_file<$i_file_cnt; $i_file++){
								$row_file = mysqli_fetch_array($query_file_1);
						?>
							<br><input type="text" name="tag_value_1_<?=$i_file?>" id="tag_value_1_<?=$i_file?>" value="<?=$row_file['tag_value_1']?>" style="width:30%;" required="no"  message="담당자" placeholder="이름"> / <input type="text" name="tag_value_2_<?=$i_file?>" id="tag_value_2_<?=$i_file?>" value="<?=$row_file['tag_value_2']?>" style="width:50%;" required="no" message="연락처" placeholder="연락처">
						<?}?>
							<div id="addedFormDiv_1"></div>
						</td>
					</tr>
					<tr>
						<th scope="row">사찰 키워드</th>
						<td colspan="3">
							<input type="text" style="width:40%;" name="hast_tag" id="hast_tag" required="no" message="사찰 키워드">
							<a href="javascript:go_hast_tag();" class="btn_green">등록</a>
							<div id="hast_tag_area" style="paddig-top:10px;">
								<!-- inner_hast_tag.php 에서 불러옴 -->
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">홈페이지</th>
						<td colspan="3">
							<input type="text" style="width:30%;" name="temple_url" id="temple_url" required="yes" message="홈페이지" onblur="checkNumber();" is_notkr="yes"> <a href="javascript:ch_turl();" class="btn_blue">중복확인</a>
							<div id="check_turl" style="paddig-top:10px;"></div>
							<br> EX : aaa 입력시 홈페이지 접속 URL 은 aaa.mybuddha.net 이 됩니다.  
						</td>
					</tr>
									
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:frm.reset();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">등록</button>
					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript">
<!--
	function uplotemple_photo(upnum){
		$("#btnUplotemple_"+upnum+"").click();
	}
	function uplotemple_file(upnum){
		$("#uplotemple_"+upnum+"").click();
	}
	
	function readURL(input,upnum) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#photo_noimg_'+upnum+'').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
	
function ch_turl(){
	var temple_url = $("#temple_url").val();
	if(temple_url == ""){
		alert("홈페이지를 입력하세요.");
		$("#temple_url").focus();
		return;
	}
	var vurl = "/pro_inc/check_turl_duple.php";
	$.ajax({
		url		: vurl,
		type	: "GET",
		data	: { idx:"", temple_url:$("#temple_url").val() },
		async	: false,
		dataType	: "json",
		success		: function(v){
			if ( v.success == "true" ){
				$("#turl_ok").val("Y");
				$("#check_turl").html( v.msg );
			} else if ( v.success == "false" ){
				$("#turl_ok").val("N");
				$("#check_turl").html( v.msg );
			} else {
				alert( "오류 발생!" );
			}
		}
	});
}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			if (document.frm.turl_ok.value != "Y"){
				alert('홈페이지 중복검사를 해주세요.');
				return;	
			}
			frm.submit();
		} else {
			false;
		}
	}

		// 파일선택 시작
            $("#uplotemple_0").change(function()
            {
               	if(window.FileReader){
					// modern browser
					var filename = $(this)[0].files[0].name;
				} else {
					// old IE
					var filename = $(this).val().split('/').pop().split('\\').pop(); // 파일명만 추출
				}
				//alert(fileSize_k);
                $("#txt_file_1").val(filename);
				//$("#fname_1").html(filename);
            }); 

			$("#uplotemple_1").change(function()
            {
               	if(window.FileReader){
					// modern browser
					var filename = $(this)[0].files[0].name;
				} else {
					// old IE
					var filename = $(this).val().split('/').pop().split('\\').pop(); // 파일명만 추출
				}
				//alert(fileSize_k);
                $("#txt_file_2").val(filename);
				//$("#fname_1").html(filename);
            }); 

		// 파일삭제 1 확인
			function file_del_1(){
				var agent = navigator.userAgent.toLowerCase();
				if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ){
					 // ie 일때 input[type=file] init.
					$("#uplotemple_0").replaceWith( $("#uplotemple_0").clone(true) );
				} else {
					//other browser 일때 input[type=file] init.
					$("#uplotemple_0").val("");
				}
				$("#txt_file_1").val("");			
			}
			// 파일삭제 1 확인 종료

			// 파일삭제 2 확인
			function file_del_2(){
				var agent = navigator.userAgent.toLowerCase();
				if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ){
					 // ie 일때 input[type=file] init.
					$("#uplotemple_1").replaceWith( $("#uplotemple_1").clone(true) );
				} else {
					//other browser 일때 input[type=file] init.
					$("#uplotemple_1").val("");
				}
				$("#txt_file_2").val("");			
			}
			// 파일삭제 2 확인 종료
	
 function set_adcate_view(ccode){
	if($("input:checkbox[id='temple_cate_"+ccode+"']").is(":checked") == true){
		$("#"+ccode+"_area1").show();
		$("#"+ccode+"_area2").show();
		$("#"+ccode+"_area3").show();
	} else {
		$("#"+ccode+"_area1").hide();
		$("#"+ccode+"_area2").hide();
		$("#"+ccode+"_area3").hide();
	}
 }

<?if(mysqli_num_rows($query_file_1) == 0){?>
	var count_1 = 1;          
<?}else{?>
	var count_1 = <?=mysqli_num_rows($query_file_1)?>;          
<?}?>              
function addForm_1(){
	var addedFormDiv = document.getElementById("addedFormDiv_1");
	var str = "";
	
	str+='<br><input type="text" name="tag_value_1_'+count_1+'" id="tag_value_1_'+count_1+'" style="width:30%;" required="no"  message="담당자" placeholder="이름"> / <input type="text" name="tag_value_2_'+count_1+'" id="tag_value_2_'+count_1+'" style="width:50%;" required="no" message="연락처" placeholder="연락처">';

	var addedDiv = document.createElement("div"); // 폼 생성
    addedDiv.id = "added_1_"+count_1; // 폼 Div에 ID 부 여 (삭제를 위해)
    addedDiv.innerHTML  = str; // 폼 Div안에 HTML삽입
    addedFormDiv.appendChild(addedDiv); // 삽입할 DIV에 생성한 폼 삽입
    count_1++;
	frm.attach_count_1.value = count_1;
}
function delForm_1(){
  var addedFormDiv = document.getElementById("addedFormDiv_1");
   if(count_1 >1){ // 현재 폼이 두개 이상이면
      var addedDiv = document.getElementById("added_1_"+(--count_1));
         addedFormDiv.removeChild(addedDiv); // 폼 삭제 
		 frm.attach_count_1.value = count_1;
    }else{ // 마 지막 폼만 남아있다면
      //  document.baseForm.reset(); // 폼 내용 삭제
     }
}

function go_hast_tag(){
	var temple_hash_id = $("#temple_hash_id").val();
	var add_hash_var = $("#hast_tag").val();

	if(add_hash_var == ""){
		alert("사찰 키워드를 입력해주세요.");
		return;
	}

	var set_hast_id = temple_hash_id+"||"+add_hash_var;

	get_data("inner_hast_tag.php","hast_tag_area","set_hast_id="+set_hast_id+"");
	$("#hast_tag").val("");
}

function del_hast_tag(deltag){
	var temple_hash_id = $("#temple_hash_id").val();

	get_data("inner_hast_tag.php","hast_tag_area","set_hast_id="+temple_hash_id+"&del_hast_id="+deltag+"");
}
//-->
</script>

<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
function foldDaumPostcode(zip) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

    function execDaumPostcode(zip,ad1,ad2) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
		// 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

				//document.getElementById(''+zip+'').value = data.zonecode;
				//document.getElementById('zip_code2').value = data.postcode2;
				document.getElementById(''+ad1+'').value = fullAddr;
				 document.getElementById(''+ad2+'').focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
    }
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>