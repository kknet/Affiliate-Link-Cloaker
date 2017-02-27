<?php

$helper = new Helper();
$js_log = $helper->post_user_info();
header("Expires: ".$helper->random_date()." GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");




