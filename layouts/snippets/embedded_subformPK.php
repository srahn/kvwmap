<? 
	# dies ist das Snippet für die SubformEmbeddedPK-Liste mit Links untereinander
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	$attributes = $layer['attributes'];

	$doit = false;
  $anzObj = count($layer['shape']);
  if ($anzObj > 0) {
  	$this->found = 'true';
  	$doit = true;
  }
  if($doit == true){
		if($layer['template']=='generic_layer_editor_doc_raster.php'){			# die Raster-Darstellung kann auch anstatt der SubFormEmbedded-Liste verwendet werden
			include(SNIPPETS.'sachdatenanzeige_embedded.php');
		}
		else{  ?>
		<table border="0" cellspacing="0" cellpadding="2" width="100%">		<?	
		$preview_attributes = explode(' ', $this->formvars['preview_attribute']);
		for ($k=0;$k<$anzObj;$k++){
			$dataset = $layer['shape'][$k];								# der aktuelle Datensatz
			for($p = 0; $p < count($preview_attributes); $p++){			
				for($j = 0; $j < count($attributes['name']); $j++){
					if($preview_attributes[$p] == $attributes['name'][$j]){
						switch ($attributes['form_element_type'][$j]){
							case 'Auswahlfeld' : {
								if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
									for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
										if($attributes['enum_value'][$j][$k][$e] == $dataset[$attributes['name'][$j]]){
											$output[$p] = $attributes['enum_output'][$j][$k][$e];
											break;
										}
									}
								}
								else{
									for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
										if($attributes['enum_value'][$j][$e] == $dataset[$attributes['name'][$j]]){
											$output[$p] = $attributes['enum_output'][$j][$e];
											break;
										}
									}
								} 
							}break;
							
							case 'Autovervollständigungsfeld' : {
								$output[$p] = $attributes['enum_output'][$j][$k];
							}break;
							
							case 'Dokument' : {
								if ($dataset[$attributes['name'][$j]]!='') {							
									$dokumentpfad = $dataset[$attributes['name'][$j]];
									$pfadteil = explode('&original_name=', $dokumentpfad);
									$dateiname = $pfadteil[0];
									$original_name = $pfadteil[1];
									$dateinamensteil=explode('.', $dateiname);
									$type = $dateinamensteil[1];
									$thumbname = $this->get_dokument_vorschau($dateinamensteil);
									$this->allowed_documents[] = addslashes($dateiname);
									$this->allowed_documents[] = addslashes($thumbname);
									if($attributes['options'][$j] != '' AND strtolower(substr($attributes['options'][$j], 0, 6)) != 'select'){		# bei Layern die auf andere Server zugreifen, wird die URL des anderen Servers verwendet
										$url = $attributes['options'][$j].$this->document_loader_name.'?dokument=';
									}
									else{
										$url = IMAGEURL.$this->document_loader_name.'?dokument=';
									}											
									if($type == 'jpg' OR $type == 'png' OR $type == 'gif' OR $type == 'pdf' ){
										echo '<tr><td><a class="preview_link" href="'.$url.$dokumentpfad.'"><img class="preview_image" src="'.$url.$thumbname.'"></a></td></tr>';									
									}else{
										echo '<tr><td><a class="preview_link" href="'.$url.$dokumentpfad.'"><img class="preview_doc" src="'.$url.$thumbname.'"></a></td></tr>';
									}
									$output[$p] = '<table><tr><td>'.$original_name.'</td>';
									echo '<input type="hidden" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].'" value="'.$dataset[$attributes['name'][$j]].'"></td>';
								}
								$output[$p] .= '<td><img border="0" title="zum Datensatz" src="'.GRAPHICSPATH.'zum_datensatz.gif"></td></tr></table>';
							}break;
							
							case 'Link': {
								$output[$p] = basename($dataset[$preview_attributes[$p]]);								
							} break;
							
							default : {
								$output[$p] = $dataset[$preview_attributes[$p]];						
							}
						}
						if($output[$p] == '')$output[$p] = ' ';
					}
				}
				if($output[$p] == '')$output[$p] = $preview_attributes[$p];
			}
			if($this->formvars['embedded'] == 'true'){
				echo '<tr style="border: none">
								<td style="height:20px"><a style="font-size: '.$this->user->rolle->fontsize_gle.'px;" href="javascript:if(document.getElementById(\'subform'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'\').innerHTML == \'\')ahah(\'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$layer['Layer_ID'].'&value_'.$layer['maintable'].'_oid='.$dataset[$layer['maintable'].'_oid'].'&embedded=true&subform_link=true&fromobject=subform'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'&targetobject='.$this->formvars['targetobject'].'&targetlayer_id='.$this->formvars['targetlayer_id'].'&targetattribute='.$this->formvars['targetattribute'].'&data='.$this->formvars['data'].'\', new Array(document.getElementById(\'subform'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'\')), \'\');clearsubforms('.$layer['Layer_ID'].');">'.implode(' ', $output).'</a><div id="subform'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'"></div></td>
							</tr>
	';
			}
			else{
				echo '<tr style="border: none">
								<td><a style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
								if($this->formvars['no_new_window'] != true){
									echo 	' target="_blank"';
								}
				echo ' href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$layer['Layer_ID'].'&value_'.$layer['maintable'].'_oid='.$dataset[$layer['maintable'].'_oid'].'&subform_link=true\')">'.implode(' ', $output).'</a></td>
							</tr>';
			}
						
		}
	?>			    
			</table>
<?	}
	if($anzObj > 1){ ?>
		<script type="text/javascript">
			document.getElementById('show_all_<? echo $this->formvars['targetobject'];?>').style.display = '';
		</script>

<? }
	} 
  else {
	# nix machen
  }
?>