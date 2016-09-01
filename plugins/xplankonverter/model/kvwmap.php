<?
	$GUI = $this;

	/**
	* Löscht alle GML-Layer, die zu der Konvertierung gehören, dessen id in dataset übergeben wird.
	*/
	$this->trigger_functions['handle_gml_layer'] = function($fired, $event, $params, $dataset) use ($GUI) {
		switch(true) {
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$this->create_gml_layer($dataset['id']);
			} break;
			case ($fired == 'BEFORE' AND $event == 'DELETE') : {
				$this->delete_gml_layer($dataset['layer_id']);
			}
		}
	};

	$this->create_gml_layer = function($regel_id) use ($GUI) {
		# create gml layer with konvertierung_id, name and geometrytype if not exists
		$regel = Regel::find_by_id($this, 614);
		$konvertierung = Konvertierung::find_by_id($this, $regel->get(konvertierung_id));

		# Erzeuge Layer mit Attributen und Datentypen
		$layertyp = 2; # default Polygon Layer
		if (strpos($regel->get('geometrietyp'), 'Punkt') !== false) $layertyp = 0;
		if (strpos($regel->get('geometrietyp'), 'Linie') !== false) $layertyp = 1;

		$sql = $this->generate_layer(
			'xplan_gml',
			array('name' => strtolower($regel->get('class_name'))),
			$konvertierung->get('output_epsg'),
			'position',
			$regel->get('geometrietyp'),
			$layertyp
		);

		echo $sql;
		# sql Ausführen
		# id vom Layer abfragen
		$layer_id = 1;

		# Assign layer_id to Konvertierung
		$konvertierung->set('gml_layer_id', $layer_id);
		$konvertierung->update();

	};

	$this->delete_gml_layer = function($layer_id) use ($GUI) {
		# delete gml layer by konvertierung_id, name and geometrytype
		echo 'Delete gml layer with layer_id: ' . $layer_id;

		# Lösche Layer, wenn von keiner anderen Regel mehr verwendet
		$this->formvars['selected_layer_id'] = $layer_id;
		$this->LayerLoeschen();

		# Lösche Datatypes, wenn von keinem anderen mehr verwendet

		# Lösche Gruppe, wenn kein anderer Layer mehr drin ist


	};
?>