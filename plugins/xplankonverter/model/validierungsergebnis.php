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
# Klasse Validierungsergebnis #
#############################

class Validierungsergebnis extends PgObject {

	
	static $schema = 'xplankonverter';
	static $tableName = 'validierungsergebnisse';

	function __construct($gui) {
		#echo '<br>Create new Object Validierungsergebnis';
		parent::__construct($gui, Validierungsergebnis::$schema, Validierungsergebnis::$tableName);
		$this->konvertierung_id = 0;
	}

	public static function delete_by_id($gui, $by, $id) {
		$validierung = new Validierungsergebnis($gui);
		$validierung->delete_by($by, $id);
	}
}

?>
