<?php
	include(SNIPPETS.'generic_formelement_definitions.php'); 
  include(LAYOUTPATH.'languages/new_layer_data_'.$this->user->rolle->language.'.php');
	
	include(SNIPPETS.'sachdatenanzeige_functions.php'); 
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

//-->
</script>
<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><h2><?php echo $strtitle; ?>:&nbsp;<? echo $this->qlayerset[$i]['Name']; ?></h2></td>
  </tr>
  <tr <? if($this->formvars['selected_layer_id'] != '')echo 'style="display:none"'; ?>>
  	<td>&nbsp;</td>
  </tr>
  <tr <? if($this->formvars['selected_layer_id'] != '')echo 'style="display:none"'; ?>> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5"><?php echo $strLayer;?></td>
  </tr>
  <tr <? if($this->formvars['selected_layer_id'] != '')echo 'style="display:none"'; ?>> 
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
  <?php if($this->Fehler != ''){
  	?>
  <tr>
  	<td><?php
  	 echo $this->Fehler;
  	 ?></td>
  </tr><?php
   }
   ?></table>

<?
if($this->formvars['selected_layer_id'] AND $this->Fehler == ''){
	$i = 0;	
	if($this->qlayerset[$i]['template']==''){
		include(SNIPPETS.'generic_layer_editor_2.php');			# Attribute zeilenweise
	}
	else{
		if(is_file(SNIPPETS.$this->qlayerset[$i]['template'])){
			include(SNIPPETS.$this->qlayerset[$i]['template']);
		}
		else{
			if(file_exists(PLUGINS.$this->qlayerset[$i]['template'])){
				include(PLUGINS.$this->qlayerset[$i]['template']);			# Pluginviews
			}
			else{
	   		#Version 1.6.5 pk 2007-04-17
	   	 	echo '<p>Das in den stellenbezogenen Layereigenschaften angegebene Templatefile:';
	   	 	echo '<br><span class="fett">'.SNIPPETS.$this->qlayerset[$i]['template'].'</span>';
	   	 	echo '<br>kann nicht gefunden werden. Überprüfen Sie ob der angegebene Dateiname richtig ist oder eventuell Leerzeichen angegeben sind.';
	   	 	echo ' Die Templatezuordnung für die Sachdatenanzeige ändern Sie über Stellen anzeigen, ändern, Layer bearbeiten, stellenbezogen bearbeiten.';
	   	 	#echo '<p><a href="index.php?go=Layer2Stelle_Editor&selected_layer_id='.$this->qlayerset[$i]['Layer_ID'].'&selected_stelle_id='.$this->Stelle->id.'&stellen_name='.$this->Stelle->Bezeichnung.'">zum Stellenbezogener Layereditor</a> (nur mit Berechtigung möglich)';
			}
		}
	}
		
?>
<table width="100%" border="0" cellpadding="2" cellspacing="0">
	<tr align="center"> 
  	<td>
  		<input type="button" name="go_plus" value="<? echo $strSave; ?>" onclick="save_new_dataset();">&nbsp;&nbsp;&nbsp;&nbsp;
  		<input type="checkbox" name="weiter_erfassen" value="1" <? if($this->formvars['weiter_erfassen'] == 1)echo 'checked="true"'; ?>>und einen weiteren Datensatz erfassen
  	</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<? } ?>

<input type="hidden" name="close_window" value="">
<input type="hidden" name="go" value="neuer_Layer_Datensatz">
<input name="form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
<input type="hidden" name="geomtype" value="<? echo $this->geomtype; ?>">

<?
	if ($this->Meldung1!='') {
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung1);
	}
?>  

