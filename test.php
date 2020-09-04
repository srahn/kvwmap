<?php
	include('config.php');
	$map = new mapObj(DEFAULTMAPFILE, SHAPEPATH);
	var_dump($map); exit;
	$map->imagecolor->setRGB(255, 255, 255);
	var_dump($map->imagecolor);
?>