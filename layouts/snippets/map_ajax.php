<?
include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
$res_x    = $this->map->width;
$res_y    = $this->map->height;
$dx       = $this->map->extent->maxx-$this->map->extent->minx;
$dy       = $this->map->extent->maxy-$this->map->extent->miny;
$pixelsize    = ($dx/$res_x+$dy/$res_y)/2;		# ist $scale in SVG_map.php

#			// nix
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
#			var polygon = svgdoc.getElementById("polygon");
#			// nix


$response = $this->formvars['code2execute_before'].
'~'.$this->img['hauptkarte'].'~
'.$this->img['scalebar'].'~
'.$this->img['referenzkarte'].'~
'.round($this->map_scaledenom).'~';
if($this->Lagebezeichung != ''){
	$response.= '<span class="fett">Gemeinde:&nbsp;</span>'.$this->Lagebezeichung['gemeindename'].' <span class="fett">Gemarkung:</span>&nbsp;'.$this->Lagebezeichung['gemkgname'].' ('.$this->Lagebezeichung['gemkgschl'].') <span class="fett">Flur:</span>&nbsp;'.$this->Lagebezeichung['flur'];
}
$response.= '~
'.$this->map->extent->minx.'~
'.$this->map->extent->miny.'~
'.$this->map->extent->maxx.'~
'.$this->map->extent->maxy.'~
'.$pixelsize.'~
~update_legend(\''.$this->layerhiddenstring.'\');'.$this->formvars['code2execute_after'];

ob_end_clean();
header('Content-Type: text/html; charset=utf-8');
echo $response;

?>
