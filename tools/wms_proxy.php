<?

	function wms_proxy(){
		$image_tile_size = 2048;
		
    $params = array_keys($_REQUEST);
    
    $url = $_REQUEST['url'].'?';
    
    $bbox = $_REQUEST['BBOX'];
    if(!$bbox)$bbox = $_REQUEST['bbox'];
    
    $width = $_REQUEST['WIDTH'];
    if(!$width)$width = $_REQUEST['width'];
    
    $height = $_REQUEST['HEIGHT'];
    if(!$height)$height = $_REQUEST['height'];
    
    $format = $_REQUEST['FORMAT'];
    if(!format)$format = $_REQUEST['format'];
    
    for($i = 0; $i < count($_REQUEST); $i++){
			if(in_array(strtolower($params[$i]), array('service', 'version', 'request', 'layers', 'format', 'srs', 'styles', 'query_layers', 'x', 'y'))){
    		$url.='&'.$params[$i].'='.$_REQUEST[$params[$i]];
    	}
    }
    
    if($width > $image_tile_size OR $heigth > $image_tile_size){		# hier muss gekachelt werden
    	$resultimage = imagecreatetruecolor($width, $height);
    	$extent = explode(',', $bbox);
    	$extentwidth = $extent[2]-$extent[0];
    	$extentheight = $extent[3]-$extent[1];
    	$miny = $extent[1];												# am Anfang auf "0" setzen
   		$maxy = $extent[1];												# am Anfang auf "0" setzen
    	$tilesx = ceil($width/$image_tile_size);		# Anzahl an Kacheln in x-Richtung
    	$tilesy = ceil($height/$image_tile_size);		# Anzahl an Kacheln in y-Richtung
    	$restheight = $height;											# Resthöhe des Teils des Gesamtbildes, für den noch keine Kacheln erzeugt wurden
    	$tileheight = $image_tile_size;							# Kachelhöhe
    	
    	for($y = 0; $y < $tilesy; $y++){
    		if($restheight < $image_tile_size)$tileheight = $restheight;
    		$tileextentheight = $extentheight/$height*$tileheight;
    		$miny = $maxy;													# das miny ist das maxy der letzten Kachel
    		$maxy = $maxy + $tileextentheight; 			# das maxy ist das maxy der letzten Kachel + die Höhe der Kachel
    		$tilewidth = $image_tile_size;						# Kachelbreite
    		$restwidth = $width;											# Restbreite des Teils der Zeile, für den noch keine Kacheln erzeugt wurden
    		$minx = $extent[0];												# am Anfang jeder Zeile auf "0" setzen
    		$maxx = $extent[0];												# am Anfang jeder Zeile auf "0" setzen
    		for($x = 0; $x < $tilesx; $x++){
    			if($restwidth < $image_tile_size)$tilewidth = $restwidth;
    			$tileextentwidth = $extentwidth/$width*$tilewidth;
    			$minx = $maxx;													# das minx ist das maxx der letzten Kachel
    			$maxx = $maxx + $tileextentwidth; 			# das maxx ist das maxx der letzten Kachel + die Breite der Kachel
    			$contents = get_map($url.'&bbox='.$minx.','.$miny.','.$maxx.','.$maxy.'&width='.$tilewidth.'&height='.$tileheight);
    			$image  = imagecreatefromstring($contents);
    			
    			#$fp = fopen('/home/fgs/fgs/test/test_'.$x.$y.'.jpg', "w");
    			#fwrite($fp, $contents);
    			
			    ImageCopy($resultimage, $image, $x*$image_tile_size, $height-($y*$image_tile_size)-$tileheight, 0, 0, $tilewidth, $tileheight);
    			$restwidth = $restwidth - $image_tile_size;
    		}
    		$restheight = $restheight - $image_tile_size;
    	}    	
    	
    	#$image = imagecreatefromstring($resultimage);
     	#$fpg = fopen('/home/fgs/fgs/test/test_gesamt.png', "w");
     	#fwrite($fpg, $image);
    	
	    if($format == 'image/png'){
	      imagepng($resultimage);
	    }
	    elseif($format == 'image/jpeg'){
	      imagejpeg($resultimage);
	    }
    }
    else{
    	print(get_map($url.'&bbox='.$bbox.'&width='.$width.'&height='.$height));
    }
  }
  
  function get_map($url){
  	ob_end_clean();
    header('content-type:'.$_REQUEST['format']);
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    #header('Content-Disposition: filename=test.jpg');
		$ctx = stream_context_create(array('http' => array('timeout' => 10)));
		return file_get_contents($url, 0, $ctx);
  }
  
  wms_proxy();
?>
