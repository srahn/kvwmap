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
# Klasse PgObject #
#############################

class PgObject {
  
  function PgObject($pgDatabase, $schema, $tableName) {
    global $debug;
    $this->debug=$debug;
    $this->pgDatabase = $pgDatabase;
    $this->schema = $schema;
    $this->tableName = $tableName;
    $this->qualifiedTableName = $schema . '.' . $tableName;
    $this->data = array();
    $this->debug = false;
  }

  function find_by_id($id) {
    $sql = "
      SELECT
        *
      FROM
        " . $this->qualifiedTableName . "
      WHERE
        id = " . $id . "
    ";
    #echo '<p>sql: ' . $sql;
    $query = pg_query($this->pgDatabase->dbConn, $sql);
    $this->data = pg_fetch_assoc($query);
  }

  function getAttributes() {
    return array_keys($this->data);
  }

  function getValues() {
    return array_values($this->data);
  }

  function get($attribute) {
    return $this->data[$attribute];
  }

  function set($attribute, $value) {
    $this->data[$attribute] = $value;
    return $value;
  }

  function save() {
    $sql = "
      INSERT INTO " . $this->qualifiedTableName . "(
        " . implode(', ', $this->getAttributes()) . "
      )
      VALUES (
        '" . implode("', '", $this->getValues()) . "'
      )
      RETURNING id
    ";
    #echo '<p>sql: ' . $sql;
    $query = pg_query($this->pgDatabase->dbConn, $sql);
    $row = pg_fetch_assoc($query);
    $this->set('id', $row['id']);
    return $this->get('id');
  }

  function delete() {
    $sql = "
      DELETE
      FROM
        " . $this->qualifiedTableName . "
      WHERE
        id = " . $this->get('id') . "
    ";
    #echo '<p>sql: ' . $sql;
    $result = pg_query($this->pgDatabase->dbConn, $sql);

    # this must have been happen when GLE is used
/*  
    $oid = $this->get('oid');
    $GUI->formvars['chosen_layer_id'] = $layer_id;
    $GUI->formvars['checkbox_names_' . $layer_id] = 'check;shapefiles;shapefiles;' . $oid;
    $GUI->formvars['check;shapefiles;shapefiles;' . $oid] = 'on';
    $GUI->layer_Datensaetze_loeschen(false);
*/
    return $result;
  }

  function debug($msg) {
    if ($this->debug)
      echo $msg;
  }
}
?>
