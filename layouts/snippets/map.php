<?php
# 2007-12-30 pk
  include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'snippets/ahah.php');
	$show_legend_graphic = (defined('LEGEND_GRAPHIC_FILE') and file_exists(SNIPPETS . LEGEND_GRAPHIC_FILE));
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);
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

function stopwaiting(){
	if(typeof document.getElementById("svghelp").SVGstopwaiting == 'function')
	document.getElementById("svghelp").SVGstopwaiting();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function startwaiting(){
	if(typeof document.getElementById("svghelp").SVGstartwaiting == 'function')
	document.getElementById("svghelp").SVGstartwaiting();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
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
  document.getElementById('MapImageLink').href='index.php?go=showMapImage&svg_string='+document.GUI.svg_string.value;
}

function slide_legend_in(evt){
	document.getElementById('legenddiv').className = 'slidinglegend_slidein';
}

function slide_legend_out(evt){
	if(window.outerWidth - evt.pageX > 100){
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
	}
}

<? if (!ie_check()){ ?>					// Firefox, Chrome

function switchlegend(){
	if(document.getElementById('legenddiv').className == 'normallegend'){
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
		ahah('index.php', 'go=changeLegendDisplay&hide=1', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize_legend.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
	else{
		document.getElementById('legenddiv').className = 'normallegend';
		ahah('index.php', 'go=changeLegendDisplay&hide=0', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>minimize_legend.png';
		document.getElementById('LegendMinMax').title="Legende verstecken";
	}
}

<? }else{ ?>						// IE

function switchlegend(){
	if(document.getElementById('legendTable').style.display == 'none'){
		document.getElementById('legendTable').style.display='';
		ahah('index.php', 'go=changeLegendDisplay&hide=0', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize.png';
		document.getElementById('LegendMinMax').title="Legende verstecken";
	}
	else{
		document.getElementById('legendTable').style.display='none';
		ahah('index.php', 'go=changeLegendDisplay&hide=1', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>minimize.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
}

<? } ?>

</script>

<?
	global $sizes;
	$map_width = $this->user->rolle->nImageWidth;
	$legend_width = $sizes[$this->user->rolle->gui]['legend']['width'];
	$legend_height = $this->map->height +
		$sizes[$this->user->rolle->gui]['scale_bar']['height'] +
		(empty($this->Lagebezeichnung) ? 0 : $sizes[$this->user->rolle->gui]['lagebezeichnung_bar']['height']) +
		$sizes[$this->user->rolle->gui]['map_functions_bar']['height'] +
		$sizes[$this->user->rolle->gui]['footer']['height'] -
		66; # height of every thing above legend inclusiv tabs

	$scrolldiv_height = $this->map->height +
		$sizes[$this->user->rolle->gui]['scale_bar']['height'] +
		(empty($this->Lagebezeichnung) ? 0 : $sizes[$this->user->rolle->gui]['lagebezeichnung_bar']['height']) +
		$sizes[$this->user->rolle->gui]['map_functions_bar']['height'] +
		$sizes[$this->user->rolle->gui]['footer']['height'] -
		91; # height of every thing above scroll div inclusive tabs.
/*
	$msg  = 'map_height: ' . $this->map->height;
	$msg .= '<br>scale_bar_height: ' . $sizes[$this->user->rolle->gui]['scale_bar']['height'];
	$msg .= '<br>lagebezeichnung_bar_height: ' . $sizes[$this->user->rolle->gui]['lagebezeichnung_bar']['height'];
	$msg .= '<br>map_functions_bar_height: ' . $sizes[$this->user->rolle->gui]['map_functions_bar']['height'];
	$msg .= '<br>footer_height: ' . $sizes[$this->user->rolle->gui]['footer']['height'];
	$msg .= '<br>scrolldiv_height: ' . $scrolldiv_height;
	$msg .= '<br>legend_height: ' . $legend_height;
	$this->add_message('error', $msg);
*/
	$res_x    = $this->map->width;
	$res_y    = $this->map->height;
	$res_xm   = $this->map->width/2;
	$res_ym   = $this->map->height/2;
	$dx       = $this->map->extent->maxx-$this->map->extent->minx;
	$dy       = $this->map->extent->maxy-$this->map->extent->miny;
	$pixelsize    = ($dx/$res_x+$dy/$res_y)/2;

# 2006-02-17 pk den javascript teil, der hier drin war in SVGvars_coordscript verschoben.
if ($this->Fehlermeldung!='') {
       include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
if($this->formvars['gps_follow'] == ''){
	$this->formvars['gps_follow'] = 'off';
}
?>
<div id="m" style="width: <?php echo ($map_width + $legend_width); ?>px;">
	<div
		id="map"
		style="display: inline-block; width: <?php echo $map_width; ?>px; max-width: <?php echo $map_width; ?>px; overflow: scroll;"
	><?php include(SNIPPETS . 'mapdiv.php'); ?></div><div
		id="legenddiv"
		style="display: inline-block; width: <?php echo $legend_width; ?>px; vertical-align: top"<?
		if (!ie_check() AND $this->user->rolle->hideLegend) { ?>
			onmouseenter="slide_legend_in(event);"
			onmouseleave="slide_legend_out(event);"
			class="slidinglegend_slideout"<?
		}
		else { ?>
			class="normallegend" <?
		} ?>
	><?php include(SNIPPETS . 'legenddiv.php'); ?></div>
</div>
