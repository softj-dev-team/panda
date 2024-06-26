<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/db_conn.php"; // 메인공지 레이어 설정 파일?>

<script type="text/javascript">
<!--
	function notice_getCookie(name){ // 오늘 하루 안열기 확인 
		    var nameOfCookie = name + "=";
			var x = 0;
			 while ( x <= document.cookie.length ){
                var y = (x+nameOfCookie.length);
                if ( document.cookie.substring( x, y ) == nameOfCookie ) {
                        if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
                                endOfCookie = document.cookie.length;
                        return unescape( document.cookie.substring( y, endOfCookie ) );
                }
                x = document.cookie.indexOf( " ", x ) + 1;
                if ( x == 0 )
                        break;
			}
			return "";
	}

	function LeyerPopupClose(idx){ // 레이어 그냥 닫기  
		var oPopup = document.getElementById("pop_main_"+idx+"");  
		oPopup.style.display = "none";  
	}  

	function notice_setCookie( name, value, expiredays ){ // 오늘 하루 안열기
        var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }

	function notice_closeWin(idx){ // 오늘하루 안열기 설정하고 닫기  
		//alert(sDivName);
        notice_setCookie("pop_main_"+idx+"", "done" , 1); // 1=하룻동안 공지창 열지 않음
		var oPopup = document.getElementById("pop_main_"+idx+"");  
		oPopup.style.display = "none";  
	}
	
	function main_notice_ok(idx){
		//alert(sDivName);
		//alert(document.getElementById("pop_main_"+idx+""));
		if (notice_getCookie("pop_main_"+idx+"") == "done"){
			var oPopup = document.getElementById("pop_main_"+idx+"");  
			oPopup.style.display = "none";  
		}
	}

	//alert(notice_getCookie("Notice_<?=$row[idx]?>"));
//-->
</script>

<?
$today = @date("Y-m-d", time());
if($inc_partner_idx != "1"){
	$query = "select * from popup_div where is_use='Y' and substring(startdt,1,10) <= '$today' and substring(enddt,1,10) >= '$today' and admin_idx='".$inc_partner_idx."' order by idx desc limit 0,6";
	$result = mysqli_query($gconnet,$query);
	
	if(mysqli_num_rows($result) == 0){
		$query = "select * from popup_div where is_use='Y' and substring(startdt,1,10) <= '$today' and substring(enddt,1,10) >= '$today' and admin_idx='1' order by idx desc limit 0,6";
		$result = mysqli_query($gconnet,$query);	
	}
} else {
	$query = "select * from popup_div where is_use='Y' and substring(startdt,1,10) <= '$today' and substring(enddt,1,10) >= '$today' and admin_idx='1' order by idx desc limit 0,6";
	$result = mysqli_query($gconnet,$query);	
}

$position_y_tot = 0;
$position_x_tot = "";
for ($i=0; $i<mysqli_num_rows($result); $i++){
	$k = $i+1;
	$row = mysqli_fetch_array($result);

	$pop_width = $row[width]+20;
	//$pop_width = $row[width];
	$pop_height = $row[height];
	$pop_height2 = $pop_height+30;
	
	if($i > 0){
		$query_prev = "select file_c from popup_info where view_ok='Y' and substring(main_memo_eng,1,10) <= '$today' and substring(pro_title,1,10) >= '$today' and idx > '".$row['idx']."' order by idx asc limit 0,1";
		//echo $query_prev."<br>";
		$result_prev = mysqli_query($gconnet,$query_prev);
		$row_prev = mysqli_fetch_array($result_prev);
		//echo $row_prev[file_c]."<br>";
		$source_photo = $_P_DIR_FILE."popup/img_thumb/".$row_prev[file_c];
		$size = getimagesize($source_photo);
		$p_width = $size[0];
		$p_height = $size[1];

		//echo $p_width."<br>";
		
		$position_y_tot = $position_y+($p_width+10);
	} 

	if($i == 0){
		$position_x_tot1 = $p_height;
	} elseif($i == 1){
		$position_x_tot2 = $p_height;
	} elseif($i == 2){
		$position_x_tot3 = $p_height;
	}

	//echo "k mod = ".($i%3);

	if($i == 0 || $i == 3){
		$position_y_tot = 300;
	} 
	
	//echo "position_x_tot = ".$position_x_tot;
	if($i == 2){
		$position_x_max = max($position_x_tot1,$position_x_tot2,$position_x_tot3);
		//echo "position_x_max = ".$position_x_max;
	}

	if($i==0 || $i==1 || $i==2){
		$position_x = "120";
		$position_y = $position_y_tot;
	} elseif($i==3 || $i==4 || $i==5){ 
		$position_x = ($position_x_max+150);
		$position_y = $position_y_tot;
	} 

	//$position_x = $row['x'];
	//$position_y = $row['y'];

	$z_index = $k*10000; 

	$position_x = $row['x'];
	$position_y = $row['y'];

	$product_detail = stripslashes($row[content]);
	$product_detail = preg_replace("/ style=(\"|\')?([^\"\']+)(\"|\')?/","",$product_detail);
	$product_detail = preg_replace("/ style=([^\"\']+) /"," ",$product_detail); 
	$product_detail = str_replace("<img","<img style='max-width:90%;'",$product_detail);
?>
	
	<?if(is_mobile()){?>
		<div class="pop" id="pop_main_<?=$row[idx]?>" style="display:block;top:10px;left:10px;position:absolute;z-index:<?=$z_index?>;width:<?=$pop_width?>px;background:#ffffff;text-align:center;box-shadow:0 0 8px rgba(0,0,0,0.16);">
	<?}else{?>
		<div class="pop" id="pop_main_<?=$row[idx]?>" style="display:block;top:<?=$position_y?>px;left:<?=$position_x?>px;position:absolute;z-index:<?=$z_index?>;width:<?=$pop_width?>px;background:#ffffff;text-align:center;box-shadow:0 0 8px rgba(0,0,0,0.16);">
	<?}?>
		<!--<div style="height:20px;background:#ffffff;">&nbsp;</div>-->
		<div class="pop_con" style="background:#ffffff;padding: 20px 10px;border-top:1px solid #ccc;border-bottom:1px solid #838383;border-left:1px solid #ccc;border-right:1px solid #ccc;">
		<?=stripslashes($product_detail)?>
		
		<?if($row[file_c]){?>
			<?if($row[link_url]){?>
				<a href="<?=$row[link_url]?>" target="<?=$row[link_target]?>"><img src="<?=$_P_DIR_WEB_FILE?>popup/img_thumb/<?=$row[file_c]?>" style="border:0;max-width:90%;"></a>
			<?}else{?>
				<img src="<?=$_P_DIR_WEB_FILE?>popup/img_thumb/<?=$row[file_c]?>" style="border:0;max-width:90%;">
			<?}?>
		<?}?>
		</div>
		<div class="pop_bottom" style="background:#ffffff;border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;height:50px;padding-top:10px;">
			<span>
				<input type="checkbox" id="haru" name="" style="margin-top:-3px;vertical-align: middle;width:16px;height:16px;" value="y" onclick="notice_closeWin('<?=$row[idx]?>');"/> <label for="haru">오늘 하루 이 창을 열지 않음</label>
			</span>
			<span class="pop_close" onclick="LeyerPopupClose('<?=$row[idx]?>')" style="cursor:pointer;"><img src="../popup/img/btn_close2.gif" align="absmiddle" border="0"></span>
		</div>
		<!--<div style="height:10px;background:#ffffff;">&nbsp;</div>-->
	</div>

	<script type="text/javascript">
	<!--
		main_notice_ok('<?=$row[idx]?>');
	//-->
	</script>
<?}?>

