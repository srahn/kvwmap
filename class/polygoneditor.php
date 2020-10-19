<?php
###################################################################
# kvwmap - Kartenserver f�r Kreisverwaltungen                     #
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
# Klasse Polygoneditor #
#############################

class polygoneditor {

  function __construct($database, $layerepsg, $clientepsg, $oid_attribute) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->clientepsg = $clientepsg;
    $this->layerepsg = $layerepsg;
    $this->oid_attribute = $oid_attribute;
  }

  function zoomTopolygon($oid, $tablename, $columnname,  $border, $schemaname = '') {
  	# Eine Variante mit der nur einmal transformiert wird
  	$sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
  	$sql.=" FROM (SELECT box2D(st_transform(".$columnname.", ".$this->clientepsg.")) as bbox";
  	$sql.=" FROM " . ($schemaname != '' ? $schemaname . '.' : '') .pg_quote($tablename)." WHERE ".$this->oid_attribute." = '".$oid."') AS foo";
    $ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = ms_newRectObj();
    $rect->minx=$rs['minx'];
    $rect->maxx=$rs['maxx'];
    $rect->miny=$rs['miny'];
    $rect->maxy=$rs['maxy'];
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
				$ret[1] = 'Die Geometrie des Polygons ist fehlerhaft und kann nicht gespeichert werden:<p>';
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
					$ret[1] = 'Die Geometrie des Polygons ist fehlerhaft und kann nicht gespeichert werden:<p>' . $reason[0];
					$ret[0] = 1;
				}
			}
		}
		return $ret;
	}

  function eintragenFlaeche($umring, $oid, $tablename, $columnname, $geomtype) {
		$geometry = ($umring == '' ? "NULL" : "ST_Transform(ST_GeometryFromText('" . $umring . "', " . $this->clientepsg . "), " . $this->layerepsg . ")");
		$sql = "
			UPDATE " . $tablename . "
			SET " . $columnname . " = " . (substr($geomtype, 0, 5) == 'MULTI' ? "ST_Multi(" . $geometry . ")" : $geometry) . "
			WHERE ".$this->oid_attribute." = " . quote($oid) . "
		";
		$ret = $this->database->execSQL($sql, 4, 1, true);
		if (!$ret['success']) {
			if (is_resource($ret[1]) AND pg_affected_rows($ret[1]) == 0) {
				$result = pg_fetch_row($ret[1]);
				$ret[1] = 'Eintrag der Geometrie nicht erfolgreich!' . $result[0];
			}
			else {
				if ($last_notice = $msg = pg_last_notice($this->database->dbConn)) {
					if ($notice_result = json_decode(substr($last_notice, strpos($last_notice, '{'), strpos($last_notice, '}') - strpos($last_notice, '{') + 1), true)) {
						$msg = $notice_result['msg'];
					}
					$ret[3] = $msg;
				}
				else {
					$ret[1] = sql_err_msg('Auf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!', $sql, $ret[1], 'error_div_' . rand(1, 99999));
				}
			}
		}
		return $ret;
	}

	function getpolygon($oid, $tablename, $columnname, $extent, $schemaname = ''){
		$sql = "SELECT st_assvg(st_transform(st_union(".$columnname."),".$this->clientepsg."), 0, 15) AS svggeom, st_astext(st_transform(st_union(".$columnname."),".$this->clientepsg.")) AS wktgeom, st_numGeometries(st_union(".$columnname.")) as numgeometries FROM " . ($schemaname != '' ? $schemaname . '.' : '') . pg_quote($tablename);
		if($oid != NULL)$sql .= " WHERE ".$this->oid_attribute." = ".quote($oid);
		#echo '<br>sql: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		$polygon = pg_fetch_array($ret[1]);
		$polygon['svggeom'] = transformCoordsSVG($polygon['svggeom']);
		return $polygon;
	}
}
?>
