<?php
$SVGvars_tooltipscript ='
function get_bbox()
{ 
	bbox = document.getElementById("tooltip_data").getBBox();
	if (bbox.width>10) { 
		// diese abfrage ist nur wegen fehlerhafter darstellung mit dem IE erforderlich!
		// ebenso in SVG_map.php:  onmousemove="get_bbox();" 
	  tt_rect = bbox.width+10;
	  document.getElementById("tt_rect").setAttribute("width", tt_rect);
	  tt_text = tt_rect/2;
	  document.getElementById("tooltip_data").setAttribute("x", tt_text);
	}
}

function show_tooltip(data,x_pos,y_pos) 
{
	x_pos = x_pos + 10; 	// 	Abstand zw. cursor und tooltip
	y_pos = y_pos + 20; 	// 	Abstand zw. cursor und tooltip
	document.getElementById("tooltip_group").setAttribute("transform","translate(" + x_pos.toString() + " " + y_pos.toString() + ")") 
	document.getElementById("tooltip_data").firstChild.data = data;
	setTimeout("document.getElementById(\'tooltip_group\').setAttribute(\'visibility\',\'visible\')",250);
	get_bbox();
} 

function hide_tooltip() 
{ 
	var tooltip_group = document.getElementById("tooltip_group");
	if(tooltip_group != undefined){
		tooltip_group.setAttribute("visibility","hidden");
		tooltip_group.setAttribute("opacity","0");
	}
	setTimeout("document.getElementById(\'tooltip_group\').setAttribute(\'opacity\',\'0.9\')",255);
} 
'
?>