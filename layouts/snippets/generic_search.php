<? 
include(LAYOUTPATH.'languages/generic_search_'.$this->user->rolle->language.'.php');
include(SNIPPETS.'/sachdatenanzeige_functions.php');
?>

<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">

<!-- wird fuer das Absenden bei Enter benoetigt -->
document.onkeydown = function(ev){
	var key;
	ev = ev || event;
	key = ev.keyCode;
	if (ev.target.className != 'quicksearch_field' && key == 13) {
		document.GUI.suchen.click();
	}
}

function changeInputType(object, oType) {
	if(object != undefined){
		object.type = oType;
	}
}

function operatorchange(layer_id, attributname, searchmask_number){
	if(searchmask_number > 0){						// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
		prefix = searchmask_number+'_';
	}
	else prefix = '';
	if(document.getElementById(prefix+"operator_"+attributname).value == "IS NULL" || document.getElementById(prefix+"operator_"+attributname).value == "IS NOT NULL"){
		changeInputType(document.getElementById(prefix+"value_"+attributname), "hidden");
	}
	else{
		changeInputType(document.getElementById(prefix+"value_"+attributname), "text");
	}
	if(document.getElementById(prefix+"operator_"+attributname).value == "between"){
		changeInputType(document.getElementById(prefix+"value2_"+attributname), "text");
		document.getElementById(prefix+"value_"+attributname).style.width = '144px';
	}
	else{
		if(document.getElementById(prefix+"value2_"+attributname) != undefined){
			changeInputType(document.getElementById(prefix+"value2_"+attributname), "hidden");
			document.getElementById(prefix+"value2_"+attributname).value = "";
			document.getElementById(prefix+"value_"+attributname).style.width = '293px';
		}
	}
	if(document.getElementById(prefix+"_avf_"+attributname) != undefined){
		if(document.getElementById(prefix+"operator_"+attributname).value == "LIKE" || document.getElementById(prefix+"operator_"+attributname).value == "NOT LIKE"){
			document.getElementById(prefix+"_avf_"+attributname).style.display = 'none';
			document.getElementById(prefix+"_text_"+attributname).style.display = 'inline';
			document.getElementById(prefix+"text_value_"+attributname).value = '';
			document.getElementById(layer_id+"_"+attributname+"_"+prefix).disabled = true;
			document.getElementById(prefix+"text_value_"+attributname).disabled = false;
		}
		else{
			document.getElementById(prefix+"_avf_"+attributname).style.display = 'inline';
			document.getElementById(prefix+"_text_"+attributname).style.display = 'none';			
			document.getElementById(attributname+"_"+prefix).value = '';
			document.getElementById(attributname+"_"+prefix).disabled = false;
			document.getElementById(prefix+"text_value_"+attributname).disabled = true;
		}
	}
}

function suche(){
	var nogo = '';
	<?
	for($i = 0; $i < @count($this->attributes['type']); $i++) {
		if($this->attributes['mandatory'][$i] == '' or $this->attributes['mandatory'][$i] > -1){
			if($this->attributes['type'][$i] != 'geometry' AND $this->attributes['form_element_type'][$i] != 'SubFormFK' AND $this->attributes['form_element_type'][$i] != 'dynamicLink') {
				if($this->attributes['mandatory'][$i] == 1){
					if($this->attributes['alias'][$i] == ''){
						$this->attributes['alias'][$i] = $this->attributes['name'][$i];
					}		?>
					if(document.GUI.value_<? echo $this->attributes['name'][$i]; ?>.value == ''){
						if('<? echo $this->attributes['form_element_type'][$i]; ?>' != 'Autovervollständigungsfeld'
						|| (document.GUI.value_<? echo $this->attributes['name'][$i]; ?>[0].value == '' && document.GUI.value_<? echo $this->attributes['name'][$i]; ?>[0].disabled == false)
						|| (document.GUI.value_<? echo $this->attributes['name'][$i]; ?>[1].value == '' && document.GUI.value_<? echo $this->attributes['name'][$i]; ?>[1].disabled == false)
						){
							nogo = 'Das Feld <? echo $this->attributes['alias'][$i]; ?> ist ein Such-Pflichtfeld und muss ausgefüllt werden.';
						}
					}
		<?	} ?>
				test = document.GUI.value_<? echo $this->attributes['name'][$i]; ?>.value + '';
				if(test.search(/%/) > -1 && document.GUI.operator_<? echo $this->attributes['name'][$i]; ?>.value == 'IN'){
					nogo = 'Der Platzhalter % darf nur bei der Suche mit ähnlich oder nicht ähnlich verwendet werden.';
				}
		<? 	if(strpos($this->attributes['type'][$i], 'time') !== false OR $this->attributes['type'][$i] == 'date'){ ?>
					test = document.GUI.value_<? echo $this->attributes['name'][$i]; ?>.value + '';
					if(test != ''){
						if(!checkDate(test)){
							nogo = 'Das Datum hat das falsche Format';
						}
					}
		<?	} 
			}
		}
	}?>
	if(document.GUI.map_flag.value == 1){
		if(document.GUI.newpathwkt.value == ''){
			if(document.GUI.newpath.value == ''){
				nogo = 'Geben Sie ein Polygon an.';
			}
			else{
				document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			}
		}
	}
	if(nogo != ''){
		alert(nogo);
	}
	else{
		document.getElementById('gs_loader').style.display = '';
		setTimeout('document.getElementById(\'loaderimg\').src=\'graphics/ajax-loader.gif\'', 50);
		document.GUI.go_plus.value = 'Suchen';
		//document.GUI.submit();
		overlay_submit(document.GUI, true);
		document.GUI.go_plus.value = '';
	}
}


function buildwktpolygonfromsvgpath(svgpath){
	var koords;
	var wkt = '';
	if(svgpath != '' && svgpath != undefined){
		wkt = "POLYGON((";
		parts = svgpath.split("M");
		for(j = 1; j < parts.length; j++){
			if(j > 1){
				wkt = wkt + "),("
			}
			koords = ""+parts[j];
			coord = koords.split(" ");
			wkt = wkt+coord[1]+" "+coord[2];
			for(var i = 3; i < coord.length-1; i++){
				if(coord[i] != ""){
					wkt = wkt+","+coord[i]+" "+coord[i+1];
				}
				i++;
			}
		}
		wkt = wkt+"))";
	}
	return wkt;
}

function update_require_attribute(attributes, layer_id, attributenamesarray, searchmask_number){
	// attributes ist eine Liste von zu aktualisierenden Attributen und attributenamesarray ein Array aller Attribute im Formular
	if(searchmask_number > 0){						// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
		prefix = searchmask_number+'_';
	}
	else prefix = '';
	var attributenames = '';
	var attributevalues = '';
	var values = [];
	for(i = 0; i < attributenamesarray.length; i++){
		if(document.getElementById(prefix+'value_'+attributenamesarray[i]) != undefined){
			attributenames += attributenamesarray[i] + '|';
			options = document.getElementById(prefix+'value_'+attributenamesarray[i]).selectedOptions;
			if (options && options.length > 0) {
				values = Array.from(options).map(({ value }) => value);
			}
			attributevalues += values.join("','") + '|';
		}
	}
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		ahah("index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&attributenames="+attributenames+"&attributevalues="+attributevalues+"&type=select-one", new Array(document.getElementById(prefix+'value_'+attribute[i])), new Array('sethtml'));
	}
}


function showform(name){
	if(document.getElementById(name).style.display == 'block'){
		document.getElementById(name).style.display = 'none';
	}
	else{
		if(document.GUI.map_flag.value == 1){
			document.GUI.map_flag.value = '';
			document.GUI.submit();
		} else {
			document.getElementById("gsl_abfrage_speichern_form").style.display = 'none';
			document.getElementById("gsl_abfrage_laden_form").style.display = 'none';
			document.getElementById(name).style.display = 'block';
			$('.'+name+'_input').focus();
		}
	}
}

function showmap(){
	if(document.GUI.map_flag.value == 0){
		document.GUI.map_flag.value = 1;
	}
	else{
		document.GUI.map_flag.value = '';
	}
	document.GUI.submit();
}

function save_search(){
	if(document.GUI.search_name.value != ''){
		document.GUI.go_plus.value = 'Suchabfrage_speichern';
		document.GUI.submit();
	}
	else{
		alert('Bitte geben Sie einen Namen für die Suchabfrage an.');
	}
}

function delete_search(){
	if(document.GUI.searches.value != ''){
		document.GUI.go_plus.value = 'Suchabfrage_löschen';
		document.GUI.submit();
	}
	else{
		alert('Es wurde keine Suchabfrage ausgewählt.');
	}
}

function add_searchmask(layer_id){
	document.GUI.searchmask_count.value = parseInt(document.GUI.searchmask_count.value) + 1;
	newdiv = document.createElement('div');
	document.getElementById('gs_searchmasks').appendChild(newdiv);
	ahah("index.php", "go=Layer-Suche_Suchmaske_generieren&selected_layer_id="+layer_id+"&searchmask_number="+document.GUI.searchmask_count.value, new Array(newdiv), new Array('sethtml'));
}

$(document).on('click', function(e){
	if(e.target.id != 'gsl_abfrage_speichern_form_link' && e.target.closest('div').id != 'gsl_abfrage_speichern_form'){
		$('#gsl_abfrage_speichern_form').css('display','none');
		if(e.target.id != 'gsl_abfrage_laden_form_link' && e.target.closest('div').id != 'gsl_abfrage_laden_form'){
			$('#gsl_abfrage_laden_form').css('display','none');
		}
	}
});

function clear(){
	var fields = document.querySelectorAll('.gsm_tabelle_td_third select, .gsm_tabelle_td_third input');
	[].forEach.call(fields, function (field){	// noch laufende getlegend-Requests abbrechen
		field.value = '';
	});
}
  
</script>
<style>
/*
--- generic_search -----------
*/
#gs_titel {
	font-family: SourceSansPro3;
	font-size: 20px;
	margin-bottom: 0px;
	margin-top: 20px;
}
#gs_loader {
	display: none;
}
#gs_searchmasks {
	margin: 0px 20px;
}
#gs_undoder {
	max-width: 750px;
	text-align: left;
	margin: 20px;
	display: flex;
	align-items: center;
}
#gs_anzahl_treffer {
	margin-bottom: 10px;
	cursor: default;
}
#gs_suchen {
	margin-bottom: 40px;
}
/*
--- generic_search_layer_selector ---
*/
#gsl_formular {
	margin: 40px 0px 20px 0px;
	padding-left: 20px;
}
#gsl_formular select {
	border-radius: 2px;
	border: 1px solid #777;
	padding-left: 5px;
}
#gsl_gruppe_waehlen, #gsl_layer_waehlen  {
	display: flex;
	width: 500px;
}
#gsl_gruppe_waehlen>div, #gsl_layer_waehlen>div  {
	margin: 0px 0px 10px 0px;
	display: flex;
}
#gsl_gruppe_waehlen select {
	height: 25px;
	width: 360px;
}
#gsl_layer_waehlen select {
	height: 35px;
	width: 360px;
}
#gsl_gruppe_waehlen div div:first-child, #gsl_layer_waehlen div div:first-child {
	margin-right: 10px;
	align-self: center;
	width: 50px;
}
#gsl_suche_speichern>div {
	margin-top: 10px;
}
#gsl_suche_speichern a {
	cursor: pointer;	
}
#gsl_abfrage_speichern_form, #gsl_abfrage_laden_form {
	display: none;
}
#gsl_abfrage_speichern_form, #gsl_abfrage_laden_form {
	position: absolute;
	margin-top: 5px;
	left: calc(50% - 131px);
	display: none;
	border: 1px solid #aaaaaa;
	padding: 5px;
	background-color: #E6E6E6;
	box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.3);
	z-index: 1;
}
#gsl_abfrage_speichern_form input[type="text"], #gsl_abfrage_laden_form select, #gsl_suche_raeumlich_params select {
	border-radius: 2px;
	border: 1px solid #777;
	padding-left: 5px;
	height: 25px;
	}
#gsl_abfrage_speichern_form input[type="text"] {
	width: 250px;
}
#gsl_abfrage_laden_form select {
	width: 260px;
}
#gsl_abfrage_laden_form input, #gsl_abfrage_laden_form a {
	margin-left: 5px;
}
#gsl_suche_raeumlich_params {
	display: flex;
	margin: 10px 0px;
}
#gsl_suche_raeumlich_params div:not(:last-child) {
	margin-right: 10px;
}
#gsl_suche_raeumlich_params div:last-child {
	position: absolute;
	right: 0px;
}
#map, #gsl_suche_raeumlich_params div:last-child {
	margin-right: 20px;
}
#gsl_suchhinweise {
	max-width: 750px;
	margin: 0 20px 20px 20px;
	text-align: left;
}
/*
--- generic_search_mask -------------
*/
.gsm_undoder {
	margin: 20px 0px;
	display: flex;
	align-items: center;
}
span[data-tooltip] {
	--left: -10px;
	margin-left: 10px;
}
.gsm_tabelle {
	border-collapse: collapse;
	cursor: default;
	min-width: calc(300px + <? if (!$this->user->rolle->visually_impaired) { ?>90px + <? } ?>295px);
}
.gsm_tabelle td {
	padding: 3px 0 3px 0;
}
.gsm_tabelle_ueberschrift {
	height: 30px;
}
.gsm_tabelle_ueberschrift td, .gsm_tabelle_gruppe tr:first-child td {
	font-family: SourceSansPro2;
}
.gsm_tabelle_td_first {
	min-width: 300px;
}
<? if (!$this->user->rolle->visually_impaired) { ?>
.gsm_tabelle_td_second {
	min-width: 90px;
}
.gsm_tabelle_td_second>div {
	display: flex;
	align-items: center;
}
.gsm_tabelle_td_second>div span[data-tooltip] {
	--width: 300px;
}
.gsm_tabelle_td_second select {
	width: 85px;
}
<? } ?>
.gsm_tabelle_td_third {
	min-width: 295px;
}
.gsm_tabelle_ueberschrift .gsm_tabelle_td_first>span {
	margin-left: 6px;
}
.gsm_tabelle_gruppe {
	width: 100%;
	border-spacing: 0;
}
.gsm_tabelle_gruppe td {
	padding: 0;
}
.gsm_tabelle_gruppe_auf, .gsm_tabelle_gruppe_zu {
	height: 25px;
	display: flex;
	align-items: center;
	background: linear-gradient(#DAE4EC 0%, #c7d9e6 100%);
	border: 1px solid #ccc;
}

.gsm_tabelle_gruppe_auf span, .gsm_tabelle_gruppe_zu span {
	margin-left: 5px;
}
.gsm_tabelle_attribute {
	height: 29px;
}
.gsm_tabelle_attribute:hover {
	background-color: #DAE4EC;
}

.gsm_tabelle_attribute .gsm_tabelle_td_first>span {
	margin-left: 5px;
}
.gsm_tabelle_attribute .gsm_tabelle_td_third {
	padding-right: 5px;
}
.gsm_tabelle_attribute .gsm_tabelle_td_second select, 
.gsm_tabelle_attribute .gsm_tabelle_td_third > div > select, 
.gsm_tabelle_attribute .gsm_tabelle_td_third input {
	border-radius: 2px;
	border: 1px solid #777;
	height: 25px;
}
.gsm_tabelle_attribute .gsm_tabelle_td_third select, .gsm_tabelle_attribute .gsm_tabelle_td_third input:not(.time) {
		width: 293px;
}
.gsm_tabelle_attribute .gsm_tabelle_td_third>div {
	position: relative;
	display: inline-block;
	height: 25px;
}

.gsm_tabelle_td_third, .gsm_tabelle_td_third > div {
	height: inherit;
}

.gsm_tabelle_attribute .gsm_tabelle_td_third>div select option {
	margin-top: 2px;
	margin-left: 2px;
}
.gsm_tabelle_kalender {
	position: absolute;
	top: 1px;
	left: 1px;
	height: 19px;
	padding: 2px;
	background: #eee;
}
.gsm_tabelle_kalender img {
	position: relative;
	top: 3px;
}

</style>

<div id="gs_titel"><? if($this->titel != '')echo $this->titel;else echo $strTitle; ?></div>
<?php
	if (!$this->user->rolle->visually_impaired) {
		include(SNIPPETS.'/generic_search_layer_selector.php');
	}
?>
<div id="gs_formular">
<? if(!in_array($this->selected_search[0]['name'], array('', '<last_search>'))){echo '<script type="text/javascript">showsearches();</script>';} ?>
	<div id="gs_searchmasks">
<? if(count($this->attributes) > 0){
		for($m = 0; $m <= $this->formvars['searchmask_count']; $m++){ 
			$searchmask_number = $m; ?>
			<div id="gs_searchmask">
			<? include(SNIPPETS.'generic_search_mask.php'); ?>
			</div>
<?		}
    }
?>
	</div>
</div>
<? if(count($this->attributes) > 0){ ?>

<?	if ($this->user->rolle->visually_impaired) { ?>
<div id="gs_suchen_vi">
	<input type="button" name="suchen" onclick="suche();" value="<? echo $this->strSearch; ?>">
</div>
<?	} ?>
<?	if($this->layerset[0]['connectiontype'] == MS_POSTGIS){ ?>
<div id="gs_undoder">
	<a href="javascript:add_searchmask(<? echo $this->formvars['selected_layer_id']; ?>);"><? echo $strAndOr; ?></a>
	<span data-tooltip="<? echo $strAndOrHint1; ?>"></span>
</div>
<?	} ?>
<div id="gs_anzahl_treffer">
	<span><? echo $strLimit; ?><span>
	<input size="2" onkeyup="checknumbers(this, 'int2', '', '');" type="text" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>">
</div>
<?	if (!$this->user->rolle->visually_impaired) { ?>
<div id="gs_suchen">
	<input type="button" name="suchen" onclick="suche();" value="<? echo $this->strSearch; ?>">
</div>
<?	} ?>
<div id="gs_loader">
	<img id="loaderimg" src="graphics/ajax-loader.gif">
</div>
<? } ?>
<? if ($this->user->rolle->visually_impaired) {
		include(SNIPPETS.'/generic_search_layer_selector.php');
   }
 ?>

<input type="hidden" name="go_plus" value="">
<input type="hidden" name="go" value="Layer-Suche">
<input type="hidden" name="titel" value="<? echo value_of($this->formvars, 'titel'); ?>">
<input type="hidden" name="map_flag" value="<? echo value_of($this->formvars, 'map_flag'); ?>">
<input type="hidden" name="area" value="">
<input type="hidden" name="always_draw" value="<? echo $always_draw; ?>">
<input type="hidden" name="searchmask_count" value="<? echo $this->formvars['searchmask_count']; ?>">

