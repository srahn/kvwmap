<?php
	include('../config.php'); ?>
<html>
	<head>
		<title>Icons from Symbolset</title>
	</head>
	<body><?
		$mapfile		 = ((array_key_exists('mapfile',		 $_REQUEST) AND $_REQUEST['mapfile']		 != '') ? $_REQUEST['mapfile']		 : DEFAULTMAPFILE);
		$symbolset	 = ((array_key_exists('symbolset',	 $_REQUEST) AND $_REQUEST['symbolset']	 != '') ? $_REQUEST['symbolset']	 : SYMBOLSET);
		$savemapfile = ((array_key_exists('savemapfile', $_REQUEST) AND $_REQUEST['savemapfile'] != '') ? $_REQUEST['savemapfile'] : SAVEMAPFILE);
		if (!file_exists($symbolset)) {
			echo 'Symboldatei: ' . $symbolset . ' nicht gefunden.';
			exit;
		}
		echo 'Symbolset: ' . $symbolset . '<br>';
		$path_parts = pathinfo($symbolset);
		define('SYMBOLPATH', $path_parts['dirname']);
		$map = new mapObj($mapfile);
		$map->setSymbolSet($symbolset);
		$map->setFontSet(FONTSET);
		$numSymbols = $map->getNumSymbols();
		$symbols = array();
		$layer_point	 = ms_newLayerObj($map);
		$layer_line		 = ms_newLayerObj($map);
		$layer_polygon = ms_newLayerObj($map);
		$layer_point->set(	'type', MS_LAYER_POINT);
		$layer_line->set(		'type', MS_LAYER_LINE);
		$layer_polygon->set('type', MS_LAYER_POLYGON);
		for ($symbolid = 1; $symbolid < $numSymbols; $symbolid++) {
			$symbol = $map->getSymbolObjectById($symbolid);
			switch ($symbol->type) {
				case 1005 : {
					$class = new classObj($layer_polygon);
					$symbolnr = $symbolid;
					$size = 6;
					$width = 1;
				} break;
				case 1002 : {
					$class = new classObj($layer_line);
					$symbolnr = 0;
					$width = 2;
				} break;
				default : {
					$class = new classObj($layer_point);
					$symbolnr = $symbolid;
					$size = 25;
					$width = 1;
				}
			}
			$class->set('name', 'testClass' . $symbolid);
			$style = new styleObj($class);
			$style->set('symbol', $symbolnr);
			$style->set('size', $size);
			$style->set('width', $width);
			$style->color->setRGB(35, 109, 191);
			$style->outlinecolor->setRGB(0, 0, 0);
			$img = $class->createLegendIcon(30, 30);
			$img->saveImage(IMAGEPATH . 'legende_' . $symbolid . '.png');
			$symbols[] = array(
				'id' => $symbolid,
				'name' => $symbol->name,
				'type' => $symbol->type,
				'bild' => $symbol->imagepath,
				'icon' => 'legende_' . $symbolid . '.png'
			);
		}
		echo implode(
			'<br>',
			array_map(
				function($symbol) {
					$html = '<img src="' . IMAGEURL . $symbol['icon'] . '" border="1" style="
						margin-right: 7px;
						margin-bottom: 2px;
						vertical-align:middle
						">' . $symbol['id'] . '. ' . $symbol['name'] . ($symbol['bild'] != '' ? ' (' . $symbol['bild'] . ')' : '') . ' Symboltyp: ' . $symbol['type'];
					return $html;
				},
				$symbols
			)
		); ?>
		<!--p>
		<select name="options"><?
			echo implode(
				'',
				array_map(
					function($symbol) {
						$html = '<option class="icon-' . $symbol['id'] . '" value="' . $symbol['id'] . '" style="background-image:url(' . IMAGEURL . $symbol['icon'] . ');">' . $symbol['id'] . '. ' . $symbol['name'] . '</option>';
						return $html;
					},
					$symbols
				)
			); ?>
		</select//-->
	</body>
</html>