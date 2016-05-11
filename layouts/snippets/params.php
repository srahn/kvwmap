<script type="text/javascript">
	function update_layer_params() {
		document.GUI.go.value='setLayerParams';
		document.GUI.submit();
	}
</script>
<?php
$params = $this->user->rolle->get_layer_params($this->Stelle->selectable_layer_params);
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
	<select name="layer_parameter_<?php echo $param['key']; ?>" onchange="update_layer_params();"><?php
		foreach($options AS $option) { ?>
			<option value="<?php echo $option['value']; ?>"<?php echo $option['selected']; ?>><?php echo $option['output']; ?></option><?php
		} ?>
	</select>&nbsp;<?php
}
?>