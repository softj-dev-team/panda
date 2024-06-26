<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$mail_key = trim(sqlfilter($_REQUEST['mail_key']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
//$smenu = "10";
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$mail_gubun = sqlfilter($_REQUEST['mail_gubun']);
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 등급별 검색
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 정회원,우수회원,셀러회원 등 검색
$v_sect = sqlfilter($_REQUEST['v_sect']); // 회원, 제휴회원 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&v_sect='.$v_sect.'&s_gender='.$s_gender.'&mail_gubun='.$mail_gubun.'&pageNo='.$pageNo;

//$sql = "SELECT * FROM send_msg where 1 and mail_method = '".$_SESSION['admin_homest_section']."' and mail_gubun = '".$mail_gubun."' and mail_key= '".$mail_key."' ";
$sql = "SELECT * FROM send_msg where 1 and mail_gubun = '".$mail_gubun."' and mail_key= '".$mail_key."' ";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 발송된 메일이 없습니다.');
	location.href =  "mail_send_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($mail_gubun == "mail"){
	$mail_gubun_str = "메일";
} elseif($mail_gubun == "sms"){
	$mail_gubun_str = "문자";
} elseif($mail_gubun == "memo"){
	$mail_gubun_str = "쪽지";
} 
?>

<!-- content -->

<SCRIPT LANGUAGE="JavaScript">
<!--
		
	function go_delete(no){
		
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "mailsend_delete_action.php?mail_key="+no+"&<?=$total_param?>";
			}
	}

	function go_list(){
		location.href = "mail_send_list.php?<?=$total_param?>";
	}
	
//-->
</SCRIPT>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/member_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>발송한 전체<?=$mail_gubun_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>발송한 전체<?=$mail_gubun_str?> 상세보기</h3>
				</div>
				<div class="write">
					<p class="tit">발송한 전체<?=$mail_gubun_str?> 상세보기</p>
				<table>
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
				<?if($mail_gubun == "mail"){?>	
					<tr>
						<th >발송메일제목</th>
						<td colspan="3"><?=$row[subject]?></td>
					</tr>
					<tr>
						<th >발송자 이름</th>
						<td ><?=$row[fromname]?></td>
						<th >발송자 메일주소</th>
						<td ><?=$row[fromemail]?></td>
					</tr>
					<tr>
						<th >메일내용</th>
						<td width="*" colspan="3" style="padding-top:10px;padding-bottom:10px;"><?=stripslashes($row[content])?></td>
					</tr>
				<?} elseif($mail_gubun == "sms"){?>
					<tr>
						<th >발송번호</th>
						<td colspan="3"><?=$row[fromemail]?></td>
					</tr>
					<tr>
						<th >발송내용</th>
						<td width="*" colspan="3" style="padding-top:10px;padding-bottom:10px;"><?=nl2br(stripslashes($row[content]))?></td>
					</tr>
				<?} elseif($mail_gubun == "memo"){?>
					<tr>
						<th >발송내용</th>
						<td width="*" colspan="3" style="padding-top:10px;padding-bottom:10px;"><?=nl2br(stripslashes($row[content]))?></td>
					</tr>
				<?}?>
					<tr>
						<th >발송일시</th>
						<td width="*" colspan="3"><?=$row[wdate]?></td>
					</tr>

			</table>
			<?
				$pageNo_sub = trim(sqlfilter($_REQUEST['pageNo_sub']));

				################## 파라미터 조합 #####################
				$total_param_sub = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.$v_sect.'&mode='.$mode.'&send_sect='.$send_sect.'&pageNo='.$pageNo.'&mail_key='.$mail_key;

				if(!$pageNo_sub){
					$pageNo_sub = 1;
				}

				$where_sub .= " and b.mail_key = '".$mail_key."' and a.member_type != 'AD' ";

				$pageScale_sub = 20; // 페이지당 10 개씩 
				$start_sub = ($pageNo_sub-1)*$pageScale_sub;

				$StarRowNum_sub = (($pageNo_sub-1) * $pageScale_sub);
				$EndRowNum_sub = $pageScale_sub;
			
				$order_by_sub = " order by b.idx desc ";
				$query_sub = "select a.user_id,a.user_name,a.tel,a.user_level,a.gender,a.email,a.cell,a.member_type,a.gender,a.nation,b.mail_ok,(select readdate from member_memo_mail where 1 and mail_key=b.mail_key) as readdate from member_info a inner join send_msg_member b on a.idx=b.member_idx where 1=1 ".$where_sub.$order_by_sub." limit ".$StarRowNum_sub." , ".$EndRowNum_sub;

				//echo "<br><br>쿼리 = ".$query_sub."<br><Br>";

				$result_sub = mysqli_query($gconnet,$query_sub);

				$query_sub_cnt = "select b.idx from member_info a inner join send_msg_member b on a.idx=b.member_idx where 1=1 ".$where_sub;
				$result_sub_cnt = mysqli_query($gconnet,$query_sub_cnt);
				$num_sub = mysqli_num_rows($result_sub_cnt);

				//echo $num_sub;

				$iTotalSubCnt_sub = $num_sub;
				$totalpage_sub	= ($iTotalSubCnt_sub - 1)/$pageScale_sub  + 1;

			?>

			<div class="align_c margin_t20">
				<!-- 목록 -->
				<a href="javascript:go_list();" class="btn_blue">목록</a>
				<!-- 삭제 -->
				<a href="javascript:go_delete('<?=$mail_key?>');" class="btn_red">삭제</a>	
			</div>
		
			<h3 style="margin-top:10px;">총 <?=$num_sub?> 명의 회원에게 이 <?=$mail_gubun_str?>을 발송했습니다.</h3>

				<table class="t_list" style="margin-top:10px;">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<th width="25%">아이디</th>
						<th width="30%">성 명</th>
						<th width="25%">국 적</th>
					<?if($mail_gubun == "memo"){?>
						<th width="10%">수신시간</th>
					<?}else{?>
						<th width="10%">발송여부</th>
					<?}?>
					</tr>
				</thead>
				<tbody>
				<? if($num_sub==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>대상자가 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i_sub=0; $i_sub<mysqli_num_rows($result_sub); $i_sub++){
					$row_sub = mysqli_fetch_array($result_sub);

					$listnum_sub	= $iTotalSubCnt_sub - (( $pageNo_sub - 1 ) * $pageScale_sub ) - $i_sub;

					if($row_sub[mail_ok] == "Y"){
						$ok_str = "<font style='color:blue;'>발송성공</font>";
					} else {
						$ok_str = "<font style='color:orange;'>발송실패</font>";
					}

					if($row_sub[gender] == "M"){
						$gender = "남성";
					} elseif($row_sub[gender] == "F"){
						$gender = "여성";
					} else {
						$gender = "";
					}

				$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row_sub[user_level]."' ";   
				$member_level_query = mysqli_query($gconnet,$member_level_sql);
				$member_level_row = mysqli_fetch_array($member_level_query);
				$user_level_str = $member_level_row['level_name'];

				?>
					<tr>
						<td><?=$listnum_sub?></td>
						<td><?=$row_sub[user_id]?></td>
						<td><?=$row_sub[user_name]?></td>
						<td><?=$row_sub[nation]?></td>
					<?if($mail_gubun == "memo"){?>
						<td><?=$row_sub[readdate]?></td>
					<?}else{?>
						<td><?=$ok_str?></td>
					<?}?>
					</tr>
				<?}?>	
			
			</tbody>
			</table>

			<!-- //Goods List -->
			<!-- paginate -->
			<div class="pagination">
			<?
				include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_sub.php";
			?>
			</div>
			<!-- //paginate -->
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>