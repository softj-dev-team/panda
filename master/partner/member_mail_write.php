<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/patin_left.php"; // 사이트설정 좌측메뉴?>
<script type="text/javascript" src="../js/mail.js"></script>
<style type="text/css">
.adr_area {float:left;width:318px;}
.adr_area .adr_sel {width:120px;}
.adr_area .btn_sch {display:inline-block;background:#666;color:#fff;border-radius:4px;padding:4px 10px;vertical-align:middle;font-weight: bold;}

.adr_area .tbl_wrap {margin-top:12px;border:1px solid #cdcdcd;height:401px;width:316px;}
.adr_area table {width:100%;}
.adr_area table thead th {background:#444842;height:35px;border-left:1px solid #575a56;color:#fff;}
.adr_area table thead th:first-child {border-left:none;}
.adr_area table tbody td {padding:6px 5px;text-align:center;}

.adr_area table thead {float:left;width:316px;}
.adr_area table tbody {float:left;width:310px;height:360px;overflow-x:hidden;overflow-y:scroll;padding:3px;}
.adr_area table thead:after, .adr_area table tbody:after {content:"";display:block;clear:both;}
.adr_area table td.chk {width:20px;}
.adr_area table td.name {width:60px;}
.adr_area table td.date {width:95px;}
.adr_area table td.phn {width:110px;}
.align_r {text-align:right;}
</style>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));

$s_cate = trim(sqlfilter($_REQUEST['s_cate']));
$s_professor = trim(sqlfilter($_REQUEST['s_professor']));
$msg_key =  time().randomChar(5);
?>
<!-- content -->
<section id="content">
	<div class="inner">
		<h3>전체메일 보내기</h3>
		<div class="cont">

			<form id="theForm" name="theForm" method="post" action="member_mail_write_action.php" target="_fra_admin" enctype="multipart/form-data">
				<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
				<input type="hidden" name="smenu" value="<?=$smenu?>"/>
				<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
				<input type="hidden" name="mode" value="msg_send"/>
				<input type="hidden" name="msg_key" value="<?=$msg_key?>"/>
				<input type="hidden" name="send_sect" value="member"/>
				<input type="hidden" name="goods" >	
				<input type="hidden" name="user_sect" value="PATIN"> 
				
				<input type="hidden"  name="msg_gubun" value="mail"> <!-- 발송대상이 회원 -->	
	
	<table width="100%">
	<tr><td width="70%" valign="top">		
			<table class="t_view">
				<colgroup>
					<col width="15%" />
					<col  />
				</colgroup>

		
			<tr id="mail_select_txt1">
				<th>받는 메일주소</th>
				<td>
					<textarea id="txt_receiver" name="txt_receiver" style="width:100%;height:100px;" required="yes" message="받는 메일주소"></textarea>
					<p class="mt10 notice">* 여러 명에게 보내려면 메일주소 다음에(,) 콤마로 구분 예) 11@aa.com,22@bb.net (공백없이)</p>
				</td>
			</tr>
		
			
					<tr>
						<th>보내는 사람</th>
						<td><input type="text" style="width:30%;" name="fromname" required="yes"  message="보내는 사람" value=""></td>
					</tr>
					<tr>
						<th>보내는 메일주소</th>
						<td><input type="text" style="width:40%;" name="fromemail" required="yes"  message="보내는 메일주소" value=""></td>
					</tr>

					<tr>
						<th>메일제목</th>
						<td><input type="text" style="width:70%;" name="subject" required="yes"  message="메일제목" value=""></td>
					</tr>
					<tr>
						<th>메일내용</th>
						<td>
						<?
							include $_SERVER["DOCUMENT_ROOT"].$_P_DIR_FCKeditor."fckeditor.php" ;
							$oFCKeditor = new FCKeditor('content') ;
							$oFCKeditor->BasePath	= $_P_DIR_FCKeditor;
							$oFCKeditor->Config['SkinPath'] =  '/PROGRAM_FCKeditor/editor/skins/office2003/';
							$oFCKeditor->Height = 400;
							$oFCKeditor->Value = '';
							$oFCKeditor->Value;
							$oFCKeditor->Create() ;
						?>
						</td>
					</tr>
				
			</table>
			</td>
			<td width="30%" valign="top">
			<!-- 주소록 -->
				<div class="adr_area">
					<p class="align_r">
						<select name="inr_s_gender" id="inr_s_gender" size="1" style="vertical-align:middle;" >
							<option value="">회원구분</option>
							<option value="NOR" <?=$s_gender=="NOR"?"selected":""?>>건축가</option>
							<option value="SPE" <?=$s_gender=="SPE"?"selected":""?>>인테리어회사</option>
						</select>&nbsp;&nbsp;
						<!--<select name="inr_s_level" id="inr_s_level" size="1" style="vertical-align:middle;" >
							<option value="">등급별 검색</option>
							<?
								$sub_sql = "select idx,level_code,level_name from member_level_set where 1=1 and is_del = 'N' order by level_align asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[level_code]?>" <?=$s_level==$sub_row[level_code]?"selected":""?>><?=$sub_row[level_name]?></option>
							<?}?>
						</select>-->
						<select name="inr_field" id="inr_field" size="1" style="vertical-align:middle;">
							<option value="">검색기준</option>
							<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
							<option value="user_name" <?=$field=="user_name"?"selected":""?>>이 름</option>
							<option value="com_name" <?=$field=="com_name"?"selected":""?>>회사명</option>
							<option value="cell" <?=$field=="cell"?"selected":""?>>휴대전화</option>
							<option value="email" <?=$field=="email"?"selected":""?>>이메일</option>
						</select>
					
					<input type="text" name="inr_keyword" id="inr_keyword" style="width:200px;" value="<?=$keyword?>" >
					<a href="javascript:go_portpolio_2();" class="btn_sch">조회</a>
					</p>
					<div class="tbl_wrap">
						<table>
							<thead>
								<tr>
									<th width="90px" colspan="2">이름 <input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>
									<th width="94px">등록일</th>
									<th width="112px" style="padding-right:18px;">메일주소</th>
								</tr>
							</thead>
							<tbody id="port_list_area2">
								<!-- ajax member_select_mail.php 로 불러오는 영역 -->
							</tbody>
						</table>
					</div>
					<!-- //tbl_wrap -->
				</div>
				<!-- //주소록 -->
			</td></tr></table>
			</form>

			<form name="portfrm_2" id="portfrm_2" method="post">
			<input type="hidden" name="s_gender" id="s_gender">
			<input type="hidden" name="s_level" id="s_level">
			<input type="hidden" name="field" id="field">
			<input type="hidden" name="keyword" id="keyword">
			</form>

			<div class="align_c margin_t20">
				<!-- 등록 -->
				<a href="javascript:btn_tran_click();" class="btn_blue2">메일발송</a>
				<!-- 목록 -->
				<a href="javascript:frm.reset();" class="btn_blue2">취소</a>
			</div>
		</div>
	</div>
</section>
<!-- //content -->
<!--//js-->
<!--footer-->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>