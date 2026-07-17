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

	function __construct($database, $layerepsg, $clientepsg, $oid_attribute) {
		global $debug;
		$this->debug=$debug;
		$this->database=$database;
		$this->clientepsg = $clientepsg;
		$this->layerepsg = $layerepsg;
		$this->oid_attribute = $oid_attribute;
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

	/**
	* Funktion returns wkb_geometry from loc_x, loc_y and dimension given in options
	* transformed from client to layerepsg of this pointeditor object.
	* @params array options with loc_x = East, loc_y = North and dimension = 3 if 3D Point
	* @return array success = false and err_msg if error in database request and true and
	* wkb_geometry with the requested WKB Geometry of that point
	*/
	function get_wkb_geometry($options) {
		$sql = "
			SELECT
				ST_Transform(
					St_GeomFromText('POINT(" . $options['loc_x'] . " " . $options['loc_y'] . ($options['dimension'] == 3 ? " 0" : "") . ")', " . $this->clientepsg . "),
					" . $this->layerepsg . "
				) AS wkb_geometry
		";
		#echo '<p>SQL zum Berechnen der WKB-Geometrie des Punktes: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 1, true);
		if ($ret[0]) {
			# Fehler beim Berechnen der WKB-Geometrie in der Datenbank
			return array(
				'success' => false,
				'err_msg' => 'Auf Grund eines Fehlers bei der Anfrage an die Datenbank konnte die Geometrie des Punktes nicht berechnet werden!<br>' . $ret[1]
			);
		}
		else {
			$rs = pg_fetch_assoc($ret['query']);
			return array(
				'success' => true,
				'wkb_geometry' => $rs['wkb_geometry']
			);
		}
	}

	function eintragenPunkt($pointx, $pointy, $oid, $tablename, $columnname, $dimension, $kvps) {
		if (is_array($kvps) AND array_key_exists('success', $kvps) AND $kvps['success'] === false) {
			return $kvps;
		}
		if ($pointx == '') {
			$wkb_geometry = 'NULL';
		}
		else {
			$ret = $this->get_wkb_geometry(array(
				'loc_x' => $pointx,
				'loc_y' => $pointy,
				'dimension' => $dimension
			));
			if (!$ret['success']) {
				return $ret;
			}
			$wkb_geometry = "'" . $ret['wkb_geometry'] . "'";
		}
		$kvps[] = $columnname . ' = ' . $wkb_geometry;
		$sql = "
			UPDATE " . $tablename . "
			SET " . implode(', ', $kvps) . "
			WHERE ".$this->oid_attribute." = '" . $oid . "'
		";
		#echo '<p>SQL zum Updaten von Punktgeometrie: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 1, true);
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
				".$this->oid_attribute." = '" . $oid . "'
		";
		$ret = $this->database->execSQL($sql, 4, 0);
		$point = pg_fetch_array($ret[1]);
		return $point;
	}
}
?>