<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/attribut_editor_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
	global $quicksearch_layer_ids;
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5"><?php echo $strLayer;?></td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5"> 
      <select style="width:250px" size="1"  name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
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
      </select> </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="5">
    	<table align="center" border="0" cellspacing="0" cellpadding="0">
        <?
		if ((count($this->attributes))!=0) {
			echo '
					<tr>
						<td align="center"><span class="fett">Attribut</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Formularelement</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Optionen</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Aliasname</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Erläuterungen</span></td>
						<td>&nbsp;</td>
						<td align="center"><span class="fett">Gruppe</span></td>
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

    	for($i = 0; $i < count($this->attributes['type']); $i++){
				echo '
				<tr>
				  <td align="center">
				  	<input type="text" name="attribute_'.$this->attributes['name'][$i].'" value="'.$this->attributes['name'][$i].'" readonly>
				  </td>
				  <td>&nbsp;</td>
				  <td align="center">
				  	<select  style="width:130px" name="form_element_'.$this->attributes['name'][$i].'">';
				  	if($this->attributes['type'][$i] == 'geometry'){
				  		echo'<option value="Geometrie" selected>Geometrie</option>';
				  	}
				  	elseif($this->attributes['constraints'][$i] != '' AND $this->attributes['constraints'][$i] != 'PRIMARY KEY'){
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
				  	</select>
				  </td>
				  <td>&nbsp;</td>
				  <td align="center">';
				  if($this->attributes['constraints'][$i] != '' AND $this->attributes['constraints'][$i] != 'PRIMARY KEY'){
				  	echo '
				  	<input disabled size="40" name="options_'.$this->attributes['name'][$i].'" type="text" value="'.$this->attributes['constraints'][$i].'">';
				  }
				  else{
				  	echo '
				  	<input size="40" name="options_'.$this->attributes['name'][$i].'" type="text" value="'.$this->attributes['options'][$i].'">';
				  }
				  echo '
				  </td>
				  <td>&nbsp;</td>
				  <td>
				  	<input name="alias_'.$this->attributes['name'][$i].'" type="text" value="'.$this->attributes['alias'][$i].'">
				  </td>
					</td>
				  <td>&nbsp;</td>
				  <td>
				  	<input name="tooltip_'.$this->attributes['name'][$i].'" type="text" value="'.htmlspecialchars($this->attributes['tooltip'][$i]).'">
				  </td>
				  <td>&nbsp;</td>
				  <td>
				  	<input name="group_'.$this->attributes['name'][$i].'" type="text" value="'.$this->attributes['group'][$i].'">
				  </td>
					<td>&nbsp;</td>
				  <td align="center">
				  	<input name="mandatory_'.$this->attributes['name'][$i].'" type="checkbox" value="1" ';
				  	if($this->attributes['mandatory'][$i]) echo 'checked="true"';
						echo '>
				  </td>';
				if(in_array($this->formvars['selected_layer_id'], $quicksearch_layer_ids)){	
					echo '
					<td>&nbsp;</td>
					<td align="center">
				  	<input name="quicksearch_'.$this->attributes['name'][$i].'" type="checkbox" value="1" ';
				  	if($this->attributes['quicksearch'][$i]) echo 'checked="true"';
						echo '>
				  </td>';
				}
				echo '
        </tr>';
    	}
			if(count($this->attributes) > 0){
				echo '<tr>
			 					<td align="center" colspan="5"><br><br><input class="button" type="submit" name="go_plus" value="speichern">
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

<input type="hidden" name="go" value="Attributeditor">

