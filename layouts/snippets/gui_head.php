<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<?php
  $url_parts = explode('/', JQUERY_PATH);
  $dir_parts = explode('-' , $url_parts[count($url_parts) - 2]);
?>
<script src="<?php echo JQUERY_PATH; ?>jquery-<?php echo $dir_parts[1]; ?>.min.js"></script>
<? if(true){ ?>
	<script src="<?php echo PROJ4JS_PATH; ?>proj4.js"></script>
<? } ?>
<link rel="stylesheet" href="<?php echo FONTAWESOME_PATH; ?>css/font-awesome.min.css" type="text/css">
<? include(WWWROOT . APPLVERSION . 'funktionen/gui_functions.php'); ?>
<link rel="shortcut icon" href="graphics/wappen/favicon.ico">
<link rel="stylesheet" href="<?php echo 'layouts/'.$this->style.'?gui='.$this->user->rolle->gui; ?>"><?
if(defined('CUSTOM_STYLE') AND CUSTOM_STYLE != '') { ?>
	<link rel="stylesheet" href="<?php echo 'layouts/custom/'.CUSTOM_STYLE; ?>"><?
} ?>