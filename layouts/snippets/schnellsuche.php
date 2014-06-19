
<script type="text/javascript">
	
	function suche(){
		document.GUI.go.value = 'Layer-Suche_Suchen';
		overlay_submit(document.GUI, true);
	}

	
	function load_search_attributes(layer_id){
		if(layer_id != undefined){
			ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_quicksearch_attributes&layer_id="+layer_id, new Array(document.getElementById('search_div')), new Array('sethtml'));
		}
	}
	
	load_search_attributes();
	
	function update_require_attribute_(attributes, layer_id, value){
	// attributes ist eine Liste von zu aktualisierenden Attribut und value der ausgewaehlte Wert
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type=select-one", new Array(document.getElementById('value_'+attribute[i])), new Array('sethtml'));
	}
}
	
</script>

<?
	$layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, NULL, NULL, $quicksearch_layer_ids);
?>
	<table>
		<tr>
			<td>
				<select size="1" class="select" name="quicksearch_layer_id" onchange="load_search_attributes(this.value);">
					<option value="">-- Schnellsuche --</option>
					<?
					for($i = 0; $i < count($layerdaten['ID']); $i++){         
						echo '<option';
						if($layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
							echo ' selected';
						}
						echo ' value="'.$layerdaten['ID'][$i].'">'.$layerdaten['Bezeichnung'][$i].'</option>';
					}
				?>
				</select>
			</td>
			<td>	
				<div id="search_div"></div>
			</td>
		</tr>
	</table>
<? if($this->formvars['selected_layer_id'] != ''){ ?>
	<script type="text/javascript">
		load_search_attributes(<? echo $this->formvars['quicksearch_layer_id']; ?>);
	</script>
<? } ?>