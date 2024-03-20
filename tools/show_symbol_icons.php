<?php
	include('../config.php');
	include('../class/kvwmap.php'); ?>
<html>
	<head>
		<title>Icons from Symbolset</title>
	</head>
	<body><?
		$GUI = new GUI("", "layouts/css/main.css.php", "html");
		$symbols = $GUI->get_symbol_list();

		echo implode(
			'<br>',
			array_map(
				function($symbol) {
					$html = '<img
						src="' . IMAGEURL . basename($symbol['image']) . '"
						border="1"
						style="
							margin-right: 7px;
							margin-bottom: 2px;
							vertical-align:middle
						"' . ($symbol['bild'] ? '
						onmouseover="this.src = \'' . URL . APPLVERSION . CUSTOM_PATH . 'symbols/' . $symbol['bild'] . '\';this.width=\'250\'"
						onmouseout="this.src = \'' . IMAGEURL . basename($symbol['image']) . '\';this.width=\'35\'"' : '') . '
						width="35"
					>' . $symbol['id'] . '. ' . $symbol['value'] . ($symbol['bild'] != '' ? ' (' . $symbol['bild'] . ')' : '') . ' Symboltyp: ' . $symbol['type'];
					return $html;
				},
				$symbols
			)
		); ?>

		<p>
		<!--select name="options"><?
			echo implode(
				'',
				array_map(
					function($symbol) {
						$html = '<option class="icon-' . $symbol['id'] . '" value="' . $symbol['id'] . '" style="background-image:url(' . IMAGEURL . basename($symbol['image']) . ');">' . $symbol['id'] . '. ' . $symbol['value'] . '</option>';
						return $html;
					},
					$symbols
				)
			); ?>
		</select-->
	</body>
</html>