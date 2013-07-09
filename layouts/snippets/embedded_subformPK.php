<? 
	$doit = false;
  $anzObj = count($this->qlayerset[$i]['shape']);
  if ($anzObj > 0) {
  	$this->found = 'true';
  	$doit = true;
  }
  if($doit == true){
?>
		<table border="0" cellspacing="0" cellpadding="2">
<?	for ($k=0;$k<$anzObj;$k++) {
			for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
				if($this->formvars['preview_attribute'] == $this->qlayerset[$i]['attributes']['name'][$j]){
					switch ($this->qlayerset[$i]['attributes']['form_element_type'][$j]){
						case 'Auswahlfeld' : {
							if(is_array($this->qlayerset[$i]['attributes']['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
								for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j][$k]); $e++){
									if($this->qlayerset[$i]['attributes']['enum_value'][$j][$k][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]){
										$output = $this->qlayerset[$i]['attributes']['enum_output'][$j][$k][$e];
										break;
									}
								}
							}
							else{
								for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
									if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]){
										$output = $this->qlayerset[$i]['attributes']['enum_output'][$j][$e];
										break;
									}
								}
							} 
						}break;
						
						case 'Dokument' : {
							if ($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]!='') {
								if($this->qlayerset[$i]['attributes']['options'][$j] != ''){		# bei Layern die auf andere Server zugreifen, wird die URL des anderen Servers verwendet
									$url = $this->qlayerset[$i]['attributes']['options'][$j];
								}
								else{
									$url = URL.APPLVERSION.'index.php?go=sendeDokument&dokument='; 
								}
								$type = strtolower(array_pop(explode('.', $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]])));
  							if($type == 'jpg' OR $type == 'png' OR $type == 'gif' ){
									echo '<tr><td><iframe height="160" frameborder="0" marginheight="3" marginwidth="3" style="border:none" src="'.$url.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'&go_plus=mit_vorschau"></iframe>';
  							}else{
  								echo '<tr><td><iframe height="80" frameborder="0" marginheight="3" marginwidth="3" style="border:none" src="'.$url.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'&go_plus=mit_vorschau"></iframe>';
  							}
								echo '<input type="hidden" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].'_alt'.';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'"></td>';
							}
							$output = '<img border="0" title="zum Datensatz" src="'.GRAPHICSPATH.'zum_datensatz.gif">';
						}break;
						
						case 'Link': {
							$output = basename($this->qlayerset[$i]['shape'][$k][$this->formvars['preview_attribute']]);								
						} break;
						
						default : {
							$output = $this->qlayerset[$i]['shape'][$k][$this->formvars['preview_attribute']];						
						}
					}
				}
			}
			if($this->formvars['embedded'] == 'true'){
				echo '
								<td><a href="javascript:if(document.getElementById(\'subform'.$this->qlayerset[$i]['Layer_ID'].$this->formvars['count'].'_'.$k.'\').innerHTML == \'\')ahah(\''.URL.APPLVERSION.'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$this->qlayerset[$i]['Layer_ID'].'&value_'.$this->qlayerset[$i]['maintable'].'_oid='.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['maintable'].'_oid'].'&embedded=true&fromobject=subform'.$this->qlayerset[$i]['Layer_ID'].$this->formvars['count'].'_'.$k.'&targetobject='.$this->formvars['targetobject'].'&targetlayer_id='.$this->formvars['targetlayer_id'].'&targetattribute='.$this->formvars['targetattribute'].'&data='.$this->formvars['data'].'\', new Array(document.getElementById(\'subform'.$this->qlayerset[$i]['Layer_ID'].$this->formvars['count'].'_'.$k.'\')), \'\');clearsubforms();">'.$output.'</a></td>
							</tr>
							<tr>
								<td><div id="subform'.$this->qlayerset[$i]['Layer_ID'].$this->formvars['count'].'_'.$k.'"></div></td>
							</tr>';
			}
			else{
				echo '
								<td><a ';
								if($this->formvars['no_new_window'] != true){
									echo 	' target="_blank"';
								}
				echo ' href="index.php?go=Layer-Suche_Suchen&selected_layer_id='.$this->qlayerset[$i]['Layer_ID'].'&value_'.$this->qlayerset[$i]['maintable'].'_oid='.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['maintable'].'_oid'].'">'.$output.'</a></td>
							</tr>';
			}
						
		}
?>			    
		</table>
<?
	} 
  else {
	# nix machen
  }
?>