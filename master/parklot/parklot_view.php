<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 주차장, 지점
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 일반, VIP
$s_level = sqlfilter($_REQUEST['s_level']); // 주차장등급
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); // 로그인 구분
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); // 추천인 (지점) 별
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order.'&pageNo='.$pageNo;

$sql = "select *,(select user_name from member_info where 1 and del_yn='N' and idx=a.member_idx) as user_name,(select user_id from member_info where 1 and del_yn='N' and idx=a.member_idx) as user_id from parklot_info a where 1 and idx = '".$idx."' and is_del='N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
	error_go("등록된 주차장이 없습니다.","parklot_list.php?".$total_param."");
}

$row = mysqli_fetch_array($query);

if($v_sect == "auth"){
	$parklot_str = "인증 주자창";
} elseif($v_sect == "public"){
	$parklot_str = "공유 주자창";
} else {
	$parklot_str = "주자창";
}

if($row['auth_status'] == "Y"){
	$login_ok = "<font style='color:blue;'>인증</font>";
}elseif($row['auth_status'] == "N"){
	$login_ok = "<font style='color:red;'>인증안됨</font>";
}

if($row['assign_status'] == "Y"){
	$master_ok = "<font style='color:blue;'>배정됨</font>";
}elseif($row['assign_status'] == "N"){
	$master_ok = "<font style='color:black;'>배정안됨</font>";
}elseif($row['assign_status'] == "C"){
	$master_ok = "<font style='color:red;'>기간만료</font>";
}
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/parklot_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>주차장 관리</li>
						<li><?=$parklot_str?> 정보 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$parklot_str?> 상세보기</h3>
				</div>
				<div class="write">
					<p class="tit">기본정보</p>
					<table>
						<caption>주차장 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						
						<tr>
							<th scope="col">공유자</th>
							<td scope="col" colspan="3">
								<?=$row['user_name']?> ( <?=$row['user_id']?> )
							</td>
						</tr>
						<tr>
							<th scope="row">지역</th>
							<td>
								<?=get_data_colname("code_bjd","bjd_code",$row['sido'],"k_name")?> > <?=get_data_colname("code_bjd","bjd_code",$row['gugun'],"k_name")?>
							</td>
							<th scope="row">주차장 번호</th>
							<td>
								<?=$row['parklot_name']?>
							</td>
						</tr>
						<tr>
							<th scope="col">주소</th>
							<td scope="col" colspan="3">
								<?=$row['parklot_addr1']?>&nbsp;<?=$row['parklot_addr2']?>
							</td>
						</tr>
						<tr>
							<td scope="col" colspan="4">
								<div id="map" style="width:100%; height:300px;"></div>
							</td>
						</tr>
						<tr>
							<th scope="row">배정상태</th>
							<td>
								<?=$master_ok?>
							</td>
							<th scope="row">배정기간</th>
							<td>
								<?=$row['assign_date_s']?> ~ <?=$row['assign_date_e']?>
							</td>
						</tr>
						<tr>
							<th scope="row">배정시간</th>
							<td>
							<?if($row['assign_time_all'] == "Y"){?>
								전일:24시간 
							<?}else{?>
								<?=$row['assign_time_s']?> ~ <?=$row['assign_time_e']?>
							<?}?>
							</td>
							<th scope="row">연락처</th>
							<td>
								<?=$row['parklot_cell']?>
							</td>
						</tr>
						<tr>
							<th scope="row">생년월일</th>
							<td>
								<?=$row['birthday']?>
							</td>
							<th scope="row">차량번호</th>
							<td>
								<?=$row['car_num']?>
							</td>
						</tr>
						<tr>
							<th scope="row">배정지역(시)</th>
							<td>
								<?=$row['assign_sido']?>
							</td>
							<th scope="row">배정지역(구)</th>
							<td>
								<?=$row['assign_gugun']?>
							</td>
						</tr>
						<tr>
					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='parklot_info' and board_code='image' and board_idx='".$row['idx']."' order by idx asc";
						$query_file = mysqli_query($gconnet,$sql_file);
						
						for($i_file=0; $i_file<mysqli_num_rows($query_file); $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;

							if($i_file == 0){
								$default_confile_num = $row_file['idx'];
							}
					?>
							<th>주차장 이미지 <?=$k_file?></th>
							<td>
								<img src="<?=$_P_DIR_WEB_FILE?>parklot/img_thumb/<?=$row_file['file_chg']?>" style="max-width:90%;">
							</td>
							<?if($k_file % 2 == 0){?></tr><tr><?}?>
					<?}?>
						</tr>
					<?
						$sql_file = "select * from parklot_public_time where 1 and is_del='N' and parklot_idx='".$row['idx']."'";
						//echo $sql_file;
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 7){
							$cnt_file = 7;
						}
						
						$now_yoil = date('w', strtotime(date("Y-m-d")));
					?>
						<tr>
							<th>공유시간</th>
							<td colspan="3">
							<?
								for($file_i=0; $file_i<$cnt_file; $file_i++){
									$row_file = mysqli_fetch_array($query_file);
									$file_k = $file_i+1;
							?>
								<div <?if($file_i > 0){?>style="margin-top:10px;"<?}?>> 
									<?=$inc_pubyoil_arr[$file_k]?> : 
									<?=$row_file['pub_time_s']?> ~ <?=$row_file['pub_time_e']?>
									<?if($row_file['pub_time_yn'] == "Y"){?>
										&nbsp; <font style='color:blue;'>공유</font>
									<?}else{?>
										&nbsp; <font style='color:red;'>비공유</font>
									<?}?>
								</div>
							<?}?>
							</td>
						</tr>
						<tr>
							<th scope="row">기본 가격정보</th>
							<td colspan="3">
								기본 <?=$row['time_1']?> 분 주차에 <?=number_format($row['price_1'])?> 원 부과
							</td>
						</tr>
						<tr>
							<th scope="row">추가 가격정보</th>
							<td colspan="3">
								기본시간에 추가로 <?=$row['time_2']?> 분 당 <?=number_format($row['price_2'])?> 원이 추가로 부과됨.
							</td>
						</tr>
						<tr>
							<th scope="row">이용규칙</th>
							<td colspan="3">
								<?=nl2br(stripslashes($row['rule_info']))?>
							</td>
						</tr>
						<tr>
							<th scope="row">등록일시</th>
							<td colspan="3">
								<?=$row['wdate']?>
							</td>
						</tr>
					</table>

					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_gray">목록보기</a>
						<a href="javascript:go_modify('<?=$row['idx']?>');" class="btn_green">정보수정</a>
						<a href="javascript:go_delete('<?=$row['idx']?>');" class="btn_red">삭제하기</a>	
					</div>
								
					<!--<p class="tit">승인 및 관리자 메모</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
			
						<form name="set_frm" id="set_frm" action="parklot_view_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="idx" value="<?=$idx?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
							<tr>
							<th scope="row">승인여부</th>
							<td colspan="3">
								<select name="master_ok" required="yes" message="승인여부" size="1" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
									<option value="Y" <?=$row[master_ok]=="Y"?"selected":""?>>승인</option>
									<option value="N" <?=$row[master_ok]=="N"?"selected":""?>>미승인</option>
								</select>
							</td>
							</tr>							
							<tr>
							<th scope="row">로그인 여부</th>
							<td colspan="3">
								<select name="login_ok" required="yes" message="로그인 승인여부" size="1" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
									<option value="Y" <?=$row[login_ok]=="Y"?"selected":""?>>로그인 가능</option>
									<option value="N" <?=$row[login_ok]=="N"?"selected":""?>>로그인 차단</option>
								</select>
							</td>
							</tr>										
							<tr>
							<th >주차장등급 변경</th>
							<td colspan="3">
								<select name="user_level" required="yes" message="주차장등급" size="1" style="vertical-align:middle;" >
								<option value="">선택하세요</option>
								<?
									$sub_sql = "select idx,level_code,level_name from parklot_level_set where 1=1 and is_del = 'N' order by level_align asc";
									$sub_query = mysqli_query($gconnet,$sub_sql);
									$sub_cnt = mysqli_num_rows($sub_query);

									for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
										$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[level_code]?>" <?=$row[user_level]==$sub_row[level_code]?"selected":""?>><?=$sub_row[level_name]?></option>
								<?}?>
								</select>
							</td>
							</tr>
							<input type="hidden" name="user_level" value="<?=$row['user_level']?>"/>
							<tr>
							<th scope="row">관리자 메모</th>
							<td colspan="3">
								<textarea style="width:90%;height:50px;" name="admin_memo" required="no"  message="관리자 메모사항" value=""><?=$row[admin_memo]?></textarea>
							</td>
							</tr>
						</form>
					</table>-->
				
				</div>
			</div>
		</div>
		<!-- content 종료 -->

<!-- Naver maps 시작 -->
<script type="text/javascript" src="https://oapi.map.naver.com/openapi/v3/maps.js?ncpClientId=xrdneh223b"></script>
<script type="text/javascript" src="/data/accidentdeath.js"></script>
<script type="text/javascript" src="/src/MarkerClustering.js"></script>
<style>
    .marker {
      position: relative;
      width: fit-content;
      height: auto;
      padding: 8px 10px 7px;
      background-color: #fff;
      border: 1px solid #FEE102;
      font-size: 15px;
      font-weight: 500;
      border-radius: 50px;
      box-sizing: border-box;
      justify-content: center;
      display: inline-flex;
      align-items: center;
      line-height: 1;
      z-index: 1;
    }

    .marker::before {
      border-top: 6px solid white;
      border-left: 3px solid transparent;
      border-right: 3px solid transparent;
      border-bottom: 0 solid transparent;
      content: "";
      position: absolute;
      bottom: -6px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 3;
    }

    .marker::after {
      border-top: 8px solid yellow;
      border-left: 4px solid transparent;
      border-right: 4px solid transparent;
      border-bottom: 0 solid transparent;
      content: "";
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 2;
    }
  </style>

  <script>
      /*
       * @description 지도
       */
      // get the iso time string formatted for usage in an input['type="datetime-local"']
      var tzoffset = (new Date()).getTimezoneOffset() * 60000; //offset in milliseconds
      var localISOTime = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);
      var localISOTimeWithoutSeconds = localISOTime.slice(0, 16);

      /*
       * @description 지도
       */
      var map = new naver.maps.Map("map", {
        zoom: 18,
        center: new naver.maps.LatLng(<?=$row['map_x']?>, <?=$row['map_y']?>),
        zoomControl: true,
        zoomControlOptions: {
          position: naver.maps.Position.RIGHT_CENTER,
          // position: naver.maps.Position.TOP_LEFT,
          style: naver.maps.ZoomControlStyle.SMALL
        }
      });

      var markers = [],
        data = accidentDeath.searchResult.accidentDeath;

      /* 마커 */
      var marker = new naver.maps.Marker({
        position: new naver.maps.LatLng(<?=$row['map_x']?>, <?=$row['map_y']?>),
        map: map,
        title: '주차',
        icon: {
          content: [
            '<div class="marker" onclick="viewCon();"><?=number_format($row[price_1])?></div>'
          ].join(''),
          size: new naver.maps.Size(22, 35),
          anchor: new naver.maps.Point(11, 35)
        },
        draggable: false
      });

      function viewCon() {
        const bottomSheet = $(".bottom-sheet");
        if ($('bottomSheet').hasClass("show") == true) {
          $(".marker").css("background-color", "#fff")
        }
      }

  </script>
<!-- Naver maps 종료 -->

<script type="text/javascript">
<!-- 
function go_view(no){
		location.href = "parklot_view.php?idx="+no+"&<?=$total_param?>";
}
	
function go_modify(no){
		location.href = "parklot_modify.php?idx="+no+"&<?=$total_param?>";
}

function go_delete(no){
	if(confirm('주차장 정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
		//if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "parklot_delete_action.php?idx="+no+"&<?=$total_param?>";
		//}
	}
}

<?if($row['memout_yn'] == "Y"){?>
	function go_list(){
		location.href = "parklot_list_out.php?<?=$total_param?>";
	}
<?}else{?>
	function go_list(){
		location.href = "parklot_list.php?<?=$total_param?>";
	}
<?}?>

	function go_memout_com(no){
		if(confirm('정말 탈퇴처리 하시겠습니까?')){
			//if(confirm('탈퇴한 주차장의 포인트 등 은 복구할수 없도록 영구 삭제 됩니다. 그래도 탈퇴처리 하시겠습니까?')){	
			if(confirm('탈퇴한 주차장은 복구할수 없도록 영구 삭제 됩니다. 그래도 탈퇴처리 하시겠습니까?')){	
				_fra_admin.location.href = "parklot_out_action.php?idx="+no+"&mode=outcom&o_sect=one&<?=$total_param?>&re_url=parklot_out_done.php";
			}
		}
	}

	function go_memout_can(no){
		if(confirm('탈퇴신청을 취소처리 하시겠습니까?')){
			_fra_admin.location.href = "parklot_out_action.php?idx="+no+"&mode=outcan&o_sect=one&<?=$total_param?>&re_url=parklot_view.php";
		}
	}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

	function go_set_submit() {
		var check = chkFrm('set_frm');
		if(check) {
			set_frm.submit();
		} else {
			false;
		}
	}

	function go_submit_bad() {
		if(confirm('정말 불량주차장명으로 등록 하시겠습니까?')){
			var check = chkFrm('frm_bad');
			if(check) {
				frm_bad.submit();
			} else {
				false;
			}
		}
	}

	function go_submit_bad2() {
		if(confirm('차단주차장으로 설정하시면 해당 주차장은 모든 자격이 박탈됩니다. 정말 차단주차장 으로 설정 하시겠습니까?')){
			var check = chkFrm('frm_bad');
			if(check) {
				frm_bad.submit();
			} else {
				false;
			}
		}
	}

	function upload_hotel(){
		$("#hotelship_photo").click();
	}

	function upload_photo() {
		var frm = document.forms["frm_photo"];
		frm.submit();
	}

	function upload_photo_callback(photo1,photo2) {

		if (photo2 != "" && photo2 != "false") {
			var frm_photo = document.forms["frm_photo"];
			frm_photo.elements["hotelship_photo_org"].value = photo2;

			//var frm = document.forms["frm"];
			//frm.elements["hotelship_photo"].value = photo;

			//$("#parklot_noimg").attr("src","/upload_file/hotel/img_thumb/" + encodeURIComponent(photo2)).addClass("circle_div");
			$("#parklot_noimg").attr("src","/upload_file/hotel/img_thumb/"+photo2);

			var frm_main = document.forms["frm_profile"];
			frm_main.elements["file_o"].value = photo1;
			frm_main.elements["file_c"].value = photo2;
		}
	}

	function unlink_photo(){
		var frm_photo = document.forms["frm_photo"];
		$.ajax({
			url : "action_unlink_photo.php",
			type : "post",
			dataType : "text",
			data : {"hotelship_photo_org" : frm_photo.elements["hotelship_photo_org"].value},
			async : true,
			timeout : 9000,
			success : function(data){
				$("#parklot_noimg").attr("src","<?//=get_parklot_photo($idx,$row['parklot_type'])?>");
				var frm_main = document.forms["frm_profile"];
				frm_main.elements["file_o"].value = "";
				frm_main.elements["file_c"].value = "";
			}
		});
	}

	function go_submit_profile(){
		var check = chkFrm('frm_profile');
		if(check) {
			frm_profile.submit();
		} else {
			false;
		}
	}

	/*function hotelship_list(){
		get_data("hotelship_list.php","hotelship_list_area","parklot_idx=<?=$row['idx']?>");
	}

	function hotelship_regist(){
		window.open("hotelship_regist.php?parklot_idx=<?=$row['idx']?>","regist_hotelship", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
	}

	function hotelstudy_list(){
		get_data("hotelstudy_list.php","hotelstudy_list_area","parklot_idx=<?=$row['idx']?>");
	}

	function hotelstudy_regist(){
		window.open("hotelstudy_regist.php?parklot_idx=<?=$row['idx']?>","regist_hotelship", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
	}

	$(document).ready(function() {
		hotelship_list();
		hotelstudy_list();
	});*/
//-->
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>