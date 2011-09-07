<?php
// Easy PHP Calendar
// Version 6
// Copyright 2001-2006 NashTech, Inc.
// http://www.EasyPHPCalendar.com

// SET ERROR REPORTING
error_reporting(E_ALL ^ E_NOTICE);

// DISABLE DEMO
require_once("config.inc.php");
if ($disableDemo==1) {
  exit;
  }

// VERIFY LICENSE HAS BEEN ENTERED
require_once("license.php");
if ($licenseTsid=="" || $licenseSite=="") {
  ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Easy PHP Calendar - Welcome!</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #666666;
}
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #990000;
}
-->
</style>
</head>
<body>
</body>
</html>

    <?php
  exit;
  }

// SHOW DEMO
require("demo.php");
?>
  </p>
  