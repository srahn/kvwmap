<? include('funktionen/input_check_functions.php'); ?>

<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

<!-- wird fuer das Absenden bei Enter benoetigt -->
document.onkeydown = function(ev){
	var key;
	ev = ev || event;
	key = ev.keyCode;
	if (key == 13) {
		document.GUI.suchen.click();
	}
}

function changeInputType(oldObject, oType) {
	if(oldObject != undefined){
	  var newObject = document.createElement('input');
	  newObject.type = oType;
	  if(oldObject.size) newObject.size = oldObject.size;
	  if(oldObject.value) newObject.value = oldObject.value;
	  if(oldObject.name) newObject.name = oldObject.name;
	  if(oldObject.id) newObject.id = oldObject.id;
	  if(oldObject.className) newObject.className = oldObject.className;
	  oldObject.parentNode.replaceChild(newObject,oldObject);
	  return newObject;
	}
}

function operatorchange(attributname, searchmask_number){
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
		document.getElementById(prefix+"value_"+attributname).size = 9;
	}
	else{
		changeInputType(document.getElementById(prefix+"value2_"+attributname), "hidden");
		document.getElementById(prefix+"value2_"+attributname).value = "";
		document.getElementById(prefix+"value_"+attributname).size = 24;
	}
}

function suche(){
	var nogo = '';
	<?
	for($i = 0; $i < count($this->attributes['type']); $i++){ 
		if($this->attributes['type'][$i] != 'geometry'){		
			if($this->attributes['mandatory'][$i] == 1){
				if($this->attributes['alias'][$i] == ''){
					$this->attributes['alias'][$i] = $this->attributes['name'][$i];
				}		?>
				if(document.GUI.value_<? echo $this->attributes['name'][$i]; ?>.value == ''){
					nogo = 'Das Feld <? echo $this->attributes['alias'][$i]; ?> ist ein Such-Pflichtfeld und muss ausgefüllt werden.';
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
		document.getElementById('loader').style.display = '';
		setTimeout('document.getElementById(\'loaderimg\').src=\'graphics/ajax-loader.gif\'', 50);
		document.GUI.go_plus.value = 'Suchen';
		document.GUI.submit();
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


function update_require_attribute(attributes, layer_id, value, searchmask_number){
	// attributes ist eine Liste von zu aktualisierenden Attribut und value der ausgewaehlte Wert
	if(searchmask_number > 0){						// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
		prefix = searchmask_number+'_';
	}
	else prefix = '';
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type=select-one", new Array(document.getElementById(prefix+'value_'+attribute[i])), new Array('sethtml'));
	}
}


function showsearches(){
	if(document.getElementById('searches2').style.display == 'none'){
		document.getElementById('searches1').style.borderTop="1px solid #C3C7C3";
		document.getElementById('searches1').style.borderLeft="1px solid #C3C7C3";
		document.getElementById('searches1').style.borderRight="1px solid #C3C7C3";
		document.getElementById('searches2').style.display = '';
	}
	else{
		document.getElementById('searches1').style.border="none";
		document.getElementById('searches2').style.display = 'none';
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
	document.getElementById('searchmasks').appendChild(newdiv);
	ahah("<? echo URL.APPLVERSION; ?>index.php", "go=Layer-Suche_Suchmaske_generieren&selected_layer_id="+layer_id+"&searchmask_number="+document.GUI.searchmask_count.value, new Array(newdiv), new Array('sethtml'));
}
  
//-->
</script>
<br><h2><? echo $this->titel; ?></h2>
<table border="0" cellpadding="5" cellspacing="2" bgcolor="<? echo $bgcolor; ?>">
  <tr>
    <td></td>
  </tr>
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5">Gruppen</td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5"> 
      <select style="width:250px" size="1"  name="selected_group_id" onchange="document.GUI.selected_layer_id.value='';document.GUI.submit();" <? if(count($this->layergruppen['ID'])==0){ echo 'disabled';}?>>
        <option value="">  -- <? echo $this->strPleaseSelect; ?> --  </option>
        <?
        for($i = 0; $i < count($this->layergruppen['ID']); $i++){         
          echo '<option';
          if($this->layergruppen['ID'][$i] == $this->formvars['selected_group_id']){
            echo ' selected';
          }
          echo ' value="'.$this->layergruppen['ID'][$i].'">'.$this->layergruppen['Bezeichnung'][$i].'</option>';
        }
      ?>
      </select>
  	</td>
  </tr>
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5">Themen</td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5"> 
      <select style="width:250px" size="1"  name="selected_layer_id" onchange="document.GUI.submit();" <? if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
        <option value="">  -- <? echo $this->strPleaseSelect; ?> --  </option>
        <?
        for($i = 0; $i < count($this->layerdaten['ID']); $i++){         
          echo '<option';
          if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
            echo ' selected';
          }
          echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
        }
      ?>
      </select>
  	</td>
  </tr>
  <tr>
    <td id="searches1"><? if($this->formvars['selected_layer_id'] != ''){ ?><a href="javascript:showsearches();">Suchabfragen...</a><? } ?>&nbsp;</td>
  </tr>
  <tr id="searches2" style="display:none"> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3">
    	<table border="0" cellspacing="0" cellpadding="1">
    		<tr align="center"> 
			    <td colspan="2"  align="right">
			    	Name:&nbsp;<input type="text" name="search_name" value="<? echo $this->formvars['searches']; ?>">
			    	<input class="button" type="button" style="width:74px" name="speichern" value="speichern" onclick="save_search();">
			    </td>
			  </tr>
    		<tr>
			  	<td align="right"  colspan="2">
			  		<input class="button" type="button" style="width:74px" name="delete" value="löschen" onclick="delete_search();">
			  		<select name="searches">
			  			<option value="">  -- <? echo $this->strPleaseSelect; ?> --  </option>
			  			<?
			  				for($i = 0; $i < count($this->searchset); $i++){
			  					echo '<option value="'.$this->searchset[$i]['name'].'" ';
			  					if($this->selected_search[0]['name'] == $this->searchset[$i]['name']){echo 'selected ';}
			  					echo '>'.$this->searchset[$i]['name'].'</option>';
			  				}
			  			?>
			  		</select>
			  		<input class="button" type="button" style="width:74px" name="laden" value="laden" onclick="document.GUI.submit();">
			    </td>
			  </tr>
    	</table>
    </td>
  </tr>
  
  <? if($this->formvars['columnname'] != ''){ ?>
  <tr>
    <td id="map1" <? if($this->formvars['map_flag'] != ''){echo 'style="border-top: 1px solid #C3C7C3;border-left: 1px solid #C3C7C3;border-right: 1px solid #C3C7C3"';} ?>><a href="javascript:showmap();">Suche räumlich eingrenzen...</a>&nbsp;</td>
  </tr>
  <? if($this->formvars['map_flag'] != ''){ ?>
  <tr id="map2"> 
    <td align="right" style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3">
    	Geometrie übernehmen von: 
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select>
  		<?
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  <? }} ?>
  
  <? if($this->selected_search != ''){echo '<script type="text/javascript">showsearches();</script>';} ?>
  <tr> 
    <td colspan="5" id="searchmasks">

<? if(count($this->attributes) > 0){  							
		for($m = 0; $m <= $this->formvars['searchmask_count']; $m++){ 
			$searchmask_number = $m; 		?>
			<div>
			<? include(SNIPPETS.'generic_search_mask.php'); ?>
			</div>
<? 	}
	} ?>
		</td>
  </tr>
	<tr> 
    <td colspan="5">
<? if(count($this->attributes) > 0){ ?>
						
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="3">			
			<? if($this->layerset[0]['connectiontype'] == MS_POSTGIS){ ?>
					<tr>
						<td><a href="javascript:add_searchmask(<? echo $this->formvars['selected_layer_id']; ?>);">und/oder...</a></td>
					</tr>
			<? } ?>
					<tr>
						<td colspan="5"><br>Anzahl Treffer anzeigen:&nbsp;<input size="2" type="text" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>"></td>
					</tr>
					<tr>
						<td colspan="5"><br><em>Zur nicht exakten Suche verwenden Sie den <br>Operator "ähnlich" und den Platzhalter %.</em></td>
					</tr>
					<tr>
						<td colspan="5"><br><em>Für Datumsangaben verwenden Sie bitte das <br>Format "TT.MM.JJJJ".</em></td>
					</tr>
					<tr>                
						<td align="center" colspan="5"><br>
							<input class="button" type="button" name="suchen" onclick="suche();" value="Suchen">
						</td>
					</tr>
					<tr>
						<td height="30" valign="bottom" align="center" colspan="5" id="loader" style="display:none"><img id="loaderimg" src="graphics/ajax-loader.gif"></td>
					</tr>
				</table><?
      }
      ?>
		</td>
  </tr>
  <tr> 
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="5" >&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="go" value="Layer-Suche">
<input type="hidden" name="titel" value="<? echo $this->formvars['titel'] ?>">
<input type="hidden" name="map_flag" value="<? echo $this->formvars['map_flag']; ?>">
<input type="hidden" name="area" value="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<? echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<input type="hidden" name="always_draw" value="<? echo $always_draw; ?>">
<input type="hidden" name="searchmask_count" value="<? echo $this->formvars['searchmask_count']; ?>">

