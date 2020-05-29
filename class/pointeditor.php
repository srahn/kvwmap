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
# Klasse Pointeditor #
#############################

class pointeditor {

	function __construct($database, $layerepsg, $clientepsg) {
		global $debug;
		$this->debug=$debug;
		$this->database=$database;
		$this->clientepsg = $clientepsg;
		$this->layerepsg = $layerepsg;
	}

	function pruefeEingabedaten($locx, $locy) {
		if ($locx == '' OR $locy == '') {
			$ret[1] = 'Es wurden keine Koordinaten übergeben!';
		}
		else {
			$ret[1] = '';
			$ret[0] = 0;
		}
		return $ret; 
	}

	function eintragenPunkt($pointx, $pointy, $oid, $tablename, $columnname, $dimension) {
		$success = true;
		if ($pointx == '') {
			$sql = "
				UPDATE " . $tablename . "
				SET " . $columnname . " = NULL
				WHERE oid = " . $oid . "
			";
		}
		else {
			# Zielgeometrie bestimmen
			$sql = "
				SELECT
					ST_Transform(
						St_GeomFromText('POINT(" . $pointx . " " . $pointy . ($dimension == 3 ? " 0" : "") . ")', " . $this->clientepsg . "),
						" . $this->layerepsg . "
					) wkb_geometry
			";
			#echo '<p>SQL zum Berechnen der WKB-Geometrie des Punktes: ' . $sql;
			$ret = $this->database->execSQL($sql, 4, 1, true);
			if ($ret[0]) {
				# Fehler beim Berechnen der WKB-Geometrie in der Datenbank
				$ret[1] = 'Auf Grund eines Fehlers bei der Anfrage an die Datenbank konnte die Geometrie des Punktes nicht berechnet werden!<br>' . $ret[1];
				$success = false;
			}
			else {
				$rs = pg_fetch_assoc($ret['query']);
				$sql = "
					UPDATE " . $tablename . "
					SET " . $columnname . " = '" . $rs['wkb_geometry'] . "'
					WHERE oid = " . $oid . "
				";
			}
		}
		if ($success) {
			#echo '<p>SQL zum Updaten von Punktgeometrie: ' . $sql;
			$ret = $this->database->execSQL($sql, 4, 1, true);
			if ($ret[0]) {
				# Fehler beim Eintragen in Datenbank
				$ret[1] = 'Auf Grund eines Datenbankfehlers konnte der Punkt nicht eingetragen werden!<br>' . $ret[1];
			}
			else {
				if ($last_notice = $msg = pg_last_notice($this->database->dbConn)){
					if($notice_result = json_decode(substr($last_notice, strpos($last_notice, '{'), strpos($last_notice, '}') - strpos($last_notice, '{') + 1), true)){
						$msg = $notice_result['msg'];
					}
					$ret[3] = $msg;
				}
			}
		}
		return $ret;
	}

	function getpoint($oid, $tablename, $columnname, $angle_column = NULL) {
		$sql = "
			SELECT
				st_x(st_transform(" . $columnname . ", " . $this->clientepsg . ")) AS pointx,
				st_y(st_transform(".$columnname.",".$this->clientepsg.")) AS pointy" .
				($angle_column != '' ? ", " . $angle_column . " as angle" : "") . "
			FROM
				" . $tablename . "
			WHERE
				oid = " . $oid . "
		";
		$ret = $this->database->execSQL($sql, 4, 0);
		$point = pg_fetch_array($ret[1]);
		return $point;
	}
}
?>