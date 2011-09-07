<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/sachdatenanzeige_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>

<?php 
	$i = 0;
  include(SNIPPETS.'generic_layer_editor_2_embedded.php');			

	if($this->editable == 'true'){ ?>
		<table width="100%" border="0" cellpadding="2" cellspacing="0">
    <tr align="center"> 
      <td>
      	<input type="button" class="button" name="savebutton" value="<? echo $strSave; ?>" onclick="subsave_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->formvars['targetobject'] ?>', '<? echo $this->formvars['targetlayer_id'] ?>', '<? echo $this->formvars['targetattribute'] ?>', '<? echo $this->formvars['data'] ?>');">
     <? if($this->qlayerset[$i]['privileg'] == '2'){ ?> 	
      	<input type="button" class="button" name="deletebutton" value="<? echo $strDelete; ?>" onclick="subdelete_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->formvars['targetobject'] ?>', '<? echo $this->formvars['targetlayer_id'] ?>', '<? echo $this->formvars['targetattribute'] ?>', '<? echo $this->formvars['data'] ?>');">
     <? } ?>
      	<input type="button" class="button" name="cancelbutton" value="<? echo $strCancel; ?>" onclick="clearsubform('<? echo $this->formvars['fromobject'] ?>');">
      </td>
    </tr>
  </table>
  
  <input name="sub_<? echo $this->formvars['selected_layer_id']; ?>_form_field_names" id="sub_<? echo $this->formvars['selected_layer_id']; ?>_form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
<?
	}
?>
  
 
