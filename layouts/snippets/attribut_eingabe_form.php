<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/attribut_eingabe_form_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function save(the_geom_checked){
	if(the_geom_checked == true){
		if(document.GUI.newpathwkt.value == ''){
			if(document.GUI.newpath.value == ''){
				alert('Geben Sie ein Polygon an.');
			}
			else{
				document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
				document.GUI.go_plus.value = 'speichern';
				document.GUI.submit();
			}
		}
		else{
			document.GUI.go_plus.value = 'speichern';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'speichern';
		document.GUI.submit();
	}
}

function buildwktpolygonfromsvgpath(svgpath){
	var koords;
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
	return wkt;
}	

function getlayers(){
	document.GUI.selected_layers.value = '';
	document.GUI.selected_options.value = '';
	addSelectedValuesToHiddenField(document.GUI.layer, document.GUI.selected_layers);
	addSelectedIndizesToHiddenField(document.GUI.layer, document.GUI.selected_options);
	document.GUI.submit();
}

function setlayers(selected_options){
	var options = selected_options.split(", ");
	for(i = 0; i < options.length; i++){
		document.GUI.layer.options[options[i]].selected = true;
	}
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr> 
    <td align="right" colspan="5" align="center">
    	Geometrie übernehmen von: 
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<option value="">--- Auswahl ---</option>
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select> 
      <?php
	  #	if ($this->stellendaten['ID']=='' OR $this->layerdaten['ID']!='') {
 				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
	  # }
			?>
    </td>
  </tr>
  <tr> 
    <td colspan="5">&nbsp; </td>
  </tr>
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3"><?php echo $this->strTask; ?></td>
    <td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="4"><?php echo $this->strLayer; ?></td>
  </tr>
  <tr> 
    <td valign="top" style="border-bottom:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3"> 
      <select  name="stelle" onchange="document.GUI.newpath.value = '';document.GUI.newpathwkt.value = '';document.GUI.pathwkt.value = '';document.GUI.result.value = '';document.GUI.layer.disabled = true;document.GUI.submit()">
        <option value=""><?php echo $this->strPleaseSelect; ?></option>
        <?
    		for($i = 0; $i < count($this->stellendaten['ID']); $i++){
    			echo '<option value="'.$this->stellendaten['ID'][$i].'" ';
    			if($this->formvars['stelle'] == $this->stellendaten['ID'][$i]){
    				echo 'selected';
    			}
    			echo '>'.$this->stellendaten['Bezeichnung'][$i].'</option>';
    		}
    	?>
      </select> </td>
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="4"> 
      <select style="width:250px" multiple size="5"  name="layer" onchange="document.GUI.newpath.value = '';document.GUI.newpathwkt.value = '';document.GUI.pathwkt.value = '';document.GUI.result.value = '';" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
        <?
    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
    			echo '<option value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
    		}
    	?>
      </select> </td>
  </tr>
  <tr>
		<td align="right" colspan="5"><input class="button" type="button" name="load" value="<?php echo $strButtonLoad; ?>" onclick="document.GUI.newpath.value = '';document.GUI.newpathwkt.value = '';document.GUI.pathwkt.value = '';document.GUI.result.value = '';;getlayers();">
		</td>
	</tr>
  <tr> 
    <td colspan="5">
    	<table align="center" border="0" cellspacing="0" cellpadding="0">
        <?
    if(count($this->selected_layers) > 1){
    	echo '
					<tr>
						<td align="center" colspan="3"><span class="fett">Gemeinsame Attribute von '.count($this->selected_layers).' Layern:</span><br><br></td>
					</tr>
			';
    }
		if ((count($this->attributes))!=0) {
			echo '
					<tr>
						<td align="center">
							<span class="fett">Attribut</span>
						</td>
						<td align="center">
							<span class="fett">Operator</span>
						</td>
						<td align="center">
							<span class="fett">Wert</span>
						</td>
					</tr>
			';
			// durch das intersecten der Arrays kann es sein, dass einige Schlüssel nicht mehr im Array existieren
			// diese werden einfach ausgelassen
			$keys = array_keys($this->attributes['name']);
			$lastindex = $keys[count($keys)-1]; 
    	for($i = 0; $i < $lastindex+1; $i++){
    		if(array_key_exists($i, $this->attributes['name'])){
	    		if($this->attributes['type'][$i] != 'geometry'){
						echo '
						<tr>
						  <td align="center">
						  	<input type="text" name="attribute_'.$this->attributes['name'][$i].'" value="'.$this->attributes['name'][$i].'" readonly>
						  </td>
						  <td align="center">
						  	<select  style="width:90px" name="operator_'.$this->attributes['name'][$i].'">
						  		<option value="=" ';
						  		if($this->formvars['operator_'.$this->attributes['name'][$i]] == '='){echo 'selected';}
						  		echo ' >=</option>
									<option value="!=" ';
						  		if($this->formvars['operator_'.$this->attributes['name'][$i]] == '!='){echo 'selected';}
						  		echo ' >!=</option>
						  		<option value="<" ';
						  		if($this->formvars['operator_'.$this->attributes['name'][$i]] == '<'){echo 'selected';}
						  		echo ' ><</option>
						  		<option value=">" ';
						  		if($this->formvars['operator_'.$this->attributes['name'][$i]] == '>'){echo 'selected';}
						  		echo ' >></option>
						  		<option value="like" ';
						  		if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'like'){echo 'selected';}
									echo ' >like</option>
									<option value="IS" ';
						  		if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'IS'){echo 'selected';}
									echo ' >IS</option>
									<option value="IN" ';
						  		if($this->formvars['operator_'.$this->attributes['name'][$i]] == 'IN'){echo 'selected';}
									echo ' >IN</option>
						  	</select>
						  </td>
						  <td align="center">
						  	<input name="value_'.$this->attributes['name'][$i].'" type="text" value="'.$this->formvars['value_'.$this->attributes['name'][$i]].'">
						  </td>
		        </tr>';
	    		}
	    		else{
	    			$the_geom_index[] = $i;
	    		}
    		}
    	}
    	for($i = 0; $i < count($the_geom_index); $i++){
	    	echo '
					<tr>
	    			<td colspan=3>&nbsp;</td>
	    		</tr>
					<tr>
					  <td align="center">
					  	<input type="text" name="attribute_'.$this->attributes['name'][$the_geom_index[$i]].'" value="'.$this->attributes['name'][$the_geom_index[$i]].'" readonly>
					  </td>
					  <td align="center">
					  	<select  style="width:90px" name="operator_'.$this->attributes['name'][$the_geom_index[$i]].'">
					  		<option value="st_intersects" ';
					  		if($this->formvars['operator_'.$this->attributes['name'][$the_geom_index[$i]]] == 'st_intersects'){echo 'selected';}
					  		echo ' >Intersects</option>
								<option value="st_within" ';
					  		if($this->formvars['operator_'.$this->attributes['name'][$the_geom_index[$i]]] == 'st_within'){echo 'selected';}
								echo ' >Within</option>
					  	</select>
					  </td>
					  <td align="center">
					  	<input type="hidden" name="value_'.$this->attributes['name'][$the_geom_index[$i]].'" value="'.$this->formvars['value_'.$this->attributes['name'][$the_geom_index[$i]]].'">
					  	<input name="check_'.$this->attributes['name'][$the_geom_index[$i]].'" type="checkbox" ';
					  	if($this->formvars['value_'.$this->attributes['name'][$the_geom_index[$i]]] != '' AND $this->formvars['value_'.$this->attributes['name'][$the_geom_index[$i]]] != '---- verschieden ----'){
					  		echo 'checked';
					  	}
					  	echo' value="egal"> Polygon';
					  	if($this->formvars['value_'.$this->attributes['name'][$the_geom_index[$i]]] == '---- verschieden ----'){
					  		echo ' <br>(verschiedene)';
					  	}
					  	echo '
					  </td>
	        </tr>
				';
			}
			if(count($this->attributes) > 0){
				echo '<tr>
			 					<td align="center" colspan="5"><br><br><input class="button" type="button" name="speichern" value="speichern" onclick="save(document.GUI.check_'.$this->attributes['name'][$the_geom_index[0]].'.checked);">
			 					</td>
			 				</tr>';
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
<?
    if($this->formvars['selected_options'] != 0){
    	echo '<script type="text/javascript">
						<!--
						setlayers("'.$this->formvars['selected_options'].'");
						//-->
						</script>'
			;
    }
?>
<INPUT TYPE="hidden" NAME="columnname" VALUE="<? echo $this->formvars['columnname'] ?>">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<input type="hidden" name="go" value="Filterverwaltung">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="selected_layers" value="<? echo $this->formvars['selected_layers'] ?>">
<input type="hidden" name="selected_options" value="<? echo $this->formvars['selected_options'] ?>">
<input type="hidden" name="map_minx" value="<? echo $this->map->extent->minx; ?>">
<input type="hidden" name="map_miny" value="<? echo $this->map->extent->miny; ?>">
<input type="hidden" name="map_pixsize" value="<? echo $this->user->rolle->pixsize; ?>">
<input type="hidden" name="area" value="<?echo $this->formvars['area']?>">
<input type="hidden" name="always_draw" value="<? echo $always_draw; ?>">
