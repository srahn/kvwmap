<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script type="text/javascript">
<!--

function create_chart(layer_id){
	go = 'true';
	if(document.getElementsByName('charttype_'+layer_id)[0].value == 'mirrorbar' && ((document.getElementsByName('chartsplit_'+layer_id)[0].value == '') || (document.getElementsByName('chartvalue_'+layer_id)[0].value == ''))){
		return;
	}
	checkbox_name_obj = document.getElementsByName('checkbox_names_'+layer_id);
	checkbox_name_string = checkbox_name_obj[0].value;
	checkbox_names = checkbox_name_string.split('|');

	if(go == 'false'){
		alert('Es wurde kein Datensatz ausgewählt.');
	}
	else{
		document.GUI.target = 'chartframe';
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.width.value = 700;
		document.GUI.go_backup.value = document.GUI.go.value;
		document.GUI.go.value = 'generisches_sachdaten_diagramm';
		document.GUI.submit();
		document.getElementById('chartframe').style.display = '';
		document.getElementById('chartframe').contentWindow.document.body.innerHTML = '<table width="100%"><tr><td height="550" width="100%" align="center"><blink>- Bitte warten -</blink></td></tr></table>';
		document.GUI.target = "";
	}
}

function change_orderby(attribute, layer_id){
	if(document.GUI.go_backup.value != ''){
		document.GUI.go.value = document.GUI.go_backup.value;
	}
	if(document.getElementById('orderby'+layer_id).value == attribute){
		document.getElementById('orderby'+layer_id).value = attribute+' DESC';
	}
	else{
		document.getElementById('orderby'+layer_id).value = attribute;
	}
	document.GUI.submit();
}


//-->
</script>
<?php
  $layer = $this->qlayerset[$i];
  $layerId = $layer['Layer_ID'];
  $resultSet = $layer['shape'];
  $attributes = $layer['attributes'];
?>
<h2><? echo $layer['Name'] ?></h2>
<?
  $anzObj = count($resultSet);
?>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table border="1" style="background-color: white; border-collapse:collapse;">
				<tr>
<?
	for($j = 0; $j < count($attributes['name']); $j++){
		if($attributes['alias'][$j] == ''){
			$attributes['alias'][$j] = $attributes['name'][$j];
		}
		if($attributes['name'][$j] != 'nr' AND $attributes['type'][$j] != 'geometry'){
			echo '<td><a style="font-size: '.$this->user->rolle->fontsize_gle.'px" title="'.$attributes['tooltip'][$j].'" style="color: #6c6c6c;" href="javascript:change_orderby(\''.$attributes['name'][$j].'\', '.$layerId.');"><b>'.$attributes['alias'][$j].'</b></a></td>';
		}
	}
?></tr>
<?
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_names .= 'check;'.$attributes['table_alias_name'][$attributes['name'][0]].';'.$attributes['table_name'][$attributes['name'][0]].';'.$resultSet[$k][$attributes['table_name'][$attributes['name'][0]].'_oid'].'|';
		?>
  		<input id="<? echo $layerId.'_'.$k; ?>" type="hidden" value="on" name="check;<? echo $attributes['table_alias_name'][$attributes['name'][0]].';'.$attributes['table_name'][$attributes['name'][0]].';'.$resultSet[$k][$attributes['table_name'][$attributes['name'][0]].'_oid']; ?>">
			<tr>
<?		for($j = 0; $j < count($attributes['name']); $j++){
				if($attributes['invisible'][$attributes['name'][$j]] != 'true' AND $resultSet[$k]['nr'] >= 0){		# nur die Interval-Gruppen darstellen){
					if($attributes['name'][$j] != 'nr' AND $attributes['type'][$j] != 'geometry'){
						echo '<td><input title="'.$attributes['alias'][$j].'" ';
						if($attributes['length'][$j]){
							echo ' maxlength="'.$attributes['length'][$j].'"';
						}
						if($attributes['privileg'][$j] == '0' OR $lock[$k]){
							echo ' readonly style="font-size: '.$this->user->rolle->fontsize_gle.'px;background-color:#ffffff; border: none"';
						}
						else{
							echo ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
						}
						if($attributes['name'][$j] == 'gruppe')echo ' size="5"';
						if($attributes['name'][$j] == 'geschlecht')echo ' size="20"';
						else echo ' size="7"';
						echo ' type="text" name="'.$layerId.';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$resultSet[$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" id="'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($resultSet[$k][$attributes['name'][$j]]).'">';
					}
		  	}
  			echo '
						</td>
				';
				if($attributes['privileg'][$j] >= '0' AND !($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld')){
					$this->form_field_names .= $layerId.';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$resultSet[$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'|';
	  		}
			}


 ?>			</tr>
<? } ?>
			</table>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"align="left">
			<table border="0">
				<tr>
					<td></td>
					<td height="23" colspan="3">
						<b>Diagramm erzeugen für das Jahr:&nbsp;</b>
						<select style="width:133px" name="chartvalue_<?php echo $layerId; ?>" onchange="create_chart(<?php echo $layerId; ?>);">
							<option value="">--- Bitte Wählen ---</option>
							<?
							for($j = 0; $j < count($attributes['name']); $j++){
								if($attributes['name'][$j] != $attributes['the_geom'] AND $attributes['name'][$j] != 'gruppe' AND $attributes['name'][$j] != 'geschlecht' AND $attributes['name'][$j] != 'nr'){
									echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr id="charts_<?php echo $layerId; ?>" style="display:none">
					<td></td>
					<td>
						<table>
							<tr>
								<td colspan="2">
									&nbsp;&nbsp;<select name="charttype_<?php echo $layerId; ?>" onchange="change_charttype(<?php echo $layerId; ?>);">
										<option value="bar">Balkendiagramm</option>
										<option value="mirrorbar" selected="true">doppeltes Balkendiagramm</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Beschriftung:
								</td>
								<td>
									<select style="width:133px" id="" name="chartlabel_<?php echo $layerId; ?>" >
									<option value="gruppe" selected="true">Alter</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;
								</td>
								<td>
								</td>
							</tr>
							<tr id="split_<?php echo $layerId; ?>" style="display:none">
								<td>
									&nbsp;&nbsp;Trenn-Attribut:
								</td>
								<td>
									<select style="width:133px" name="chartsplit_<?php echo $layerId; ?>" onchange="create_chart(<?php echo $layerId; ?>);">
										<option value="geschlecht" selected="true">Geschlecht</option>
									</select>
								</td>
							</tr>
							<tr id="comparison_<?php echo $layerId; ?>" style="display:none">
								<td>
									&nbsp;&nbsp;Vergleichs-Attribut:
								</td>
								<td>
									<select style="width:133px" name="chartcomparison_<?php echo $layerId; ?>" onchange="create_chart(<?php echo $layerId; ?>);">
										<option value="einwohner2030p" selected="true">2030</option>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3"><iframe style="display:none" id="chartframe" width="745" height="1000" name="chartframe"></iframe></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<input type="hidden" name="checkbox_names_<? echo $layerId; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $layerId; ?>" id="orderby<? echo $layerId; ?>" value="<? echo $this->formvars['orderby'.$layerId]; ?>">

