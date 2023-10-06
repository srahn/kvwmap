<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2008  Peter Korduan                               #
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
# users.php Klassenbibliothek zu Nutzern und Stellen #
######################################################
# Copyright (C) 2008  Peter Korduan
# This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
#
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
#
############################################################################################
class funktion {

	function __construct($database) {
		global $debug;
		$this->debug=$debug;
		$this->database=$database;
	}
  
	function getFunktionen($id, $order, $stelle_id = 0, $admin_id = 0) {
		global $admin_stellen;
		$where = array();
		$more_from = '';

		if ($admin_id > 0 AND !in_array($stelle_id, $admin_stellen)) {
			$more_from = "
				JOIN u_funktion2stelle f2s ON f.id = f2s.funktion_id
				JOIN rolle r ON r.stelle_id = f2s.stelle_id
			";
			$where[] = "r.user_id = " . $admin_id;
		}

		if ($id > 0) {
			$where[] = 'f.id = ' . $id;
		}

		if ($order != '') {
			$order = ' ORDER BY ' . replace_semicolon($order);
		}

		$sql = "
			SELECT DISTINCT
				f.*
			FROM
				u_funktionen f" .
				$more_from .
				(count($where) > 0 ? " WHERE " . implode(' AND ', $where) : "") .
			$order . "
		";
		#echo '<br>sql: ' . $sql;

		/*
		$sql ='SELECT * FROM u_funktionen WHERE 1=1';
		if ($id>0) {
		$sql.=' AND id='.$id;
		}
		if ($order!='') {
		$sql.=' ORDER BY ' . replace_semicolon($order);
		}
		*/
		$this->debug->write("<p>file:users.php class:funktion->getFunktionen - Abfragen einer oder aller Funktionen:<br>".$sql,4);
    $ret1 = $this->database->execSQL($sql, 4, 1);
    if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = $this->database->result->fetch_assoc()){
			$funktionen[]=$rs;
		}
		return $funktionen;
	}
  
	function NeuAnlegen($formvars){
		$sql = "INSERT INTO u_funktionen SET ";
		if($formvars['id']){
			$sql.= "id = ".(int)$formvars['id']."," ;
		}
		$sql.= "bezeichnung = '".$formvars['bezeichnung']."'";
		$ret=$this->database->execSQL($sql,4, 1);
		$ret[1] = $this->database->mysqli->insert_id;
		return $ret;
	}
  
	function Aendern($formvars){
		$sql = "UPDATE u_funktionen SET id = ".(int)$formvars['id'].", bezeichnung = '".$formvars['bezeichnung']."' ";
		$sql.= "WHERE id = ".(int)$formvars['selected_function_id'];
		$ret=$this->database->execSQL($sql,4, 1);
	}
  
	function Loeschen($formvars){
		$sql = "DELETE FROM u_funktionen ";
		$sql.= "WHERE id = ".(int)$formvars['selected_function_id'];
		$ret=$this->database->execSQL($sql,4, 1);
		$sql = "DELETE FROM u_funktion2stelle ";
		$sql.= "WHERE funktion_id = ".(int)$formvars['selected_function_id'];
		$ret=$this->database->execSQL($sql,4, 1);
	}
}
?>
