<title><? echo TITLE; ?></title>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<script src="<?php echo JQUERY_PATH; ?>jquery-1.12.0.min.js"></script>
<link rel="stylesheet" href="<?php echo FONTAWESOME_PATH; ?>css/font-awesome.min.css" type="text/css">
<? include(WWWROOT . APPLVERSION . 'funktionen/gui_functions.php'); ?>
<link rel="shortcut icon" href="graphics/wappen/favicon.ico">
<link rel="stylesheet" href="<?php echo 'layouts/'.$this->style; ?>"><?
if(defined('CUSTOM_STYLE') AND CUSTOM_STYLE != '') { ?>
	<link rel="stylesheet" href="<?php echo 'layouts/custom/'.CUSTOM_STYLE; ?>"><?
} ?>