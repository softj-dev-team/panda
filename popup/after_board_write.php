<? include "../inc/header.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT']."/pro_inc/check_login_popup.php"; ?>
<?		
$mode = trim(sqlfilter($_REQUEST['mode']));
$mode_sub = trim(sqlfilter($_REQUEST['mode_sub']));
$cate_type = trim(sqlfilter($_REQUEST['cate_type']));
$pro_cate1 = trim(sqlfilter($_REQUEST['pro_cate1']));
$pro_cate2 = trim(sqlfilter($_REQUEST['pro_cate2']));
$cart_idx = trim(sqlfilter($_REQUEST['cart_idx']));
$product_idx = trim(sqlfilter($_REQUEST['product_idx']));
?>	
		<!-- 후기작성 시작 -->
				<div class="review_write" style="position:fixed;background-color:#fff;width:800px;">
					<div class="popup_title" style="position:relative; height:60px; background-color:#d02139; padding-left:28px; font-size:0;">
						<p style="display:inline-block; vertical-align:middle; font-size:18px; color:#fff; line-height:60px;">후기작성</p>
						<span class="btn_close" style="position:absolute; top:0; right:0; width:60px; height:60px; cursor:pointer; background-image:url(../images/common/pop_close.png); background-repeat:no-repeat; background-position:center center; background-size:100% 100%;" onClick="self.close();"></span>
					</div>
					<div class="popup_con info_change">
						<table class="common_table">
						<form name="after_board_frm" id="after_board_frm" action="/goods/pboard_write_action.php" target="_self" method="post"  enctype="multipart/form-data">
							<input type="hidden" name="mode" value="<?=$mode?>"/>
							<input type="hidden" name="mode_sub" value="<?=$mode_sub?>"/>
							<input type="hidden" name="cate_type" value="<?=$cate_type?>"/>
							<input type="hidden" name="pro_cate1" value="<?=$pro_cate1?>"/>
							<input type="hidden" name="pro_cate2" value="<?=$pro_cate2?>"/>
							<input type="hidden" name="cart_idx" value="<?=$cart_idx?>"/>
							<input type="hidden" name="product_idx" value="<?=$product_idx?>"/>
							<caption>후기작성</caption>
							<colgroup>
								<col style="width:20%">
								<col style="*">
							</colgroup>
							<tbody>
								<tr>
									<th scope="col">제목</th>
									<td scope="col"><input type="text" required="yes" message="제목" name="subject"></td>
								</tr>
								<tr>
									<th scope="col">내용</th>
									<td scope="col"  style="width:720px;"><textarea id="after_editor" name="content" required="no" message="내 용"></textarea></td>
								</tr>
							</tbody>
						</form>
						</table>
						<div class="btn_area mt20">
							<button class="info_ok_btn" type="button" onclick="go_after_submit();">확인</button>
							<button class="info_can_btn" type="button" onclick="self.close();">취소</button>
						</div>
					</div>
				</div>
					<!-- 후기작성 종료 -->

	<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">

var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "after_editor",
		sSkinURI: "/smarteditor2/SmartEditor2Skin.html",	
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
				//alert("아싸!");	
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});

	function go_after_submit() {
		var check = chkFrm('after_board_frm');
		if(check) {
			oEditors.getById["after_editor"].exec("UPDATE_CONTENTS_FIELD", []);
			after_board_frm.submit();
		} else {
			false;
		}
	}

</script>