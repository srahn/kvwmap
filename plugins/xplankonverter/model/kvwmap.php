<?php
	$GUI = $this;

	/**
	* Trigger für Konvertierungen
	*/
	$this->trigger_functions['handle_konvertierung'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		#echo '<br>Handle Konvertierungen trigger mit fired: ' . $fired . ' event: ' . $event . ' layer: ' . print_r($layer, true) . ' oid: ' . $oid;
		$executed = true;
		$success = true;

		switch(true) {
			# Erzeuge Layergruppe und Verzeichnisse nach dem Erzeugen einer Konvertierung
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				#echo 'AFTER INSERT';
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				# layer_group wird erstellt, wenn diese noch nicht existiert (wird derzeit nicht mehr gelöscht)
				$layer_group_id = $this->get(strtolower($layer_type) . '_layer_group_id');
				if (empty($layer_group_id)) {
					$konvertierung->create_layer_group('GML');
					$konvertierung->create_directories();
				}
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				#echo 'INSTEAD DELETE';
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->destroy();
			} break;

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};

	/**
	* Trigger für Shapefiles
	*/
	$this->trigger_functions['handle_shapes'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;

		switch(true) {
			# Passe die SRID der Spalte the_geom an den epsg_code des Shapefiles an.
			case ($fired == 'AFTER' AND $event == 'UPDATE') : {
				$shapefile = ShapeFile::find_by_id($this, 'oid', $oid);
				if ($shapefile->geometry_column_srid() != $shapefile->get(epsg_code))
					$shapefile->update_geometry_srid();
			} break;

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};


	/**
	* Trigger für XP_Plan Objekte
	*/
	$this->trigger_functions['handle_xp_plan'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		#echo '<br>Trigger Funktion handle_xp_plan ' . $fired . ' ' . $event . ' aufgerufen.';
		$executed = true;
		$success = true;

		switch ($layer['Layer_ID']) {
			case XPLANKONVERTER_BP_PLAENE_LAYER_ID : {
				$planart = 'BP-Plan';
				$bereichtable = 'bp_bereich';
			} break;
			case XPLANKONVERTER_FP_PLAENE_LAYER_ID : {
				$planart = 'FP-Plan';
				$bereichtable = 'fp_bereich';
			} break;
			case XPLANKONVERTER_SO_PLAENE_LAYER_ID : {
				$planart = 'SO-Plan';
				$bereichtable = 'so_bereich';
			} break;
			case XPLANKONVERTER_RP_PLAENE_LAYER_ID : {
				$planart = 'RP-Plan';
				$bereichtable = 'rp_bereich';
			} break;
		}

		switch(true) {

			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				#echo '<br>Führe ' . $fired . ' ' . $event . ' in handle_rp_plan Funktion aus.';
				$xp_plan = XP_Plan::find_by_id($this, 'oid', $oid, $planart);

				# Create Konvertierung and get konvertierung_id
				$konvertierung = new Konvertierung($this);
				$konvertierung_id = $konvertierung->create(
					$xp_plan->get_anzeige_name(),
					$this->Stelle->epsg_code,
					$this->user->rolle->epsg_code,
					$planart,
					$this->Stelle->id,
					$this->user->id
				);

				$xp_plan->set('konvertierung_id', $konvertierung_id);
				$xp_plan->update();

				$konvertierung = $konvertierung->find_by_id($this, 'id', $konvertierung_id);
				$this->debug->show('Trigger ' . $fired . ' ' . $event . ' konvertierung planart: ' . $konvertierung->get('planart') . ' plan planart: ' . $konvertierung->plan->get('planart'), false);
				$konvertierung->set_status();

				if(!empty($this->formvars['layer_schemaname'])) {
					# Creates Bereiche for each Plan loaded with GMLAS
					$gml_extractor = new Gml_extractor($this->pgdatabase, 'placeholder', 'xplan_gmlas_' . $this->user->id);
					$gml_extractor->insert_into_bereich($bereichtable, $konvertierung_id, $this->user->id);
					# Inserts regeln for each possible class loaded with GMLAS
					$gml_extractor->insert_all_regeln_into_db($konvertierung_id, $this->Stelle->id);
				}
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				#echo '<br>Führe ' . $fired . ' ' . $event . ' in handle_xp_plan Funktion aus.';
				# Delete Konvertierung and all pending objects instead of deleting only plan
				$konvertierung_id = $old_dataset['konvertierung_id'];
				#echo '<p>Lösche Konvertierung mit Id: ' . $konvertierung_id;
				$konvertierung = Konvertierung::find_by_id($this, 'id', $konvertierung_id);
				$konvertierung->destroy();
			} break;

			default : {
				#echo '<br>Default Case in ' . $fired . ' ' . $event . ' Triggerfunktion, tuhe nichts!';
				$executed = false;
			}
		}
		#echo '<br>Trigger Funktion ' . $fired . ' ' . $event . ' ausgeführt: ' . ($executed ? 'Ja' : 'Nein');
		return array('executed' => $executed, 'success' => $success);
	};

	/**
	* Trigger für Regeln
	* @params $layer Array mit Angben des Layers aus der MySQL-Datenbank
	*/
	$this->trigger_functions['handle_regel'] = function($fired, $event, $layer, $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;

		switch(true) {

			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$this->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus mit oid: ' . $oid, false);
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->create_gml_layer();
				$regel->konvertierung->set_status();
			} break;

			case ($fired == 'AFTER' AND $event == 'UPDATE') : {
				$this->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus mit oid: ' . $oid, false);
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->delete_gml_layer();
				$regel->create_gml_layer();
				$regel->konvertierung->set_status();
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				$this->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus.', false);
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->destroy();
				$regel->konvertierung->set_status();
			} break;

			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				$this->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus.', false);
				if (empty($old_dataset['konvertierung_id'])) {
					# hole konvertierung_id ueber plan und bereich_gml_id
					$bereich = RP_Bereich::find_by_id($this, 'gml_id', $old_dataset['bereich_gml_id']);
					$plan = XP_Plan::find_by_id($this, 'gml_id', $bereich->get('gehoertzuplan'));
					$konvertierung_id = $plan->get('konvertierung_id');
				}
				else {
					$konvertierung_id = $old_dataset['konvertierung_id'];
				}

				$konvertierung = Konvertierung::find_by_id($this, 'id', $konvertierung_id);
				#echo '<br>Konvertierung mit id: ' . $konvertierung->get('id') . ' gefunden.';
				$konvertierung->set_status();
			}

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};

	$this->xplankonverter_is_case_forbidden = function() {
		$forbidden = false;
		if ($this->formvars['konvertierung_id'] == '') {
			#echo 'Diese Link kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
			$forbidden = true;
		}
		else {
			$this->konvertierung = Konvertierung::find_by_id($this, 'id', $this->formvars['konvertierung_id']);
			if (!isInStelleAllowed($this->Stelle, $this->konvertierung->get('stelle_id'))) {
				$forbidden = true;
			}
		}
		return $forbidden;
	}

?>