<?php
# 2007-12-30 pk
  include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'snippets/ahah.php');
	$show_legend_graphic = (defined('LEGEND_GRAPHIC_FILE') and file_exists(SNIPPETS . LEGEND_GRAPHIC_FILE));
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);

	global $sizes;
	$size = $sizes[$this->user->rolle->gui];

	$map_width = $this->user->rolle->nImageWidth;
	$legend_hide_width = $size['legend']['hide_width'];
	$legend_width = ($this->user->rolle->hideLegend == 1 ? $legend_hide_width : $size['legend']['width']);

	$legend_height = $this->map->height +
		$size['scale_bar']['height'] +
		(empty($this->Lagebezeichnung) ? 0 : $size['lagebezeichnung_bar']['height']) +
		$size['map_functions_bar']['height'] +
		$size['footer']['height'] -
		66; # height of every thing above legend inclusiv tabs

	$scrolldiv_height = $this->map->height +
		$size['scale_bar']['height'] +
		(empty($this->Lagebezeichnung) ? 0 : $size['lagebezeichnung_bar']['height']) +
		$size['map_functions_bar']['height'] +
		$size['footer']['height'] -
		91; # height of every thing above scroll div inclusive tabs.

	$msg  = 'browser width x height: ' . $this->formvars['browserwidth'] . ' x ' . $this->formvars['browserwidth'];
	$msg .= '<br>map width x height: ' . $this->map->width . ' x ' . $this->map->height;
	$msg .= '<br>scale_bar_height: ' . $size['scale_bar']['height'];
	$msg .= '<br>lagebezeichnung_bar_height: ' . $size['lagebezeichnung_bar']['height'];
	$msg .= '<br>map_functions_bar_height: ' . $size['map_functions_bar']['height'];
	$msg .= '<br>footer_height: ' . $size['footer']['height'];
	$msg .= '<br>scrolldiv_height: ' . $scrolldiv_height;
	$msg .= '<br>menue hide width: ' . $size['menue']['hide_width'];
	$msg .= '<br>menue width: ' . $size['menue']['width'];
	$msg .= '<br>legend width x height: ' . $legend_width . ' x ' . $legend_height;
#	$this->add_message('error', $msg);

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
  document.getElementById('MapImageLink').href='index.php?go=showMapImage&svg_string='+document.GUI.svg_string.value;
}

function slide_legend_in(evt) {
	document.getElementById('legenddiv').className = 'slidinglegend_slidein';
	$('#legenddiv').width(<?php echo $size['legend']['width']; ?>);
}

function slide_legend_out(evt) {
	if(window.outerWidth - evt.pageX > 100) {
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
		$('#legenddiv').width(<?php echo $legend_hide_width; ?>);
	}
}

<? if (!ie_check()){ ?>					// Firefox, Chrome

function switchlegend(){
	if (document.getElementById('legenddiv').className == 'normallegend') {
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
		ahah('index.php', 'go=changeLegendDisplay&hide=1', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize_legend.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
	else {
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

# 2006-02-17 pk den javascript teil, der hier drin war in SVGvars_coordscript verschoben.
if ($this->Fehlermeldung!='') {
       include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
if($this->formvars['gps_follow'] == ''){
	$this->formvars['gps_follow'] = 'off';
}
?>
<div id="map_frame" style="position: relative; width: <?php echo $map_width + $legend_width; ?>px; height: <?php echo $legend_height; ?>px;">
	<div
		id="map"
		style="float: left; width: <?php echo $map_width; ?>px;"
	>
		<?php include(SNIPPETS . 'mapdiv.php'); ?>
	</div>
	<div
		id="legenddiv"<?
		if (!ie_check() AND $this->user->rolle->hideLegend) { ?>
			onmouseenter="slide_legend_in(event);"
			onmouseleave="slide_legend_out(event);"
			style="left: <?php echo $map_width + $legend_width; ?>px; width: <?php echo $legend_hide_width; ?>;"
			class="slidinglegend_slideout"<?
		}
		else { ?>
			style="left: <?php echo $map_width + $legend_width; ?>px; width: <?php echo $legend_width; ?>;"
			class="normallegend" <?
		} ?>
	>
		<?php include(SNIPPETS . 'legenddiv.php'); ?>
	</div>
</div>