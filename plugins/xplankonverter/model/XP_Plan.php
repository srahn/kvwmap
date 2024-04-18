<?php
#############################
# Klasse XP_Plan #
#############################

class XP_Plan extends PgObject {

	static $schema = 'xplan_gml';

	function __construct($gui, $planart, $select = '*') {
		$this->planart = $planart;
		$this->planartAbk = strtolower(substr($planart, 0, 2));
		$this->planartShort = strtolower(substr($planart, 0, 1));
		$this->tableName = $this->planartAbk . '_plan';
		$this->shortName = $this->planartShort . 'plan';
		$this->shortNamePlural = $this->planartShort . 'plaene';
		$this->umlName = strtoupper($this->planartAbk) . '_Plan';
		$this->bereichTableName = $this->planartAbk . '_bereich';
		$this->bereichUmlName = strtoupper($this->planartAbk) . '_Bereich';
		parent::__construct($gui, XP_Plan::$schema, $this->tableName);
		$this->bereiche = array();
		$this->select = $select;
		$this->identifier = 'gml_id';
		$this->identifier_type = 'text';
		$this->identifiers = array(
			array(
				'column' => 'gml_id',
				'type' => 'character varying'
			)
		);
		$this->debug->show('Objekt XP_Plan created with planart: ' . $this->planart . ' tableName: ' . $this->tableName, false);
		$this->geom_column = 'raeumlichergeltungsbereich';
	}

	public static	function find_by_id($gui, $by, $id, $planart) {
		$xp_plan = new XP_Plan($gui, $planart);
		$xp_plan->find_by($by, $id);
		$xp_plan->get_extent();
		return $xp_plan;
	}

	public static	function find_where_by_planart($gui, $planart, $where, $order = '', $select = '*', $limit = '') {
		$plan = new XP_Plan($gui, $planart);
		$plaene = $plan->find_where($where, $order, $select, $limit);
		return $plaene;
	}

	/**
	 * Die Funktion filtert dokumente aus dem Attribut externereferenz die laut Angaben zur Auslegung
	 * nicht veröffentlicht werden sollen raus. Rausgefiltert wird wenn das Attribut nurzurauslegung der externenreferenz true ist und der Zeitraum nicht zu den Planattributen auslegungsstartdatum und auslegungsenddatum passen.
	 * Bei denen, die veröffentlicht werden sollen, wird das Attribut nurzurauslegung abgezogen.
	 * ToDo: so wie es jetzt ist, ist externereferenzen schon mit der Funktion parseCompositeDataType zerlegt worden.
	 * Besser ist es wohl wenn externereferenzen so übergeben wird wie von Postgres geliefert als spezexternereferenzauslegung und das ganze dann
	 * umgewandelt wird in spezexternereferenz
	 * ToDo: Möglicherweise ist es aber auch insgesammt besser wenn wie eine Triggerfunktion bauen, die
	 * zusätzlich zu externereferenz ein Attribut externereferenzauslegung einführen. In kvwmap wird letzteres verwaltet
	 * und die Triggerfunktion belegt das dazugehörige externereferenz ohne nurzurauslegung
	 * Bei der Abfrage wird externereferenz dann entsprechend externereferenzauslegung gefiltert.
	 * Dann muss man den Typ von externereferenz nicht anpassen. externereferenzauslegung wird
	 * beim Export ignoriert weil es in gml nicht vorkommt und externereferenz wird so exportiert
	 * wie nach der Filterung reduziert. externereferenzauslegung ist dann also zur Bearbeitung und
	 * externereferenz für den GML-Export. Die find Funktion von XP_Plan filtert dann immer externereferenz
	 * entsprechend er Angaben in externereferenzauslegung und den Planattributen auslegungsstart und enddatum.
	 */
	function filter_nurzurauslegung($gml_builder) {
		$start = explode(',', str_replace(['{', '}'], '', $this->get('auslegungsstartdatum')));
		$ende = explode(',', str_replace(['{', '}'], '', $this->get('auslegungsenddatum')));
		$now = date('d.m.Y');
		$externereferenzen_mit_nurzurauslegung_json = json_decode($this->get_json('externereferenz'));
		// Die, die nicht zur Auslegung sind rausfiltern
		$externereferenzen_mit_nurzurauslegung_json_gefiltert = array_filter(
			$externereferenzen_mit_nurzurauslegung_json,
			function ($externereferenz_mit_nurzurauslegung_json) use ($gml_builder, $start, $ende, $now) {
				$keep_referenz = true;
				if ($externereferenz_mit_nurzurauslegung_json->nurzurauslegung) {
					$im_zeitraum = in_date_range($start, $ende, $now);
					if (!$im_zeitraum) {
						$keep_referenz = false;
					}
				}
				return $keep_referenz;
			}
		);
		// Entferne die Eigenschaft nurzurauslegung aus externen Referenzen
		$externereferenzen_json_gefiltert = array_map(
			function ($externereferenz_mit_nurzurauslegung_json_gefiltert) {
				unset($externereferenz_mit_nurzurauslegung_json_gefiltert->nurzurauslegung);
				return $externereferenz_mit_nurzurauslegung_json_gefiltert;
			},
			$externereferenzen_mit_nurzurauslegung_json_gefiltert
		);
		$externereferenzen_gefiltert = $this->gui->processJSON($externereferenzen_json_gefiltert);
		$this->set('externereferenz', $externereferenzen_gefiltert);
	}

	function get_anzeige_name() {
		return $this->get_first_planart_name() . ' ' . $this->get_first_gemeinde_name() . ' ' . $this->get('name') . ' Nr. ' . $this->get('nummer');
	}

	/**
	 * Function extract regionalschlüssel from first gemeinde if exists.
	 * @return 12 stelliger Regionalschlüssel or null if not exists
	 */
	function get_regionalschluessel() {
		$schl = $this->get('gemeinde');
		if (!empty($schl)) {
			$parts = explode(',', $schl);
			if (count($parts) > 1 AND !empty($parts[1])) {
				return $parts[1];
			}
		}
		return null;
	}

	/**
	 * Return names of layer that have content from the plan
	 * @param array $xplan_layers Array mit GUI->xplankonverter_get_xplan_layers() abgefragt wurden
	 */
	function get_layers_with_content($xplan_layers, $konvertierung_id = '') {
		$layers_with_content = array();
		foreach ($xplan_layers AS $xplan_layer) {
			if ($xplan_layer['geom_column'] == '') {
				$msg = 'In der Layerdefinition des Layers ' . $xplan_layer['Name'] . ' ist keine geom_column angegeben.';
				$ret[0] = 1;
				$ret[1] = $msg;
				$ret['success'] = false;
				$ret['msg'] = $msg;
				return $ret;
			}
			$sql = "
				SELECT
					'" . $xplan_layer['Name'] . "',
					count(CASE WHEN LOWER(ST_GeometryType(" . $xplan_layer['geom_column'] . ")) LIKE '%point%' THEN 1 ELSE 0 END) AS num_points,
					count(CASE WHEN LOWER(ST_GeometryType(" . $xplan_layer['geom_column'] . ")) LIKE '%linestring%' THEN 1 ELSE 0 END) AS num_lines,
					count(CASE WHEN LOWER(ST_GeometryType(" . $xplan_layer['geom_column'] . ")) LIKE '%polygon%' THEN 1 ELSE 0 END) AS num_polygons
				FROM
					" . $xplan_layer['schema'] . '.' . $xplan_layer['maintable'] . "
				WHERE
					" . ($konvertierung_id == '' ? "true" : "konvertierung_id = " . $this->get('konvertierung_id')) . "
			";

			#echo '<p>' . $sql;
			set_error_handler(function($e) {
				return true;
			});
			$ret = $this->database->execSQL($sql, 4, 0, true);
			if (! $ret['success']) {
				$ret['msg'] .= ' Aufgetreten in SQL: ' . $sql;
				return $ret;
			}
			$content = pg_fetch_array($ret[1]);
			if (
				($xplan_layer['Datentyp'] = 0 AND $content['num_points'] > 0) OR
				($xplan_layer['Datentyp'] = 1 AND $content['num_lines'] > 0) OR
				($xplan_layer['Datentyp'] = 2 AND $content['num_polygons'] > 0)
			) {
				$layers_with_content[$xplan_layer['Name']] = $xplan_layer;
			}
		}

		return array(
			'success' => true,
			'layers_with_content' => $layers_with_content
		);
	}

	/*
	* Get the name of first planart
	* @return string
	*/
	function get_first_planart_name() {
		$planart_table = $this->get_planart_table();
		$planart_obj = new PgObject($this->gui, $this->schema, $planart_table['table_name']);
		$planart = $planart_obj->find_by($planart_table['value_attribute'], $this->get_first_planart_value());
		return $planart->get('name_attribute');
	}

	/*
	* Return the first value of planart attribute of xp_plan
	* @return string
	*/
	function get_first_planart_value() {
		return trim(explode(',', trim($this->get('planart'),'{}'))[0]);
	}

	/*
	* Return table name as well as value and name attribute for specified planart (BP-Plan, FP-Plan, SO-Plan or RP-Plan)
	* @return array()
	*/
	function get_planart_table() {
		switch ($this->planart) {
			case ('BP-Plan') : { $table_name = 'enum_bp_planart'; $value_attribute = 'wert'; $name_attribute = 'abkuerzung'; } break;
			case ('FP-Plan') : { $table_name = 'enum_fp_planart'; $value_attribute = 'wert'; $name_attribute = 'abkuerzung'; } break;
			case ('SO-Plan') : { $table_name = 'so_planart'; $value_attribute = 'id'; $name_attribute = 'value'; } break;
			case ('RP-Plan') : { $table_name = 'enum_rp_art'; $value_attribute = 'wert'; $name_attribute = 'beschreibung'; } break;
		}
		return array(
			'table_name' => $table_name,
			'value_attribute' => $value_attribute,
			'name_attribute' => $name_attribute
		);
	}

	/*
	* Return the name of the first gemeinde from gemeinde attribute
	* - if gemeinde is an array
	* 	- Replace {} by [] brackets
	* 	- Convert string as json to array
	* 	- Extract the first element of the array
	* - Replace () brackes
	* - Explode string by ,
	* - Extract the 3. Element of the array
	* - Strip empty Spaces
	* @return string
	*/
	function get_first_gemeinde_name() {
		$g = $this->get('gemeinde');
		if (strpos($g, '{') !== false) {
		  $g = json_decode(str_replace(array( '{', '}' ), array('[',']'), $g))[0];
		}
		return trim(explode(',',trim($g,'()'))[2], '"');
	}

	function get_first_ags() {
		$g = $this->get('gemeinde');
		if (strpos($g, '{') !== false) {
		  $g = json_decode(str_replace(array( '{', '}' ), array('[',']'), $g))[0];
		}
		return trim(explode(',',trim($g,'()'))[0], '"');
	}

	function get_center_coord() {
		$sql = "
			SELECT
				ST_Y(
					ST_Transform(
						ST_Centroid(raeumlichergeltungsbereich),
						4326
					)
				) AS lat,
				ST_X(
					ST_Transform(
						ST_Centroid(raeumlichergeltungsbereich),
						4326
					)
				) AS lon
			FROM
				" . $this->qualifiedTableName . "
			WHERE
				gml_id = '" . $this->get($this->identifier) . "'
		";
		#echo 'SQL zur Abfrage der Centroid-Koordinate: ' . $sql;
		$results = $this->getSQLResults($sql);
		$this->center_coord = $results[0];
		return $this->center_coord;
	}

	function get_json($attribute) {
		$sql = "
			SELECT
				to_json(" . $attribute . ") AS " . $attribute . "
			FROM
				" . $this->qualifiedTableName . "
			WHERE
				gml_id = '" . $this->get($this->identifier) . "'
		";
		#echo 'SQL zur Abfrage des Attributes: ' . $attribute . ' mit to_json: '. $sql;
		$results = $this->getSQLResults($sql);
		return $results[0][$attribute];
	}

	function get_bereiche() {
		$bereiche = array();
		$bereich = new XP_Bereich($this->gui, $this->planart);
		$bereiche = $bereich->find_where("
			gehoertzuplan = '{$this->get('gml_id')}'
		");
		return $bereiche;
	}

	/*
	* Fügt einen Zusatz zum existierenden Kommentar hinzu falls dieser Zusatz nicht schon im Kommentar existiert.
	* @param string $zustatz, Der Text, der angehängt wird falls er noch nicht existiert
	*/
	function add_kommentar_if_not_exists($zusatz) {
		if (strpos($this->get('kommentar'), $zusatz) === false) {
			# Hinweis an Kommentar anhängen, weil richtig aber noch nicht vorhanden
			$this->set(
				'kommentar',
				$this->get('kommentar')
				. ($this->get('kommentar') != '' ? ' ' : '')
				. $zusatz
			);
			$this->update_attr(array("kommentar = '" . $this->get('kommentar') . "'"));
		}
	}

	/*
	* Entfernt einen Zusatz aus einem Kommentar falls dieser darin enthalten ist.
	* @param string $zusatz, Der Text, der entfernt werden soll falls er existiert
	*/
	function remove_kommentar_if_exists($zusatz) {
		if (strpos($this->get('kommentar'), $zusatz) !== false) {
			# Hinweis aus Kommentar löschen, weil vorhanden, aber nicht mehr richtig
			$this->set(
				'kommentar',
				trim(
					str_replace(
						$zusatz,
						'',
						$this->get(kommentar)
					)
				)
			);
			$this->update_attr(array("kommentar = '" . $this->get('kommentar') . "'"));
		}
	}

	/**
	 * Löscht textabschnitte des Planes
	 * 
	 */
	function destroy_associated_textabschnitte() {
		$sql = "
			DELETE FROM
				xplan_gml." . $this->planartAbk . "_textabschnitt ta
			WHERE
				ta.konvertierung_id = " . $this->get('konvertierung_id') . "
		";
		#echo '<br>SQL zum Löschen der Textabschnitte der Konvertierung' . $this->get('konvertierung_id') . ': ' . $sql;
		pg_query($this->database->dbConn, $sql);
	}

	/*
	 * Löscht den Plan und alles was damit verbunden ist
	 */
	function destroy() {
		$this->debug->show('Objekt XP_Plan gml_id: ' . $this->get('gml_id') . ' destroy', false);
		$bereiche = $this->get_bereiche();
		foreach ($bereiche AS $bereich) {
			$bereich->destroy();
		}
		$this->destroy_associated_textabschnitte();
		$this->delete();
	}
}
?>
