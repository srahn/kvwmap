<?php
	include(SNIPPETS.'generic_form_parts.php');
  include(LAYOUTPATH.'languages/new_layer_data_'.$this->user->rolle->language.'.php');
 ?>


	<?  
	if($this->formvars['selected_layer_id'] AND $this->Fehler == ''){
		$i = 0;
		if($this->qlayerset[$i]['template']=='' OR in_array($this->qlayerset[$i]['template'], array('generic_layer_editor.php', 'generic_layer_editor_2.php'))){
	   	include(SNIPPETS.'generic_layer_editor_2_embedded.php');
			$CreateAnotherOne = true;
		}
		else{																																		# falls man mal ein eigenes Subformular einbinden will			
		  if(is_file(SNIPPETS.$this->qlayerset[$i]['template'])){
				$this->subform_classname = 'subform_'.$this->qlayerset[$i]['Layer_ID'];
		   	include(SNIPPETS.$this->qlayerset[$i]['template']);
		  }
			else{
				if(file_exists(PLUGINS.$this->qlayerset[$i]['template'])){
					$this->subform_classname = 'subform_'.$this->qlayerset[$i]['Layer_ID'];
					include(PLUGINS.$this->qlayerset[$i]['template']);			# Pluginviews
				}
			}  	 
	  }		
	}?>
	
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
		<tr align="right"> 
	  	<td height="30" valign="middle">
	  		<a tabindex="1" name="go_plus" class="buttonlink" id="sub_savebutton" href="javascript:subsave_new_layer_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->formvars['targetobject'] ?>', '<? echo $this->formvars['targetlayer_id'] ?>', '<? echo $this->formvars['targetattribute'] ?>', '<? echo $this->formvars['reload'] ?>', '<? echo $this->formvars['list_edit'] ?>');"><span><? echo $strSave; ?></span></a>
	  		<a tabindex="1" name="cancelbutton" class="buttonlink" href="javascript:clearsubform('<? echo $this->formvars['fromobject'] ?>');"><span><? echo $strCancel; ?></span></a>&nbsp;&nbsp;&nbsp;&nbsp;<?
				if ($CreateAnotherOne){ ?>
					<input
						type="checkbox"
						tabindex="1"
						class="subform_<? echo $this->formvars['selected_layer_id']; ?>"
						name="weiter_erfassen"
						value="1"<?
						echo (($this->formvars['weiter_erfassen'] == 1 OR $this->user->rolle->immer_weiter_erfassen) ? ' checked="true"' : ''); ?>
					><? echo $strCreateAnotherOne;
				} ?>
	  	</td>
		</tr>
	</table>
	<input type="hidden" name="geomtype" class="<? echo $this->subform_classname; ?>" value="<? echo $this->geomtype; ?>">
	
█
var button_bottom = document.getElementById('sub_savebutton').getBoundingClientRect().bottom;
document.getElementById('<? echo $this->formvars['fromobject'] ?>').querySelectorAll('[tabindex="1"]')[0].focus();
if(button_bottom > window.innerHeight){
	window.scrollBy({top: button_bottom - window.innerHeight + 70, behavior: 'smooth'});		// wegen Overlayfooter geht kein scrollintoview
}
auto_resize_overlay();
 
