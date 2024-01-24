<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2004  Peter Korduan                               #
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
#############################
# Klasse Validierung #
#############################

class Validierung extends PgObject {
	static $schema = 'xplankonverter';
	static $tableName = 'validierungen';
	static $write_debug = false;

	function __construct($gui) {
		#echo '<br>Create new Object Validierung';
		parent::__construct($gui, Validierung::$schema, Validierung::$tableName);
		$this->konvertierung_id = 0;
		$this->identifiers = array(
			array(
				'column' => 'konformitaet_nummer',
				'type' => 'character varying'
			),
			array(
				'column' => 'konformitaet_version_von',
				'type' => 'character varying'
			)
		);
	}

	public static	function find_by_id($gui, $by, $id) {
		$validierung = new Validierung($gui);
		$validierung->find_by($by, $id);
		return $validierung;
	}

	public static function find_by_class_name($gui, $class_name) {
		$konformitaets_validierung = new PgObject($gui, 'xplankonverter', 'konformitaets_validierung');
		$konformitaets_validierung->find_where('class_name LIKE ' . $class_name);
	}

	public static function delete_by_id($gui, $by, $id) {
		$validierung = new Validierung($gui);
		$validierung->delete_by($by, $id);
	}

	function regel_existiert($regeln) {
		$regeln_existieren = (count($regeln) > 0);
		$validierungsergebnis = new Validierungsergebnis($this->gui);
		$validierungsergebnis->create(
			array(
				'konvertierung_id' => $this->konvertierung_id,
				'validierung_id' => $this->get('id'),
				'status' => ($regeln_existieren ? 'Erfolg' : 'Warnung'),
				'msg' => 'Es sind' . ($regeln_existieren ? ' ' . count($regeln) : ' keine') . ' Regeln zur Konvertierung vorhanden.'
			)
		);
		return $regeln_existieren;
	}

	function plan_attribute_has_value() {
		$has_value = true;
		$sql = "
			SELECT
				count(*) = 1 AS has_value
			FROM
				xplan_gml.xp_plan
			WHERE
				konvertierung_id = " . $this->konvertierung_id . " AND
				" . implode(' AND ', json_decode(str_replace('}', ']', str_replace('{', '[', $this->get('functionsargumente'))))) . "
		";
		$this->debug->write('plan_attribute_has_value sql: ' . $sql, false);
		$result = pg_fetch_assoc(pg_query($this->database->dbConn, $sql));
		$has_value = $result['has_value'] == 't';
		$validierungsergebnis = new Validierungsergebnis($this->gui);
		$validierungsergebnis->create(
			array(
				'konvertierung_id' => $this->konvertierung_id,
				'validierung_id' => $this->get('id'),
				'status' => ($has_value ? 'Erfolg' : 'Fehler'),
				'msg' => $this->get('msg_' . ($has_value ? 'success' : 'error'))
			)
		);
		return $has_value;
	}

	function sql_ausfuehrbar($regel) {
		$ausfuehrbar = true;

		$sql = $regel->get_convert_sql($this->konvertierung_id);

		$this->debug->write('<br>Validiere ob sql_ausfuehrbar sql: ' . $sql, Validierung::$write_debug);
		# Objekte anlegen
		$search_path  = "SET search_path=xplan_gmlas_{$this->gui->user->id}, xplan_shapes_{$this->konvertierung_id},public;";
		$result = @pg_query(
			$this->database->dbConn,
			$search_path . ' EXPLAIN ' . $sql
		);

		if (!$result) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => 'Regel: ' . $regel->get('name') . ', ' . str_replace("'","''",'SQL: ' . $sql . ' nicht ausführbar.<br>' . @pg_last_error($this->database->dbConn)),
					'regel_id' => $regel->get('id')
				)
			);
			$ausfuehrbar = false;
		}
		$this->debug->write('<br>' . ($ausfuehrbar ? ' sql ist ausführbar.' : ' sql ist nicht ausführbar'), Validierung::$write_debug);
		return $ausfuehrbar;
	}

	function sql_vorhanden($sql, $regel) {
		$this->debug->write('<hr><br>Regel: ' . $regel->get('name') . '<br>Validiere ob sql_vorhanden sql:<br>' . $sql . ' validieren.', Validierung::$write_debug);
		$vorhanden = true;
		if (empty($sql)) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => 'Das SQL-Statement ist leer.',
					'regel_id' => $regel->get('id')
				)
			);
			$vorhanden = false;
		}
		$this->debug->write('<br>' . ($vorhanden ? 'sql vorhanden' : 'sql nicht vorhanden'), Validierung::$write_debug);
		return $vorhanden;
	}

	function alle_sql_ausfuehrbar($alle_ausfuehrbar) {
		if ($alle_ausfuehrbar) {
			$this->debug->write('<br>Alle sql ausführbar.', Validierung::$write_debug);
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Erfolg',
					'msg' => 'Alle Regeln konnten erfolgreich konvertiert werden.'
				)
			);
		}
	}

	/*
	* Replaces last and only last (strRpos) case-insensitive occurence of a value in a string, else return the original string.
	*/
	
	public static function str_ilreplace($search, $replace, $string) {
		$pos = strrpos(strtolower($string), strtolower($search));
		if($pos !== false) {
			$search_length = strlen($search);
			$string = substr_replace($string, $replace, $pos, $search_length);
		}
		return $string;
	}

	/*
	* Funktion findet alle Shape-Objekte, die the_geom = NULL haben.
	* ToDo: Eigentlich müsste hier noch die Shape-Tabelle, bzw. der Shape-Layer extrahiert werden,
	* damit in der Meldung direkt auf die fehlerhaften Objekte verwiesen werden kann.
	* Wenn die Geometrie dann auch änderbar sein soll, müssten die Layerrechte für den shape Layer
	* noch gesetzt werden. Aber dann immer automatisch beim Anlegen des Layers.
	*/
	function geometrie_vorhanden($sql, $regel_id, $sourcetype = 'shape') {
		$this->debug->write('<br>validate ob geometrie_vorhanden mit Ausgangs-sql: ' . $sql, Validierung::$write_debug);
		$geometrie_vorhanden = true;
		$sql = stristr($sql, 'select');
		$this->debug->write('<br>sql von select an: ' . $sql, Validierung::$write_debug);

		# Default Shape
		# ogc_fid is pkey serial (an alternative oid would have to be added to each table after loading it)
		# (position as the default geometry of all objects inheriting from xp_objekt in gmlas
		$gid_or_oid = ($sourcetype == 'gmlas') ? 'ogc_fid' : 'gid';
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';
		$search_path  = ($sourcetype == 'gmlas') ? "xplan_gmlas_{$this->gui->user->id}" : "xplan_shapes_{$this->konvertierung_id}";

		# Frage gid mit ab
		$sql = substr_replace(
			$sql,
			" SELECT " . $gid_or_oid . ", ",
			stripos($sql, 'select'),
			strlen('select')
		);
		$this->debug->write('<br>sql mit ' . $gid_or_oid . ': ' . $sql, Validierung::$write_debug);

		# Hänge Where Klauses is null an
		if (strpos(strtolower($sql), 'where') === false) {
			$sql .= ' WHERE ' . $geometry_col .' IS NULL';
		}
		else {
			$sql = $this->str_ilreplace(
				'where',
				//"WHERE " . $geometry_col . " IS NULL AND ",
				"WHERE " . $geometry_col . " IS NULL AND (",
				$sql
			);
			$sql .= ')';
		}
		$this->debug->write('<br>sql mit where Klausel: ' . $sql, Validierung::$write_debug);

		$sql = "SET search_path=" . $search_path . ", public; " . $sql;

		$this->debug->write('<br>Validiere ob geometrie_vorhanden mit sql:<br>' . $sql, Validierung::$write_debug );;

		$result = pg_query($this->database->dbConn, $sql);
		$regel = Regel::find_by_id($this->gui, 'id', $this->konvertierung_id);
		
		while ($row = pg_fetch_assoc($result)) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Warnung',
					'regel_id' => $regel_id,
					'msg' => 'Objekt mit gid: ' . $row['gid'] . ((array_key_exists('uuid', $row) AND $row['uuid'] != '') ? ' uuid: ' . $row['uuid'] : '') . ' in Shape: ' . $regel->get_shape_table_name() . ' hat keine Geometrie.'
				)
			);
			// gid may not appear in gmlas-data, therefore optional
			if($row['gid'] != '') {
					$validierungsergebnis->shape_gid = $row['gid'];
				}
			$geometrie_vorhanden = false;
		}

		$this->debug->write('<br>' . ($geometrie_vorhanden ? ' geometrie vorhanden' : ' geometrie nicht vorhanden'), Validierung::$write_debug);
		return $geometrie_vorhanden;
	}

  /**
	 * Funktion prüft ob die Geometrien der mit $regel->get_shape_table_name() ermittelte Ausgangsdatentabelle eine SRID haben
	 * und wenn nicht wird der XPLANKONVERTER_DEFAULT_EPSG gesetzt.
	 * @param Regel $regel Die Regel für die auf srid geprüft wird.
	 * @param Konvertierung $konvertierung Die Konvertierung auf zu der die Regel gehört.
	 * @return Boolean $all_geom_has_srid Return true if all geometry has srid otherwise false.
	 */
	function force_geometrie_srid($regel, $konvertierung) {
		$this->debug->write('<br>Validate geom_has_srid:', Validierung::$write_debug);
		$all_geom_has_srid = true;
		if ($regel->is_source_shape_or_gmlas($regel, $konvertierung->get('id')) == 'gmlas') {
			$id_col = 'id';
			$geometry_col = 'position';
		}
		else {
			$id_col = 'gid';
			$geometry_col = 'the_geom';
		}
		$sql = $regel->get_convert_sql($konvertierung->get('id'));
		# Extrahiere alles ab select
		$sql = stristr($sql, 'select');
		$this->debug->write('<br>sql von select an: ' . $sql, Validierung::$write_debug);
		$sql = "
			SELECT
				" . $id_col . " AS gid
			FROM
				" . $regel->get_shape_table_name() . "
			WHERE
				ST_SRID(" . $geometry_col . ") = 0
		";
		$this->debug->write('<br>SQL zum Abfragen der gid der Datensätze mit Geometrien ohne SRID: ' . $sql, Validierung::$write_debug);

		$result = @pg_query(
			$this->database->dbConn,
			$sql
		);

		if (!$result) {
			$this->debug->write('<br>SQL ist nicht ausführbar: ' . $sql, Validierung::$write_debug);
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung->get('id'),
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => 'Regel: ' . $regel->get('name') . ', ' . str_replace("'", "''", 'SQL: ' . $sql . ' nicht ausführbar.<br>' . @pg_last_error($this->database->dbConn)),
					'user_id' => $this->gui->user->id,
					'regel_id' => $regel->get('id')
				)
			);
		}
		else {
			while ($row = pg_fetch_assoc($result)) {
				$this->debug->write("<br>SRID der geometrie mit gid: {$row['gid']} ist 0 und wird auf den Default-EPSG {XPLANKONVERTER_DEFAULT_EPSG} gesetzt.", Validierung::$write_debug);
				$all_geom_has_srid = false;
				$update_result = $this->setDefaultSRID(
					$konvertierung,
					$regel,
					$row['gid']
				);
				$validierungsergebnis = new Validierungsergebnis($this->gui);
				if (!$update_result['success']) {
					$this->debug->write('<br>SQL zum Update der SRID ist nicht ausführbar: ' . $update_result['sql'], Validierung::$write_debug);
					$validierungsergebnis->create(
						array(
							'konvertierung_id' => $konvertierung->get('id'),
							'validierung_id' => $this->get('id'),
							'status' => 'Fehler',
							'msg' => 'Regel: ' . $regel->get('name') . ', ' . str_replace("'", "''", 'SQL: ' . $update_result['sql'] . ' nicht ausführbar.<br>' . $update_result['err_msg']),
							'user_id' => $this->gui->user->id,
							'regel_id' => $regel->get('id')
						)
					);
				}
				else {
					$validierungsergebnis->create(
						array(
							'konvertierung_id' => $konvertierung->get('id'),
							'validierung_id' => $this->get('id'),
							'status' => 'Warnung',
							'msg' => "EPSG-Code: {XPLANKONVERTER_DEFAULT_EPSG}<br>Regel: {$regel->get('name')}<br>Tabelle: {$regel->get_shape_table_name()}<br>Geometriespalte: {$geometry_col} {$id_col}: {$row['gid']}" . ((array_key_exists('uuid', $row) AND $row['uuid'] != '') ? ' uuid: ' . $row['uuid'] : ''),
							'user_id' => $this->gui->user->id,
							'regel_id' => $regel->get('id')
						)
					);
					if ($row['gid'] != '') {
						$validierungsergebnis->shape_gid = $row['gid'];
					}
				}
			}
		}
		$this->debug->write('<br>' . ($all_geom_has_srid ? 'Alle Geometrien haben eine SRID.' : 'Es gibt Geometrie ohne SRID.'), Validierung::$write_debug);
		return $all_geom_has_srid;
	}

	/**
	 * Set XPLANKONVERTER_DEFAULT_EPSG for geometry of dataset with $id in Table $regel->get_shape_table_name()
	 * @param Regel $regel Die Regel für die auf srid geprüft wird.
	 * @param Konvertierung $konvertierung Die Konvertierung auf zu der die Regel gehört.
	 * @return array Array with success Boolean and in error case sql String and err_msg String. 
	 */
	function setDefaultSRID($konvertierung, $regel, $id) {
		if ($regel->is_source_shape_or_gmlas($regel, $konvertierung->get('id')) == 'gmlas') {
			$id_col = 'id';
			$geometry_col = 'position';
		}
		else {
			$id_col = 'gid';
			$geometry_col = 'the_geom';
		}
		$sql = "
			UPDATE
				" . $regel->get_shape_table_name() . "
			SET
				" . $geometry_col . " = ST_SetSRID(" . $geometry_col . ", " . XPLANKONVERTER_DEFAULT_EPSG . ")
			WHERE
				" . $id_col . " = " . quote($id) . "
		";
		$this->debug->write('SQL zum Update der SRID:<br>' . $sql, Validierung::$write_debug);
		$result = @pg_query(
			$this->database->dbConn,
			$sql
		);
		if (!$result) {
			return array(
				'success' => false,
				'sql' => $sql,
				'err_msg' => @pg_last_error($this->database->dbConn)
			);
		}
		else {
			return array(
				'success' => true
			);
		}
	}

	/**
	* Prüft ob die im SQL-Teil der Abfrage gelieferten Geometrien valide sind
	* @param Regel $regel Objekt der Regel
	* @param Konvertierung $konvertierung Objekt der Konvertierung
	* @return boolean $all_geom_isvalid true wenn alle valide sind, false wenn nicht.
	*/
	function geometrie_isvalid($regel, $konvertierung) {
		$this->debug->write('<br>Validate geom_isvalid:', Validierung::$write_debug);
		$all_geom_isvalid = true;
		$sourcetype = $regel->is_source_shape_or_gmlas($regel,$konvertierung->get('id'));
		# shape or gmlas?
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';

		$sql = $regel->get_convert_sql($konvertierung->get('id'));

		# Extrahiere alles ab select
		$sql = stristr($sql, 'select');
		$this->debug->write('<br>sql von select an: ' . $sql, Validierung::$write_debug);

		if($sourcetype != 'gmlas') {
			# Selektiere gid zur eindeutigen Identifizierung des Datensatzes und st_isvalidreason
			$sql = substr_replace(
				$sql,
				" SELECT
					gid,
					st_isvalidreason(" . $geometry_col . ") validreason,
				",
				stripos($sql, 'select'),
				strlen('select')
			);
			$this->debug->write('<br>sql mit gid und is_validreason: ' . $sql, Validierung::$write_debug);
		} else {
			# Selektiere id als gid zur eindeutigen Identifizierung des Datensatzes, st_isvalidreason und geom als Text
			$sql = substr_replace(
				$sql,
				"SELECT
					id AS gid,
					st_isvalidreason(" . $geometry_col .") validreason,
					ST_AsText(" . $geometry_col . ") AS geom_text,
				" ,
				stripos($sql, 'select'),
				strlen('select')
			);
			$this->debug->write('<br>sql mit is_validreason: ' . $sql, Validierung::$write_debug);
		}

		# where klausel für plan hinzufügen
		$sql = $this->str_ilreplace(
			'where',
			"WHERE
				NOT st_isvalid(" . $geometry_col . ") AND (
			",
			$sql
		);

		$this->debug->write('<br>sql mit where st_isvalid: ' . $sql, Validierung::$write_debug);

		# $sql = "SET search_path=xplan_shapes_" . $konvertierung->get('id') . ", public; " . substr($sql, 0, stripos($sql, 'returning'));
		$sql = substr($sql, 0, stripos($sql, 'returning')) . ')';

		$this->debug->write('Sql für Prüfung geom_isvalid:<br>' . $sql, Validierung::$write_debug);

		$result = @pg_query(
			$this->database->dbConn,
			$sql
		);

		if (!$result) {
			$this->debug->write('<br>sql ist nicht ausführbar: ' . $sql, Validierung::$write_debug);
			if (!$ausfuehrbar) echo 'SQL zur Abgrage der Geometrievalidität:<br>' . $sql . '<br><br><br><br>';
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung->get('id'),
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => 'Regel: ' . $regel->get('name') . ', ' . str_replace("'","''",'SQL: ' . $sql . ' nicht ausführbar.<br>' . @pg_last_error($this->database->dbConn)),
					'user_id' => $this->gui->user->id,
					'regel_id' => $regel->get('id')
				)
			);
		}
		else {
			while ($row = pg_fetch_assoc($result)) {
				$this->debug->write('<br>geometrie mit gid: ' . $row['gid'] . ' ist nicht valid', Validierung::$write_debug);
				$validierungsergebnis = new Validierungsergebnis($this->gui);

				$validierungsergebnis->create(
					array(
						'konvertierung_id' => $konvertierung->get('id'),
						'validierung_id' => $this->get('id'),
						'status' => 'Fehler',
						'regel_id' => $regel->get('id'),
						'msg' => 'Regel: ' . $regel->get('name') . '. Geometrie: ' . $row['geom_text'] . ' im Objekt mit gid: ' . $row['gid'] . ((array_key_exists('uuid', $row) AND $row['uuid'] != '') ? ' uuid: ' . $row['uuid'] : '') . ' in Shape: ' . $regel->get_shape_table_name() . ' ist nicht valide. Grund: ' . $row['validreason'] . ' SQL: ' . $sql
					)
				);
				if($row['gid'] != '') {
					$validierungsergebnis->shape_gid = $row['gid'];
				}
				$all_geom_isvalid = false;
			}
		}

		if ($all_geom_isvalid) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung->get('id'),
					'validierung_id' => $this->get('id'),
					'status' => 'Erfolg',
					'msg' => 'Alle Geometrien der Regel sind valide.',
					'regel_id' => $regel->get('id')
				)
			);
		}

		$this->debug->write('<br>' . ($all_geom_isvalid ? 'Alle Geometrien sind valid.' : 'Es sind nicht alle Geometrien valid.'), Validierung::$write_debug);
		return $all_geom_isvalid;
	}

	/*
	* Prüft ob die im SQL-Teil der Abfrage gelieferten Geometrien im räumlichen Geltungsbereich
	* des Planes mit plan_gml_id enthalten sind.
	* Wenn nicht wird pro Ausreißer ein Validierungsergebnis mit status = Warnung angelegt
	* wenn die Entfernung <= 100km ist ansonsten mit status = Fehler.
	* Wenn alle innerhalb sind, wird ein Validierungsergebnis mit status = Erfolg angelegt.
	* @param object $regel Objekt der Regel
	* @param object $konvertierung Objekt der Konvertierung
	* @return boolean $all_within_plan true wenn alle drin liegen, false wenn nicht.
	*/
	function geom_within_plan($regel, $konvertierung) {
		$this->debug->write('<br>Validate ob geom_within_plan sql.', Validierung::$write_debug);
		$all_within_plan = true;

		$sql = $regel->get_convert_sql($konvertierung->get('id'));
		$sourcetype = $regel->is_source_shape_or_gmlas($regel,$konvertierung->get('id'));
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';
		# Default Shape (the_geom), gmlas (position)

		$plantype = $konvertierung->get_plan()->tableName;

		# Extrahiere alles ab select
		$sql = stristr($sql, 'select');
		$this->debug->write('<br>sql von select an: ' . $sql, Validierung::$write_debug);
		

		# Selektiere gid zur eindeutigen Identifizierung des Datensatzes und within und distance
		#if for gid (shape) or no gid (gmlas)
		# changed to 25832 as otherwise thered be mixed geometry errors
		# TODO Get the Epsg code from $konvertierung

		# Buffer = 0.001mm, as 2mm euclidean distance is considered equivalent in XPlanung
		# Buffer distance according SRID unit, for 25832 and 25833 distance is meters
		# TODO In PostGIS 2.5 and higher, consider using one of the new variable precision functions instead
		# e.g. ST_AsText with precision, ST_QuantizeCoordinates
		# or build a custom ST_Within (and ST_Intersection?) variant, that utilizes the relevant equivalency distance
		$tolerance_meters = '0.002';
		
		if($sourcetype != 'gmlas') {
			# replaces only first occurence to allow later subqueries
			$sql = substr_replace(
				$sql,
				" SELECT
					gid,
					NOT st_within(" . $geometry_col . ", ST_Buffer(ST_Transform(" . $plantype . ".raeumlichergeltungsbereich," . $konvertierung->get('input_epsg') . ")," . $tolerance_meters . ")) AS ausserhalb,
					st_distance(ST_Transform(" . $geometry_col . ", " . $konvertierung->get('input_epsg') ."), ST_Transform(" . $plantype . ".raeumlichergeltungsbereich, " . $konvertierung->get('input_epsg') ."))/1000 AS distance,
				 ",
				stripos($sql, 'select'),
				strlen('select')
			);
			

			$this->debug->write('<br>sql mit gid, within und distance: ' . $sql, Validierung::$write_debug);
		} else {
			$sql = substr_replace(
				$sql,
				" SELECT
					NOT st_within(" . $geometry_col . ", ST_Buffer(ST_Transform(" . $plantype . ".raeumlichergeltungsbereich," . $konvertierung->get('input_epsg') . ")," . $tolerance_meters . ")) AS ausserhalb,
					st_distance(ST_Transform(" . $geometry_col . ", " . $konvertierung->get('input_epsg') ."), ST_Transform(" . $plantype . ".raeumlichergeltungsbereich, " . $konvertierung->get('input_epsg') ."))/1000 AS distance,
				 ",
				stripos($sql, 'select'),
				strlen('select')
			);

			$this->debug->write('<br>sql mit gid, within und distance: ' . $sql, Validierung::$write_debug);
		}

		# tabelle " . $plantype . " hinzufügen
		$sql = $this->str_ilreplace(
			'from',
			"FROM
				xplan_gml." . $plantype . " " . $plantype . ",
			",
			$sql
		);
		$this->debug->write('<br>sql mit " . $plantype . " Tabelle: ' . $sql, Validierung::$write_debug);

		# where klausel für plan hinzufügen
		$sql = $this->str_ilreplace(
			'where',
			"WHERE
				" . $plantype . ".konvertierung_id = " . $konvertierung->get('id') . " AND 
				NOT st_within(ST_Transform(" . $geometry_col . ", " . $konvertierung->get('input_epsg') ."), 
						ST_Buffer(raeumlichergeltungsbereich," . $tolerance_meters . ")) AND (
			",
			$sql
		);

		$this->debug->write('<br>sql mit where Klausel für ' . $plantype . ': ' . $sql, Validierung::$write_debug);

		#$sql = "SET search_path=xplan_shapes_" . $konvertierung->get('id') . ", public; " . 
		$sql = substr($sql, 0, stripos($sql, 'returning')) . ')';
		$this->debug->write('<br>sql ohne returning: ' . $sql, Validierung::$write_debug);

		$this->debug->write('Sql für Prüfung geom_within_plan:<br>' . $sql, false);


		$search_path  = ($sourcetype == 'gmlas') ? "xplan_gmlas_{$this->gui->user->id}" : "xplan_shapes_{$this->konvertierung_id}";
		$sql = "SET search_path=" . $search_path . ", public; " . $sql;

		$result = @pg_query(
			$this->database->dbConn,
			$sql
		);

		if (!$result) {
			$this->debug->write('<br>sql ist nicht ausführbar: ' . $sql, Validierung::$write_debug);
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung->get('id'),
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => 'Regel: ' . $regel->get('name') . ', ' . str_replace("'","''",'SQL: ' . $sql . ' nicht ausführbar.<br>' . @pg_last_error($this->database->dbConn)),
					'user_id' => $this->gui->user->id,
					'regel_id' => $regel->get('id')
				)
			);
		}
		else {
			while ($row = pg_fetch_assoc($result)) {
				if ($row['ausserhalb'] == 't') {
					$this->debug->write('geometrie mit gid: ' . $row['gid'] . ' ist außerhalb des Geltungsbereich des Plans', Validierung::$write_debug);
					$validierungsergebnis = new Validierungsergebnis($this->gui);
					$validierungsergebnis->create(
						array(
							'konvertierung_id' => $konvertierung->get('id'),
							'validierung_id' => $this->get('id'),
							'status' => ($row['distance'] > 100 ? 'Fehler' : 'Warnung'),
							'regel_id' => $regel->get('id'),
							'msg' => 'Objekt mit' . ($sourcetype == 'gmlas' ? '' : ' gid: ' . $row['gid']). ((array_key_exists('uuid', $row) AND $row['uuid'] != '') ? ' uuid: ' . $row['uuid'] : '') . ' in Shape: ' . $regel->get_shape_table_name() . ' ist außerhalb des räumlichen Geltungsbereiches des Planes.' . ($row['distance'] > 100 ? ' Das Objekt ist mehr als 100 km entfernt.' : '')
						)
					);
					if($row['gid'] != '') {
					$validierungsergebnis->shape_gid = $row['gid'];
				}
					$all_within_plan = false;
				}
			}
		}

		if ($all_within_plan) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung->get('id'),
					'validierung_id' => $this->get('id'),
					'status' => 'Erfolg',
					'msg' => 'Alle Objekte der Regel liegen im Geltungsbereich des Planes.',
					'regel_id' => $regel->get('id')
				)
			);
		}
		$this->debug->write('<br>' . ($all_within_plan ? 'Alle Geometrien innerhalb des Geltungsbereiches des Plans.' : 'Nicht alle Geometrien innerhalb des Geltungsbereiches des Plans.'), Validierung::$write_debug);
		return $all_within_plan;
	}

	/*
	* Prüft ob die im SQL-Teil der Abfrage gelieferten Geometrien im räumlichen Geltungsbereich
	* des Planbereiches (mit gehoertzubereich) liegen zu dem die Objekte zugeordnet sind.
	* Wenn nicht wird pro Ausreißer ein Validierungsergebnis mit status = Warnung angelegt
	* wenn die Entfernung <= 100km ist ansonsten mit status = Fehler.
	* Wenn alle innerhalb sind, wird ein Validierungsergebnis mit status = Erfolg angelegt.
	* @param object $regel Objekt der Regel
	* @param object $konvertierung Objekt der Konvertierung
	* @return boolean $all_within_bereich true wenn alle drin liegen, false wenn nicht.
	*/
	function geom_within_bereich($regel, $konvertierung) {
		$this->debug->write('<br>Validiere ob Geometrien in Bereich. (ToDo: Noch zu implementieren)', Validierung::$write_debug);
		$all_within_bereich = true;

		# SQL der Konvertierungsregel abfragen
		$sql = $regel->get_convert_sql($konvertierung->get('id'));

		# Spaltenname der Geometrie ermitteln
		$sourcetype = $regel->is_source_shape_or_gmlas($regel,$konvertierung->get('id'));
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';

		# Plantyp abfragen
		$bereichtype = $konvertierung->get_plan()->planartAbk . '_bereich';

		# Alles ab select extrahieren
		$sql = stristr($sql, 'select');
		$this->debug->write('<br>sql von select an: ' . $sql, Validierung::$write_debug);		

		# Tolerance and buffer, see comment in geom_within_plan
		$tolerance_meters = '0.002';
		# gid zur eindeutigen Identifizierung des Datensatzes (nicht bei gmlas) sowie within und distance zum select hinzufügen
		# replace only first instance, not potential following subqueries
		$sql = substr_replace(
			$sql,
			"select
				" . ($sourcetype == 'gmlas' ? '' : 'gid,') . "
				NOT st_within(" . $geometry_col . ", ST_Buffer(" . $bereichtype . ".geltungsbereich," . $tolerance_meters . ")) AS ausserhalb,
				st_distance(ST_Transform(" . $geometry_col . ", " . $konvertierung->get('input_epsg') ."), ST_Transform(" . $bereichtype . ".geltungsbereich, " . $konvertierung->get('input_epsg') . "))/1000 AS distance,
			",
			stripos($sql, 'select'),
			strlen('select')
		
		);

		$this->debug->write('<br>sql mit gid (nicht bei gmlas), within und distance: ' . $sql, Validierung::$write_debug);

		# Tabelle " . $bereichtype . " hinzufügen
		$sql = $this->str_ilreplace(
			'from',
			"FROM
				xplan_gml." . $bereichtype . " " . $bereichtype . ",
			",
			$sql
		);
		$this->debug->write('<br>sql mit " . $plantype . " Tabelle: ' . $sql, Validierung::$write_debug);

		# where klausel für bereich hinzufügen
		$sql = $this->str_ilreplace(
			'where',
			"WHERE
				" . $bereichtype . ".gml_id = '" . $regel->get('bereich_gml_id') . "' AND
				" . $bereichtype . ".konvertierung_id = " . $konvertierung->get('id') . " AND 
				NOT st_within(ST_Transform(" . $geometry_col . ", " . $konvertierung->get('input_epsg') . "),
											ST_Transform(ST_Buffer(" . $bereichtype . ".geltungsbereich," . $tolerance_meters . "), " . $konvertierung->get('input_epsg') . ")) AND (
			",
			$sql
		);
		

		$this->debug->write('<br>sql mit where Klausel für ' . $bereichtype . ': ' . $sql, Validierung::$write_debug);
		$sql = substr($sql, 0, stripos($sql, 'returning')) . ')';
		$this->debug->write('<br>sql ohne returning: ' . $sql, Validierung::$write_debug);

		# SQL zur Validierung ausführen
		/*
			Beispiel für eine korrekte Abfrage
			SELECT
			  gid,
				NOT st_within(the_geom, bp_bereich.geltungsbereich) AS ausserhalb,
				st_distance(ST_Transform(the_geom, 25833), ST_Transform(bp_bereich.geltungsbereich, 25833))/1000 AS distance,
			  '2000'::xplan_gml.bp_rechtscharakter AS rechtscharakter,
			  '2000'::xplan_gml.xp_rechtsstand AS rechtsstand,
			  FALSE AS flaechenschluss,
			  the_geom AS position
			FROM
			  xplan_gml.bp_bereich bp_bereich,
			  xplan_shapes_95007.shp_lk_nwm_bplan_1528_flaechen
			WHERE
			  bp_bereich.gml_id = 'b0ae69e8-8b4c-11e8-b78b-470a08309bd3' AND
			  bp_bereich.konvertierung_id = 95007 AND
			  NOT st_within(the_geom, bp_bereich.geltungsbereich) AND (
			  xp_x_plan = 'BP_GebaeudeFlaeche' AND
			  objektstat = 'Ursprungsplan'
			)
		*/
		$this->debug->write('Sql für Prüfung geom_within_plan:<br>' . $sql, false);
		$result = @pg_query(
			$this->database->dbConn,
			$sql
		);

		if (!$result) {
			$this->debug->write('<br>sql ist nicht ausführbar: ' . $sql, Validierung::$write_debug);
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung->get('id'),
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => 'Regel: ' . $regel->get('name') . ', ' . str_replace("'","''",'SQL: ' . $sql . ' nicht ausführbar.<br>' . @pg_last_error($this->database->dbConn)),
					'user_id' => $this->gui->user->id,
					'regel_id' => $regel->get('id')
				)
			);
		}
		else {
			while ($row = pg_fetch_assoc($result)) {
				if ($row['ausserhalb'] == 't') {
					$this->debug->write('geometrie mit gid: ' . $row['gid'] . ' ist außerhalb vom Planbereich', Validierung::$write_debug);
					$validierungsergebnis = new Validierungsergebnis($this->gui);
					$validierungsergebnis->create(
						array(
							'konvertierung_id' => $konvertierung->get('id'),
							'validierung_id' => $this->get('id'),
							'status' => ($row['distance'] > 100 ? 'Fehler' : 'Warnung'),
							'regel_id' => $regel->get('id'),
							'msg' => 'Objekt mit gid: ' . $row['gid'] . ((array_key_exists('uuid', $row) AND $row['uuid'] != '') ? ' uuid: ' . $row['uuid'] : '') . ' in Shape: ' . $regel->get_shape_table_name() . ' ist außerhalb des räumlichen Geltungsbereiches seines Planbereiches.' . ($row['distance'] > 100 ? ' Das Objekt ist mehr als 100 km entfernt.' : '')
						)
					);
					if($row['gid'] != '') {
					$validierungsergebnis->shape_gid = $row['gid'];
				}
					$all_within_bereich = false;
				}
			}
		}

		if ($all_within_bereich) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung->get('id'),
					'validierung_id' => $this->get('id'),
					'status' => 'Erfolg',
					'msg' => 'Alle Objekte der Regel liegen in ihren Planbereichen.',
					'regel_id' => $regel->get('id')
				)
			);
		}
		$this->debug->write('<br>' . ($all_within_plan ? 'Alle Geometrien innerhalb ihrer Planbereiche.' : 'Nicht alle Geometrien innerhalb ihrer Planbereiche.'), Validierung::$write_debug);
		return $all_within_bereich;
	}

	function detaillierte_requires_bedeutung($bereich) {
		$success = true;
		if ($bereich->get('detailliertebeschreibung') == '') {
			$msg = 'Im Bereich ' . $bereich->get('nummer') . ' wurde keine detaillierte Bedeutung angegeben, deswegen muss auch keine Bedeutung angegeben werden.';
		}
		else {
			if ($bereich->get('bedeutung') == '') {
				$success = false;
				$msg = 'Da im Bereich ' . $bereich->get('nummer') . ' eine detaillierte Bedeutung angegeben wurde, muss auch eine Bedeutung angegeben werden.';
			}
			else {
				$msg = 'Zur angegebenen detaillierten Bedeutung im Bereich ' . $bereich->get('nummer') . ' wurde auch eine Bedeutung angegeben.';
			}
		}
		$validierungsergebnis = new Validierungsergebnis($this->gui);
		$validierungsergebnis->create(
			array(
				'konvertierung_id' => $this->konvertierung_id,
				'validierung_id' => $this->get('id'),
				'status' => ($success ? 'Erfolg' : 'Fehler'),
				'msg' => $msg
			)
		);
		return $success;
	}

	/**
	* Zerteilt alle Multipolygone von bp_, fp_ und so_flaechenschlussobjekten und ließt diese in die Tabelle xplankonverter.flaechenschlussobjekte ein.
	* Erzeugt ein umschließendes Pufferpolygon vom Geltungsbereich des Planes und fügt diese auch in die flaechenschlussobjekt Tabelle
	* Erzeugt eine Topologie von all diesen Flächen
	* Ermittelt die faces, die zu mehr als einem Polygon zugeordnet sind (Überlappungen)
	* @param object $plan, Der Plan der validiert wird
	* @return array mit success boolean True wenn keine Überlappungen und Lücken gefunden wurden. 
	* wenn welche gefunden wurden, succes = false und eine err_msg, die angibt welche Objekte sich überlappen oder wo Lücken sind.
	*/
	function flaechenschluss_ueberlappungen($plan) {
		$success = true;
		$kommentar_zusatz = 'Die Flächenschlussbedingung des Planes ist nicht gegeben wegen Überlappungen der Geometrien von Inhalten.';

		# prüft ob es faces gibt, die mehreren Geometrien zugeordnet sind
		$sql = "
			SELECT
				string_agg(foo.uuid, ' - ') neighbours
			FROM
				(
					SELECT DISTINCT
						fo.uuid,
						r.element_id
					FROM
					  flaechenschluss_topology.relation r JOIN
					  xplankonverter.flaechenschlussobjekte fo ON r.topogeo_id = (fo.topo).id
					WHERE
						fo.konvertierung_id = " . $this->konvertierung_id . "
				) foo
			GROUP BY foo.element_id
			HAVING
				count(foo.element_id) > 1 AND
				string_agg(foo.uuid, ' - ') != 'räumlicher Geltungsbereich Plan'
		";
		#echo '<p>SQL zur Abfrage von Überlappungen in der Topologie: ' . $sql;
		$overlaps = $this->getSQLResults($sql);
		if (count($overlaps) > 0) {
			$success = false;
			$msg = 'Die Validierung schlägt fehl, weil es zwischen folgenden Flächenschlussobjekten Überlappungen gibt, sie außerhalb des räumlichen Geltungsbereiches liegen oder Stützpunkte in der Nachbargeometrie fehlen:<br>' .
				implode('<br>', array_map(
					function($overlap) {
						return $overlap['neighbours'];
					},
					$overlaps
				));
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => ($success ? 'Erfolg' : 'Warnung'),
					'msg' => $msg
				)
			);
		} else {
			$success = true;
			$msg = 'Erfolg der Flächenschlussprüfung. Der Plan beinhaltet keine Überlappungen im Flächenschluss.';
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => ($success ? 'Erfolg' : 'Warnung'),
					'msg' => $msg
				)
			);
		}

		if ($success) {
			$plan->remove_kommentar_if_exists($kommentar_zusatz);
		}
		else {
			$plan->add_kommentar_if_not_exists($kommentar_zusatz);
		}

		return $success;
	}

	/**
	* Zerteilt alle Multipolygone von bp_, fp_ und so_flaechenschlussobjekten und ließt diese in die Tabelle xplankonverter.flaechenschlussobjekte ein.
	* Erzeugt ein umschließendes Pufferpolygon vom Geltungsbereich des Planes und fügt diese auch in die flaechenschlussobjekt Tabelle
	* Erzeugt eine Topologie von all diesen Flächen
	* Ermittelt die faces, die zu keinem Polygon zugeordnet sind (Lücken)
	* @param object $plan, Der Plan der validiert wird
	* @return array mit success boolean True wenn keine Überlappungen und Lücken gefunden wurden. 
	* wenn welche gefunden wurden, succes = false und eine err_msg, die angibt welche Objekte sich überlappen oder wo Lücken sind.
	*/
	function flaechenschluss_luecken($plan) {
		$success = true;
		$kommentar_zusatz = 'Die Flächenschlussbedingung des Planes ist nicht gegeben wegen Lücken zwischen den Geometrien von Inhalten.';

		# prüft ob es faces gibt, die keiner Geometrie zugeordnet sind
		$sql = "
			SELECT
				string_agg(foo.uuid, ' - ') neighbours
			FROM
				(
					SELECT DISTINCT
						fo.uuid,
						f.face_id
					FROM
						flaechenschluss_topology.face f LEFT JOIN
						flaechenschluss_topology.relation r ON f.face_id = r.element_id LEFT JOIN
						flaechenschluss_topology.edge_data el ON f.face_id = el.left_face JOIN
						flaechenschluss_topology.relation rr ON el.right_face = rr.element_id JOIN
						xplankonverter.flaechenschlussobjekte fo ON rr.topogeo_id = (fo.topo).id
					WHERE
						f.face_id != 0 AND
						r.element_id IS NULL AND
						fo.konvertierung_id = " . $this->konvertierung_id . "
				)	foo
			GROUP BY foo.face_id
			HAVING string_agg(foo.uuid, ' - ') != 'räumlicher Geltungsbereich Plan'
		";
		#echo '<p>SQL zur Abfrage von Lücken in der Topologie: ' . $sql;
		$gaps = $this->getSQLResults($sql);
		if (count($gaps) > 0) {
			$success = false;
			$msg = 'Die Validierung schlägt fehl, weil zwischen folgenden Flächenschlussobjekten oder Flächenschlussobjekten und dem räumlichen Geltungsbereich Lücken sind oder Stützpunkte in der Nachbargeometrie fehlen:<br>' .
				implode('<br>', array_map(
					function($gap) {
						return $gap['neighbours'];
					},
					$gaps
				));
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => ($success ? 'Erfolg' : 'Warnung'),
					'msg' => $msg
				)
			);
		} else {
			$success = true;
			$msg = 'Erfolg der Flächenschlussprüfung. Der Plan beinhaltet keine Lücken im Flächenschluss.';
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => ($success ? 'Erfolg' : 'Warnung'),
					'msg' => $msg
				)
			);
		}

		if ($success) {
			$plan->remove_kommentar_if_exists($kommentar_zusatz);
		}
		else {
			$plan->add_kommentar_if_not_exists($kommentar_zusatz);
		}
		return $success;
	}

	function validiere_konformitaet($konvertierung_id, $bedingung) {
		$conformity = false;
		$konformitaetsbedingung = $bedingung['konformitaet'];
		#echo '<p>Validierung der Klasse: ' . $bedingung['class_name'] . ', Konformität Nr: ' . $konformitaetsbedingung->get('nummer') . ' ' . $konformitaetsbedingung->get('bezeichnung') . ', Validierung: ' . $this->get('name');
		switch ($this->get('functionsname')) {
			default :
				$sql = "
					SELECT
						*
					FROM
						xplan_gml." . strtolower($bedingung['class_name']) . "
					WHERE
						NOT (" . substr($this->get('functionsargumente'), 2, -2) . ") AND
						konvertierung_id = " . $konvertierung_id . "
				";
		}
		$this->debug->write('sql to find failed konformities: ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
		$validierungsergebnis = new Validierungsergebnis($this->gui);
		if (pg_num_rows($query) > 0) {
			# Ermittle die Regel, in der $bedingung['class_name'] konvertiert wurde
			$regeln = Regel::find_by_konvertierung_and_class_name($this->gui, $konvertierung_id, $bedingung['class_name']);
			$fehler = pg_fetch_all($query);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => 'Folgende Objekte der Klasse ' . $bedingung['class_name']
						. ' widersprechen der Validierung:<br>'
						. implode(
							', ',
							array_map(
								function($rs) {
									return ($rs['uuid'] != '' ? $rs['uuid'] : $rs['gml_id']);
								},
								$fehler
							)
						)
						. ((count(array_filter(
								$fehler,
								function($rs) {
									return ($rs['uuid'] == '');
								}
								)) > 0 AND count($regeln) > 0) ? '<br>Bei der Regel ' . implode(', ', array_map(function($regel) {
									return '<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=15&operator_id==&value_id=' . $regel->get('id') . '">' . $regel->get('id') . '</a>';
								}, $regeln)) . ' fehlt die Übernahme der Spalte gid in das Attribut uuid des XPlanungobjektes. Bitte Regel korrigieren.' : ''),
					'regel_id' => $regeln[0]->get('id')
				)
			);
		}
		else {
			$conformity = true;
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Erfolg',
					'msg' => 'Alle Objekte der Klasse ' . $bedingung['class_name'] . ' entsprechen der Validierung ' . $this->get('name')
						. ' der Konformitätsbedingung Nr: ' . $konformitaetsbedingung->get('nummer') . ' ' . $konformitaetsbedingung->get('bezeichnung')
				)
			);
		}
		return $conformity;
	}

	function doValidate($konvertierung) {
		// validate here
		if (true)
			return array('success' => 'OK');
		else
			return array('success' => 'ERROR', 'error' => 'Validierung fehlgeschlagen');
	}

	function validateKonvertierung($konvertierung,$success,$failure) {
		$result = $this->doValidate($konvertierung);
		if ($result['success'] == 'OK') {
			// status setzen
			$konvertierung->set('status', Konvertierung::$STATUS['KONVERTIERUNG_OK']);
			$konvertierung->update();
			// success callback ausführen
			$success();
		} else {
			// status setzen
			$konvertierung->set('status', Konvertierung::$STATUS['KONVERTIERUNG_ERR']);
			$konvertierung->update();
			// error callback ausfuehren
			$failure($result['error']);
		}
		return;
	}
}

?>
