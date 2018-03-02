<?php
	header('Content-type: text/css');
	include('../config.php');
	
	global $sizes;
	$size = $sizes[$_REQUEST['gui']];
?>
	
@font-face {
	font-family: 'SourceSansPro';
	font-style: normal;
	font-weight: 200;
	src: local('SourceSansPro'), url(../fonts/SourceSansPro-Light.ttf);
}
@font-face {
	font-family: 'SourceSansPro1';
	font-style: normal;
	font-weight: 400;
	src: local('SourceSansPro1'), url(../fonts/SourceSansPro-Regular.ttf);
}
@font-face {
	font-family: 'SourceSansPro2';
	font-style: normal;
	font-weight: 600;
	src: local('SourceSansPro2'), url(../fonts/SourceSansPro-Semibold.ttf);
}
@font-face {
	font-family: 'SourceSansPro3';
	font-style: normal;
	font-weight: 700;
	src: local('SourceSansPro3'), url(../fonts/SourceSansPro-Bold.ttf);
}

body {	
	font-family: SourceSansPro1, Arial, Verdana, Helvetica, sans-serif;
	BACKGROUND:white;
	margin:0px;
	font-size: 15px;
}

.gui-table {
  width: 900px;
}

form {
	margin: 0;
	padding: 0;
} 

.fett{
	font-family: SourceSansPro2;
	line-height: 18px;
}

.fetter{
	font-family: SourceSansPro3;
	line-height: 20px;
}

.px13 {
	font-size: 13px;
}

.px14 {
	font-size: 14px;
}

.px15{
	font-size: 15px;
}

.px16{
	font-size: 16px;
	line-height: 16px;
}

.px17{
	font-size: 17px;
}

.px20{
	font-size: 20px;
}


h1 {
	font-family: SourceSansPro3;
	font-size: 24px; 
	margin-top: 0px; 
	margin-bottom: 0px; 
	padding-top: 0px; 
	padding-bottom: 0px
}

h2 {
	font-family: SourceSansPro3;
	font-size: 20px; 
	margin-bottom: 0px;
	margin-top: 0px;
}

input[type="text"]{
	font-size: 14px;
	font-family: SourceSansPro1;
	height: 22px;
}

input[type="file"] {
	font-size: 15px;
	font-family: SourceSansPro1;
	height: 24px;
}

input[type="button"], input[type="reset"], input[type="submit"] {
	height: 24px;
	font-size: 15px;
	font-family: SourceSansPro1;
	line-height : 15px;
}

input[type="text"].transparent_input{
	font-size: 15px;
	font-family: SourceSansPro1;
	border:0px;
	background-color:	transparent;
}

.search-form {
  display: inline-block;
}

.search-form h2 {
  color: black;
  margin-bottom: 10px
}

.search-form label {
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #eee;  
  padding: 3px;
  float: left;
  text-align: left;
  width: 150px;
  height: 15px;
  margin-right: 10px;
}

.search-form select {
  float: left;
  text-align: left;
  width: 150px;
}

.search-form input[type=text] {
  float: left;
  text-align: left;
  width: 150px;
}

.search-form input[type=reset] {
  float: none;
  text-align: center;
  width: 80px;
}

.search-form input[type=button] {
  float: none;
  text-align: center;
  width: 80px;
}

.search-form .clear {
  clear: both;
  padding-top: 10px;
}

.input-form {
  display: inline-block;
}

.input-form h2 {
  color: black;
  margin-bottom: 10px
}

.input-form label {
  float: left;
  text-align: right;
	font-size: 17px;
  width: 210px;
  height: 15px;
  margin-right: 10px;
}

.input-form select {
  float: left;
  text-align: left;
  width: 150px;
}

.input-form input {
}

.input-form input[type=text] {
  float: left;
  text-align: left;
  width: 400px;
}

.input-form input[type=reset] {
  float: none;
  text-align: center;
}

.input-form input[type=button] {
  float: none;
  text-align: center;
}

.input-form .clear {
  clear: both;
  padding-top: 10px;
}

.center-outerdiv {
  padding: 30px;
  text-align: center;
}

select {
	font-size: 14px;
	font-family: SourceSansPro1;
	padding: 0 0;
}

textarea {
	font-size: 14px;
	font-family: SourceSansPro1;
}

tr.tr_hover:hover{
	background-color: #DAE4EC;
}

td {	
	font-size: 15px;
	line-height: 16px;
	font-family: SourceSansPro1;
}

th {	
	font-size: 17px;
	font-family: SourceSansPro2;
}

th a {	
	font-size: 17px;
	font-family: SourceSansPro2;
}

pre {
	white-space: pre-wrap;       /* Since CSS 2.1 */
	white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
	white-space: -pre-wrap;      /* Opera 4-6 */
	white-space: -o-pre-wrap;    /* Opera 7 */
	word-wrap: break-word;       /* Internet Explorer 5.5+ */
	background: inherit;
	font: inherit;
	margin: 0;
	overflow: auto;
	padding: 0;
}

a, img {	
	color: firebrick; 
	TEXT-DECORATION: none;
	font-size: 15px;
	border: none;
	outline:none;
}

a:focus{
	outline: none;
}

a:hover {	
	color: black;
}

a.name:link {
	text-decoration: none;
	font-family: Arial, Helvetica, sans-serif;
	color: #993333;
	font-weight: bold;
}
a.name:visited {
	text-decoration:none;
	font-family: Arial, Helvetica, sans-serif;
	color: #006633;
	font-weight: bold;
}

a.red {
	color: #993333;
	font-weight: 600; 
}

a.link {
	color: firebrick; 
}

a.blue_underline {
	color: #0000CC;
		font-weight: bold;
	TEXT-DECORATION:underline; 
}

a.green {
	color: #339933;
	TEXT-DECORATION: none; 
}

a.metalink {
	color: black; 
}

a.visiblelayerlink {
	color: black;
	cursor: context-menu;
}

a.boldhover:hover{
	text-shadow: 0 0 0 rgba(0,0,0,0.8);
}

a.invisiblelayerlink {
	color: gray;
	cursor: context-menu;
}

a.invisiblelayerlink:hover{
	color: gray;
}

.buttonlink{
	height: 13px;
	display: inline-block;
	border: 1px solid #cccccc;
	border-color: #bbbbbb;
	background: linear-gradient(#eff3f6, lightsteelblue);
	padding: 4px;
	padding-top: 0px;
	padding-bottom: 4px;
	text-align: center;
	color: black;
	border-radius: 5px;
}

.buttonlink:hover{
	background: linear-gradient(#DAE4EC, #84accf);
}

#openLayerParamBarIcon{
	width: 16px; 
	height: 16px; 
	padding: 5px; 
	font-size: 1.1em;
	margin: 0 5px 0 40px;
}

#layerParamsBar {
	display: none;
	position: absolute;
	border-radius: 5px;
	top: 22;
	right: 5;
	width: 350;
	z-index: 9999;
}

#menuebar{
	background: url('../graphics/bg.gif');
	border: 1px solid;
	border-color: #CCC; 
	border-top: none;
	border-bottom: none;
}

#menue_switch{
	min-width: <? echo $size['menue']['hide_width']; ?>px;
	background: linear-gradient(90deg, #DAE4EC, #c1d6f2);
}

#menueTable{
	margin-bottom: 2px;
	width: <? echo ($size['menue']['width'] - 2); ?>px;
	text-align: center;
	display: flex; 
	flex-wrap: wrap;
	justify-content: flex-start;
}

#menueTable a {
	color: firebrick;
}

.menu {
	background: linear-gradient(#DAE4EC 0%, #c7d9e6 100%);
	position: relative; 
	visibility: visible; 
	left: 0px; 
	top: 0px; 
	z-index:3;
	border: 1px solid #cccccc;
	margin: 2px;
	margin-bottom: 1px;
	padding-bottom: 4px;
	line-height : 17px;
	text-align: left;
}

.menu:hover{
	background: linear-gradient(#DAE4EC 0%, #adc7da 100%);
}

a.menuered {
	color: #993333;
	font-size: 15px;
	line-height : 17px;
}

a.menuered:hover {	
	color: black;
}

.menue_before {
	display: none;
	height: 17px;
	width: 17px;
	box-sizing:border-box;
  padding-left: 17;
	padding-right: 3px;
}

.menue-auf .menue_before {
	display: inline-block;
	background: url('../graphics/menue_top_open.gif');
}

.menue-zu .menue_before {	
	display: inline-block;
	background: url('../graphics/menue_top.gif');
}

.obermenue {
	cursor: pointer;
	font-size: 15px;
	color: black;
	font-family: SourceSansPro2;
	height: 17px;
}

.hauptmenue {
	cursor: pointer;
	font-size: 15px;
	color: #a82e2e;
	font-family: SourceSansPro2;
}

.hauptmenue:before {
	content: ' ';
	margin-right: 23px;
}

.untermenue:before {
	padding-left: 3px;
	content:url('../graphics/submenue.png');
	position:relative;
	z-index:100000;
	float: left;
}

.untermenue {
	display: flex;
  align-items: top;
	cursor: pointer;
	background: rgb(237, 239, 239);
	margin-bottom: 0px;
	margin-top: 0px;
	padding-top: 1px;
	padding-bottom: 1px;
	border: 0px;
}

.untermenue:hover{
	background: linear-gradient(#dae4ec 0%, #c7d9e6 100%);
}

.untermenues {
	color: #993333;
	font-size: 15px;
	line-height: 17px;
	padding-bottom: 2px;
}

.ausgewaehltes-menue {
/*	background: rgb(205, 208, 208); */
}

.button-menue{
	flex: 0 0 auto;
	margin: 0 0 2 0;
}

.text-menue{
	flex: 0 0 100%;
}

.use_for_dataset{
	background-image: url(../graphics/use_for_dataset.png);
}

.copy_dataset{
	background-image: url(../graphics/copy_dataset.png);
}

.datensatz_exportieren{
	background-image: url(../graphics/datensatz_exportieren.png);
}

.drucken{
	background-image: url(../graphics/drucken.png);
}

.schnelldruck{
	background-image: url(../graphics/schnelldruck.png);
}

.merken{
	background-image: url(../graphics/merken.png);
}

.nicht_mehr_merken{
	background-image: url(../graphics/nicht_mehr_merken.png);
}

.datensatz_loeschen{
	background-image: url(../graphics/datensatz_loeschen.png);
}

.edit_geom{
	background-image: url(../graphics/edit_geom.png);
}

.zoom_normal{
	background-image: url(../graphics/zoom_normal.png);
}

.zoom_highlight{
	background-image: url(../graphics/zoom_highlight.png);
}

.zoom_select{
	background-image: url(../graphics/zoom_select.png);
}

.switch_gle{
	background-image: url(../graphics/switch_gle.png);
}

.url_extent{
	background-image: url(../graphics/url_extent.png);
}

.save_extent{
	background-image: url(../graphics/save_extent.png);
}

.load_extent{
	background-image: url(../graphics/load_extent.png);
}

.save_image{
	background-image: url(../graphics/save_image.png);
}

.resize_map{
	background-image: url(../graphics/resize_map.png);
}

.optionen{
	background-image: url(../graphics/optionen.png);
}

.karte{
	background-image: url(../graphics/karte.png);
}

.logout{
	background-image: url(../graphics/logout.png);
}

.gesamtansicht {
	background-image: url(../graphics/gesamtansicht.png);
}

.notiz{
	background-image: url(../graphics/notiz.png);
}

.hilfe{
	background-image: url(../graphics/hilfe.png);
}

.save_layers{
	background-image: url(../graphics/save_layers.png);
}

.load_layers{
	background-image: url(../graphics/load_layers.png);
}

.tool_info{
	background-image: url(../graphics/tool_info.png);
}

.layer{
	background-image: url(../graphics/layer.png);
}

.button_background{
	background: linear-gradient(#fdfdfd, #DAE4EC);
	box-shadow: 0px 1px 0px #bbb;
}

.button{
	position: relative;
	background-repeat: no-repeat;
	background-position: center; 
	width:36px;
	height:36px;
}

.button::after {
	border-radius: 3px;
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.4);
  content: '';
	opacity: 0;
  position: absolute;
  height: calc(100% - 2px);
	width: calc(100% - 2px);
  left: 1;
  top: 1;
}

.button:active{
	background-color: #e3e8ed;
}

.button:hover::after{
  opacity: 1;
}

#header{
	height: <? echo ($size['header']['height'] - 2); ?>;
	border: 1px solid; 
	border-color: #ffffff #cccccc #bbbbbb;
}

#footer{
	height: <? echo ($size['footer']['height'] - 2); ?>;
	border: 1px solid; 
	border-color: #aaa #cccccc #cccccc;
}

#scale_bar {
	background: <? echo BG_MENUETOP; ?>;
	border-top: 1px solid #aaaaaa;
	height: <? echo ($size['scale_bar']['height'] - 1); ?>;
}

#lagebezeichnung_bar{
	background: <? echo BG_MENUETOP; ?>;
	border-top: 1px solid #aaaaaa;
	height: <? echo ($size['lagebezeichnung_bar']['height'] - 1); ?>;
	text-align: center;
}

#lagebezeichnung{
	padding-top: 5px;
	font-size: 15px;
	font-family: SourceSansPro1;
}

#map_functions_bar{
	background: linear-gradient(#fdfdfd, #DAE4EC);
	border-top: 1px solid #ccc;
	box-shadow: 0px 1px 0px #bbb;
	height: 36px;
}

#legenddiv {
	border-left: 1px solid #ccc;
	display: flex; 
	flex-direction: column;
}

#legend_layer{
	flex: 1; 
	display: flex; 
	flex-direction: column;
}

.legend_layer_highlight{
	background-color: none;
	animation: highlight 3s ease-in-out;
}

@keyframes highlight{
  0%,100% { background-color: none }
  10%,60% { background-color: lightsteelblue }
}

#legendcontrol{
	margin-top: 5px;
	margin-bottom: 8px;
	display: flex; 
	flex-direction: row;
	justify-content: flex-start;
	padding-left: 7px;
}

#drawingOrderForm{
	margin: 5 0 10 0;
	position: relative;
}

.drawingOrderFormDropZone{
	position: relative;
	z-index: 1000;
	margin: 0;
	height: 0px;
	width: 177px;
}

.drawingOrderFormDropZone.ready{
	margin: -12 0 -12 15;
	height: 24px;
	transition: height 0.1s ease, margin 0.1s ease;
}

.drawingOrderFormDropZone.over{
	height: 51px;
	margin: -13 0 -13 15;
	transition: height 0.1s ease, margin 0.1s ease;
}

.drawingOrderFormLayer{
	background-color: #f6f6f6;
	box-shadow: 1px 1px 4px #aaa;
	z-index: 100;
	margin: 3 0 0 15;
	padding: 2 2 2 3;
	height: 16px;
	width: 177px;
	border: 1px solid grey;
	cursor: pointer;
}

.drawingOrderFormLayer:hover{
	background-color: #fcfcfc;
}

.drawingOrderFormLayer.dragging{
	box-shadow: 3px 3px 6px #aaa;
}

.drawingOrderFormLayer.picked{
	visibility: hidden;
	height: 0px;
	margin: 0 0 0 0;
	padding: 0 0 0 0;
	border: none;
	transition: height 0.15s ease, margin 0.15s ease, padding 0.15s ease;
}

.drawingOrderFormLayer.over{
	border: 1px dashed #000;
}

#layersearchdiv{
	margin: 7px;
}

#layer_search{
	width: 145px;
}

#scrolldiv{
	width: <?php echo ($size['legend']['width'] - 3); ?>px;
	margin-right: 2px;
	flex: 1 1 0; 
	overflow:auto; 
	scrollbar-base-color:<?php echo BG_DEFAULT ?>;
}

#legend{
	margin: 4px 0 4px 7px;
}

.normallegend {
	float: right;
	width: <?php echo ($size['legend']['width'] - 1); ?>px;
	vertical-align: top;
	background-image: url(../graphics/bg.gif);
	border-top: 1px solid #eeeeee;
}

.slidinglegend_slideout {
	cursor: pointer;
	right: -<?php echo $size['legend']['width']; ?>px;
	position:absolute;
	border-top: 1px solid #eeeeee;
	border-left:1px solid #CCCCCC;
	border-bottom: 1px solid #aaaaaa;
	background-image: url(../graphics/bg.gif);
	transform: translate3d(-<? echo ($size['legend']['hide_width'] + 2); ?>px,0px,0px);
	transition: all 0.3s ease;
}


.slidinglegend_slideout	#legend_layer {
	opacity: 0.0;
	transition: all 0.3s ease;
}

.slidinglegend_slidein {
	right: -<?php echo $size['legend']['width']; ?>px;
	position: absolute;
	border-top: 1px solid #eeeeee;
	border-left:1px solid #CCCCCC;
	border-bottom: 1px solid #aaaaaa;
	background-image: url(../graphics/bg.gif);
	transform: translate3d(-<?php echo $size['legend']['width']; ?>px,0px,0px);
	transition: all 0.3s ease;
}

.slidinglegend_slidein #legend_layer{
	opacity: 1;
	transition: all 0.4s ease;
}

.legend_group{
	color: firebrick;
	font-family: SourceSansPro1;
	font-size: 15px;
	line-height: 17px;
}

.legend_group_active_layers{
	color: firebrick;
	font-family: SourceSansPro3;
	font-size: 15px;
	line-height: 17px;
}

.legend_layer_hidden{
	font-size: 15px;
	color: gray;
}

.infobox {	
	text-decoration:none;
	overflow: auto;
}

.infotext{	
	overflow-y: auto; 
	max-height:100px;
}

.infobox:hover { 
	text-decoration:none;	
}

.infobox .infotext 
	{ display:none; 
		position:absolute;
		padding:0.5em; 
		text-decoration:none; 
}

.infobox:hover .infotext{
	display:inline;
	border:1px solid steelblue; 
	background:white; 
	text-decoration:none;
}


select.imagebacked { 
	padding: 2px 0 3px 155px; 
	background-repeat: no-repeat; 
	background-position: 0px 0px; 
	vertical-align: middle; 
}

option.imagebacked {
	padding: 2px 0 3px 1px; 
	background-repeat: no-repeat; 
	background-position: 0px 0px; 
	vertical-align: top; 
}

span.italic {
	font-style: italic; 
}

span.red {
	font-size: 15px;	
	color: #a82e2e;
	font-family: SourceSansPro2;
}

.blink {
	animation: blink 1s step-end infinite;
}
@keyframes blink { 80% { visibility: hidden; }}

span.black {
	color: #252525;
	font-size: 15px;
	font-family: SourceSansPro2;
}

#layer {
	margin:0px 8px 8px 8px;
/*	overflow:hidden; */
	clear: both;
}

a .preview_image{border:1px solid black;width: 125px;transition: all 0.25s ease;}	/* Vorschaubilder für Bilder (und PDFs) werden zunächst mit 125px Breite angezeigt und bei Hover auf 250px vergrößert */

a:hover .preview_image{width: <? echo PREVIEW_IMAGE_WIDTH; ?>px;transition: all 0.25s ease;}

.preview_doc{}

.tr_show{}

.tr_hide{}


/* Ohne Mouseover: */
.raster_record{position: relative; max-width: 135px;border:1px solid gray;margin: 5px;padding: 0.0001px;transition: all 0.25s ease;}
.raster_record .gle tr{border:none;}
.raster_record .tgle{border:none;}
.raster_record a{font-size: 0.0001px;transition: all 0.25s ease;}
/* Attribute, die ausgeblendet werden sollen: */
.raster_record .tr_hide{visibility:collapse;}
.raster_record .tr_hide td{font-size: 0.0001px;line-height: 0.0001px;padding: 0.0001px !important;transition: all 0.25s ease;}
.raster_record .tr_hide select{font-size: 0.0001px !important;width:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide select:focus{display:none;width:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide input{width:0.0001px;font-size: 0.0001px;height:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide input[type=checkbox]{display:none;width:12px;font-size: 15px;height:12px;transition: all 0.25s ease;}
.raster_record .tr_hide textarea{font-size: 0.0001px !important;transition: all 0.25s ease;}
.raster_record .tr_hide div{min-width: 0.0001px !important;}
/* Attribute, die eingeblendet werden sollen: */
.raster_record .tr_show{visibility:visible;}
.raster_record .tr_show #formelement{width: 125px;overflow: hidden;}
.raster_record .tr_show .readonly_text{word-wrap: break-word;min-width: 122px !important;}
.raster_record .tr_show td{border:none;padding: 0.0001px;transition: all 0.25s ease;}
.raster_record .tr_show select{width: 112%;height:22px;transition: all 0.25s ease;}									/* Selectfelder werden auf 130px Breite verkleinert*/
.raster_record .tr_show input{width:130px;font-size: 15px;height:22px;transition: all 0.25s ease;}		/* normale Inputfelder werden auf 130px Breite verkleinert*/
.raster_record .tr_show input[type=file]{width:0.0001px;font-size: 0.0001px;height:0.0001px;transition: all 0.25s ease;}		/* Das FileUpload-Inputfeld soll auch versteckt werden*/
.raster_record .tr_show .preview_image{width: 125px;transition: all 0.25s ease;}	/* Vorschaubilder für Bilder (und PDFs) werden zunächst mit 125px Breite angezeigt und bei Hover auf 250px vergrößert */
.raster_record .tr_show .preview_doc{width: auto;}																/* Vorschaubilder für andere Dokumente nicht */
/* Alle Attribute: */
.raster_record span{line-height:0.0001px;font-size: 0px;transition: all 0.25s ease;}
.raster_record img{width: 0px;transition: all 0.25s ease;}

/* Bei Mouseover auf Datensatz: */
.raster_record_open{position: relative; max-width: 800px;transition: all 0.25s ease;}
.raster_record_open .gle tr{border:1px dotted gray;}
.raster_record_open .gle tr:hover{border:1px solid #03476F;}
.raster_record_open td{line-height: 16px;padding: 2px;transition: all 0.25s ease;}
.raster_record_open a{font-size: 15px;transition: all 0.25s ease;}
.raster_record_open input{width:200px;font-size: 15px;height:22px;transition: all 0.25s ease;}
.raster_record_open input[type=checkbox]{width:12px;font-size: 15px;height:12px;transition: all 0.25s ease;}
.raster_record_open textarea{font-size: 15px;transition: all 0.25s ease;}
.raster_record_open .tr_show #formelement{width: 100%;overflow: visible}
.raster_record_open .tr_show input[type=file]{width:290px;font-size: 15px;height:22px;transition: all 0.25s ease;}
.raster_record_open select{font-size: 15px;display:inline;width:290px;transition: all 0.25s ease;}
.raster_record_open select:focus{font-size: 15px;display:inline;width:290px;}
.raster_record_open span{line-height:16px;font-size: 15px;transition: all 0.25s ease;}
.raster_record_open img{width: auto; transition: all 0.25s ease;}
.raster_record_open .tr_hide{visibility:visible;}
.raster_record_open .preview_image{width: 125px;transition: all 0.25s ease;}
.raster_record_open .preview_image:hover{width: 125px;transition: all 0.25s ease;}


#layer	h2{
	font-weight: bold;
	padding-left: 35px;
}

#datensatz {
	border:0px solid gray;
	border-collapse:collapse;
	padding:0px 0px 0px 0px;
}

.datensatz_header{
	background: linear-gradient(#DAE4EC 0%, lightsteelblue 100%);
	#background-color:<? echo BG_GLEHEADER; ?>;
	border-bottom: 1px solid #bbb;
}

.message_box {
	opacity: 1;
	position: fixed;
	display: block;
	top:20%;
	left:45%;
	min-width:250px;
	max-width: 550px;
/*	height:90px; */
	max-height: 600px;
	overflow: auto;
	font-size: 17px;
	font-family: SourceSansPro2;
	margin:-45px 0 0 -100px;
	padding: 10px;
	text-align: center;
	line-height: 20px;
	border: 1px solid grey;
/*	border-radius: 5px;*/
	background-color: #DAE4EC;
	box-shadow: 10px 10px 14px #777;
	z-index: 1000000;
}

.message_box_hide {
	opacity: 0;
	transition: opacity 2s ease-out;
}

.message_box_hidden, .hidden {
	display: none;
}

.message-box-notice {
	background-color: #d3ffd3;
	padding: 5px;
}
.message-box-warning {
	background-color: #dae4ec;
	padding: 5px;
}
.message-box-error {
	background-color: #ffd9d9;
	padding: 5px;
}
.message-box-type {
	float: left;
	margin-left: 10px;
}

.message-box-msg {
	float: left;
	margin-left: 20px;
	padding: 0px;
	max-width: 490px
}

table.tgle {
 border:1px solid gray;
 border-collapse:collapse;
 margin-left:auto;
 margin-right:auto;
}

thead.gle th { 
 padding: 0;
 text-align:left;
}

.gle_attribute_name { 
 padding-left: 2px;
 padding-right: 2px;
 vertical-align: top;
 background-color: <? echo BG_GLEATTRIBUTE; ?>;
}

.gle_attribute_value {
 position: relative;
 padding-left: 4px;
 padding-right: 5px;
 min-width: 30px;
}

tbody.gle tr { 
 border:1px dotted gray;
}

tbody.nogle tr,tr:hover{ 
 border: none !important;
}

tbody.gle tr:hover { 
 border:1px solid #03476F;
}


.gle1_table{
		border-collapse: collapse;
}

.gle1_table>tbody>tr>td{
		border: 1px solid grey;
}

.gle_datatype_table{
	border: 1px solid grey;
	border-collapse: collapse;
	margin: 2px 2px 2px 0;
}


.calendar { /* Fuer IE <= 6 */
	text-align: center;
	position: absolute;
	z-index: 1000000;
}

.timepicker{
	min-width: 180px;
	font-size: 18px;
	line-height: 24px;
	border: solid #112A5D 2px;
	padding: 3 0 3 0;
	margin-top: 7px;
	margin-left:auto;
	margin-right:auto;
	background-color: #F6F6F6;
	display: flex;
	justify-content: center;
}

.timepicker .time{
	flex: 0 0 auto;
	cursor: n-resize;
	width: 33px;
	padding: 0 5 0 5;
	font-size: 18px;
	border: 1px solid white;
}

.timepicker .time:focus{
	border: 1px solid grey;
}

.timepicker .time:hover{ 
	background-color: #CCD2D8;
}

.timepicker .submit{
	cursor: pointer;
	position: absolute;
	right: 8px;
	margin: 3 0 3 0;
	font-size: 1.2em;
	color: silver;
}

.timepicker .submit:hover{
	color: gray;
}

.calendar tr { 
 border:none;
}

.calendar table thead th{ 
	font-weight: bold; 
	font-size: 15px;
	line-height: 1.2em;				
	color: #BFBFC1; 
	text-align: center;
	background-color: #112A5D;
}

.calendar table thead th.weekday{ 
	font-weight: bold; 
	font-size: 14px;
	line-height: 1.2em;
	color: #112A5D; 
	text-align: center;
	background-color: #CCD2D8;
	border: solid #112A5D 1px;
}

.calendar table tbody td, .calendar table tfoot td{ 
	font-weight: normal; 
	font-size: 14px;
	line-height: 1.2em;
	width: 1.4em;
	padding-left: 0.4em; 
	padding-right: 0.4em; 
	color: #0E224B; 
	text-align: right;
	border: 1px solid #CCD2D8;
}

.calendar table tfoot td {
	font-size: 7px;
	border: none;
}

.calendar table tfoot td.calendar_week {
	text-align: left;
}

.calendar table tbody td:hover{ 
	background-color: #CCD2D8;
	font-weight: bold;
}

.calendar table tbody td.saturday{ 
	color: #9A2525;
	font-weight: normal;
}

.calendar table tbody td.sunday{ 
	color: #9A2525;
	font-weight: bold;
}

.calendar table tbody td.weekend{ 
	color: #9A2525;
}

.calendar table tbody td.today{
	background-color: #A7B5C7;
}

.calendar table thead th.prev_year, .calendar table thead th.next_year {
	border:none;
	margin: 0.1em;
	padding: 0.1em;
	line-height: 0.75em;
	font-size: 11px;
}

.calendar table tbody td.last_month, .calendar table tbody td.next_month {
	color:	 #a3afc4;
}

.calendar table{
	border-collapse: collapse;
	border: solid #112A5D 2px;
	padding: 0;
	margin-top: 7px;
	margin-left:auto;
	margin-right:auto;
	background-color: #F6F6F6;
}

.abc {
	float:left;
	text-align:center;
	width:19px;
	margin-left:3px;
}

.fstanzeigecontainer{
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: center;
		align-items: flex-start;
}

.fstanzeigehover{
								position: relative;
								float: left;
								margin: auto;
								visibility: visible;
								z-index:3;
								line-height: 30px;
}

.fstanzeigehover:hover{
								background-color:rgba(255,255,255,0.2);
}

.flexcontainer1{
		display: flex;
		justify-content:flex-start;
		flex-direction: row;
		flex-wrap: wrap;
}

.flexcontainer2{
		max-width: 800px;
		display: flex;
		justify-content:flex-start;
		flex-direction: row;
		align-items:stretch;
}

.map-right{
	border-right: 1px solid #CCCCCC;
}

.rollenwahl-gruppe {
	margin: 10px;
	border: 1px solid #cccccc;
	background: url('../graphics/bg.gif'); 
	position: relative; 
	z-index: 2;
}

.rollenwahl-table {
	width: 700px;
}

.rollenwahl-gruppen-header {
	background-color: #c7d9e6; 
	padding: 2px;
	padding-left: 8px
}

.rollenwahl-gruppen-options{
	padding : 4px;
}

.rollenwahl-option-header {
	width: 221px;
	padding : 4px;
}

.rollenwahl-option-data {
	width: 460px;
	padding : 4px;
}

.hover-border{
	padding: 2px;
}

.hover-border:hover{
	padding: 1px;
	border: 1px solid #CCCCCC;
}

.fa {
	color: gray;
}

.fa-7x {
	font-size: 7em !important;
}



.spinner {
	opacity: 0.75;
	color: LightSteelBlue;
	text-shadow: 0px 0px 4px #000000;
	animation: fa-spin 1s infinite steps(8);
}

.waitingdiv_spinner {
	pointer-events:none;
	background:none;
}

.waitingdiv_spinner_lock {
	background:rgba(200,200,200,0.3);
	pointer-events:auto;
}

.fa-color-red {
	color: #236dbf;
}

.pointer:hover {
	color: #90a0b5;
	cursor: pointer;
}

.layerOptions, #legendOptions{
	width: 220px;
	border: 1px solid #cccccc;
	background: #EDEFEF;
	padding:0px;
	position:absolute;
	z-index: 1000;
	-moz-box-shadow: 6px 5px 7px #777;
	-webkit-box-shadow: 6px 5px 7px #777;
	box-shadow: 6px 5px 7px #777;
}

.layerOptions{
	top:300px;
	right:210px;
}

#legendOptionsIcon{
	font-size: 1.1em;
	margin: 0 0 0 42;
	height: 16px;
	width: 16px;
	padding: 5px;
}

#legendOptions{
	border: 1px solid #cccccc;
	background: #EDEFEF;
	position: absolute;
	right: 100px;
	display: none;
}

.layerOptionsHeader, #legendOptionsHeader{
	background-color: #c7d9e6;
	padding: 2px 2px 2px 8px;
}

.layerOptions ul, #legendOptions ul{
	color: lightsteelblue;
	margin: 5px;
	padding: 0px;
	padding-left: 5px;
	list-style: square inside none;
}

.layerOptions li, #legendOptions li{
	margin-bottom: 5px;
}

.layerOptions span, #legendOptions span, label{
	color: #252525;
}

#legendOptions label{
	margin-left: 12px;
}

.groupOptions{
	border: 1px solid #cccccc;
	background: #EDEFEF;
	padding:0px;
	right: 240px;
	top: 300px;
	width: 200px;
	position:absolute;
	z-index: 1000;
	-moz-box-shadow: 6px 5px 7px #777;
	-webkit-box-shadow: 6px 5px 7px #777;
	box-shadow: 6px 5px 7px #777;
}

.groupOptionsHeader{
	background-color: #c7d9e6;
	padding: 2px 2px 2px 8px;
}

.groupOptions ul{
	color: lightsteelblue;
	margin: 5px;
	padding: 0px;
	padding-left: 5px;
	list-style: square inside none;
}

.groupOptions span{
	color: #252525;
}

#nu_bereich{
	position: relative;
	background-color: #dae4ec;
	padding: 7px;
	margin-bottom: 7px;
}

#nu_bereich_span{
	position: absolute;
	font-family: SourceSansPro2;
	line-height: 18px;
}

#nu_gruppe_nutzungsart{
	background-color: white;
	margin-left: 90px;
	padding: 7px;
}

#nu_gruppe_nutzungsart_span{
	font-family: SourceSansPro2;
	line-height: 18px;
}

#nu_gruppe_nutzungsart_table{
	margin-left: 10px;
	margin-top: 5px;
	margin-bottom: 10px;
	padding: 0px;
	border-spacing: 0px;
}

#nu_gruppe_nutzungsart_table th{
	text-align: left;
	font-family: SourceSansPro2;
	font-size: 15px;
	font-weight: 600;
}

#nu_gruppe_nutzungsart_table td{
	vertical-align: top;
	min-width: 85px;
	max-width: 350px;
	padding-right: 5px;
}

#gbb_grundbuchblatt{
	width: 1000px;
	background-color: #dae4ec;
	padding: 7px;
	margin: 10px;
	margin-bottom: 20px;
	border: 1px solid lightgrey;
}

#gbb_grundstueck{
	height: 100%;
	width: 600px;
	background-color: white;
	border: 1px solid lightgrey;
}

#gbb_eigentuemer{
	width: 390px;
	background-color: white;
	padding: 7px;
	height: 100%;
	border: 1px solid lightgrey;
}

.btn-new {
	padding: 5px;
	background-color: #6cc644;
	background-image: linear-gradient(#91dd70, #55ae2e);
	border: 1px solid #5aad35;
	border-radius: 5px;
	color: white;
}

.half-width {
	display: inline-block;
	width: 50%;
}

.legend-tab {
	text-align: center;
	cursor: pointer;
	font-family: SourceSansPro2;
	color: #a82e2e;
	margin-top: 1px;
	border: 1px solid #aaa;
	border-top-right-radius: 10px;
	border-top-left-radius: 10px;
	padding-top: 1px;
	padding-right: 0px;
	padding-bottom: 2px;
	padding-left: 5px;
	padding-bottom: 2px;
/*	background: linear-gradient(#DAE4EC 0%, #c7d9e6 100%);*/
}

.activ-legend-tab {
	border-bottom-color: #fff;
}

#legend_graphic {
	display: none;
	width: 246px;
	padding: 4px;
	overflow: scroll;
}

#log {
	display: none;
	background-color: #dae4ec;
	border: 1px solid #cccccc;
	margin-top: 1px;
	padding: 5px;
}

.dynamicLink{
	padding: 0 0 0 3;
}

.attribute-editor-table td {
	padding-right: 2px
}

#neuer_datensatz_button {
	display: none;
	position: relative;
	text-align: right;
	margin-right: 8px;
}

.scrolltable thead, .scrolltable tbody {
	display: block;
}

.scrolltable tbody {
  height: 590px;
  overflow-y: auto;
  overflow-x: hidden;
}

.scrolltable td {
	padding: 5 0 5 0;
}

.toggle_fa_off i{
	color: #888888;
}

.toggle_fa_off:hover i{
	color: #444444;
}

.toggle_fa_on i{
	color: #444444;
}
