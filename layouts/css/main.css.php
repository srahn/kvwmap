/* main.css.php */
<?
	if ($this->user->rolle->font_size_factor) {
		$key = $this->user->rolle->gui;
		$font_size_factor = $this->user->rolle->font_size_factor;
	}
	else {
		$key = ((array_key_exists('gui', $_REQUEST) AND array_key_exists($_REQUEST['gui'], $sizes)) ? $_REQUEST['gui'] : 'layouts/gui.php');
		$font_size_factor = 1;
	}
	$size =	$sizes[$key];
?>
input {

	font-size: <? echo $font_size_factor * 15; ?>px
}

.alerts-border {
	border: 2px #ff0000 dotted;
	animation: borderblink 0.8s;
	animation-iteration-count: 5;
}

@keyframes borderblink { 50% { border-color:#fff ; }  }

.validation-error-msg-div {
	background-color: #eee;
	float: left;
	width: 390px;
	padding: 5px;
	color: red;
	border-radius: 5px;
}

#form-titel {
	font-family: SourceSansPro3;

	font-size: <? echo $font_size_factor * 20; ?>px;
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

	font-size: <? echo $font_size_factor * 17; ?>px;
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

	font-size: <? echo $font_size_factor * 17; ?>px;
	width: 32%;
	text-align: right;
}

.form-value {
	float: left;
	margin-left: 10px;

	font-size: <? echo $font_size_factor * 15; ?>px;
	line-height: 16px;
}

@font-face {
	font-family: 'SourceSansPro';
	font-style: normal;
	font-weight: 200;
	src: local('SourceSansPro'), url(fonts/SourceSansPro-Light.ttf);
}

@font-face {
	font-family: 'SourceSansPro1';
	font-style: normal;
	font-weight: 400;
	src: local('SourceSansPro1'), url(fonts/SourceSansPro-Regular.ttf);
}

@font-face {
	font-family: 'SourceSansPro2';
	font-style: normal;
	font-weight: 600;
	src: local('SourceSansPro2'), url(fonts/SourceSansPro-Semibold.ttf);
}

@font-face {
	font-family: 'SourceSansPro3';
	font-style: normal;
	font-weight: 700;
	src: local('SourceSansPro3'), url(fonts/SourceSansPro-Bold.ttf);
}

body {	
	/*font-family: SourceSansPro1, Arial, Verdana, Helvetica, sans-serif;*/
	BACKGROUND: white;
	margin:0px;

	font-size: <? echo $font_size_factor * 15; ?>px;
}

#gui-table {
	width: 900px;
	margin: auto;
}

.collapsed, div:not(.gle_tabular)~.collapsedfull {
	visibility: collapse;
  height: 0;
	padding: 0 !important;
	display: block;
}

.collapsed *{
	visibility: collapse !important;
	margin: 0 !important;
  padding: 0;
}

.collapsedfull *{
	visibility: collapse !important;
	margin: 0 !important;
	width: 0;
  padding: 0;
}

#copyrights_div table td {
	vertical-align: top;
	padding: 4px;
}

#copyrights_div table td:first-of-type {
	max-width: 300px;
}

#copyrights_div table td:nth-of-type(2) {
	max-width: 500px;
}

.notification-box {
	margin-bottom: 5px;
	padding: 5px;
	border-radius: 5px;
	background-color: antiquewhite;
	border: 1px dashed gray;
	min-height: 38px;
	-moz-hyphens: auto;
	-o-hyphens: auto;
	-webkit-hyphens: auto;
	-ms-hyphens: auto;
	hyphens: auto; 
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

.radio-label {
	margin-right: 2px;
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

	font-size: <? echo $font_size_factor * 24; ?>px;
	height: 35px;
}

form {
	margin: 0;
	padding: 0;
}

hr {
	margin: 7px; 
	background-color: #777; 
	height: 1px; 
	border-width: 0;
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
	font-size: <? echo $font_size_factor * 13; ?>px;
}

.px14 {
	font-size: <? echo $font_size_factor * 14; ?>px;
}

.px15{
	font-size: <? echo $font_size_factor * 15; ?>px;
}

.px16{
	font-size: <? echo $font_size_factor * 16; ?>px;
	line-height: 16px;
}

.px17{
	font-size: <? echo $font_size_factor * 17; ?>px;
}

.px20{
	font-size: <? echo $font_size_factor * 20; ?>px;
}

.hidden{
	display: none !important;
}

ul{
	color: lightsteelblue;
	margin: 5px;
	padding: 0 0 0 <? echo $font_size_factor * 15; ?>px;
	list-style: square outside none;
}

.ul_table td:first-of-type{
	display: inline list-item;
	color: lightsteelblue;
	margin: 4px;
	padding: 0;
	list-style: square outside none;
	line-height: 22px;
}

h1 {
	font-family: SourceSansPro3;

	font-size: <? echo $font_size_factor * 24; ?>px; 
	margin-top: 0px; 
	margin-bottom: 0px; 
	padding-top: 0px; 
	padding-bottom: 0px
}

h2 {
	font-family: SourceSansPro3;

	font-size: <? echo $font_size_factor * 20; ?>px; 
	margin-bottom: 0px;
	margin-top: 0px;
}

input[type="text"], input[type="float"], input[type="number"] {
	font-size: <? echo $font_size_factor * 14; ?>px;
	font-family: SourceSansPro1;
	height: 22px;
	padding-top: 0;
}

input[type="file"] {

	font-size: <? echo $font_size_factor * 15; ?>px;
	font-family: SourceSansPro1;
	height: 24px;
}

input[type="button"], input[type="reset"], input[type="submit"] {
	height: 24px;
	font-size: <? echo $font_size_factor * 15; ?>px;
	font-family: SourceSansPro1;
	line-height : <? echo $font_size_factor * 15; ?>px;
}

input[type="text"].transparent_input{
	font-size: <? echo $font_size_factor * 15; ?>px;
	font-family: SourceSansPro1;
	border:0px;
	background-color:	transparent;
}

span[data-tooltip] {
  position: relative;
  cursor: help;
	--left: -250px;
	--width: 500px;
	--top: 24px;
	font-weight: normal;
}

span[data-tooltip]::before {
	content: url(<? echo GRAPHICSPATH; ?>icon_i.png);
}

span[data-tooltip]:hover::after {
  content: attr(data-tooltip);
	text-align: left;
  position: absolute;
	right: 0px;
	left: -300px;		/* IE Fallback */
	left: var(--left);
  top: var(--top);
	max-width: 500px;		/* IE Fallback */
  max-width: var(--width);
	font-size: <? echo $font_size_factor * 11; ?>px;
	font-family: verdana, arial;
	box-shadow: 12px 10px 14px #777;
  border: 1px #236dbf solid;
	border-top: <? echo $font_size_factor * 15; ?>px #236dbf solid;
  background-color: #DAE4EC;
  padding: 4px;
  z-index: 10000;
	width: 400px;		/* IE Fallback */
	width: max-content;
	white-space: pre-wrap;
  display: block;
}


.custom-select {
	background-color: #e9e9ed;
  position: relative;
  width: 200px;
  border: 1px solid #888;
	border-radius: 2px;
  align-items: center;
	margin: 0 0 3px 0;
}

.custom-select .dropdown {
	z-index: 10000;
	color: black;
  list-style: none;
  padding: 0;
  display: none;
	border: 1px solid #bbb;
	border-radius: 2px;
	box-shadow: 1px 1px 6px 1px #ddd;
  position: absolute;
	width: max-content;
	min-width: 200px;
	max-width: 400px;
  top: 1px;
  right: 0;
  left: 0;
	max-height: 350px;
	overflow-y: auto;
	overflow-x: hidden;
	margin: 0 0 0 -1px;
}

.custom-select .dropdown.upward {
	top: auto;
	bottom: 24px;
}

.custom-select.active .dropdown {
  display: flex;
  flex-direction: column;
}

.custom-select .dropdown li {
  display: flex;
  align-items: baseline;
	max-width: 400px;
	min-width: 200px;
  background-color: #fff;
	min-height: 20px;
  padding: 3px;
	margin-bottom: 0;
  cursor: pointer;
}

.custom-select .dropdown li span {
	white-space: nowrap;
}

.custom-select .dropdown li:not(:last-child) {
  border-bottom: 1px solid #cdcdcd;
}

.custom-select .dropdown li.selected {
  background-color: #e0e0e6;
}

.custom-select img {
  display: inline-block;
  max-width: 30px;
	margin-right: 5px;
}

.custom-select .placeholder {
	width: 100%;
  display: flex;
  align-items: center;
  padding: 3px;
  position: relative;
	min-height: 20px;
	overflow: hidden;
}

.custom-select .placeholder.editable{
  cursor: pointer;
}

.custom-select .placeholder.editable::before,
.custom-select .placeholder.editable::after {
  content: "";
  display: inline-block;
  height: 2px;
  width: 10px;
  background-color: #aaa;
  position: absolute;
  right: 0;
}

.custom-select .placeholder.editable::before {
  transform: rotate(45deg);
  right: 20px;
}

.custom-select .placeholder.editable::after {
  transform: rotate(-45deg);
  right: <? echo $font_size_factor * 15; ?>px;
}


.select_option_link:hover{
	background-image: url(<? echo GRAPHICSPATH; ?>pfeil_rechts.gif);
	background-repeat: no-repeat;
	background-position: 307px;
}

.table_border_collapse{
	border-collapse:	collapse;
}

.table_border_collapse>tbody>tr>td{
	border:	1px solid #C3C7C3;
}

.styleFormField {
	width: 120px;
}

.layerdaten-topdiv, .userdaten-topdiv, .stellendaten-topdiv {
	height: calc(100vh - 210px);
	overflow-y: scroll;
	padding: 0px 6px;
}

.listen-tr:hover {
	background-color: #c5d6e3;
}

.listen-tr td:last-child .fa {
	margin-right: 10px;
}

.listen-tr .fa {
	padding: 3px;
}

.listen-tr.archived {
  background: rgba(255, 0, 0, 0.23);
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
	font-size: <? echo $font_size_factor * 17; ?>px;
	width: 240px;
	height: <? echo $font_size_factor * 15; ?>px;
	margin-right: 10px;
}

.input-form select {
	float: left;
	text-align: left;
	width: 150px;
}

.input-form textarea {
	float: left;
	text-align: left;
	width: 55%;
	margin-left: 5px;
}

.input-form input {

	font-size: <? echo $font_size_factor * 15; ?>px
}

.input-form input[type=text] {
	float: left;
	text-align: left;
	width: 370px;
}

.input-form input[type=checkbox] {
	float: left;
	margin-left: 0px;
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
	min-width: 800px;
}

select {

	font-size: <? echo $font_size_factor * 14; ?>px;
	font-family: SourceSansPro1;
}

select option{
	font-family: SourceSansPro1,arial;
	padding: 2px;
}

textarea {

	font-size: <? echo $font_size_factor * 14; ?>px;
	font-family: SourceSansPro1;
}

tr.tr_hover:hover{
	background-color: #DAE4EC;
}

td {	

	font-size: <? echo $font_size_factor * 15; ?>px;
	line-height: 16px;
	font-family: SourceSansPro1;
}

th {	

	font-size: <? echo $font_size_factor * 17; ?>px;
	font-family: SourceSansPro2;
}

th a {	

	font-size: <? echo $font_size_factor * 17; ?>px;
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

	font-size: <? echo $font_size_factor * 15; ?>px;
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

a.red .fa {
	color: #993333;
}

a.blue {
	color: #007bff;
	font-weight: 600; 
}

a.blue .fa {
	color: #007bff;
}

a.orange {
	color: orange;
	font-weight: 600; 
}

a.orange .fa {
	color: orange;
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

a.green .fa {
	color: #339933;
}

a.metalink {
	color: black; 
	cursor: context-menu;
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

.sachdatenanzeige_paging{
	margin-top: 10px;
}

a.invisiblelayerlink:hover{
	color: gray;
}

select[name="geom_from_layer"] {
	width: 250px;
}

.suggests {
	width: 450px;
	height: auto;
	padding:4px; 
	margin:-2px -17px -4px -4px;
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

	margin-right: <? echo $font_size_factor * 15; ?>px;
}

#datendrucklayouteditor_formular_scroll>table>tbody>tr>td>table{
	width: 100%;
	background: url('<? echo BG_IMAGE; ?>');
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
	padding: 10px 0 6px <? echo $font_size_factor * 15; ?>px;
}

#geo_name_search_result_div ul li{
	margin-top: 4px;
}

.code{
	text-align: left;
	font-family: courier;
	font-size: <? echo $font_size_factor * 12; ?>px;
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

	font-size: <? echo $font_size_factor * 15; ?>px;
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
	margin-top: <? echo $font_size_factor * 15; ?>px;
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

	font-size: <? echo $font_size_factor * 15; ?>px;
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
	background: url('<? echo BG_IMAGE; ?>');
	border: 1px solid;
	border-color: #CCC; 
	border-top: none;
	border-bottom: none;
	position: relative;
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

#menueScrollTable{
	width: <? echo ($size['menue']['width'] - 2); ?>px;
	overflow-y: auto;
	-ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}

#menueScrollTable::-webkit-scrollbar {
  display: none; /* Chrome, Safari und Opera */
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

	font-size: <? echo $font_size_factor * 15; ?>px;
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
	position: absolute;
}

.menue-auf .menue_before {
	display: inline-block;
	background: url('<? echo GRAPHICSPATH; ?>menue_top_open.gif');
}

.menue-zu .menue_before {	
	display: inline-block;
	background: url('<? echo GRAPHICSPATH; ?>menue_top.gif');
}

.obermenue {
	cursor: pointer;
	font-size: <? echo $font_size_factor * 15; ?>px;
	color: black;
	font-family: SourceSansPro2;
	height: 17px;
}

.obermenue > span {
	margin-left: 23px;
}

.hauptmenue {
	cursor: pointer;
	font-size: <? echo $font_size_factor * 15; ?>px;
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

	font-size: <? echo $font_size_factor * 15; ?>px;
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

.qr_code{
	background-image: url(<? echo GRAPHICSPATH; ?>qr_code.png);
}

.use_for_dataset{
	background-image: url(<? echo GRAPHICSPATH; ?>use_for_dataset.png);
}

.copy_dataset{
	background-image: url(<? echo GRAPHICSPATH; ?>copy_dataset.png);
}

.datensatz_exportieren{
	background-image: url(<? echo GRAPHICSPATH; ?>datensatz_exportieren.png);
}

.drucken{
	background-image: url(<? echo GRAPHICSPATH; ?>drucken.png);
}

.schnelldruck{
	background-image: url(<? echo GRAPHICSPATH; ?>schnelldruck.png);
}

.merken{
	background-image: url(<? echo GRAPHICSPATH; ?>merken.png);
}

.nicht_mehr_merken{
	background-image: url(<? echo GRAPHICSPATH; ?>nicht_mehr_merken.png);
}

.url_dataset{
	background-image: url(<? echo GRAPHICSPATH; ?>url_dataset.png);
}

.datensatz_loeschen{
	background-image: url(<? echo GRAPHICSPATH; ?>datensatz_loeschen.png);
}

.edit_geom{
	background-image: url(<? echo GRAPHICSPATH; ?>edit_geom.png);
}

.zoom_normal{
	background-image: url(<? echo GRAPHICSPATH; ?>zoom_normal.png);
}

.zoom_highlight{
	background-image: url(<? echo GRAPHICSPATH; ?>zoom_highlight.png);
}

.zoom_select{
	background-image: url(<? echo GRAPHICSPATH; ?>zoom_select.png);
}

.switch_gle{
	background-image: url(<? echo GRAPHICSPATH; ?>switch_gle.png);
}

.url_extent{
	background-image: url(<? echo GRAPHICSPATH; ?>url_extent.png);
}

.save_extent{
	background-image: url(<? echo GRAPHICSPATH; ?>save_extent.png);
}

.load_extent{
	background-image: url(<? echo GRAPHICSPATH; ?>load_extent.png);
}

.save_image{
	background-image: url(<? echo GRAPHICSPATH; ?>save_image.png);
}

.resize_map{
	background-image: url(<? echo GRAPHICSPATH; ?>resize_map.png);
}

.optionen{
	background-image: url(<? echo GRAPHICSPATH; ?>optionen.png);
}

.karte{
	background-image: url(<? echo GRAPHICSPATH; ?>karte.png);
}

.logout{
	background-image: url(<? echo GRAPHICSPATH; ?>logout.png);
}

.gesamtansicht {
	background-image: url(<? echo GRAPHICSPATH; ?>gesamtansicht.png);
}

.notiz{
	background-image: url(<? echo GRAPHICSPATH; ?>notiz.png);
}

.hilfe{
	background-image: url(<? echo GRAPHICSPATH; ?>hilfe.png);
}

.timetravel{
	background-image: url(<? echo GRAPHICSPATH; ?>timetravel.png);
}

.save_layers{
	background-image: url(<? echo GRAPHICSPATH; ?>save_layers.png);
}

.load_layers{
	background-image: url(<? echo GRAPHICSPATH; ?>load_layers.png);
}

.tool_info{
	background-image: url(<? echo GRAPHICSPATH; ?>tool_info.png);
}

.layer{
	background-image: url(<? echo GRAPHICSPATH; ?>layer.png);
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
	left: 0;
	top: 1;
}

.button:active{
	background-color: #e3e8ed;
}

.button:hover::after{
	opacity: 1;
}

#header {
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
	top: <? echo $font_size_factor * 15; ?>px;
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
	padding: <? echo $font_size_factor * 15; ?>px;
}

#map{
	transform: translate(0, 0);		/* ansonsten wird das SVG unscharf */
}

#mapimage{
	position: relative;
}

#mapbuttons {
	padding-left: 1px;
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

#hist_range_div {
	margin-left: 10px; 
	overflow-x: scroll; 
	width: 600px; 
	padding-top: 1px;
}

#hist_timestamp3{
	-webkit-appearance: none;
	box-shadow: 0px -4px 0px #74A9D8;
	border-radius: 1px;
	border: 0px solid #010101;
	height: 7px;
	margin: 8px 0 2px 0;
	background: linear-gradient(90deg, #cccccc 1px, rgba(238, 238, 238, 0) 1px) repeat-x content-box, linear-gradient(90deg, #cccccc 1px, rgba(238, 238, 238, 0) 1px) repeat-x content-box;
	background-size: 49.95px;
}

#hist_timestamp3::-moz-range-thumb {
  box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4);
  border: 1px solid #00001E;
  height: 13px;
  width: 13px;
  border-radius: 15px;
  background: #FFFFFF;
  cursor: pointer;
	transform: translate(0px, -5px);
}

#hist_timestamp3::-webkit-slider-thumb {
	-webkit-appearance: none;
  box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4);
  border: 1px solid #00001E;
  height: 13px;
  width: 13px;
  border-radius: 15px;
  background: #FFFFFF;
  cursor: pointer;
	margin-top: -13px;
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

	font-size: <? echo $font_size_factor * 15; ?>px;
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
	background-image: url(<? echo BG_IMAGE; ?>);
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

.dropZone{
	position: relative;
	z-index: 1000;
	margin: 0;
	height: 1px;
	width: 100%;
}

.dropZone.ready{
	margin: -12 0 -12 15;
	height: 25px;
	transition: height 0.1s ease, margin 0.1s ease;
}

.dropZone.over{
	height: 51px;
	margin: -13 0 -13 15;
	transition: height 0.1s ease, margin 0.1s ease;
}

.dragObject{
	background-color: #f6f6f6;
	box-shadow: 1px 1px 4px #aaa;
	z-index: 100;
	margin: 3 0 0 15;
	padding: 2 2 2 3;
	height: 23px;
	min-width: 177px;
	border: 1px solid grey;
	cursor: pointer;
	text-align: start;
}

.dragObject:hover{
	background-color: #fcfcfc;
}

.dragObject.dragging{
	box-shadow: 3px 3px 6px #aaa;
}

.dragObject.picked{
	visibility: hidden;
	max-height: 16px;
}

.dragObject.over{
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
	width: 93%;
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

	font-size: <? echo $font_size_factor * 15; ?>px;
	line-height: 17px;
}

.legend_group_active_layers{
	color: firebrick;
	font-family: SourceSansPro3;

	font-size: <? echo $font_size_factor * 15; ?>px;
	line-height: 17px;
}

.legend-group-checkbox {
	vertical-align: text-top;
  margin: 3px 0px 0px -2px;
}

.legend_layer_hidden{

	font-size: <? echo $font_size_factor * 15; ?>px;
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

	font-size: <? echo $font_size_factor * 15; ?>px;	
	color: #a82e2e;
	font-family: SourceSansPro2;
}

.blink {
	animation: blink 1s step-end infinite;
}
@keyframes blink { 80% { visibility: hidden; }}

span.black {
	color: #252525;

	font-size: <? echo $font_size_factor * 15; ?>px;
	font-family: SourceSansPro2;
}

#layer {
	margin: 18px 8px 8px 8px;
	clear: both;
}

/* Vorschaubilder für Bilder (und PDFs) werden zunächst mit 125px Breite angezeigt und bei Hover auf PREVIEW_IMAGE_WIDTH vergrößert */

.td_preview_image{
	padding-top: 125px;	
  padding-right: 125px;
	position: relative;
}

/* Klasse für Bilder, die sich beim Hovern vergrößern (alle die nicht im Rasterlayout angezeigt werden) */
a .preview_image{
	border:1px solid black;
	max-width: 125px;
	max-height: 125px;
	position: absolute;
	top: 5px;
	transition: all 0.25s ease;
}

a:hover .preview_image{
	max-width: <? echo PREVIEW_IMAGE_WIDTH; ?>px;
	max-height: <? echo PREVIEW_IMAGE_WIDTH; ?>px;
	transition: all 0.25s ease;
	transition-delay: 0.2s;
	z-index: 9999999999;
}

/* Klasse für Bilder im Rasterlayout, die bei Mouseover eine Vorschau rechts oben in der Ecke anzeigen */
a .preview_image_hover{
	border:1px solid black;
	width: auto !important;
	max-width: 125px;
	max-height: 125px;
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
.raster_record .tr_hide input[type=checkbox]{display:none;width:12px;font-size: <? echo $font_size_factor * 15; ?>px;height:12px;transition: all 0.25s ease;}
.raster_record .tr_hide textarea{font-size: 0.0001px !important;transition: all 0.25s ease;}
.raster_record .tr_hide div{min-width: 0.0001px !important; transition: all 0.25s ease;}
.raster_record .tr_hide .readonly_text{font-size: 0.0001px !important;min-width: 0.0001px !important;max-width: 0.0001px !important; transition: all 0.25s ease;}
.raster_record .tr_hide .datensatz_header{display: none}
/* Attribute, die eingeblendet werden sollen: */
.raster_record .tr_show{visibility:inherit;}
.raster_record .tr_show .readonly_text{font-size: <? echo $font_size_factor * 15; ?>px;min-width: 122px !important;max-width: 122px !important;transition: all 0.25s ease;}
.raster_record .tr_show td{border:none;padding: 0.0001px;transition: all 0.25s ease;}
.raster_record .tr_show select{width: 112%;height:22px;transition: all 0.25s ease;}									/* Selectfelder werden auf 130px Breite verkleinert*/
.raster_record .tr_show input{width:130px;font-size: <? echo $font_size_factor * 15; ?>px;height:22px;transition: all 0.25s ease;}		/* normale Inputfelder werden auf 130px Breite verkleinert*/
.raster_record .tr_show input[type=file]{width:0.0001px;font-size: 0.0001px;height:0.0001px;transition: all 0.25s ease;}		/* Das FileUpload-Inputfeld soll auch versteckt werden*/
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
.raster_record_open a{font-size: <? echo $font_size_factor * 15; ?>px;transition: all 0.25s ease;}
.raster_record_open input[type=text]{width:200px;font-size: <? echo $font_size_factor * 15; ?>px;height:22px;transition: all 0.25s ease;}
.raster_record_open input[type=checkbox]{width:12px;font-size: <? echo $font_size_factor * 15; ?>px;height:12px;transition: all 0.25s ease;}
.raster_record_open textarea{font-size: <? echo $font_size_factor * 15; ?>px;transition: all 0.25s ease;}
.raster_record_open .tr_show #formelement{width: 100%;overflow: visible}
.raster_record_open .readonly_text{font-size: <? echo $font_size_factor * 15; ?>px;min-width: 400px !important;max-width: 400px !important;transition: all 0.25s ease;}
.raster_record_open .tr_show input[type=file]{width:290px;font-size: <? echo $font_size_factor * 15; ?>px;height:22px;transition: all 0.25s ease;}
.raster_record_open select{font-size: <? echo $font_size_factor * 15; ?>px;display:inline;width:290px;transition: all 0.25s ease;}
.raster_record_open select:focus{font-size: <? echo $font_size_factor * 15; ?>px;display:inline;width:290px;transition: all 0.25s ease;}
.raster_record_open span{line-height:16px;font-size: <? echo $font_size_factor * 15; ?>px;transition: all 0.25s ease;}
.raster_record_open img{width: auto; transition: all 0.25s ease;}
.raster_record_open .tr_hide{visibility:visible;}


#layer	h2{
	font-weight: bold;
	padding-left: 35px;
}

.datensatz {
	border:1px solid #999;
	border-collapse:collapse;
	padding:0px 0px 0px 0px;
	margin: 7px 5px 11px 0;
	width: fit-content;
}

#nds_edit .datensatz {
	border: none;
}

.datensatz_header {
	background: linear-gradient(#DAE4EC 0%, lightsteelblue 100%);
	#background-color:<? echo BG_GLEHEADER; ?>;
	border-bottom: 1px solid #bbb;
}

.identifier_text {
	font-family: SourceSansPro3;

	font-size: <? echo $font_size_factor * 17; ?>px;
}

#message_box {
	white-space: pre-line;
	opacity: 1;
	position: fixed;
	display: none;
	top: 48%;
	left: 45%;
	min-width:250px;
	max-width: 550px;
/*	height:90px; */
	max-height: 600px;
	overflow: auto;

	font-size: <? echo $font_size_factor * 17; ?>px;
	font-family: SourceSansPro2;
	margin: -300px 0 0 -220px;
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
	text-align: left;
	width: 90%;
}

#overlayheader {
	box-shadow: inset 0px -1px 0px 0px #ccc; 
	position:fixed; 
	top: 0px; 
	margin: 0px 0 -11px 0; 
	padding-top: 0; 
}

#overlayheader2 {
	margin-top: -2px; 
}

#overlayheader, #overlayheader2 {
	background: url(<? echo BG_IMAGE; ?>);
	z-index: 1000;
	display: flex;
	flex-wrap: wrap;
	flex-direction: row;
	justify-content: flex-start;
	width: 100%;
}

#contentdiv {
	background: url(<? echo BG_IMAGE; ?>);
	width: fit-content;
	position:relative;
}

#overlayfooter {
	background: url(<? echo BG_IMAGE; ?>);
	border: 1px solid #cccccc;
	width: 100%; 
	position:fixed; 
	bottom: 0px;
}

.dstable{
	#max-width: 900px;
	width: 100%;
}

table.tgle {
	width: 100%;
	border: 0px solid gray;
	border-collapse:collapse;
	margin-left:auto;
	margin-right:auto;
}

.gledata > tr > td, .glegeom > tr > td {

	font-size: <? echo $font_size_factor * 15; ?>px;
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

.gle-view {
	display: flex;
	position: sticky;
  right: 18px;
  height: 18px;
	clip-path: polygon(100% 0px, 100% 18px, calc(100% - 18px) 18px, calc(100% - 18px) 0px);
	transition: clip-path 0.2s ease;
}

.gle-view:hover {
	clip-path: polygon(100% 0px, 100% 18px, 0px 18px, 0px 0px);
}

.gle-view-button.active {
	order: 2;
}

#column_options_button{
	cursor: pointer;
  margin: 1px 8px 0 8px;
}

#gle_column_options_div {
	position: fixed;
	right: 2px;
	margin-right: 35px;
	text-align: left;
	border: 1px solid gray;
	padding: 4px;
	background-color: white;
	border-radius: 4px;
	z-index: 1000;
	box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.3);
}

.gle_tabs{
	display: flex;
	margin: 2px 0 -11px 0;
	border-left: 1px solid #bbb;
}

.gle_tabs > div{
	padding: 1px 5px 0 8px;
	cursor: pointer;
	border: 1px solid #bbb;
	border-left: none;
	background-color: #fff;
	color: #aaa;
	white-space: nowrap;
	border-radius: 0 5px 0 0;
	height: 21px;
}

.gle_tabs > div.active_tab{
	border-bottom: 1px dashed #bbb;
	background-color: <? echo BG_GLEATTRIBUTE; ?>;
	color: black;
}

.gle_tabs > div:hover{
	background-color: <? echo BG_GLEATTRIBUTE; ?>;
}

.gle_arrayelement_table {
	width: 100%;
	margin: 10px 0;
}

.gle_datatype_table {
	width: 100%;
	table-layout: auto;
	border: 1px solid #bbb;
	border-bottom: none;
	border-collapse: collapse;
	border-left: 3px solid #555;
}

.gle_datatype_table td.gle_attribute_name {
	width: 10%;
	background-clip: padding-box;
	padding: 3px 2px;
	vertical-align: top;
	background-color: <? echo BG_GLEATTRIBUTE; ?>;
}

.gle_attribute_value, .gle_attribute_name {
	border: 1px solid #bbb;
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

table.tgle .gledata select:not(.suggests), table.tgle .gledata input:not([type=radio]):not([type=checkbox]) {
	height: 25px;
}

.readonly_text{
	word-wrap: break-word;
}

.list_edit_div {
	overflow-y:auto; 
	max-height: 400px;
}

.gle1_table{
	border-collapse: collapse;
	position: relative;
}

.gle1_table>thead>tr>td{
	border: 1px solid grey;
	padding: 2px;
}

.gle1_table>tbody>tr>td{
	border: 1px solid grey;
	padding: 2px;
}

.gle_datatype_table{
	border: 1px solid grey;
	border-collapse: collapse;
	margin: 2px 2px 2px 0;
}

.gle_neu_link {
	text-align: right;
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
	vertical-align: top;
}

.subFormListItem > a{
	display: flex;
	align-items: center;
}

.subFormShowCount{
	margin: 5px 5px 5px 8px;
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
	margin: 2px 7px 0px 10px;
	background-color: #236dbf;
	vertical-align: top;
	display: inline-block;
}

.formelement-link {
	padding: 5px;
	margin: 5px 1px;
	background: #e3e3e3;
	border-radius: 2px;
}

.gle_result_filter {
	position: absolute; 
	top: 2px;
	right: 2px;
  z-index: 100;
	text-align: right;
}

.gle_result_filter .value_list {
	max-height: 250px;
	max-width: 250px;
	scrollbar-width: thin;
	display: none;
}

.gle_result_filter:hover .value_list {
	display: block;
}

.calendar {
	text-align: center;
	position: absolute;
	z-index: 1000000;
	right: 0px;
	left: 240px;
	bottom: 30px;
	width: 220px;
	height: fit-content;
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

	font-size: <? echo $font_size_factor * 14; ?>px;
	line-height: 1.2em;				
	color: #F6F6F6; 
	text-align: center;
	background-color: #112A5D;
	height: 1.5em;
}

.calendar table thead th.weekday{ 
	font-weight: normal; 

	font-size: <? echo $font_size_factor * 14; ?>px;
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

	font-size: <? echo $font_size_factor * 14; ?>px;
	line-height: 1.2em;
	width: 31px;
	padding-right: 4px; 
	color: #0E224B; 
	text-align: right;
	border: 1px solid #CCD2D8;
}

.calendar table tfoot td {

	font-size: <? echo $font_size_factor * 7; ?>px;
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

	font-size: <? echo $font_size_factor * 11; ?>px;
}

.calendar table tbody td.last_month, .calendar table tbody td.next_month {
	color:	 #a3afc4;
	cursor: default;	
}


.timepicker{
	min-width: 205px;

	font-size: <? echo $font_size_factor * 18; ?>px;
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

	font-size: <? echo $font_size_factor * 18; ?>px;
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

	font-size: <? echo $font_size_factor * 19; ?>px;
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
	width: 580px;
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
	<!-- width: 270px; -->
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
/*	color: #555; */
}

.fa-7x {
	font-size: <? echo $font_size_factor * 117; ?>px !important;
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
	top: 42px;
	right: <? echo $font_size_factor * 15; ?>px;
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
	padding: 100px;
	text-align: center;
}

.layerOptions, #legendOptions{
	min-width: 200px;
	background: #EDEFEF;
	padding:0px;
	position:absolute;
	z-index: 1000;
	box-shadow: 6px 5px 7px rgba(0, 0, 0, 0.4);
}

.layerOptions{
	top:300px;
	right:240px;
}

.layerOptionsIcon{

	font-size: <? echo $font_size_factor * 14; ?>px;
	height: 14px;
	width: 14px;
	padding: 3px;
	margin-left: -4;
}

#legendOptionsIcon{

	font-size: <? echo $font_size_factor * 18; ?>px;
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

.layerOptions li{
	line-height: 22px;
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

	font-size: <? echo $font_size_factor * 15; ?>px;
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
	padding-right: 2px;
}

#neuer_datensatz_button {
	/* display: none; */
	position: relative;
	text-align: right;
	margin-right: 8px;
}

.scrolltable {
	position: relative;
}

.scrolltable thead, .scrolltable tbody {
	display: block;
}

.scrolltable tbody {
	overflow-y: auto;
	overflow-x: hidden;
}

.scrolltable_header{
	position: absolute;
	top: -30px;
}

.scrolltable_footer{
	position: absolute;
	bottom: -30px;
	padding-left: 5px;
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
  font-size: <? echo $font_size_factor * 12; ?>px;
  line-height: 1.3;
  min-height: 50px;
  width: 100%;
  box-sizing:border-box;
  -moz-box-sizing:border-box;
}

.small-gray {
	color: gray;

	font-size: <? echo $font_size_factor * 12; ?>px;
}

.green {
	color: green;
}

.orange {
	color: orange;
}

.blue {
	color: blue;
}

.white {
	color: white;
}

.yellow {
	color: #c6b000;
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

#shareRollenlayerDiv {
	color: black;
	margin-bottom: 5px;
}

.map-info-button {
	color: #666;
	margin-top: 8px;
	font-size: 110%
}

#nds_titel {
	font-family: SourceSansPro3;
	font-size: 20px;
	margin-bottom: 20px;
	margin-top: 10px;
}

#nds_formular {
	margin: 40px 0px 20px 0px;
	padding-left: 20px;
}
.nds_select  {
	display: flex;
	width: 500px;
	margin: 0px 0px 10px 0px;
}
.nds_select select {
	height: 25px;
	width: 360px;
	border-radius: 2px;
	border: 1px solid #777;
	padding-left: 5px;
}
.nds_select div:first-child {
	margin-right: 10px;
	align-self: center;
	width: 50px;
}
#nds_submit {
	display:flex;
	flex-flow: row nowrap;
	justify-content: center;
	margin-bottom: 15px;
}
#nds_submit>div {
	display: flex;
	align-items: center;
}
#nds_submit input {
	margin-right: 5px;
}
#nds_submit input[type="checkbox"] {
	margin-top: auto;
	margin-bottom: auto;
	margin-left: 15px;
}
#nds_submit span {
	margin: auto;
	margin-left: 3px;
}

#dataset_operations {
	text-align: left;
  padding: 2;
}

#nds_edit #dataset_operations {
	display: none;
}

#gemkgschl-name-Trennerwort-span {
	display: none;
	margin-left: 10px;
}

.chosen-container-multi {
  border: none;
}

.chosen-container-multi .chosen-choices {
	background-image: none;
	padding: 7px;
	border: none !important;
	border-radius: 4px;
	-webkit-box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1) !important;
	box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1) !important;
}

.chosen-container-multi .chosen-choices {
	position: relative;
	overflow: hidden;
	margin: 0;
	padding: 0 5px;
	width: 100%;
	height: auto;
	border: 1px solid #aaa;
	background-color: #fff;
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(1%, #eee), color-stop(15%, #fff));
	background-image: linear-gradient(#eee 1%, #fff 15%);
	cursor: text;
}

.chosen-container-multi .chosen-choices li.search-choice {
	-webkit-box-shadow: none;
	box-shadow: none;
	padding-top: 7px;
	padding-bottom: 7px;
	padding-left: 10px;
	padding-right: 26px;
	border: none;
	background-image: none;
}

.chosen-container-multi .chosen-choices li.search-choice {
	position: relative;
	margin: 3px 5px 3px 0;
	padding: 3px 20px 3px 5px;
	border: 1px solid #aaa;
	max-width: 100%;
	border-radius: 3px;
	background-color: #eeeeee;
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(20%, #f4f4f4), color-stop(50%, #f0f0f0), color-stop(52%, #e8e8e8), to(#eee));
	background-image: linear-gradient(#f4f4f4 20%, #f0f0f0 50%, #e8e8e8 52%, #eee 100%);
	background-size: 100% 19px;
	background-repeat: repeat-x;
	background-clip: padding-box;
	-webkit-box-shadow: 0 0 2px #fff inset, 0 1px 0 rgba(0, 0, 0, 0.05);
	box-shadow: 0 0 2px #fff inset, 0 1px 0 rgba(0, 0, 0, 0.05);
	color: #333;
	line-height: 13px;
	cursor: default;
}

.chosen-container-multi .chosen-choices li {
	float: left;
	list-style: none;
}

.chosen-container-multi .chosen-choices li.search-field input[type="text"] {
	height: 32px;
	font-size: 14px;
}

.chosen-container-multi .chosen-choices li.search-field input[type="text"] {
	margin: 1px 0;
	padding: 0;
	height: 25px;
	outline: 0;
	border: 0 !important;
	background: transparent !important;
	-webkit-box-shadow: none;
	box-shadow: none;
	color: #999;
	font-size: 100%;
	font-family: sans-serif;
	line-height: normal;
	border-radius: 0;
	width: 25px;
}

.chosen-container-multi .chosen-choices li.search-field {
	margin: 0;
	padding: 0;
	white-space: nowrap;
}

.chosen-container-multi .chosen-choices li {
	float: left;
	list-style: none;
}

.selectable-item {
  color: black;
}

.selected-item {
	color: gray;
	display: none;
}

.highlighted-item {
	background-color: #ff9d9d;
	cursor: pointer;
}

#chosen-container {
	width: 93%;
	float:left;
}

#chosen-choices {
	list-style: none;
	margin-left: -15px;
}

.chosen-item {
	position: relative;
	margin: 3px 5px 3px 0;
	padding: 3px 20px 3px 5px;
	border: 1px solid #aaa;
	max-width: 100%;
	border-radius: 3px;
	background-color: #eeeeee;
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(20%, #f4f4f4), color-stop(50%, #f0f0f0), color-stop(52%, #e8e8e8), to(#eee));
	background-image: linear-gradient(#f4f4f4 20%, #f0f0f0 50%, #e8e8e8 52%, #eee 100%);
	background-size: 100% 19px;
	background-repeat: repeat-x;
	background-clip: padding-box;
	-webkit-box-shadow: 0 0 2px #fff inset, 0 1px 0 rgba(0, 0, 0, 0.05);
	box-shadow: 0 0 2px #fff inset, 0 1px 0 rgba(0, 0, 0, 0.05);
	color: #333;
	line-height: 13px;
	cursor: default;
}

#chosen-drop {
	display: none;
}

#chosen-buttons {
	float: right;
	width: 42px;
	margin-top: 7px;
}

.chosen-item-close {
	cursor: pointer;
}

.delete-button {

}

.delete-button:hover {
	background-color: #ffa2a2;
}
