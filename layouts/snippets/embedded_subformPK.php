<?
	include(SNIPPETS . 'generic_form_parts.php');
	include(LAYOUTPATH.'languages/generic_layer_editor_2_'.rolle::$language.'.php');
	# dies ist das Snippet für die SubformEmbeddedPK-Liste mit Links bzw. editierbaren Datensätzen untereinander oder im Rasterlayout dargestellt
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	$attributes = $layer['attributes'];

	$size = 50;
	$select_width = 'width: 235px';

	$doit = false;
	$anzObj = count_or_0($layer['shape']);
  if ($anzObj > 0) {
		$this->found = 'true';
		$doit = true;
  }

	#	Link für neuen Datensatz zusammenbauen
	$neu_link = '';
	if ($layer['privileg'] > 0 AND $this->formvars['attribute_privileg'] > 0){
		if($attributes['privileg'][$attributes['indizes'][$attributes['the_geom']]] == 1){		# falls das Geometrie-Attribut editierbar ist, im Hauptfenster öffnen
			$target = 'root';
		}
		$data = array();
		$data[] = 'go=neuer_Layer_Datensatz';
		$data[] = 'selected_layer_id=' . $this->formvars['selected_layer_id'];
		for ($p = 0; $p < count($this->formvars['attributenames']); $p++) {
			$data[] = 'attributenames[' . $p . ']=' . $this->formvars['attributenames'][$p];
			$data[] = 'values[' . $p . ']=' . $this->formvars['values'][$p];
		}
		if ($this->formvars['embedded'] == 'true'){
			$data[] = 'fromobject=new_dataset_'.$this->formvars['targetobject'];
			$data[] = 'weiter_erfassen='.$this->formvars['weiter_erfassen'];
			$neu_link = '&nbsp;<a tabindex="1" id="new_'.$this->formvars['targetobject'].'" class="buttonlink" ';
			if($target == 'root'){		# im Hauptfenster öffnen
				$neu_link .= 'target="root" href="index.php?'.implode('&', $data).'">';
			}
			else{											# eingebettet öffnen
				$data[] = 'embedded=true';
				$data[] = 'targetobject='.$this->formvars['targetobject'];
				$data[] = 'targetlayer_id='.$this->formvars['targetlayer_id'];
				$data[] = 'targetattribute='.$this->formvars['targetattribute'];
				$data[] = 'reload='.$this->formvars['reload'];
				$neu_link .= '
					href="javascript:ahah(\'index.php\', \''.implode('&', $data).'\',
					new Array(document.getElementById(\'new_dataset_'.$this->formvars['targetobject'].'\'), \'\'), 
					new Array(\'sethtml\', \'execute_function\'));
					clearsubforms(\''.$this->formvars['targetlayer_id'].'_'.$this->formvars['selected_layer_id'].'\');">';
			}
			$neu_link .= '<span>'.$strNewEmbeddedPK.'</span></a>';
		}
		else {
			$data[] = 'subform=true';
			$data[] = 'layer_id_mother=' . $this->formvars['targetlayer_id'];
			$data[] = 'oid_mother=' . $this->formvars['oid_mother'];
			$data[] = 'tablename_mother=' . $this->formvars['tablename_mother'];
			$data[] = 'columnname_mother=' . $this->formvars['columnname_mother']; 
			$neu_link .= '
			<a class="buttonlink" ' . ($this->formvars['no_new_window'] != true ? ' target="_blank"': '') . '	href="javascript:overlay_link(\'&' . implode('&', $data) . '\', false, \'' . $target . '\')">
				<span>&nbsp;' . $strNewEmbeddedPK . '</span>
			</a>';
		}
	}

	if($this->formvars['list_edit'] OR $layer['template']=='generic_layer_editor_doc_raster.php'){		# in beiden Fällen erscheint ein SubFormular mit mehreren editierbaren Datensätzen
		$this->subform_classname = 'subform_'.$layer['layer_id'];
		if ($layer['template']=='generic_layer_editor_doc_raster.php') { # die Raster-Darstellung kann auch anstatt der SubFormEmbedded-Liste verwendet werden
			$save_button_display = 'display: none';
			include(SNIPPETS.$layer['template']);
		}
		else {
			if ($this->formvars['list_edit']) {
				$table_id = rand(0, 100000);			 ?>
				<div class="list_edit_div">
				<table id="<? echo $table_id; ?>" border="1" cellspacing="0" cellpadding="2" style="border-collapse: collapse" width="100%">
					<tr><?
						for ($j = 0; $j < count($attributes['name']); $j++) {
							if ($layer['attributes']['privileg'][$j] >= '0') {
								if ($layer['attributes']['visible'][$j]) {
									$explosion = explode(';', $layer['attributes']['group'][$j]);
									if ($explosion[1] != 'collapsed') { ?>
										<td class="gle-attribute-name" style="height: auto">
										<? if ($layer['attributes']['labeling'][$j] != 2) { ?>
											<a href="javascript:reload_subform_list('<? echo $this->formvars['targetobject']; ?>', 1, '', '', '&orderby<? echo $layer['layer_id']; ?>=<? echo $layer['attributes']['name'][$j]; ?>')" title="Sortieren nach <? echo $layer['attributes']['name'][$j]; ?>"><?
												echo attribute_name($layer['layer_id'], $layer['attributes'], $j, 0, false); ?>
											</a>
										<? } ?>
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
						$element_id = 'subform_dataset_tr_' . $layer['layer_id'] . '_' . $layer['shape'][$k][$layer['maintable'] . '_oid']; ?>
						<tr id="<? echo $element_id; ?>">
							<td style="display: none">
								<input type="hidden" value="" onchange="root.document.GUI.gle_changed.value=this.value" name="changed_<? echo $layer['layer_id'].'_'.str_replace('-', '', $layer['shape'][$k][$layer['maintable'].'_oid']); ?>">
								<input
									id="<? echo $layer['layer_id'] . '_' . $k; ?>"
									type="checkbox"
									class="check_<? echo $layer['layer_id']; ?> <? if ($layer['shape'][$k][$layer['attributes']['Editiersperre']] == 't') { echo 'no_edit'; } ?>"
									name="check;<? echo $layer['attributes']['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['layer_id']; ?>"
								>
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
											<td id="value_<? echo $layer['layer_id'] . '_' . $layer['attributes']['name'][$j] . '_' . $k; ?>" <? echo get_td_class_or_style(array($layer['shape'][$k][$layer['attributes']['style_attribute'][$j]])); ?>><?
												if (in_array($layer['attributes']['type'][$j], array('date', 'time', 'timestamp'))){
													echo calendar($layer['attributes']['type'][$j], $layer['layer_id'].'_'.$layer['attributes']['name'][$j].'_'.$k, $layer['attributes']['privileg'][$j]);
												}
												echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size, $select_width, false, NULL, NULL, NULL, $this->subform_classname); ?>
											</td><?
										}
									}
									else {
										$invisible_attributes[$layer['layer_id']][] = '<input type="hidden" class="'.$this->subform_classname.'" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$layer['attributes']['name'][$j]]).'">';
									}
								}
							} 
							if ($layer['privileg'] == 2 and $layer['shape'][$k][$layer['attributes']['Editiersperre']] != 't'){	?>
							<td style="text-align: center">
								<a href="javascript:void(0)" onclick="subdelete_data(<? echo $layer['layer_id']; ?>, '<? echo $element_id; ?>', '<? echo $layer['shape'][$k][$layer['maintable'] . '_oid']; ?>', '');"><img style="width: 18px" src="graphics/datensatz_loeschen.png"></a>
							</td>
							<? } ?>
						</tr><?
						$layer['attributes']['privileg'] = $definierte_attribute_privileges;
					} ?>
				</table>
				</div>

				<script type="text/javascript">
					var vchangers = document.getElementById(<? echo $table_id; ?>).querySelectorAll('.visibility_changer');
					[].forEach.call(vchangers, function(vchanger){vchanger.oninput();});
				</script>				
	<?
				for($l = 0; $l < count_or_0($invisible_attributes[$layer['layer_id']]); $l++){
					echo $invisible_attributes[$layer['layer_id']][$l]."\n";
				}
			}
		} ?>
		<div style="width: 100%;text-align: center;margin-top: 4px">
	<? if ($anzObj > 0){
			if ($editable AND $this->formvars['list_edit']) { ?>
				<a tabindex="1" class="buttonlink" href="javascript:reload_subform_list('<? echo $this->formvars['targetobject']; ?>', 0)"><span><? echo $this->strCancel; ?></span></a>
			<? }
			if($editable OR $layer['template'] == 'generic_layer_editor_doc_raster.php'){ ?>
				<a id="subform_save_button_<? echo $layer['layer_id']; ?>" class="buttonlink" style="<? echo $save_button_display; ?>" tabindex="1" href="javascript:subsave_data(<? echo $layer['layer_id']; ?>, '<? echo $this->formvars['targetobject']; ?>', '<? echo $this->formvars['targetobject']; ?>', <? echo $this->formvars['reload']; ?>);"><span>Speichern</span></a>
				<a id="subdelete_all_button_<? echo $layer['layer_id']; ?>" class="buttonlink" style="<? echo $save_button_display; ?>" tabindex="1" href="javascript:subdelete_all(<? echo $layer['layer_id']; ?>, '<? echo $this->formvars['targetobject']; ?>', '<? echo $this->formvars['targetobject']; ?>', <? echo $this->formvars['reload']; ?>);"><span>alle Löschen</span></a>
	<?	}
		}
		
		echo $neu_link;
		
		if ($this->formvars['list_edit']) {
			echo '&nbsp;<a tabindex="1" class="show_all_button buttonlink" href="javascript:void(0);" onclick="overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$this->formvars['selected_layer_id'];
			for($p = 0; $p < count($this->formvars['attributenames']); $p++){
				echo '&value_'.$this->formvars['attributenames'][$p].'='.$this->formvars['values'][$p];
				echo '&operator_'.$this->formvars['attributenames'][$p].'==';
			}				
			echo '&subform_link=true\')"><span>'.$strShowAllSeparat.'</span></a>';
		}
			?>
		</div>
	<?
	}		# Ende list-edit oder Rasterlayout, Anfang "normale" Subformliste
	else { 
		if ($this->formvars['show_count']) {	?>
			<div class="subFormShowCount"><? echo $anzObj . (($anzObj == 1)? ' Datensatz' : ' Datensätze'); ?></div>
<?  }	?>
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
								case 'Auswahlfeld' : case 'Auswahlfeld_Bild' : case 'Radiobutton' : {
									if (is_array($attributes['dependent_options'][$j])) {		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
										$enum = $attributes['enum'][$j][$k][$dataset[$attributes['name'][$j]]];
									}
									else {
										$enum = $attributes['enum'][$j][$dataset[$attributes['name'][$j]]];
									}
									$output[$p] = ($attributes['form_element_type'][$j] == 'Auswahlfeld_Bild'? '<img style="width: 30px" src="data:image/jpg;base64,' . base64_encode(@file_get_contents($enum['image'])) . '">&nbsp;' : '') . $enum['output'];
								} break;
								
								case 'Autovervollständigungsfeld' : case 'Autovervollständigungsfeld_zweispaltig' :{
									$output[$p] = $attributes['enum_output'][$j][$k];
								} break;

								case 'Dokument' : {
									if ($dataset[$attributes['name'][$j]] != '') {
										$preview = $this->get_dokument_vorschau($dataset[$attributes['name'][$j]], $layer['document_path'], $layer['document_url']);
										switch ($preview['doc_type']) {
											case 'local_img' : {	# Bilder mit Vorschaubild
												$preview_link = '<a class="preview_link" ' . $preview['target'] . ' href="' . $preview['doc_src'] . '"><img class="preview_image" src="' . $preview['thumb_src'] . '"></a>';
												$preview_link = '<table><tr><td class="td_preview_image">' . $preview_link . '</td></tr></table>';
											}break;
											
											case 'local_doc' : case 'remote_url' : {	# lokale Dateien oder fremde URLs
												$preview_link = '<a class="preview_link" ' . $preview['target'] . ' href="' . $preview['doc_src'] . '"><img class="preview_doc" src="' . $preview['thumb_src'] . '"></a>';
											}break;
											
											case 'videostream' : {	# Videostream
												$preview_link = '
													<video width="'.PREVIEW_IMAGE_WIDTH.'" controls>
														<source src="' . $preview['doc_src'] . '" type="video/mp4">
													</video>
													';
											}break;
										}
										$output[$p] = '<table><tr><td>' . $preview['original_name'] . '</td>';
									}
									else {
										$output[$p] = '<table><tr><td></td>';
									}
									$output[$p] .= '<td><img border="0" title="zum Datensatz" src="'.GRAPHICSPATH.'zum_datensatz.gif"></td></tr></table>';
									echo '<input type="hidden" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].'" value="'.$dataset[$attributes['name'][$j]].'"></td>';
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
								<td'. get_td_class_or_style(array($dataset[$attributes['style'][0]], 'subFormListItem')) . '>'.($preview_link != ''? $preview_link.'</td><td valign="top">' : '');
								
				if ($this->formvars['embedded'] == 'true') {
					echo '<a href="javascript:void(0);" onclick="checkForUnsavedChanges(event);if (document.getElementById(\'subform'.$this->formvars['targetlayer_id'].'_'.$layer['layer_id'].$this->formvars['count'].'_'.$k.'\').innerHTML == \'\')ahah(\'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$layer['layer_id'].'&value_'.$layer['maintable'].'_oid='.$dataset[$layer['maintable'].'_oid'].'&embedded=true&subform_link=true&fromobject=subform'.$this->formvars['targetlayer_id'].'_'.$layer['layer_id'].$this->formvars['count'].'_'.$k.'&targetobject='.$this->formvars['targetobject'].'&reload='.$this->formvars['reload'].'&attribute_privileg='.$this->formvars['attribute_privileg'].'\', new Array(document.getElementById(\'subform'.$this->formvars['targetlayer_id'].'_'.$layer['layer_id'].$this->formvars['count'].'_'.$k.'\'), \'\'), new Array(\'sethtml\', \'execute_function\'));clearsubforms(\''.$this->formvars['targetlayer_id'].'_'.$layer['layer_id'].'\');">'.implode(' ', $output).'</a><div class="subForm" id="subform'.$this->formvars['targetlayer_id'].'_'.$layer['layer_id'].$this->formvars['count'].'_'.$k.'"></div></td>';
				}
				else {
					echo '<a ';
									if ($this->formvars['no_new_window'] != true) {
										echo 	' target="_blank"';
									}
					echo ' href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$layer['layer_id'].'&value_'.$layer['maintable'].'_oid='.$dataset[$layer['maintable'].'_oid'].'&subform_link=true&attribute_privileg='.$this->formvars['attribute_privileg'].'\')">'.implode(' ', $output).'</a></td>
								</tr>';
				}
			} ?>
		</table>

		<table width="100%">
			<tr>
				<td class="gle_neu_link"><?
					# alle anzeigen
					if ($anzObj > 1) {
						# alle in Liste anzeigen
						if ($this->formvars['embedded'] == 'true') {
							echo '<a tabindex="1" id="edit_list_'.$this->formvars['targetobject'].'" class="list_edit_button buttonlink" href="javascript:void(0);" onclick="checkForUnsavedChanges(event); reload_subform_list(\''.$this->formvars['targetobject'].'\', 1)"><span>'.$strShowAll.'</span></a>';
						}
						else{		# alle separat anzeigen
							echo '&nbsp;<a tabindex="1" class="show_all_button buttonlink" href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$this->formvars['selected_layer_id'];
							for($p = 0; $p < count($this->formvars['attributenames']); $p++){
								echo '&value_'.$this->formvars['attributenames'][$p].'='.$this->formvars['values'][$p];
								echo '&operator_'.$this->formvars['attributenames'][$p].'==';
							}				
							echo '&subform_link=true\')"><span>'.$strShowAll.'</span></a>';
						}
					}
					# neu
					echo $neu_link;
					?>
				</td>
			</tr>
		</table>
		<?
	}
	echo '<div class="subForm" id="new_dataset_'.$this->formvars['targetobject'].'"></div>';
	if($this->formvars['weiter_erfassen'] == 1){
		echo '
			<script type="text/javascript">
				href_save = document.getElementById("new_'.$this->formvars['targetobject'].'").href;
				document.getElementById("new_'.$this->formvars['targetobject'].'").href = document.getElementById("new_'.$this->formvars['targetobject'].'").href.replace("go=neuer_Layer_Datensatz", "go=neuer_Layer_Datensatz&weiter_erfassen=1'.urldecode($this->formvars['weiter_erfassen_params']).'")
				document.getElementById("new_'.$this->formvars['targetobject'].'").click();
				document.getElementById("new_'.$this->formvars['targetobject'].'").href = href_save;
			</script>';
	}
	
	echo '
		<script type="text/javascript">
			root.open_subform_requests--;
			auto_resize_overlay();
		</script>';
?>