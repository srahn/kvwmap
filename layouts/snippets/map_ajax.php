<?
include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
$res_x    = $this->map->width;
$res_y    = $this->map->height;
$dx       = $this->map->extent->maxx-$this->map->extent->minx;
$dy       = $this->map->extent->maxy-$this->map->extent->miny;
$pixelsize    = ($dx/$res_x+$dy/$res_y)/2;		# ist $scale in SVG_map.php

#			var mapimg = svgdoc.getElementById("mapimg");
#			var scalebar = document.getElementById("scalebar");
# 		var refmap = document.getElementById("refmapdiv");
#			var scale = document.getElementById("scale");
#			var lagebezeichnung = document.getElementById("lagebezeichnung");
#			var minx = document.GUI.minx;
#			var miny = document.GUI.miny;
#			var maxx = document.GUI.maxx;
#			var maxy = document.GUI.maxy;			
#			var pixelsize = document.GUI.pixelsize;
#			var maptime = document.getElementById("maptime");
#			var polygon = svgdoc.getElementById("polygon");
#			// nix
#			// nix
$response =
$this->img['hauptkarte'].'~
'.$this->img['scalebar'].'~
'.$this->img['referenzkarte'].'~
'.round($this->map_scaledenom).'~';
if($this->Lagebezeichung != ''){
	$response.= '<b>Gemeinde:&nbsp;</b>'.$this->Lagebezeichung['gemeindename'].' <b>Gemarkung:</b>&nbsp;'.$this->Lagebezeichung['gemkgname'].' <b>Flur:</b>&nbsp;'.$this->Lagebezeichung['flur'];
}
$response.= '~
'.$this->map->extent->minx.'~
'.$this->map->extent->miny.'~
'.$this->map->extent->maxx.'~
'.$this->map->extent->maxy.'~
'.$pixelsize.'~
&nbsp;<a href="index.php?go=Kartenkommentar_Formular&consumetime='.$this->user->rolle->newtime.'&hauptkarte='.$this->img['hauptkarte'].'">'.$strSave.'</a>&nbsp;|&nbsp;<a href="index.php?go=Kartenkommentar_Waehlen&prevtime='.$this->user->rolle->newtime.'">'.$strChoose.'</a>&nbsp;|&nbsp;';
if(SHOW_MAP_IMAGE == 'true'){
	$response .= '<a id="MapImageLink" target="_blank" href="" onmouseover="javascript:showMapImage();">'.$strMapImageURL.'</a></b>';
}
$response .='&nbsp;|&nbsp;<a href="javascript:resizemap2window();" >Gr&ouml;&szlig;e anpassen</a>
~
~startup()
~update_legend(\''.$this->layerhiddenstring.'\')';

ob_end_clean();
header('Content-Type: text/html; charset=utf-8');
echo $response;

?>
