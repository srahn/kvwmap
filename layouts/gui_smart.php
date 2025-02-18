<html lang="de">
	<head>
		<title><? echo TITLE; ?></title>
		<? include(SNIPPETS . 'gui_head.php'); ?>
		<link rel="stylesheet" href="layouts/css/smart.css" type="text/css">
	</head>
	<body onload="onload_functions();">
		<? include(LAYOUTPATH.'snippets/SVGvars_defs.php');	?>
		<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0">
		<defs>
			<? echo $SVGvars_defs; ?>
		</defs>
		</svg>
		<div id="waitingdiv" style="position: absolute;height: 100%; width: 100%; display:none; z-index: 1000000; text-align: center">
			<div style="position: absolute;  top: 50%; left: 50%; transform: translate(-50%,-50%);">
				<i class="fa fa-spinner fa-7x wobble-fix spinner"></i>
			</div>
		</div>
		<div id="vorschau" style="pointer-events:none; box-shadow: 12px 10px 14px #777;z-index: 1000000; position: fixed; right:10px; top:5px; ">
			<img id="preview_img" style="max-height: 940px" src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
		</div>
		<a name="oben"></a>
		<div onclick="remove_calendar();">
			<form name="GUI" enctype="multipart/form-data" method="post" action="index.php" id="GUI">
				<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?: '' ?>">
				<div id="message_box"></div>		<!-- muss innerhalb des form stehen -->
				<div id="container_paint" style="height:100%; position: relative; <? echo ($this->main == 'map.php' ? 'overflow: hidden' : ''); ?>">
					<!-- overflow wird für rausfliegende Legende benötigt und height:100% für den Box-Shadow unter der MapFunctionsBar und Legende -->
					<script type="text/javascript">
						window.name = 'root';
						currentform = document.GUI;
						<? $this->currentform = 'document.GUI'; ?>
					</script><?php
					$this->debug->write("<br>GUI->main: <b>" . $this->main . "</b> in gui_smart.php", 4, false);
					if ($this->main == 'map.php') {
						$this->main = 'map_smart.php';
						$this->debug->write("<br>Change GUI->main to: <b>" . $this->main . "</b> in gui_smart.php", 4, false);
					}
					if (file_exists($this->main)) {
						$this->debug->write("<br>Include <b>" . $this->main . "</b> in gui_smart.php", 4, false);
						include($this->main); # Pluginviews
					}
					else {
						$this->debug->write("<br>Include <b>" . LAYOUTPATH . "snippets/" . $this->main . "</b> in gui_smart.php", 4, false);
						include(LAYOUTPATH . "snippets/" . $this->main);		# normale snippets
					} ?>
				</div>
				<div id="header_menue"><?
					global $supportedLanguages;
					include(LAYOUTPATH . 'languages/header_' . $this->user->rolle->language . '.php'); 
					include(LAYOUTPATH . 'snippets/headermenues.php'); ?>
				</div>
				<input type="hidden" name="overlayx" value="<? echo $this->user->rolle->overlayx; ?>">
				<input type="hidden" name="overlayy" value="<? echo $this->user->rolle->overlayy; ?>">
				<input type="hidden" name="browserwidth">
				<input type="hidden" name="browserheight">
				<input type="hidden" name="stopnavigation" value="0">
				<input type="hidden" name="gle_changed" value="">
				<input type="hidden" name="mime_type" value="">
				<input type="hidden" name="window_type" value="">
			</form>
		</div>
	</body>
</html>