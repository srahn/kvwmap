<?php
	global $supportedLanguages;
  include(LAYOUTPATH.'languages/attribut_editor_'.$this->user->rolle->language.'.php');
	global $quicksearch_layer_ids;
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--
function submitLayerSelector() {
	var element = document.getElementById('selected_datatype_id');
	    element.value = '<?php echo $strPleaseSelect; ?>';
	document.GUI.submit();
}

function submitDatatypeSelector() {
	var element = document.getElementById('selected_layer_id');
	    element.value = '<?php echo $strPleaseSelect; ?>';
	document.GUI.submit();
}  
//-->
</script>
<table cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td style="border: 1px solid #C3C7C3;">
			<?php echo $strLayer;?><br>
      <select id="selected_layer_id" style="width:250px" size="1" name="selected_layer_id" onchange="submitLayerSelector();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
      <option value=""><?php echo $strPleaseSelect; ?></option>
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
    <td style="border:1px solid #C3C7C3;<? if(count($this->datatypes) == 0)echo 'display: none'; ?>">
			<?php echo $strDatatype;?><br>
      <select id="selected_datatype_id" style="width:250px" size="1"  name="selected_datatype_id" onchange="submitDatatypeSelector();" <?php if(count($this->datatypes)==0){ echo 'disabled';}?>>
      <option value=""><?php echo $strPleaseSelect; ?></option>
        <?
    		for($i = 0; $i < count($this->datatypes); $i++){
    			echo '<option';
    			if($this->datatypes[$i]['id'] == $this->formvars['selected_datatype_id']){
    				echo ' selected';
    			}
    			echo ' value="' . $this->datatypes[$i]['id'] . '">' . $this->datatypes[$i]['name'] . '</option>';
    		}
    	?>
      </select>
		</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2">

    	<table align="center" border="0" cellspacing="0" cellpadding="0">
        <? if ((count($this->attributes))!=0) {
				echo '
					<tr>
						<td align="center"><span class="fett">Attribut</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Formularelement</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Optionen</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Aliasname</span></td>
						<td>&nbsp;</td>';
						foreach($supportedLanguages as $language){
							if($language != 'german'){
								echo '<td align="center"><span class="fett">Aliasname '.$language.'</span></td>
											<td>&nbsp;</td>';
							}
						}
			echo '
						<td align="center"><span class="fett">Erläuterungen</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Gruppe</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Anordnung</span></td>
						<td>&nbsp;&nbsp;</td>
						<td align="center"><span class="fett">Beschriftung</span></td>
						<td>&nbsp;&nbsp;</td>
						<td align="center"><span class="fett">sichtbar im Rastertemplate</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Suche-Pflicht</span></td>';
			if(in_array($this->formvars['selected_layer_id'], $quicksearch_layer_ids)){			
				echo '
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Schnell-<br>suche</span></td>';
			}
			echo '
					</tr>
			';

    	for($i = 0; $i < count($this->attributes['type']); $i++){ ?>
				<tr>
				  <td align="left" valign="top">
				  	<input type="text"
						  name="attribute_<?php echo $this->attributes['name'][$i]; ?>"
							value="<?php echo $this->attributes['name'][$i]; ?>"
							readonly
						>
				  </td>
				  <td>&nbsp;</td>
				  <td align="left" valign="top">
				<?	$type = ltrim($this->attributes['type'][$i], '_');
						if(is_numeric($type)){ ?>
							<a href="index.php?go=Attributeditor&selected_datatype_id=<?php echo $type; ?>"><?php echo $this->attributes['typename'][$i]; ?></a><?php
						}
						else {
							echo '
					  	<select  style="width:130px" name="form_element_'.$this->attributes['name'][$i].'">';
					  	if($this->attributes['type'][$i] == 'geometry'){
					  		echo'<option value="Geometrie" selected>Geometrie</option>';
					  	}
					  	elseif($this->attributes['constraints'][$i] != '' AND !in_array($this->attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))){
					  		echo '<option value="Auswahlfeld" selected>Auswahlfeld</option>';
					  	}
					  	else{
					  		echo '
					  		<option value="Text" ';
					  		if($this->attributes['form_element_type'][$i] == 'Text'){echo 'selected';}
					  		echo ' >Text</option>
					  		<option value="Zahl" ';
					  		if($this->attributes['form_element_type'][$i] == 'Zahl'){echo 'selected';}
					  		echo ' >Zahl</option>
					  		<option value="Textfeld" ';
					  		if($this->attributes['form_element_type'][$i] == 'Textfeld'){echo 'selected';}
					  		echo ' >Textfeld</option>
					  		<option value="Auswahlfeld" ';
					  		if($this->attributes['form_element_type'][$i] == 'Auswahlfeld'){echo 'selected';}
					  		echo ' >Auswahlfeld</option>
								<option value="Autovervollständigungsfeld" ';
					  		if($this->attributes['form_element_type'][$i] == 'Autovervollständigungsfeld'){echo 'selected';}
					  		echo ' >Autovervollständigungsfeld</option>
					  		<option value="Checkbox" ';
					  		if($this->attributes['form_element_type'][$i] == 'Checkbox'){echo 'selected';}
					  		echo ' >Checkbox</option>
					  		<option value="SubFormPK" ';
					  		if($this->attributes['form_element_type'][$i] == 'SubFormPK'){echo 'selected';}
					  		echo ' >SubFormPK</option>
					  		<option value="SubFormFK" ';
					  		if($this->attributes['form_element_type'][$i] == 'SubFormFK'){echo 'selected';}
					  		echo ' >SubFormFK</option>
								<option value="SubFormEmbeddedPK" ';
					  		if($this->attributes['form_element_type'][$i] == 'SubFormEmbeddedPK'){echo 'selected';}
					  		echo ' >SubFormEmbeddedPK</option>
					  		<option value="Time" ';
					  		if($this->attributes['form_element_type'][$i] == 'Time'){echo 'selected';}
					  		echo ' >Time</option>
								<option value="User" ';
					  		if($this->attributes['form_element_type'][$i] == 'User'){echo 'selected';}
					  		echo ' >User</option>
					  		<option value="UserID" ';
					  		if($this->attributes['form_element_type'][$i] == 'UserID'){echo 'selected';}
					  		echo ' >UserID</option>
					  		<option value="Stelle" ';
					  		if($this->attributes['form_element_type'][$i] == 'Stelle'){echo 'selected';}
					  		echo ' >Stelle</option>
								<option value="StelleID" ';
					  		if($this->attributes['form_element_type'][$i] == 'StelleID'){echo 'selected';}
					  		echo ' >StelleID</option>
					  		<option value="Dokument" ';
					  		if($this->attributes['form_element_type'][$i] == 'Dokument'){echo 'selected';}
					  		echo ' >Dokument</option>
								<option value="Link" ';
					  		if($this->attributes['form_element_type'][$i] == 'Link'){echo 'selected';}
					  		echo ' >Link</option>
								<option value="dynamicLink" ';
					  		if($this->attributes['form_element_type'][$i] == 'dynamicLink'){echo 'selected';}
					  		echo ' >dynamischer Link</option>
								<option value="mailto" ';
					  		if($this->attributes['form_element_type'][$i] == 'mailto'){echo 'selected';}
					  		echo ' >MailTo</option>
								<option value="Fläche" ';
					  		if($this->attributes['form_element_type'][$i] == 'Fläche'){echo 'selected';}
					  		echo ' >Fläche</option>
					  		<option value="Länge" ';
					  		if($this->attributes['form_element_type'][$i] == 'Länge'){echo 'selected';}
					  		echo ' >Länge</option>';
					  	}
					  	echo'
					  	</select>';
						} ?>
				  </td>
				  <td>&nbsp;</td>
				  <td align="left" valign="top"><?php
				  if($this->attributes['options'][$i] == '' AND $this->attributes['constraints'][$i] != '' AND !in_array($this->attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))){
				  	echo '
				  	<input style="width:180px" name="options_'.$this->attributes['name'][$i].'" type="text" value="'.$this->attributes['constraints'][$i].'">';
				  }
				  else{
				  	echo '
						<textarea name="options_'.$this->attributes['name'][$i].'" style="height:22px; width:180px">'.$this->attributes['options'][$i].'</textarea>';
				  }
				  echo '
				  </td>
				  <td>&nbsp;</td>
				  <td align="left" valign="top">
				  	<input name="alias_'.$this->attributes['name'][$i].'" type="text" value="'.$this->attributes['alias'][$i].'">
				  </td>
				  <td>&nbsp;</td>';
					foreach($supportedLanguages as $language){
						if($language != 'german'){
							echo '
							<td align="left" valign="top">
								<input name="alias_'.$language.'_'.$this->attributes['name'][$i].'" type="text" value="'.$this->attributes['alias_'.$language][$i].'">
							</td>
							<td>&nbsp;</td>';
						}
					}
					echo '
				  <td align="left" valign="top">
						<textarea name="tooltip_'.$this->attributes['name'][$i].'" style="height:22px; width:120px">'.htmlspecialchars($this->attributes['tooltip'][$i]).'</textarea>
				  </td>
				  <td>&nbsp;</td>
				  <td align="left" valign="top">
				  	<input name="group_'.$this->attributes['name'][$i].'" type="text" value="'.$this->attributes['group'][$i].'">
				  </td>
					<td>&nbsp;</td>';
					if($this->attributes['arrangement'][$i] == 1)$bgcolor = '#faef1e';else $bgcolor = 'white';
					echo '
					<td align="center" valign="top">
				  	<select style="outline: 1px solid lightgrey; border: none; width: 59px; height: 18px; background-color: '.$bgcolor.'" onchange="this.setAttribute(\'style\', \'outline: 1px solid lightgrey; border: none; width: 59px; height: 18px;\'+this.options[this.selectedIndex].getAttribute(\'style\'));" name="arrangement_'.$this->attributes['name'][$i].'">
							<option style="background-color: white" value="0"';if($this->attributes['arrangement'][$i] == 0) echo ' selected="true" ';echo '>unter &nbsp;dem vorigen</option>
							<option style="background-color: #faef1e" value="1"';if($this->attributes['arrangement'][$i] == 1) echo ' selected="true" ';echo '>neben dem vorigen</option>
						</select>
				  </td>
					<td>&nbsp;</td>';
					if($this->attributes['labeling'][$i] == 0)$bgcolor = 'white';
					if($this->attributes['labeling'][$i] == 1)$bgcolor = '#faef1e';
					if($this->attributes['labeling'][$i] == 2)$bgcolor = '#ff6600';
					echo '
					<td align="center" valign="top">
				  	<select style="outline: 1px solid lightgrey; border: none; width: 53px; height: 18px; background-color: '.$bgcolor.'" onchange="this.setAttribute(\'style\', \'outline: 1px solid lightgrey; border: none; width: 59px; height: 18px;\'+this.options[this.selectedIndex].getAttribute(\'style\'));" name="labeling_'.$this->attributes['name'][$i].'">
							<option style="background-color: white" value="0"';if($this->attributes['labeling'][$i] == 0) echo ' selected="true" ';echo '>links neben dem Attribut</option>
							<option style="background-color: #faef1e" value="1"';if($this->attributes['labeling'][$i] == 1) echo ' selected="true" ';echo '>über &nbsp;dem Attribut</option>
							<option style="background-color: #ff6600" value="2"';if($this->attributes['labeling'][$i] == 2) echo ' selected="true" ';echo '>ohne</option>
						</select>
				  </td>
					<td>&nbsp;</td>
					<td align="center" valign="top">
				  	<input name="raster_visibility_'.$this->attributes['name'][$i].'" type="checkbox" value="1" ';
				  	if($this->attributes['raster_visibility'][$i]) echo 'checked="true"';
						echo '>
				  </td>
					<td>&nbsp;</td>
				  <td align="center" valign="top">
				  	<input name="mandatory_'.$this->attributes['name'][$i].'" type="checkbox" value="1" ';
				  	if($this->attributes['mandatory'][$i]) echo 'checked="true"';
						echo '>
				  </td>';
				if(in_array($this->formvars['selected_layer_id'], $quicksearch_layer_ids)){	
					echo '
					<td>&nbsp;</td>
					<td align="center" valign="top">
				  	<input name="quicksearch_'.$this->attributes['name'][$i].'" type="checkbox" value="1" ';
				  	if($this->attributes['quicksearch'][$i]) echo 'checked="true"';
						echo '>
				  </td>';
				}
				echo '
        </tr>';
    	}
			if(count($this->attributes) > 0){ ?>
				<tr>
					<td align="center" colspan="19"><br><br>
						<input class="button" type="submit" name="go_plus" value="speichern">
					</td>
				</tr><?php
			}
		} 
			?>
      </table>

		</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" >&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="Attributeditor">
