<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 관리자 로그인여부 확인
?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']);
$s_gubun = sqlfilter($_REQUEST['s_gubun']);
$s_level = sqlfilter($_REQUEST['s_level']);
$s_gender = sqlfilter($_REQUEST['s_gender']);
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu=' . $bmenu . '&smenu=' . $smenu . '&field=' . $field . '&keyword=' . $keyword . '&v_sect=' . $v_sect . '&s_gubun=' . $s_gubun . '&s_level=' . $s_level . '&s_gender=' . $s_gender . '&s_sect1=' . $s_sect1 . '&s_sect2=' . $s_sect2 . '&s_cnt=' . $s_cnt . '&s_order=' . $s_order;

$member_sect_str = "";

/*if($s_gubun == "NOR"){
	$member_sect_str = "";
} elseif($s_gubun == "SPE"){
	$member_sect_str = "VVIP 멤버십";
}*/

$sql = "SELECT *,
(select mb_short_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_short_fee,
(select mb_long_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_long_fee,
(select mb_img_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_img_fee,
(select mb_kko_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_kko_fee,
(select call_num from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as call_num,
(select call_memo from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as call_memo,
(select use_yn from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as use_yn,
(select auth_method from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as auth_method,
(select user_id from member_info where 1 and del_yn='N' and idx=(select member_idx from member_info_company 
where 1 and is_del='N' and idx=a.partner_idx order by idx desc limit 0,1)) as partner_id FROM member_info a where 1=1 and idx = '" . $idx . "'";
$query = mysqli_query($gconnet, $sql);

if (mysqli_num_rows($query) == 0) {
?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		alert('수정할 회원이 없습니다.');
		location.href = "member_list.php?<?= $total_param ?>";
		//
		-->
	</SCRIPT>
<?
	exit;
}

$row = mysqli_fetch_array($query);

$cell_arr = explode("-", $row[cell]);
$tel_arr = explode("-", $row[tel]);
$com_tel_arr = explode("-", $row[com_tel]);
$com_num_arr = explode("-", $row[com_num]);
$birthday_arr = explode("-", $row[birthday]);
$post_arr = explode("-", $row[post]);

$bbs_code = "member";


?>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
	function IsNumber(formname) {
		var form = eval("document.frm." + formname);
		for (var i = 0; i < form.value.length; i++) {
			var chr = form.value.substr(i, 1);
			if ((chr < '0' || chr > '9')) {
				return false;
			}
		}
		return true;
	}

	function checkNum() {
		if (event.keyCode < 48 || event.keyCode > 57) {
			event.returnValue = false;
		}
	}

	function checkNumber() {
		var objEv = event.srcElement;
		var numPattern = /([^a-z,0-9])/;
		numPattern = objEv.value.match(numPattern);
		if (numPattern != null) {
			alert("한글, 특수문자, 대문자 입력 불가입니다!");
			objEv.value = "";
			objEv.focus();
			return false;
		}
	}

	function go_submit() {
		var check = chkFrm('frm');

		if (check) {

			if (document.frm.id_ok.value != "Y") {
				alert('아이디 중복검색을 해주세요.');
				return;
			}
            var isValid = true;
            var ajaxRequests = []; // Ajax 요청을 저장할 배열
            $('input[name="call_num[]"]').each(function() {
                var pcs = $(this).val().replace(/[^0-9]/g, '');
               if(validatePhoneNumber(pcs)){
                   // 발신 차단된 번호 확인을 위한 Ajax 요청을 비동기적으로 처리
                   var request = $.ajax({
                       url: '/kakao/index.php?route=getBlockCallNumber',
                       type: 'GET',
                       data: {cell_num: pcs},
                       dataType: 'json'
                   }).done(function(response) {
                       if (response.success) {
                           alert(pcs + " 발신차단 된 휴대전화번호는 등록이 불가능합니다.");
                           isValid = false;
                       }
                   }).fail(function(xhr, status, error) {
                       alert("서버와 연결에 실패했습니다. 다시 시도해 주세요.");
                       console.error('Error: ' + error);
                       console.error('Status: ' + status);
                       console.dir(xhr);
                       isValid = false;
                   });

                   // Ajax 요청을 배열에 추가
                   ajaxRequests.push(request);

               }else{
                   alert(pcs+' 는(은) 올바른 휴대폰 번호 형식이 아닙니다.');
                   isValid = false;  // 잘못된 형식이면 상태를 변경
                   return false;  // each() 루프 중지
               }
            });
            // Ajax 요청들이 완료될 때까지 기다린 후, 유효성 검사 통과 시 폼 제출
            $.when.apply($, ajaxRequests).done(function() {
                if (isValid) {
                    <?php if ($row['member_gubun'] == "SPE") { ?>
                    if (!vender_num(document.frm.com_num1.value, document.frm.com_num2.value, document.frm.com_num3.value)) {
                        isValid = false;
                        return;
                    }
                    <? } ?>

                    if (document.frm.member_password.value) {
                        if (fnCheckId(document.frm.member_password.value, "비밀번호")) {
                            if (document.frm.member_password.value != document.frm.member_password2.value) {
                                alert('비밀번호와 비밀번호 확인이 맞지 않습니다!');
                                isValid = false;
                            }
                        } else {
                            isValid = false;
                        }
                    }

                    if (isValid) {
                        document.frm.submit(); // 모든 검증을 통과하면 폼을 제출
                    }
                }
            });

		} else {
			false;
		}
	}

	function vender_num(num1, num2, num3) {

		var num = (num1 + num2 + num3)
		var w_c, w_e, w_f, w_tot
		w_c = num.charAt(8) * 5
		w_e = parseInt((w_c / 10), 10)
		w_f = w_c % 10
		w_tot = num.charAt(0) * 1
		w_tot += num.charAt(1) * 3
		w_tot += num.charAt(2) * 7
		w_tot += num.charAt(3) * 1
		w_tot += num.charAt(4) * 3
		w_tot += num.charAt(5) * 7
		w_tot += num.charAt(6) * 1
		w_tot += num.charAt(7) * 3
		w_tot += num.charAt(9) * 1
		w_tot += (w_e + w_f)
		if (!(w_tot % 10)) {
			return (true);
		} else {
			alert("사업자 수정 번호가 규격에 맞지 않습니다.")
			return (false);
		}
	}

	//이메일 넣기
	function mailChange(get) {
		document.frm.email2.value = get;
	}

	function focus_next(num, fromform, toform) {
		var str = fromform.value.length;
		if (str == num)
			toform.focus();
	}

	//나이계산
	function set_age(jumin1, jumin2) {
		if (jumin1.length == 6 && jumin2.length >= 1) {
			var date = new Date();
			var strAge = "";
			var strSex = "";
			var strYYYY = null;
			var iAge = 0;
			strAge = jumin1.substr(0, 2);
			strSex = jumin2.substr(0, 1);
			//3, 4는 국내 2000년 이후 출생일 경우, 7, 8은 외국인 국내거주자 중 2000년 이후 출생자
			if ("3478".indexOf(strSex) != -1) {
				strYYYY = date.getYear();
				iAge = parseInt(strYYYY) - parseInt('20' + strAge) + 1;
				if (iAge < 0) {
					alert("주민번호가 잘못되었습니다.");
					return;
				}
				return iAge;
			} else {
				strYYYY = date.getYear();
				iAge = parseInt(strYYYY) - parseInt('19' + strAge) + 1;
				return iAge;
			}
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
				idx: <?= $idx ?>,
				user_id: $("#member_id").val()
			},
			async: false,
			dataType: "json",
			success: function(v) {
				if (v.success == "true") {
					$("#id_ok").val("Y");
					$("#check_id").html(v.msg);
				} else if (v.success == "false") {
					$("#id_ok").val("N");
					$("#check_id").html(v.msg);
				} else {
					alert("오류 발생!");
				}
			}
		});
	}

	function ch_email() {
		var chkemail = $("#member_email").val();
		if (chkemail == "") {
			alert("이메일을 입력하세요.");
			$("#member_email").focus();
			return;
		}
		/*if (chkemail.length !=chkemail.replace(/[^a-zA-Z0-9]/gi, "").length ){
			alert("아이디는 영문과 숫자로만 작성하십시오.");
			$("#member_email").focus();
			return;
		}*/
		if (!emailCheck(chkemail)) {
			alert("이메일 형식이 올바르지 않습니다.");
			$("#member_email").focus();
			return;
		}
		var vurl = "/pro_inc/check_email_duple.php";
		$.ajax({
			url: vurl,
			type: "GET",
			data: {
				idx: <?= $idx ?>,
				user_email: $("#member_email").val()
			},
			async: false,
			dataType: "json",
			success: function(v) {
				if (v.success == "true") {
					$("#email_ok").val("Y");
					$("#check_email").html(v.msg);
				} else if (v.success == "false") {
					$("#email_ok").val("N");
					$("#check_email").html(v.msg);
				} else {
					alert("오류 발생!");
				}
			}
		});
	}

	function ch_nick() {
		var chknick = $("#user_nick").val();
		if (chknick == "") {
			alert("닉네임을 입력하세요.");
			$("#user_nick").focus();
			return;
		}
		/*if (chknick.length !=chknick.replace(/[^a-zA-Z0-9]/gi, "").length ){
			alert("사용자 아이디는 영문과 숫자로만 작성하십시오.");
			$("#member_nick").focus();
			return;
		}*/

		var vurl = "/pro_inc/check_nick_duple.php";
		$.ajax({
			url: vurl,
			type: "GET",
			data: {
				idx: <?= $idx ?>,
				user_nick: $("#user_nick").val()
			},
			async: false,
			dataType: "json",
			success: function(v) {
				if (v.success == "true") {
					$("#nick_ok").val("Y");
					$("#check_nick").html(v.msg);
				} else if (v.success == "false") {
					$("#nick_ok").val("N");
					$("#check_nick").html(v.msg);
				} else {
					alert("오류 발생!");
				}
			}
		});
	}

    // 숫자, 영문, 특수문자 조합 검사
    function fnCheckId(uid, str) {
        // 영문 소문자, 숫자, 특수문자 조합으로 6~12자리인지 확인
        if (!/^[a-z0-9!@#$%^&*()_+={}\[\]:;"'<>,.?/\\|-]{6,12}$/.test(uid)) {
            alert(str + '는 숫자, 영문자, 특수문자 조합으로 6~12자리를 사용해야 합니다.');
            return false;
        }

        // 숫자 포함 여부 검사
        var chk_num = uid.search(/[0-9]/g);
        // 영문자 포함 여부 검사
        var chk_eng = uid.search(/[a-z]/ig);
        // 특수문자 포함 여부 검사
        var chk_special = uid.search(/[!@#$%^&*()_+={}\[\]:;"'<>,.?/\\|-]/g);

        // 숫자, 영문자, 특수문자가 모두 포함되어 있는지 확인
        if (chk_num < 0 || chk_eng < 0 || chk_special < 0) {
            alert(str + '는 숫자, 영문자, 특수문자를 혼용하여야 합니다.');
            return false;
        }

        // 같은 문자를 4번 이상 반복하여 사용하는지 검사
        if (/(\w)\1\1\1/.test(uid)) {
            alert(str + '에 같은 문자를 4번 이상 사용하실 수 없습니다.');
            return false;
        }

        return true;
    }

	function openDaumPostcode() {
		new daum.Postcode({
			oncomplete: function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
				// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
				document.getElementById('zip_code1').value = data.zonecode;
				//document.getElementById('zip_code2').value = data.postcode2;
				document.getElementById('member_address').value = data.address;
				document.getElementById('member_address2').focus();
			}
		}).open();
	}


	function go_list() {
		location.href = "member_view.php?idx=<?= $idx ?>&<?= $total_param ?>";
	}

	function cate_sel_1(z) {
		var tmp = z.options[z.selectedIndex].value;
		//alert(tmp);
		_fra_admin.location.href = "cate_select_3.php?cate_code1=" + tmp + "&fm=frm&fname=gugun";
	}

	<? if ($v_sect == "SEL") { // 셀러회원 시작
	?>

		function cate_code_1(idx) {
			//alert(idx);
			$("#cate1_code_in").val(idx);
			get_data('cate_code_2.php', 'cate_code_2_area', 'member_idx=<?= $idx ?>&cate_code1=' + idx + '');
			get_data('cate_code_3.php', 'cate_code_3_area', 'member_idx=<?= $idx ?>&cate_code1=' + idx + '');
		}

		function cate_code_2(idx) {
			//alert(idx);
			var cate_code1 = $("#cate1_code_in").val();
			get_data('cate_code_3.php', 'cate_code_3_area', 'member_idx=<?= $idx ?>&cate_code1=<?= $cate_code1 ?>&cate_code2=' + idx + '');
		}

		cate_code_1('<?= $cate_code1 ?>');
		cate_code_2('<?= $cate_code2 ?>');
	<? } ?>

	$(function() {
		$(".datepicker").datepicker({
			changeYear: true,
			changeMonth: true,
			minDate: '-90y',
			yearRange: 'c-90:c',
			dateFormat: 'yy-mm-dd',
			showMonthAfterYear: true,
			constrainInput: true,
			dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
			monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월']
		});
	});
</script>

<body>
	<div id="wrap" class="skin_type01">
		<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/admin_top.php"; // 상단메뉴
		?>
		<div class="sub_wrap">
			<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/member_left.php"; // 좌측메뉴
			?>
			<!-- content 시작 -->
			<div class="container clearfix">
				<div class="content">
					<div class="navi">
						<ul class="clearfix">
							<li>HOME</li>
							<li>회원관리</li>
							<li><?= $member_sect_str ?>회원수정</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3><?= $member_sect_str ?> 회원정보 수정</h3>
					</div>

					<form name="frm" action="member_modify_action.php" target="_fra_admin" method="post" enctype="multipart/form-data">
						<input type="hidden" name="idx" id="member_idx" value="<?= $idx ?>" />
						<input type="hidden" name="total_param" value="<?= $total_param ?>" />
						<input type="hidden" name="id_ok" id="id_ok" value="Y" />
						<input type="hidden" name="nick_ok" id="nick_ok" value="Y" />
						<input type="hidden" name="email_ok" id="email_ok" value="Y" />

						<div class="write">

							<p class="tit">기본정보</p>
							<table>
								<caption>가맹점 수정</caption>
								<colgroup>
									<col style="width:10%">
									<col style="width:40%">
									<col style="width:10%">
									<col style="width:40%">
								</colgroup>
								<tr>
									<th scope="row"> 아이디</th>
									<td colspan="3">
										<input type="text" style="width:30%;" name="member_id" id="member_id" value="<?= $row['user_id'] ?>" required="yes" message="아이디" readonly />
										<div id="check_id" style="paddig-top:10px;"></div>
									</td>
								</tr>
								<tr>
									<th scope="row">비밀번호</th>
									<td>
										<input type="password" maxlength="16" name="member_password" message="비밀번호" style="width:200px; ime-mode:disabled"> <span style="display:inline-block; padding-top:4px;">(비밀번호 수정시에만 입력. 6-12자 사이) </span>
									</td>
									<th scope="row">비밀번호 확인</th>
									<td>
										<input type="password" maxlength="16" name="member_password2" message="비밀번호 확인" style="width:200px; ime-mode:disabled"> <span style="display:inline-block; padding-top:4px;">(비밀번호 수정시에만 입력. 6-12자 사이) </span>
									</td>
								</tr>
								<tr>
									<th scope="row">이름</th>
									<td>
										<input type="text" style="width:20%;" name="member_name" id="member_name" required="yes" message="이름" value="<?= $row['user_name'] ?>" readonly>
									</td>
									<th scope="row">사업자</th>
                                    <td>
                                        <div  class="flex-just-start">
                                            <input type="checkbox" name="company_yn" <?=$row['company_yn']?'checked':'' ?>><label>사업자회원</label>
                                            <label for="company_name">사업자명 : </label><input type="text" style="width:220px;display: <?=$row['company_yn']?'flex':'none'?>" name="company_name" id="company_name" value="<?= $row['company_name'] ?>" placeholder="사업자명" />
                                        </div>
                                    </td>
								</tr>
								<tr>
									<th scope="row"> 휴대전화<br />(인증번호)</th>
									<td colspan="3">
										<!--<input type="text" style="width:20%;" name="cell1" required="yes"  size="3" maxlength="3" message="휴대전화1" is_num="yes" value="">-<input type="text" style="width:20%;" name="cell2" required="yes" size="4" maxlength="4" message="휴대전화2" is_num="yes" value="">-<input type="text" style="width:20%;" name="cell3" required="yes" size="4" maxlength="4" message="휴대전화3" is_num="yes" value="">-->
										<input type="text" style="width:30%;" name="cell" id="join_member_cell" onblur="set_cell_num('join_member_cell');" required="yes" message="연락처" value="<?= $row['cell'] ?>" readonly>
									</td>
								</tr>
								<tr class="address">
									<th scope="col">주소</th>
									<td scope="col" colspan="3">
										<p>
											<input type="text" name="zip_code1" id="zip_code1" value="<?= $row[post] ?>" readonly message="우편번호" is_num="yes">
											<a href="javascript:execDaumPostcode('zip_code1', 'member_address', 'member_address2');" class="btn_green">우편번호검색</a>
										</p>
										<!-- 우편번호 레이어 시작 -->
										<div id="post_wrap_zip_code1" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
											<div><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1;width:30px;" onclick="foldDaumPostcode('zip_code1')" alt="접기 버튼"></div>
										</div>
										<!-- 우편번호 레이어 종료 -->
										<p>
											<input type="text" name="member_address" id="member_address" value="<?= $row[addr1] ?>" style="width:50%;" message="기본주소">
											<span class="info">기본주소</span>
										</p>
										<p>
											<input type="text" name="member_address2" id="member_address2" value="<?= $row[addr2] ?>" style="width:50%;" message="상세주소">
											<span class="info">상세주소</span>
										</p>
									</td>
								</tr>
								<tr>
									<th scope="row">이메일</th>
									<td>
										<input type="text" style="width:60%;" name="member_email" id="member_email" required="yes" message="이메일" is_email="yes" value="<?= $row['email'] ?>"><!--&nbsp;<a href="javascript:ch_email();" class="btn_green">중복확인</a>
								<div id="check_email" style="paddig-top:10px;"></div>-->
									</td>
									<th scope="row">가맹점 ID</th>
									<td>
										<input type="text" name="partner_id" id="partner_id" message="가맹점 ID" style="width:80%; ime-mode:disabled" value="<?= $row['partner_id'] ?>">
									</td>
								</tr>
							</table>

							<p class="tit">추가정보</p>
							<table>
								<caption>게시글 등록</caption>
								<colgroup>
									<col style="width:15%;">
									<col style="width:35%;">
									<col style="width:15%;">
									<col style="width:35%;">
								</colgroup>
								<tr>
									<th scope="row"> 승인여부</th>
									<td>
										<input type="radio" name="master_ok" value="Y" <?= $row[master_ok] == "Y" ? "checked" : "" ?> message="승인여부" id="master_ok_1"> 승인
										<input type="radio" name="master_ok" value="N" <?= $row[master_ok] == "N" ? "checked" : "" ?> message="승인여부" id="master_ok_2"> 미승인
									</td>
									<th scope="row"> 회원구분</th>
									<td>
										<input type="radio" name="member_gubun" value="1" <?= $row[member_gubun] == "1" ? "checked" : "" ?> message="회원구분" id="member_gubun_1"> 일반회원
										<input type="radio" name="member_gubun" value="2" <?= $row[member_gubun] == "2" ? "checked" : "" ?> message="회원구분" id="member_gubun_2"> 광고회원
										<input type="radio" name="member_gubun" value="3" <?= $row[member_gubun] == "3" ? "checked" : "" ?> message="회원구분" id="member_gubun_3"> 휴면회원

								</tr>

								<tr>
									<th scope="row"> 단가설정</th>
									<td>
										SMS : <input type="text" id="mb_short_fee" name="mb_short_fee" required="yes" message="sms 단가" is_num="no" size="5" value="<?= $row['mb_short_fee'] ?>">&nbsp;
										<br>LMS : <input type="text" id="mb_long_fee" name="mb_long_fee" required="yes" message="lms 단가" is_num="no" size="5" value="<?= $row['mb_long_fee'] ?>">&nbsp;
										<br>MMS : <input type="text" id="mb_img_fee" name="mb_img_fee" required="yes" message="mms 단가" is_num="no" size="5" value="<?= $row['mb_img_fee'] ?>">&nbsp;
                                        <br>알림톡 : <input type="text" id="mb_kko_fee" name="mb_kko_fee" required="yes" message="알림톡 단가" is_num="no" size="5" value="<?= $row['mb_kko_fee'] ?>">&nbsp;
									</td>
									<th scope="row">통신가입 증명원</th>
									<td>
										<?
										$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='member_info_sendinfo' and board_code='commu_certi' and board_idx='" . $row['sendinfo_idx'] . "' order by idx asc";
										$query_file = mysqli_query($gconnet, $sql_file);
										$cnt_file = mysqli_num_rows($query_file);

										if ($cnt_file < 1) {
											$cnt_file = 1;
										}

										for ($i_file = 0; $i_file < $cnt_file; $i_file++) {
											$row_file = mysqli_fetch_array($query_file);
											$k_file = $i_file + 1;
										?>
											<input type="hidden" name="file_idx_<?= $i_file ?>" value="<?= $row_file['idx'] ?>" />
											<input type="hidden" name="file_old_name_<?= $i_file ?>" value="<?= $row_file['file_chg'] ?>" />
											<input type="hidden" name="file_old_org_<?= $i_file ?>" value="<?= $row_file['file_org'] ?>" />

											<div <? if ($i_file > 0) { ?>style="margin-top:10px;" <? } ?>>
												<input type="file" style="width:50%;" message="첨부파일" name="file_<?= $i_file ?>" id="file_<?= $i_file ?>">
												<? if ($row_file['file_chg']) { ?>
													기존파일 : <a href="/pro_inc/download_file.php?nm=<?= $row_file['file_chg'] ?>&on=<?= $row_file['file_org'] ?>&dir=certi"><?= $row_file['file_org'] ?></a>
													(기존파일 삭제 : <input type="checkbox" name="del_org_<?= $i_file ?>" value="Y">)
												<? } else { ?>
													<input type="hidden" name="del_org_<?= $i_file ?>" value="">
												<? } ?>
											</div>
										<? } ?>
									</td>
								</tr>
								<!--<tr>
							<th scope="row"> 건수설정</th>
							<td>
								SMS : <input type="text" id="mb_short_cnt" name="mb_short_cnt" required="yes" message="sms 건수" is_num="yes" size="5" value="10">&nbsp;
								<br>LMS : <input type="text" id="mb_long_cnt" name="mb_long_cnt" required="yes" message="lms 건수" is_num="yes" size="5" value="20">&nbsp;
								<br>MMS : <input type="text" id="mb_img_cnt" name="mb_img_cnt" required="yes" message="mms 건수" is_num="yes" size="5" value="30">&nbsp;
							</td>
						</tr>-->
								<tr>
									<th scope="row"> 모듈설정</th>
									<td colspan="3">
										<div>
											SMS :
											<select name="sms_module_type">
												<option value="LG" <?= $row['sms_module_type'] == "LG" ? "selected" : "" ?>>LGHV</option>
												<option value="JUD1" <?= $row['sms_module_type'] == "JUD1" ? "selected" : "" ?>>JUD1</option>
												<option value="JUD2" <?= $row['sms_module_type'] == "JUD2" ? "selected" : "" ?>>JUD2</option>
											</select>
										</div>
										<div>
											LMS :
											<select name="lms_module_type">
												<option value="LG" <?= $row['lms_module_type'] == "JUD1" ? "selected" : "" ?>>LGHV</option>
												<option value="JUD1" <?= $row['lms_module_type'] == "JUD1" ? "selected" : "" ?>>JUD1</option>
												<option value="JUD2" <?= $row['lms_module_type'] == "JUD2" ? "selected" : "" ?>>JUD2</option>
											</select>
										</div>
										<div>
											MMS :
											<select name="mms_module_type">
												<option value="LG" <?= $row['mms_module_type'] == "JUD1" ? "selected" : "" ?>>LGHV</option>
												<option value="JUD1" <?= $row['mms_module_type'] == "JUD1" ? "selected" : "" ?>>JUD1</option>
												<option value="JUD2" <?= $row['mms_module_type'] == "JUD2" ? "selected" : "" ?>>JUD2</option>
											</select>
										</div>

									</td>
								</tr>
								<tr>
									<th scope="row"> 발신정보</th>
									<td colspan="3">
										<div style="margin-bottom:10px;text-align:right;padding-right:10px;">
											<a href="javascript:addForm_2();" class="btn_green">추가</a>
											<a href="javascript:delForm_2();" class="btn_red">삭제</a>
										</div>
										<?
										$call_num_arr = json_decode($row['call_num'], true);
										$call_memo_arr = json_decode($row['call_memo'], true);
										$use_yn_arr = json_decode($row['use_yn'], true);
										$auth_method_arr = json_decode($row['auth_method'], true);

										$call_num_cnt = sizeof($call_num_arr);
										if ($call_num_cnt < 1) {
											$call_num_cnt = 1;
										}

										for ($i_num = 0; $i_num < $call_num_cnt; $i_num++) {
										?><div id="callNumberList<?=$i_num?>">
                                                <span class="marr5 mnw50 dib">발신번호 : </span> <input type="text" placeholder="" class="call_num" name="call_num[]" maxlength="13" value="<?= $call_num_arr[$i_num] ?>" id="call_num_<?= $i_num ?>" onchange="check_cell_num('<?= $i_num ?>');">
                                                <span class="marr5 marl20">메모 : </span> <input type="text" placeholder="" name="call_memo[]" size="30" value="<?= $call_memo_arr[$i_num] ?>">
                                                <span class="marr5 marl20">상태 : </span> <select name="use_yn[]">
                                                    <option value="">선택하세요</option>
                                                    <option value="Y" <?= $use_yn_arr[$i_num] == "Y" ? "selected" : "" ?>>사용가능</option>
                                                    <option value="N" <?= $use_yn_arr[$i_num] == "N" ? "selected" : "" ?>>사용불가</option>
                                                </select>
                                                <span class="marr5 marl20">인증방법 : </span> <select name="auth_method[]">
                                                    <option value="">선택하세요</option>
                                                    <option value="kcp" <?= $auth_method_arr[$i_num] == "kcp" ? "selected" : "" ?>>kcp</option>
                                                    <option value="admin" <?= $auth_method_arr[$i_num] == "admin" ? "selected" : "" ?>>admin</option>
                                                </select><button type="button" class="btn_red" onclick="document.getElementById('callNumberList<?=$i_num?>').remove()">삭제</button>
                                            </div>
										<? } ?>

										<div id="addedFormDiv_2"></div>
									</td>
								</tr>
							</table>

					</form>

					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">정보수정</button>
					</div>

				</div>
			</div>
		</div>
		<!-- content 종료 -->

		<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
		<script type="text/javascript">
			function foldDaumPostcode(zip) {
				var element_wrap = document.getElementById('post_wrap_' + zip + '');
				// iframe을 넣은 element를 안보이게 한다.
				element_wrap.style.display = 'none';
			}

			function execDaumPostcode(zip, ad1, ad2) {
				var element_wrap = document.getElementById('post_wrap_' + zip + '');
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
						if (data.addressType === 'R') {
							//법정동명이 있을 경우 추가한다.
							if (data.bname !== '') {
								extraAddr += data.bname;
							}
							// 건물명이 있을 경우 추가한다.
							if (data.buildingName !== '') {
								extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
							}
							// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
							fullAddr += (extraAddr !== '' ? ' (' + extraAddr + ')' : '');
						}

						document.getElementById('' + zip + '').value = data.zonecode;
						//document.getElementById('zip_code2').value = data.postcode2;
						document.getElementById('' + ad1 + '').value = fullAddr;
						document.getElementById('' + ad2 + '').focus();

						// iframe을 넣은 element를 안보이게 한다.
						// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
						element_wrap.style.display = 'none';

						// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
						document.body.scrollTop = currentScroll;
					},
					// 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
					onresize: function(size) {
						element_wrap.style.height = size.height + 'px';
					},
					width: '100%',
					height: '100%'
				}).embed(element_wrap);

				// iframe을 넣은 element를 보이게 한다.
				element_wrap.style.display = 'block';
			}
		</script>

		<script>
			function set_cell_num(target) {
				var cell_inp = $("#" + target + "").val();
				//alert(cell_inp);
				var cell_ninp = cell_inp.replace(/\-/g, "");
				//alert(cell_ninp);
				$("#" + target + "").val(cell_ninp);
			}

			var count_2 = <?= $call_num_cnt ?>;

			function addForm_2() {
				var addedFormDiv = document.getElementById("addedFormDiv_2");
				var str = "";

				str += '<span class="marr5 mnw50 dib">발신번호 : </span> <input type="text" placeholder="" class="call_num"  name="call_num[]" maxlength="13" id="call_num_' + count_2 + '" onchange=check_cell_num("' + count_2 + '");>&nbsp;<span class="marr5 marl20">메모 : </span> <input type="text" placeholder="" name="call_memo[]" size="30">&nbsp;<span class="marr5 marl20">상태 : </span> <select name="use_yn[]"><option value="">선택하세요</option><option value="Y">사용가능</option><option value="N">사용불가</option></select><span class="marr5 marl20">인증방법 : </span><select name="auth_method[]"><option value="">선택하세요</option><option value="kcp">kcp</option><option value="admin">admin</option></select><br/>';

				var addedDiv = document.createElement("div"); // 폼 생성 
				addedDiv.id = "added_2_" + count_2; // 폼 Div에 ID 부 여 (삭제를 위해)
				addedDiv.innerHTML = str; // 폼 Div안에 HTML삽입
				addedFormDiv.appendChild(addedDiv); // 삽입할 DIV에 생성한 폼 삽입
				count_2++;
			}

			function delForm_2() {
				var addedFormDiv = document.getElementById("addedFormDiv_2");
				if (count_2 > 1) { // 현재 폼이 두개 이상이면
					var addedDiv = document.getElementById("added_2_" + (--count_2));
					addedFormDiv.removeChild(addedDiv); // 폼 삭제 
				} else { // 마 지막 폼만 남아있다면
					//  document.baseForm.reset(); // 폼 내용 삭제
				}
			}

			function check_cell_num(num) {
				var cell_receive_dan = $("#call_num_" + num + "").val();
				if (cell_receive_dan == "") {
					alert("추가할 번호를 입력해 주세요.");
					return;
				} else {
					// 숫자만 포함되도록 정규표현식을 사용하여 검사합니다.
					var numericPattern = /^[0-9]+$/;
					if (!numericPattern.test(cell_receive_dan)) {
						alert("숫자만 입력해 주세요.");
						$("#call_num_" + num + "").val("");
						return;
					}

					// 자리수 확인
					if (cell_receive_dan.length > 11) {
						//alert("변작번호로 판별되어 관련 법령에 따라 문자 발송이 차단됩니다.");
						alert("자리수가 맞지 않습니다.");
						$("#call_num_" + num + "").val("");
						return;
					}
				}
			}
		</script>

		<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom_admin_tail.php"; ?>
</body>

</html>