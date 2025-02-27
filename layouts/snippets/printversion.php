<?
	header('Content-Type: text/html; charset=UTF-8');
	$this->currentform = 'document.GUI';
?><!DOCTYPE HTML>
<html lang="de">
	<head>
		<title><? echo TITLE; ?></TITLE>
		<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
		<link rel="shortcut icon" href="graphics/favicon.ico">
		<script>
			window.name = 'root';
			currentform = document.GUI;
		</script>
		<style><?
			include(WWWROOT . APPLVERSION . 'layouts/css/main.css.php'); ?>
			.gle_hr {
				display: none;
			}
		</style><?
		if (defined('CUSTOM_STYLE') AND CUSTOM_STYLE != '') { ?>
			<style><?
				include(WWWROOT . APPLVERSION . CUSTOM_STYLE); ?>
			</style><?
		}
		if (isset($this->Stelle) AND isset($this->Stelle->style) AND $this->Stelle->style != '') { ?>
			<link rel="stylesheet" href="<? echo $this->Stelle->style; ?>"><?
		} ?>
	</head>
	<body> <!-- leftmargin="5" topmargin="5" bgcolor="#FFFFFF" link="#FF0000" alink="#FF9999" vlink="#663333"> -->
		<!--table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" valign="top"//-->
					<form name="GUI" enctype="multipart/form-data" method="post" action="index.php" id="GUI">
						<!--table border=1 cellpadding=0 cellspacing=0 bordercolor="#FFFFFF" bordercolorlight="#CCCCCC" bordercolordark="#CCCCCC"//-->
						<table>
							<tr>
								<td align="center" valign="top" background="<? echo BG_IMAGE; ?>"><?php
									$this->debug->write("Include <b>" . LAYOUTPATH . "snippets/" . $this->main . "</b> in gui.php", 4);
									include(LAYOUTPATH . "snippets/" . $this->main); ?>
								</td>
							</tr>
						</table>
					</form>
					<!--/td>
			</tr>
		</table//-->
	</body>
</html>
