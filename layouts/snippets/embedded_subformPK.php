<?
	include(SNIPPETS . 'generic_form_parts.php');
	include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'.php');
	# dies ist das Snippet für die SubformEmbeddedPK-Liste mit Links bzw. editierbaren Datensätzen untereinander oder im Rasterlayout dargestellt
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	$attributes = $layer['attributes'];

	$size = 50;

	$doit = false;
	$anzObj = count($layer['shape']);
  if ($anzObj > 0) {
		$this->found = 'true';
		$doit = true;
  }

if($this->formvars['list_edit'] OR $layer['template']=='generic_layer_editor_doc_raster.php'){		# in beiden Fällen erscheint ein SubFormular mit mehreren editierbaren Datensätzen
	$this->subform_classname = 'subform_'.$layer['Layer_ID'];
	if ($layer['template']=='generic_layer_editor_doc_raster.php') { # die Raster-Darstellung kann auch anstatt der SubFormEmbedded-Liste verwendet werden
		$save_button_display = 'display: none';
		include(SNIPPETS.$layer['template']);
	}
	else {
		if ($this->formvars['list_edit']) { ?>
			<table border="1" cellspacing="0" cellpadding="2" style="border-collapse: collapse" width="100%">
				<tr><?
					for ($j = 0; $j < count($attributes['name']); $j++) {
						if ($layer['attributes']['privileg'][$j] >= '0') {
							if ($layer['attributes']['visible'][$j]) {
								$explosion = explode(';', $layer['attributes']['group'][$j]);
								if ($explosion[1] != 'collapsed') { ?>
									<td class="gle-attribute-name">
										<a href="javascript:reload_subform_list('<? echo $this->formvars['targetobject']; ?>', 1, '', '', '&orderby<? echo $layer['Layer_ID']; ?>=<? echo $layer['attributes']['name'][$j]; ?>')" title="Sortieren nach <? echo $layer['attributes']['name'][$j]; ?>"><?
											echo attribute_name($layer['Layer_ID'], $layer['attributes'], $j, 0, $this->user->rolle->fontsize_gle, false); ?>
										</a>
									</td><?
								}
							}
						}
					}
					if ($layer['privileg'] == 2){	?>
						<td class="gle-attribute-name">&nbsp;</td>
					<? } ?>
				</tr><?
				for ($k = 0; $k < $anzObj; $k++) {
					$definierte_attribute_privileges = $layer['attributes']['privileg'];		// hier sichern und am Ende des Datensatzes wieder herstellen
					if (is_array($layer['attributes']['privileg'])) {
						if ($layer['shape'][$k][$layer['attributes']['Editiersperre']] == 't' OR $this->formvars['attribute_privileg'] == '0') {
							$layer['attributes']['privileg'] = array_map(function($attribut_privileg) { return 0; }, $layer['attributes']['privileg']);
						}
					}
					$element_id = 'subform_dataset_tr_' . $layer['Layer_ID'] . '_' . $layer['shape'][$k][$layer['maintable'] . '_oid']; ?>
					<tr id="<? echo $element_id; ?>">
						<td style="display: none">
							<input type="hidden" value="" onchange="root.document.GUI.gle_changed.value=this.value" name="changed_<? echo $layer['Layer_ID'].'_'.str_replace('-', '', $layer['shape'][$k][$layer['maintable'].'_oid']); ?>">
						</td><?
						$editable = false;
						for ($j = 0; $j < count($attributes['name']); $j++) {
							if ($layer['attributes']['privileg'][$j] >= '0') {
								if ($layer['attributes']['privileg'][$j] == 1) {
									$editable = true;
								}								
								if ($layer['attributes']['visible'][$j]) {
									$explosion = explode(';', $layer['attributes']['group'][$j]);
									if ($explosion[1] != 'collapsed') { ?>
										<td <? echo get_td_class_or_style(array($layer['shape'][$k][$attributes['style']])); ?>><?
											echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size, $select_width, $this->user->rolle->fontsize_gle, false, NULL, NULL, NULL, $this->subform_classname); ?>
										</td><?
									}
								}
								else {
									$invisible_attributes[$layer['Layer_ID']][] = '<input type="hidden" class="'.$this->subform_classname.'" name="'.$layer['Layer_ID'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$layer['attributes']['name'][$j]]).'">';
								}
							}
						} 
						if ($layer['privileg'] == 2 and $layer['shape'][$k][$layer['attributes']['Editiersperre']] != 't'){	?>
						<td style="text-align: center">
							<a href="javascript:void(0)" onclick="subdelete_data(<? echo $layer['Layer_ID']; ?>, '<? echo $element_id; ?>', <? echo $layer['shape'][$k][$layer['maintable'] . '_oid']; ?>, '');"><img style="width: 18px" src="graphics/datensatz_loeschen.png"></a>
						</td>
						<? } ?>
					</tr><?
					$layer['attributes']['privileg'] = $definierte_attribute_privileges;
				} ?>
			</table>
<?
			for($l = 0; $l < count($invisible_attributes[$layer['Layer_ID']]); $l++){
				echo $invisible_attributes[$layer['Layer_ID']][$l]."\n";
			}
		}
	} ?>
	<div style="width: 100%;text-align: center;margin-top: 4px">
<? if ($anzObj > 0){
		if ($this->formvars['list_edit']) { ?>
			<a tabindex="1" class="buttonlink" href="javascript:reload_subform_list('<? echo $this->formvars['targetobject']; ?>', 0)"><span><? echo $this->strCancel; ?></span></a>
		<? }
		if($editable OR $layer['template'] == 'generic_layer_editor_doc_raster.php'){ ?>
			<a id="subform_save_button_<? echo $layer['Layer_ID']; ?>" class="buttonlink" style="<? echo $save_button_display; ?>" tabindex="1" href="javascript:subsave_data(<? echo $layer['Layer_ID']; ?>, '<? echo $this->formvars['targetobject']; ?>', '<? echo $this->formvars['targetobject']; ?>', <? echo $this->formvars['reload']; ?>);"><span>Speichern</span></a>
<?	}
	 }
		if ($layer['privileg'] > 0 AND $this->formvars['attribute_privileg'] > 0){
			echo '<a tabindex="1" id="new_'.$this->formvars['targetobject'].'" class="buttonlink" href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz';
			for ($p = 0; $p < count($this->formvars['attributenames']); $p++) {
				echo '&attributenames['.$p.']='.$this->formvars['attributenames'][$p];
				echo '&values['.$p.']='.$this->formvars['values'][$p];
			}
			echo '&selected_layer_id='.$this->formvars['selected_layer_id'].
					 '&embedded=true&fromobject=new_dataset_'.$this->formvars['targetobject'].
					 '&weiter_erfassen='.$this->formvars['weiter_erfassen'].
					 '&targetobject='.$this->formvars['targetobject'].
					 '&targetlayer_id='.$this->formvars['targetlayer_id'].
					 '&targetattribute='.$this->formvars['targetattribute'].
					 '&list_edit=1\', 
					 new Array(document.getElementById(\'new_dataset_'.$this->formvars['targetobject'].'\'), \'\'), 
					 new Array(\'sethtml\', \'execute_function\'));
					 clearsubforms(\''.$this->formvars['targetlayer_id'].'_'.$this->formvars['selected_layer_id'].'\');"><span>'.$strNewEmbeddedPK.'</span></a>';
		}
		if ($this->formvars['list_edit']) {
			echo '&nbsp;<a tabindex="1" style="font-size: '.$linksize.'px;" class="show_all_button buttonlink" href="javascript:void(0);" onclick="overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$this->formvars['selected_layer_id'];
			for($p = 0; $p < count($this->formvars['attributenames']); $p++){
				echo '&value_'.$this->formvars['attributenames'][$p].'='.$this->formvars['values'][$p];
				echo '&operator_'.$this->formvars['attributenames'][$p].'==';
			}				
			echo '&subform_link=true\')"><span>'.$strShowAllSeparat.'</span></a>';
		}
		?>
	</div>
<?
}
else{ ?>
			<table border="0" cellspacing="0" cellpadding="2" width="100%"><?
				$preview_attributes = explode(' ', $this->formvars['preview_attribute']);
				for ($k=0;$k<$anzObj;$k++) {
					$preview = '';
					$dataset = $layer['shape'][$k]; # der aktuelle Datensatz
					for ($p = 0; $p < count($preview_attributes); $p++) {
						$output[$p] = $preview_attributes[$p];
						for ($j = 0; $j < count($attributes['name']); $j++) {
							if ($preview_attributes[$p] == $attributes['name'][$j]) {
								$output[$p] = '';
								switch ($attributes['form_element_type'][$j]) {
									case 'Auswahlfeld' : case 'Radiobutton' : {
										if (is_array($attributes['dependent_options'][$j])) {		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
											for ($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++) {
												if ($attributes['enum_value'][$j][$k][$e] == $dataset[$attributes['name'][$j]]) {
													$output[$p] = $attributes['enum_output'][$j][$k][$e];
													break;
												}
											}
										}
										else {
											for ($e = 0; $e < count($attributes['enum_value'][$j]); $e++) {
												if ($attributes['enum_value'][$j][$e] == $dataset[$attributes['name'][$j]]) {
													$output[$p] = $attributes['enum_output'][$j][$e];
													break;
												}
											}
										} 
									} break;
									
									case 'Autovervollständigungsfeld' : case 'Autovervollständigungsfeld_zweispaltig' :{
										$output[$p] = $attributes['enum_output'][$j][$k];
									} break;

									case 'Dokument' : {
										if ($dataset[$attributes['name'][$j]]!='') {
											$dokumentpfad = $dataset[$attributes['name'][$j]];
											$pfadteil = explode('&original_name=', $dokumentpfad);
											$dateiname = $pfadteil[0];
											if ($layer['document_url'] != '')$dateiname = url2filepath($dateiname, $layer['document_path'], $layer['document_url']);
											$pathinfo = pathinfo($dateiname);
											$type = strtolower($pathinfo['extension']);
											$thumbname = $this->get_dokument_vorschau(
												array(
													$pathinfo['dirname'] . '/' . $pathinfo['filename'],
													$pathinfo['extension']
												)
											);
											if ($layer['document_url'] != '') {
												$url = '';										# URL zu der Datei (komplette URL steht schon in $dokumentpfad)
												$target = 'target="_blank"';
												$thumbname = dirname($dokumentpfad).'/'.basename($thumbname);
											}
											else {
												$original_name = $pfadteil[1];
												$this->allowed_documents[] = addslashes($dateiname);
												$this->allowed_documents[] = addslashes($thumbname);
												$url = IMAGEURL.$this->document_loader_name.'?dokument=';			# absoluter Dateipfad
											}
											if (in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf')) ) {
												$preview = '<a class="preview_link" '.$target.' href="'.$url.$dokumentpfad.'"><img class="preview_image" src="'.$url.$thumbname.'"></a>';
											}
											else {
												$preview = '<a class="preview_link" '.$target.' href="'.$url.$dokumentpfad.'"><img class="preview_doc" src="'.$url.$thumbname.'"></a>';
											}
											$output[$p] = '<table><tr><td>'.$original_name.'</td>';
										}
										else {
											$output[$p] = '<table><tr><td></td>';
										}
										$output[$p] .= '<td><img border="0" title="zum Datensatz" src="'.GRAPHICSPATH.'zum_datensatz.gif"></td></tr></table>';
										echo '<input type="hidden" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].'" value="'.$dataset[$attributes['name'][$j]].'"></td>';
									} break;
							
									case 'Link': {
										$output[$p] = basename($dataset[$preview_attributes[$p]]);
									} break;
							
									default : {
										$output[$p] = $dataset[$preview_attributes[$p]];
									}
								}
								if ($output[$p] == '') {
									$output[$p] = ' ';
								}
							}
						}
					}
					echo '<tr style="border: none">
									<td'. get_td_class_or_style(array($dataset[$attributes['style']], 'subFormListItem')) . '>'.($preview != ''? $preview.'</td><td valign="top">' : '');
									
					if ($this->formvars['embedded'] == 'true') {
						echo '<a style="font-size: '.$this->user->rolle->fontsize_gle.'px;" href="javascript:void(0);" onclick="checkForUnsavedChanges(event);if (document.getElementById(\'subform'.$this->formvars['targetlayer_id'].'_'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'\').innerHTML == \'\')ahah(\'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$layer['Layer_ID'].'&value_'.$layer['maintable'].'_oid='.$dataset[$layer['maintable'].'_oid'].'&embedded=true&subform_link=true&fromobject=subform'.$this->formvars['targetlayer_id'].'_'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'&targetobject='.$this->formvars['targetobject'].'&reload='.$this->formvars['reload'].'&attribute_privileg='.$this->formvars['attribute_privileg'].'\', new Array(document.getElementById(\'subform'.$this->formvars['targetlayer_id'].'_'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'\'), \'\'), new Array(\'sethtml\', \'execute_function\'));clearsubforms(\''.$this->formvars['targetlayer_id'].'_'.$layer['Layer_ID'].'\');">'.implode(' ', $output).'</a><div id="subform'.$this->formvars['targetlayer_id'].'_'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'"></div></td>';
					}
					else {
						echo '<a style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										if ($this->formvars['no_new_window'] != true) {
											echo 	' target="_blank"';
										}
						echo ' href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$layer['Layer_ID'].'&value_'.$layer['maintable'].'_oid='.$dataset[$layer['maintable'].'_oid'].'&subform_link=true&attribute_privileg='.$this->formvars['attribute_privileg'].'\')">'.implode(' ', $output).'</a></td>
									</tr>';
					}
				} ?>
			</table>

			<table width="100%">
				<tr>
					<td align="right"><?
						# alle anzeigen
						if ($anzObj > 1) {
							# alle in Liste anzeigen
							if ($this->formvars['embedded'] == 'true') {
								echo '<a tabindex="1" id="edit_list_'.$this->formvars['targetobject'].'" class="list_edit_button buttonlink" href="javascript:void(0);" onclick="checkForUnsavedChanges(event); reload_subform_list(\''.$this->formvars['targetobject'].'\', 1)"><span>'.$strShowAll.'</span></a>';
							}
							else{		# alle separat anzeigen
								echo '&nbsp;<a tabindex="1" style="font-size: '.$linksize.'px;" class="show_all_button buttonlink" href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$this->formvars['selected_layer_id'];
								for($p = 0; $p < count($this->formvars['attributenames']); $p++){
									echo '&value_'.$this->formvars['attributenames'][$p].'='.$this->formvars['values'][$p];
									echo '&operator_'.$this->formvars['attributenames'][$p].'==';
								}				
								echo '&subform_link=true\')"><span>'.$strShowAll.'</span></a>';
							}
						}
						# neu
						if ($layer['privileg'] > 0 AND $this->formvars['attribute_privileg'] > 0){
							if ($this->formvars['embedded'] == 'true'){
								echo '&nbsp;<a tabindex="1" id="new_'.$this->formvars['targetobject'].'" class="buttonlink" href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz';
								for($p = 0; $p < count($this->formvars['attributenames']); $p++){
									echo '&attributenames['.$p.']='.$this->formvars['attributenames'][$p];
									echo '&values['.$p.']='.$this->formvars['values'][$p];
								}
								echo '&selected_layer_id='.$this->formvars['selected_layer_id'].
										 '&embedded=true&fromobject=new_dataset_'.$this->formvars['targetobject'].
										 '&weiter_erfassen='.$this->formvars['weiter_erfassen'].
										 '&targetobject='.$this->formvars['targetobject'].
										 '&targetlayer_id='.$this->formvars['targetlayer_id'].
										 '&targetattribute='.$this->formvars['targetattribute'].
										 '&mime_type='.$this->formvars['mime_type'].
										 '&reload='.$this->formvars['reload'].'\', 
										 new Array(document.getElementById(\'new_dataset_'.$this->formvars['targetobject'].'\'), \'\'), 
										 new Array(\'sethtml\', \'execute_function\'));
										 clearsubforms(\''.$this->formvars['targetlayer_id'].'_'.$this->formvars['selected_layer_id'].'\');"><span>'.$strNewEmbeddedPK.'</span></a>';
							}
							else {
								$data = array();
								$data[] = 'go=neuer_Layer_Datensatz';
								$data[] = 'subform=true';
								for ($p = 0; $p < count($this->formvars['attributenames']); $p++) {
									$data[] = 'attributenames[' . $p . ']=' . $this->formvars['attributenames'][$p];
									$data[] = 'values[' . $p . ']=' . $this->formvars['values'][$p];
								}
								$data[] = 'selected_layer_id=' . $this->formvars['selected_layer_id'];
								$data[] = 'layer_id_mother=' . $this->formvars['targetlayer_id'];
								$data[] = 'oid_mother=' . $this->formvars['oid_mother'];
								$data[] = 'tablename_mother=' . $this->formvars['tablename_mother'];
								$data[] = 'columnname_mother=' . $this->formvars['columnname_mother']; ?>
								<a class="buttonlink"<?
									if ($this->formvars['no_new_window'] != true) {
										echo ' target="_blank"';
									}	?>	href="javascript:overlay_link('&<? echo implode('&', $data); ?>')">
									<span>&nbsp;<?php echo $strNewEmbeddedPK; ?></span>
								</a><?
							}
						}
						?>
					</td>
				</tr>
			</table>
			<?
		}
		echo '<div style="display:inline" id="new_dataset_'.$this->formvars['targetobject'].'"></div>';
		if($this->formvars['weiter_erfassen'] == 1){
			echo '
				<script type="text/javascript">
					href_save = document.getElementById("new_'.$this->formvars['targetobject'].'").href;
					document.getElementById("new_'.$this->formvars['targetobject'].'").href = document.getElementById("new_'.$this->formvars['targetobject'].'").href.replace("go=neuer_Layer_Datensatz", "go=neuer_Layer_Datensatz&weiter_erfassen=1'.urldecode($this->formvars['weiter_erfassen_params']).'")
					document.getElementById("new_'.$this->formvars['targetobject'].'").click();
					document.getElementById("new_'.$this->formvars['targetobject'].'").href = href_save;
				</script>';
		}
?>