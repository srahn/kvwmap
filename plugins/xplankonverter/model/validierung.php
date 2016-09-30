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

	function regel_existiert($num_regeln) {
		$validierungsergebnis = new Validierungsergebnis($this->gui);
		$validierungsergebnis->create(
			array(
				'konvertierung_id' => $this->konvertierung_id,
				'validierung_id' => $this->get('id'),
				'status' => ($num_regeln == 0 ? 'Warung' : 'Erfolg'),
				'msg' => 'Es sind Regeln zur Konvertierung vorhanden.'
			)
		);
	}

	function sql_ausfuehrbar($result, $regel_id) {
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
		}
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
