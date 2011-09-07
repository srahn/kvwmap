<?php

class wms_request_obj {

  var $request;

  
  function wms_request_obj($request) {
    $this->request = $request;
    $link=explode('?',$this->request);
    $this->online_resource=$link[0];
    $this->param = $this->getKeyValuePairs(explode('&',$link[1]));
  }

  function getKvpsToLower($kvps) {
    foreach ($kvps as $key => $value) {
      $param[strtolower($key)]=strtolower($value);
    }
    return $param;
  }
  
  function getKeyValuePairs($kvps) {
    for ($i=0;$i<count($kvps);$i++) {
      $kvp=explode('=',$kvps[$i]);
      $param[$kvp[0]]=$kvp[1];
    }
    return $param;
  }

  function getMap() {
    $num_rows=intval(sqrt($this->num_tiles));
    # Teilen der BBOX
    $this->bbox=explode(',',$this->param['BBOX']);
    # Größe der tiles bestimmen
    $width = $this->param['WIDTH']/$num_rows;
    $height = $this->param['HEIGHT']/$num_rows;
    $width_ext = $width*$this->tile_extend/100;
    $height_ext = $height*$this->tile_extend/100;
    $width_box=($this->bbox[2]-$this->bbox[0])/$num_rows;
    $height_box=($this->bbox[3]-$this->bbox[1])/$num_rows;
    $width_box_ext = round($width_box*$this->tile_extend/100);
    $height_box_ext = round($height_box*$this->tile_extend/100);
    for ($i=0;$i<$num_rows;$i++) {
      for ($j=0;$j<$num_rows;$j++) {
        # Erzeugen der Requests der jeweiligen Tiles
        $tile_request=$this->createTileRequest($i,$j,$width,$height,$width_ext,$height_ext,$width_box,$height_box,$width_box_ext,$height_box_ext);
        #echo 'tile_request: '.$tile_request;
        # Abfragen der erweiterten Tiles
        $tile_ext=$this->getTile($tile_request);
        # Ausklippen der Tiles
        $tile=$this->clipTile($tile_ext);
        # Hinzufügen zum Gesamtbild
        $img=$this->addTile($tile);
      }
    }
    return $img;
  }
  
  function createTileRequest($i,$j,$width,$height,$width_ext,$height_ext,$width_box,$height_box,$width_box_ext,$height_box_ext) {
    # Berechnen der BBOX des Tiles
    $minx=$this->bbox[0]+$width_box*$i-$width_box_ext;
    $miny=$this->bbox[1]+$height_box*$j-$height_box_ext;
    $maxx=$this->bbox[0]+$width_box*($i+1)+$width_box_ext;
    $maxy=$this->bbox[1]+$height_box*($j+1)+$height_box_ext;
    # Setzen der BBOX
    $this->param['BBOX']=$minx.','.$miny.','.$maxx.','.$maxy;
    # Setzen der Breite und Höhe in Pixeln
    $this->param['WIDTH']=strval($width+$width_ext);
    $this->param['HEIGHT']=strval($height+$height_ext);
    # Berechnen der Position der Tiles im Grid des Tiles
    $this->tilepos_x=$i*$width;
    $this->tilepos_y=$j*$height;
    # Zusammensetzen des Tile Requests
    $keys=array_keys($this->param);
    $values=array_values($this->param);
    $tile_request =$this->online_resource.'?'.$keys[0].'='.$values[0];
    for ($n=1;$n<count($this->param);$n++) {
      $tile_request.='&'.$keys[$n].'='.$values[$n];
    }
    return $tile_request;
  }
  
  function getTile($tile_request) {
    #$img = imagecreatefrompng("http://www.gaia-mv.de/dienste/DTK50Vf?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DTK50Vf&SRS=EPSG:25833&BBOX=260031,5949700,261924,5950865&FORMAT=image/png&WIDTH=325&HEIGHT=200&STYLE=");
    $img = imagecreatefrompng($tile_request);
    return $img;
  }
  
  function clipTile($tile_ext) {
    return $tile_ext;
  }
  
  function addTile($tile) {
    return $tile;
  }
}

class wms_response_obj {

  function wms_response_obj($img) {
    $this->img=$img;
  }

  function send() {
    header ("Content-type: ".DEFAULT_IMAGE_TYPE);
    imagepng($this->img);
    imagedestroy($this->img);
  }
  
  function save($content) {
  	# speichert das Bild lokal
  	if (!$handle = fopen($this->img, 'a')) {
      echo "Cannot open file (".$this->img.")";
      exit;
    }
    if (fwrite($handle,$content) === FALSE) {
      echo "Cannot write to file (".$this->img.")";
      exit;
    }
    fclose($handle);
  }
}
?>