<?php
	include(SNIPPETS.'generic_formelement_definitions.php'); 
  include(LAYOUTPATH.'languages/sachdatenanzeige_'.$this->user->rolle->language.'.php');
  
	$i = 0;

  if($this->qlayerset[$i]['template']==''){
   	include(SNIPPETS.'generic_layer_editor_2_embedded.php');
	}
  else{																																		# falls man mal ein eigenes Subformular einbinden will
	  if(is_file(SNIPPETS.$this->qlayerset[$i]['template'])){
	   	include(SNIPPETS.$this->qlayerset[$i]['template']);
	  }
		else{
			if(file_exists(PLUGINS.$this->qlayerset[$i]['template'])){
				include(PLUGINS.$this->qlayerset[$i]['template']);			# Pluginviews
			}
		}  	 
  }
 ?>
 <table width="100%" border="0" cellpadding="2" cellspacing="0">
    <tr align="center"> 
      <td>
    <? if($this->editable == 'true'){ ?>
      	<input type="button" class="button" name="savebutton" value="<? echo $strSave; ?>" onclick="subsave_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->formvars['targetobject'] ?>', '<? echo $this->formvars['targetlayer_id'] ?>', '<? echo $this->formvars['targetattribute'] ?>', '<? echo $this->formvars['data'] ?>', '<? echo $this->formvars['reload'] ?>');">
     <? if($this->qlayerset[$i]['privileg'] == '2'){ ?> 	
      	<input type="button" class="button" name="deletebutton" value="<? echo $strDelete; ?>" onclick="subdelete_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->formvars['targetobject'] ?>', '<? echo $this->formvars['targetlayer_id'] ?>', '<? echo $this->formvars['targetattribute'] ?>', '<? echo $this->formvars['data'] ?>');">
     <? } ?>
      	<input type="button" class="button" name="cancelbutton" value="<? echo $strCancel; ?>" onclick="clearsubform('<? echo $this->formvars['fromobject'] ?>');">
      <? }
      if($this->qlayerset[$i]['template']==''){ # wenn man ein Template für einen embeddeden Layer gesetzt hat, will man diesen Layer ja nur in der embeddeten Anzeige sehen?>
   			<input type="button" class="button" name="extrabutton" value="Datensatz anzeigen" onclick="location.href='index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo $this->qlayerset[$i]['Layer_ID'].'&value_'.$this->qlayerset[$i]['maintable'].'_oid='.$this->qlayerset[$i]['shape'][0][$this->qlayerset[$i]['maintable'].'_oid']; ?>'">
   		<? } ?>
      </td>
    </tr>
  </table>
  
  <input name="sub_<? echo $this->formvars['selected_layer_id']; ?>_form_field_names" id="sub_<? echo $this->formvars['selected_layer_id']; ?>_form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
  
 
