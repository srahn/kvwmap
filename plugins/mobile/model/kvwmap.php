<?php
	$GUI = $this;

	/**
	* Frage den Layer mit selected_layer_id und die dazugehörigen Attributdaten ab
	*/
	$this->mobile_get_layers = function() {
		# ToDo get more than only the layer with selected_layer_id
		$layers = array();
		if ($this->formvars['selected_layer_id'] != '') {
			# Abfragen der Layerdefinition
			$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
			if ($layerset) {
				# Abfragen der Privilegien der Attribute
				$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);

				# Abfragen der Attribute des Layers mit selected_layer_id
				$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
				$layerdb = $mapDB->getlayerdatabase(
					$this->formvars['selected_layer_id'],
					$this->Stelle->pgdbhost
				);
				$layerdb->setClientEncoding();
				$attributes = $mapDB->read_layer_attributes(
					$this->formvars['selected_layer_id'],
					$layerdb,
					$privileges['attributenames'],
					false,
					true
				);

				# Zuordnen der Privilegien zu den Attributen
				for ($j = 0; $j < count($attributes['name']); $j++) {
					$attributes['privileg'][$j] = $attributes['privileg'][$attributes['name'][$j]] = ($privileges == NULL ? 0 : $privileges[$attributes['name'][$j]]);
				}

				$layer = $this->mobile_reformat_layer($layerset[0]);
				$layer['attributes'] = $this->mobile_reformat_attributes($attributes);
				$layers[] = $layer;
				$result = array(
					"success" => true,
					"layers" => $layers
				);
			}
			else {
				$result = array(
					"success" => false,
					"err_msg" => "Es konnten keine Layerdaten zur angegebenen Layer-ID: ". $this->formvars['selected_layer_id'] . " abgefragt werden. Prüfen Sie ob die Layer-ID korrekt ist und ob Sie die Rechte für den Zugriff auf den Layer in der aktuellen Stelle haben."
				);
			}
		}
		else {
			$result = array(
				"success" => false,
				"err_msg" => "Es wurde kein Layer zur Abfrage der Daten angegeben. Geben Sie die ID des Layers im Parameter selected_layer_id an."
			);
		}
		return $result;
	};

	$this->mobile_update_data = function() {
		if ($this->formvars['selected_layer_id'] != '') {
			# ToDo update data

			$result = array(
				"success" => true
			);
		}
		else {
			$result = array(
				"success" => false,
				"err_msg" => "Es wurde kein Layer zur Aktualisierung angegeben. Geben Sie die ID des Layers im Parameter selected_layer_id an."
			);
		}
		return $result;
	};

	$this->mobile_reformat_layer = function($layerset) {
		$geometry_types = array(
			"Point", "Line", "Polygon"
		);
		$layer = array(
			"id" => $layerset['Layer_ID'],
			"title" => $layerset['Name'],
			"id_attribute" => "id",
			"title_attribute" => "title",
			"geometry_type" => $geometry_types[$layerset['Datentyp']]
		);
		return $layer;
	};

	$this->mobile_reformat_attributes = function($attr) {
		$attributes = array();
		foreach($attr['name'] AS $key => $value) {
			$attributes[] = array(
				"index" => $attr['indizes'][$value],
				"name" => $value,
				"real_name" => $attr['real_name'][$value],
				"alias" => $attr['alias'][$value],
				"tooltip" => $attr['tooltip'][$key],
				"type" => $attr['type'][$key],
				"nullable" => $attr['nullable'][$key],
				"form_element_type" => $attr['form_element_type'][$key],
				"options" => $attr['options'][$key],
				"privilege" => $attr['privileg'][$key]
			);
		}
		return $attributes;
	};

?>