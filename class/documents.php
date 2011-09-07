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
#  documents.php  Klasse zur Dokumentenverwaltung                 #
###################################################################

#-----------------------------------------------------------------------------------------------------------------
class textdocument {
  ###################### Liste der Funktionen ####################################
  # spatialDocIndexing($path,$filepattern,$recursive);
  # convertToText($filename);
  # pdftohtml($filename);
  # pdftotxt($filename);
  # loadStopWords($stoplistfilepointer);
  # loadDocumentWords($documentfilepointer);
  # $doc_id=insertDocumentProperties($filename);
  # spatialWordFilter($doc_id);
  # insertDistinctWords();
  # insertTempWord($word);
  # deleteAllWords();
  # 
  ################################################################################
  var $doc_id;
  var $filelist;
  var $searchpath;
  var $recursive;
  var $stopwordlist;

  function textdocument($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }

  function spatialDocIndexing($path,$filepattern,$recursive,$update){
    $this->searchpath=$path;
    # Stopwörter laden
    $ret=$this->loadStopWords(STOPWORDFILE);
    if ($ret[0]) {
      echo $ret[1];
      return;
    }
    #var_dump($this->stopwordlist);

    # lesen der Dateinamen des Pfades mit gegebenen Pattern
    # todo Schleife
    $anzFiles=1;
    $filelist[0]["filename"]=$filepattern;
    for ($i=0;$i<$anzFiles;$i++) {
      # Abfragen ob Dokument schon existiert
      if ($this->isDocumentRegistered($filelist[$i]["filename"])) {
        # Hier steht was passiert, wenn eine Datei schon mal erfasst wurde
        if ($update) {
          # Einträge in doc_doc2geonames aktualisieren
          # löschen
          $ret=$this->deleteDocToGeoname($this->doc_id);
          # neu eintragen
          # konvertieren der gefundenen Datei in Textformat
          echo '<br>Datei: '.$this->searchpath.$filelist[$i]["filename"].' wird in Textdatei konvertiert.';
          $ret=$this->convertToText($filelist[$i]["filename"]);
          $textdocumentname=$ret[1];
          # Eintragen der Eigenschaften der bearbeiteten Datei
          $ret=$this->updateDocumentProperties($this->doc_id,$filelist[$i]["filename"]);
          # Laden der Wörter aus der konvertierten Datei
          $ret=$this->loadDocumentWords($textdocumentname);
          # Join der gefundenen Wörter mit dem Gazetteer in der Datenbank
          # und eintragen der gefundenen raumbezogenen Wörter zusammen mit Doc_id 
          $ret=$this->insertDocToGeoname($this->doc_id);
          echo '<br>Räumliche Begriffe zum Dokument aktualisiert.';
        }
      }
      else {
        # konvertieren der gefundenen Datei in Textformat
        echo '<br>Datei: '.$this->searchpath.$filelist[$i]["filename"].' wird in Textdatei konvertiert.';     
        $ret=$this->convertToText($filelist[$i]["filename"]);
        $textdocumentname=$ret[1];
        # Eintragen der Eigenschaften der bearbeiteten Datei
        $ret=$this->insertDocumentProperties($filelist[$i]["filename"],$time);
        # Laden der Wörter aus der konvertierten Datei
        $ret=$this->loadDocumentWords($textdocumentname);
        if ($ret[0]) {
          echo $ret[1];
        }
        # Join der gefundenen Wörter mit dem Gazetteer in der Datenbank
        # und eintragen der gefundenen raumbezogenen Wörter zusammen mit Doc_id 
        $ret=$this->insertDocToGeoname($this->doc_id);
        echo '<br>Räumliche Begriffe dem Dokument zugeordnet.';
      }
    }
    if ($recursive) {
      # lese alle unterverzeichnisse und rufe für jedes spatialDocSearch auf
    }
  }
  
  function loadStopWords($stopwordfile) {
    if ($lines = file($stopwordfile)) {
      foreach ($lines as $value) {
        $this->stopwordlist[trim($value)]=true;
      }
      $ret[0]=0;
    }
    else {
      $ret[0]=1;
      $ret[1]='Fehler beim Einlesen der Stopworddatei: '.$stopwordfile.' Prüfen Sie ob die Datei vorhanden ist und Leserechte für Apache eingestellt sind.';
    }
    return $ret;
  } 
  
  function convertToText($filename) {
    $dateiname = explode('.',$filename);
    $dateityp=$dateiname[1];
    switch ($dateityp) {
      case "pdf" : {
        $ret=$this->pdftotext($filename);
      } break;
    }
    echo '<br>Datei: '.$ret[1].' erstellt.';
    return $ret;
  }
  
  function pdftotext($filename) {
    exec("pdftotext ".$this->searchpath.$filename);
    $ret[0]=0;
    $name=explode('.',$filename);
    $ret[1]=$name[0].".txt";
    return $ret;
  }
  
  function pdftohtml($filename) {
    exec("pdftohtml ".$this->searchpath.$filename);
    $ret[0]=0;
    $name=explode('.',$filename);
    $ret[1]=$name[0]."s.html";
    return $ret;
  }
  
  function loadDocumentWords($filename) {
    $fp = fopen($this->searchpath.$filename,"r");
    echo '<br>Datei: '.$this->searchpath.$filename.' zum Wörtersuchen geöffnet.';
    if ($fp) {
      $this->deleteAllWords();
      echo '<br>Alte Wörterliste gelöscht.';
      $anzWords=0;
      while (!feof($fp)) {
        $line = fgets($fp);
        if (!trim($line)=='') {
          $wordlist=explode(' ',$line);
          foreach ($wordlist as $word) {
            $word=trim($word);
            $word=trim($word,'.');
            $word=trim($word,':');
            $word=trim($word,',');
            $word=trim($word,';');
            $word=trim($word,'\'');
            $word=trim($word,'"');
            if ($word!='') {
              # prüfe ob es ein Stoppwort ist
              if (!$this->stopwordlist[$word]) {
                # prüfe ob es ein Tag ist
                if (!isTag($word)) {
                  # prüfe ob die Zeichen enthalten sind
                  if (!eregi('([0-9]||·|-|\'|\.|"|<|>|\*|\+|\$|=|\?|!|\(|\)|{|}|#|\[|]|@|€|<|>|\^|°|&|§|/|\²|\³|_)',$word)) {
                    # schreibe temporär in Datenbank
                    #echo '<br>>'.$word.'<';
                    $this->insertTempWord($word);
                  } # end of Zeichen sind nicht enthalten.
                } # end of es ist kein Tag
              } # end of es ist kein Stopwort
            } # end of word ist nicht leer
            $anzWords++;
          } # end of Durchlaufen des Array von Worten in einer Zeile
        } # end of line ist nicht leer
      } # end of zeilenweises lesen der Datei
      fclose($fp);
      $this->insertDistinctWords();
      echo '<br>'.$anzWords.' Wörter eingelesen.';
    }
    else {
      $ret[0]=1;
      $ret[1]='Fehler beim Einlesen des Dokumentes: '.$this->searchpath.$filename.' Prüfen Sie ob die Datei vorhanden ist und Leserechte für Apache eingestellt sind.';
    }
    return $ret;
  }
  
  function deleteAllWords() {
    $sql = "TRUNCATE TABLE doc_words";
    $queryret=$this->database->execSQL($sql, 4, 0);
    $sql = "TRUNCATE TABLE doc_tempwords";
    $queryret=$this->database->execSQL($sql, 4, 0);
  }
  
  function insertTempWord($word) {
    $sql = "INSERT INTO doc_tempwords (begriff) VALUES ('".mb_convert_encoding($word, "Latin1", "UTF-8")."')";
    $queryret=$this->database->execSQL($sql, 4, 0);
  }

  function insertDistinctWords() {
    $sql = "INSERT INTO doc_words (begriff) SELECT DISTINCT * FROM doc_tempwords";
    $queryret=$this->database->execSQL($sql, 4, 0);
  }
  
  function insertDocumentProperties($filename) {
    $sql ="INSERT INTO doc_documents (filename,filelastmodified)" .
    $sql.=" VALUES('".$this->searchpath.$filename."','".date("Y-m-d H:i:s",filemtime($this->searchpath.$filename))."')";
    $queryret=$this->database->execSQL($sql, 4, 0);
    $sql ="SELECT id FROM doc_documents WHERE filename = '".$this->searchpath.$filename."'";
    $sql.=" AND filelastmodified='".date("Y-m-d H:i:s",filemtime($this->searchpath.$filename))."'";
    $queryret=$this->database->execSQL($sql, 4, 0);
    $rs=pg_fetch_array($queryret[1]);
    $this->doc_id=$rs['id'];
    return $queryret;
  }
  
  function isDocumentRegistered($filename) {
    $sql = "SELECT id FROM doc_documents";
    $sql.=" WHERE filename = '".$this->searchpath.$filename."'";
    $sql.=" AND filelastmodified='".date("Y-m-d H:i:s",filemtime($this->searchpath.$filename))."'";
    $queryret=$this->database->execSQL($sql, 4, 0);
    if (pg_num_rows($queryret[1])>0) {
      $registered=true;
      $rs=pg_fetch_array($queryret[1]);
      $this->doc_id=$rs['id'];            
    }
    else {
      $registered=false;
    }
    return $registered;
  }
  
  function insertDocToGeoname($doc_id) {
    $sql ="INSERT INTO doc_doc2geoname (doc_id,geoname_id)";
    $sql.=" SELECT '".$doc_id."' AS doc_id,g.oid AS geoname_id FROM doc_words AS w,gaz_begriffe AS g";
    $sql.=" WHERE lower(w.begriff) = lower(g.bezeichnung)";
    $queryret=$this->database->execSQL($sql, 4, 0);   
  }
  
  function deleteDocToGeoname($doc_id) {
    $sql ="DELETE FROM doc_doc2geoname WHERE doc_id=".$doc_id;
    $queryret=$this->database->execSQL($sql, 4, 0);   
  }
  
  function updateDocumentProperties($doc_id,$filename) {
    $sql ="UPDATE doc_documents";
    $sql.=" SET filelastmodified='".date("Y-m-d H:i:s",filemtime($this->searchpath.$filename))."'";
    $sql.=" WHERE id=".$doc_id;
    $queryret=$this->database->execSQL($sql, 4, 0);   
  }
  
  function pdf2string($sourcefile) {
    # Returns a -1 if uncompression failed
    # Funktion zum direkten lesen von PDF mit PHP
    # gibt den daraus extrahierten Text zurück
    # Quellcode von mattb at bluewebstudios dot com
    # 04-Feb-2005 10:44 http://de2.php.net/manual/en/ref.pdf.php#49690
    
    $fp = fopen($sourcefile, 'rb');
    $content = fread($fp, filesize($sourcefile));
    fclose($fp);

    # Locate all text hidden within the stream and endstream tags
    $searchstart = 'stream';
    $searchend = 'endstream';
    $pdfdocument = "";

    $pos = 0;
    $pos2 = 0;
    $startpos = 0;
    # Iterate through each stream block
    while( $pos !== false && $pos2 !== false ) {
      # Grab beginning and end tag locations if they have not yet been parsed
      $pos = strpos($content, $searchstart, $startpos);
      $pos2 = strpos($content, $searchend, $startpos + 1);
      if( $pos !== false && $pos2 !== false ) {
        # Extract compressed text from between stream tags and uncompress
        $textsection = substr($content, $pos + strlen($searchstart) + 2, $pos2 - $pos - strlen($searchstart) - 1);
        $data = @gzuncompress($textsection);
        # Clean up text via a special function
        $data = $this->extractText($data);
        # Increase our PDF pointer past the section we just read
        $startpos = $pos2 + strlen($searchend) - 1;
        if( $data === false ) { return -1; }
        $pdfdocument = $pdfdocument . $data;
      }
    }
    return $pdfdocument;
  }

  function extractText($postScriptData) {
    while( (($textStart = strpos($postScriptData, '(', $textStart)) && ($textEnd = strpos($postScriptData, ')', $textStart + 1)) && substr($postScriptData, $textEnd - 1) != '\\') ) {
      $plainText .= substr($postScriptData, $textStart + 1, $textEnd - $textStart - 1);
      if( substr($postScriptData, $textEnd + 1, 1) == ']' ) {
        // This adds quite some additional spaces between the words
        $plainText .= ' ';
      }
      $textStart = $textStart < $textEnd ? $textEnd : $textStart + 1;
    }
    return stripslashes($plainText);
  }
  
}
?>
