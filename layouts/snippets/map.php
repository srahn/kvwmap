<?php
# 2007-12-30 pk
  include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'snippets/ahah.php');
	$show_legend_graphic = (defined('LEGEND_GRAPHIC_FILE') and file_exists(SNIPPETS . LEGEND_GRAPHIC_FILE));
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);

	global $sizes;
	$size = $sizes[$this->user->rolle->gui];
	$size['map_functions_bar']['height'] = ($this->user->rolle->showmapfunctions == 1 ? $size['map_functions_bar']['height'] : 0);
	$size['lagebzeichnung_bar']['height'] = (count($this->Lagebezeichnung) == 0 ? $size['lagebezeichnung_bar']['height'] : 0);

	$map_width = $this->user->rolle->nImageWidth;
	$legend_hide_width = $size['legend']['hide_width'];
	$legend_width = ($this->user->rolle->hideLegend == 1 ? $legend_hide_width : $size['legend']['width']);

	$legend_height = $this->map->height +
		$size['scale_bar']['height'] +
		+ (LAGEBEZEICHNUNGSART != '' ? $size['lagebezeichnung_bar']['height'] : 0)
		+ ($this->user->rolle->showmapfunctions == 1 ? $size['map_functions_bar']['height'] : 0);

	$res_x    = $this->map->width;
	$res_y    = $this->map->height;
	$res_xm   = $this->map->width/2;
	$res_ym   = $this->map->height/2;
	$dx       = $this->map->extent->maxx-$this->map->extent->minx;
	$dy       = $this->map->extent->maxy-$this->map->extent->miny;
	$pixelsize    = ($dx/$res_x+$dy/$res_y)/2;
?>

<script type="text/javascript">

function zoomto(layer_id, oid, tablename, columnname){
  location.href="index.php?go=zoomtoPolygon&oid="+oid+"&layer_tablename="+tablename+"&layer_columnname="+columnname+"&layer_id="+layer_id+"&selektieren=zoomonly";
}

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function show_vertices(){	
	document.getElementById("vertices").SVGshow_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function startup(){
	document.getElementById("map").SVGstartup();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function showtooltip(result, showdata){
	document.getElementById("svghelp").SVGshowtooltip(result, showdata);			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function showMapImage(){ 
	svgdoc = document.SVG.getSVGDocument();	
	var svg = svgdoc.getElementById("moveGroup");
	try{
  	var serializer = new XMLSerializer();
  	document.GUI.svg_string.value = serializer.serializeToString(svg);
	}
	catch(e){
		document.GUI.svg_string.value = printNode(svg);
	}
  document.getElementById('MapImageLink').href='index.php?go=showMapImage&svg_string='+encodeURI(document.GUI.svg_string.value);
}

function addRedlining(){
	var redlining = svgdoc.getElementById("redlining");
	for(var i = 0; i < redlining.childNodes.length; i++){
		child = redlining.childNodes[i];
		switch(child.id){
			case 'free_polygon':
			case 'free_arrow':
				if(child.transform.baseVal.numberOfItems > 0)child.transform.baseVal.consolidate();
				for(var j = 0; j < child.points.numberOfItems; j++){		// Punkte in Weltkoordinaten umrechnen
					point = child.points.getItem(j);
					if(child.transform.baseVal.numberOfItems > 0)point = point.matrixTransform(child.transform.baseVal.getItem(0).matrix);
					x = point.x*parseFloat(document.GUI.pixelsize.value) + parseFloat(document.GUI.minx.value);
					y = document.GUI.maxy.value - (<? echo $this->map->height; ?> - point.y)*parseFloat(document.GUI.pixelsize.value);
					if(j > 0)document.GUI.free_polygons.value += ','
					document.GUI.free_polygons.value += x+' '+y;
				}
				document.GUI.free_polygons.value += '|'+child.getAttribute('style')+'||';
				break;
			case 'free_text':
				x = child.getAttribute('x')*parseFloat(document.GUI.pixelsize.value) + parseFloat(document.GUI.minx.value);
				y = document.GUI.maxy.value - (<? echo $this->map->height; ?> - (-1 * child.getAttribute('y')))*parseFloat(document.GUI.pixelsize.value);
				document.GUI.free_texts.value += x+' '+y+'|';
				for(var j = 0; j < child.childNodes.length; j++){
					tspan = child.childNodes[j];
					if(j > 0)document.GUI.free_texts.value += String.fromCharCode(13);
					document.GUI.free_texts.value += tspan.textContent;
				}
				document.GUI.free_texts.value += '||';
			break;
		}
	}
}

function printMap(){ 
	svgdoc = document.SVG.getSVGDocument();	
	addRedlining();
	document.GUI.go.value = 'Druckausschnittswahl';
	document.GUI.submit();
}

</script>
<?

# 2006-02-17 pk den javascript teil, der hier drin war in SVGvars_coordscript verschoben.
if ($this->Fehlermeldung!='') {
       include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
if($this->formvars['gps_follow'] == ''){
	$this->formvars['gps_follow'] = 'off';
}
?>
<div id="map_frame" style="text-align: left;position: relative; width: <?php echo ($map_width + $legend_width); ?>px;">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top">
				<div id="map" style="float: left; width: <?php echo $map_width; ?>px; height: 100%">
					<?php include(SNIPPETS . 'mapdiv.php'); ?>
				</div>
			</td>
			<td valign="top">
				<div id="legenddiv" style="height: <? echo $legend_height; ?>px;"<?
					if (!ie_check() AND $this->user->rolle->hideLegend) { ?>
						onmouseenter="slide_legend_in(event);"
						onmouseleave="slide_legend_out(event);"
						class="slidinglegend_slideout"<?
					}
					else { ?>
						class="normallegend" <?
					} ?>
				>
					<?php include(SNIPPETS . 'legenddiv.php'); ?>
				</div>
			</td>
		</tr>
	</table>
</div>
