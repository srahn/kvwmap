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

	function Validierung($gui) {
		#echo '<br>Create new Object Validierung';
		$this->PgObject($gui, Validierung::$schema, Validierung::$tableName);
		$this->konvertierung_id = 0;
	}

	public static	function find_by_id($gui, $by, $id) {
		$validierung = new Validierung($gui);
		$validierung->find_by($by, $id);
		return $validierung;
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
				'msg' => 'Es sind' . ($regeln_existieren ? '' : ' keine') . ' Regeln zur Konvertierung vorhanden.'
			)
		);
		return $regeln_existieren;
	}

	function sql_ausfuehrbar($result, $regel_id) {
		$ausfuehrbar = true;
		if (!$result) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => str_replace("'","''",@pg_last_error($this->database->dbConn)),
					'regel_id' => $regel_id
				)
			);
			$ausfuehrbar = false;
		}
		return $ausfuehrbar;
	}

	function sql_vorhanden($sql, $regel_id) {
		$this->debug->show('sql_vorhanden mit sql: ' . $sql . ' validieren.', Validierung::$write_debug);
		$vorhanden = true;
		if (empty($sql)) {
			$validierungsergebnis = new Validierungsergebnis($this->gui);
			$validierungsergebnis->create(
				array(
					'konvertierung_id' => $this->konvertierung_id,
					'validierung_id' => $this->get('id'),
					'status' => 'Fehler',
					'msg' => 'Das SQL-Statement ist leer.',
					'regel_id' => $regel_id
				)
			);
			$vorhanden = false;
		}
		return $vorhanden;
	}

	function alle_sql_ausfuehrbar($alle_ausfuehrbar) {
		if ($alle_ausfuehrbar) {
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
	function geometrie_vorhanden($sql, $regel_id) {
		$this->debug->show('validate geometrie_vorhanden mit sql: ' . $sql, Validierung::$write_debug);
		$geometrie_vorhanden = true;
		$sql = stristr($sql, 'select');
		$this->debug->show('sql von select an: ' . $sql, Validierung::$write_debug);

		$sql = str_ireplace(
			'select',
			"select gid,",
			$sql
		);
		$this->debug->show('sql mit gid: ' . $sql, Validierung::$write_debug);

		if (strpos(strtolower($sql), 'where') === false) {
			$sql .= ' where the_geom IS NULL';
		}
		else {
			$sql = str_ireplace(
				'where',
				"where the_geom IS NULL AND",
				$sql
			);
		}
		$this->debug->show('sql mit where Klausel: ' . $sql, Validierung::$write_debug);

		$sql = "SET search_path=xplan_shapes_{$this->konvertierung_id}, public; " . $sql;

		$this->debug->show('sql zur Prüfung: ' . $sql, Validierung::$write_debug);

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

		return $geometrie_vorhanden;
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
