<?php
class gewaesser {
  var $shapeFile;
  var $dbDir;
  var $dbDirNeu;
  var $pgdatabase;
  
  function gewaesser($pgdatabase) {
    $this->pgdatabase = $pgdatabase;
    $this->shapeFile = '/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_ost';
    $this->dbDir = '/home/fgs/wasserverband-kroepelin/db/';
    $this->dbDirNeu = '/home/fgs/wasserverband-kroepelin/db_neu/';
  }

  function truncateSpatial() {
    $sql = 'TRUNCATE wasserverband.gewaesser;';
    $this->pgdatabase->execSQL($sql,4, 0);
  }
  
  function loadSpatial($shapeFile) {
    exec('shp2pgsql -a -s 25833 '.$shapeFile.' wasserverband.gewaesser > '.$shapeFile.'.sql');
	  exec(POSTGRESBINPATH.'psql -f '.$shapeFile.'.sql -U '.$this->pgdatabase->user.' '.$this->pgdatabase->dbName);
  }
  
  function loadThematic() {
    $sqlFiles = scandir($this->dbDir);
    foreach($sqlFiles AS $sqlFile) {
      if ($sqlFile != '.' and $sqlFile != '..') {
        #echo $this->dbDir.$sqlFile.'<br>';
        $dateizeilen = file($this->dbDir.$sqlFile);
        $dateizeilen_neu = array();
        $dateizeilen_neu[] = "SET search_path=wasserverband_import, public;\n";
        $dateizeilen_neu[] = "DROP TABLE IF EXISTS ".strtolower(basename($sqlFile,".sql")).";\n";
        foreach($dateizeilen AS $zeile) {
          $zeile = str_replace("\"\"\"","\"\"",$zeile); # """ => ""
          $zeile = str_replace("\"\"\"","\"\"",$zeile); # """ => ""
          $zeile = str_replace("\"\"\"","\"\"",$zeile); # """ => ""
          $zeile = str_replace("\"\"\"","\"\"",$zeile); # """ => ""
          $zeile = str_replace("\"\"\"","\"\"",$zeile); # """ => ""
          $zeile = str_replace("\"\"\"","\"\"",$zeile); # """ => "",
          $zeile= str_replace("\"\"","''",$zeile);      # "" => ''
          $zeile= str_replace(",\"",",'",$zeile);       # ," => ,'
          $zeile= str_replace("\",","',",$zeile);       # ", => ',
          $zeile= str_replace("(\"","('",$zeile);       # (" => ('
          $zeile= str_replace("\")","')",$zeile);       # ") => ')
          foreach (getArrayOfChars() AS $character) {
            $zeile= str_replace($character."''",$character."'",$zeile);       # "character'' => character')
          }
          
          $zeile= str_replace("\\","/",$zeile);         # \ => /
          $zeile= str_replace("„","ä",$zeile);          # „ => ä
          $zeile= str_replace("„","”",$zeile);          # ” => ö
          $zeile= str_replace("á","ß",$zeile);          # á => ß

          $zeile = str_replace("char","character varying",$zeile);
          $zeile = str_replace("real(8)","real",$zeile);
          $zeile = str_replace("date","character varying(10)",$zeile);				
          $dateizeilen_neu[] = utf8_encode($zeile);
        }
        echo $this->dbDirNeu.$sqlFile."<br>";
        file_put_contents($this->dbDirNeu.$sqlFile,$dateizeilen_neu);
        exec(POSTGRESBINPATH.'psql -f '.$this->dbDirNeu.$sqlFile.' -U '.$this->pgdatabase->user.' '.$this->pgdatabase->dbName.' 2>> '.$this->dbDirNeu.'psql_err.log');
      }
    }
  }
  
}
?>