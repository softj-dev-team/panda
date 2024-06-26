<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));

$s_cate = trim(sqlfilter($_REQUEST['s_cate']));
$s_professor = trim(sqlfilter($_REQUEST['s_professor']));
$msg_key =  time().randomChar(5);
?>
<script type="text/javascript" src="../js/mail.js"></script>
<style type="text/css">
.adr_area {float:left;width:100%;}
.adr_area .adr_sel {width:120px;}
.adr_area .btn_sch {display:inline-block;background:#666;color:#fff;border-radius:4px;padding:4px 10px;vertical-align:middle;font-weight: bold;}

.adr_area .tbl_wrap {margin-top:12px;border:1px solid #cdcdcd;height:401px;width:98%;}
.adr_area table {width:100%;}
.adr_area table thead th {background:#444842;height:35px;border-left:1px solid #575a56;color:#fff;}
.adr_area table thead th:first-child {border-left:none;}
.adr_area table tbody td {padding:6px 5px;text-align:center;}

.adr_area table thead {float:left;width:98%;}
.adr_area table tbody {float:left;width:95%px;height:360px;overflow-x:hidden;overflow-y:scroll;padding:3px;}
.adr_area table thead:after, .adr_area table tbody:after {content:"";display:block;clear:both;}
.adr_area table td.chk {width:5%;}
.adr_area table td.name {width:45%;}
.adr_area table td.date {width:25%;}
.adr_area table td.phn {width:25%;}
.align_r {text-align:right;}
</style>
<!-- content -->
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/member_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>푸쉬메시지 발송</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>푸쉬메시지 보내기</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
			<form id="theForm" name="theForm" method="post" action="member_push_write_action.php" target="_fra_admin" enctype="multipart/form-data">
				<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
				<input type="hidden" name="smenu" value="<?=$smenu?>"/>
				<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
				<input type="hidden" name="mode" value="msg_send"/>
				<input type="hidden" name="msg_key" value="<?=$msg_key?>"/>
				<input type="hidden" name="send_sect" value="member"/>
				<input type="hidden" name="goods" >	
				<input type="hidden" name="user_sect" value="GEN"> 
				
				<input type="hidden"  name="msg_gubun" value="mail"> <!-- 발송대상이 회원 -->	
	
	<table width="100%">
	<tr><td width="70%" valign="top">		
			<table class="t_view">
				<colgroup>
					<col width="15%" />
					<col  />
				</colgroup>

		
			<tr id="mail_select_txt1">
				<th>발송대상 아이디</th>
				<td>
					<textarea id="txt_receiver" name="txt_receiver" style="width:100%;height:100px;" required="yes" message="발송대상 아이디"></textarea>
					<p class="mt10 notice">* 여러 명에게 보내려면 아이디 다음에(,) 콤마로 구분 예) choi,choi2 (공백없이)</p>
				</td>
			</tr>
		
			
					<tr>
						<th>제목</th>
						<td><input type="text" style="width:80%;" name="subject" required="yes"  message="제목" value=""></td>
					</tr>
					<tr>
						<th>링크주소</th>
						<td><input type="text" style="width:80%;" name="tour_homepage" required="no"  message="링크주소" value=""></td>
					</tr>
					<tr>
						<th>메시지</th>
						<td>
							<textarea name="content" id="editor" style="width:80%;height:200px;" required="yes"  message="메시지"></textarea>
						</td>
					</tr>
				
			</table>
			</td>
			<td width="30%" valign="top">
			<!-- 주소록 -->
				<div class="adr_area">
						<select name="inr_field" id="inr_field" size="1" style="vertical-align:middle;">
							<option value="">검색기준</option>
							<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
							<option value="user_name" <?=$field=="user_name"?"selected":""?>>닉네임</option>
							<option value="email" <?=$field=="email"?"selected":""?>>이메일</option>
						</select>
						<input type="text" name="inr_keyword" id="inr_keyword" style="width:50%;" value="<?=$keyword?>" >
						<a href="javascript:go_portpolio_2();" class="btn_sch">조회</a>
					<div class="tbl_wrap">
						<table style="width:450px;">
							<thead>
								<tr>
									<th style="width:200px;"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"> 아이디</th>
									<th style="width:100px;">닉네임</th>
									<th style="width:150px;">이메일</th>
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
			<input type="hidden" name="s_gender" id="s_gender" value="push">
			<input type="hidden" name="s_level" id="s_level">
			<input type="hidden" name="field" id="field">
			<input type="hidden" name="keyword" id="keyword">
			</form>

			<div class="write_btn align_r">
					<button type="button" class="btn_modify" onclick="btn_tran_click();">푸쉬발송</button>
					<a href="javascript:frm.reset();" class="btn_list">취소</a>
			</div>
				
		</div>
		<!-- content 종료 -->
	</div>
</div>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

		