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

	function sql_ausfuehrbar($regel) {
		$this->debug->show('<br>Validiere ob sql_ausfuehrbar: ', Validierung::$write_debug);
		$ausfuehrbar = true;

		$sql = $regel->get_convert_sql($this->konvertierung_id);

		# Objekte anlegen
		$result = @pg_query(
			$this->database->dbConn,
			'EXPLAIN ' . $sql
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
		$this->debug->show('<br>' . ($ausfuehrbar ? ' sql ist ausführbar.' : ' sql ist nicht ausführbar'), Validierung::$write_debug);
		return $ausfuehrbar;
	}

	function sql_vorhanden($sql, $regel) {
		$this->debug->show('<hr><br>Regel: ' . $regel->get('name') . '<br>Validiere ob sql_vorhanden sql:<br>' . $sql . ' validieren.', Validierung::$write_debug);
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
		$this->debug->show('<br>' . ($vorhanden ? 'sql vorhanden' : 'sql nicht vorhanden'), Validierung::$write_debug);
		return $vorhanden;
	}

	function alle_sql_ausfuehrbar($alle_ausfuehrbar) {
		if ($alle_ausfuehrbar) {
			$this->debug->show('<br>Alle sql ausführbar.', Validierung::$write_debug);
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
	* Funktion findet alle Shape-Objekte, die the_geom = NULL haben.
	* ToDo: Eigentlich müsste hier noch die Shape-Tabelle, bzw. der Shape-Layer extrahiert werden,
	* damit in der Meldung direkt auf die fehlerhaften Objekte verwiesen werden kann.
	* Wenn die Geometrie dann auch änderbar sein soll, müssten die Layerrechte für den shape Layer
	* noch gesetzt werden. Aber dann immer automatisch beim Anlegen des Layers.
	*/
	function geometrie_vorhanden($sql, $regel_id, $sourcetype = 'shape') {
		$this->debug->show('<br>validate ob geometrie_vorhanden mit Ausgangs-sql: ' . $sql, Validierung::$write_debug);
		$geometrie_vorhanden = true;
		$sql = stristr($sql, 'select');
		$this->debug->show('<br>sql von select an: ' . $sql, Validierung::$write_debug);

		# Default Shape
		# ogc_fid is pkey serial (an alternative oid would have to be added to each table after loading it)
		# (position as the default geometry of all objects inheriting from xp_objekt in gmlas
		$gid_or_oid = ($sourcetype == 'gmlas') ? 'ogc_fid' : 'gid';
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';
		$search_path  = ($sourcetype == 'gmlas') ? "xplan_gmlas_{$this->gui->user->id}" : "xplan_shapes_{$this->konvertierung_id}";

		# Frage gid mit ab
		$sql = str_ireplace(
			'select',
			"select " . $gid_or_oid . ",",
			$sql
		);
		$this->debug->show('<br>sql mit ' . $gid_or_oid . ': ' . $sql, Validierung::$write_debug);

		# Hänge Where Klauses is null an
		if (strpos(strtolower($sql), 'where') === false) {
			$sql .= ' where ' . $geometry_col .' IS NULL';
		}
		else {
			$sql = str_ireplace(
				'where',
				"WHERE " . $geometry_col . " IS NULL AND ",
				$sql
			);
		}
		$this->debug->show('<br>sql mit where Klausel: ' . $sql, Validierung::$write_debug);

		$sql = "SET search_path=" . $search_path . ", public; " . $sql;

		$this->debug->show('<br>Validiere ob geometrie_vorhanden mit sql:<br>' . $sql, Validierung::$write_debug);

		$result = pg_query($this->database->dbConn, $sql);

		while ($row = pg_fetch_assoc($result)) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Warnung',
					'regel_id' => $regel_id,
					'shape_gid' => $row['gid'],
					'msg' => 'Objekt mit gid=' . $row['gid'] . ' hat keine Geometrie.'
				)
			);
			$geometrie_vorhanden = false;
		}

		$this->debug->show('<br>' . ($geometrie_vorhanden ? ' geometrie vorhanden' : ' geometrie nicht vorhanden'), Validierung::$write_debug);
		return $geometrie_vorhanden;
	}

	/*
	* Prüft ob die im SQL-Teil der Abfrage gelieferten Geometrien valide sind
	* @param object $regel Objekt der Regel
	* @param object $konvertierung Objekt der Konvertierung
	* @return boolean $all_valid true wenn alle valide sind, false wenn nicht.
	*/
	function geometrie_isvalid($regel, $konvertierung) {
		$this->debug->show('<br>Validate geom_isvalid:', Validierung::$write_debug);
		$all_geom_isvalid = true;
		$sourcetype = $regel->is_source_shape_or_gmlas($regel);
		# shape or gmlas?
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';

		$sql = $regel->get_convert_sql($konvertierung->get('id'));

		# Extrahiere alles ab select
		$sql = stristr($sql, 'select');
		$this->debug->show('<br>sql von select an: ' . $sql, Validierung::$write_debug);

		if($sourcetype != 'gmlas') {
			# Selektiere gid zur eindeutigen Identifizierung des Datensatzes und st_isvalidreason
			$sql = str_ireplace(
				'select',
				"select
					gid,
					st_isvalidreason(" . $geometry_col . ") validreason,
				",
				$sql
			);
			$this->debug->show('<br>sql mit gid und is_validreason: ' . $sql, Validierung::$write_debug);

		} else {
			# Selektiere gid zur eindeutigen Identifizierung des Datensatzes und st_isvalidreason
			$sql = str_ireplace(
				'select',
				"select
					st_isvalidreason(" . $geometry_col .") validreason,
				",
				$sql
			);
			$this->debug->show('<br>sql mit is_validreason: ' . $sql, Validierung::$write_debug);
		}

	# where klausel für plan hinzufügen
	$sql = str_ireplace(
		'where',
		"where
			NOT st_isvalid(" . $geometry_col . ") AND (
		",
		$sql
		);

		$this->debug->show('<br>sql mit where st_isvalid: ' . $sql, Validierung::$write_debug);

#		$sql = "SET search_path=xplan_shapes_" . $konvertierung->get('id') . ", public; " . substr($sql, 0, stripos($sql, 'returning'));
		$sql = substr($sql, 0, stripos($sql, 'returning')) . ')';

		$this->debug->show('Sql für Prüfung geom_isvalid:<br>' . $sql, Validierung::$write_debug);

		$result = @pg_query(
			$this->database->dbConn,
			$sql
		);

		if (!$result) {
			$this->debug->show('<br>sql ist nicht ausführbar: ' . $sql, Validierung::$write_debug);
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
				$this->debug->show('<br>geometrie mit gid: ' . $row['gid'] . ' ist nicht valid', Validierung::$write_debug);
				$validierungsergebnis = new Validierungsergebnis($this->gui);
				$validierungsergebnis->create(
					array(
						'konvertierung_id' => $konvertierung->get('id'),
						'validierung_id' => $this->get('id'),
						'status' => 'Fehler',
						'regel_id' => $regel->get('id'),
						'shape_gid' => $row['gid'],
						'msg' => 'Regel: ' . $regel->get('name') . '. Objekt mit gid=' . $row['gid'] . ' ist nicht valide. Grund: ' . $row['validreason']
					)
				);
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

		$this->debug->show('<br>' . ($all_geom_isvalid ? 'Alle Geometrien sind valid.' : 'Es sind nicht alle Geometrien valid.'), Validierung::$write_debug);
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
		$this->debug->show('<br>Validate ob geom_within_plan sql.', Validierung::$write_debug);
		$all_within_plan = true;

		$sql = $regel->get_convert_sql($konvertierung->get('id'));
		$sourcetype = $regel->is_source_shape_or_gmlas($regel);
		# Default Shape (the_geom), gmlas (position)
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';

		$plantype = $konvertierung->get_plan()->tableName;

		# Extrahiere alles ab select
		$sql = stristr($sql, 'select');
		$this->debug->show('<br>sql von select an: ' . $sql, Validierung::$write_debug);
		

		# Selektiere gid zur eindeutigen Identifizierung des Datensatzes und within und distance
		#if for gid (shape) or no gid (gmlas)
		# changed to 25832 as otherwise thered be mixed geometry errors
		# TODO Get the Epsg code from $konvertierung
		if($sourcetype != 'gmlas') {
			$sql = str_ireplace(
				'select',
				"select
					gid,
					NOT st_within(" . $geometry_col . ", " . $plantype . ".raeumlichergeltungsbereich) AS ausserhalb,
					st_distance(ST_Transform(" . $geometry_col . ", " . $konvertierung->get('input_epsg') ."), ST_Transform(" . $plantype . ".raeumlichergeltungsbereich, " . $konvertierung->get('input_epsg') ."))/1000 AS distance,
				",
				$sql
			);
			$this->debug->show('<br>sql mit gid, within und distance: ' . $sql, Validierung::$write_debug);
		} else {
			$sql = str_ireplace(
				'select',
				"select
					NOT st_within(" . $geometry_col . ", " . $plantype . ".raeumlichergeltungsbereich) AS ausserhalb,
					st_distance(ST_Transform(" . $geometry_col . ", " . $konvertierung->get('input_epsg') ."), ST_Transform(" . $plantype . ".raeumlichergeltungsbereich, " . $konvertierung->get('input_epsg') ."))/1000 AS distance,
				",
				$sql
			);
			$this->debug->show('<br>sql mit gid, within und distance: ' . $sql, Validierung::$write_debug);
		}

		# tabelle " . $plantype . " hinzufügen
		$sql = str_ireplace(
			'from',
			"from
				xplan_gml." . $plantype . " " . $plantype . ",
			",
			$sql
		);
		$this->debug->show('<br>sql mit " . $plantype . " Tabelle: ' . $sql, Validierung::$write_debug);

		# where klausel für plan hinzufügen
		$sql = str_ireplace(
			'where',
			"where
				" . $plantype . ".konvertierung_id = " . $konvertierung->get('id') . " AND 
				NOT st_within(" . $geometry_col . ", raeumlichergeltungsbereich) AND (
			",
			$sql
		);
		$this->debug->show('<br>sql mit where Klausel für ' . $plantype . ': ' . $sql, Validierung::$write_debug);

		#$sql = "SET search_path=xplan_shapes_" . $konvertierung->get('id') . ", public; " . 
		$sql = substr($sql, 0, stripos($sql, 'returning')) . ')';
		$this->debug->show('<br>sql ohne returning: ' . $sql, Validierung::$write_debug);

		$this->debug->show('Sql für Prüfung geom_within_plan:<br>' . $sql, false);

		$result = @pg_query(
			$this->database->dbConn,
			$sql
		);

		if (!$result) {
			$this->debug->show('<br>sql ist nicht ausführbar: ' . $sql, Validierung::$write_debug);
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
					$this->debug->show('geometrie mit gid: ' . $row['gid'] . ' ist außerhalb vom Geltungsbereich des Plans', Validierung::$write_debug);
					$validierungsergebnis = new Validierungsergebnis($this->gui);
					$validierungsergebnis->create(
						array(
							'konvertierung_id' => $konvertierung->get('id'),
							'validierung_id' => $this->get('id'),
							'status' => ($row['distance'] > 100 ? 'Fehler' : 'Warnung'),
							'regel_id' => $regel->get('id'),
							'shape_gid' => $row['gid'],
							'msg' => 'Objekt mit' . ($sourcetype == 'gmlas' ? '' : ' gid=' . $row['gid']) . ' ist außerhalb des räumlichen Geltungsbereiches des Planes.' . ($row['distance'] > 100 ? ' Das Objekt ist mehr als 100 km entfernt.' : '')
						)
					);
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
		$this->debug->show('<br>' . ($all_within_plan ? 'Alle Geometrien innerhalb des Geltungsbereiches des Plans.' : 'Nicht alle Geometrien innerhalb des Geltungsbereiches des Plans.'), Validierung::$write_debug);
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
		$this->debug->show('<br>Validiere ob Geometrien in Bereich. (ToDo: Noch zu implementieren)', Validierung::$write_debug);
		$all_within_bereich = true;

		# SQL der Konvertierungsregel abfragen
		$sql = $regel->get_convert_sql($konvertierung->get('id'));

		# Spaltenname der Geometrie ermitteln
		$sourcetype = $regel->is_source_shape_or_gmlas($regel);
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';

		# Plantyp abfragen
		$bereichtype = $konvertierung->get_plan()->planartAbk . '_bereich';

		# Alles ab select extrahieren
		$sql = stristr($sql, 'select');
		$this->debug->show('<br>sql von select an: ' . $sql, Validierung::$write_debug);		

		# gid zur eindeutigen Identifizierung des Datensatzes (nicht bei gmlas) sowie within und distance zum select hinzufügen
		$sql = str_ireplace(
			'select',
			"select
				" . ($sourcetype == 'gmlas' ? '' : 'gid,') . "
				NOT st_within(" . $geometry_col . ", " . $bereichtype . ".geltungsbereich) AS ausserhalb,
				st_distance(ST_Transform(" . $geometry_col . ", " . $konvertierung->get('input_epsg') ."), ST_Transform(" . $bereichtype . ".geltungsbereich, " . $konvertierung->get('input_epsg') . "))/1000 AS distance,
			",
			$sql
		);
		$this->debug->show('<br>sql mit gid (nicht bei gmlas), within und distance: ' . $sql, Validierung::$write_debug);

		# Tabelle " . $bereichtype . " hinzufügen
		$sql = str_ireplace(
			'from',
			"from
				xplan_gml." . $bereichtype . " " . $bereichtype . ",
			",
			$sql
		);
		$this->debug->show('<br>sql mit " . $plantype . " Tabelle: ' . $sql, Validierung::$write_debug);

		# where klausel für bereich hinzufügen
		$sql = str_ireplace(
			'where',
			"where
				" . $bereichtype . ".gml_id = '" . $regel->get('bereich_gml_id') . "' AND
				" . $bereichtype . ".konvertierung_id = " . $konvertierung->get('id') . " AND 
				NOT st_within(" . $geometry_col . ", " . $bereichtype . ".geltungsbereich) AND (
			",
			$sql
		);
		$this->debug->show('<br>sql mit where Klausel für ' . $bereichtype . ': ' . $sql, Validierung::$write_debug);
		$sql = substr($sql, 0, stripos($sql, 'returning')) . ')';
		$this->debug->show('<br>sql ohne returning: ' . $sql, Validierung::$write_debug);

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
		$this->debug->show('Sql für Prüfung geom_within_plan:<br>' . $sql, false);
		$result = @pg_query(
			$this->database->dbConn,
			$sql
		);

		if (!$result) {
			$this->debug->show('<br>sql ist nicht ausführbar: ' . $sql, Validierung::$write_debug);
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
					$this->debug->show('geometrie mit gid: ' . $row['gid'] . ' ist außerhalb vom Planbereich', Validierung::$write_debug);
					$validierungsergebnis = new Validierungsergebnis($this->gui);
					$validierungsergebnis->create(
						array(
							'konvertierung_id' => $konvertierung->get('id'),
							'validierung_id' => $this->get('id'),
							'status' => ($row['distance'] > 100 ? 'Fehler' : 'Warnung'),
							'regel_id' => $regel->get('id'),
							'shape_gid' => $row['gid'],
							'msg' => 'Objekt mit gid=' . $row['gid'] . ' ist außerhalb des räumlichen Geltungsbereiches seines Planbereiches.' . ($row['distance'] > 100 ? ' Das Objekt ist mehr als 100 km entfernt.' : '')
						)
					);
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
		$this->debug->show('<br>' . ($all_within_plan ? 'Alle Geometrien innerhalb ihrer Planbereiche.' : 'Nicht alle Geometrien innerhalb ihrer Planbereiche.'), Validierung::$write_debug);
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
	* Ermittelt die faces, die zu keinem Polygon zugeordnet sind (Lücken)
	* @return array mit success boolean True wenn keine Überlappungen und Lücken gefunden wurden. 
	* wenn welche gefunden wurden, succes = false und eine err_msg, die angibt welche Objekte sich überlappen oder wo Lücken sind.
	*/
	function flaechenschluss_ueberlappungen() {
		$success = true;

		# prüft ob es faces gibt, die mehreren Geometrien zugeordnet sind
		$sql = "
			SELECT
			  string_agg(fo.gml_id::text, ', ') neighbours
			FROM
			  flaechenschluss_topology.relation r JOIN
			  xplankonverter.flaechenschlussobjekte fo ON r.topogeo_id = (fo.topo).id
			WHERE
				fo.konvertierung_id = " . $this->konvertierung_id . "
			GROUP BY r.element_id
			HAVING count(r.element_id) > 1
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
					'status' => ($success ? 'Erfolg' : 'Fehler'),
					'msg' => $msg
				)
			);
		}
		return $success;
	}

	function flaechenschluss_luecken() {
		$success = true;

		# prüft ob es faces gibt, die keiner Geometrie zugeordnet sind
		$sql = "
			SELECT DISTINCT
				string_agg(fo.gml_id::text, ', ') neighbours
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
			GROUP BY face_id
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
					'status' => ($success ? 'Erfolg' : 'Fehler'),
					'msg' => $msg
				)
			);
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
						xplan_gml." . $bedingung['class_name'] . "
					WHERE
						NOT (" . substr($this->get('functionsargumente'), 2, -2) . ") AND
						konvertierung_id = " . $konvertierung_id . "
				";
		}
		$this->debug->show('sql to find failed konformities: ' . $sql, false);
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
						. ' der Konformitätsbedinung Nr: ' . $konformitaetsbedingung->get('nummer') . ' ' . $konformitaetsbedingung->get('bezeichnung')
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
