<? 
include(LAYOUTPATH.'languages/generic_search_'.rolle::$language.'.php');
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

var attrname;
var prefix;
function operatorchange(layer_id, attributname, searchmask_number){
	attrname = attributname;
	if (searchmask_number > 0) {						// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
		prefix = searchmask_number+'_';
	}
	else prefix = '';

	if (document.getElementById(prefix+"operator_" + attributname).value == "IS NULL" || document.getElementById(prefix+"operator_" + attributname).value == "IS NOT NULL") {
		document.getElementById(prefix + "_third_" + attributname).style.display = "none";
	}
	else {
		document.getElementById(prefix + "_third_" + attributname).style.display = "";
	}
	if (document.getElementById("gsm_default_input2_" + attributname) != undefined) {
		if (document.getElementById(prefix+"operator_" + attributname).value == "between"){
			document.getElementById(prefix+"gsm_default_input1_" + attributname).style.display = "block";
			document.getElementById(prefix+"gsm_default_input2_" + attributname).style.display = "block";
		}
		else {
			if (typeof document.getElementById(prefix+"value2_"+attributname) !== 'undefined'){
				document.getElementById(prefix+"gsm_default_input2_" + attributname).style.display = "none";
			}
		}
	}
	if (document.getElementById(prefix+"_avf_" + attributname) != undefined) {
		if (document.getElementById(prefix + "operator_" + attributname).value == "LIKE" || document.getElementById(prefix + "operator_" + attributname).value == "NOT LIKE") {
			document.getElementById(prefix + "_avf_" + attributname).style.display = 'none';
			document.getElementById(prefix + "_text_" + attributname).style.display = 'inline';
			document.getElementById(prefix + "text_value_" + attributname).value = '';
			document.getElementById(layer_id + "_" + attributname + "_" + prefix).disabled = true;
			document.getElementById(prefix + "text_value_" + attributname).disabled = false;
		}
		else {
			document.getElementById(prefix + "_avf_" + attributname).style.display = 'inline';
			document.getElementById(prefix + "_text_" + attributname).style.display = 'none';			
			document.getElementById(layer_id + "_" + attributname + "_" + prefix).value = '';
			document.getElementById(layer_id + "_" + attributname + "_" + prefix).disabled = false;
			document.getElementById(prefix + "text_value_" + attributname).disabled = true;
		}
	}

}

function suche(){
	var nogo = '';
	
	<?
	for($i = 0; $i < count_or_0($this->attributes['type'] ?: []); $i++) {
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
				nogo = 'Geben Sie ein Polygon an oder schließen Sie die Karte';
			}
			else{
				document.GUI.newpathwkt.value = SVG.buildwktpolygonfromsvgpath(document.GUI.newpath.value);
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
		values = [];
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
	if(document.getElementById(name).style.display == 'flex'){
		document.getElementById(name).style.display = 'none';
	}
	else{
		if(document.GUI.map_flag.value == 1){
			document.GUI.map_flag.value = '';
			document.GUI.submit();
		} else {
			document.getElementById("gsl_abfrage_speichern_form").style.display = 'none';
			document.getElementById("gsl_abfrage_laden_form").style.display = 'none';
			document.getElementById(name).style.display = 'flex';
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
	if(e.target.id != 'gsl_abfrage_speichern_form_link' && e.target.closest('div')?.id != 'gsl_abfrage_speichern_form'){
		$('#gsl_abfrage_speichern_form').css('display','none');
		if(e.target.id != 'gsl_abfrage_laden_form_link' && e.target.closest('div')?.id != 'gsl_abfrage_laden_form'){
			$('#gsl_abfrage_laden_form').css('display','none');
		}
	}
});

function clear(){
	window.location.href='index.php?go=Layer-Suche&selected_layer_id=' + document.GUI.selected_layer_id.value;
}
  
</script>
			 
	
															
	
					 
														 
								 
										
									
 
						
							 
 
								 
									
 
						 
									
									
							
							 
										 
 
										
										 
								 
 
						
										 
 
	
																		 
	
							 
													 
										
 
											
										
												
									 
 
																					
							 
							
 
																									
													
							 
 
														
							
							
 
													 
							
							
 
																																								 
										
										
						 
 
													
									
 
												
									
 
																											
							 
 
																											
										
								 
												 
							 
													 
							
													 
																						
						
 
																																																										
										
												
									 
							
	
																								
							
 
																
							
 
																													
									
 
														 
							 
									
 
																									
										
 
																						
										
						
 
																									
										
 
									 
									
													
									
 
	
																		 
	
							
									
							 
										 
 
										
							 
									 
 
							
													 
								 
																																																 
 
								 
											
 
													 
							
 
																																		 
														 
 
											 
									
 
																									 
												
								 
 
														
							 
										 
 
																							 
								
 
															 
						 
 
			 
											 
									
 
																											
									
 
										 
						 
									 
 
												
						
 
																								 
							
							 
										 
																											 
												
 

																													 
									
 
												
							
 
															
													 
 

																									 
									
 
																							
										
 
																											
																														 
																										
										
												
							
 
																																																										
							 
 
																									
										
											 
							
 

																										
								 
 

																																
								 
									
 
											 
										
					
					 
							
							
									
 
													 
										
					
 

				

<div id="gs_titel" name="titel"><? if($this->titel != '')echo $this->titel;else echo $strTitle; ?></div>
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
	<input class="gs_suchen_input" type="button" name="suchen" onclick="suche();" value="<? echo $this->strSearch; ?>">
</div>
<?	} ?>
<?	if($this->layerset[0]['connectiontype'] == MS_POSTGIS){ ?>
<div id="gs_undoder">
	<a href="javascript:add_searchmask(<? echo $this->formvars['selected_layer_id']; ?>);"><? echo $strAndOr; ?></a>
	<span data-tooltip="<? echo $strAndOrHint; ?>"></span>
</div>
<?	} ?>
<div id="gs_anzahl_treffer">
	<span><? echo $strLimit; ?><span>
	<input size="2" onkeyup="checknumbers(this, 'int2', '', '');" type="text" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>">
</div>
<?	if (!$this->user->rolle->visually_impaired) { ?>
<div id="gs_suchen">
	<input class="gs_suchen_input" type="button" name="suchen" onclick="suche();" value="<? echo $this->strSearch; ?>">
	<div class="gs_sucht_opac"></div>
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

