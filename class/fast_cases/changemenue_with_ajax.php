<?function checkPasswordAge($passwordSettingTime,$allowedPassordAgeMonth) {
  $passwordSettingUnixTime=strtotime($passwordSettingTime); # Unix Zeit in Sekunden an dem das Passwort gesetzt wurde
  $allowedPasswordAgeDays=round($allowedPassordAgeMonth*30.5); # Zeitintervall, wie alt das Password sein darf in Tagen
  $passwordAgeDays=round((time()-$passwordSettingUnixTime)/60/60/24); # Zeitinterval zwischen setzen des Passwortes und aktueller Zeit in Tagen
  $allowedPasswordAgeRemainDays=$allowedPasswordAgeDays-$passwordAgeDays; # Zeitinterval wie lange das Passwort noch gilt in Tagen
	return $allowedPasswordAgeRemainDays; // Passwort ist abgelaufen wenn Wert < 1  
}
class GUI {  var $layout;  var $style;  var $mime_type;  var $menue;  var $pdf;  var $addressliste;  var $debug;  var $dbConn;  var $flst;  var $formvars;  var $legende;  var $map;  var $mapDB;  var $img;  var $FormObject;  var $StellenForm;  var $Fehlermeldung;  var $Hinweis;  var $Stelle;  var $ALB;  var $activeLayer;  var $nImageWidth;  var $nImageHeight;  var $user;  var $qlayerset;  var $scaleUnitSwitchScale;  var $map_scaledenom;  var $map_factor;  var $formatter;  function GUI($main, $style, $mime_type) {
    # Debugdatei setzen
    global $debug;
    $this->debug=$debug;
    # Logdatei für Mysql setzen
    global $log_mysql;
    $this->log_mysql=$log_mysql;
    # Logdatei für PostgreSQL setzten
    global $log_postgres;
    $this->log_postgres=$log_postgres;
    # layout Templatedatei zur Anzeige der Daten
    if ($main!="") $this->main=$main;
    # Stylesheetdatei
    if (isset($style)) $this->style=$style;
    # mime_type html, pdf
    if (isset ($mime_type)) $this->mime_type=$mime_type;
  }
	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
    $this->Stelle->language=$language;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$this->user->rolle->language.'.php');
  }
	function changemenue_with_ajax($id, $status){
    $this->changemenue($id, $status);
    if($status == 'on'){
      echo $this->Stelle->getsubmenues($id);
    }
  }
	function changemenue($id, $status){
    $sql ='SELECT status from u_menue2rolle WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id.' AND `menue_id` ='.$id;
    $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    if($rs[0] == 0 AND $status == 'on'){
      $sql ='UPDATE u_menue2rolle SET `status` = 1 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id.' AND `menue_id` ='.$id;
      $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    }
    elseif($rs[0] == 1 AND $status == 'off'){
      $sql ='UPDATE u_menue2rolle SET `status` = 0 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id.' AND `menue_id` ='.$id;
      $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    }
  }
}class database {  var $ist_Fortfuehrung;  var $debug;  var $loglevel;  var $logfile;  var $commentsign;  var $blocktransaction;  function database() {
    global $debug;
    $this->debug=$debug;
    $this->loglevel=LOG_LEVEL;
 		$this->defaultloglevel=LOG_LEVEL;
 		global $log_mysql;
    $this->logfile=$log_mysql;
 		$this->defaultlogfile=$log_mysql;
    $this->ist_Fortfuehrung=1;
    $this->type="MySQL";
    $this->commentsign='#';
    # Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
    # BEGIN TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
    # Anweisungen nicht in Transactionsblöcken ablaufen.
    # Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
    # Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
    # und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
    # Dazu Fehlerausschriften bearchten.
    $this->blocktransaction=0;
  }
  function open() {
    $this->debug->write("<br>MySQL Verbindung öffnen mit Host: ".$this->host." User: ".$this->user,4);
    $this->dbConn=mysql_connect($this->host,$this->user,$this->passwd);
    $this->debug->write("Datenbank mit ID: ".$this->dbConn." und Name: ".$this->dbName." auswählen.",4);
    return mysql_select_db($this->dbName,$this->dbConn);
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
      $query=mysql_query($sql,$this->dbConn);
      #echo $sql;
      if ($query==0) {
        $ret[0]=1;
        $ret[1]="<b>Fehler bei SQL Anweisung:</b><br>".$sql."<br>".mysql_error($query);
        $this->debug->write($ret[1],$debuglevel);
        if ($logsql) {
          $this->logfile->write("#".$ret[1]);
        }
      }
      else {
        $ret[0]=0;
        $ret[1]=$query;
        if ($logsql) {
          $this->logfile->write($sql.';');
        }
        $this->debug->write(date('H:i:s')."<br>".$sql,$debuglevel);
      }
      $ret[2]=$sql;
    }
    else {
    	if ($logsql) {
    		$this->logfile->write($sql.';');
    	}
    	$this->debug->write("<br>".$sql,$debuglevel);
    }
    return $ret;
  }
  function close() {
    $this->debug->write("<br>MySQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    if (LOG_LEVEL>0){
    	$this->logfile->close();
    }
    return mysql_close($this->dbConn);
  }
}class user {  var $id;  var $Name;  var $Vorname;  var $login_name;  var $funktion;  var $dbConn;  var $Stellen;  var $nZoomFactor;  var $nImageWidth;  var $nImageHeight;  var $database;  var $remote_addr;	function user($login_name,$id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->database=$database;
		if($login_name){
			$this->login_name=$login_name;
			$this->readUserDaten(0,$login_name);
			$this->remote_addr=getenv('REMOTE_ADDR');
		}
		else{
			$this->id = $id;
			$this->readUserDaten($id,0);
		}
	}
  function readUserDaten($id,$login_name) {
    $sql ='SELECT * FROM user WHERE 1=1';
    if ($id>0) {
      $sql.=' AND ID='.$id;
    }
    if ($login_name!='') {
      $sql.=' AND login_name LIKE "'.$login_name.'"';
    }
    $this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->id=$rs['ID'];
    $this->login_name=$rs['login_name'];
    $this->Namenszusatz=$rs['Namenszusatz'];
    $this->Name=$rs['Name'];
    $this->Vorname=$rs['Vorname'];
    $this->stelle_id=$rs['stelle_id'];
    $this->phon=$rs['phon'];
    $this->email=$rs['email'];
    if (CHECK_CLIENT_IP) {
      $this->ips=$rs['ips'];
    }
    $this->password_setting_time=$rs['password_setting_time'];
  }
  function getLastStelle() {
    $sql = 'SELECT stelle_id FROM user WHERE ID='.$this->id;
    $this->debug->write("<p>file:users.php class:user->getLastStelle - Abfragen der zuletzt genutzten Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['stelle_id'];
  }
	function clientIpIsValide($remote_addr) {
    # Prüfen ob die übergebene IP Adresse zu den für den Nutzer eingetragenen Adressen passt
    $ips=explode(';',$this->ips);
    foreach ($ips AS $ip) {
      if (trim($ip)!='') {
        $ip=trim($ip);
        if (in_subnet($remote_addr,$ip)) {
          $this->debug->write('<br>IP:'.$remote_addr.' paßt zu '.$ip,4);
          #echo '<br>IP:'.$remote_addr.' paßt zu '.$ip;
          return 1;
        }
      }
    }
    return 0;
  }
	function setRolle($stelle_id) {
		# Abfragen und zuweisen der Einstellungen für die Rolle		
		$rolle=new rolle($this->id,$stelle_id,$this->database);		
		if ($rolle->readSettings()) {
			$this->rolle=$rolle;			
			return 1;
		}
		return 0;
	}
}class stelle {  var $id;  var $Bezeichnung;  var $debug;  var $nImageWidth;  var $nImageHeight;  var $oGeorefExt;  var $pixsize;  var $selectedButton;  var $database;	function stelle($id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->id=$id;
		$this->database=$database;
		$this->Bezeichnung=$this->getName();
		$this->readDefaultValues();
	}
  function getName() {
    $sql ='SELECT ';
    if ($this->language != 'german' AND $this->language != ''){
      $sql.='`Bezeichnung_'.$this->language.'` AS ';
    }
    $sql.='Bezeichnung FROM stelle WHERE ID='.$this->id;
    #echo $sql;
    $this->debug->write("<p>file:users.php class:stelle->getName - Abfragen des Namens der Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->Bezeichnung=$rs['Bezeichnung'];
    return $rs['Bezeichnung'];
  }
  function readDefaultValues() {
    $sql ='SELECT * FROM stelle WHERE ID='.$this->id;
    $this->debug->write("<p>file:users.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);    
    $this->MaxGeorefExt=ms_newRectObj();
    $this->MaxGeorefExt->setextent($rs['minxmax'],$rs['minymax'],$rs['maxxmax'],$rs['maxymax']);
    $this->epsg_code=$rs["epsg_code"];
    $this->alb_raumbezug=$rs["alb_raumbezug"];
    $this->alb_raumbezug_wert=$rs["alb_raumbezug_wert"];
    $this->wasserzeichen=$rs["wasserzeichen"];
    $this->pgdbhost=$rs["pgdbhost"];
    $this->pgdbname=$rs["pgdbname"];
    $this->pgdbuser=$rs["pgdbuser"];
    $this->pgdbpasswd=$rs["pgdbpasswd"];
    $this->protected=$rs["protected"];
    //---------- OWS Metadaten ----------//
    $this->ows_title=$rs["ows_title"];
    $this->ows_abstract=$rs["ows_abstract"];
    $this->wms_accessconstraints=$rs["wms_accessconstraints"];
    $this->ows_contactperson=$rs["ows_contactperson"];
    $this->ows_contactorganization=$rs["ows_contactorganization"];
    $this->ows_contactelectronicmailaddress=$rs["ows_contactemailaddress"];
    $this->ows_contactposition=$rs["ows_contactposition"];
    $this->ows_fees=$rs["ows_fees"];
    $this->ows_srs=$rs["ows_srs"];
    $this->check_client_ip=$rs["check_client_ip"];
    $this->checkPasswordAge=$rs["check_password_age"];
    $this->allowedPasswordAge=$rs["allowed_password_age"];
    $this->useLayerAliases=$rs["use_layer_aliases"];
  }
  function checkClientIpIsOn() {
    $sql ='SELECT check_client_ip FROM stelle WHERE ID = '.$this->id;
    $this->debug->write("<p>file:users.php class:stelle->checkClientIpIsOn- Abfragen ob IP's der Nutzer in der Stelle getestet werden sollen<br>".$sql,4);
    #echo '<br>'.$sql;
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    if ($rs['check_client_ip']=='1') {
      return 1;
    }
    return 0;
  }
	function getsubmenues($id){
		$sql ='SELECT menue_id,';
		if ($this->language != 'german') {
			$sql.='`name_'.$this->language.'` AS ';
		}
		$sql .=' name, target, links FROM u_menue2stelle, u_menues';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND obermenue = '.$id;
		$sql .=' AND menueebene = 2';
		$sql .=' AND u_menue2stelle.menue_id = u_menues.id';
		$sql .= ' ORDER BY menue_order';
		$this->debug->write("<p>file:users.php class:stelle->getsubMenues - Lesen der UnterMenuepunkte eines Menüpunktes:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$menue['name'][]=$rs['name'];
				$menue['target'][]=$rs['target'];
				$menue['links'][]=$rs['links'];
			}
		}
		$html = '<table cellspacing="2" cellpadding="0" border="0">';
		for ($i = 0; $i < count($menue['name']); $i++) {
			$html .='
        <tr>
          <td> 
            <img src="'.GRAPHICSPATH.'leer.gif" width="17" height="1" border="0">
					</td>
					<td>
            <a href="';
			if ($menue['target'][$i]=='confirm') {
				$html .='javascript:Bestaetigung(\'';
			}
			$html .= $menue['links'][$i];
			if ($menue['target'][$i]=='confirm') {
				$html .= '\',\'Diese Aktion wirklich ausf&uuml;hren?\')';
				$menue['target'][$i]='';
			}
			$html .= '" class="menuered"';
			if ($menue['target'][$i]!='') {
				$html .= ' target="'.$menue['target'][$i].'"';
			}
			$html .= '>'.$menue['name'][$i].'</a>
          </td>
        </tr>';
		}
		$html .= '</table>';
		return $html;
	}
}class rolle {  var $user_id;  var $stelle_id;  var $debug;  var $database;  var $loglevel;	function rolle($user_id,$stelle_id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->user_id=$user_id;
		$this->stelle_id=$stelle_id;
		$this->database=$database;
		#$this->layerset=$this->getLayer('');
		#$this->groupset=$this->getGroups('');
		$this->loglevel = 0;
	}
  function readSettings() {
    # Abfragen und Zuweisen der Einstellungen der Rolle
    $sql ='SELECT * FROM rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    #echo $sql;
    $this->debug->write("<p>file:users.php class:rolle function:readSettings - Abfragen der Einstellungen der Rolle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
    $rs=mysql_fetch_array($query);
    $this->oGeorefExt=ms_newRectObj();
    $this->oGeorefExt->setextent($rs['minx'],$rs['miny'],$rs['maxx'],$rs['maxy']);
    $this->nImageWidth=$rs['nImageWidth'];
    $this->nImageHeight=$rs['nImageHeight'];
    $this->mapsize=$this->nImageWidth.'x'.$this->nImageHeight;
    @$this->pixwidth=($rs['maxx']-$rs['minx'])/$rs['nImageWidth'];
    @$this->pixheight=($rs['maxy']-$rs['miny'])/$rs['nImageHeight'];
    $this->pixsize=($this->pixwidth+$this->pixheight)/2;
    $this->nZoomFactor=$rs['nZoomFactor'];
    $this->epsg_code=$rs['epsg_code'];
    $this->epsg_code2=$rs['epsg_code2'];
    $this->coordtype=$rs['coordtype'];
    $this->last_time_id=$rs['last_time_id'];
    $this->gui=$rs['gui'];
    $this->language=$rs['language'];
		define(LANGUAGE, $this->language);
    $this->hideMenue=$rs['hidemenue'];
    $this->hideLegend=$rs['hidelegend'];
    $this->fontsize_gle=$rs['fontsize_gle'];
    $this->highlighting=$rs['highlighting'];
    $this->scrollposition=$rs['scrollposition'];
    $this->result_color=$rs['result_color'];
    $this->always_draw=$rs['always_draw'];
    $this->runningcoords=$rs['runningcoords'];
		$this->singlequery=$rs['singlequery'];
		$this->querymode=$rs['querymode'];
		$this->geom_edit_first=$rs['geom_edit_first'];		
		$this->overlayx=$rs['overlayx'];
		$this->overlayy=$rs['overlayy'];
		if($rs['hist_timestamp'] != ''){
			$this->hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('d.m.Y H:i:s');
			define(HIST_TIMESTAMP, DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('Y-m-d\TH:i:s\Z'));
		}
		else define(HIST_TIMESTAMP, '');
    $buttons = explode(',', $rs['buttons']);
    $this->back = in_array('back', $buttons);
    $this->forward = in_array('forward', $buttons);
    $this->zoomin = in_array('zoomin', $buttons);
    $this->zoomout = in_array('zoomout', $buttons);
    $this->zoomall = in_array('zoomall', $buttons);
    $this->recentre = in_array('recentre', $buttons);
    $this->jumpto = in_array('jumpto', $buttons);
    $this->query = in_array('query', $buttons);
    $this->queryradius = in_array('queryradius', $buttons);
    $this->polyquery = in_array('polyquery', $buttons);
    $this->touchquery = in_array('touchquery', $buttons);
    $this->measure = in_array('measure', $buttons);
    $this->freepolygon = in_array('freepolygon', $buttons);
    $this->freetext = in_array('freetext', $buttons);
    $this->freearrow = in_array('freearrow', $buttons);
    return 1;
  }
}?>