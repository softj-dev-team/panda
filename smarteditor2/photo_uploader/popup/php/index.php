<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

//include_once("./_common.php");

@include_once("./JSON.php");

if( !function_exists('json_encode') ) {
    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }
}

@ini_set('gd.jpeg_ignore_warning', 1);

/*$ym = date('ym', TB_SERVER_TIME);

$data_dir = TB_DATA_PATH.'/editor/'.$ym.'/';
$data_url = TB_DATA_URL.'/editor/'.$ym.'/';

@mkdir($data_dir, TB_DIR_PERMISSION);
@chmod($data_dir, TB_DIR_PERMISSION);*/

$data_dir = $_SERVER["DOCUMENT_ROOT"].'/smarteditor2/upload/';
$data_url = '/smarteditor2/upload/';

@mkdir($data_dir, 0777);
@chmod($data_dir, 0777);

if (version_compare(phpversion(), '5.3', '<')) // namespace는 5.3.0 이상부터 지원
	require('UploadHandler52.php');
else
	require('UploadHandler.php');

$options = array(
    'upload_dir' => $data_dir,
    'upload_url' => $data_url,
    // This option will disable creating thumbnail images and will not create that extra folder.
    // However, due to this, the images preview will not be displayed after upload
    'image_versions' => array()
);

$upload_handler = new UploadHandler($options);