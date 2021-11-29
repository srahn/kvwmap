<?
	# dies ist das Snippet für die Sachdatenanzeige eines aufgeklappten Links aus einer SubformEmbeddedPK-Liste
	include_once(SNIPPETS.'generic_form_parts.php'); 
  include(LAYOUTPATH.'languages/sachdatenanzeige_'.$this->user->rolle->language.'.php');
  
	$i = 0;

  if($this->qlayerset[$i]['template']=='' OR in_array($this->qlayerset[$i]['template'], array('generic_layer_editor.php', 'generic_layer_editor_2.php'))){
   	include(SNIPPETS.'generic_layer_editor_2_embedded.php');
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
 ?>
 <table width="100%" border="0" cellpadding="2" cellspacing="0">
    <tr align="center"> 
      <td>
    <? if($this->editable != ''){ ?>
      	<input type="button" name="sub_savebutton" id="sub_savebutton" value="<? echo $strSave; ?>" onclick="subsave_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->formvars['targetobject'] ?>', '<? echo $this->formvars['reload'] ?>');">
     <? if($this->formvars['embedded_subformPK'] == ''){
					if($this->qlayerset[$i]['privileg'] == '2'){ ?> 	
      	<input type="button" name="deletebutton" value="<? echo $strDelete; ?>" onclick="subdelete_data(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->formvars['fromobject'] ?>', '<? echo $this->qlayerset[$i]['shape'][0][$this->qlayerset[$i]['maintable'].'_oid']; ?>', '<? echo $this->formvars['targetobject'] ?>');">
     <? 	} ?>
      	<input type="button" name="cancelbutton" value="<? echo $strCancel; ?>" onclick="clearsubform('<? echo $this->formvars['fromobject'] ?>');">
      <? }
				}
      if($this->qlayerset[$i]['template']==''){ # wenn man ein Template für einen embeddeden Layer gesetzt hat, will man diesen Layer ja nur in der embeddeten Anzeige sehen?>
   			<input type="button" name="extrabutton" value="Datensatz anzeigen" onclick="overlay_link('go=Layer-Suche_Suchen&selected_layer_id=<? echo $this->qlayerset[$i]['Layer_ID'].'&value_'.$this->qlayerset[$i]['maintable'].'_oid='.$this->qlayerset[$i]['shape'][0][$this->qlayerset[$i]['maintable'].'_oid']; ?>');">
   		<? } ?>
      </td>
    </tr>
  </table>
█
if (button = document.getElementById('sub_savebutton')) {
	var button_bottom = button.getBoundingClientRect().bottom;
	if(button_bottom > window.innerHeight){
		window.scrollBy({top: button_bottom - window.innerHeight + 70, behavior: 'smooth'});		// wegen Overlayfooter geht kein scrollintoview
	}
}
auto_resize_overlay();
  
 
