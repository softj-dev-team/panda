<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/cert/cfg/cert_conf.php";  // 본인인증 환경설정
?>

<body>

	<!--header-->
	<div><? include "./common/header.php"; ?></div>

	<!--content-->

	<section class="sub">
		<div class="sub_title">
			<h2>일반회원 정보입력</h2>
			<p>본 서비스는 실명가입을 원칙으로 하므로 타인의 명의를 도용하거나 실명이 아닌 허위가입일 경우 사이트를 이용하시는데 제한을 받으실 수 있습니다.</p>
		</div>
		<form name="frm" id="frm" action="join_detail_action.php" method="post" target="_fra" enctype="multipart/form-data">
			<input type="hidden" id="join_push_key" name="push_key">
			<input type="hidden" name="sns_code" value="email">
			<input type="hidden" name="id_ok" id="id_ok">
			<input type="hidden" name="member_gubun" id="member_gubun">
			<input type="hidden" name="ipin_code" id="ipin_code" /> <!-- 인증수단 -->
			<input type="hidden" name="ipin_code_dup" id="ipin_code_dup" /> <!-- 인증확인 코드 -->
			<input type="hidden" name="birthday" id="birthday" />
			<input type="hidden" name="gender" id="gender" />
			<input type="hidden" name="user_ci" id="user_ci" />
			<div class="tlb">
				<table>
					<tr>
						<th>이름<span class="inportant">*</span></th>
						<td><input type="text" name="member_name" id="member_name" required="yes" message="이름" readonly></td>
					</tr>
					<tr>
						<th>핸드폰 번호<span class="inportant">*</span></th>
						<td>
							<div class="tlb_flex">
								<input type="text" name="cell" id="join_member_cell" onblur="set_cell_num('join_member_cell');" required="yes" message="핸드폰 번호" readonly><button type="button" class="btn" onclick="auth_type_check();">본인인증</button>
							</div>
							<p>6~20자 이내의 영문, 숫자만 가능합니다.</p>
						</td>
					</tr>
					<tr>
						<th>아이디<span class="inportant">*</span></th>
						<td>
							<div class="tlb_flex">
								<input type="text" name="member_id" id="member_id" required="yes" message="아이디"><button type="button" class="btn" onclick="ch_id();">중복확인</button>
							</div>
							<div id="check_id" style="paddig-top:10px;"></div>
							<p>6~20자 이내의 영문, 숫자만 가능합니다.</p>
						</td>
					</tr>
					<tr>
						<th>비밀번호<span class="inportant">*</span></th>
						<td>

							<div class="tlb_flex">
								<input type="password" name="member_password" id="member_password" required="yes" message="비밀번호">
							</div>

							<p>영문, 숫자, 특수문자를 조합하여 8~20자로 입력해 주세요.</p>
						</td>
					</tr>
					<tr>
						<th>비밀번호 확인<span class="inportant">*</span></th>
						<td><input type="password" name="member_password2" id="member_password2" required="yes" message="비밀번호 재입력"></td>
					</tr>
					<tr>
						<th>이메일<span class="inportant">*</span></th>
						<td><input type="text" name="member_email" id="member_email" required="yes" message="이메일" is_email="yes"></td>
					</tr>

					<? if ($inc_partner_id == "admin") { ?>
						<!--
						<tr>
							<th>가맹점 ID</th>
							<td><input type="text" name="partner_id" id="partner_id" required="no" message="가맹점 ID"></td>
						</tr>
					-->
					<? } else { ?>
						<!--
						<input type="hidden" name="partner_id" id="partner_id" value="<?= $inc_partner_id ?>" />
					-->
					<? } ?>
				</table>
			</div>
		</form>

		<div class="btn_pry">
			<a href="/" class="btn01 btn">취소</a>
			<a href="javascript:go_regist_submit();" class="btn02 btn">다음단계</a>
		</div>

		<form name="form_auth">
			<input type="hidden" name="ordr_idxx" />
			<!-- 요청종류 -->
			<input type="hidden" name="req_tx" value="cert" />
			<!-- 요청구분 -->
			<input type="hidden" name="cert_method" value="01" />
			<!-- 웹사이트아이디 : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
			<input type="hidden" name="web_siteid" value="<?= $g_conf_web_siteid ?>" />
			<!-- 노출 통신사 default 처리시 아래의 주석을 해제하고 사용하십시요 
                     SKT : SKT , KT : KTF , LGU+ : LGT
                <input type="hidden" name="fix_commid"      value="KTF"/>
                -->
			<!-- 사이트코드 : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
			<input type="hidden" name="site_cd" value="<?= $g_conf_site_cd ?>" />
			<!-- Ret_URL : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
			<input type="hidden" name="Ret_URL" value="<?= $g_conf_Ret_URL ?>" />
			<!-- cert_otp_use 필수 ( 메뉴얼 참고)
                     Y : 실명 확인 + OTP 점유 확인 , N : 실명 확인 only
                -->
			<input type="hidden" name="cert_otp_use" value="Y" />
			<!-- 리턴 암호화 고도화 -->
			<input type="hidden" name="cert_enc_use_ext" value="Y" />

			<input type="hidden" name="res_cd" value="" />
			<input type="hidden" name="res_msg" value="" />

			<!-- up_hash 검증 을 위한 필드 -->
			<input type="hidden" name="veri_up_hash" value="" />

			<!-- 본인확인 input 비활성화 -->
			<input type="hidden" name="cert_able_yn" value="" />

			<!-- web_siteid 을 위한 필드 -->
			<input type="hidden" name="web_siteid_hashYN" value="Y" />

			<!-- 가맹점 사용 필드 (인증완료시 리턴)-->
			<input type="hidden" name="param_opt_1" value="opt1" />
			<input type="hidden" name="param_opt_2" value="opt2" />
			<input type="hidden" name="param_opt_3" value="opt3" />
		</form>

	</section>

	<script>
		/* 폰갭 푸시 키 받기 시작 */
		$(function() {
			if (typeof Android != "undefined") {
				Android.get_fcm_token();
			}
		});

		function androidCallJSgcm(push_key) {
			var frm = document.forms["frm"];
			//frm.elements["push_key"].value = push_key;
			$("#join_push_key").val(push_key);
		}

		$(document).ready(function() {
			if (typeof Android != "undefined") {
				Android.get_fcm_token();
			}
		});
		/* 폰갭 푸시 키 받기 종료 */

		function go_regist_submit() {
			/*if(document.getElementById("agreement").checked == false){
				alert('이용약관에 동의하여 주세요.');
				return;	
			}
			if(document.getElementById("privacy").checked == false){
				alert('개인정보 수집 및 이용에 동의하여 주세요.');
				return;	
			}*/
			var ipin_code = $("#ipin_code").val();
			if (ipin_code != "0000") {
				alert('먼저 본인인증을 완료해 주세요.');
				return;
			}
			var check = chkFrm('frm');
			if (check) {
				if (document.frm.id_ok.value != "Y") {
					alert('아이디 중복확인이 되지 않았습니다.');
					return;
				}
				if (validatePassword(document.frm.member_password.value)) {
					if (document.frm.member_password.value != document.frm.member_password2.value) {
						alert('비밀번호와 비밀번호 확인이 맞지 않습니다 ! ');
						const passWord = document.querySelector('#member_password2');
						passWord.value = '';
						return;
					}
					frm.submit();
				} else {
					alert('비밀번호는 영문, 숫자, 특수문자를 조합하여 8~20자로 입력해 주세요.');
					return;
				}
				/*
				if (fnCheckId(document.frm.member_password.value, "비밀번호")) {
					if (document.frm.member_password.value != document.frm.member_password2.value) {
						alert('비밀번호와 비밀번호 확인이 맞지 않습니다 ! ');
						const passWord = document.querySelector('#member_password2');
						passWord.value = '';
						return;
					}
					frm.submit();
				} else {
					return;
				}
				*/
			} else {
				false;
			}
		}

		function validatePassword(input) {
			// 영문 대문자, 소문자, 숫자, 특수 문자 중 적어도 하나가 포함되어야 합니다.
			const pattern = /^(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,20}$/;

			// 입력값이 패턴과 일치하는지 확인합니다.
			return pattern.test(input);
		}

		function handleOnInput(el, maxlength) {
			if (el.value.length > maxlength) {
				el.value = el.value.substr(0, maxlength);
			}
		}

		function ch_id() {
			var chkid = $("#member_id").val();
			if (chkid == "") {
				alert("아이디를 입력하세요.");
				$("#member_id").focus();
				return;
			}
			if (chkid.length != chkid.replace(/[^a-zA-Z0-9]/gi, "").length) {
				alert("아이디는 영문과 숫자로만 작성하십시오.");
				$("#member_id").focus();
				return;
			}
			/*if(!emailCheck(chkid)){
				alert("이메일 형식이 올바르지 않습니다.");
				$("#member_id").focus();
				return;
			}*/
			var vurl = "/pro_inc/check_id_duple.php";
			$.ajax({
				url: vurl,
				type: "GET",
				data: {
					idx: "",
					user_id: $("#member_id").val()
				},
				async: false,
				dataType: "json",
				success: function(v) {
					if (v.success == "true") {
						$("#id_ok").val("Y");
						$("#check_id").html(v.msg);
						//alert(v.msg);
					} else if (v.success == "false") {
						$("#id_ok").val("N");
						$("#check_id").html(v.msg);
						//alert(v.msg);
					} else {
						alert("오류 발생!");
					}
				}
			});
		}

		//숫자,영문 조합검사
		function fnCheckId(uid, str) {
			if (!/^[a-z0-9]{6,12}$/.test(uid)) {
				alert(str + '는 숫자와 영(소)문자 조합으로 6~12자리를 사용해야 합니다.');
				return false;
			}

			var chk_num = uid.search(/[0-9]/g);
			var chk_eng = uid.search(/[a-z]/ig);

			if (chk_num < 0 || chk_eng < 0) {
				alert(str + '는 숫자와 영문자를 혼용하여야 합니다.');
				return false;
			}

			if (/(\w)\1\1\1/.test(uid)) {
				alert(str + '에 같은 문자를 4번 이상 사용하실 수 없습니다.');
				return false;
			}
			return true;
		}

		function set_cell_num(target) {
			var cell_inp = $("#" + target + "").val();
			//alert(cell_inp);
			var cell_ninp = cell_inp.replace(/\-/g, "");
			//alert(cell_ninp);
			$("#" + target + "").val(cell_ninp);
		}
	</script>

	<!-- 본인인증 관련 스크립트 -->
	<script type="text/javascript">
		// 인증창 종료후 인증데이터 리턴 함수
		function auth_data(frm) {
			var auth_form = document.form_auth;
			var nField = frm.elements.length;
			var response_data = "";

			// up_hash 검증 
			if (frm.up_hash.value != auth_form.veri_up_hash.value) {
				alert("up_hash 변조 위험있음");

			}


			/* 리턴 값 모두 찍어보기 (테스트 시에만 사용) */
			var form_value = "";

			for (i = 0; i < frm.length; i++) {
				form_value += "[" + frm.elements[i].name + "] = [" + frm.elements[i].value + "]\n";

				if (frm.elements[i].name == "res_cd") {
					var res_cd = frm.elements[i].value; // 인증결과 코드
				}
				if (frm.elements[i].name == "res_msg") {
					var res_msg = frm.elements[i].value; // 인증결과 메시지
				}
				//if(frm.elements[i].name == "user_name"){
				if (frm.elements[i].name == "user_name_real") {
					var user_name = frm.elements[i].value; // 인증받은 성명
				}
				//if(frm.elements[i].name == "phone_no"){
				if (frm.elements[i].name == "phone_no_real") {
					var phone_no = frm.elements[i].value; // 인증받은 전화번호
				}
				if (frm.elements[i].name == "comm_id") {
					var comm_id = frm.elements[i].value; // 인증받은 통신사
				}
				if (frm.elements[i].name == "birth_day") {
					var birth_day = frm.elements[i].value; // 인증받은 생년월일
				}
				if (frm.elements[i].name == "sex_code") {
					var sex_code = frm.elements[i].value; // 인증받은 성별
				}
				if (frm.elements[i].name == "di_url") {
					var di_url = frm.elements[i].value; // 인증받은 인증코드
				}
				if (frm.elements[i].name == "dn_hash") {
					var dn_hash = frm.elements[i].value; // 인증받은 인증코드
				}
				if (frm.elements[i].name == "user_ci") {
					var user_ci = frm.elements[i].value; // 인증받은 인증코드
				}
			}

			if (res_cd != "0000") {
				alert("인증에 실패 하였습니다."+frm.elements);console.log(frm.elements)
				return;
			} else {
				alert("인증되었습니다.");
			}

			//console.log(form_value);

			//document.write(form_value);
			/*
				[phone_no] = [] 
				[res_msg] = [정상처리] 
				[DI] = [] 
				[user_name_url_yn] = [] 
				[cert_otp_use] = [] 
				[b2b_yn] = [] 
				[comm_id] = [LGT] 
				[sex_code] = [] 
				[safe_guard_yn] = [] 
				[van_tx_id] = [] 
				[tx_type] = [3300] 
				[good_code] = [] 
				[site_key] = [] 
				[web_siteid_hashYN] = [Y] 
				[dn_hash] = [977C3B3CB3D7C336D979A3447F4D067C4338DB72] 
				[res_cd] = [0000] 
				[DI_URL] = [] 
				[app_time] = [] 
				[site_name] = [] 
				[Ret_URL] = [https://pandasms.co.kr/cert/kcpcert_proc_res.php] 
				[shopInfo_require_yn] = [] 
				[auth_tx_id] = [] 
				[kcp_merchant_time] = [] 
				[info_code] = [] 
				[cert_type] = [] 
				[user_name] = [최창환] 
				[mvno_code] = [] 
				[cert_02_yn] = [Y] 
				[log_trace_no] = [AJQNCbF6BdYoa5eu] 
				[param_opt_1] = [opt1] 
				[Ret_Noti] = [] 
				[param_opt_3] = [opt3] 
				[param_opt_2] = [opt2] 
				[cert_enc_use] = [Y] 
				[enc_cert_data2] = [.1.3D99C5BA4761DEAA7080CB1D7D5E9F7400C134BBC3F5C25CE45E0752A6EF5C412B730B180B0A6A5E138F67BE9D5342737C2E6ABE9283B448F1F27539AFF0B7A10EC3B1141EC4AA9865FA8F3F8922957C058386FCE00FD81E9A73270F8D4C7BD7905DBBB50F8B9812C01C4FA3976FAFD9835745934F8038AFEFE368A9BAA4B89ACC2D2F16F106667BD6F8B8C037C41410A393578AC399D58598786DEBD0E0DDE00AFEDE9370DC60FF2ABFA189FD5ADD79B368E653925AFE03EA9B4C73575114FE1D06C79105368C1C2B43C40DFD1177DBF16954286A87CD44FDC53597D81B0FC2B7504DBEE6AE23EB4C4036738BD204A92758387B92D00F780F1AE4AECB6B37B1EA928A78A1EFA4C226D821E765F3725C441A0BD4F98752DC79721CE3EAE9D32FD6483C4419B0DF171F8B33FA79EE547CFF2C8D3CA9BA7D228BC135AF53E5A0BAB6AFBF17795F23E44F012D4B33E2CDEE411BABA3FDCB844D59BE81715EF1D9AAC0A76B451230A9FF4D94094B82A1AABA1ADE6F087F3709730B4353431A5DFFF0236BCF2C875E0EEAF5C01ECA0E651CDB21BBB442737B8966E24F1F72B0B8E189B53C813F33DDF6ADAB1B42C0621996FF5551490D46FF70C8B0DCE1779F78F76111CE9D51E8B6DC56E410335A95AE013C7AC4D32F79A7D89AEAEA186AC62311E5AA84D8CE0D9609105FB0087D20EB9729262C62EF2B99B0AEC754F3895D698FE1089B6BEC916BE72504A056C02AF9342253EEDBAA09F088D5A277FBA03BD4509A320A9AD6D40C7CD8FCBD206FEF02CB2F9F1575C4806374350B8E07CDA6A21CDE6C1F659E4507ECCF1B9F55AA93FF724048788BCFB8D1A145C50D4EE2F7D1637314E1595FF40077A72597271AB492AEFB46F686A9BEDA3538C06BBE606D1449B5339F555BBA731CDC5E5BF6C61496DB9DF041E6946F2ECBF6D64A05440507FB82CA9042C85C986267E25ADFEED87C8F21.kt_xjcRNtejQREKVWo9AnhL9m8lBMMa1DMHS_BvLBxQVwTLyr-Ka3jneBOU2v6jGuJ_DHkr7BdJ3njKG6i1PFpY2t79d-lVkDd_pVsTg_pKz87VV1F9NPCBd92XkijGqKyPAfGOnGLOAsYIN2Yvc6je9t97XVeQ6CZzZYZFVtxAfuPCXKj7eTdRqH18-bvTLon7ZNFZGX9omv-tIub7NuS2yStevMBFY9ZlRcR9Rs6TSMfZ4kaxH1Yvad0Ehq4r_JG9leIVP3WPod77Z2j6rtMe5ZCykkLwk7Tt62DLiVMhJTqUXfdDxgdo61luaYTEH4zXtvdIZlT0Xjepzg8CSwQ.MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmO92ZYkJNYMn80Q982_psNFMIS3Zm_oCtEQMfKbap7y3YE9TxCxGWiTlCpsMmmy0WdiJQVJoMLec2Z7O436hSN2praZAQzXVNYq50Y4bhrVZhNlN3KPyM4YjLIldqTuyvgY1xxfrpLLwk1KuDufm5jJf05UcRGsnkWRM6GxhyAqZjTdDYkaP1XXlkMHKi39fPqCoyyF6tlW4byS_mjeVopbFXoCH4DcrVJiO7j9V8oE4ZQiztNPRYr5SoCDOzhGK9zlD4Cz2DMl-XRqrSrvY0-8wLr9bL6UfS9yJPoexXDzcWfrQXIruvCZghg7uwicEqeE6koz2ZXJd62JfbmaQKQIDAQAB.cLQ_4EB-bzFFRvVXIhmK_OuBH3q74UMCuMT0rpV8pnN_lmzvixWZDmnJBa-0gIplzaezeowWVFIrNQHpB8gBrMhYc-9L9fdjPwA9DQEnDts5d6dwlbnwiqmeGUfXxCQRo1VUCOMDIth8CHnHj5MIIj7N9ztGag1ELgmgLWOxHP23S-R1VpVE21M0PPikFDRD2PWsuLrzsb6WBicf4fKwl-mwm9tIhY7wJKBPeWrNSXCqDAS8YscBaNt1jbm6BdLIPlouNvudp6Ta_H3AIonb6m5uepqb65I2gLnvmbPxcRxwzu00Wc4gOa9ebCd6wEas8KX8pcsPUX_yen0mw12G6g] 
				[cc] = [] 
				[Logo_URL] = [] 
				[cert_enc_use_ext] = [Y] 
				[cert_no] = [23534228812325] 
				[CI] = [] 
				[session_id] = [] 
				[local_code] = [] 
				[web_siteid] = [J23100409787] 
				[cp_code] = [] 
				[cert_01_yn] = [Y] 
				[birth_day] = [] 
				[up_hash] = [B32C2912C8FE00CF3B9F860B22B9EAF308ECBB95] 
				[site_cd] = [AJQNC] 
				[CI_URL] = [] 
				[per_cert_no] = [23534228812325] 
				[ordr_idxx] = [202310111696993659429] 
				[cert_method] = [01] 
				[req_tx] = [otp_auth]
			*/

			$("#ipin_code").val(res_cd);
			//$("#ipin_code_dup").val(di_url);
			$("#ipin_code_dup").val(dn_hash);
			$("#member_name").val(user_name);
			if (user_name != "") {
				$("#member_name").prop('readonly', true);
			}
			$("#join_member_cell").val(phone_no);
			if (phone_no != "") {
				$("#join_member_cell").prop('readonly', true);
			}
			$("#birthday").val(birth_day);
			$("#gender").val(sex_code);
			$("#user_ci").val(user_ci);
		}

		// 인증창 호출 함수
		function auth_type_check() {
			var auth_form = document.form_auth;

			if (auth_form.ordr_idxx.value == "") {
				alert("요청번호는 필수 입니다.");
				return;
			} else {
				if ((navigator.userAgent.indexOf("Android") > -1 || navigator.userAgent.indexOf("iPhone") > -1) == false) // 스마트폰이 아닌경우
				{
					var return_gubun;
					var width = 410;
					var height = 500;

					var leftpos = screen.width / 2 - (width / 2);
					var toppos = screen.height / 2 - (height / 2);

					var winopts = "width=" + width + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
					var position = ",left=" + leftpos + ", top=" + toppos;
					var AUTH_POP = window.open('/cert/kcpcert_proc_req.php', 'auth_popup', winopts + position);

					//alert(winopts);
				}

				auth_form.method = "post";
				auth_form.target = "auth_popup"; // !!주의 고정값 ( 리턴받을때 사용되는 타겟명입니다.)
				auth_form.action = "/cert/kcpcert_proc_req.php"; // 인증창 호출 및 결과값 리턴 페이지 주소
				auth_form.submit();
				//return true;
			}
		}

		/* 예제 */
		/*window.onload=function()
            {
                var today            = new Date();
                var year             = today.getFullYear();
                var month            = today.getMonth() + 1;
                var date             = today.getDate();
                var time             = today.getTime();
                var year_select_box  = "<option value=''>선택 (년)</option>";
                var month_select_box = "<option value=''>선택 (월)</option>";
                var day_select_box   = "<option value=''>선택 (일)</option>";
                
                if(parseInt(month) < 10) {
                    month = "0" + month;
                }
    
                if(parseInt(date) < 10) {
                    date = "0" + date;
                }
    
                year_select_box = "<select name='year' class='frmselect' id='year_select'>";
                year_select_box += "<option value=''>선택 (년)</option>";
       
                for(i=year;i>(year-100);i--)
                {
                    year_select_box += "<option value='" + i + "'>" + i + " 년</option>";
                }
                
                year_select_box  += "</select>";
                month_select_box  = "<select name=\"month\" class=\"frmselect\" id=\"month_select\">";
                month_select_box += "<option value=''>선택 (월)</option>";
                
                for(i=1;i<13;i++)
                {
                    if(i < 10)
                    {
                        month_select_box += "<option value='0" + i + "'>" + i + " 월</option>";
                    }
                    else
                    {
                        month_select_box += "<option value='" + i + "'>" + i + " 월</option>";
                    }
                }
                
                month_select_box += "</select>";
                day_select_box    = "<select name=\"day\"   class=\"frmselect\" id=\"day_select\"  >";
                day_select_box   += "<option value=''>선택 (일)</option>";
                for(i=1;i<32;i++)
                {
                    if(i < 10)
                    {
                        day_select_box += "<option value='0" + i + "'>" + i + " 일</option>";
                    }
                    else
                    {
                        day_select_box += "<option value='" + i + "'>" + i + " 일</option>";
                    }
                }
                
                day_select_box += "</select>";
                
                document.getElementById( "year_month_day"  ).innerHTML = year_select_box + month_select_box + day_select_box;
                
                init_orderid(); // 요청번호 샘플 생성
            }*/

		window.onload = function() {
			init_orderid();
		}

		// 요청번호 생성 예제 ( up_hash 생성시 필요 ) 
		function init_orderid() {
			var today = new Date();
			var year = today.getFullYear();
			var month = today.getMonth() + 1;
			var date = today.getDate();
			var time = today.getTime();

			if (parseInt(month) < 10) {
				month = "0" + month;
			}

			var vOrderID = year + "" + month + "" + date + "" + time;

			document.form_auth.ordr_idxx.value = vOrderID;
		}
	</script>

	<!--footer-->
	<div><? include "./common/footer.php"; ?></div>

</body>

</html>