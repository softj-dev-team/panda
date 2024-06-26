<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; ?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$mode = trim(sqlfilter($_REQUEST['mode']));
$query = "select * from popup_div where idx='".$idx."' ";
$result_popup = mysqli_query($gconnet,$query);
$row_popup = mysqli_fetch_array($result_popup);

//echo $query;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>공지사항</title>
<link rel="stylesheet" type="text/css" href="pop_up.css"/>
</head>

<script language="JavaScript">
<!--

window.resizeTo (<?=$row_popup[width]?>+25,<?=$row_popup[height]?>+20);

function notice_setCookie( name, value, expiredays )
    {
        var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        }


function notice_closeWin() 
{ 
        notice_setCookie( "Notice_<?=$idx?>", "done" , 1); // 1=하룻동안 공지창 열지 않음
        self.close(); 
}


//-->
</script>


<script language="JavaScript">
<!--

function go_opener_url(recv) {
	opener.location.href =  recv;
	self.close();
}

// -->
</script>

</head>

<body topmargin="0" leftmargin="0" style="overflow-x:hidden">
	<table border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
        <td align="center"><?=stripslashes($row_popup[content])?></td>
      </tr>
      <?//if($mode != "prev"){?>
      <tr>
        <td bgcolor="f3f3f3" style="text-align:right;padding-right:10px;">오늘은 이창을 띄우지 않음  <input type="checkbox" name="checkbox" id="checkbox" onclick="javascript:notice_closeWin();"> &nbsp;&nbsp; <A HREF="javascript:self.close();"><img src="img/btn_close2.gif" align="absmiddle" border="0"></A></td>
      </tr>
	  <?//}?>
    </table>
</body>

</html>
