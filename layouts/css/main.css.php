<?php
	header('Content-type: text/css');
	include('../../config.php');
	
	global $sizes;
	$key = ((array_key_exists('gui', $_REQUEST) AND array_key_exists($_REQUEST['gui'], $sizes)) ? $_REQUEST['gui'] : 'layouts/gui.php');
	$size =	$sizes[$key];
?>

#form-titel {
	font-family: SourceSansPro3;
	font-size: 20px;
	margin-bottom: 0px;
	margin-top: 20px;
}
#form_formular-main {
	margin: 40px 0px 20px 0px;
	padding-left: 20px;
	cursor: default;
}
.form_formular-aic {
	align-items: center;
}
.form_formular-input {
	width:100%;
	margin: 0px 0px 10px 0px;
	display: flex;
	flex-flow: row nowrap;
}
#form_formular-main select, #form_formular-main input[type="text"], .form_formular-input-selector select {
	border-radius: 2px;
	border: 1px solid #777;
	padding-left: 5px;
}
#form_formular-main .form_formular-input select, #form_formular-main input[type="text"] {
	height: 25px;
}
.form_formular-input > div:first-child, .form_formular-input-selector > div:first-child {
	width: 210px;
	text-align: right;
	margin-right: 10px;
	font-family: SourceSansPro3;
	line-height: 20px;
	font-size: 17px;
}
.form_formular-input-selector > div:first-child {
	margin-top: 3px;
}

.clear {
	clear: both;
}

.form-field {
	font-family: SourceSansPro1;
}

.form-label {
	float: left;
	font-size: 17px;
	width: 32%;
	text-align: right;
}

.form-value {
	float: left;
	margin-left: 10px;
	font-size: 15px;
	line-height: 16px;
}

@font-face {
	font-family: 'SourceSansPro';
	font-style: normal;
	font-weight: 200;
	src: local('SourceSansPro'), url(../../fonts/SourceSansPro-Light.ttf);
}
@font-face {
	font-family: 'SourceSansPro1';
	font-style: normal;
	font-weight: 400;
	src: local('SourceSansPro1'), url(../../fonts/SourceSansPro-Regular.ttf);
}
@font-face {
	font-family: 'SourceSansPro2';
	font-style: normal;
	font-weight: 600;
	src: local('SourceSansPro2'), url(../../fonts/SourceSansPro-Semibold.ttf);
}
@font-face {
	font-family: 'SourceSansPro3';
	font-style: normal;
	font-weight: 700;
	src: local('SourceSansPro3'), url(../../fonts/SourceSansPro-Bold.ttf);
}

body {	
	font-family: SourceSansPro1, Arial, Verdana, Helvetica, sans-serif;
	BACKGROUND:white;
	margin:0px;
	font-size: 15px;
}

#gui-table {
	width: 900px;
	margin: auto;	
}


input[type="checkbox"], input[type="radio"] {
	position: relative;
	margin-bottom: auto;
	width: 14px;
	height: 14px;
	color: #000;
	border: 1px solid #353535;
	border-radius: 3px;
	appearance: none;
	-moz-appearance:none;
	-webkit-appearance:none;
	-o-appearance:none;
	outline: 0;
	cursor: pointer;
	background: #fff;
}

#scrolldiv input[type="checkbox"], #scrolldiv input[type="radio"] {
	margin: 3px;
}

input[type="checkbox"][disabled="true"], input[type="checkbox"][disabled=""], input[type="radio"][disabled="true"], input[type="radio"][disabled=""] {
	border: 1px solid #aaa;
}

input[type="radio"] {
	border-radius: 50%;
}

input[type="checkbox"]::before, input[type="radio"]::before {
	position: absolute;
	content: '';
	display: block;
	top: 1px;
	left: 3px;
	width: 3px;
	height: 6px;
	border-style: solid;
	border-color: #fff;
	border-width: 0 2px 2px 0;
	transform: rotate(35deg);
	opacity: 0;
}

input[type="radio"]::before {
	top: 3px;
	left: 3px;
	width: 2px;
	height: 2px;
	border-width: 2px;
	border-radius: 50%;
	background-color: #fff;
}

input[type="checkbox"]:checked, input[type="radio"]:checked {
	color: #fff;
	background: #4f9f72;
}

input[type="checkbox"][disabled="true"]:checked, input[type="checkbox"][disabled=""]:checked, input[type="radio"][disabled="true"]:checked, input[type="radio"][disabled=""]:checked {
	background: #fff0;
}

input[type="checkbox"]:hover, input[type="radio"]:hover {
	color: #fff;
	border-color: #4999d8;
}

input[type="checkbox"][disabled="true"]:hover, input[type="checkbox"][disabled=""]:hover, input[type="radio"][disabled="true"]:hover, input[type="radio"][disabled=""]:hover {
	color: #fff0;
	background: #fff0;
	border-color: #aaa;
	cursor: default;
}

input[type="checkbox"]:checked::before, input[type="radio"]:checked::before {
	opacity: 1;
}

input[type="checkbox"][disabled="true"]:checked::before, input[type="checkbox"][disabled=""]:checked::before, input[type="radio"][disabled="true"]:checked::before, input[type="radio"][disabled=""]:checked::before {
	border-color: #aaa;
}

input[type="checkbox"]:hover::before, input[type="radio"]:hover::before {
	opacity: 1;
	border-color: #bfbfbf;
}

input[type="checkbox"][disabled="true"]:hover::before, input[type="checkbox"][disabled=""]:hover::before, input[type="radio"][disabled="true"]:hover::before, input[type="radio"][disabled=""]:hover::before {
	opacity: 0;
}

input[type="checkbox"]:checked:hover::before, input[type="radio"]:checked:hover::before {
	opacity: 1;
	border-color: #fff;
}

input[type="checkbox"][disabled="true"]:checked:hover::before, input[type="checkbox"][disabled=""]:checked:hover::before, input[type="radio"][disabled="true"]:checked:hover::before, input[type="radio"][disabled=""]:checked:hover::before {
	background-color: #fff0;
	border-color: #aaa;
}

input[type="radio"]:hover::before {
	background-color: #bfbfbf;
}

input[type="radio"]:checked:hover::before {
	background-color: #fff;
}


input[type="button"][value='«'], input[type="button"][value='»'] {
	margin: 0.2em;
	padding: 0px 0.5em 3px 0.5em;
	font-size: 1.5em;
	height: 35px;
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

.hidden{
	display: none;
}

ul{
	color: lightsteelblue;
	margin: 5px;
	padding: 0 0 0 15px;
	list-style: square outside none;
}

.ul_table td:first-of-type{
	display: inline list-item;
	color: lightsteelblue;
	margin: 4px;
	padding: 0;
	list-style: square outside none;
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
	padding-top: 0;
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

span[data-tooltip] {
  position: relative;
  cursor: help;
	--left: -250px;
	--width: 500px;
}

span[data-tooltip]::before {
	content: url(../../graphics/icon_i.png);
}

span[data-tooltip]:hover::after {
  content: attr(data-tooltip);
	text-align: left;
  position: absolute;
	right: 0px;
	left: -300px;		/* IE Fallback */
	left: var(--left);
  top: 24px;
	max-width: 500px;		/* IE Fallback */
  max-width: var(--width);
	font-size: 11px;
	font-family: verdana, arial;
	box-shadow: 12px 10px 14px #777;
  border: 1px #236dbf solid;
	border-top: 15px #236dbf solid;
  background-color: #DAE4EC;
  padding: 4px;
  z-index: 10000;
	width: 400px;		/* IE Fallback */
	width: max-content;
	white-space: pre-wrap;
  display: block;
}

.select_option_link:hover{
	background-image: url(../../graphics/pfeil_rechts.gif);
	background-repeat: no-repeat;
	background-position: 307px;
}

.table_border_collapse{
	border-collapse:	collapse;
}

.table_border_collapse>tbody>tr>td{
	border:	1px solid #C3C7C3;
}

.layerdaten-topdiv, .userdaten-topdiv, .stellendaten-topdiv {
	height: calc(100vh - 210px);
	overflow-y: scroll;
	padding: 0px 6px;
}

.listen-tr:hover {
	background-color: #DAE4EC;
}

.listen-tr td:last-child .fa {
	margin-right: 10px;
}

.listen-tr .fa {
	padding: 3px;
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

select option{
	font-family: SourceSansPro1,arial;
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
	white-space: pre-wrap;			/* Since CSS 2.1 */
	white-space: -moz-pre-wrap;	/* Mozilla, since 1999 */
	white-space: -pre-wrap;			/* Opera 4-6 */
	white-space: -o-pre-wrap;		/* Opera 7 */
	word-wrap: break-word;			/* Internet Explorer 5.5+ */
	background: inherit;
	font: inherit;
	margin: 0;
	padding: 0;
}

a, img, a table span {	
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

.sachdatenanzeige_paging{
	margin-top: 10px;
}

#params_table{
	width: 1000px
}

#datendrucklayouteditor{
	display: flex;
	margin: 10px;
}

#datendrucklayouteditor_formular{
	margin-left: 5px;
}

#datendrucklayouteditor_formular_scroll{
	background-color: white;
	border: 1px solid #aaa;
	overflow-y: auto;
	max-height: 800px;
	width: 100%;
	min-width: 600px;
	margin-right: 15px;
}

#datendrucklayouteditor_formular_scroll>table>tbody>tr>td>table{
	width: 100%;
	background: url('../../<? echo BG_IMAGE; ?>');
}

#datendrucklayouteditor_formular_scroll>table>tbody>tr>td>table>tbody:first-of-type>tr:first-of-type{
	background-color: #c5d4e7;
}

#geo_name_search_result_div{
	position: absolute;
	z-index: 1000000;
	background-color: white;
	box-shadow: 12px 10px 14px rgba(0, 0, 0, 0.3);
	border: 1px solid #ddd;
}

#geo_name_search_result_div ul{
	padding: 10px 0 6px 15px;
}

#geo_name_search_result_div ul li{
	margin-top: 4px;
}

.code{
	text-align: left;
	font-family: courier;
	font-size: 12px;
	line-height: 17px;
}

#login_table{
	margin-top: 20px; 
	background-color: <? echo BG_DEFAULT; ?>;
	box-shadow: 12px 10px 14px rgba(0, 0, 0, 0.4); 
	border: 1px solid #bbbbbb; 
	background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);
}

.config_param_saved_0{
	border-collapse: separate;
	outline: 2px solid red;
}

#nachweisanzeige_optionen{
	border-collapse: collapse; 
	border: 1px solid grey; 
	margin-top: 7px;
}

#ortho_points{
	font-size: 15px;
	text-align: left;
}

.ortho_point_div{
	padding: 0 0 5px 30px;
}

.ortho_point_div input{
	width: 50px;
	margin-left: 5px;
}

#data_import_upload_zone{
	position: relative;
	width: 600px;
	height: 300px;
	outline: 2px dashed steelblue;
	outline-offset: -6px;
	background:	linear-gradient(#adc7da 0%, #DAE4EC 50%, #adc7da 100%);
	opacity: 0.7;
	transition: all 0.2s ease;	
}

#data_import_upload_zone:hover{
	opacity: 0.9;
	transition: all 0.2s ease;
}

#data_import_upload_zone.dragover{
	opacity: 0.5;
	outline-offset: -2px;
	transition: all 0.2s ease;
}

#data_import_upload_zone #text{
	position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
	color: #333;
}

#data_import_upload_progress{
	display: table;
	width: 100%;
	border-collapse: separate;
  border-spacing: 5px;
}

#data_import_upload_progress .file_status{
	display: table-row;
	border: 1px solid #bbb;
	padding: 3px;
}

#data_import_upload_progress .file_status div{
	display: table-cell;
	vertical-align: top;
}

#data_import_upload_progress .file_status .serverResponse{
	text-align: right;
}

#data_import_upload_progress .file_status .serverResponse div{
	margin: 0;
	display: inline-block;
}


.print_options_box{
	width: 200px;
	margin-right: <? echo $size['legend']['hide_width']; ?>px;
	margin-bottom: 5px;
	border: 1px solid #aaa;
	padding: 5px;
}

.buttonlink{
	height: 13px;
	display: inline-block;
	border: 1px solid #cccccc;
	border-color: #bbbbbb;
	background: linear-gradient(#eff3f6, lightsteelblue);
	text-align: center;
	color: black;
	border-radius: 5px;
	padding: 2px 7px 6px 7px;
	margin: 0px 2px 0px 2px;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
	max-width: 300px;
}

.buttonlink:hover, .buttonlink:focus{
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
	width: <? echo ($size['menue']['width'] - 2); ?>px;
	background: url('../../<? echo BG_IMAGE; ?>');
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
	background: url('../../graphics/menue_top_open.gif');
}

.menue-zu .menue_before {	
	display: inline-block;
	background: url('../../graphics/menue_top.gif');
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

.untermenue::before {
	width: 4px;
	height: 4px;
	border-radius: 50%;
	content: '';
	margin: 8px 7px 0px 10px;
	background-color: #236dbf;
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

.header-button {
	float: right;
	padding: 4px;
	font-size: 150%;
}

.header-button:hover {
	cursor: pointer;
}

.options-button {
	width: 18px;
}

.button-menue{
	flex: 0 0 auto;
	margin: 0 0 2 0;
}

.text-menue{
	flex: 0 0 100%;
}

.use_for_dataset{
	background-image: url(../../graphics/use_for_dataset.png);
}

.copy_dataset{
	background-image: url(../../graphics/copy_dataset.png);
}

.datensatz_exportieren{
	background-image: url(../../graphics/datensatz_exportieren.png);
}

.drucken{
	background-image: url(../../graphics/drucken.png);
}

.schnelldruck{
	background-image: url(../../graphics/schnelldruck.png);
}

.merken{
	background-image: url(../../graphics/merken.png);
}

.nicht_mehr_merken{
	background-image: url(../../graphics/nicht_mehr_merken.png);
}

.datensatz_loeschen{
	background-image: url(../../graphics/datensatz_loeschen.png);
}

.edit_geom{
	background-image: url(../../graphics/edit_geom.png);
}

.zoom_normal{
	background-image: url(../../graphics/zoom_normal.png);
}

.zoom_highlight{
	background-image: url(../../graphics/zoom_highlight.png);
}

.zoom_select{
	background-image: url(../../graphics/zoom_select.png);
}

.switch_gle{
	background-image: url(../../graphics/switch_gle.png);
}

.url_extent{
	background-image: url(../../graphics/url_extent.png);
}

.save_extent{
	background-image: url(../../graphics/save_extent.png);
}

.load_extent{
	background-image: url(../../graphics/load_extent.png);
}

.save_image{
	background-image: url(../../graphics/save_image.png);
}

.resize_map{
	background-image: url(../../graphics/resize_map.png);
}

.optionen{
	background-image: url(../../graphics/optionen.png);
}

.karte{
	background-image: url(../../graphics/karte.png);
}

.logout{
	background-image: url(../../graphics/logout.png);
}

.gesamtansicht {
	background-image: url(../../graphics/gesamtansicht.png);
}

.notiz{
	background-image: url(../../graphics/notiz.png);
}

.hilfe{
	background-image: url(../../graphics/hilfe.png);
}

.timetravel{
	background-image: url(../../graphics/timetravel.png);
}

.save_layers{
	background-image: url(../../graphics/save_layers.png);
}

.load_layers{
	background-image: url(../../graphics/load_layers.png);
}

.tool_info{
	background-image: url(../../graphics/tool_info.png);
}

.layer{
	background-image: url(../../graphics/layer.png);
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
	height: <? echo ($size['header']['height'] - 2); ?>px;
	border: 1px solid; 
	border-color: #ffffff #cccccc #bbbbbb;
}

#footer{
	height: <? echo ($size['footer']['height'] - 2); ?>px;
	border: 1px solid; 
	border-color: #aaa #cccccc #cccccc;
}

#agreement_message{
	box-shadow: 12px 10px 14px rgba(0, 0, 0, 0.4);
	background-color: #ffffff;
	border: 2px solid #000000;
	position: absolute;
	top: 15px;
	left: 50%;
	transform: translate(-50%, 0);
	height: 90%;
}

#agreement_message_body{	
	position: relative;
	border-bottom: 2px solid #000000;
	height: 93%;
	overflow-y:	auto;
}

#agreement_message_button{
	height: 50px;
	position: relative;
	text-align: center;
	padding: 15px;
}

#map{
	transform: translate(0, 0);		/* ansonsten wird das SVG unscharf */
}

#mapimage{
	position: relative;
}

#showcoords, #showmeasurement{
	position: absolute; 
	bottom: 0px;
	text-align: left;
	border-top: 1px solid #aaaaaa;
	border-right: 1px solid #aaaaaa;
	padding: 5 0 5 5; 
	background-color: <? echo BG_MENUETOP; ?>;
	box-shadow: 3px 0px 4px rgba(0, 0, 0, 0.3);
}

#hist_timestamp_form{
	position: absolute; 
	top: 50px;
	text-align: left;
	border-top: 1px solid #aaaaaa;
	border-right: 1px solid #aaaaaa;
	border-bottom: 1px solid #aaaaaa;
	padding: 5 0 5 5; 
	background-color: <? echo BG_MENUETOP; ?>;
	box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.3);
	z-index: 100000;
}

#scale_bar {
	background: <? echo BG_MENUETOP; ?>;
	border-top: 1px solid #aaaaaa;
	height: <? echo ($size['scale_bar']['height'] - 1); ?>px;
}

#lagebezeichnung_bar{
	background: <? echo BG_MENUETOP; ?>;
	border-top: 1px solid #aaaaaa;
	height: <? echo ($size['lagebezeichnung_bar']['height'] - 1); ?>px;
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
	height: <? echo ($size['map_functions_bar']['height'] - 1); ?>px;
}

#legend_switch{
	background: linear-gradient(90deg, #c1d6f2, #DAE4EC);
}

#legenddiv {
	border-left: 1px solid #ccc;
	box-shadow: 0px 1px 0px #bbb;
	display: flex; 
	flex-direction: column;
	background-image: url(../../<? echo BG_IMAGE; ?>);
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
	width: 100%;
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
	min-width: 177px;
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
}

.slidinglegend_slideout {
	cursor: pointer;
	right: -<?php echo $size['legend']['width']; ?>px;
	position:absolute;
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
	#padding: 2px 0 3px 155px; 
	color: rgba(255, 255, 255, 0);
	background-repeat: no-repeat; 
	background-position: 0px 0px; 
	vertical-align: middle; 
}

option.imagebacked {
	color: black;
	padding: 2px 0 3px 1px; 
	background-repeat: no-repeat; 
	background-position: 0px 0px; 
	vertical-align: top; 
}

span.italic {
	font-style: italic; 
}

tr.selected{
	background-color: ccc;
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

/* Vorschaubilder für Bilder (und PDFs) werden zunächst mit 125px Breite angezeigt und bei Hover auf PREVIEW_IMAGE_WIDTH vergrößert */
a .preview_image{
	border:1px solid black;
	max-width: 125px;
	max-height: 125px;
	transition: all 0.25s ease;
}

a:hover .preview_image{
	max-width: <? echo PREVIEW_IMAGE_WIDTH; ?>px;
	max-height: <? echo PREVIEW_IMAGE_WIDTH; ?>px;
	transition: all 0.25s ease;
	transition-delay: 0.5s;
}

.preview_doc{}

.tr_show{}

.tr_hide{}


/* Ohne Mouseover: */
.raster_record{position: relative; width: 135px;border:1px solid gray;margin: 5px;padding: 0.0001px;transition: all 0.25s ease;}
.raster_record .gle tr{border:none;}
.raster_record .tgle{border:none;}
.raster_record a{font-size: 0.0001px;transition: all 0.25s ease;}
.raster_record #formelement{width: 135px;overflow: hidden;}
/* Attribute, die ausgeblendet werden sollen: */
.raster_record .tr_hide{visibility:collapse;}
.raster_record .tr_hide td{font-size: 0.0001px;line-height: 0.0001px;padding: 0.0001px !important;transition: all 0.25s ease;}
.raster_record .tr_hide select{font-size: 0.0001px !important;width:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide select:focus{display:none;width:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide input{width:0.0001px;font-size: 0.0001px;height:0.0001px;transition: all 0.25s ease;}
.raster_record .tr_hide input[type=checkbox]{display:none;width:12px;font-size: 15px;height:12px;transition: all 0.25s ease;}
.raster_record .tr_hide textarea{font-size: 0.0001px !important;transition: all 0.25s ease;}
.raster_record .tr_hide div{min-width: 0.0001px !important; transition: all 0.25s ease;}
.raster_record .tr_hide .readonly_text{font-size: 0.0001px !important;min-width: 0.0001px !important;max-width: 0.0001px !important; transition: all 0.25s ease;}
.raster_record .tr_hide .datensatz_header{display: none}
/* Attribute, die eingeblendet werden sollen: */
.raster_record .tr_show{visibility:visible;}
.raster_record .tr_show .readonly_text{font-size: 15px;min-width: 122px !important;max-width: 122px !important;transition: all 0.25s ease;}
.raster_record .tr_show td{border:none;padding: 0.0001px;transition: all 0.25s ease;}
.raster_record .tr_show select{width: 112%;height:22px;transition: all 0.25s ease;}									/* Selectfelder werden auf 130px Breite verkleinert*/
.raster_record .tr_show input{width:130px;font-size: 15px;height:22px;transition: all 0.25s ease;}		/* normale Inputfelder werden auf 130px Breite verkleinert*/
.raster_record .tr_show input[type=file]{width:0.0001px;font-size: 0.0001px;height:0.0001px;transition: all 0.25s ease;}		/* Das FileUpload-Inputfeld soll auch versteckt werden*/
.raster_record .tr_show .preview_image{width: 125px;transition: all 0.25s ease;}	/* Vorschaubilder für Bilder (und PDFs) werden zunächst mit 125px Breite angezeigt und bei Hover auf 250px vergrößert */
.raster_record .tr_show .preview_doc{width: auto;}																/* Vorschaubilder für andere Dokumente nicht */
.raster_record .tr_show .buttonlink{display: none}
/* Alle Attribute: */
.raster_record span{line-height:0.0001px;font-size: 0px;transition: all 0.25s ease;}
.raster_record img{width: 0px;transition: all 0.25s ease;}

/* Bei Mouseover auf Datensatz: */
.raster_record_open{position: relative; max-width: 800px;transition: all 0.25s ease;}
.raster_record_open .gle tr{border:1px dotted gray;}
.raster_record_open .gle tr:hover{border:1px solid #03476F;}
.raster_record_open td{line-height: 16px;padding: 2px;transition: all 0.25s ease;}
.raster_record_open a{font-size: 15px;transition: all 0.25s ease;}
.raster_record_open input[type=text]{width:200px;font-size: 15px;height:22px;transition: all 0.25s ease;}
.raster_record_open input[type=checkbox]{width:12px;font-size: 15px;height:12px;transition: all 0.25s ease;}
.raster_record_open textarea{font-size: 15px;transition: all 0.25s ease;}
.raster_record_open .tr_show #formelement{width: 100%;overflow: visible}
.raster_record_open .readonly_text{font-size: 15px;min-width: 400px !important;max-width: 400px !important;transition: all 0.25s ease;}
.raster_record_open .tr_show input[type=file]{width:290px;font-size: 15px;height:22px;transition: all 0.25s ease;}
.raster_record_open select{font-size: 15px;display:inline;width:290px;transition: all 0.25s ease;}
.raster_record_open select:focus{font-size: 15px;display:inline;width:290px;transition: all 0.25s ease;}
.raster_record_open span{line-height:16px;font-size: 15px;transition: all 0.25s ease;}
.raster_record_open img{width: auto; transition: all 0.25s ease;}
.raster_record_open .tr_hide{visibility:visible;}
.raster_record_open .preview_image{width: 125px;transition: all 0.25s ease;}
.raster_record_open .preview_image:hover{width: 125px;transition: all 0.25s ease;}


#layer	h2{
	font-weight: bold;
	padding-left: 35px;
}

.datensatz {
	border:1px solid #999;
	border-collapse:collapse;
	padding:0px 0px 0px 0px;
}

.datensatz_header{
	background: linear-gradient(#DAE4EC 0%, lightsteelblue 100%);
	#background-color:<? echo BG_GLEHEADER; ?>;
	border-bottom: 1px solid #bbb;
}

#message_box {
	opacity: 1;
	position: fixed;
	display: none;
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
	box-shadow: 10px 10px 14px rgba(0, 0, 0, 0.4);
	z-index: 1000000;
}

.message-box {
	padding: 5px 5px 5px 0px;
}
.message-box-notice {
	background-color: #d3ffd3;
}
.message-box-warning {
	background-color: #dae4ec;
}
.message-box-error {
	background-color: #ffd9d9;
}
.message-box-type {
	float: left;
	margin-left: 10px;
	width: 1px;
	margin-right: 2px;
}
.message-box-msg {
	float: left;
	margin-left: 20px;
	padding: 0px;
	max-width: 480px;
	text-align: left;
}

.dstable{
	#max-width: 900px;
	width: 100%;
}

table.tgle {
	border: 0px solid gray;
	border-collapse:collapse;
	margin-left:auto;
	margin-right:auto;
}

.gledata > tr > td, .glegeom > tr > td {
	font-size: 15px;
	border: 1px solid #bbb;
}

.tglegroup{
	border: 1px solid #bbb;
	border-bottom: none;
}

thead.gle th { 
 padding: 0;
 text-align:left;
}

.gle-attribute-name {
	height: 32px;
	position: relative;
	background-clip: padding-box;
	padding: 3px 2px;
	vertical-align: top;
	background-color: <? echo BG_GLEATTRIBUTE; ?>;
}


.gle-attribute-mandatory {
}

.gle_attribute_value {
	background-clip: padding-box;
	position: relative;
	padding: 0px 5px;
	min-width: 30px;
	height: 32px;
}

.gle_attribute_value input[type="checkbox"] {
	margin: 5.5px;
}

.gle_attribute_value input[type="radio"] {
	margin-top: 6px;
	margin-bottom: 1px;
}

.gle_attribute_value label {
	position: relative;
	vertical-align: bottom;
}

table.tgle .glehead tr {
	height: 26px;
}

table.tgle .glehead td {
	background: <? echo BG_GLEATTRIBUTE; ?>;
}

table.tgle .gledata select, table.tgle .gledata input:not([type=radio]), table.tgle .gledata textarea {
	border-radius: 2px;
	border: 1px solid #777;
}

table.tgle .gledata select, table.tgle .gledata input:not([type=radio]):not([type=checkbox]) {
	min-height: 25px;
}

.readonly_text{
	word-wrap: break-word;
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

.subForm:not(:empty){
	border: 1px solid #bbb;
	margin: 10px 5px 10px 5px;
	box-shadow: 0px 0px 4px #999;
	padding: 0 5px 5px 0;
	background: #fdfdfd;
}

.subFormListItem{
	height: 20px;
	padding: 0 0 8px 0;
}

.gle_hr{
	height: 3px; 
	margin: 15 0; 
	color: <? echo BG_GLEHEADER; ?>; 
	background: <? echo BG_GLEHEADER; ?>;
}

.subFormListItem > a:before{
	width: 4px;
	height: 4px;
	border-radius: 50%;
	content: '';
	margin: 8px 7px 0px 10px;
	background-color: #236dbf;
	vertical-align: top;
	display: inline-block;
}

.calendar {
	text-align: center;
	position: absolute;
	z-index: 1000000;
	right: 0px;
	left: 0px;
	bottom: 30px;
	width: 220px;
}

.calendar table{
	width: 100%;
	border-collapse: collapse;
	outline: solid #112A5D 1px;
	padding: 0px;
	margin-left:auto;
	margin-right:auto;
	background-color: #F6F6F6;
	height: 200px;
}

.calendar tr { 
 border:none;
}

.calendar table thead th{ 
	font-weight: bold; 
	font-size: 14px;
	line-height: 1.2em;				
	color: #F6F6F6; 
	text-align: center;
	background-color: #112A5D;
	height: 1.5em;
}

.calendar table thead th.weekday{ 
	font-weight: normal; 
	font-size: 14px;
	line-height: 1.2em;
	height: 2em;
	width: 1.3em;
	padding-left: 0.4em; 
	padding-right: 0.4em; 
	color: #0E224B; 
	text-align: center;
	background-color: #CCD2D8;
}

.calendar table tbody td, .calendar table tfoot td{ 
	font-weight: normal; 
	font-size: 14px;
	line-height: 1.2em;
	width: 31px;
	padding-right: 4px; 
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
	cursor: default;	
}


.timepicker{
	min-width: 205px;
	font-size: 18px;
	line-height: 24px;
	outline: solid #112A5D 1px;
	padding: 3px 0px;
	margin-top: 3px;
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
	right: 10px;
	margin-top: 1px;
	font-size: 1.2em;
	color: silver;
}

.timepicker .submit:hover{
	color: gray;
}

.calendar>div>table, .timepicker {
	box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.3);
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
								line-height: 30px;
}

.fstanzeigehover:hover{
								background-color:rgba(255,255,255,0.4);
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

.map-right{
	border-right: 1px solid #CCCCCC;
}

#rollenwahl_main_div {
	width: 830px; 
	min-height: 430px
}

#rollenwahl_optionen_div {
	background:rgb(248, 248, 249);
	margin: 10px;
	border: 1px solid #bbb;
}

.rollenwahl-gruppe {
	margin:0;
	border:0;
}

.rollenwahl-table {
	width: 100%;
}

.rollenwahl-gruppen-options{
	padding : 4px;
}

.rollenwahl-option-header {
	width: 270px;
	padding : 4px;
}

.rollenwahl-option-data {
	width: 560px;
	padding : 4px;
}

.button_selection{
	width: 390px;
	display: flex;
	flex-wrap: wrap;
}

.button_selection div{
	padding: 0 0 3px 4px;
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

.fa:hover {
	color: #555;
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

.user-options {
	display: none;
	min-width: 200px;
	border: 1px solid #cccccc;
	background-color: #FFFFFF;
	position:absolute;
	box-shadow: 6px 5px 7px rgba(0, 0, 0, 0.3);
	top: 30px;
	right: 15px;
	z-index: 2000;
}

.user-options-header {
	background-color: #c7d9e6;
	padding: 7px 7px 7px 7px;
/*	width: 242px; */
	font-weight: bolder;
	z-index: 2000;
}

.user-options-section-header {
	padding: 7px 0px 7px 7px;
	z-index: 2000;
}

.options-devider {
	background-color: #e1e4e8;
	height: 1px;
	margin: 0px 1px 0px 1px;
	z-index: 2000;
}

.user-option {
	padding: 7px 0px 7px 7px;
	z-index: 2000;
	cursor: pointer;
}

.user-option:hover {
	background: linear-gradient(#dae4ec 0%, #c7d9e6 100%);
	z-index: 2000;
}

.sperr-div {
	display: none;
	position: fixed;
	width: 100%;
	height: 100%;
	left: 0;
	top: 0;
	background: rgba(51,51,51,0.2);
	z-index: 1000;
}

.layerOptions, #legendOptions{
	min-width: 220px;
	border: 1px solid #cccccc;
	background: #EDEFEF;
	padding:0px;
	position:absolute;
	z-index: 1000;
	box-shadow: 6px 5px 7px rgba(0, 0, 0, 0.4);
}

.layerOptions{
	top:300px;
	right:210px;
}

.layerOptionsIcon{
	font-size: 0.9em;
	height: 14px;
	width: 14px;
	padding: 3px;
	margin-left: -4;
}

#legendOptionsIcon{
	font-size: 1.1em;
	margin: 0 5px 0 42px;
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

.layerOptionHeader {
	padding-left: 9px;
}

.layerOptions li, #legendOptions li{
	margin-bottom: 5px;
}

.layerOptions span, #legendOptions span, label{
	color: #252525;
}

.layerOptions input[type="text"]{
	width: 195px;
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
	box-shadow: 6px 5px 7px rgba(0, 0, 0, 0.4);
}

.groupOptionsHeader{
	background-color: #c7d9e6;
	padding: 2px 2px 2px 8px;
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
	display: inline-table;
}

.scrolltable tbody {
	overflow-y: auto;
	overflow-x: hidden;
}

.scrolltable_header{
	position: absolute;
	margin-top: -25px;
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

.sql-statement {
  text-align: left;
  font-size: 12px;
  line-height: 1.3;
  min-height: 50px;
  width: 100%;
  box-sizing:border-box;
  -moz-box-sizing:border-box;
}

.small-gray {
	color: gray;
	font-size: 12px;
}

.green {
	color: green;
}

.red {
	color: red;
}

.edit_button {
  margin-left: 5px;
}

.edit_button.fa-pencil:hover {
	cursor: pointer;
	color: red;
}

.edit_button.fa-undo:hover {
	cursor: pointer;
	color: blue;
}

.edit_button.fa-check:hover {
	cursor: pointer;
	color: green;
}