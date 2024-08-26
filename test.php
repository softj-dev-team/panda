<?php
$sms_content = "(광고)\n\n무료거부 0808709813";
$sms_content_length = mb_strwidth($sms_content, "UTF-8");
var_dump($sms_content_length);
