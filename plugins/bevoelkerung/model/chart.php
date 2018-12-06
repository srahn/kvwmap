<?php
define('TERN_CHART_PADDING', 0.09);

function drawAxes($image, $cornerPoints, $majorAxisColor, $minorAxisColor) {
  $pointA = $cornerPoints[0];
  $pointB = $cornerPoints[1];
  $pointC = $cornerPoints[2];
  $vectorA = array(x => $pointB[x]-$pointA[x], y => $pointB[y]-$pointA[y]);
  $vectorB = array(x => $pointC[x]-$pointB[x], y => $pointC[y]-$pointB[y]);
  $vectorC = array(x => $pointA[x]-$pointC[x], y => $pointA[y]-$pointC[y]);

  # draw major axes
  imagesetstyle($image,array($majorAxisColor));
  imagesetthickness($image,2);
  imagepolygon(
    $image,
    array(
      $pointC[x], $pointC[y], $pointB[x], $pointB[y], $pointA[x], $pointA[y]
    ),
    3,
    IMG_COLOR_STYLED
  );

  # draw minor axes
  imagesetstyle($image,array($minorAxisColor, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT));
  imagesetthickness($image,1);
  for ($idx = 1; $idx < 10; $idx++) {
    imageline(
      $image,
      $pointA[x] + $vectorA[x] / 10 * $idx, $pointA[y] + $vectorA[y] / 10 * $idx,
      $pointA[x] - $vectorC[x] / 10 * $idx, $pointA[y] - $vectorC[y] / 10 * $idx,
      IMG_COLOR_STYLED
    );
    imageline(
      $image,
      $pointB[x] + $vectorB[x] / 10 * $idx, $pointB[y] + $vectorB[y] / 10 * $idx,
      $pointB[x] - $vectorA[x] / 10 * $idx, $pointB[y] - $vectorA[y] / 10 * $idx,
      IMG_COLOR_STYLED
    );
    imageline(
      $image,
      $pointC[x] + $vectorC[x] / 10 * $idx, $pointC[y] + $vectorC[y] / 10 * $idx,
      $pointC[x] - $vectorB[x] / 10 * $idx, $pointC[y] - $vectorB[y] / 10 * $idx,
      IMG_COLOR_STYLED
    );
  }

};

function drawAxisScales($image, $color, $font){
  $fontwidth = imagefontwidth($font);
  $fontheight = imagefontheight($font);
  // scales A-axis
  $posY = imagesy($image) * 0.85 - $fontheight;
  $posX = imagesx($image) * 0.1 - $fontwidth;
  imagestring($image, $font, $posX, $posY, '0', $color);
  $posX = imagesx($image) * 0.9 - $fontwidth;
  imagestring($image, $font, $posX, $posY, '100%', $color);
  // scales B-axis
  $posY = imagesy($image) * 0.8 - $fontheight*1.5;
  $posX = imagesx($image) * 0.9 + $fontwidth;
  imagestring($image, $font, $posX, $posY, '0', $color);
  $posX = imagesx($image) * 0.5 + $fontwidth;
  $posY = imagesy($image) * 0.1 - $fontheight;
  imagestring($image, $font, $posX, $posY, '100%', $color);
  // scales C-axis
  $posY = imagesy($image) * 0.8 - $fontheight*1.5;
  $posX = imagesx($image) * 0.07 - $fontwidth*3;
  imagestring($image, $font, $posX, $posY, '100%', $color);
  $posX = imagesx($image) * 0.5 - $fontwidth*2;
  $posY = imagesy($image) * 0.1 - $fontheight;
  imagestring($image, $font, $posX, $posY, '0', $color);
};

function drawAxisLabels($image, $labels, $color, $font){
  $labelA = $labels[0];
  $labelB = $labels[1];
  $labelC = $labels[2];
  $fontwidth = imagefontwidth($font);
  $fontheight = imagefontheight($font);
  $padding = imagesx($image) * TERN_CHART_PADDING;
  // label A-axis
  $posX = (imagesx($image) - $fontwidth * strlen($labelA)) / 2;
  $posY = imagesy($image) - $padding - $fontheight;
  imagestring($image, $font, $posX, $posY, $labelA, $color);
  // label B-axis
  $posX = imagesx($image) - $padding - $fontheight / 2;
  $posY = (imagesy($image) + $fontwidth * strlen($labelB)) / 2 - $padding;
  imagestringup($image, $font, $posX, $posY, $labelB, $color);
  // label C-axis
  $posX = $padding - $fontheight / 2;
  $posY = (imagesy($image) + $fontwidth * strlen($labelC)) / 2 - $padding;
  imagestringup($image, $font, $posX, $posY, $labelC, $color);
};

function drawLegend($image, $levels, $value_colors, $legende_color, $font){
	$x = 400;
	$y = 40;
	foreach($levels AS $i => $level) {
		imagefilledellipse($image, $x, $y + 5, 6, 6, $value_colors[$i]);
		imagestring($image, $font, $x + 10, $y, $level['legend_name'], $legende_color);
		$y += 12;
	}
};

function drawTitle($image, $title, $color, $font) {
  $fontwidth = imagefontwidth($font);
  $fontheight = imagefontheight($font);
  $titlewidth = $fontwidth * strlen($title);
  if ($titlewidth > imagesx($image)) {
    $ratio = imagesx($image) / $titlewidth;
    $title = substr($title, 0, strlen($title)*$ratio - 4).'...';
  }
  $posX = (imagesx($image) - $fontwidth * strlen($title)) / 2;
  $posY = imagesy($image) - 1.5 * $fontheight;
  imagestring($image, $font, $posX, $posY, utf8_decode($title), $color);
};

function colorFromName($image,$colorName){
  $namedColors = array(
    'black'     => array(0,0,0),
    'white'     => array(255,255,255),
    'gray25'    => array(64,64,64),
    'darkgray'  => array(64,64,64),
    'gray50'    => array(128,128,128),
    'gray'      => array(128,128,128),
    'gray75'    => array(192,192,192),
    'silver'    => array(192,192,192),
    'lightgray' => array(192,192,192),
    'yellow'    => array(255,255,0),
    'orange'    => array(255,165,0),
    'red'       => array(255,0,0),
    'lime'      => array(0,255,0),
    'green'     => array(0,128,0),
    'cyan'      => array(0,255,255),
    'aqua'      => array(0,255,255),
    'blue'      => array(0,0,255),
    'magenta'   => array(255,0,255),
    'fuchsia'   => array(255,0,255),
    'teal'      => array(0,128,128),
    'purple'    => array(128,0,128),
    'navy'      => array(0,0,128),
    'olive'     => array(128,128,0),
    'maroon'    => array(128,0,0)
  );

  if (substr($colorName,0,1) == '#') {
    $color = array(hexdec(substr($colorName,1,2)),hexdec(substr($colorName,3,2)),hexdec(substr($colorName,5,2)));
  } else {
    $color = $namedColors[$colorName];
  }
  return imagecolorallocate($image, $color[0], $color[1], $color[2]);
}

function valueMarkerFromName($markerName){
  $markerNames = array(
    'asterisk' => '*',
    'plus'     => '+',
    'bullet'   => '\u25cf',
    'circle'   => '\u25cb',
    'diamond'  => '\u2666',
    'square'   => "\u25a0"
  );
  return $markerNames[$markerName];
}

function checkValue($value){
  $epsilon = 0.1;
  $result = ($value[0]+$value[1]+$value[2]-100 < $epsilon);
	return $result;
};

function drawTernaryChart($path, $labels, $levels, $title = "", $options=array()) {
	#echo '<br>Draw Ternary Cahrt: ' . $path;
  $size = $options['size'] ?  $options['size'] : 400;
  $im = imagecreatetruecolor ($size,$size);

  $bgColor    = colorFromName($im, $options['backgoundColor'] ? $options['backgoundColor'] : 'white');
  $titleColor = colorFromName($im, $options['titleColor']     ? $options['titleColor']     : 'black');
  $axisColor  = colorFromName($im, $options['axisColor']      ? $options['axisColor']      : 'black');
  $labelColor = colorFromName($im, $options['labelColor']     ? $options['labelColor']     : 'gray75');
  $valueColors = array(
		colorFromName($im, $options['valueColor1']     ? $options['valueColor1']     : 'green'),
		colorFromName($im, $options['valueColor2']     ? $options['valueColor2']     : 'blue'),
		colorFromName($im, $options['valueColor3']     ? $options['valueColor3']     : 'red')
	);
  $minorAxisColor = $options['minorAxisColor'] ? colorFromName($im, $options['minorAxisColor']) : $axisColor;
  $markerSize = $options['markerSize'] ? $options['markerSize'] : 5;

  imagefill($im,0,0,$bgColor);

  # calculate chart geometry
  $padding = $size * TERN_CHART_PADDING;
  $effectiveHeight = ($size - $padding - $padding) * sqrt(3) / 2;
  $pointA  = array(x => $padding, y => $effectiveHeight+$padding);
  $pointB  = array(x => $size - $padding, y => $effectiveHeight+$padding);
  $pointC  = array(x => $size / 2, y => $padding);

  # draw axes
  drawAxes($im,array($pointA,$pointB, $pointC), $axisColor, $minorAxisColor);

  # draw title
  if (strlen(title) > 0) drawTitle($im, $title, $titleColor, 5);

  # draw labels and scales
  drawAxisLabels($im, $labels, $labelColor, 3);
  drawAxisScales($im, $axisColor, 3);
	drawLegend($im, $levels, $valueColors, $titleColor, 3);

	# plot values
	$vectorA = array(x => $pointB[x]-$pointA[x], y => $pointB[y]-$pointA[y]);
	$vectorC = array(x => $pointA[x]-$pointC[x], y => $pointA[y]-$pointC[y]);
	$pos = array();
	foreach ($levels AS $i => $level) {
	  foreach ($level['values'] AS $value) {
	    if (!checkValue($value)) continue;
	    $posX = $pointA[x] + $vectorA[x] / 100 * $value[0] - $vectorC[x] / 100 * $value[1];
	    $posY = $pointA[y] + $vectorA[y] / 100 * $value[0] - $vectorC[y] / 100 * $value[1];
			$pos[] = array('x' => $posX, 'y' => $posY, 'label' => $value[3]);
	    imagefilledellipse($im, $posX, $posY, $markerSize, $markerSize, $valueColors[$i]);
			#echo '<br>Draw Point x: ' . $posX . ' y: ' . $posY . ' label: ' . $value[3];
		}
	}

  # save the chart as png
  imagepng($im,$path);
  imagedestroy($im);
  return $pos;
};

function writeImageMap($areas_file, $areas) {
	$fp = fopen($areas_file, 'w');
	fwrite($fp, "<map name=\"" . basename($areas_file, '.html') . "\">" . PHP_EOL);
	foreach($areas AS $area) {
		fwrite($fp, "<area
			shape=\"circle\"
			title=\"" . $area['label'] . "\"
			alt=\"" . $area['label'] . "\"
			coords=\"" . round($area['x']) . ", " . round($area['y']) . ", 2\"
		>" . PHP_EOL);
		}
	fwrite($fp, "</map>" . PHP_EOL);
	fclose($fp);
}
?>