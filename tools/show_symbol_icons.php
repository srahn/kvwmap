<?php
  include('../config.php'); ?>
<html>
  <head>
    <title>Icons from Symbolset</title>
  </head>
  <body><?
    $mapfile = '/var/www/data/mapfiles/defaultmapfile_pet_dev.map';
    $savemapfile = '/var/www/logs/kvwmap_pet_dev/save_mapfile.map';
    $symbolset = ((array_key_exists('symbolset', $_REQUEST) AND $_REQUEST['symbolset'] != '') ? $_REQUEST['symbolset'] : SYMBOLSET);
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
    $layer = ms_newLayerObj($map);
    $layer->set('type', MS_LAYER_POINT);
    for ($symbolid = 1; $symbolid < $numSymbols; $symbolid++) {
      $class = new classObj($layer);
      $class->set('name', 'testClass' . $symbolid);
      $symbol = $map->getSymbolObjectById($symbolid);
      $style = new styleObj($class);
      $style->set('symbol', $symbolid);
      $style->set('size', 25);
      $style->set('width', 1);
      $style->color->setRGB(35, 109, 191);
      $style->outlinecolor->setRGB(0, 0, 0);
      $img = $class->createLegendIcon(30, 30);
      $img->saveImage(IMAGEPATH . 'legende_' . $symbolid . '.png');
      $symbols[] = array(
        'id' => $symbolid,
        'name' => $symbol->name,
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
            ">' . $symbol['id'] . '. ' . $symbol['name'] . ($symbol['bild'] != '' ? ' (' . $symbol['bild'] . ')' : '');
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