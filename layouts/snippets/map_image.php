<?
	if ($jpgfile) { ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Kartenbild</title>
		<script type="text/javascript">
			function copyImageById(Id) {
				var imgs = document.createElement('img');
				imgs.src = document.getElementById(Id).src;
				var bodys = document.body;
				bodys.appendChild(imgs);
				if (document.createRange) {
					var myrange = document.createRange();
					myrange.setStartBefore(imgs);
					myrange.setEndAfter(imgs);
					myrange.selectNode(imgs);
				}
				var sel = window.getSelection();
				sel.addRange(myrange);
				var successful = document.execCommand('copy');
				bodys.removeChild(imgs);
			}
		</script>
		<style>
			body {
				font-family: SourceSansPro1, Arial, Verdana, Helvetica, sans-serif;
				font-size: 15px;
			}
			table {
				margin: auto;
			}
			td {
				padding: 4px;
			}
		</style>
	</head>
	<body style="text-align:center">
		<div style="width: <? echo $this->map->width; ?>px; margin: auto">
			<img id="mapimg" src="<? echo TEMPPATH_REL . $jpgfile; ?>" style="box-shadow:  0px 0px 14px #777;"><br><br>
			<input type="button" onclick="copyImageById('mapimg');" value="Bild kopieren">
			<h3><? echo $strShowCopyrightHeader; ?></h3><?
				echo $this->get_copyrights(); ?>
		</div>
	</body>
</html><?php
} ?>