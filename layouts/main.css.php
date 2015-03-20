<?php
  header('Content-type: text/css');
  include('../config.php');
?>

@font-face {
  font-family: 'SourceSansPro';
  font-style: normal;
  font-weight: 300;
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

select {
	font-size: 14px;
	font-family: SourceSansPro1;
  padding: 0 0;
}

textarea {
	font-size: 14px;
	font-family: SourceSansPro1;
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


a {  
	color: firebrick; 
	TEXT-DECORATION: none;
	font-size: 15px;
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

.menu{
	background: linear-gradient(#DAE4EC 0%, #c7d9e6 100%);
	position: relative; 
	visibility: visible; 
	left: 0px; 
	top: 0px; 
	z-index:3;
	border: 1px solid #cccccc;
	line-height : 17px;
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

.normallegend {
	border-bottom: 1px solid #aaaaaa;
}

#scrolldiv{
	width:250;
}

#legend{
	margin-left:5px;
}

#legendcontrol{
	margin-left:13px;
}

.use_for_dataset{
	background-image: url(../graphics/use_for_dataset.png);
}

.datensatz_exportieren{
	background-image: url(../graphics/datensatz_exportieren.png);
}

.drucken{
	background-image: url(../graphics/drucken.png);
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

.emboss{
	width:30px;
	height:30px;
	box-shadow:
	2px 3px 3px rgba(0, 0, 0, 0.2), 
	0px 3px 1px rgba(255, 255, 255, 0.6) inset, 
	-1px -1px 3px rgba(0, 0, 0, 0.7) inset,   
	0px 1px 1px rgba(0, 0, 0, 0.8) inset;
}

.emboss:hover{
	box-shadow:
	2px 0 7px rgba( 255, 255, 255 ,0.1), 
	0 2px 7px rgba( 255, 255, 255 ,0.1), 
	-2px 0 7px rgba( 255, 255, 255 ,0.1),
	0 -2px 7px rgba( 255, 255, 255 ,0.1),
	2px 3px 3px rgba(0, 0, 0, 0.2), 
	0px 3px 1px rgba(255, 255, 255, 0.6) inset, 
	-1px -1px 3px rgba(0, 0, 0, 0.7) inset,   
	0px 1px 1px rgba(0, 0, 0, 0.8) inset;
}

.emboss:active{
	box-shadow:
	2px 0 7px rgba( 255, 255, 255 ,0.2), 
	0 2px 7px rgba( 255, 255, 255 ,0.2), 
	-2px 0 7px rgba( 255, 255, 255 ,0.2),
	0 -2px 7px rgba( 255, 255, 255 ,0.2),
	-1px -1px 3px rgba(0, 0, 0, 0.7) inset,   
	0px 0px 1px rgba(0, 0, 0, 0.8) inset;
}

.slidinglegend_slideout {
  position:absolute;
  border-left:1px solid #CCCCCC;
  background-image: url(../graphics/bg.gif);
  transform: translate3d(-27px,0px,0px);
  transition: all 0.4s ease;
  -webkit-transform: translate3d(-27px,0px,0px);
  -webkit-transition: all 0.3s ease;
}

.slidinglegend_slideout  .table1{
  opacity: 0.0;
	transition: all 0.4s ease;
}

.slidinglegend_slidein .table1{
  opacity: 1;
	transition: all 0.4s ease;
}

.slidinglegend_slidein {
	position:absolute;
  border-left:1px solid #CCCCCC;
  background-image: url(../graphics/bg.gif);
  transform: translate3d(-255px,0px,0px);
  transition: all 0.4s ease;
  -webkit-transform: translate3d(-255px,0px,0px);
  -webkit-transition: all 0.3s ease;
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

.legend_layer{
	font-size: 15px;
}

.legend_layer_hidden{
	font-size: 15px;
	color: gray;
}

#lagebezeichnung{
	font-size: 15px;
	font-family: SourceSansPro1;
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
	#overflow:hidden;
	clear: both;
}


.preview_image{border:1px solid black;width: 125px;transition: all 0.25s ease;}	/* Vorschaubilder für Bilder (und PDFs) werden zunächst mit 125px Breite angezeigt und bei Hover auf 250px vergrößert */

.preview_image:hover{width: <? echo PREVIEW_IMAGE_WIDTH; ?>px;transition: all 0.25s ease;}

.preview_doc{}

.tr_show{}

.tr_hide{}


/* Ohne Mouseover: */
.raster_record{position: relative; max-width: 135px;border:1px solid gray;margin: 5px;padding: 0.0001px;transition: all 0.25s ease;}
.raster_record .gle tr{border:none;}
/* Attribute, die ausgeblendet werden sollen: */
.raster_record .tr_hide{visibility:collapse;}
.raster_record .tr_hide td{line-height: 0.0001px;padding: 0.0001px !important;transition: all 0.25s ease;}
.raster_record .tr_hide select{font-size: 0.0001px !important;width:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide select:focus{display:none;width:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide input{width:0.0001px;font-size: 0.0001px;height:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide textarea{font-size: 0.0001px !important;transition: all 0.25s ease;}
/* Attribute, die eingeblendet werden sollen: */
.raster_record .tr_show{visibility:visible;}
.raster_record .tr_show #formelement{width: 125px;overflow: hidden}
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
.raster_record_open input{width:200px;font-size: 15px;height:22px;transition: all 0.25s ease;}
.raster_record_open textarea{font-size: 15px;transition: all 0.25s ease;}
.raster_record_open .tr_show #formelement{width: 100%;overflow: visible}
.raster_record_open .tr_show input[type=file]{width:290px;font-size: 15px;height:22px;transition: all 0.25s ease;}
.raster_record_open select{font-size: 15px;display:inline;width:290px;transition: all 0.25s ease;}
.raster_record_open select:focus{font-size: 15px;display:inline;width:290px;}
.raster_record_open span{line-height:16px;font-size: 15px;transition: all 0.25s ease;}
.raster_record_open img{width: auto; transition: all 0.25s ease;}
.raster_record_open .tr_hide{visibility:visible;}
.raster_record_open .preview_image{width: <? echo PREVIEW_IMAGE_WIDTH; ?>px;transition: all 0.25s ease;}


#layer  h2{
	font-weight: bold;
}

#datensatz {
 border:0px solid gray;
 border-collapse:collapse;
 padding:0px 0px 0px 0px;
}

.message_box_visible{
  opacity: 1;
  position: absolute;
  top:40%;
  left:50%;
  width:200px;
  height:90px;
	font-size: 17px;
	font-family: SourceSansPro2;
	margin:-45px 0 0 -100px;
  text-align:center;
	line-height: 90px;
  border: 1px solid grey;
  background-color: #DAE4EC;
	box-shadow: 0px 0px 14px #777;
	z-index: 1000;
}

.message_box_hide{
	opacity: 0;
	transition: opacity 2s ease-out;
  position: absolute;
  top:40%;
  left:50%;
  width:200px;
  height:90px;
	font-size: 17px;
	font-family: SourceSansPro2;
	margin:-45px 0 0 -100px;
  text-align:center;
	line-height: 90px;
  border: 1px solid grey;
  background-color: #DAE4EC;
	box-shadow: 0px 0px 14px #777;
	z-index: 1000;
}

.message_box_hidden{
	display: none;
  position: absolute;
  top:40%;
  left:50%;
  width:200px;
  height:90px;
	margin:-45px 0 0 -100px;
  text-align:center;
	font-size: 17px;
	font-family: SourceSansPro2;
	line-height: 90px;
  border: 1px solid grey;
  background-color: #DAE4EC;
	box-shadow: 0px 0px 14px #777;
	z-index: 1000;
}

table.tgle {
 border:1px solid gray;
 border-collapse:collapse;
 margin-left:auto;
 margin-right:auto;
}

thead.gle th { 
 padding:4px 4px 4px 4px;
 text-align:left;
}

tbody.gle tr { 
 border:1px dotted gray;
}

tbody.gle tr:hover { 
 border:1px solid #03476F;
}

#calendar { /* Fuer IE <= 6 */
	text-align: center;
	position: absolute;
}

#calendar tr { 
 border:none;
}

#calendar table thead th{ 
	font-weight: bold; 
	font-size: 15px;
	line-height: 1.2em;				
	color: #BFBFC1; 
	text-align: center;
	background-color: #112A5D;
}

#calendar table thead th.weekday{ 
	font-weight: bold; 
	font-size: 14px;
	line-height: 1.2em;
	color: #112A5D; 
	text-align: center;
	background-color: #CCD2D8;
	border: solid #112A5D 1px;
}

#calendar table tbody td, #calendar table tfoot td{ 
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

#calendar table tfoot td {
	font-size: 7px;
	border: none;
}

#calendar table tfoot td.calendar_week {
	text-align: left;
}

#calendar table tbody td:hover{ 
	background-color: #CCD2D8;
	font-weight: bold;
}

#calendar table tbody td.saturday{ 
	color: #9A2525;
	font-weight: normal;
}

#calendar table tbody td.sunday{ 
	color: #9A2525;
	font-weight: bold;
}

#calendar table tbody td.weekend{ 
	color: #9A2525;
}

#calendar table tbody td.today{
	background-color: #A7B5C7;
}

#calendar table thead th.prev_year, #calendar table thead th.next_year {
	border:none;
	margin: 0.1em;
	padding: 0.1em;
	line-height: 0.75em;
	font-size: 11px;
}

#calendar table tbody td.last_month, #calendar table tbody td.next_month {
	color: 	#a3afc4;
}

#calendar table{
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
    display: flex;
    justify-content:flex-start;
    flex-direction: row;
    align-items:stretch;
}
