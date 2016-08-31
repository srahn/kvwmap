<?
	$GUI = $this;

	/**
	* Löscht alle GML-Layer, die zu der Konvertierung gehören, dessen id in dataset übergeben wird.
	*/
	$this->trigger_functions['delete_gml_layer'] = function($params, $dataset) use ($GUI) {
		# delete gml layer by konvertierung_id, name and geometrytype
		echo 'Delete gml layer with konvertierung_id: ' . $dataset['konvertierung_id'] . ' featuretype: ' . $dataset['class_name'] . ' und geometrietyp: ' . $dataset['geometrytyp'];
	};

	$this->trigger_functions['create_gml_layer'] = function($params, $dataset) use ($GUI) {
		# create gml layer with konvertierung_id, name and geometrytype if not exists
		echo 'Create gml layer for konvertierung_id: ' . $dataset['konvertierung_id'] . ' featuretype: ' . $dataset['class_name'] . ' und geometrietyp: ' . $dataset['geometrytyp'];
	};

?>