<?php
	include(SNIPPETS.'generic_form_parts.php');
  include(LAYOUTPATH.'languages/new_layer_data_'.$this->user->rolle->language.'.php');
 ?>


	<?  
	if($this->formvars['selected_layer_id'] AND $this->Fehler == ''){
			$i = 0;
		if($this->qlayerset[$i]['template']=='' OR in_array($this->qlayerset[$i]['template'], array('generic_layer_editor.php', 'generic_layer_editor_2.php'))){
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
	}?>
	
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
		<tr align="center"> 
	  	<td height="30" valign="middle">
	  		<input type="button" class="button" name="go_plus" value="<? echo $strSave; ?>" onclick="subsave_new_layer_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->formvars['targetobject'] ?>', '<? echo $this->formvars['targetlayer_id'] ?>', '<? echo $this->formvars['targetattribute'] ?>', '<? echo $this->formvars['data'] ?>', '<? echo $this->formvars['reload'] ?>');">
	  		<input type="button" class="button" name="cancelbutton" value="<? echo $strCancel; ?>" onclick="clearsubform('<? echo $this->formvars['fromobject'] ?>');">
	  	</td>
		</tr>
	</table>
	
	
	<input name="sub_<? echo $this->formvars['selected_layer_id']; ?>_form_field_names" id="sub_<? echo $this->formvars['selected_layer_id']; ?>_form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
	
 
