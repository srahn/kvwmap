<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<? include(WWWROOT . APPLVERSION . 'funktionen/gui_defs.php'); ?>
<script type="text/javascript" src="funktionen/gui_functions.js?v=91"></script>
<script type="text/javascript" src="funktionen/calendar.js"></script>
<script type="text/javascript" src="funktionen/keyfunctions.js"></script>
<script type="text/javascript" src="<? echo JQUERY_PATH; ?>jquery.min.js"></script><?
if (true) { ?>
	<script src="<?php echo PROJ4JS_PATH; ?>proj4.js"></script><?
} ?>
<link rel="stylesheet" href="<?php echo FONTAWESOME_PATH; ?>css/font-awesome.min.css" type="text/css">
<link rel="shortcut icon" href="<? echo CUSTOM_PATH; ?>wappen/favicon.ico">
<style>
	<? include(WWWROOT . APPLVERSION . 'layouts/css/main.css.php'); ?>
</style><?
if (defined('CUSTOM_STYLE') AND CUSTOM_STYLE != '') { ?>
	<style>
		<? include(WWWROOT . APPLVERSION . CUSTOM_STYLE); ?>
	</style><?
}
if (isset($this->Stelle) AND isset($this->Stelle->style) AND $this->Stelle->style != '') { ?>
	<link rel="stylesheet" href="<? echo $this->Stelle->style; ?>"><?
}
?>