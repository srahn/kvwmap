<?php
#############################
# Klasse Konvertierung #
#############################

class Regel extends PgObject {
	
	static $schema = 'xplankonverter';
	static $tableName = 'regeln';
	static $write_debug = false;

	function Regel($gui) {
		$gui->debug->show('Create new Object Regel', Regel::$write_debug);
		$this->PgObject($gui, Regel::$schema, Regel::$tableName);
		$this->layertypen = array(
			'Punkte',
			'Linien',
			'Flächen'
		);
	}

public static	function find_by_id($gui, $by, $id) {
		$regel = new Regel($gui);
		$regel->find_by($by, $id);
		$regel->konvertierung = $regel->get_konvertierung();
		return $regel;
	}

	/*
	* Führt die in der Regel definierten SQL-Statements aus um
	* Daten aus Shapefiles in die Tabellen der XPlan GML Datentabellen
	* zu schreiben. Dabei wird die im sql angegebene Id der Konvertierung
	* für jedes RP_Objekt gesetzt.
	* Wenn die Regel auch eine Bereich Id hat, wird diese in der
	* Tabelle rp_breich2rp_objekt zusammen mit den gml_id's der erzeugten
	* XPlan GML Objekte eingetragen.
	*/
	function convert($konvertierung) {
		$konvertierung_id = $konvertierung->get('id');
		$this->debug->show('Regel convert mit konvertierung_id: ' . $konvertierung_id, Regel::$write_debug);
		$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'sql_vorhanden');
		$validierung->konvertierung_id = $konvertierung_id;

		if ($validierung->sql_vorhanden($this->get('sql'), $this)) {

			# Prüft ob alle Objekte eine Geometrie haben
			$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'geometrie_vorhanden');
			$validierung->konvertierung_id = $konvertierung_id;
			$validierung->geometrie_vorhanden($this->get('sql'), $this->get('id'));

			# Prüft ob das sql ausführbar ist und legt die Objekte an wenn ja.
			$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'sql_ausfuehrbar');
			$validierung->konvertierung_id = $konvertierung_id;
			$alle_ausfuehrbar = $validierung->sql_ausfuehrbar($this, $konvertierung_id);

			if ($alle_ausfuehrbar and !empty($this->get('bereich_gml_id'))) {

					# Prüft ob die erzeugten Geometrien valide sind.
					$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'geometrie_isvalid');
					$validierung->konvertierung_id = $konvertierung_id;
					$all_geom_isvalid = $validierung->geometrie_isvalid($this, $konvertierung);
					
					if ($all_geom_isvalid) {
						# Prüft ob die erzeugten Geometrien im räumlichen Geltungsbereich des Planes liegen.
						$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'geom_within_plan');
						$validierung->konvertierung_id = $konvertierung_id;
						$validierung->geom_within_plan($this, $konvertierung);

						# Prüft ob die erzeugten Geometrien im Geltungsbereich der Bereiche liegen.
						$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'geom_within_bereich');
						$validierung->konvertierung_id = $konvertierung_id;
						$validierung->geom_within_bereich($this, $konvertierung);
				}
			}

		}
		else {
			$success = false;
		}
		return $success;
	}

	function get_convert_sql($konvertierung_id) {
		$this->debug->show('<br>Konvertiere sql vor Anpassung:<br>' . $this->get('sql'), Regel::$write_debug);
		$sql = $this->get('sql');
		$konvertierung = $this->get_konvertierung();
		$epsg = $konvertierung->get('output_epsg');

		# konvertierung_id hinzufügen
		$sql = substr_replace(
			$sql,
			'(konvertierung_id, ',
			strpos($sql, '('),
			strlen('(')
		);
		$sql = str_ireplace(
			'select',
			"select {$konvertierung_id} AS konvertierung_id,",
			$sql
		);
		$this->debug->show('sql nach konvertierung_id hinzufügen:<br>' . $sql, Regel::$write_debug);

		# transformation hinzufügen
		$sql = str_ireplace(
			'the_geom',
			"st_multi(st_transform(the_geom, {$epsg}))",
			$sql
		);
		$this->debug->show('sql nach transformation:<br>' . $sql, Regel::$write_debug);

		# nur nicht leere Geometrien übernehmen
		if (strpos(strtolower($sql), 'where') === false) {
			$sql .= ' WHERE the_geom IS NOT NULL';
		}
		else {
			$sql = str_ireplace(
				'where',
				"WHERE the_geom IS NOT NULL AND",
				$sql
			);
		}
		$this->debug->show('sql nach nicht leere Geometrien:<br>' . $sql, Regel::$write_debug);


		if ($this->get('bereich_gml_id') != '') {
			$sql = substr_replace(
				$sql,
				' (gehoertzubereich, ',
				strpos($sql, '('),
				strlen('(')
			);
			
			$sql = str_ireplace(
				'select',
				"select '" . $this->get_bereich_gml_ids() . "' AS gehoertzubereich,",
				$sql
			);
		}
		$this->debug->show('sql nach bereich:<br>' . $sql, Regel::$write_debug);

		# search_path und returning hinzufügen.
		$sql = "SET search_path=xplan_gml, xplan_shapes_{$konvertierung_id}, public;
			{$sql}
			RETURNING gml_id, gehoertzubereich
		";

		$this->debug->show('sql nach Anpassung:<br>' . $sql, Regel::$write_debug);
		return $sql;
	}

	function gml_layer_exists() {
		$this->debug->show("Layer mit Gruppe: {$this->konvertierung->get('gml_layer_group_id')} Name: {$this->get('class_name')} {$this->get('geometrietyp')} Datentyp: {$this->get_layertyp()}", $this->write_debug);
		$layers = Layer::find($this->gui, "
			`Gruppe` = {$this->konvertierung->get('gml_layer_group_id')} AND
			`Name` = '{$this->get('class_name')} {$this->get('geometrietyp')}' AND
			`Datentyp`= {$this->get_layertyp()}
		");
		if (count($layers) > 0) {
			$layer_exists = true;
			$this->gml_layer = $layers[0];
			return true;
		}
		else {
			return false;
		}
	}

	function get_layertyp() {
		$layertyp = 2; # default Polygon Layer
		if (strpos($this->get('geometrietyp'), 'Punkt') !== false) $layertyp = 0;
		if (strpos($this->get('geometrietyp'), 'Linie') !== false) $layertyp = 1;
		return $layertyp;
	}

	function get_bereich() {
		$bereich = new RP_Bereich($this->gui);
		return $bereich->find_by('gml_id', $this->get('bereich_gml_id'));
	}

	/*
	* Diese Funktion liefert die bereich_gml_id der Regel oder falls vorhanden mehrere aus dem Attribut bereiche
	*/
	function get_bereich_gml_ids() {
		if (empty($this->get('bereiche')) or $this->get('bereiche') == '{}')
			$gml_ids = '{' . $this->get('bereich_gml_id') . '}';
		else
			$gml_ids = $this->get('bereiche');

		return $gml_ids;
	}

	/*
	* Funktion fragt die zur Regel gehöhrende Konvertierung ab
	*/
	function get_konvertierung() {
		$konvertierung_id = $this->get('konvertierung_id');
		if (!empty($this->get('konvertierung_id'))) {
			#echo '<br>Regel gehört direkt zur Konvertierung: ' . $this->get('konvertierung_id');
			$konvertierung = Konvertierung::find_by_id($this->gui, 'id', $this->get('konvertierung_id'));
		}
		else {
			#echo '<br>Regel gehört über einen Bereich und Plan zur Konvertierung.';
			$sql = "
				SELECT
					coalesce(bp.konvertierung_id, rp.konvertierung_id) AS konvertierung_id
				FROM
					xplankonverter.regeln r LEFT JOIN
					xplan_gml.rp_bereich b ON r.bereich_gml_id = b.gml_id LEFT JOIN
					xplan_gml.rp_plan bp ON bp.gml_id::text = b.gehoertzuplan LEFT JOIN
					xplan_gml.rp_plan rp ON rp.konvertierung_id = r.konvertierung_id
				WHERE
					r.id = " . $this->get('id') . "
			";
			#echo '<br>SQL zum Abfragen der konvertierung_id der Regel: ' . $sql;
			$result = pg_query($this->database->dbConn, $sql);
			if (pg_num_rows($result) > 0) {
				$row = pg_fetch_assoc($result);
				$konvertierung = Konvertierung::find_by_id($this->gui, 'id', $row['konvertierung_id']);
			}
			else {
				$konvertierung = null;
			}
		}
		return $konvertierung;
	}

	function create_gml_layer() {
		if (!$this->gml_layer_exists()) {
			$layertyp = $this->get_layertyp();
			$this->debug->show('Erzeuge Layer ' . $this->get('class_name') . ' ' . $this->layertypen[$layertyp] . ' in Gruppe ' . $this->konvertierung->get('bezeichnung') . ' layertyp ' . $layertyp, $this->write_debug);
			
			$this->debug->show('<p>Suche nach Templatelayer ' . $this->get('class_name') . ' ' . $this->layertypen[$layertyp] . ' in Obergruppe ' . GML_LAYER_TEMPLATE_GROUP, Regel::$write_debug);
			$template_layer = Layer::find_by_obergruppe_und_name(
				$this->gui,
				GML_LAYER_TEMPLATE_GROUP,
				$this->get('class_name') . ' ' . $this->layertypen[$layertyp]
			);

			if (empty($template_layer)) {
				# ToDo: Kein Template Layer vorhanden, erzeuge einen Dummy
			}
			else {
				$this->debug->show('<p>Copiere Templatelayer in gml layer gruppe id: ' . $this->konvertierung->get('gml_layer_group_id'), Regel::$write_debug);
				$gml_layer = $template_layer->copy(
					array(
						'Gruppe' => $this->konvertierung->get('gml_layer_group_id'),
						'Data' => str_ireplace('using srid=25832', 'using srid=' . $this->konvertierung->get('output_epsg'), $template_layer->get('Data')),
						'epsg_code' => $this->konvertierung->get('output_epsg')
					)
				);

				$formvars_before = $this->gui->formvars;
			}

			$stellen = $this->gui->Stellenzuweisung(
				array($gml_layer->get($gml_layer->identifier)),
				array($this->gui->Stelle->id),
				'(konvertierung_id = ' . $this->konvertierung->get('id') .')'
			);

			# Assign layer_id to Konvertierung
			$this->set('layer_id', $gml_layer->get($gml_layer->identifier));
			$this->update();

			$this->gui->formvars = $formvars_before;
		}

	}

	function delete_gml_layer() {
		if (!empty($this->layer_id)) {
			# delete gml layer by konvertierung_id, name and geometrytype
			#echo 'Delete gml layer with layer_id: ' . $this->layer_id;

			# Lösche Layer, wenn von keiner anderen Regel mehr verwendet
			$this->gui->formvars['selected_layer_id'] = $layer_id;
			$this->gui->LayerLoeschen();

			# Lösche Datatypes, wenn von keinem anderen mehr verwendet

			# Lösche Gruppe, wenn kein anderer Layer mehr drin ist
		}
	}

	function destroy() {
		$this->debug->show('destroy regel ' . $this->get('name'), Regel::$write_debug);

		# Frage ab ob es in der Gruppe der gml Layer einen Layer von class_name gibt
		# der ansonsten von keiner anderen Regel verwendet wird und lösche diesen
		$sql = "
			SELECT
				class_name
			FROM
				(
					SELECT
						rk.*
					FROM
						xplankonverter.konvertierungen k join
						xplankonverter.regeln rk on k.id = rk.konvertierung_id
					WHERE
						k.id = {$this->konvertierung->get('id')}
					UNION
					SELECT
						rb.*
					FROM
						xplan_gml.rp_plan p JOIN
						xplan_gml.rp_bereich b ON p.gml_id::text = b.gehoertzuplan JOIN
						xplankonverter.regeln rb ON b.gml_id = rb.bereich_gml_id
					WHERE
						p.konvertierung_id = {$this->konvertierung->get('id')}
				) regeln
			WHERE
				lower(class_name) || ' ' || geometrietyp = (
				SELECT
					lower(class_name) || ' ' || geometrietyp
					from
						xplankonverter.regeln
					WHERE
						id = {$this->get('id')}
				) AND
				id != {$this->get('id')}
		";
		$this->debug->show('Gibt es weitere Regeln, die den selben Klassname verwenden?<br>' . $sql, Regel::$write_debug);
		$result = pg_query($this->database->dbConn, $sql);
		if (pg_num_rows($result) == 0) {
			$this->debug->show('nein, Prüfe ob der Layer existiert.', Regel::$write_debug);
			if ($this->gml_layer_exists()) {
				$this->debug->show("Layer {$this->gml_layer->get('Name')} existiert.", Regel::$write_debug);
				$this->debug->show("Lösche Layer mit ID: " . $this->gml_layer->get('Layer_ID'), Regel::$write_debug);

				$formvars_before = $this->gui->formvars;
				$this->gui->formvars['selected_layer_id'] = $this->gml_layer->get('Layer_ID');
				$this->gui->LayerLoeschen();
				$this->gui->formvars = $formvars_before;
			}
		}
		else {
			$this->debug->show('ja', Regel::$write_debug);
		}
		$this->delete();
	}

}

?>
