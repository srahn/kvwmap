<?php
# Objekt für graphische Benutzeroberfläche erzeugen
$GUI=new GUI("map.php", "main.css.php", "html");

$GUI->allowed_documents = array();
$GUI->document_loader_name = session_id().rand(0,99999999).'.php';

# Übergabe aller Formularvariablen an die Benutzeroberfläche an formvars
# Dabei wird unterschieden zwischen Aufrufen über das Internet oder von der Komandozeile aus
if (is_array($argc) AND $argc[1]!='') {
 # Aufruf des PHP-Skriptes über die Komandozeile (CLI)
 # Wenn die Variable argc > 0 ist, wurde die Datei von der Komandozeile aus aufgerufen
 # in dem Fall können die übergebenen Parameter hier der formvars-Variable übergeben werden.
 $arg['go']=$argv[1];
 $arg['ist_Fortfuehrung']=$argv[2];
 $arg['WLDGE_lokal']=$argv[3];
 $arg['WLDGE_Datei_lokal']=$argv[4];
 $GUI->formvars=$arg;
}
else {
  # Übergeben der Variablen aus den Post oder Get Aufrufen
  # normaler Aufruf des PHP-Skriptes über Apache oder CGI  
  #$GUI->formvars=stripScript($_REQUEST);
  foreach($_REQUEST as $key => $value){
  	#if(is_string($value))$_REQUEST[$key] = addslashes($value);
		if(is_string($value))$_REQUEST[$key] = pg_escape_string($value);
  }
  $GUI->formvars=$_REQUEST;
}		
											

#################################################################################
# Setzen der Konstante, ob in die Datenbank geschrieben werden soll oder nicht.
# Kann z.B. zu Testzwecken ausgeschaltet werden.
if ($GUI->formvars['disableDbWrite']=='1') {
	define('DBWRITE',false);
}
else {
	define('DBWRITE',DEFAULTDBWRITE);
}
if (!DBWRITE) { echo '<br>Das Schreiben in die Datenbank wird unterdrückt!'; }

# Öffnen der Datenbankverbindung zur Kartenverwaltung (MySQL)
# Erzeugen des MYSQL-DB-Objekts, falls es noch nicht durch den Login erzeugt wurde
if($userDb == NULL){
	$userDb = new database();
	$userDb->host = MYSQL_HOST;
	$userDb->user = MYSQL_USER;
	$userDb->passwd = MYSQL_PASSWORD;
	$userDb->dbName = MYSQL_DBNAME;
}
$GUI->database = $userDb;
if (!$GUI->database->open()) {
  # Prüfen ob eine neue Datenbank angelegt werden soll
  if ($GUI->formvars['go']=='install-mysql-db') {
    # Anlegen der neuen Datenbank
    # Herstellen der Verbindung mit defaultwerten
    $GUI->dbConn=mysql_connect(MYSQL_HOST,'kvwmap','kvwmap');
    $GUI->debug->write("MySQL Datenbank mit ID: ".$GUI->dbConn." und Name: mysql auswählen.",4);
    # Auswählen der Datenbank mysql
    mysql_select_db('mysql',$GUI->dbConn);
    # Erzeugen der leeren Datenbank für kvwmap
    $sql = 'CREATE DATABASE '.$GUI->database->dbName.' CHARACTER SET latin1 COLLATE latin1_german2_ci';
    $GUI->database->execSQL($sql,4,0);
    # Anlegen der leeren Tabellen für kvwmap
    if ($GUI->formvars['install-GUI']) {
      # Demo Daten in Datenbank schreiben
      $sql = file_get_contents(LAYOUTPATH.'sql_dumps/mysql_setup_GUI.sql');     
      $GUI->database->execSQL($sql,4,0);
    }
    # Abfrage ob Zugang zur neuen Datenbank jetzt möglich
    if (mysql_select_db($GUI->database->dbName,$GUI->dbConn)) {
      $debug->write("Verbindung zur MySQL Datenbank erfolgreich hergestellt.",4);
    }
    else {
      # Die neue Datenbank konnte nicht hergestellt werden
      echo 'Die Neue Datenbank konnte nicht hergestellt werden mit:';
      echo '<br>Host: '.$GUI->database->host;
      echo '<br>User: '.$GUI->database->user;
     # echo '<br>Passwd: '.$GUI->database->passwd;
      echo '<br>Datenbankname: '.$GUI->database->dbName;
      echo '<p>Das kann folgende Gründe haben:<lu>';
      echo '<li>Der Datenbankserver ist gerade nicht erreichbar.</li>';
      echo '<li>Die Angaben zum Host, Benutzer und Password in der config.php sind falsch.</li>';
      echo '<li>Die Angaben zum Host, Benutzer und Password in der Tabelle mysql.users sind falsch.</li>';
      echo '</lu>';
      exit;
    } # ende fehler beim aufbauen der mysql datenbank
  } # ende mysql datenbank installieren
  else {
    # Es konnte keine Datenbankverbindung aufgebaut werden
    echo 'Die Verbindung zur Kartendatenbank konnte mit folgenden Daten nicht hergestellt werden:';
    echo '<br>Host: '.$GUI->database->host;
    echo '<br>User: '.$GUI->database->user;
   # echo '<br>Passwd: '.$GUI->database->passwd;
    echo '<br>Datenbankname: '.$GUI->database->dbName;
    echo '<p>Das kann folgende Gründe haben:<lu>';
    echo '<li>Die Datenbank existiert noch nicht. Legen Sie eine leere Datenbank an und führen Sie das <a href="install.php">Installationsskript</a> durch.';
    echo '<li>Der Datenbankserver ist gerade nicht erreichbar.</li>';
    echo '<li>Die Angaben zum Host, Benutzer und Password in der config.php sind falsch.</li>';
    echo '<li>Die Angaben zum Host, Benutzer und Password in der Tabelle mysql.users sind falsch.</li>';
    echo '</lu>';   
    exit;
  }
}
else {
  $debug->write("Verbindung zur MySQL Kartendatenbank erfolgreich hergestellt.",4);        
}

# Angeben, dass die Texte in latin1 zurückgegeben werden sollen
$GUI->database->execSQL("SET NAMES '".MYSQL_CHARSET."'",0,0);


#######################################################################
# aktuellen Benutzer abfragen
$login_name = $_SESSION['login_name'];


# User Daten lesen
$GUI->user=new user($login_name,0,$GUI->database);
if(BEARBEITER == 'true'){
	define('BEARBEITER_NAME', 'Bearbeiter: '.$GUI->user->Name);
}

/*
 * Eintragen eines neuen Passwortes, wenn es neu vergeben wurde
 */
if (isset($GUI->formvars['newPassword'])) {
	$GUI->Fehlermeldung=isPasswordValide($GUI->formvars['passwort'],$GUI->formvars['newPassword'],$GUI->formvars['newPassword2']);
	if ($GUI->Fehlermeldung=='') {
		$GUI->user->setNewPassword($GUI->formvars['newPassword']);
		$GUI->user->password_setting_time=date('Y-m-d H:i:s',time());
		$GUI->Fehlermeldung='Password ist erfolgreich geändert worden.';
		#$GUI->formvars['newPassword'];
	}
  else {
  	$GUI->Fehlermeldung=urlencode($GUI->Fehlermeldung.'!<br>Vorschlag für ein neues Password: <b>'.createRandomPassword(8).'</b><br>');
	  $go='logout';
  }
}
 
###################################################################################
# Einstellung der Stellen_ID
# 1) Die Variable Stelle_ID wurde nicht neu gesetzt,
#    Die zuletzt genutzte Stellen_ID wird aus der Datenbank gelesen und verwendet
#    sollte dabei ein Fehler auftreten oder keine Zahl > 0 enthalten sein wird der
#    in der Konstante DEFAULTSTELLE gesetzte Wert verwendet.
# 2) Stellen_ID ist neu gesetzt worden, ein Stellenwechsel wird durchgeführt
#    Ist user dazu berechtigt, wird diese neue Stelle in Datenbank eingetragen,
#    sonst wird wieder alte Stelle für Stelle_ID verwendet und das Formular zur
#    Stellenauswahl mit Fehlermeldung angezeigt.

# zuletzt verwendete Stellen_ID für user aus Datenbank abfragen
$alteStelle=$GUI->user->getLastStelle();
$neueStelle=$GUI->formvars['Stelle_ID'];
if ($alteStelle==0 OR $alteStelle=='') { $alteStelle==DEFAULTSTELLE; }
# Abfragen, ob Stelle_ID in Formular neu gesetzt wurde
if ($neueStelle>0 AND $GUI->formvars['go']!='Abbrechen') {
  # Stellen_ID wurde in Formular neu ausgewählt deshalb versuchen in die Stelle zu wechseln
  if ($GUI->user->StellenZugriff($neueStelle)>0 OR $neueStelle==DEFAULTSTELLE) {
    # Nutzer darf laut Zuordnung zu den Stellen in die gewünschte Stelle wechseln
    # Setzen der Stellen_ID als zuletzt benutzt
    if ($GUI->user->setStelle($neueStelle,$GUI->formvars)) {
      $Stelle_ID=$neueStelle;
    }
    else {
      $Stelle_ID=$alteStelle;
      $GUI->Fehlermeldung='Fehler beim Wechseln der Stelle. Prüfen Sie die Angaben.';
      if($GUI->formvars['go'] == 'OWS'){
        $GUI->formvars['go_plus'] = 'Exception';
      }
      else{
        $go='Stelle Wählen';
      }
    }
  }
  else {
    # Nutzer ist nicht berechtigt in die gewünschte Stelle zu wechseln
    $Stelle_ID=$alteStelle;
    $GUI->Fehlermeldung='Sie haben keine Berechtigung zum Zugriff auf die gewählte neue Stelle.';
    if($GUI->formvars['go'] == 'OWS'){
      $GUI->formvars['go_plus'] = 'Exception';
    }
    else{
      $go='Stelle Wählen';
    }
  }
}
else{
	$Stelle_ID=$alteStelle;
	if($GUI->user->StellenZugriff($alteStelle) == 0){
		$Stelle_ID=$alteStelle;
		$GUI->Fehlermeldung='Sie haben keine Berechtigung zum Zugriff auf die gewählte Stelle.';
    if($GUI->formvars['go'] == 'OWS'){
      $GUI->formvars['go_plus'] = 'Exception';
    }
    else{
      $go='Stelle Wählen';
    }
	}
}

# Erzeugen eines Stellenobjektes
$GUI->Stelle=new stelle($Stelle_ID,$GUI->database);

# Prüfung ob Client-IP-Adressen nach Vorgabe aus der Configurationsdatei überhaupt geprüft werden sollen
if (CHECK_CLIENT_IP) {
	$GUI->debug->write('<br>Es wird geprüft ob IP-Adressprüfung in der Stelle durchgeführt werden muss.',4);
	#echo 'Es wird geprüft ob IP-Adressprüfung in der Stelle durchgefürht werden muss.';
	# Prüfen ob IP in dieser Stelle geprüft werden muss
	if ($GUI->Stelle->checkClientIpIsOn()) {
		#echo '<br>IP-Adresse des Clients wird in dieser Stelle geprüft.';
    $GUI->debug->write('<br>IP-Adresse des Clients wird in dieser Stelle geprüft.',4);
		# Remote_Address mit ips des Users vergleichen
	  if ($GUI->user->clientIpIsValide(getenv('REMOTE_ADDR'))==false) {
	  	# Remote_Addr stimmt nicht mit den ips des Users überein
	  	# bzw. ist nicht innerhalb eines angegebenen Subnetzes
	    # Nutzer ist nicht berechtigt in die gewünschte Stelle zu wechseln
	    $Stelle_ID=$alteStelle;
      $GUI->Stelle=new stelle($Stelle_ID,$GUI->database);
	    $GUI->Fehlermeldung='Sie haben keine Berechtigung von dem Rechner mit der IP: '.getenv('REMOTE_ADDR'). ' auf die Stelle zuzugreifen.';
	    if($GUI->formvars['go'] == 'OWS'){
	      $GUI->formvars['go_plus'] = 'Exception';
	    }
	    else{
	      $go='Stelle Wählen';
	    }
		}
	} # end of IP-Adressen werden in der Stelle geprüft
} # End of IP-Adressenprüfung verfügbar

# Püfung ob das Alter der Passwörter in der Stelle geprüft werden müssen
if ($GUI->Stelle->checkPasswordAge==true){
	# Das Alter des Passwortes des Nutzers muß geprüft werden
	$remainingDays=checkPasswordAge($GUI->user->password_setting_time,$GUI->Stelle->allowedPasswordAge);
	#echo 'Verbleibende Tage '.$remainingDays;
	if ($remainingDays<=0) {
		# Der Geltungszeitraum des Passwortes ist abgelaufen
    $GUI->Fehlermeldung.='Das Passwort des Nutzers '.$GUI->user->login_name.' ist in der Stelle '.$GUI->Stelle->Bezeichnung.' abgelaufen. Passwörter haben in dieser Stelle nur eine Gütligkeit von '.$GUI->Stelle->allowedPasswordAge.' Monaten. Geben Sie ein neues Passwort ein und notieren Sie es sich.';
    if($GUI->formvars['go'] == 'OWS'){
    	 $GUI->Fehlermeldung.=' Melden Sie sich unter '.URL.' mit Ihrem alten Password an. Daraufhin werden Sie aufgefordert ein neues Passwort einzugeben. Ist dies erfolgt, können Sie diesen Dienst weiter nutzen.';
      $GUI->formvars['go_plus'] = 'Exception';
    }
    else{
    	# Setzen eines zufälligen Passwortes
    	$newPassword='xxx';
    	$go='logout';
    }
  }
}

# Abfragen der Einstellungen des Benutzers in der ausgewählten Stelle
# Rollendaten zuweisen
if(!$GUI->user->setRolle($Stelle_ID)){
	echo 'Dem aktuellen Nutzer ist keine Stelle zugeordet!';
	exit;
}

#echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
# Rollenbezogene Stellendaten zuweisen
$GUI->loadMultiLingualText($GUI->user->rolle->language);

# Ausgabe der Zugriffsinformationen in debug-Datei
$debug->write('User: '.$GUI->user->login_name,4);
$debug->write('Name: '.$GUI->user->Name.' '.$GUI->user->Vorname,4);
$debug->write('Stelle_ID: '.$GUI->Stelle->id,4);
$debug->write('Stellenbezeichnung: '.$GUI->Stelle->Bezeichnung,4);
$debug->write('Host_ID: '.getenv("REMOTE_ADDR"),4); 

if(!in_array($go, $non_spatial_cases)){		// für fast_cases, die keinen Raumbezug haben, den PGConnect und Trafos weglassen
	##############################################################################
	# Übergeben der Datenbank für die raumbezogenen Daten (PostgreSQL mit PostGIS)
	if(POSTGRES_DBNAME != ''){																													
		$PostGISdb=new pgdatabase();											
		$PostGISdb->host = POSTGRES_HOST;												
		$PostGISdb->user = POSTGRES_USER;													
		$PostGISdb->passwd = POSTGRES_PASSWORD;										
		$PostGISdb->dbName = POSTGRES_DBNAME;												
	}	
	else{
		# pgdbname ist leer, die Informationen zur Verbindung mit der PostGIS Datenbank
		# mit Geometriedaten werden aus der Tabelle stelle
		# der kvwmap-Datenbank $GUI->database gelesen
		$PostGISdb=new pgdatabase();
		$PostGISdb->host = $GUI->Stelle->pgdbhost;
		$PostGISdb->dbName = $GUI->Stelle->pgdbname;
		$PostGISdb->user = $GUI->Stelle->pgdbuser;
		$PostGISdb->passwd = $GUI->Stelle->pgdbpasswd;
		$PostGISdb->port = $GUI->Stelle->port;  
	}
	if ($PostGISdb->dbName!='') {
		# Übergeben der GIS-Datenbank für GIS-Daten an die GUI
		$GUI->pgdatabase=$PostGISdb;
		# Übergeben der GIS-Datenbank für die Bauaktendaten an die GUI
		$GUI->baudatabase=$PostGISdb;
		
		if (!$GUI->pgdatabase->open()) {
			echo 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden:';
			echo '<br>Host: '.$GUI->pgdatabase->host;
			echo '<br>User: '.$GUI->pgdatabase->user;
		 # echo '<br>Passwd: '.$GUI->database->passwd;
			echo '<br>Datenbankname: '.$GUI->pgdatabase->dbName;
			exit;
		}
		else {
			$debug->write("Verbindung zur PostGIS Datenbank erfolgreich hergestellt.",4);
			$GUI->pgdatabase->setClientEncoding();
		}
	}
	$GUI->epsg_codes = $GUI->pgdatabase->read_epsg_codes(false);
	# Umrechnen der für die Stelle eingetragenen Koordinaten in das aktuelle System der Rolle
	# wenn die EPSG-Codes voneinander abweichen
	if ($GUI->Stelle->epsg_code != $GUI->user->rolle->epsg_code){
		$user_epsg = $epsg_codes[$GUI->user->rolle->epsg_code];
		if($user_epsg['minx'] != ''){							// Koordinatensystem ist räumlich eingegrenzt
			if($GUI->Stelle->epsg_code != 4326){
				$projFROM = ms_newprojectionobj("init=epsg:".$GUI->Stelle->epsg_code);
				$projTO = ms_newprojectionobj("init=epsg:4326");
				$GUI->Stelle->MaxGeorefExt->project($projFROM, $projTO);			// max. Stellenextent wird in 4326 transformiert
			}
			// Vergleich der Extents und ggfs. Anpassung
			if($user_epsg['minx'] > $GUI->Stelle->MaxGeorefExt->minx)$GUI->Stelle->MaxGeorefExt->minx = $user_epsg['minx'];
			if($user_epsg['miny'] > $GUI->Stelle->MaxGeorefExt->miny)$GUI->Stelle->MaxGeorefExt->miny = $user_epsg['miny'];
			if($user_epsg['maxx'] < $GUI->Stelle->MaxGeorefExt->maxx)$GUI->Stelle->MaxGeorefExt->maxx = $user_epsg['maxx'];
			if($user_epsg['maxy'] < $GUI->Stelle->MaxGeorefExt->maxy)$GUI->Stelle->MaxGeorefExt->maxy = $user_epsg['maxy'];
			$projFROM = ms_newprojectionobj("init=epsg:4326");
			$projTO = ms_newprojectionobj("init=epsg:".$GUI->user->rolle->epsg_code);
			$GUI->Stelle->MaxGeorefExt->project($projFROM, $projTO);				// Transformation in das System des Nutzers
		}
		else{
			# Umrechnen der maximalen Kartenausdehnung der Stelle
			$projFROM = ms_newprojectionobj("init=epsg:".$GUI->Stelle->epsg_code);
			$projTO = ms_newprojectionobj("init=epsg:".$GUI->user->rolle->epsg_code);
			$GUI->Stelle->MaxGeorefExt->project($projFROM, $projTO);
		}
	}
}

if($_SESSION['login_routines'] == true){
# hier befinden sich Routinen, die beim einloggen des Nutzers einmalig durchgeführt werden
	# Löschen der Rollenlayer
	if(DELETE_ROLLENLAYER == 'true'){
		$mapdb = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
		$rollenlayerset = $mapdb->read_RollenLayer(NULL, 'search');
    for($i = 0; $i < count($rollenlayerset); $i++){   
      $mapdb->deleteRollenLayer($rollenlayerset[$i]['id']);
			$mapdb->delete_layer_attributes(-$rollenlayerset[$i]['id']);
      # auch die Klassen und styles löschen
			if($rollenlayerset[$i]['Class'] != ''){
				foreach($rollenlayerset[$i]['Class'] as $class){
					$mapdb->delete_Class($class['Class_ID']);
					if($class['Style'] != ''){
						foreach($class['Style'] as $style){
							$mapdb->delete_Style($style['Style_ID']);
						}
					}
				}
			}
    }
	}
	# Zurücksetzen des histtimestamps
	if($GUI->user->rolle->hist_timestamp != '')$GUI->setHistTimestamp();
	# Zurücksetzen der veränderten Klassen
	$GUI->user->rolle->resetClasses();
	$_SESSION['login_routines'] = false;
}

# Anpassen der Kartengröße an das Browserfenster
if($GUI->user->rolle->auto_map_resize AND $GUI->formvars['browserwidth'] != '')$GUI->resizeMap2Window();

if(isset($_FILES)) {
	foreach ($_FILES AS $datei) {
		$name = strtolower(basename($datei['name']));
		if(strpos($name,'.php') OR strpos($name,'.phtml') OR strpos($name,'.php3')) {
			echo 'PHP Dateien dürfen nicht hochgeladen werden. Auch nicht '.$datei['name'];
			move_uploaded_file($datei['tmp_name'],LOGPATH.'AusfuehrbareDatei_vom'.date('d.m.Y',time()).'_stelleID'.$GUI->Stelle->id.'_userID'.$GUI->user->id.'_'.$datei['name'].'.txt');
			unset($_FILES);
			exit;
		}
	}
}

?>