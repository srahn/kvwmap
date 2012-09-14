<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

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

function checkDate(string){
    var split = string.split(".");
    var day = parseInt(split[0], 10);
    var month = parseInt(split[1], 10);
    var year = parseInt(split[2], 10);
    var check = new Date(year, month-1, day);
    var day2 = check.getDate();
    var year2 = check.getFullYear();
    var month2 = check.getMonth()+1;
    if(year2 == year && month == month2 && day == day2){
    	return true;
    }
    else{
    	return false;
    }
}


function operatorchange(attributname){
	if(document.getElementById("operator_"+attributname).value == "IS NULL" || document.getElementById("operator_"+attributname).value == "IS NOT NULL"){
		changeInputType(document.getElementById("value_"+attributname), "hidden");
	}
	else{
		changeInputType(document.getElementById("value_"+attributname), "text");
	}
	if(document.getElementById("operator_"+attributname).value == "between"){
		changeInputType(document.getElementById("value2_"+attributname), "text");
		document.getElementById("value_"+attributname).size = 9;
	}
	else{
		changeInputType(document.getElementById("value2_"+attributname), "hidden");
		document.getElementById("value2_"+attributname).value = "";
		document.getElementById("value_"+attributname).size = 24;
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
				if(document.GUI.value_<?php echo $this->attributes['name'][$i]; ?>.value == ''){
					nogo = 'Das Feld <?php echo $this->attributes['alias'][$i]; ?> ist ein Such-Pflichtfeld und muss ausgefüllt werden.';
				}
	<?	} ?>
			test = document.GUI.value_<?php echo $this->attributes['name'][$i]; ?>.value + '';
			if(test.search(/%/) > -1 && document.GUI.operator_<?php echo $this->attributes['name'][$i]; ?>.value == 'IN'){
				nogo = 'Der Platzhalter % darf nur bei der Suche mit ähnlich oder nicht ähnlich verwendet werden.';
			}
	<? 	if(strpos($this->attributes['type'][$i], 'time') !== false OR $this->attributes['type'][$i] == 'date'){ ?>
				test = document.GUI.value_<?php echo $this->attributes['name'][$i]; ?>.value + '';
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


function update_require_attribute(attributes, layer_id, value){
	// attributes ist eine Liste von zu aktualisierenden Attribut, k die Nummer des Datensatzes und value der ausgewaehlte Wert
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type=select-one", new Array(document.getElementById('value_'+attribute[i])), 'sethtml');
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
	if(document.getElementById('map2').style.display == 'none'){
		document.getElementById('map1').style.borderTop="1px solid #C3C7C3";
		document.getElementById('map1').style.borderLeft="1px solid #C3C7C3";
		document.getElementById('map1').style.borderRight="1px solid #C3C7C3";
		document.getElementById('map2').style.display = '';
		document.GUI.map_flag.value = 1;
	}
	else{
		document.getElementById('map1').style.border="none";
		document.getElementById('map2').style.display = 'none';
		document.GUI.map_flag.value = '';
	}
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
  
//-->
</script>
<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5">Gruppen</td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5"> 
      <select style="width:250px" size="1" class="select" name="selected_group_id" onchange="document.GUI.selected_layer_id.value='';document.GUI.submit();" <?php if(count($this->layergruppen['ID'])==0){ echo 'disabled';}?>>
        <option value="">  -- Bitte auswählen --  </option>
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
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5">Layer</td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5"> 
      <select style="width:250px" size="1" class="select" name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
        <option value="">  -- Bitte auswählen --  </option>
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
			  			<option value="">  -- Bitte auswählen --  </option>
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
    <td id="map1"><a href="javascript:showmap();">Suche räumlich eingrenzen...</a>&nbsp;</td>
  </tr>
  <tr id="map2" style="display:none"> 
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
  		<?php
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  <? } ?>
  
  <? if($this->selected_search != ''){echo '<script type="text/javascript">showsearches();</script>';} ?>
  <? if($this->formvars['map_flag'] != ''){echo '<script type="text/javascript">showmap();</script>';} ?>
  <tr> 
    <td colspan="5">
      <table align="center" border="0" cellspacing="0" cellpadding="0">
        <?
    if ((count($this->attributes))!=0) {
      ?><tr>
            <td><b>Attribut</b></td>
            <td>&nbsp;&nbsp;</td>
            <td align="center"><b>Operator</b></td>
            <td>&nbsp;&nbsp;</td>
            <td align="center"><b>Wert</b></td>
          </tr><?php

      for($i = 0; $i < count($this->attributes['name']); $i++){
        if($this->attributes['type'][$i] != 'geometry'){
          ?><tr>
            <td><?php
              if($this->attributes['alias'][$i] != ''){
                echo $this->attributes['alias'][$i];
              }
              else{
                echo $this->attributes['name'][$i];
              }
              if(strpos($this->attributes['type'][$i], 'time') !== false OR $this->attributes['type'][$i] == 'date'){
              ?>
                <img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0">
              <?
              }
          ?></td>
            <td>&nbsp;&nbsp;</td>
            <td>
              <select class="select" style="width:75px" <? if(count($this->attributes['enum_value'][$i]) == 0){ ?>onchange="operatorchange('<?php echo $this->attributes['name'][$i]; ?>');" id="operator_<?php echo $this->attributes['name'][$i]; ?>" <? } ?> name="operator_<?php echo $this->attributes['name'][$i]; ?>">
                <option title="Der Suchbegriff muss exakt so in der Datenbank stehen" value="=" <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == '='){ echo 'selected';} ?> >=</option>
                <option title="Der Suchbegriff kommt so NICHT in der Datenbank vor" value="!=" <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == '!='){ echo 'selected';} ?> >!=</option>
                <option title="'kleiner als': nur bei Zahlen verwenden!" value="<" <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == '<'){ echo 'selected';} ?> ><</option>
                <option title="'größer als': nur bei Zahlen verwenden!" value=">" <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == '>'){ echo 'selected';} ?> >></option>
                <option title="Fügen Sie das %-Zeichen vor und/oder nach dem Suchbegriff für beliebige Zeichen ein" value="LIKE" <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'LIKE'){ echo 'selected';} ?> >ähnlich</option>
                <option title="Fügen Sie das %-Zeichen vor und/oder nach dem Suchbegriff für beliebige Zeichen ein" value="NOT LIKE" <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'NOT LIKE'){ echo 'selected';} ?> >nicht ähnlich</option>
                <option title="Sucht nach Datensätzen ohne Eintrag in diesem Attribut" value="IS NULL" <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'IS NULL'){ echo 'selected';} ?> >ist leer</option>
                <option title="Sucht nach Datensätzen mit beliebigem Eintrag in diesem Attribut" value="IS NOT NULL" <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'IS NOT NULL'){ echo 'selected';} ?> >ist nicht leer</option>
                <option title="Sucht nach mehreren exakten Suchbegriffen, zur Trennung '|' verwenden:  [Alt Gr] + [<]" value="IN" <? if (count($this->attributes['enum_value'][$i]) > 0){ echo 'disabled="true"'; } ?> <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'IN'){ echo 'selected';} ?> >befindet sich in</option>
                <option title="Sucht zwischen zwei Zahlwerten" value="between" <? if (count($this->attributes['enum_value'][$i]) > 0){ echo 'disabled="true"'; } ?> <? if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'between'){ echo 'selected';} ?> >zwischen</option>
              </select>
            </td>
            <td>&nbsp;&nbsp;</td>
            <td align="left"><?php
               if($this->attributes['form_element_type'][$i] == 'Auswahlfeld'){
                  ?><select class="select" 
                  <?
                  	if($this->attributes['req_by'][$i] != ''){
											echo 'onchange="update_require_attribute(\''.$this->attributes['req_by'][$i].'\','.$this->formvars['selected_layer_id'].', this.value);" ';
										}
									?> 
                  	id="value_<?php echo $this->attributes['name'][$i]; ?>" name="value_<?php echo $this->attributes['name'][$i]; ?>"><?echo "\n"; ?>
                      <option value="">-- Bitte Auswählen --</option><?php echo "\n";
                      if(is_array($this->attributes['enum_value'][$i][0])){
                      	$this->attributes['enum_value'][$i] = $this->attributes['enum_value'][$i][0];
                      	$this->attributes['enum_output'][$i] = $this->attributes['enum_output'][$i][0];
                      }
                    for($o = 0; $o < count($this->attributes['enum_value'][$i]); $o++){
                      ?>
                      <option <? if($this->formvars['value_'.$this->attributes['name'][$i]] == $this->attributes['enum_value'][$i][$o]){ echo 'selected';} ?> value="<?php echo $this->attributes['enum_value'][$i][$o]; ?>"><?php echo $this->attributes['enum_output'][$i][$o]; ?></option><?php echo "\n";
                    } ?>
                    </select>
                    <input class="input" size="9" id="value2_<?php echo $this->attributes['name'][$i]; ?>" name="value2_<?php echo $this->attributes['name'][$i]; ?>" type="hidden" value="<?php echo $this->formvars['value2_'.$this->attributes['name'][$i]]; ?>">
                    <?php
                }
                else { 
                  ?>
                  <input class="input" size="<? if($this->formvars['value2_'.$this->attributes['name'][$i]] != ''){echo '9';}else{echo '24';} ?>" id="value_<?php echo $this->attributes['name'][$i]; ?>" name="value_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo $this->formvars['value_'.$this->attributes['name'][$i]]; ?>">
                  &nbsp;<input class="input" size="9" id="value2_<?php echo $this->attributes['name'][$i]; ?>" name="value2_<?php echo $this->attributes['name'][$i]; ?>" type="<? if($this->formvars['value2_'.$this->attributes['name'][$i]] != ''){echo 'text';}else{echo 'hidden';} ?>" value="<?php echo $this->formvars['value2_'.$this->attributes['name'][$i]]; ?>">
                  <?php
               }
           ?></td>
          </tr><?php
        }
      }
      if(count($this->attributes) > 0){
        ?>  	<tr>
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
              </tr><?php
      }
    } 
      ?>
      </table></td>
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
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<input type="hidden" name="always_draw" value="<? echo $always_draw; ?>">

