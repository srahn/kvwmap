<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/new_layer_data_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>


	<?  
		if($this->formvars['selected_layer_id'] AND $this->Fehler == ''){
			$i = 0;
		if($this->qlayerset[$i]['template']==''){
	   	include(SNIPPETS.'generic_layer_editor_2_embedded.php');
		}
	  else{
	  	include(SNIPPETS.$this->qlayerset[$i]['template']);			# falls man mal ein eigenes Subformular einbinden will  	 
	  }			
		}?>
	
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
		<tr align="center"> 
	  	<td height="30" valign="middle">
	  		<input type="button" class="button" name="go_plus" value="<? echo $strSave; ?>" onclick="subsave_new_layer_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->formvars['targetobject'] ?>', '<? echo $this->formvars['targetlayer_id'] ?>', '<? echo $this->formvars['targetattribute'] ?>', '<? echo $this->formvars['data'] ?>');">
	  		<input type="button" class="button" name="cancelbutton" value="<? echo $strCancel; ?>" onclick="clearsubform('<? echo $this->formvars['fromobject'] ?>');">
	  	</td>
		</tr>
	</table>
	
	
	<input name="sub_<? echo $this->formvars['selected_layer_id']; ?>_form_field_names" id="sub_<? echo $this->formvars['selected_layer_id']; ?>_form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
	
 
