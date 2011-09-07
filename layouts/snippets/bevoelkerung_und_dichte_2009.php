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
		document.getElementById('chartframe').contentWindow.document.body.innerHTML = '<table width="100%"><tr><td height="350" width="100%" align="center"><blink>- Bitte warten -</blink></td></tr></table>';
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

<h2><? echo $this->qlayerset[$i]['Name'] ?></h2>
<?
  $anzObj = count($this->qlayerset[$i]['shape']);
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
	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
		if($this->qlayerset[$i]['attributes']['alias'][$j] == ''){
			$this->qlayerset[$i]['attributes']['alias'][$j] = $this->qlayerset[$i]['attributes']['name'][$j];
		}
		if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
			echo '<td><a style="font-size: '.$this->user->rolle->fontsize_gle.'px" title="'.$this->qlayerset[$i]['attributes']['tooltip'][$j].'" style="color: #6c6c6c;" href="javascript:change_orderby(\''.$this->qlayerset[$i]['attributes']['name'][$j].'\', '.$this->qlayerset[$i]['Layer_ID'].');"><b>'.$this->qlayerset[$i]['attributes']['alias'][$j].'</b></a></td>';
		}
	}
?></tr>
<?
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_names .= 'check;'.$this->qlayerset[$i]['attributes']['table_alias_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].'_oid'].'|';
?>
			<tr>
<?		for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
				if($this->qlayerset[$i]['attributes']['invisible'][$this->qlayerset[$i]['attributes']['name'][$j]] != 'true'){
					if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
						echo '<td><input title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" ';
						if($this->qlayerset[$i]['attributes']['length'][$j]){
							echo ' maxlength="'.$this->qlayerset[$i]['attributes']['length'][$j].'"';
						}
						if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
							echo ' readonly style="font-size: '.$this->user->rolle->fontsize_gle.'px;background-color:#ffffff; border: none"';
						}
						else{
							echo ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
						}
						#if($j > 0)echo ' size="7"';else echo ' size="20"';
						if($this->qlayerset[$i]['attributes']['length'][$j]){
							echo ' size="'.$this->qlayerset[$i]['attributes']['size'][$j].'"';
						}
						echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">';
					}
		  	}		  	
  			echo '
						</td>
				';
				?>
		  	<input id="<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$k; ?>" type="hidden" value="on" name="check;<? echo $this->qlayerset[$i]['attributes']['table_alias_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].'_oid']; ?>">
		  	<?
				if($this->qlayerset[$i]['attributes']['privileg'][$j] >= '0' AND !($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' AND $this->qlayerset[$i]['attributes']['form_element_type'][$j] == 'Auswahlfeld')){
					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'|';
	  		} 
			}
			
				
 ?>			</tr>
<? } ?>
			</table>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
<input type="hidden" name="checkbox_names_<? echo $this->qlayerset[$i]['Layer_ID']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $this->qlayerset[$i]['Layer_ID']; ?>" id="orderby<? echo $this->qlayerset[$i]['Layer_ID']; ?>" value="<? echo $this->formvars['orderby'.$this->qlayerset[$i]['Layer_ID']]; ?>">

