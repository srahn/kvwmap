<?php
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
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de                #
###################################################################
##################################################
# Klasse Datenbank für ALB Modell und PostgreSQL #
##################################################
class pgdatabase_core {

  function pgdatabase_core() {
    global $debug;
    $this->debug=$debug;
    $this->loglevel=LOG_LEVEL;
 		$this->defaultloglevel=LOG_LEVEL;
 		global $log_postgres;
    $this->logfile=$log_postgres;
 		$this->defaultlogfile=$log_postgres;
    $this->ist_Fortfuehrung=1;
    $this->type='postgresql';
    $this->commentsign='--';
    # Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
    # START TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
    # Anweisungen nicht in Transactionsblöcken ablaufen.
    # Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
    # Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
    # und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
    # Dazu Fehlerausschriften bearchten.
    $this->blocktransaction=0;
  }


####################################################
# database Funktionen
###########################################################
  function open() {
    $this->debug->write("<br>Datenbankverbindung öffnen: Datenbank: ".$this->dbName." User: ".$this->user." Passwd: ".$this->passwd,4);
    $this->dbConn=pg_connect('dbname='.$this->dbName.' port=5432 user='.$this->user.' password='.$this->passwd.' host='.$this->host);
    $this->debug->write("Datenbank mit Connection_ID: ".$this->dbConn." geöffnet.",4);
    # $this->version = pg_version($this->dbConn); geht erst mit PHP 5
    $this->version = POSTGRESVERSION;
    return $this->dbConn;
  }

  function setClientEncoding() {
    $sql ="SET CLIENT_ENCODING TO '".POSTGRES_CHARSET."';";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    return $ret[1];    	
  }  

  function close() {
    $this->debug->write("<br>PostgreSQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    return pg_close($this->dbConn);
  }
  
  function transformRect($curExtent,$curSRID,$newSRID) {
    $sql ="SELECT round(CAST (X(min) AS numeric),5) AS minx, round(CAST (Y(min) AS numeric),5) AS miny";
    $sql.=", round(CAST (X(max) AS numeric),5) AS maxx, round(CAST (Y(max) AS numeric),5) AS maxy";
    $sql.=" FROM (SELECT";
    $sql.=" TRANSFORM(GeomFromText('POINT(".$curExtent->minx." ".$curExtent->miny.")',".$curSRID."),".$newSRID.") AS min";
    $sql.=" ,TRANSFORM(GeomFromText('POINT(".$curExtent->maxx." ".$curExtent->maxy.")',".$curSRID."),".$newSRID.") AS max";
    $sql.=") AS foo";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $curExtent->minx=$rs['minx'];
      $curExtent->miny=$rs['miny'];
      $curExtent->maxx=$rs['maxx'];
      $curExtent->maxy=$rs['maxy'];
      $ret[1]=$curExtent;
    }
    
    /*$projFROM = ms_newprojectionobj("init=epsg:".$curSRID);
		$projTO = ms_newprojectionobj("init=epsg:".$newSRID);
		$curExtent->project($projFROM, $projTO);
		$ret[0] = 0;
		$ret[1] = $curExtent;*/
    return $ret;
  }

  function execSQL($sql,$debuglevel, $loglevel) {
  	switch ($this->loglevel) {
  		case 0 : {
  			$logsql=0;
  		} break;
  		case 1 : {
  			$logsql=1;
  		} break;
  		case 2 : {
  			$logsql=$loglevel;
  		} break;
  	}
    # SQL-Statement wird nur ausgeführt, wenn DBWRITE gesetzt oder
    # wenn keine INSERT, UPDATE und DELETE Anweisungen in $sql stehen.
    # (lesend immer, aber schreibend nur mit DBWRITE=1)
    if (DBWRITE OR (!stristr($sql,'INSERT') AND !stristr($sql,'UPDATE') AND !stristr($sql,'DELETE'))) {
      #echo "<br>".$sql;
      $sql = "SET datestyle TO 'German';".$sql;
      if($this->schema != ''){
      	$sql = "SET search_path = ".$this->schema.", public;".$sql;
      }
      $query=pg_query($this->dbConn,$sql);
      //$query=0;
      if ($query==0) {
        $ret[0]=1;
        $ret[1]="Fehler bei SQL Anweisung:<br>".$sql."<br>".pg_result_error($query);
        echo "<br><b>".$ret[1]."</b>";
        $this->debug->write("<br><b>".$ret[1]."</b>",$debuglevel);
        if ($logsql) {
          $this->logfile->write($this->commentsign." ".$ret[1]);
        }
      }
      else {
      	# Abfrage wurde erfolgreich ausgeführt
        $ret[0]=0;
        $ret[1]=$query;
        $this->debug->write("<br>".$sql,$debuglevel);
        # 2006-07-04 pk $logfile ersetzt durch $this->logfile
        if ($logsql) {
          $this->logfile->write($sql.';');
        }
      }
      $ret[2]=$sql;
    }
    else {
      # Es werden keine SQL-Kommandos ausgeführt
      # Die Funktion liefert ret[0]=0, und zeigt damit an, daß kein Datenbankfehler aufgetreten ist,
      $ret[0]=0;
      # jedoch hat $ret[1] keine query_ID sondern auch den Wert 0
      $ret[1]=0;
      # Wenn $this->loglevel != 0 wird die sql-Anweisung in die logdatei geschrieben
      # zusätzlich immer in die debugdatei
      # 2006-07-04 pk $logfile ersetzt durch $this->logfile
      if ($logsql) {
        $this->logfile->write($sql.';');
      }
      $this->debug->write("<br>".$sql,$debuglevel);
    }

    return $ret;
  }

}





















