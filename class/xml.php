<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 # 
# Copyright (C) 2007  Peter Korduan                               #
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
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de                #
###################################################################
#  xml.php  Klassen zur Verarbeitung von XML Dokumenten           #
###################################################################
#
# xml2Array
# hidaDocument
#
#

class xml2Array {
	#----------------------------------------------------------------
	# Source of xml2Array from php@b635.com Comment from 23-Jun-2007 07:17
	# see: http://de.php.net/manual/en/function.xml-parse.php	
   
  var $arrOutput = array();
  var $resParser;
  var $strXmlData;
   
  function parse($strInputXML) {
    $this->resParser = xml_parser_create ();
    xml_set_object($this->resParser,$this);
    xml_set_element_handler($this->resParser, "tagOpen", "tagClosed");
    xml_set_character_data_handler($this->resParser, "tagData");
    $this->strXmlData = xml_parse($this->resParser,$strInputXML );
    if(!$this->strXmlData) {
      die(sprintf("XML error: %s at line %d",
      xml_error_string(xml_get_error_code($this->resParser)),
      xml_get_current_line_number($this->resParser)));
    }
    xml_parser_free($this->resParser);
    return $this->arrOutput;
  }
  
  function tagOpen($parser, $name, $attrs) {
    $tag=array("name"=>$name,"attrs"=>$attrs);
    array_push($this->arrOutput,$tag);
  }
   
  function tagData($parser, $tagData) {
    if(trim($tagData)) {
      if(isset($this->arrOutput[count($this->arrOutput)-1]['tagData'])) {
        $this->arrOutput[count($this->arrOutput)-1]['tagData'] .= $tagData;
    	}
      else {
        $this->arrOutput[count($this->arrOutput)-1]['tagData'] = $tagData;
      }
    }
  }
   
  function tagClosed($parser, $name) {
    $this->arrOutput[count($this->arrOutput)-2]['children'][] = $this->arrOutput[count($this->arrOutput)-1];
    array_pop($this->arrOutput);
  }
}

#############################################################################
# Klasse zur Verarbeitung von HIDA XML Exportdateien
#############################################################################
class hidaDocument {
  function hidaDocument($file) {
  	# Zuweisen der Datei in der das HIDA Export Dokument ist
    $this->file=file_get_contents(DEFAULT_DENKMAL_IMPORT_FILE);
  }
  
  function loadDocInDatabase() {
    # Einlesen der XML-Datei in ein Array
		$rootElements=$this->getElements();
		# Extrahieren der Unterelemente des Wurzelelementes DocumentSet
		$docs=$rootElements[0]['children'];
		$this->openDatabase();    
		# Für alle Elemente unterhalt DocumentSet außer ContentInfo   
		for ($nrDoc=1;$nrDoc<count($docs);$nrDoc++) {
			$doc=$docs[$nrDoc];
			# Zuweisen des DocKey
			$dockey=trim(substr($doc['attrs']['DOCKEY'],4));
			# Zuweisen des Blocks des Documents, enthält alle Fields und Blöcke des Documents
			$docBlock=$doc['children'][0];
			# Behandeln des Blockes für die Ausgabe
			#   dabei übergabe des docKey und des Blocks
			$this->outputBlock($dockey,NULL,$docBlock);
		}
		$this->closeDatabase();
  }

  function getElements() {
  	# Einlesen der XML Datei und erzeugen eines Array mit den Dokumentelementen
	  $xmlDocument=new xml2Array();
	  $array=$xmlDocument->parse($this->file);
	  # Zurückgabe des Arrays
	  return $array;
  }
	
  function outputBlock($dockey,$blockKey,$block) {
		# Zuweisen der Elemente des Blocks
		$elements=$block['children'];
		# Für alle Elemente des Blocks
		for ($nrElements=0;$nrElements<count($elements);$nrElements++) {
			$element=$elements[$nrElements];
			$elementName=$element['name'];
			switch ($elementName) {
			  case 'H1:FIELD' : {
					# Wenn Element ein Block ist
					# Ausgabe der Daten des Elementes
					$this->outputFields($dockey,$blockKey,NULL,$element['attrs']['TYPE'],$element['attrs']['VALUE']);
					if (isset($element['children'])) {
						# Wenn das Element Unterelemente hat
						# Zuweisen der ID des Elternelementes
						$parentElement=$element['attrs']['TYPE'];
						# Zuweisen der Unterelemente
						$subElements=$element['children'];
						# Für jedes Unterelement
						for ($nrSubElements=0;$nrSubElements<count($subElements);$nrSubElements++) {
							# Zuweisen des Unterelements
							$subElement=$subElements[$nrSubElements];
							# Ausgabe der Daten des Unterelements
							$this->outputFields($dockey,$blockKey,$parentElement,$subElement['attrs']['TYPE'],$subElement['attrs']['VALUE']);
						}
					}
			  } break;
			  case 'H1:BLOCK' : {
					# Element ist ein Block
					# Zuweisen des Key des Blocks
					$blockKey=$element['children'][0]['attrs']['VALUE'];
					# Behandeln des Blocks für die Ausgabe
					#   dabei übergeben des zugehörigen doc- und block Keys und des Blockelements
					$this->outputBlock($dockey,$blockKey,$element);
			  } break;
			  default : {
			  	# ignorieren
			  }
			} # ende der Unterscheidung der Elemente des Blocks
		} # ende der Behandlung der Elemente des Blocks
	}
	
	function outputFields($docKey,$blockKey,$parentField,$fieldKey,$fieldValue) {
		# Schreiben der Werte in die Datenbank
		$ret=$this->writeFieldToDatabase($docKey,$blockKey,$parentField,$fieldKey,$fieldValue);
		if ($ret[0]) {
			$Fehlermeldung ='<br>Abbruch beim Einlesen der HIDA DAten in die Datenbank<br>';
      $Fehlermeldung.='beim Datensatz: '.$docKey.','.$blockKey.','.$parentField.','.$fieldKey.','.$fieldValue;
      return $Fehlermeldung;
 		}
		else {
			$this->fields[]=array('docKey' => $docKey,'blockKey' => $blockKey,'parentField'=>$parentField,'fieldKey'=>$fieldKey,'fieldValue'=>$fieldValue);			
		}	
	}
	
	function openDatabase() {
    $this->dbConn=pg_connect('dbname='.DENKMAL_DB_DATABASENAME.' user='.DENKMAL_DB_USERNAME.' password='.DENKMAL_DB_PASSWORD);
  }		
	
	function closeDatabase() {
		pg_close($this->dbConn);
	}
	
	function writeFieldToDatabase($docKey,$blockKey,$parentField,$fieldKey,$fieldValue) {
		# Schreibt die Daten in die Datentabelle der folgenden Struktur#
		/*-- Table: dm_fields

		-- DROP TABLE dm_fields;
		
		CREATE TABLE dm_fields
		(
		  dockey varchar(14),
		  blockkey varchar(14),
		  fieldparent varchar(4),
		  fieldkey varchar(4),
		  value text
		) 
		WITH OIDS;
		ALTER TABLE dm_fields OWNER TO kvwmap;
		*/
		
	  $sql="INSERT INTO dm_fields VALUES ('".trim($docKey)."','".trim($blockKey)."','".trim($parentField)."','".trim($fieldKey)."','".trim($fieldValue)."')";
    $query=pg_query($this->dbConn,$sql);
    if ($query==0) {
      $ret[0]=1;
      $ret[1]="Fehler bei SQL Anweisung:<br>".$sql."<br>".pg_result_error($query);
      echo $ret[1];
    }
	  else {
	  	# Abfrage wurde erfolgreich ausgeführt
	    $ret[0]=0;
	    $ret[1]=$query;
	  }
	  return $ret;
	}
}
?>