<?php
# Objekt für graphische Benutzeroberfläche erzeugen mit default-Werten
$GUI = new GUI("map.php", "main.css.php", "html");
$GUI->user = new stdClass();
$GUI->user->rolle = new stdClass();
$GUI->user->rolle->querymode = 0;
$GUI->allowed_documents = array();
$GUI->document_loader_name = session_id().rand(0,99999999).'.php';
$GUI->formvars=$formvars;
$GUI->echo = true;

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
if ($userDb == NULL){
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
      $GUI->debug->write("Verbindung zur MySQL Datenbank erfolgreich hergestellt.",4);
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
  $GUI->debug->write("Verbindung zur MySQL Kartendatenbank erfolgreich hergestellt.",4);
}

# Angeben, dass die Texte in latin1 zurückgegeben werden sollen
$GUI->database->execSQL("SET NAMES '".MYSQL_CHARSET."'",0,0);

/**
	Hier findet sich die gesamte Loging für Login und Reggistrierung, sowie Stellenwechsel
**/

# Test cases
if ($GUI->formvars['go'] == 'test') {
	if (in_array($GUI->formvars['case'], array('start'))) {
		$GUI->user->rolle->gui = '../tests/' . $GUI->formvars['case'] . '.php';
		$GUI->output();
	}
	else {
		echo 'Test nicht gefunden!';
	}
	exit;
}

# logout
if (is_logout($GUI->formvars)) {
	$GUI->debug->write('Logout angefragt.', 4, $GUI->echo);
	if (is_logged_in()) {
		$GUI->debug->write('Logout.', 4, $GUI->echo);
		logout($GUI);
	}
	else {
		$GUI->debug->write('Ist schon logged out.', 4, $GUI->echo);
	}
}

# login
$show_login_form = false;
if (is_logged_in()) {
	$GUI->debug->write('Ist angemeldet.', 4, $GUI->echo);
	$GUI->formvars['login_name'] = $_SESSION['login_name'];
	$GUI->user = new user($_SESSION['login_name'], 0, $GUI->database);
	# login case 1
}
else {
	$GUI->debug->write('Nicht angemeldet.', 4, $GUI->echo);
	if (is_gast_login($GUI->formvars, $gast_stellen)) {
		$GUI->debug->write('Es ist eine Gastanmeldung.', 4, $GUI->echo);
		if (has_width_and_height($GUI->formvars)) {
			$GUI->debug->write('Hat width und height. (' . $GUI->formvars['browserwidth'] . 'x' . $GUI->formvars['browserheight'] . ')', 4, $GUI->echo);
			$gast = $userDb->create_new_gast($_REQUEST['gast']);
			$GUI->formvars['login_name'] = $gast['username'];
			$GUI->formvars['passwort'] = $gast['passwort'];
			$GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database, $GUI->formvars['passwort']);
			$GUI->user->stelle_id = $GUI->formvars['gast']; # set new stelle
			# login case 2
		}
		else {
			$GUI->debug->write('Hat kein width und height. Frage sie ab.', 4, $GUI->echo);
			# // ToDo: frage browser width und height ab.
			$show_login_form = true;
			$go = 'login_browser_size';
			# Test case 3
		}
	}
	else { # ist keine gastanmeldung
		$GUI->debug->write('Es ist keine Gastanmeldung.', 4, $GUI->echo);

		if (is_login($GUI->formvars)) {
			$GUI->debug->write('Es ist eine reguläre Anmeldung.', 4, $GUI->echo);

			# Frage den Nutzer mit dem login_namen ab
			$GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database, $GUI->formvars['passwort']);

			if (is_login_granted($GUI->user, $GUI->formvars['login_name'])) {
				$GUI->debug->write('Anmeldung war erfolgreich, Benutzer wurde mit angegebenem Passwort gefunden.', 4, $GUI->echo);
				Nutzer::reset_num_login_failed($GUI, $GUI->formvars['login_name']);

				if (
					!defined('AGREEMENT_MESSAGE') OR
					AGREEMENT_MESSAGE == '' OR
					is_agreement_accepted($GUI->user)
				) {
					$GUI->debug->write('Agreement ist akzeptiert.', 4, $GUI->echo);

					if (is_new_password($GUI->formvars)) {
						$GUI->debug->write('Es wurde ein neues Passwort angegeben.', 4, $GUI->echo);
						$new_password_err = isPasswordValide($GUI->formvars['passwort'], $GUI->formvars['new_password'], $GUI->formvars['new_password_2']);

						if (is_new_password_valid($new_password_err)) {
							$GUI->debug->write('Neues Password ist valid.', 4, $GUI->echo);
							update_password($GUI);
							# login case 5
						}
						else { # new password is not ok
							$GUI->debug->write('Neues Password ist nicht valid. Zurück zur Anmeldung mit Fehlermeldung.', 4, $GUI->echo);
							$GUI->Fehlermeldung = $new_password_err . '!<br>Vorschlag für ein neues Password: <b>' . createRandomPassword(8) . '</b><br>';
							$show_login_form = true;
							$go = 'login_new_password';
							# login case 6
						}
					}
					else {
						$GUI->debug->write('Es wurde kein neues Passwort angegeben.', 4, $GUI->echo);
						# login case 4
					}
				}
				else {
					if ($GUI->formvars['agreement_accepted'] == '1') {
						$GUI->debug->write('Nutzer bestätigt Agreement. Trage das ein.', 4, $GUI->echo);
						$GUI->user->update_agreement_accepted($GUI->formvars['agreement_accepted']);
					}
					else {
						$GUI->debug->write('Agreement ist nicht akzeptiert.', 4, $GUI->echo);
						$show_login_form = true;
						$go = 'login_agreement';
						# login case 16
					}
				}
			}
			else { # Anmeldung ist fehlgeschlagen
				$GUI->debug->write('Anmeldung ist fehlgeschlagen.', 4, $GUI->echo);
				$GUI->formvars['num_failed'] = Nutzer::increase_num_login_failed($GUI, $GUI->formvars['login_name']);
				sleep($GUI->formvars['num_failed'] * $GUI->formvars['num_failed']);
				$show_login_form = true;
				$go = 'login_failed';
				# login case 7
			}
		}
		else { # ist keine Anmeldung
			$GUI->debug->write('Es ist keine Anmeldung.', 4, $GUI->echo);

			if (is_registration($GUI->formvars)) {
				$GUI->debug->write('Es ist eine Registrierung.', 4, $GUI->echo);

				if (is_new_password($GUI->formvars)) {
					$GUI->debug->write('Registrierung mit neuem Passwort.', 4, $GUI->echo);
					$new_registration_err = checkRegistration($GUI);

					if (is_registration_valid($new_registration_err)) {
						$GUI->debug->write('Registrierung ist valide.', 4, $GUI->echo);

						# // ToDo: Create a new user and go to login with user and password
						$result = Nutzer::register($GUI, $GUI->formvars['stelle_id']);
						
						
						if ($result['success']) {
							$invitation = Invitation::find_by_id($GUI, $GUI->formvars['token']);
							$invitation->set('completed', date("Y-m-d H:i:s"));
							$invitation->update();
							$GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database);
							unset($GUI->formvars['Stelle_id']);
							$GUI->add_message('info', 'Nutzer erfolgreich angelegt.<br>Willkommen im WebGIS kvwmap.');
							# login case 9
						}
						else {
							$GUI->Fehlermeldung = 'Datenbankfehler beim Anlegen des Nutzers.<br>' . $result['msg'];
							$show_login_form = true;
							$go = 'login_registration';
							# login case 11
						}
					}
					else {
						$GUI->debug->write('Registrier ist nicht valid.', 4, $GUI->echo);
						$GUI->Fehlermeldung = $new_registration_err . '<br>Die Registrierung ist nicht erfolgreich.<br>Versuchen Sie es erneut oder lassen Sie sich erneut einladen.';
						$show_login_form = true;
						$go = 'login_registration';
						# login case 11
					}
				}
				else {
					$GUI->debug->write('Es wurde noch kein neues Passwort für die Registrierung vergeben.', 4, $GUI->echo);
					$show_login_form = true;
					$go = 'login_registration';
					# login case 10
				}
			}
			else { # keine Registrierung
				$GUI->debug->write('Es ist keine Registrierung.', 4, $GUI->echo);
				$show_login_form = true;
				$go = 'login';
				# login case 8
			} # ende keine Registrierung
		} # ende keine Anmeldung
	} # ende keine gastanmeldung
} # ende nicht angemeldet

# $show_login_form = true nach login cases 3, 6, 7, 8, 9, 10
if (!$show_login_form) {
	if (is_new_stelle($GUI->formvars['Stelle_ID'], $GUI->user)) {
		$GUI->debug->write('Neue Stelle ' . $GUI->formvars['Stelle_ID'] . ' angefragt.', 4, $GUI->echo);
		$GUI->Stelle = new stelle($GUI->formvars['Stelle_ID'], $GUI->database);
	}
	else {
		$GUI->debug->write('Keine neue Stelle angefragt. Stelle: ' . $GUI->user->stelle_id . ' bleibt.', 4, $GUI->echo);
		$GUI->Stelle = new stelle($GUI->user->stelle_id, $GUI->database);
	}

	# check stelle wenn noch nicht angemeldet gewesen oder auch wenn stelle gewechselt wird.
	if (!is_logged_in() OR is_new_stelle($GUI->formvars['Stelle_ID'], $GUI->user)) {
		$GUI->debug->write('Zugang zu Stelle ' . $GUI->Stelle->id . ' wird angefragt.', 4, $GUI->echo);

		$GUI->user->Stellen = $GUI->user->getStellen(0);
		$permission = get_permission_in_stelle($GUI);

		if ($permission['allowed']) {
			$GUI->debug->write('Nutzer ist in Stelle ' . $GUI->Stelle->id . ' erlaubt.', 4, $GUI->echo);
			$GUI->user->stelle_id = $GUI->Stelle->id; # set selected stelle to user
			$GUI->user->updateStelleID($GUI->Stelle->id);
		}
		else {
			$GUI->debug->write('Zugang zur Stelle ' . $GUI->Stelle->id . ' für Nutzer nicht erlaubt weil: ' . $permission['reason'], 4, $GUI->echo);
			$GUI->Fehlermeldung = $permission['errmsg'];

			if (is_ows_request($GUI->formvars)) {
				$GUI->debug->write('OWS Request führt zu Exception.', 4);
				$GUI->Fehlermeldung .= ' Melden Sie sich unter ' . URL . ' mit Ihrem alten Password an. Daraufhin werden Sie aufgefordert ein neues Passwort einzugeben. Ist dies erfolgt, können Sie diesen Dienst weiter nutzen.';
				$go = 'OWS_Exception';
			}
			else {
				$GUI->debug->write('Kein OWS Request.', 4);

				if ($permission['reason'] == 'password expired') {
					$GUI->debug->write('Passwort ist abgelaufen. Frage neues ab.', 4, $GUI->echo);
					$GUI->passwort_abgelaufen = true;
					$show_login_form = true;
					$go = 'login_new_password';
					# login case 11
				}
				else {
					$GUI->debug->write('Passwort ist nicht abgelaufen.', 4);
					$go = 'Stelle_waehlen';
				}
			}
		}
	}
}

# $show_login_form = true nach login cases 3, 6, 7, 8, 9, 10, 11
if ($show_login_form) {
	$GUI->debug->write('Zeige Login-Form', 4, $GUI->echo);
	$GUI->user->rolle = new stdClass();
	$GUI->user->rolle->querymode = 0;
}
else {
	$GUI->debug->write('Lade Stelle und Rolle.', 4, $GUI->echo);
	# Alles was man immer machen muss bevor die go's aufgerufen werden
	$GUI->user->setRolle($GUI->user->stelle_id);

	#$GUI->debug->write('Eingestellte Rolle: ' . print_r($GUI->user->rolle, true), 4, $GUI->echo);

	#echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
	# Rollenbezogene Stellendaten zuweisen
	$GUI->loadMultiLingualText($GUI->user->rolle->language);

	$GUI->debug->write('Set Session', 4, $GUI->echo);
	set_session_vars($GUI->formvars);

	#$GUI->debug->write('<p>Session: ' . print_r($_SESSION, true), 4, $GUI->echo);

	# Ausgabe der Zugriffsinformationen in debug-Datei
	$GUI->debug->write('User: ' . $GUI->user->login_name, 4);
	$GUI->debug->write('Name: ' . $GUI->user->Name.' '.$GUI->user->Vorname, 4);
	$GUI->debug->write('Stelle_ID: ' . $GUI->Stelle->id, 4);
	$GUI->debug->write('Stellenbezeichnung: ' . $GUI->Stelle->Bezeichnung, 4);
	$GUI->debug->write('Host_ID: ' . getenv("REMOTE_ADDR"), 4);

	if(BEARBEITER == 'true'){
		define('BEARBEITER_NAME', 'Bearbeiter: ' . $GUI->user->Name);
	}

	if (!in_array($go, $non_spatial_cases)) {	// für fast_cases, die keinen Raumbezug haben, den PGConnect und Trafos weglassen
		##############################################################################
		# Übergeben der Datenbank für die raumbezogenen Daten (PostgreSQL mit PostGIS)
		if(POSTGRES_DBNAME != '') {
			$PostGISdb=new pgdatabase();
			$PostGISdb->host = POSTGRES_HOST;
			$PostGISdb->user = POSTGRES_USER;
			$PostGISdb->passwd = POSTGRES_PASSWORD;
			$PostGISdb->dbName = POSTGRES_DBNAME;
		}
		else {
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

		if ($PostGISdb->dbName != '') {
			# Übergeben der GIS-Datenbank für GIS-Daten an die GUI
			$GUI->pgdatabase = $PostGISdb;
			# Übergeben der GIS-Datenbank für die Bauaktendaten an die GUI
			$GUI->baudatabase = $PostGISdb;

			if (!$GUI->pgdatabase->open()) {
				echo 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden:';
				echo '<br>Host: '.$GUI->pgdatabase->host;
				echo '<br>User: '.$GUI->pgdatabase->user;
			 # echo '<br>Passwd: '.$GUI->database->passwd;
				echo '<br>Datenbankname: '.$GUI->pgdatabase->dbName;
				exit;
			}
			else {
				$GUI->debug->write("Verbindung zur PostGIS Datenbank erfolgreich hergestellt.", 4);
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

	if($_SESSION['login_routines'] == true) {
		define('AFTER_LOGIN', true);
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
		#$GUI->user->rolle->resetClasses();
		$_SESSION['login_routines'] = false;
	} else {
			define('AFTER_LOGIN', false);
	}

	# Anpassen der Kartengröße an das Browserfenster
	if ($GUI->user->rolle->auto_map_resize AND $GUI->formvars['browserwidth'] != '') {
		$GUI->resizeMap2Window();
	}

	if(isset($_FILES)) {
		foreach ($_FILES AS $datei) {
	    if (!is_array($datei['name'])) # $datei so umformen als wäre es ein multi file upload
	      $datei = array_map(
	        function($attribute) {
	          return array($attribute);
	        },
	        $datei
	      );
	    foreach ($datei['name'] AS $i => $datei_name) {
	    	$base_name = strtolower(basename($datei_name));
	    	if(strpos($base_name, '.php') OR strpos($base_name, '.phtml') OR strpos($base_name, '.php3'))
	        $forbidden_files[] = array('name' => $datei_name, 'tmp_name' => $datei['tmp_name'][$i]);
	    }
		}
	  if (count($forbidden_files) > 0) {
	    echo 'PHP Dateien dürfen nicht hochgeladen werden. Auch nicht:';
	    foreach ($forbidden_files AS $forbidden_file) {
		    echo '<br>' . $forbidden_file['name'];
		    move_uploaded_file(
	        $forbidden_file['tmp_name'],
	        LOGPATH . 'AusfuehrbareDatei_vom' . date('c',time()) . '_stelleID' . $GUI->Stelle->id . '_userID' . $GUI->user->id . '_' . $forbidden_file['name'] . '.txt'
	      );
	    }
			unset($_FILES);
			exit;
	  }
	}
}

/**
 Functions
**/

function is_logout($formvars) {
	return ($formvars['go'] == 'logout');
}

function is_logged_in() {
	return (array_key_exists('angemeldet', $_SESSION) AND $_SESSION['angemeldet'] === true AND $_SESSION['login_name'] != '');
}

function is_logged_out() {
	return !is_logged_in();
}

function is_gast_login($formvars, $gast_stellen) {
	return $formvars['gast'] != '' AND $formvars['login_name'] == '' AND in_array($formvars['gast'], $gast_stellen);
}

function has_width_and_height($var) {
	return (intval($var['browserwidth']) > 0 AND intval($var['browserheight'] > 0));
}

function is_login($formvars) {
	return $formvars['login_name'] != '' AND $formvars['passwort'] != '';
}

function is_login_granted($user, $login_name) {
	return $user->login_name == $login_name;
}

function is_agreement_accepted($user) {
	return $user->agreement_accepted == 1;
}

function is_new_stelle($new_stelle_id, $user) {
	return ($new_stelle_id != '' AND $new_stelle_id != $user->stelle_id);
}

function is_user_member_in_stelle($user_stelle_id, $allowed_stellen_ids) {
	return in_array($user_stelle_id, $allowed_stellen_ids);
}

function get_permission_in_stelle($GUI) {
	$GUI->debug->write('start get permission in stelle', 4, $GUI->echo);
	$allowed = true;
	$reason = '';
	$errmsg = '';

	if (is_user_member_in_stelle($GUI->Stelle->id, $GUI->user->Stellen['ID'])) {
		$GUI->debug->write('Nutzer gehört zur Stelle ' . $GUI->Stelle->id, 4, $GUI->echo);

		if (is_password_expired($GUI->user, $GUI->Stelle)) {
			$GUI->debug->write('Passwort ist abgelaufen.', 4, $GUI->echo);
			$allowed = false;
			$reason = 'password expired';
			$errmsg = 'Das Passwort des Nutzers ' . $GUI->user->login_name . ' ist in der Stelle ' . $GUI->stelle->Bezeichnung . ' abgelaufen. Passwörter haben in dieser Stelle nur eine Gütligkeit von ' . $GUI->Stelle->allowedPasswordAge . ' Monaten. Geben Sie ein neues Passwort ein und notieren Sie es sich.';
		}
		else {
			$GUI->debug->write('Passwort ist nicht abgelaufen.', 4, $GUI->echo);

			if (CHECK_CLIENT_IP) {
				$GUI->debug->write('Es wird geprüft ob IP-Adressprüfung in der Stelle durchgeführt werden muss.', 4);

				if ($GUI->Stelle->checkClientIpIsOn()) {
					$GUI->debug->write('IP-Adresse des Clients wird in dieser Stelle geprüft.', 4);

					if ($GUI->user->clientIpIsValide(getenv('REMOTE_ADDR')) == false) {
						$GUI->debug->write('IP-Adresse des Clients ist in der Stelle valid.', 4);
						$allowed = false;
						$reason = 'IP not allowed';
						$errmsg = 'Sie haben keine Berechtigung von dem Rechner mit der IP: ' . getenv('REMOTE_ADDR') . ' auf die Stelle zuzugreifen.';
					}
				}
			}
		}
	}
	else {
		$GUI->debug->write('Nutzer gehört nicht zur Stelle ' . $GUI->Stelle->id, 4, $GUI->echo);
		$allowed = false;
	}
	return array(
		'allowed' => $allowed,
		'reason' => $reason,
		'errmsg' => $errmsg
	);
}

function is_new_password($formvars) {
	return $formvars['new_password'] != '';
}

function is_new_password_valid($msg) {
	return ($msg == '');
}

function is_password_expired($user, $stelle) {
	$abgelaufen = false;
	if ($stelle->checkPasswordAge) {
		$remainingDays = checkPasswordAge($user->password_setting_time, $stelle->allowedPasswordAge);
		#echo '<br>Verbleibende Tage '.$remainingDays;
		return ($remainingDays <= 0);
	}
	return $abgelaufen;
}

function is_registration($formvars) {
	return strpos($formvars['go'], 'invitation') === false AND $formvars['token'] != '' AND $formvars['email'] != '' AND $formvars['stelle_id'] != '';
}

function checkRegistration($gui) {
	include_once(CLASSPATH . 'Invitation.php');
	$params = $gui->formvars;
	$registration_errors = array();
	$check = 0;

	# Prüft ob ein Name übergeben wurde
	if ($params['Name'] == '') {
		$registration_errors[] = 'Name fehlt.';
		$check = 1;
	}

	# Prüft ob ein login_name übergeben wurde
	if ($check == 0 AND $params['login_name'] == '') {
		$registration_errors[] = 'Parameter login_name fehlt.';
		$check = 1;
	}

	# Prüft ob login_name schon existiert
	if ($check == 0) {
		$user = Nutzer::find_by_login_name($gui, $params['login_name']);
		if ($user->get('login_name') == $params['login_name']) {
			$registration_errors[] = 'login_name: ' . $params['login_name'] . ' existiert schon im System.<br>Bitte wählen sie einen anderen aus.';
			$check = 1;
		}
	}

	# Prüft ob new_password und new_password_2 valide sind
	if ($check == 0) {
		$password_errors = isPasswordValide('', $params['new_password'], $params['new_password_2']);
		if ($password_errors != '') {
			$registration_errors[] = $password_errors . '<br>Passwörter der Registrierung nicht valide.';
			$check = 1;
		}
	}

	# Prüft ob ein token übergeben wurde
	if ($check == 0 AND $params['token'] == '') {
		$registration_errors[] = 'Parameter token fehlt.';
		$check = 1;
	}

	# Prüft ob eine Einladung zum token existiert
	if ($check == 0) {
		$invitation = Invitation::find_by_id($gui, $params['token']);
		if ($invitation->get('token') != $params['token']) {
			$registration_errors[] = 'Einladung zu token: ' . $params['token'] . ' nicht gefunden.<br>Prüfen Sie ob Sie den richtigen Link aufgerufen oder<br>die URL richtig kopiert haben.';
			$check = 1;
		}
	}

	# Prüft ob Einladung schon wahrgenommen wurde
	if ($check == 0 AND $invitation->get('compleeted') != '') {
		$registration_errors[] = 'Einladung zu token: ' . $params['token'] . ' wurde schon am: ' . $invitation->get('completed') . ' wahrgenommen.';
		$check = 1;
	}

	# Prüft ob eine korrekte email übergeben wurde
	$email_errors = emailcheck($params['email']);

	if ($check == 0 AND $email_errors != '') {
		$registration_errors[] = $email_errors;
		$check = 1;
	}

	# Prüft ob email zum token passt
	if ($check == 0 AND $params['email'] != $invitation->get('email')) {
		$registration_errors[] = 'email: ' . $params['email'] . ' passt nicht zu<br>token: ' . $params['token'] . '.';
		$check = 1;
	}

	# Prüft ob eine stellen_id übergeben wurde
	if ($check == 0 AND $params['stelle_id'] == '') {
		$registration_errors[] = 'Parameter stellen_id fehlt.';
		$check = 1;
	}

	# Prüft ob stelle_id zum token passt
	if ($check == 0 AND $params['stelle_id'] != $invitation->get('stelle_id')) {
		$registration_errors[] = 'stelle_id: ' . $params['stelle_id'] . ' passt nicht zu<br>token: ' . $params['token'] . '.';
		$check = 1;
	}

	return implode('<br>', $registration_errors);
}


function is_registration_valid($msg) {
	return ($msg == '');
}

function is_ows_request($formvars) {
	return ($formvars['go'] == 'OWS');
}

function logout() {
	session_start();
	$_SESSION = array();
	if (ini_get("session.use_cookies")){
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}
	session_destroy();
}

function update_password($GUI) {
	$GUI->user->setNewPassword($GUI->formvars['new_password']);
	$GUI->user->password_setting_time=date('Y-m-d H:i:s',time());
	$GUI->add_message('notice', 'Password ist erfolgreich geändert worden.');
}

function set_session_vars($formvars) {
	$_SESSION['angemeldet'] = true;
	$_SESSION['login_name'] = $formvars['login_name'];
	$_SESSION['login_routines'] = true;
	$_SESSION['CONTEXT_PREFIX'] = $_SERVER['CONTEXT_PREFIX'];
}
?>
