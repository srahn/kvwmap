<?php
	$params = $this->user->rolle->get_layer_params($this->Stelle->selectable_layer_params);?>
	<script language="javascript" type="text/javascript">
		function updateLayerParams() {
			var data = 'go=setLayerParams<?php
			foreach($params AS $param) {
				echo '&layer_parameter_' . $param['key'] . "=' + document.getElementById('layer_parameter_" . $param['key'] . "').value + '";
			} ?>';
			ahah('index.php', data, [''], ['execute_function']);
		}
		function onLayerParameterChanged(parameter) {
			document.getElementById("update_layer_parameter_button").style.color = "red";
		}
		function onLayerParamsUpdated(status) {
			document.getElementById("update_layer_parameter_button").style.color = "gray";
		}
	</script><?php
foreach($params AS $param) {
	$options = array();
	$options_result = $this->pgdatabase->execSQL($param['options_sql'], 4, 1);	
	if ($options_result[0]) {
		echo '<br>Fehler bei der Abfrage der Optionen des Layerparameters ' . $param['key'] . ' mit SQL: ' . $param['options_sql'];
	}
	else {
		while ($option = pg_fetch_assoc($options_result[1])) {
			$option['selected'] = ($option['value'] == rolle::$layer_params[$param['key']]) ? ' selected' : '';
			$options[] = $option;
		}
	}
	echo $param['alias']; ?>
	<select id="layer_parameter_<?php echo $param['key']; ?>" name="layer_parameter_<?php echo $param['key']; ?>" onchange="onLayerParameterChanged(this);"><?php
		foreach($options AS $option) { ?>
			<option value="<?php echo $option['value']; ?>"<?php echo $option['selected']; ?>><?php echo $option['output']; ?></option><?php
		} ?>
	</select>&nbsp;<?php
}
?>&nbsp;
<i id="update_layer_parameter_button" class="fa fa-floppy-o" aria-hidden="true" onclick="updateLayerParams();"></i>&nbsp;
<i class="fa fa-times" aria-hidden="true" onclick="toggleLayerParamsBar();"></i>&nbsp;