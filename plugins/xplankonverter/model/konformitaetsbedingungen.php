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

class Konformitaetsbedingung extends PgObject {


	static $schema = 'xplankonverter';
	static $tableName = 'konformitaetsbedingungen';
	static $write_debug = false;

	function __construct($gui) {
		#echo '<br>Create new Object Konformitaetsbedingungen';
		parent::__construct($gui, Konformitaetsbedingung::$schema, Konformitaetsbedingung::$tableName);
		$this->identifiers = array(
			array(
				'column' => 'nummer',
				'type' => 'character varying'
			),
			array(
				'column' => 'version_von',
				'type' => 'character varying'
			)
		);
		$this->validierungen = array();
	}

	public static	function find_by_id($gui, $nummer, $version_von) {
		$bedingung = new Konformitaetsbedingung($gui);
		$bedingung->find_by_ids($nummer, $version_von);
		$bedingung->getValidierungen();
		return $bedingung;
	}

	/**
	* function get validierungen of this konformitaetsbedingung
	*/
	function getValidierungen() {
		$validierung = new Validierung($this->gui);
		$where =  $validierung->get_id_condition(array($this->get('nummer'), $this->get('version_von')));
		$this->validierungen = $validierung->find_where($where);
		return $this->validierungen;
	}

}

?>
