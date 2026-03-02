<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<? include('funktionen/gui_defs.php'); ?>
  <script type="text/javascript" src="funktionen/gui_functions.js?v=139"></script>
  <script type="text/javascript" src="funktionen/calendar.js?v=3"></script>
  <script type="text/javascript" src="funktionen/keyfunctions.js"></script>
  <script type="text/javascript" src="<? echo JQUERY_PATH; ?>jquery.min.js"></script><?
if (true) { ?>
	<script src="<? echo PROJ4JS_PATH; ?>proj4.js"></script><?
} ?>
  <link rel="stylesheet" href="<? echo FONTAWESOME_PATH; ?>css/font-awesome.min.css" type="text/css">
  <link rel="shortcut icon" href="<? echo CUSTOM_PATH; ?>wappen/favicon.ico">
  <style>
    <? include(LAYOUTPATH.'css/main.css.php'); ?>
  </style>
<?

if (file_exists(LAYOUTPATH . 'css/' . basename($this->main, ".php") . '.css')) { ?>
  <style>
    <? include(LAYOUTPATH . 'css/' . basename($this->main, ".php") . '.css'); ?>
  </style>
<?
}

if (defined('CUSTOM_STYLE') AND CUSTOM_STYLE != '') { ?>
  <style>
    <? include(CUSTOM_STYLE); ?>
  </style>
<?
}

$custom_snippets_style = INSTALLPATH . WWWROOT . APPLVERSION . CUSTOM_PATH . 'layouts/css/' . basename($this->main, ".php") . '.css';
if (file_exists($custom_snippets_style)) { ?>
  <style>
    <? include($custom_snippets_style); ?>
  </style>
<?
}

if (isset($this->Stelle) AND isset($this->Stelle->style) AND $this->Stelle->style != '') { ?>
  <link rel="stylesheet" href="<? echo $this->Stelle->style; ?>"><?
}
?>