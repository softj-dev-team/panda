<?php
/***************************************************************************
 * 여러번 호출시 에러 발생 금지
 **************************************************************************/
if($_user_function_php_excuted) return;
$_user_function_php_excuted = true;

########## 일반 파일 업로드 
function uploadFile($HTTP_POST_FILES, $el_name, $el, $_P_DIR_FILE, $filename_org=""){
	//echo "el_name = ".$el_name."<br>";
	//echo "function file name = ".$_FILES[$el_name]['name']."<br>";
	//exit;

	if ($_FILES[$el_name]['name']){
		$file_name = $_FILES[$el_name][name];
		$full_filename = explode(".", "$file_name");
		$extension = $full_filename[sizeof($full_filename)-1];
		########## 등록한 파일이 업로드가 허용되지 않는 확장자를 갖는 파일인지를 검사한다. ##########
		if(!strcmp($extension,"html") || !strcmp($extension,"htm") || !strcmp($extension,"cgi") ||
			!strcmp($extension,"php") || !strcmp($extension,"php3") || !strcmp($extension,"pl") ||
			!strcmp($extension,"php4") || !strcmp($extension, "inc")|| !strcmp($extension, "php5")){
			error_frame("HTML 파일은 보안상 업로드하실 수 없습니다. ");
			exit;
		}

		$imsi_filename = str_replace($file_name,randomChar(5),$file_name).".".$extension; // 한글명일때를 대비하여 파일명을 교체한다.
		$file_name = time()."-".$imsi_filename;

		if(!is_dir($_P_DIR_FILE)){
		mkdir($_P_DIR_FILE, 0777); 
		chmod($_P_DIR_FILE, 0777);
		}

		$toName = $_P_DIR_FILE.$file_name;
		$fromName = $_FILES[$el_name]['tmp_name'];
		if(!move_uploaded_file($fromName,$toName)) {
		error_frame("UPLOAD_COPY_FAILURE");
		exit;
		}
		
		########## 신규파일 업로드시 기존 파일은 삭제... ##########
		if($file_name && $filename_org){
			unlink($_P_DIR_FILE.$filename_org);
		}
		return $file_name;
	}
	return "";
	
}

########## 이미지 업로드 + 섬네일 
function uploadFileThumb_1($HTTP_POST_FILES, $el_name, $el, $_P_DIR_FILE, $i_width="", $i_height="", $i_width2="", $i_height2="",$i_width3="", $i_height3="",$watermark_sect=""){
	
	if ($_FILES[$el_name]['size']>0){
		
		$file_name = $_FILES[$el_name][name];
		$full_filename = explode(".", "$file_name");
		$extension = $full_filename[sizeof($full_filename)-1];
		
		########## 등록한 파일이 업로드가 허용되지 않는 확장자를 갖는 파일인지를 검사한다. ##########
		if(!strcmp($extension,"html") || !strcmp($extension,"htm") || !strcmp($extension,"cgi") ||
			!strcmp($extension,"php") || !strcmp($extension,"php3") || !strcmp($extension,"pl") ||
			!strcmp($extension,"php4") || !strcmp($extension, "inc")|| !strcmp($extension, "php5")){
			error_frame("HTML 파일은 보안상 업로드하실 수 없습니다. ");
			exit;
		}

		$imsi_filename = str_replace($file_name,randomChar(5),$file_name).".".$extension; // 한글명일때를 대비하여 파일명을 교체한다.
		$file_name = time()."-".$imsi_filename;
		
		if(!is_dir($_P_DIR_FILE)){
		mkdir($_P_DIR_FILE, 0777); 
		chmod($_P_DIR_FILE, 0777);
		}

		if($i_width){
		$_P_DIR_FILE_thm1 = $_P_DIR_FILE."img_thumb"."/";
			if(!is_dir($_P_DIR_FILE_thm1)){
			mkdir($_P_DIR_FILE_thm1, 0777); 
			chmod($_P_DIR_FILE_thm1, 0777);
			}
		}

		if($i_width2){
		$_P_DIR_FILE_thm2 = $_P_DIR_FILE."img_thumb2"."/";
			if(!is_dir($_P_DIR_FILE_thm2)){
			mkdir($_P_DIR_FILE_thm2, 0777); 
			chmod($_P_DIR_FILE_thm2, 0777);
			}
		}

		if($i_width3){
		$_P_DIR_FILE_thm3 = $_P_DIR_FILE."img_thumb3"."/";
			if(!is_dir($_P_DIR_FILE_thm3)){
			mkdir($_P_DIR_FILE_thm3, 0777); 
			chmod($_P_DIR_FILE_thm3, 0777);
			}
		}

		$toName = $_P_DIR_FILE.$file_name;
		$fromName = $_FILES[$el_name]['tmp_name'];
		if(!move_uploaded_file($fromName,$toName)) {
		error("UPLOAD_COPY_FAILURE");
		exit;
	}

		##########  썸내일 만드는소스 ##########
		// 이미지타입 구분(gif, jpg, png 만 가능)
		$thumbS_width = $i_width; $thumbS_height = $i_height; // 스몰섬네일 크기
		$thumbS_width2 = $i_width2; $thumbS_height2 = $i_height2; // 스몰섬네일 크기
		$thumbS_width3 = $i_width3; $thumbS_height3 = $i_height3; // 스몰섬네일 크기
		$dest_file = $_P_DIR_FILE.$file_name;
		$upfile_path = $_P_DIR_FILE;

		if(!$i_height){
			$w = 1024; 
			$h = 900; 

			$w_rate = round(($w/$i_width),2); 
			$i_height = round(($h/$w_rate),0); 

		}
		
		if(img_type($dest_file)) {
			$srcimg = $file_name;
			$dstimg = $file_name;

			// 워터마크 이미지 생성시작 
			if($watermark_sect == "text"){ // 텍스트형 워터마크 
				
				$font_size = 18; // 글자 크기 
				$opacity = 70; // 투명도 높을수록 불투명 
				$font_path = $_SERVER["DOCUMENT_ROOT"]."/pro_inc/H2HDRM.TTF";  //폰트 패스 
				$string = "PoleStar";  // 찍을 워터마크 

				$image = $dest_file; // 업로드된, 워터마크가 없는 파일.
				$image_f_name = $file_name;
				$image_name = explode(".",$image_f_name); 
				
				$image_targ1 = explode("-",$image_name[0]);

				$image_targ = $_P_DIR_FILE.$image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];  // 워터마크가 찍혀 저장될 이미지 

				$image_org = $image; // 원본 이미지를 다른 이름으로 저장 
				
				//$image = imagecreatefromjpeg($image); // JPG 이미지를 읽고 

				if($image_name[1] == "gif" || $image_name[1] == "GIF")  $image = imagecreatefromgif($image);  
				if($image_name[1] == "jpg" || $image_name[1] == "jpeg" || $image_name[1] == "JPG" || $image_name[1] == "JPEG")  $image = imagecreatefromjpeg($image);  
				if($image_name[1] == "png" || $image_name[1] == "PNG")  $image = imagecreatefrompng($image);  
				
				$w = imagesx($image); 
				$h = imagesy($image);  

				$text_color = imagecolorallocate($image,255,255,255); // 텍스트 컬러 지정 

				// 적당히 워터마크가 붙을 위치를 지정 
				$text_pos_x = $font_size; 
				$text_pos_y = $h - $font_size; 

				imagettftext($image, $font_size, 0, $text_pos_x, $text_pos_y, $text_color, $font_path, $string);  // 읽은 이미지에 워터마크를 찍고 

				$image_org = imagecreatefromjpeg($image_org); // 원본 이미지를 다시한번 읽고 
  
				imagecopymerge($image,$image_org,0,0,0,0,$w,$h,$opacity); // 원본과 워터마크를 찍은 이미지를 적당한 투명도로 겹치기 
				imagejpeg($image, $image_targ, 90); // 이미지 저장. 해상도는 90 정도 

				//echo "<img src='$image'><br><br>";
				//echo "<img src='$image_targ'><br><br>";
  
				imagedestroy($image); 
				imagedestroy($image_org); 

			} elseif($watermark_sect == "imgw"){ // 이미지합성 워터마크

				if (isset($_GET['transparency'])) {
					if ($_GET['transparency'] >= 0 && $_GET['transparency'] <= 100) {
						$transparency = (int) $_GET['transparency'];
					}
				} else {
					$transparency = 40;
				}
				
				$source_photo = $dest_file;   // 업로드된, 워터마크가 없는 파일.
				
				$image_f_name = $file_name;
				$image_name = explode(".",$image_f_name); 
				$image_targ1 = explode("-",$image_name[0]);
				
				$image_targ = $_P_DIR_FILE.$image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];  // 워터마크가 찍혀 저장될 이미지 
				$image_targ_name = $image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];

				$filetype = substr($source_photo,strlen($source_photo)-4,4); 
				$filetype = strtolower($filetype);


				if($filetype == ".gif" || $filetype == ".GIF")  $photo = imagecreatefromgif($source_photo);  
				if($filetype == ".jpg" || $filetype == ".jpeg" || $filetype == ".JPG" || $filetype == ".JPEG")  $photo = imagecreatefromjpeg($source_photo);  
				if($filetype == ".png" || $filetype == ".PNG")  $photo = imagecreatefrompng($source_photo);  

				if (!$photo) echo "원본파일 없음."; 
				$watermark = imagecreatefrompng($_SERVER["DOCUMENT_ROOT"].'/pro_inc/watermark.png');
				$watermark_width = imagesx($watermark);
				$watermark_height = imagesy($watermark);

				//location of the watermark on the source image
				$size = getimagesize($source_photo);
				$dest_x = ($size[0] - $watermark_width) / 2;
				$dest_y = ($size[1] - $watermark_height) / 2;

				//make the image (merge source image with watermark)
				imagecopymerge($photo, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $transparency);
				imagejpeg($photo, $image_targ, 90); // 이미지 저장. 해상도는 90 정도 
				
				imagedestroy($photo);

			}
			//워터마크 이미지 생성 종료 

			// 이미지 썸내일 생성
			
				img_resize($srcimg, $dstimg, $upfile_path, $i_width,$i_height,$watermark_sect); // 크기에 맞춰 비율대로 사이즈를 줄인다
			
			if($i_width2){	
				
				img_resize_1($srcimg, $dstimg, $upfile_path, $i_width2,$i_height2,"img_thumb2",$watermark_sect);  // 크기에 맞춰 비율대로 사이즈를 줄인다 
			}

			if($i_width3){	
				
				img_resize_1($srcimg, $dstimg, $upfile_path, $i_width3,$i_height3,"img_thumb3",$watermark_sect);  // 크기에 맞춰 비율대로 사이즈를 줄인다 
			}
		} else {
			//echo "<script>alert('이미지가등록되지 않았습니다.')</script>";
			unlink($dest_file);
			return "";
		}
		return $file_name;
	}
	return "";
	
}

#### 이미지 체크 
function img_type( $srcimg ) {
	

	if(is_file($srcimg)) {
		if(getExt($srcimg) == "svg"){
			return true; 
		} else {
			$image_info = @getimagesize($srcimg);
			//echo "mime = ".$image_info['mime']."<br>";
			switch ($image_info['mime']) {
				case 'image/gif': return true; break;
				case 'image/jpeg': return true; break;
				case 'image/png': return true; break;
				//case 'image/bmp': return true; break;
				default : return false; break;
			}
		}
	} else {
		return false;
	}
}
 
##### 비율대비 섬네일 1 
function img_resize( $srcimg, $dstimg, $imgpath, $rewidth, $reheight,$watermark_sect ) {

	$src_info = getimagesize("$imgpath$srcimg");

	if($rewidth < $src_info[0] || $reheight < $src_info[1]) {
		if(($src_info[0] - $rewidth) > ($src_info[1] - $reheight)) {
			$reheight = round(($src_info[1]*$rewidth)/$src_info[0]);
		} else {
			$rewidth = round(($src_info[0]*$reheight)/$src_info[1]);
		}
	} else {
		if (!copy($imgpath.$srcimg, $imgpath."/img_thumb/".$dstimg)) {
			echo "failed to copy $imgpath$srcimg...\n";
		}
		return;
	}

	$dst = imageCreatetrueColor($rewidth, $reheight);

	if($src_info[2] == 1) {
		$src = ImageCreateFromGIF("$imgpath$srcimg");

		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagejpeg($dst,"$imgpath/img_thumb/$dstimg",100);
	} elseif($src_info[2] == 2) {
		$src = ImageCreateFromJPEG("$imgpath$srcimg");

		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagejpeg($dst,"$imgpath/img_thumb/$dstimg",100);
	} elseif($src_info[2] == 3) {
		$src = ImageCreateFromPNG("$imgpath$srcimg");

		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagepng($dst,"$imgpath/img_thumb/$dstimg",9);
	}

	// 워터마크 이미지 생성시작 
			if($watermark_sect == "text"){ // 텍스트형 워터마크 
				
				$font_size = 18; // 글자 크기 
				$opacity = 70; // 투명도 높을수록 불투명 
				$font_path = $_SERVER["DOCUMENT_ROOT"]."/pro_inc/H2HDRM.TTF";  //폰트 패스 
				$string = "PoleStar";  // 찍을 워터마크 

				$image = $imgpath."/img_thumb/".$dstimg; // 업로드된, 워터마크가 없는 파일.

				$image_f_name = $dstimg;
				$image_name = explode(".",$image_f_name); 
				$image_targ1 = explode("-",$image_name[0]);

				$image_targ = $imgpath."/img_thumb/".$image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];  // 워터마크가 찍혀 저장될 이미지 

				$image_org = $image; // 원본 이미지를 다른 이름으로 저장 
				
				//$image = imagecreatefromjpeg($image); // JPG 이미지를 읽고 

				if($image_name[1] == "gif" || $image_name[1] == "GIF")  $image = imagecreatefromgif($image);  
				if($image_name[1] == "jpg" || $image_name[1] == "jpeg" || $image_name[1] == "JPG" || $image_name[1] == "JPEG")  $image = imagecreatefromjpeg($image);  
				if($image_name[1] == "png" || $image_name[1] == "PNG")  $image = imagecreatefrompng($image);  
				
				$w = imagesx($image); 
				$h = imagesy($image);  

				$text_color = imagecolorallocate($image,255,255,255); // 텍스트 컬러 지정 

				// 적당히 워터마크가 붙을 위치를 지정 
				$text_pos_x = $font_size; 
				$text_pos_y = $h - $font_size; 

				imagettftext($image, $font_size, 0, $text_pos_x, $text_pos_y, $text_color, $font_path, $string);  // 읽은 이미지에 워터마크를 찍고 

				$image_org = imagecreatefromjpeg($image_org); // 원본 이미지를 다시한번 읽고 
  
				imagecopymerge($image,$image_org,0,0,0,0,$w,$h,$opacity); // 원본과 워터마크를 찍은 이미지를 적당한 투명도로 겹치기 
				imagejpeg($image, $image_targ, 90); // 이미지 저장. 해상도는 90 정도 
  
				imagedestroy($image); 
				imagedestroy($image_org); 

			} elseif($watermark_sect == "imgw"){ // 이미지합성 워터마크

				if (isset($_GET['transparency'])) {
					if ($_GET['transparency'] >= 0 && $_GET['transparency'] <= 100) {
						$transparency = (int) $_GET['transparency'];
					}
				} else {
					$transparency = 40;
				}
				
				$source_photo = $imgpath."/img_thumb/".$dstimg;   // 업로드된, 워터마크가 없는 파일.

				$image_f_name = $dstimg;
				$image_name = explode(".",$image_f_name); 
				$image_targ1 = explode("-",$image_name[0]);

				$image_targ = $imgpath."/img_thumb/".$image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];  // 워터마크가 찍혀 저장될 이미지 
				$image_targ_name = $image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];
				$filetype = substr($source_photo,strlen($source_photo)-4,4); 
				$filetype = strtolower($filetype);

				if($filetype == ".gif" || $filetype == ".GIF")  $photo = imagecreatefromgif($source_photo);  
				if($filetype == ".jpg" || $filetype == ".jpeg" || $filetype == ".JPG" || $filetype == ".JPEG")  $photo = imagecreatefromjpeg($source_photo);  
				if($filetype == ".png" || $filetype == ".PNG")  $photo = imagecreatefrompng($source_photo);  

				//if (!$photo) die(); 
				$watermark = imagecreatefrompng($_SERVER["DOCUMENT_ROOT"].'/pro_inc/watermark.png');
				$watermark_width = imagesx($watermark);
				$watermark_height = imagesy($watermark);

				//location of the watermark on the source image
				$size = getimagesize($source_photo);
				$dest_x = ($size[0] - $watermark_width) / 2;
				$dest_y = ($size[1] - $watermark_height) / 2;

				imagecopymerge($photo, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $transparency);
				imagejpeg($photo, $image_targ, 90); // 이미지 저장. 해상도는 90 정도 
				
				imagedestroy($photo);

			}
			//워터마크 이미지 생성 종료 

	imageDestroy($src);
	imageDestroy($dst);
}

##### 비율대비 섬네일 2
function img_resize_1( $srcimg, $dstimg, $imgpath, $rewidth, $reheight,$thumbp="",$watermark_sect ) {

	$src_info = getimagesize("$imgpath$srcimg");

	if(!$thumbp){
		$thumbp = "img_thumb2";
	}

	if($rewidth < $src_info[0] || $reheight < $src_info[1]) {
		if(($src_info[0] - $rewidth) > ($src_info[1] - $reheight)) {
			$reheight = round(($src_info[1]*$rewidth)/$src_info[0]);
		} else {
			$rewidth = round(($src_info[0]*$reheight)/$src_info[1]);
		}
	} else {
		if (!copy($imgpath.$srcimg, $imgpath."/".$thumbp."/".$dstimg)) {
			echo "failed to copy $imgpath$srcimg...\n";
		}
		return;
	}

	$dst = imageCreatetrueColor($rewidth, $reheight);

	if($src_info[2] == 1) {
		$src = ImageCreateFromGIF("$imgpath$srcimg");

		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagejpeg($dst,"$imgpath/$thumbp/$dstimg",100);
	} elseif($src_info[2] == 2) {
		$src = ImageCreateFromJPEG("$imgpath$srcimg");

		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagejpeg($dst,"$imgpath/$thumbp/$dstimg",100);
	} elseif($src_info[2] == 3) {
		$src = ImageCreateFromPNG("$imgpath$srcimg");

		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagepng($dst,"$imgpath/$thumbp/$dstimg",9);
	}

			// 워터마크 이미지 생성시작 
			if($watermark_sect == "text"){ // 텍스트형 워터마크 
				
				$font_size = 18; // 글자 크기 
				$opacity = 70; // 투명도 높을수록 불투명 
				$font_path = $_SERVER["DOCUMENT_ROOT"]."/pro_inc/H2HDRM.TTF";  //폰트 패스 
				$string = "PoleStar";  // 찍을 워터마크 

				$image = $imgpath."/".$thumbp."/".$dstimg; // 업로드된, 워터마크가 없는 파일.

				$image_f_name = $dstimg;
				$image_name = explode(".",$image_f_name); 
				$image_targ1 = explode("-",$image_name[0]);

				$image_targ = $imgpath."/".$thumbp."/".$image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];  // 워터마크가 찍혀 저장될 이미지 

				$image_org = $image; // 원본 이미지를 다른 이름으로 저장 
				
				//$image = imagecreatefromjpeg($image); // JPG 이미지를 읽고 

				if($image_name[1] == "gif" || $image_name[1] == "GIF")  $image = imagecreatefromgif($image);  
				if($image_name[1] == "jpg" || $image_name[1] == "jpeg" || $image_name[1] == "JPG" || $image_name[1] == "JPEG")  $image = imagecreatefromjpeg($image);  
				if($image_name[1] == "png" || $image_name[1] == "PNG")  $image = imagecreatefrompng($image);  
				
				$w = imagesx($image); 
				$h = imagesy($image);  

				$text_color = imagecolorallocate($image,255,255,255); // 텍스트 컬러 지정 

				// 적당히 워터마크가 붙을 위치를 지정 
				$text_pos_x = $font_size; 
				$text_pos_y = $h - $font_size; 

				imagettftext($image, $font_size, 0, $text_pos_x, $text_pos_y, $text_color, $font_path, $string);  // 읽은 이미지에 워터마크를 찍고 

				$image_org = imagecreatefromjpeg($image_org); // 원본 이미지를 다시한번 읽고 
  
				imagecopymerge($image,$image_org,0,0,0,0,$w,$h,$opacity); // 원본과 워터마크를 찍은 이미지를 적당한 투명도로 겹치기 
				imagejpeg($image, $image_targ, 90); // 이미지 저장. 해상도는 90 정도 
  
				imagedestroy($image); 
				imagedestroy($image_org); 

			} elseif($watermark_sect == "imgw"){ // 이미지합성 워터마크

				if (isset($_GET['transparency'])) {
					if ($_GET['transparency'] >= 0 && $_GET['transparency'] <= 100) {
						$transparency = (int) $_GET['transparency'];
					}
				} else {
					$transparency = 40;
				}
				
				$source_photo = $imgpath."/".$thumbp."/".$dstimg;   // 업로드된, 워터마크가 없는 파일.

				$image_f_name = $dstimg;
				$image_name = explode(".",$image_f_name); 
				$image_targ1 = explode("-",$image_name[0]);

				$image_targ = $imgpath."/".$thumbp."/".$image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];  // 워터마크가 찍혀 저장될 이미지 
				$image_targ_name = $image_targ1[1]."_marke_".$image_targ1[0].".".$image_name[1];
				$filetype = substr($source_photo,strlen($source_photo)-4,4); 
				$filetype = strtolower($filetype);


				if($filetype == ".gif" || $filetype == ".GIF")  $photo = imagecreatefromgif($source_photo);  
				if($filetype == ".jpg" || $filetype == ".jpeg" || $filetype == ".JPG" || $filetype == ".JPEG")  $photo = imagecreatefromjpeg($source_photo);  
				if($filetype == ".png" || $filetype == ".PNG")  $photo = imagecreatefrompng($source_photo);  

				$watermark = imagecreatefrompng($_SERVER["DOCUMENT_ROOT"].'/pro_inc/watermark.png');
				$watermark_width = imagesx($watermark);
				$watermark_height = imagesy($watermark);

				//location of the watermark on the source image
				$size = getimagesize($source_photo);
				$dest_x = ($size[0] - $watermark_width) / 2;
				$dest_y = ($size[1] - $watermark_height) / 2;

				//make the image (merge source image with watermark)
				imagecopymerge($photo, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $transparency);
				imagejpeg($photo, $image_targ, 90); // 이미지 저장. 해상도는 90 정도 
				imagedestroy($photo);

			}
			//워터마크 이미지 생성 종료 

	imageDestroy($src);
	imageDestroy($dst);
}

########## 이미지 파일명 -> 워터마크 붙은 이미지명으로 변환 
function get_imgname_water($filename){
	$full_filename = explode(".","$filename");
	$extension = $full_filename[sizeof($full_filename)-1];
	$filename2 = str_replace(".".$extension,"",$filename);

	$filename_arr = explode("-",$filename2);	
	$filename_water = $filename_arr[1]."_marke_".$filename_arr[0].".".$extension;

	return $filename_water;
}

##### 자동으로 http 붙이기 
function print_url($txt){
	$url_patt =" http://([0-9a-zA-Z./@~?&=_]+)" ;//-----(1) 
	$url_patt1 ="\nhttp://([0-9a-zA-Z./@~?&=_]+)" ;//----(2) 
	$email_patt=" ([_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*)@([0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*)" ; //-----(3) 

	$txt = eregi_replace($url_patt," <a href=http://\\1 target='_blank'>http://\\1</a>",$txt); 
	$txt = eregi_replace($url_patt1,"\n<a href=http://\\1 target='_blank'>http://\\1</a>",$txt); 
	$txt = eregi_replace($email_patt,"<a href=mailto:\\1@\\3>\\1@\\3</a>",$txt); 
	return $txt;
}

######## 에러메시지 출력후 back
function error_back($msg) {
				echo ("
				<script type='text/javascript'>
				<!--
					alert ('$msg');
					history.back();
				//-->
				</script>
				");
				exit;
}

######## iframe 에서 에러메시지 출력만
function error_frame($msg) {
				echo ("
				<script type='text/javascript'>
				<!--
					alert ('$msg');
				//-->
				</script>
				");
				exit;
}

######## iframe 에서  에러메시지 출력 후 페이지이동
function error_frame_go($msg,$url) {
				echo ("
				<script type='text/javascript'>
				<!--
					alert ('$msg');
					parent.location.href='".$url."';
				//-->
				</script>
				");
				exit;
}

######## iframe 에서  에러메시지 출력 후 부모창 새로고침
function error_frame_reload($msg) {
				echo ("
				<script type='text/javascript'>
				<!--
					alert ('$msg');
					parent.location.reload();
				//-->
				</script>
				");
				exit;
}

######## iframe 에서  에러메시지 출력 없이 페이지이동
function frame_go($url) {
				echo ("
				<script type='text/javascript'>
				<!--
					parent.location.href='".$url."';
				//-->
				</script>
				");
				exit;
}

######## 팝업창에서 에러메시지 출력후 창닫기
function error_popup_parent($msg) {
				echo ("
				<script type='text/javascript'>
				<!--
					alert ('$msg');
					parent.close();
				//-->
				</script>
				");
				exit;
}

function error_popup($msg) {
				echo ("
				<script type='text/javascript'>
				<!--
					alert ('$msg');
					self.close();
				//-->
				</script>
				");
				exit;
}

######## 팝업창에서 에러메시지 출력후 본페이지 이동하며 창닫기
function error_popup_go($msg,$url) {
				echo ("
				<script type='text/javascript'>
				<!--
					alert ('$msg');
					opener.location.href='".$url."';
					self.close();
				//-->
				</script>
				");
				exit;
}

######## 팝업창에서 에러메시지 출력후 창닫기
function no_error_popup_go($url) {
				echo ("
				<script type='text/javascript'>
				<!--
					opener.location.href='".$url."';
					self.close();
				//-->
				</script>
				");
				exit;
}

######## 에러메시지 출력후 페이지이동
function error_go($msg,$url) {
				echo "
				<script type='text/javascript'>
				<!--
					alert ('$msg');
					location.href='".$url."';
				//-->
				</script>
				";
				exit;
}

######## 에러메시지 출력 없이 페이지이동
function no_error_go($url) {
				echo "
				<script type='text/javascript'>
				<!--
					location.href='".$url."';
				//-->
				</script>
				";
				exit;
}

######## 이메일 형식 확인 
function validate_email ($email, $error, $err_msg) {
	if(ereg("([^[:space:]]+)", $email) && (!ereg("(^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)", $email))  ) {
		$error($err_msg);
		exit;
	}
}

######## 홈페이지 URL 형식 확인 
function validate_homepage ($homepage, $error, $err_msg){
	if(ereg("([^[:space:]]+)", $homepage) && (!ereg("http://([0-9a-zA-Z./@~?&=_]+)", $homepage))){
   		$error($err_msg);   
   		exit;
   	}
}

#### 등록일이 하루전인지 추출하기 위한 펑션
function now_date($c_time){ // 등록일이 하루전인지 추출하기 위한 펑션

	$nowdate = time()-86400*1; // 하루전 리눅스타임	

	if ($c_time >= $nowdate){
		$new_icon = "<img src='/images/icon/icon_new.png' border='0' align='absmiddle'>";
	} else {
		$new_icon = "";	
	}

	return $new_icon;
}

#### SQL 필터링 full
function sqlfilter_ext($str) {
	//1단계 ? ',",NULL 문자 필터링. 각 문자들에 백슬래쉬(\) 삽입됨. 필수 항목
	//출력시 stripslashes()함수를 이용하여 백슬래쉬(\)를 제거
	$str = urldecode($str);
	if (!get_magic_quotes_gpc()) $str = addslashes($str);
	//3단계 ? 특수 문자 및 문자열 필터링
	//WHERE 구문에서 쓰여지는 데이터만 사용하는 것이 바람직하다.
	$search = array("--","javascript:;",";","+");
	$replace = array("\--","\#","\;","\+");
	$str = str_replace($search, $replace, $str);
	return $str;
}

#### SQL 필터링 간략화
function sqlfilter($str) {
	//$str = urldecode($str);
	if (!get_magic_quotes_gpc()) $str = addslashes($str);
	$str=str_replace("javascript:;","\#", $str);
	$str=str_replace(";","\;", $str);

	return $str;
}

#### 날짜를 timestamp값으로 바꿔줌.
function to_time($date_str,$niddle='-'){
	//echo "받은날짜 = ".$date_str;
	$date_arr=explode($niddle,$date_str);
	return mktime(0,0,0,$date_arr[1],$date_arr[2],$date_arr[0]);
}

#### 알파벳 난수생성 
function randomChar($rsltea){
		srand((double)microtime() * 1000000);
		$patternA = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  //26
			for($i=1;$i<=$rsltea;$i++){
				$pick2 = rand(0,25);
				$rsltea = $rsltea.substr($patternA,$pick2,1);
				//echo $rsltea."<br>";
			}
		$rsltea = substr($rsltea,1,$rsltea);
		return $rsltea;
}

####### 특정숫자 제외 난수생성 
function mt_rand_n($min,$max,$disallowed,$check_cnt=0) { 
	
	$rand = mt_rand($min,$max);
	$disallowed_arr = explode(",",$disallowed);

	for($k=0; $k<sizeof($disallowed_arr); $k++){ // 제외해야할 숫자의 갯수만큼 루프 시작

		if(trim($rand) == trim($disallowed_arr[$k])){ // 만들어진 랜덤수가 제외해야할 숫자와 같다면 시작
			
			$check_cnt = $check_cnt+1;
			if($check_cnt >= 9){ // 무한루프 차단
				//error_frame("이월수 또는 고정수, 혹은 제외수에 1궁도 값이 너무 많아서 자동으로 번호를 배당할수가 없습니다.");
			}
					
			 $rand = mt_rand_n($min,$max,$disallowed,$check_cnt);

		} // 만들어진 랜덤수가 제외해야할 숫자와 같다면 종료

	} // 제외해야할 숫자의 갯수만큼 루프 종료
	return $rand;
}

####### 경로채로 파일 삭제 
function LIB_removeAllData( $URL ) 
{ 
    if( is_dir( $URL ) ) 
    { 
        if( $dh = opendir( $URL ) ) 
        { 
            while( ( $file = readdir( $dh ) ) !== false ) 
            { 
                if( $file == '.' || $file == ".." )        continue; 

                if( filetype( $URL.$file ) == "dir" )    LIB_removeAllData( $URL.$file.'/' ); 
                else                                    @unlink( $URL.$file );                    // 파일 삭제 
            } 

            @rmdir( $URL );        // 폴더 삭제 
            closedir( $dh ); 
        } 
    } 
} 

####### 글자수 자르기 
Function string_cut2($str, $len, $checkmb=false, $tail='...') { 
	$len = $len + 14;
	$title = stripslashes($str);
	$title = htmlspecialchars_decode($title, ENT_QUOTES);
	$tsize = strlen($title);
	return ($tsize <= $len)? $title : mb_strcut($title, 0, $len-2, 'UTF-8').$tail;
}

#### utf-8 HTML을 utf-8 로 변환해서 보내기
function encode_2047($subject) {
    return '=?utf-8?b?'.base64_encode($subject).'?=';
}

#### 메일발송 함수
function mail_utf($from_email,$from_name,$to_email,$subject,$body,$file=""){ 
    if (strlen($to_email)==0) return 0; 
	$body = stripslashes($body);
	$subject = iconv("utf-8","euc-kr",$subject);
	$from_name = iconv("utf-8","euc-kr",$from_name);
    
	$mailheaders .= "From: $from_name<$from_email> \r\n"; 
    $mailheaders .= "Reply-To: $from_name<$from_email>\r\n"; 
    $mailheaders .= "Return-Path: $from_name<$from_email>\r\n"; 
	//echo "파일사이즈 = ".$file[size]."<br>";
    if ($file[size]>0) { 
		$file['data'] = file_get_contents($file['tmp_name']); // 파일 내용 읽기 4.3 이상 
        $boundary = uniqid("part"); 
        if (strlen($file[type])==0) $file[type] = "application/octet-stream"; 

        $mailheaders .= "MIME-Version: 1.0\r\n"; 
        $mailheaders .= "Content-Type: Multipart/mixed; boundary = \"".$boundary."\""; 

        $bodytext = "This is a multi-part message in MIME format.\r\n\r\n"; 
        $bodytext .= "--".$boundary."\r\n"; 
        $bodytext .= "Content-Type: text/html; charset=\"utf-8\"\r\n"; 
        $bodytext .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
        $bodytext .= ereg_replace("(.{80})","\\1\r\n",base64_encode(stripslashes($body))) . "\r\n\r\n";

        $bodytext .= "--".$boundary."\r\n"; 
        $bodytext .= "Content-Type: ".$file[type]."; name=\"".$file[name]."\"\r\n"; 
        $bodytext .= "Content-Transfer-Encoding: base64\r\n"; 
        $bodytext .= "Content-Disposition: attachment; filename=\"".$file[name]."\"\r\n\r\n"; 
        $bodytext .= chunk_split(base64_encode($file[data]))."\r\n\r\n"; 

        $bodytext .= "--".$boundary."--"; 
    } else { 
        $mailheaders .= "Content-Type: text/html; charset=\"utf-8\"\r\n";  
        $bodytext = stripslashes($body) . "\r\n\r\n"; 
    } 
	
    if(!mail($to_email,$subject,$bodytext,$mailheaders,'-f'.$from_email)) {return 0;} 
    return 1; 
} 

#### 리다이렉트
function redirect($locate,$sec){
	echo("<meta http-equiv='refresh' content='$sec ; url=$locate'>");
}

#### 한글변환 
function tran_encode_euckr($str) {
	$str = iconv("euc-kr", "utf-8", $str);
	return $str;
}

#### 숫자 앞에 0 붙이기 
function fnzero($str) {
	$sren = strlen($str);

		if($sren == 1){
		$str = "0".$str;
		}

	return $str;
}

#### 숫자 앞에 0 빼기 
function fnzero_minus($str) {
	$sren = strlen($str);
	if($sren == 2){
		$str = str_replace("0","",$str);
	}

	return $str;
}

#### md5 기반 암호화 
function encrypt_md5_base64($plain_text, $password="password", $iv_len = 16){
        $plain_text .= "\x13";
        $n = strlen($plain_text);
        if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
        $i = 0;
        while ($iv_len-- >0)
        {
            $enc_text .= chr(mt_rand() & 0xff);
        }
        
        $iv = substr($password ^ $enc_text, 0, 512);
        while($i <$n)
        {
            $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
            $enc_text .= $block;
            $iv = substr($block . $iv, 0, 512) ^ $password;
            $i += 16;
        }
        return base64_encode($enc_text);
}

#### md5 기반 복호화 
function decrypt_md5_base64($enc_text, $password="password", $iv_len = 16){
        $enc_text = base64_decode($enc_text);
        $n = strlen($enc_text);
        $i = $iv_len;
        $plain_text = '';
        $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
        while($i <$n)
        {
            $block = substr($enc_text, $i, 16);
            $plain_text .= $block ^ pack('H*', md5($iv));
            $iv = substr($block . $iv, 0, 512) ^ $password;
            $i += 16;
        }
        return preg_replace('/\x13\x00*$/', '', $plain_text);
 }

#### 서버에 저장하기 전 이미지 파일 확인 
function upload_img_type($srcimg) {
	if(is_file($srcimg)) {
		$image_info = @getimagesize($srcimg);

		switch ($image_info['mime']) {
			case 'image/gif': return true; break;
			case 'image/jpeg': return true; break;
			case 'image/png': return true; break;
			case 'image/bmp': return true; break;
			default : return false; break;
		}
	} else {
		return false;
	}
}

#### 파일확장자 확인 
function getExt($file) { 
	$needle = strrpos($file, ".") + 1; // 파일 마지막의 "." 문자의 위치를 반환한다. 
	$slice = substr($file, $needle); // 확장자 문자를 반환한다. 
	$ext = strtolower($slice); // 반환된 확장자를 소문자로 바꾼다. 
	return $ext; 
} 

#### 등록 후 경과시간 텍스트  
function get_minustime($strtime) { 
	if(strtotime($strtime) >= time()){
		$calculate_time = strtotime($strtime) - time(); 
	} else {
		$calculate_time = time() - strtotime($strtime); 
	}

	$min = round($calculate_time/60)."분"; //분
    if($min >60){
		 $min = round($calculate_time/3600)."시간"; //시간
		 if($min > 24){
			$min = round($calculate_time/86400)."일"; //일
		}
   }
   return $min; 
} 

#### 데드라인 표시 텍스트   
function get_minustime_req($reqtime,$strtime) { 
	if(strtotime($reqtime) <= strtotime($strtime)){
		$min = "즉시"; 
	} else {
		$calculate_time = strtotime($reqtime) - strtotime($strtime); 
		$min = round($calculate_time/60)."분 후"; //분
		if($min >60){
			 $min = round($calculate_time/3600)."시간 후"; //시간
			if($min > 24){
				$min = round($calculate_time/86400)."일 후"; //일
			}
		}
	}
	
   return $min; 
}

#### 이달의 마지막 날 
function get_totaldays($year,$month) {
$date = 1;
  while(checkdate($month,$date,$year)) {
      $date++;
  }
   $date--;
   return $date;
}

#### 외부 xml 파일 오픈
function getUrlData($url,$path) {	
		$socket = fsockopen($url, 80);	
		if($socket) {		
				$header = "GET /".$path." HTTP/1.0\n\n";		
				fwrite($socket, $header); 		
				$data = '';		
				while(!feof($socket)) { 
						$data .= fgets($socket); 
				}		
				fclose($socket); 		
				$data = explode("\r\n\r\n", $data, 2);		
				return $data[1];	
		} else {
			return false;
		}
}

#### 두 좌표간의 거리를 구하기(WGS84 기준)
function get_distance($lat1, $lon1, $lat2, $lon2) {
  /* WGS84 stuff */
  $a = 6378137;
  $b = 6356752.3142;
  $f = 1/298.257223563;
  /* end of WGS84 stuff */

  $L = deg2rad($lon2-$lon1);
  $U1 = atan((1-$f) * tan(deg2rad($lat1)));
  $U2 = atan((1-$f) * tan(deg2rad($lat2)));
  $sinU1 = sin($U1);
  $cosU1 = cos($U1);
  $sinU2 = sin($U2);
  $cosU2 = cos($U2);

  $lambda = $L;
  $lambdaP = 2*pi();
  $iterLimit = 20;
  while ((abs($lambda-$lambdaP) > pow(10, -12)) && ($iterLimit-- > 0)) {
    $sinLambda = sin($lambda);
    $cosLambda = cos($lambda);
    $sinSigma = sqrt(($cosU2*$sinLambda) * ($cosU2*$sinLambda) + ($cosU1*$sinU2-$sinU1*$cosU2*$cosLambda) * ($cosU1*$sinU2-$sinU1*$cosU2*$cosLambda));

    if ($sinSigma == 0) {
      return 0;
    }

    $cosSigma   = $sinU1*$sinU2 + $cosU1*$cosU2*$cosLambda;
    $sigma      = atan2($sinSigma, $cosSigma);
    $sinAlpha   = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
    $cosSqAlpha = 1 - $sinAlpha*$sinAlpha;
    $cos2SigmaM = $cosSigma - 2*$sinU1*$sinU2/$cosSqAlpha;

    if (is_nan($cos2SigmaM)) {
      $cos2SigmaM = 0;
    }

    $C = $f/16*$cosSqAlpha*(4+$f*(4-3*$cosSqAlpha));
    $lambdaP = $lambda;
    $lambda = $L + (1-$C) * $f * $sinAlpha *($sigma + $C*$sinSigma*($cos2SigmaM+$C*$cosSigma*(-1+2*$cos2SigmaM*$cos2SigmaM)));
  }

  if ($iterLimit == 0) {
    // formula failed to converge
    return NaN;
  }

  $uSq = $cosSqAlpha * ($a*$a - $b*$b) / ($b*$b);
  $A = 1 + $uSq/16384*(4096+$uSq*(-768+$uSq*(320-175*$uSq)));
  $B = $uSq/1024 * (256+$uSq*(-128+$uSq*(74-47*$uSq)));
  $deltaSigma = $B*$sinSigma*($cos2SigmaM+$B/4*($cosSigma*(-1+2*$cos2SigmaM*$cos2SigmaM)- $B/6*$cos2SigmaM*(-3+4*$sinSigma*$sinSigma)*(-3+4*$cos2SigmaM*$cos2SigmaM)));

  return round($b*$A*($sigma-$deltaSigma) / 1000,1);


/* sphere way */
  $distance = rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon1 - $lon2))));

  $distance *= 111.18957696; // Convert to km

  return $distance;
}

#### curl post
function get_curl_post($url,$data,$header=""){
	//print_r($data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	if($header){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	$ret = curl_exec($ch);
    if($_SERVER['REMOTE_ADDR'] == "211.227.88.137"){   
	    echo "<div style='display:block;'>result string : ";
	        print_r($ret);
	    echo "</div>";
    }
	curl_close($ch);

	return $ret;
}

#### curl json post
function get_curl_json_post($url, $data){
    $url = str_replace('}]"}','}]}',$url);
    $data = json_encode($data);
	if($_SERVER['REMOTE_ADDR'] == "121.167.147.150"){
       //echo "url = ".$url."<br>";
       /*echo "data = ".$data."<br>";
       echo "<div style='display:block;'>req: ";
	        print_r($url);
	    echo "</div>";*/
    }
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTkeyword2S, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
    /*if($_SERVER['REMOTE_ADDR'] == "121.167.147.150"){   
	    echo "<div style='display:block;'>res: ";
	        print_r($ret);
	    echo "</div>";
    }*/
	curl_close($curl);

	return $ret;
}

#### curl xml post
function get_curl_xml_post($url, $data){
    if($_SERVER['REMOTE_ADDR'] == "121.167.147.150"){
         echo "data = ".$data['q']."<br>";  
    }
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTkeyword2S, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec ($ch);
	curl_close ($curl);
	return $response;
}

######## FCM 푸시 발송 
########사용법 : send_fcm("title","message","http://www.naver.com","em7LJgnbK6g:APA91bGVmFkO5ZVSB8hpsz4WzdAQ9EWmFVHVOU-0z5lG6lFkawCcmvqvMTITzNn3BgbrBpW4Y0spwh4eubiKHVcKH9cFmDKp1VdEf4-NF2RExw7-DqNdkHi31YpdhAqqtUc8Y70rwI_N");
########send_fcm(푸시 제목, 푸시 메세지, URL, 푸시받는 회원 푸시키)
function send_fcm($title,$message,$directURL, $id) {
	$url = 'https://fcm.googleapis.com/fcm/send';

	$headers = array (
        'Authorization: key='.$inc_GOOGLE_SERVER_KEY, // $inc_GOOGLE_SERVER_KEY 는 include_default.php 같은데서 지정하면 된다 
        'Content-Type: application/json'
    );


    $keyword2s = array (
        'data' => array ("url" => $directURL),
        'notification' => array ("title" =>$title,
                                "body" => $message,
                                "sound" => "true")
    );

    if(is_array($id)) {
        $keyword2s['registration_ids'] = $id;
    } else {
        $keyword2s['to'] = $id;
    }

    $keyword2s['priority'] = "high";

    $keyword2s = json_encode ($keyword2s);
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTkeyword2S, $keyword2s );

    $result = curl_exec ( $ch );

    if ($result === FALSE) {
		die('FCM Send Error: ' . curl_error($ch));
    } else {
		echo $result;
	}
    curl_close ( $ch );
    return $result;
}

#### 접속한 디바이스가 모바일인가 
function is_mobile(){
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	
	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
		return true;
	} else {
		return false;
	}
}

#### 회원정보   
function get_member_data($member_idx) { 
	$sql = "select *,(select logdate from mem_login_count where 1 and member_idx=a.idx order by idx desc limit 0,1) as last_login,(select cur_mile from member_point where 1 and point_sect='smspay' and mile_sect != 'P' and member_idx=a.idx order by idx desc limit 0,1) as current_point,(select com_name from member_info_company where 1 and is_del='N' and idx=a.partner_idx order by idx desc limit 0,1) as com_name,(select mb_short_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_short_fee,(select mb_long_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_long_fee,(select mb_img_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_img_fee,(select mb_short_cnt from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_short_cnt,(select mb_long_cnt from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_long_cnt,(select mb_img_cnt from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_img_cnt,(select call_num from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as call_num,(select call_memo from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as call_memo,(select use_yn from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as use_yn from member_info a where 1 and idx='".$member_idx."' and del_yn='N'";
	$query = mysqli_query($GLOBALS['gconnet'],$sql);
	$row = mysqli_fetch_array($query);
	return $row;
} 

######## 조회수 증가 
function set_vcnt_up($tbname,$code="",$idx,$wmidx,$lmidx,$targettb,$targetclm){
	//if($lmidx && ($lmidx != $wmidx)){ // 작성자 본인이 열람하는것이 아닐때 시작
		
		$sql_prev = "select idx from board_view_cnt where 1 and board_tbname='".$tbname."'";
		if($code){
			$sql_prev .= " and board_code = '".$code."'";
		}
		$sql_prev .= " and board_idx='".$idx."' and member_idx = '".$lmidx."' ";
		$query_prev = mysqli_query($GLOBALS['gconnet'],$sql_prev);
		$cnt_prev = mysqli_num_rows($query_prev);

		//if($cnt_prev == 0){ // 현 게시물을 처음 볼때 한해서 조회수를 증가시킨다 시작 
			
			$query_view_cnt = " insert into board_view_cnt set "; 
			$query_view_cnt .= " board_tbname = '".$tbname."', ";
			$query_view_cnt .= " board_code = '".$code."', ";
			$query_view_cnt .= " board_idx = '".$idx."', ";
			$query_view_cnt .= " member_idx = '".$lmidx."', ";
			$query_view_cnt .= " cnt = '1', ";
			$query_view_cnt .= " wdate = now() ";
			//$result_view_cnt = mysqli_query($GLOBALS['gconnet'],$query_view_cnt);

			$sql_cnt = "update ".$targettb." set ".$targetclm."=".$targetclm."+1 where 1 and idx = '".$idx."'";
			//echo $sql_cnt."<br>";
			$query_cnt = mysqli_query($GLOBALS['gconnet'],$sql_cnt);
	
		//} // 현 게시물을 처음 볼때 한해서 조회수를 증가시킨다 종료 

	//}  // 작성자 본인이 열람하는것이 아닐때 종료
}

####### 조회수 조회 
function get_vcnt($tbname,$code="",$idx){
	$current_cnt_query = "select sum(cnt) as current_cnt from board_view_cnt where 1 and board_tbname='".$tbname."'";
	if($code){
		$current_cnt_query .= " and board_code = '".$code."'";
	}
	$current_cnt_query .= " and board_idx='".$idx."'";
	$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
	$current_cnt_row = mysqli_fetch_array($current_cnt_result);

	if ($current_cnt_row['current_cnt']){
		$current_cnt = $current_cnt_row['current_cnt'];
	} else{
		$current_cnt = 0;
	}
	
	return $current_cnt;
}

########## 회원의 현재 포인트 
function mem_current_point($member_idx,$point_sect=""){
	if(!$point_sect){
		$point_sect = "refund"; // 적립금 
	}

	$sql_sub1 = "select cur_mile from member_point where member_idx='".$member_idx."' and point_sect='".$point_sect."' and mile_sect != 'P' order by idx desc limit 0,1 "; 
	
	$query_sub1 = mysqli_query($GLOBALS['gconnet'],$sql_sub1);
					
	if(mysqli_num_rows($query_sub1)==0) {
		$mile_pre = 0; 
	} else {
		$row_sub1 = mysqli_fetch_array($query_sub1); 
		$mile_pre = $row_sub1[cur_mile];
	}

	return $mile_pre;
}

########## 회원의 현재 포인트로 순위추출 
function mem_point_ranking($member_idx,$point_sect=""){
	if(!$point_sect){
		$point_sect = "refund"; // 적립금 
	}

	$mem_current_point = mem_current_point($member_idx,$point_sect);

	$sql_sub1 = "select idx,(select cast(cur_mile as unsigned) from member_point where member_idx=member_info.idx and point_sect='".$point_sect."' and mile_sect != 'P' order by idx desc limit 0,1) as cur_coin from member_info where 1 and member_type = 'GEN' and memout_yn != 'Y' and memout_yn != 'S' and (select cast(cur_mile as unsigned) as cur_coin from member_point where member_idx=member_info.idx and point_sect='".$point_sect."' and mile_sect != 'P' order by idx desc limit 0,1) >= '".$mem_current_point."' and idx != '".$member_idx."'"; 
	$query_sub1 = mysqli_query($GLOBALS['gconnet'],$sql_sub1);
					
	$mile_pre = mysqli_num_rows($query_sub1)+1; 
	return $mile_pre;
}

######## 포인트 적립/차감 
function coin_plus_minus($point_sect,$member_idx,$mile_sect,$chg_mile,$mile_title,$order_num="",$pay_price="",$ad_sect="",$board_tbname="",$board_code="",$board_idx=""){
	
	//echo "변동되는 값 = ".$chg_mile."<br>";

	if($chg_mile > 0 ){
		$mile_pre = mem_current_point($member_idx,$point_sect); // 현재 적립금 금액
		
		if($mile_sect == "A"){
			$cur_mile = $mile_pre+$chg_mile;
		} elseif($mile_sect == "M"){
			$cur_mile = $mile_pre-$chg_mile;
		}

		if($cur_mile < 0){
			$cur_mile = 0;
		}
	
		$query_mile = " insert into member_point set "; 
		$query_mile .= " order_num = '".$order_num."', ";
		$query_mile .= " member_idx = '".$member_idx."', ";
		$query_mile .= " pay_price = '".$pay_price."', ";
		$query_mile .= " mile_title = '".$mile_title."', ";
		$query_mile .= " mile_sect = '".$mile_sect."', ";
		$query_mile .= " mile_pre = '".$mile_pre."', ";
		$query_mile .= " chg_mile = '".$chg_mile."', ";
		$query_mile .= " cur_mile = '".$cur_mile."', ";
		$query_mile .= " point_sect = '".$point_sect."', ";
		$query_mile .= " board_tbname = '".$board_tbname."', ";
		$query_mile .= " board_code = '".$board_code."', ";
		$query_mile .= " board_idx = '".$board_idx."', ";
		$query_mile .= " ad_sect = '".$ad_sect."', ";
		$query_mile .= " wdate = now() ";
		//echo $query_mile."<br>";
		$result_mile = mysqli_query($GLOBALS['gconnet'],$query_mile);
		
		$contents_idx = mysqli_insert_id($GLOBALS['gconnet']);
		
	} else {
		$contents_idx = "";
	}
	
	return $contents_idx;
}

######### 좋아요 클릭 수 가져오기 
function get_cnt_zzim($tbname,$tcode="",$tidx,$member_idx="",$abs=""){
	$current_cnt_query = "select idx from board_reco_cnt where 1 and board_tbname='".$tbname."' and board_idx='".$tidx."'";
	if($tcode){
		$current_cnt_query .= " and board_code='".$tcode."'";
	}
	if($abs == "Y"){
		$current_cnt_query .= " and member_idx = '".$member_idx."'";
	} else {
		if($member_idx){
			$current_cnt_query .= " and member_idx = '".$member_idx."'";
		}
	}
	//echo $current_cnt_query."<br>";
	$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
	$current_cnt = mysqli_num_rows($current_cnt_result);
	
	return $current_cnt;
}

############### 댓글 수 가져오기 
function get_cnt_comment($tbname,$tcode="",$tidx,$member_idx=""){
	$current_cnt_query = "select idx from board_comment where 1 and board_tbname='".$tbname."' and board_idx='".$tidx."'";
	if($tcode){
		$current_cnt_query .= " and board_code='".$tcode."'";
	}
	if($member_idx){
		$current_cnt_query .= " and member_idx = '".$member_idx."'";
	}
	//echo $current_cnt_query."<br>";
	$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
	$current_cnt = mysqli_num_rows($current_cnt_result);
	
	return $current_cnt;
}

########### 날자_시퀀스형 주문번호 만들기 
function make_order_num($order_gift_yn=""){
	$next_order_num = "";
    $today =  date("Y-m-d");
    $today2 =  date("Ymd");

	if(!$order_gift_yn){
		$tb_name = "order_member";
	} else {
		$tb_name = $order_gift_yn;
	}
	
    $query = "select order_num from ".$tb_name." where 1 and substring(order_num,1,8) = '".$today2."' order by idx desc limit 0, 1";
	
	//echo $query."<br>"; //exit;
    
	if($result = mysqli_query($GLOBALS['gconnet'],$query)){
		if(mysqli_num_rows($result)){
			if($row = mysqli_fetch_assoc($result)){
				$order_num = $row["order_num"];
                $order_num = str_replace("coinc","",$order_num);
				$order_num = str_replace("-","",$order_num);
				$date = substr($order_num, 0, 8);
				$seq = (int)substr($order_num, 8, 4);
				$seq++;
                $next_order_num = $date."_".sprintf("%04d", $seq);
			}
		}else{
			$next_order_num = $today2."_0001";
		}
	}
	
	$next_order_num = $next_order_num."_".randomChar(4);
    return $next_order_num;
}

########### 모바일형 컨텐츠 - 에디터 속의 이미지 파일이 있을경우 이미지 사이즈 제한 
function get_mobile_content($content){
	$p_memo = preg_replace("/ style=(\"|\')?([^\"\']+)(\"|\')?/","",$content);
	$p_memo = preg_replace("/ style=([^\"\']+) /"," ",$p_memo); 
	$p_memo = str_replace("<img","<img style='max-width:90%;'",$p_memo);
	$p_memo = str_replace("<p>","<p class='txt'>",$p_memo);

	return $p_memo;
}

##### 퍼센트 구하기 
function fnPercent($range, $total, $slice){
	// 전체값의 몇 퍼센트(total, totalPer, 98:전체값, 20:구할퍼센트) fnPercent("total",98, 20)
	// 전체값에서 일부값의 퍼센트 $traffic1_total : 전체, $arrValue1[$i-1] : 일부 fnPercent("slice",$traffic1_total, $arrValue1[$i-1])
	if($total == 0)$total = 1; //Division by zero 에러방지

	$result;

	if($range == "totalPer" || $range == "total"){
		//n = 전체값 * 퍼센트 / 100;
		$result = ($total * $slice) / 100;
		return round($result);
	}else{
		//n% = 일부값 / 전체값 * 100;
		$result = ($slice / $total) * 100;
		return number_format($result, 2, '.', '');
	}
}

############### 옵션선택 - 드롭다운 
function get_code_list_select($where,$ctype1,$ctype2,$tgparam=""){
	$sql = "select idx,".$ctype1." as opt_code,".$ctype2." as opt_name from common_code where 1 and is_del='N' and del_ok = 'N' ";
	if($where){
		$sql .= $where;
	}
	$sql .= " order by cate_align desc";
	//echo $sql."<br>";
	$query = mysqli_query($GLOBALS['gconnet'],$sql);
	$cnt = mysqli_num_rows($query);
	
	$row_val = "";
	for($i=0; $i<$cnt; $i++){
		$row = mysqli_fetch_array($query);
		
		if(trim($tgparam) == trim($row['opt_code'])){
			$row_val .= "<option value='".$row['opt_code']."' selected>".$row['opt_name']."</option>";
		} else {
			$row_val .= "<option value='".$row['opt_code']."'>".$row['opt_name']."</option>";
		}
	}
	
	echo $row_val;
	//return $row_val;
}

############### 옵션선택 - 라디오버튼 방식 ###################
function get_code_list_radio($where,$ctype1,$ctype2,$colname,$inputok,$inputname,$tgparam=""){
	$sql = "select idx,".$ctype1." as opt_code,".$ctype2." as opt_name from common_code where 1 and is_del='N' and del_ok = 'N' ";
	if($where){
		$sql .= $where;
	}
	$sql .= " order by cate_align desc";
	//echo $sql."<br>";
	$query = mysqli_query($GLOBALS['gconnet'],$sql);
	$cnt = mysqli_num_rows($query);
	
	$row_val = "";
	for($i=0; $i<$cnt; $i++){
		$row = mysqli_fetch_array($query);
		
		if(is_compet_info_opt($tgparam,$row['opt_code']) == "Y"){
		//if(trim($tgparam) == trim($row['opt_code'])){
			$row_val .= "<input type='radio' name='".$colname."' value='".$row['opt_code']."' checked required='".$inputok."'  message='".$inputname."' id='".$colname."_".$i."'> <label for='".$colname."_".$i."'>".$row['opt_name']."</label> &nbsp; ";
		} else {
			$row_val .= "<input type='radio' name='".$colname."' value='".$row['opt_code']."' required='".$inputok."'  message='".$inputname."' id='".$colname."_".$i."'> <label for='".$colname."_".$i."'>".$row['opt_name']."</label> &nbsp; ";
		}
	}
	
	echo $row_val;
	//return $row_val;
}

############### 옵션선택 - 체크박스 방식 ###################
function get_code_list_checkb($where,$ctype1,$ctype2,$colname,$inputok,$inputname,$tgparam=""){
	$sql = "select idx,".$ctype1." as opt_code,".$ctype2." as opt_name from common_code where 1 and is_del='N' and del_ok = 'N' ";
	if($where){
		$sql .= $where;
	}
	$sql .= " order by cate_align desc";
	//echo $sql."<br>";
	$query = mysqli_query($GLOBALS['gconnet'],$sql);
	$cnt = mysqli_num_rows($query);
	
	$row_val = "";
	for($i=0; $i<$cnt; $i++){
		$row = mysqli_fetch_array($query);

		$opt_name_arr = explode("(",$row['opt_name']);
		
		//if(trim($tgparam) == trim($row['opt_code'])){
		if(is_compet_info_opt($tgparam,$row['opt_code']) == "Y"){
			$row_val .= "<input type='checkbox' name='".$colname."[]' value='".$row['opt_code']."' checked required='".$inputok."' message='".$inputname."' id='".$colname."_".$i."'> <label for='".$colname."_".$i."'>".$opt_name_arr[0]."</label> &nbsp; ";
		} else {
			$row_val .= "<input type='checkbox' name='".$colname."[]' value='".$row['opt_code']."' required='".$inputok."' message='".$inputname."' id='".$colname."_".$i."'> <label for='".$colname."_".$i."'>".$opt_name_arr[0]."</label> &nbsp; ";
		}
	}
	
	echo $row_val;
	//return $row_val;
}

######### 특정 테이블의 특정 칼럼 추출 ########
function get_data_colname($tbname,$tbcal,$tbkey,$column,$where=""){
	$sql = "select ".$column." as receivem_idx from ".$tbname." where 1 and ".$tbcal."='".$tbkey."'";
	if($where){
		$sql .= $where;
	}
	//echo "get_data_colname = ".$sql."<br>";
	//exit;
	$query = mysqli_query($GLOBALS['gconnet'],$sql);
	$row = mysqli_fetch_array($query);
	return $row['receivem_idx'];
}

######### 남은 날자 계산 ########
function get_remain_date($tgdate){
	$nDate = date("Y-m-d"); // 오늘날자
	$valDate = Trim($tgdate); // 종료일
	if($valDate < $nDate){
		$leftDate = 0;
	} else {
		$leftDate = intval((strtotime($valDate)-strtotime($nDate)) / 86400); // 나머지 날짜값이 나옵니다.
	}
	return $leftDate;
}

######### 얼마전에 등록한 것인지 가져오기 
function get_minus_datet($cdate){
	$current = strtotime(date('Y-m-d H:i:s'));
	$timestamp = strtotime($cdate);
	  if ($timestamp <= $current - 86400 * 365) { 
		 $str = (int)(($current - $timestamp) / (86400 * 365)) . "년전"; 
	  } else if ($timestamp <= $current - 86400 * 31) { 
		 $str = (int)(($current - $timestamp) / (86400 * 31)) . "개월전"; 
	  } else if ($timestamp <= $current - 86400 * 1) { 
		 $str = (int)(($current - $timestamp) / 86400) . "일전"; 
	  } else if ($timestamp <= $current - 3600 * 1) { 
		 $str = (int)(($current - $timestamp) / 3600) . "시간전"; 
	  } else if ($timestamp <= $current - 60 * 1) { 
		 $str = (int)(($current - $timestamp) / 60) . "분전"; 
	  } else { 
		 $str = (int)($current - $timestamp) . "초전"; 
	  } 
	  
	  return $str;
}

######### 남은날짜 time 함수 
function remain($d) {
    $day = strtotime($d);
    return intval((time() - $day)/86400);
}

######### d-day 몇일전인가?
function d_day($d) {
    if(date("Y-m-d") == $d){
		$cal_day = 0;
	} else {
		$d_day = $timestamp = strtotime($d);
		$cal_day = intval(($d_day-time())/86400);
		$cal_day = $cal_day+1;
	}
	return $cal_day;
}

######### 숫자 -> 금액표시 
function get_money_won($k) {
	   $len = strlen ($k);
       $len_ahr = ceil($len/4);
       $len_skanwj = $len%4;
       $a=0;
       for ($i=1;$i<=$len_ahr;$i++) {
		   $sub = array("원", "만", "억","조");
           $a=$a-4;
			if ($i < $len_ahr) {
                if (substr($k, $a, 4) !="0000") {
                    $str=substr($k, $a, 4)+0;
                    $k2 = number_format($str)."".$sub[$i-1].$k2;
                }
            }else {
               if ($len_skanwj==0) {
                  $len_skanwj=4;
               }
               $k2 = number_format(substr($k, $a,$len_skanwj))."".$sub[$i-1].$k2;
            }
       }
	  
      $ch = strpos($k2,"원");
      if ($ch == 0) {
         $k2=$k2."원";
      }
       return $k2;
	}

############### 액션로그 dB 저장 
function set_auth_log($member_idx,$board_tbname,$board_code="",$board_idx,$log_content,$board_wdate=""){
	$query_view_cnt = "insert into auth_log set";
	$query_view_cnt .= " member_idx = '".$member_idx."', ";
	$query_view_cnt .= " board_tbname = '".$board_tbname."', ";
	$query_view_cnt .= " board_code = '".$board_code."', ";
	$query_view_cnt .= " board_idx = '".$board_idx."', ";
	$query_view_cnt .= " log_content = '".$log_content."', ";
	$query_view_cnt .= " board_wdate = '".$board_wdate."', ";
	$query_view_cnt .= " wdate = now() ";
	$result_view_cnt = mysqli_query($GLOBALS['gconnet'],$query_view_cnt);
}

############### 특정 일자가 시작되는 주의 첫번째 날자 구하기 (이번주는 X 일 부터 ~ 
function weekOfMonth($vdate) {
	$mydate = strtotime("monday this week, +2 days", strtotime($vdate)); //수요일을 기준으로 "wednesday this week"으로 해도 될 듯...
	$month1 = date("m", $mydate);   
	//$rvalue = (int)$month1 ."월 ";  //리턴값
	$firstOfMonth = strtotime(date("Y-m-01", $mydate));  //그달의 첫날

	//일요일을 한주의 시작으로 간주하는 경우 만일 그 달의 시작일이 일요일이면 이전 주(달)로 계산되기 때문에 임시로 하루를 증가시킴. (심지어 2017-01-01(일)은 2016년 12월로 계산되기도 함)

	if(date("w",$firstOfMonth)==0) $firstOfMonth = strtotime("tomorrow",$firstOfMonth);
	$weekOfMonth = intval(date("W",$mydate)) - intval(date("W",$firstOfMonth)) + 1; //전체주수-그달 첫날의 주수 +1
	// 그달의 시작일이 수요일 이후 즉, 목금토일 때는 한주를 줄임

	if(date("w",$firstOfMonth) > 3) $weekOfMonth -= 1; 
	$rvalue .= $weekOfMonth;
	return $rvalue;
}

/**
 * 파일로그남기기
 *
 * @param   : Type (dir명, 날짜뒤에 붙일 파일명, 로그남길 데이터)
 * @return
 */
function logs($type, $filename, $data, $title = '')
{

    $dir = '/var/www/html/logs/' . $type . '/';

    if (!is_dir($dir)) {
        mkdir($dir, '0777');
        chmod($dir, 0777);
    }
    $filename = date('Y-m-d') . '_' . $filename;

    $titleTxt = (!empty($title)) ? '======> ' . $title . "(" . date('Y-m-d H:i:s') .") \r\n" : '';

    file_put_contents($dir . $filename, "\r\n" . $titleTxt . print_r($data, true), FILE_APPEND);
}

/**
 *
*/
function makeUrlEncode($code)
{
	$encode = rawurlencode(base64_encode($code));
    return $_SERVER['HTTP_HOST'] . '/email_auth.php?auth=' .$encode;
}

/**
 * 이메일 별표시 하기
*/
function hideEmail($email='')
{

	$aEmail = explode("@", $email);
	$strEmail_1 = str_pad(substr($aEmail['0'], 0, strlen($aEmail['0'])/2), strlen($aEmail['0']), '*');

	return $strEmail_1 . '@' . $aEmail['1'];
}

/**
 * Y값을 O/X로 리턴하기
 * type == Y 이면 O/X
 * type == N 이면 예/아니오
*/
function getAnswerTxt($params='', $type='Y')
{
	if ($type == 'Y') {
		if (!empty($params) && $params == 'Y') {
			return 'O';
		} else {
			return 'X';
		}
	} else {
		if (!empty($params) && $params == 'Y') {
			return '예';
		} else {
			return '아니오';
		}
	}
}

// 회원사진 이미지 
function get_member_photo($member_idx,$member_type=""){ 
	$point_sql = "select file_chg from member_info where 1 and idx='".$member_idx."'"; 
	//echo $point_sql."<br>";
	$point_query = mysqli_query($GLOBALS['gconnet'],$point_sql);
	if(mysqli_num_rows($point_query) == 0){
		if($member_type == "PAT"){
			//$default_mem_photo = "/images/80_35.png";
			$default_mem_photo = "/images/icn-user-60.png";
		} elseif($member_type == "GEN"){
			$default_mem_photo = "/images/icn-user-60.png";
		} 
	} else {
		$point_row = mysqli_fetch_array($point_query);
		if(!$point_row['file_chg']){
			if($member_type == "PAT"){
				//$default_mem_photo = "/images/80_35.png";
				$default_mem_photo = "/images/icn-user-60.png";
			} elseif($member_type == "GEN"){
				$default_mem_photo = "/images/icn-user-60.png";
			} 
		} else {
			$default_mem_photo = "/upload_file/member/img_thumb/".$point_row['file_chg'];
		}
	}
	
	return $default_mem_photo;
}

function get_star_avg($target_idx,$target_tbname,$percent=""){
	$query_star_sub = "select sum(after_point) as after_point from contents_estimate_info where 1 and del_yn='N'";
	if($target_idx){
		$query_star_sub .= " and contents_idx = '".$target_idx."'";
	}
	if($target_tbname){
		$query_star_sub .= " and contents_tbname = '".$target_tbname."'";
	}
	//echo $query_star_sub."<br>";
	$result_star_sub = mysqli_query($GLOBALS['gconnet'],$query_star_sub);
	$row_star_sub = mysqli_fetch_array($result_star_sub);

	$query_star_cnt = "select idx from contents_estimate_info where 1 and del_yn='N'";
	if($target_idx){
		$query_star_cnt .= " and contents_idx = '".$target_idx."'";
	}
	if($target_tbname){
		$query_star_cnt .= " and contents_tbname = '".$target_tbname."'";
	}
	$result_star_cnt = mysqli_query($GLOBALS['gconnet'],$query_star_cnt);
	$num_sub = mysqli_num_rows($result_star_cnt);

	//echo $row_star_sub[after_point]." / ".$num_sub."<br>";

	if($num_sub == 0){
		$avg_star = 0;
	} else {
		$avg_star = round($row_star_sub[after_point]/$num_sub,1);	
	}

	if($percent == "Y"){
		$avg_star = $avg_star*20;
	}

	return $avg_star;
}

// 2022-0921 deep singup_terms
function terms($idx, $lang){
	//1.이용약관, 2.개인정보정책 [kor],[eng] 
	global $gconnet; 
	$query = "SELECT * FROM signup_terms WHERE idx='$idx' AND lang='$lang'";
	$result = mysqli_query($gconnet, $query);
	$row = mysqli_fetch_array($result);
	//return print_r($row['body']);
	return $row['body'];
}

//random number 6자리 생성
function random_num($size) {
    $alpha_key = '';
    
    $length = $size;

    $key = '';
    $keys = range(0, 9);

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $alpha_key . $key;
}

function get_payment_method($option){
	if ($option == "card_isp"){
		$name = "신용카드";
	} elseif ($option == "bank_iche"){
		$name = "계좌이체";
	} elseif ($option == "pay_virt"){
		$name = "가상계좌";
	} elseif ($option == "handphone"){
		$name = "휴대폰결제";
	} elseif ($option == "refund"){
		$name = "적립금결제";
	} elseif ($option == "dirbank"){
		$name = "무통장";
	}
	return $name;
}

function get_card_name($code){
	switch($code){
		case "01": $name = "외환"; break;
		case "03": $name = "롯데"; break;
		case "04": $name = "현대"; break;
		case "06": $name = "국민"; break;
		case "11": $name = "BC"; break;
		case "12": $name = "삼성"; break;
		case "13": $name = "LG"; break;
		case "14": $name = "신한"; break;
		case "15": $name = "한미"; break;
		case "16": $name = "NH"; break;
		case "17": $name = "하나 SK"; break;
		case "21": $name = "해외비자"; break;
		case "22": $name = "해외마스터"; break;
		case "23": $name = "JCB"; break;
		case "24": $name = "해외아멕스"; break;
		case "25": $name = "해외다이너스"; break;
		default: $name = "";
	}
	return $name;
}

function get_bank_name_code($code){
	switch($code){
		case "03": $name = "기업은행"; break;
		case "04": $name = "국민은행"; break;
		case "05": $name = "하나은행(구 외환)"; break;
		case "07": $name = "수협중앙회"; break;
		case "11": $name = "농협중앙회"; break;
		case "20": $name = "우리은행"; break;
		case "23": $name = "SC제일은행"; break;
		case "31": $name = "대구은행"; break;
		case "32": $name = "부산은행"; break;
		case "34": $name = "광주은행"; break;
		case "37": $name = "전북은행"; break;
		case "39": $name = "경남은행"; break;
		case "53": $name = "한국씨티은행"; break;
		case "71": $name = "우체국"; break;
		case "81": $name = "하나은행"; break;
		case "88": $name = "통합신항은행(신한, 조흥은행)"; break;
		case "D1": $name = "유안타증권(구 동양증권)"; break;
		case "D2": $name = "현대증권"; break;
		case "D3": $name = "미래에셋증권"; break;
		case "D4": $name = "한국투자증권"; break;
		case "D5": $name = "우리투자증권"; break;
		case "D6": $name = "하이투자증권"; break;
		case "D7": $name = "HMC투자증권"; break;
		case "D8": $name = "SK증권"; break;
		case "D9": $name = "대신증권"; break;
		case "DA": $name = "하나대투증권"; break;
		case "DB": $name = "굿모닝신한증권"; break;
		case "DC": $name = "동부증권"; break;
		case "DD": $name = "유진투자증권"; break;
		case "DE": $name = "메리츠증권"; break;
		case "DF": $name = "신영증권"; break;
		case "27": $name = "한국씨티은행(한미은행)"; break;
		default: $name = "";
	}
	return $name;
    //return $code;
}

function get_order_status($status){
	switch ($status) {
		case "pre" : 
		$name = "입금대기";
		break;
		case "com" : 
		$name = "결제완료";
		break;
		case "can" : 
		$name = "취소완료";
		break;
		case "reing" : 
		$name = "취소신청";
		break;
	}
	return $name;
}

/** 송기호 (2022.10.05) */
function format_phone_numeric($number) : string{
	$number = preg_replace("/[^0-9]*/", "", $number);
	$meter = "";
	switch(strlen($number)){
		case 11: //010
			$meter = implode('-', [
				substr($number, 0, 3),
				substr($number, 3, 4),
				substr($number, 7, 11)
			]);
			break;
		case 9: //0n
			$meter = implode('-', [
				substr($number, 0, 2),
				substr($number, 2, 3),
				substr($number, 5, 9)
			]);
			break;
	}
	return $meter;
}

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function get_yakkwan($cate_code1){
	global $inc_partner_idx,$inc_partner_id;
	
	//echo "inc_partner_idx = ".$inc_partner_idx."<br>";
	if($inc_partner_idx != "1"){
		
		$current_cnt_query = "select content from delv_guide where 1";
		if($cate_code1){
			$current_cnt_query .= " and cate_code1 = '".$cate_code1."'";
		}
		$current_cnt_query .= " and admin_idx='".$inc_partner_idx."' order by idx desc limit 0,1";
		$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
		
		if(mysqli_num_rows($current_cnt_result) == 0){
			$current_cnt_query = "select content from delv_guide where 1";
			if($cate_code1){
				$current_cnt_query .= " and cate_code1 = '".$cate_code1."'";
			}
			$current_cnt_query .= " and admin_idx='1' order by idx desc limit 0,1";
			$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
			$row = mysqli_fetch_array($current_cnt_result);
			$current_cnt = $row[content];	
		} else {
			$row = mysqli_fetch_array($current_cnt_result);
			$current_cnt = $row[content];	
		}
		
	} else {
		$current_cnt_query = "select content from delv_guide where 1";
		if($cate_code1){
			$current_cnt_query .= " and cate_code1 = '".$cate_code1."'";
		}
		$current_cnt_query .= " and admin_idx='1' order by idx desc limit 0,1";
		$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
		$row = mysqli_fetch_array($current_cnt_result);
		$current_cnt = $row[content];	
	}
		
	return $current_cnt;
}

function get_yakkwan_img($cate_code1){
	global $inc_partner_idx,$inc_partner_id;
	
	if($inc_partner_idx != "1"){
		
		$current_cnt_query = "select idx from delv_guide where 1";
		if($cate_code1){
			$current_cnt_query .= " and cate_code1 = '".$cate_code1."'";
		}
		$current_cnt_query .= " and admin_idx='".$inc_partner_idx."' order by idx desc limit 0,1";
		$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
		
		if(mysqli_num_rows($current_cnt_result) == 0){
			$current_cnt_query = "select idx from delv_guide where 1";
			if($cate_code1){
				$current_cnt_query .= " and cate_code1 = '".$cate_code1."'";
			}
			$current_cnt_query .= " and admin_idx='1' order by idx desc limit 0,1";
			$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
			$row = mysqli_fetch_array($current_cnt_result);
			$current_cnt = $row['idx'];	
		} else {
			$row = mysqli_fetch_array($current_cnt_result);
			$current_cnt = $row['idx'];	
		}
		
	} else {
		$current_cnt_query = "select idx from delv_guide where 1";
		if($cate_code1){
			$current_cnt_query .= " and cate_code1 = '".$cate_code1."'";
		}
		$current_cnt_query .= " and admin_idx='1' order by idx desc limit 0,1";
		$current_cnt_result = mysqli_query($GLOBALS['gconnet'],$current_cnt_query);
		$row = mysqli_fetch_array($current_cnt_result);
		$current_cnt = $row['idx'];	
	}

	$sql_file = "select file_chg from board_file where 1=1 and board_tbname='delv_guide' and board_code = '".$cate_code1."' and board_idx='".$current_cnt."' order by idx desc limit 0,1";
	$query_file = mysqli_query($GLOBALS['gconnet'],$sql_file);
	$row_file = mysqli_fetch_array($query_file);
	
	return $row_file['file_chg'];
}
?>
