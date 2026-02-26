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
# Klasse Multigeomeditor #
#############################

class multigeomeditor {

  function __construct($database, $layerepsg, $clientepsg, $oid_attribute) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->clientepsg = $clientepsg;
    $this->layerepsg = $layerepsg;
    $this->oid_attribute = $oid_attribute;
  }

  function zoomToGeom($oid, $tablename, $columnname,  $border, $schemaname = '') {
  	# Eine Variante mit der nur einmal transformiert wird
  	$sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
  	$sql.=" FROM (SELECT box2D(st_transform(".$columnname.", ".$this->clientepsg.")) as bbox";
  	$sql.=" FROM " . ($schemaname != '' ? $schemaname . '.' : '') .pg_quote($tablename)." WHERE ".$this->oid_attribute." = '".$oid."') AS foo";
    $ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = rectObj(
			$rs['minx'],
			$rs['miny'],			
			$rs['maxx'],
			$rs['maxy']
		);
		if($border == NULL AND defined('ZOOMBUFFER') AND ZOOMBUFFER > 0)$border = ZOOMBUFFER;
		if($border != NULL){
			if($this->clientepsg == 4326)$border = $border/10000;
			$randx=$randy=$border;
		}
		else{
			$randx=($rect->maxx-$rect->minx)*0.1;
			$randy=($rect->maxy-$rect->miny)*0.1;
		}
    $rect->minx -= $randx;
    $rect->miny -= $randy;
    $rect->maxx += $randx;
    $rect->maxy += $randy;
    return $rect;
  }

	function pruefeEingabedaten($newpathwkt) {
		$ret[1] = '';
		$ret[0] = 0;
		if ($newpathwkt != '') {
			if ($newpathwkt == '\\') {
				$ret[1] = 'Die Geometrie ist fehlerhaft und kann nicht gespeichert werden:<p>';
				$ret[0] = 1;
			}
			else {
				$sql = "
					SELECT st_isvalid(st_geomfromtext('" . $newpathwkt . "'))
				";
				$ret = $this->database->execSQL($sql, 4, 0);
				$valid = pg_fetch_row($ret[1]);
				if ($valid[0] == 'f') {
					$sql = "
						SELECT st_isvalidreason(st_geomfromtext('".$newpathwkt."'))
					";
					$ret = $this->database->execSQL($sql, 4, 0);
					$reason = pg_fetch_row($ret[1]);
					$ret[1] = 'Die Geometrie ist fehlerhaft und kann nicht gespeichert werden:<p>' . $reason[0];
					$ret[0] = 1;
				}
			}
		}
		return $ret;
	}

	/**
	 * Function returns a WKT multigeometry from a singlegeometry, transformed from client to layer system.
	 * @params string $geom The geometry as comma separated text
	 * @return string The WKT Multigeometry
	 */
	function get_multigeom($geom) {
		return "ST_Multi(ST_GeometryFromText('" . $geom . "', " . $this->clientepsg . "))";
	}

	/**
	 * Function returns a WKT singlegeometry from a single- or multigeometry by using only the first geometry, transformed from client to layer system.
	 * @params string $geom The geometry as comma separated text
	 * @return string The WKT Geometry
	 */
	function get_geom($geom) {
		return "ST_geometryN(ST_GeometryFromText('" . $geom . "', " . $this->clientepsg . "), 1)";
	}

	/**
	 * Function returns wkb_geometry from geometry and geomtype given in options
	 * transformed from client to layerepsg of this object.
	 * @params array options with geom = WKT-Geometry and geomtype = Postgis-Geometrytype string
	 * @return array success = false and err_msg if error in database request and true and
	 * wkb_geometry with the requested WKB Geometry
	 */
	function get_wkb_geometry($options) {
		$sql = "
			SELECT
				ST_Transform(
					" . (in_array(strtoupper(substr($options['geomtype'], 0, 5)), ['MULTI', 'GEOME']) ? $this->get_multigeom($options['geom']) : $this->get_geom($options['geom'])) . ",					
					" . $this->layerepsg . "
				) AS wkb_geometry
		";
		#echo '<p>SQL zum Berechnen der WKB-Geometrie: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 1, true);
		if ($ret[0]) {
			# Fehler beim Berechnen der WKB-Geometrie in der Datenbank
			return array(
				'success' => false,
				'err_msg' => 'Auf Grund eines Fehlers bei der Anfrage an die Datenbank konnte die Geometrie nicht berechnet werden!<br>' . $ret[1]
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

	function updateGeom($polygon, $oid, $tablename, $columnname, $geomtype, $kvps) {
		if (is_array($kvps) AND array_key_exists('success', $kvps) AND $kvps['success'] === false) {
			return $kvps;
		}
		if ($polygon == '') {
			$wkb_geometry = 'NULL';
		}
		else {
			$ret = $this->get_wkb_geometry(array(
				'geomtype' => $geomtype,
				'geom' => $polygon
			));
			if (!$ret['success']) {
				return $ret;
			}
			$wkb_geometry = "'" . $ret['wkb_geometry'] . "'";
		}
		$kvps[] = pg_quote($columnname) . ' = ' . $wkb_geometry;
		$sql = "
			UPDATE " . pg_quote($tablename) . "
			SET " . implode(', ', $kvps) . "
			WHERE " . pg_quote($this->oid_attribute) . " = '" . $oid . "'
		";
		#echo '<p>SQL zum Updaten von Geometrie: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 1, true);
		if (!$ret[0]) {
			if (pg_affected_rows($ret[1]) == 0) {
				$ret[0] = 1;
				$result = pg_fetch_row($ret[1]);
				$ret[1] = 'Eintrag nicht erfolgreich.' . $result[0];
			}
		}
		return $ret;
	}

	function getGeom($oid, $tablename, $columnname, $schemaname = ''){
		$sql = "
			SELECT 
				st_assvg(st_transform(st_union(".$columnname."),".$this->clientepsg."), 0, 15) AS svggeom, 
				st_astext(st_transform(st_union(".$columnname."),".$this->clientepsg.")) AS wktgeom, 
				st_numGeometries(st_union(".$columnname.")) as numgeometries 
			FROM 
				" . ($schemaname != '' ? $schemaname . '.' : '') . pg_quote($tablename) . "
			WHERE 
				" . $this->oid_attribute . " = '" . $oid. "'";
		#echo '<br>sql: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		$geom = pg_fetch_array($ret[1]);
		$geom['svggeom'] = transformCoordsSVG($geom['svggeom']);
		return $geom;
	}
}
?>
