<?php
$language = (in_array($_REQUEST['language'], array('german', 'english', 'low-german', 'polish', 'vietnamese')) ? $_REQUEST['language'] : 'german');
include_once(LAYOUTPATH . 'languages/start_' . $language . '.php');
$errors = array();

# Objekt für graphische Benutzeroberfläche erzeugen mit default-Werten
$GUI = new GUI("map.php", "layouts/css/main.css.php", "html");
$GUI->user = new stdClass();
$GUI->user->rolle = new stdClass();
$GUI->user->rolle->querymode = 0;
$GUI->allowed_documents = array();
$GUI->document_loader_name = session_id().rand(0,99999999).'.php';
$GUI->formvars = $formvars;
$GUI->echo = false;

#################################################################################
# Setzen der Konstante, ob in die Datenbank geschrieben werden soll oder nicht.
# Kann z.B. zu Testzwecken ausgeschaltet werden.
if (array_key_exists('disableDbWrite', $GUI->formvars) and $GUI->formvars['disableDbWrite'] == '1') {
	define('DBWRITE', false);
}
else {
	define('DBWRITE', DEFAULTDBWRITE);
}
if (!DBWRITE) { echo '<br>Das Schreiben in die Datenbank wird unterdrückt!'; }

# Öffnen der Datenbankverbindung zur Kartenverwaltung (MySQL)
# Erzeugen des MYSQL-DB-Objekts, falls es noch nicht durch den Login erzeugt wurde
if (!isset($userDb)) {
	$userDb = new database();
	$userDb->host = MYSQL_HOST;
	$userDb->user = MYSQL_USER;
	$userDb->passwd = MYSQL_PASSWORD;
	$userDb->dbName = MYSQL_DBNAME;
}
$GUI->database = $userDb;

if ($formvars['go'] == 'health_check') {
	include(SNIPPETS . 'health_check.php');
	exit;
}

if (!$GUI->database->open()) {
  # Prüfen ob eine neue Datenbank angelegt werden soll
  if ($GUI->formvars['go'] == 'install-mysql-db') {
    # Anlegen der neuen Datenbank
    # Herstellen der Verbindung mit defaultwerten
		$GUI->mysqli = new mysqli(MYSQL_HOST, 'kvwmap', 'kvwmap', 'mysql');
    $GUI->debug->write('MySQL Datenbankverbindung hergestellt mit (' . MYSQL_HOST . ', kvwmap, kvwmap, mysql) thread_id: ' . $GUI->mysqli->thread_id, 4);
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
    if ($GUI->database->select_db($GUI->database->dbName)) {
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
    } # ende fehler beim aufbauen der mysql datenbank
  } # ende mysql datenbank installieren
  else {
    # Es konnte keine Datenbankverbindung aufgebaut werden
    $errors[] = '
		Die Verbindung zur Kartendatenbank konnte mit folgenden Daten nicht hergestellt werden:<br>
		Host: ' . $GUI->database->host . '<br>
		User: ' . $GUI->database->user . '<br>
		Datenbankname: ' . $GUI->database->dbName . '<br>';
		exit;
  }
}
else {
  $GUI->debug->write("Verbindung zur MySQL Kartendatenbank erfolgreich hergestellt.",4);
}

$GUI->database->execSQL("SET NAMES '".MYSQL_CHARSET."'",0,0);
$GUI->database->execSQL("SET CHARACTER SET ".MYSQL_CHARSET.";",0,0);

/*
*	Hier findet sich die gesamte Loging für Login und Reggistrierung, sowie Stellenwechsel
*/
#$GUI->debug->write('Formularvariablen: ' . print_r($GUI->formvars, true), 4, $GUI->echo);
# logout
if (is_logout($GUI->formvars)) {
	$GUI->debug->write('Logout angefragt.', 4, $GUI->echo);
	if (is_logged_in()) {
		$GUI->user = new user($_SESSION['login_name'], 0, $GUI->database);
		if (LOGOUT_ROUTINE != '' AND file_exists(LOGOUT_ROUTINE) AND is_file(LOGOUT_ROUTINE)) {
			include(LOGOUT_ROUTINE);
		}
		$GUI->debug->write('Logout.', 4, $GUI->echo);
		logout();
	}
	else {
		#$GUI->add_message('info', $strLoggedOutAlready);
		$GUI->debug->write('Ist schon logged out.', 4, $GUI->echo);
	}
	$GUI->formvars['go'] = '';
}

# login
$show_login_form = false;
if (is_logged_in()) {
	$GUI->debug->write('Ist angemeldet an: ' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_URL'], 4, $GUI->echo);
	if ($_SESSION['login_name'] == '') {
		$GUI->debug->write('login_name in Session ist leer', 4, $GUI->echo);
		logout();
		$show_login_form = true;
		$go = 'login';
	}
	$GUI->formvars['login_name'] = $_SESSION['login_name'];
	$GUI->debug->write('Ist angemeldet als: ' . $_SESSION['login_name'], 4, $GUI->echo);
	$GUI->user = new user($_SESSION['login_name'], 0, $GUI->database);
	if ($GUI->user->login_name == '') {
		$GUI->debug->write('Nutzer mit login_name: ' . $_SESSION['login_name'] . ' nicht in Datenbank vorhanden.', 4, $GUI->echo);
		logout();
		$show_login_form = true;
		$go = 'login';
	}
	else {
		$GUI->debug->write('Nutzerdaten gelesen von: ' . $GUI->user->login_name, 4, $GUI->echo);
	}
	# login case 1
}
else {
	header('logout: true');		// damit ajax-Requests das auch mitkriegen
	$GUI->debug->write('Nicht angemeldet.', 4, $GUI->echo);
	if (is_gast_login($GUI->formvars, $gast_stellen)) {
		$GUI->formvars['gast'] = intval($GUI->formvars['gast']);
		$GUI->debug->write('Es ist eine Gastanmeldung.', 4, $GUI->echo);
		if (has_width_and_height($GUI->formvars)) {
			if (width_or_height_empty($GUI->formvars)) {
				$GUI->formvars = set_width_or_height_default($GUI->formvars);
			}
			$GUI->debug->write('Hat width und height. (' . $GUI->formvars['browserwidth'] . 'x' . $GUI->formvars['browserheight'] . ')', 4, $GUI->echo);
			$gast = $userDb->create_new_gast($GUI->formvars['gast']);
			$GUI->formvars['login_name'] = $gast['username'];
			$GUI->formvars['passwort'] = $gast['passwort'];
			$GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database, $GUI->formvars['passwort']);
			$GUI->user->stelle_id = $GUI->formvars['gast']; # set new stelle
			set_session_vars($GUI->formvars);
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
			/**
				This set the passwort with the sha1 method before each login
				if not allready exists and only if it matches with the old md5 method.
			*/
			if (prepare_sha1(trim($GUI->database->mysqli->real_escape_string($GUI->formvars['login_name'])), trim($GUI->database->mysqli->real_escape_string($GUI->formvars['passwort'])))) {
				if ($GUI->database->mysqli->affected_rows > 0) {
					$GUI->debug->write('Passwort mit SHA1 Methode für login_name ' . $GUI->formvars['login_name'] . ' eingetragen.', 4, $GUI->echo);
				}
				$GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database);
				$GUI->debug->write('Nutzer mit login_name ' . $GUI->formvars['login_name'] . ' abgefragt.', 4, $GUI->echo);
				if ($GUI->database->success) {
					if ($GUI->is_login_granted($GUI->user, $GUI->formvars['login_name'], $GUI->formvars['passwort'])) {
						$GUI->debug->write('Nutzer mit id: ' . $GUI->user->id . ' gefunden. Setze Session.', 4, $GUI->echo);
						set_session_vars($GUI->formvars);
						$GUI->user->update_tokens($_SESSION['csrf_token']);
						$GUI->user->has_logged_in = true;
						$GUI->debug->write('Anmeldung war erfolgreich, Benutzer wurde mit angegebenem Passwort gefunden.', 4, $GUI->echo);
						$nutzer = Nutzer::reset_num_login_failed($GUI, $GUI->formvars['login_name']);
						$GUI->user->num_login_failed 		= $GUI->formvars['num_failed'] = 0;
						$GUI->user->login_locked_until 	= '';
						if ($GUI->user->stelle_id == '') {
							# Nutzer hat keine stellen_id
							$GUI->user->Stellen = $GUI->user->getStellen(0);
							if (count($GUI->user->Stellen['ID']) > 0) {
								# Nutzer hat aber rollen, weise die stellen_id der ersten Rolle zu
								$GUI->formvars['Stelle_ID'] = $GUI->user->Stellen['ID'][0];
							}
						}
					}
					else {
						# Anmeldung ist fehlgeschlagen
						$GUI->debug->write('Anmeldung ist fehlgeschlagen. Grund: ' . $GUI->login_failed_reason, 4, $GUI->echo);
						if ($GUI->login_failed_reason == 'authentication') {
							$GUI->debug->write('Passwort passt nicht zum login_namen:', 4, $GUI->echo);
							$nutzer = Nutzer::increase_num_login_failed($GUI, $GUI->formvars['login_name']);
							$GUI->user->num_login_failed 		= $GUI->formvars['num_failed'] = $nutzer->get('num_login_failed');
							$GUI->user->login_locked_until 	= $nutzer->get('login_locked_until');
							$GUI->user->language = ($nutzer->get_rolle() ? $nutzer->rolle->get('language') : '');
#							sleep($GUI->formvars['num_failed'] * $GUI->formvars['num_failed']);
						}
						if ($GUI->login_failed_reason == 'login_is_locked') {
							$nutzer = Nutzer::find_by_login_name($GUI, $GUI->formvars['login_name']);
							$GUI->user->language = ($nutzer->get_rolle() ? $nutzer->rolle->get('language') : '');
						}
						$show_login_form = true;
						$go = 'login_failed';
						# login case 7
					}
				}
				else {
					$GUI->add_message('error', 'Fehler bei der Abfrage des Nutzers. ' . $GUI->database->mysqli->error);
					$show_login_form = true;
					$go = 'login_failed';
					# login case 7 b
				}
			}
			else {
				$GUI->add_message('error', 'Fehler beim Eintragen des SHA1 Passwortes. ' . $GUI->database->mysqli->error);
				$show_login_form = true;
				$go = 'login_failed';
			};
		}
		else { # ist keine Anmeldung
			$GUI->debug->write('Es ist keine Anmeldung.', 4, $GUI->echo);

			if (is_registration($GUI->formvars)) {
				$GUI->debug->write('Es ist eine Registrierung.', 4, $GUI->echo);

				if (is_new_password($GUI->formvars)) {
					$GUI->debug->write('Registrierung mit neuem Passwort.', 4, $GUI->echo);
					array_walk(
						$GUI->formvars,
						function(&$formvar, $key, $database) {
							$formvar = $database->mysqli->real_escape_string($formvar);
						},
						$GUI->database
					);

					$new_registration_err = checkRegistration($GUI);

					if (is_registration_valid($new_registration_err)) {
						$GUI->debug->write('Registrierung ist valide.', 4, $GUI->echo);

						$result = Nutzer::register($GUI, $GUI->formvars['Stelle_ID']);

						if ($result['success']) {
							$invitation = Invitation::find_by_id($GUI, $GUI->formvars['token']);
							$invitation->set('completed', date("Y-m-d H:i:s"));
							$invitation->update();
							$GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database);
							$GUI->add_message('info', 'Nutzer erfolgreich angelegt.<br>Willkommen im WebGIS kvwmap.');
							$GUI->debug->write('Set Session', 4, $GUI->echo);
							set_session_vars($GUI->formvars);
							unset($GUI->formvars['Stelle_ID']);
							unset($GUI->formvars['token']);
							unset($GUI->fromvars['passwort']);
							unset($GUI->formvars['new_password']);
							unset($GUI->formvars['new_password_2']);
							unset($GUI->formvars['email']);
							unset($GUI->formvars['Name']);
							unset($GUI->formvars['Vorname']);
							unset($GUI->formvars['Namenszusatz']);
							unset($GUI->formvars['phon']);
							# login case 9
						}
						else {
							$GUI->add_message('error', 'Datenbankfehler beim Anlegen des Nutzers.<br>' . $result['msg']);
							$show_login_form = true;
							$go = 'login_registration';
							# login case 10
						}
					}
					else {
						$GUI->debug->write('Registrierung ist nicht valid.', 4, $GUI->echo);
						$GUI->add_message('error', $new_registration_err . '<br>Die Registrierung ist nicht erfolgreich.<br>Versuchen Sie es erneut oder lassen Sie sich erneut einladen.');
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
	if (is_new_stelle($GUI->formvars, $GUI->user)) {
		$GUI->debug->write('Neue Stelle ' . $GUI->formvars['Stelle_ID'] . ' angefragt.', 4, $GUI->echo);
		$GUI->Stelle = new stelle($GUI->formvars['Stelle_ID'], $GUI->database);
	}
	else {
		$GUI->debug->write('Keine neue Stelle angefragt. Stelle: ' . $GUI->user->stelle_id . ' bleibt.', 4, $GUI->echo);
		$GUI->Stelle = new stelle($GUI->user->stelle_id, $GUI->database);
		if ($GUI->database->errormessage != '') {
			$GUI->add_message('error', 'Die Stelle kann nicht abgefragt werden. Prüfen Sie ob das Datenmodell der Stelle aktuell ist!');
			logout();
			$show_login_form = true;
			$go = 'login';
		}
	}

	# check stelle wenn noch nicht angemeldet gewesen, wenn noch nicht in Stelle angemeldet auch wenn stelle gewechselt wird.
	if (is_login($GUI->formvars) OR !is_logged_in_stelle() OR is_new_stelle($GUI->formvars, $GUI->user)) {
		$GUI->debug->write('Zugang zu Stelle ' . $GUI->Stelle->id . ' wird angefragt.', 4, $GUI->echo);

		$GUI->user->Stellen = $GUI->user->getStellen(0);
		$permission = get_permission_in_stelle($GUI);

		if ($permission['allowed']) {
			$GUI->debug->write('Nutzer ist in Stelle ' . $GUI->Stelle->id . ' erlaubt.', 4, $GUI->echo);
			$GUI->user->stelle_id = $GUI->Stelle->id; # set selected stelle to user
			$GUI->debug->write('Setze neue Stellen-ID: ' . $GUI->Stelle->id . ' für Nutzer: ' . $GUI->user->id, 4, $GUI->echo);
			$GUI->user->updateStelleID($GUI->Stelle->id);
			$_SESSION['stelle_angemeldet'] = true;
			# login case 15
		}
		else {
			$GUI->debug->write('Zugang zur Stelle ' . $GUI->Stelle->id . ' für Nutzer fehlgeschlagen weil: ' . $permission['reason'] . '<br>', 4, ($permission['reason'] == 'Der Nutzer ist keiner aktiven Stelle zugeordnet.' ? true : $GUI->echo));
			if($permission['reason'] == 'Der Nutzer ist keiner aktiven Stelle zugeordnet.') {
				exit;
			}

			if (is_ows_request($GUI->formvars)) {
				$GUI->debug->write('OWS Request führt zu Exception.', 4);
				$GUI->Fehlermeldung .= ' Der Zugang zur URL: ' . URL . ' ist mit dem Login oder in der Stelle nicht möglich. Melden Sie sich über einen Browser an dieser Adresse an und aktualisieren Sie ggf. Ihr Passwort oder passen Sie die URL an.';
				$go = 'OWS_Exception';
				# login case 13
			}
			else {
				$GUI->debug->write('Kein OWS Request.', 4);

				if (in_array($permission['reason'], ['password_expired', 'password_age_expired'])) {
					logout();
					if (is_new_password($GUI->formvars)) {
						$GUI->debug->write('Passwort ist abgelaufen. Es wurde ein neues Passwort angegeben.', 4, $GUI->echo);
						$new_password_err = isPasswordValide($GUI->formvars['passwort'], $GUI->formvars['new_password'], $GUI->formvars['new_password_2']);

						if (is_new_password_valid($new_password_err)) {
							$GUI->debug->write('Neues Password ist valid.', 4, $GUI->echo);
							update_password($GUI);
							$GUI->debug->write('Set Session', 4, $GUI->echo);
							session_start();
							set_session_vars($GUI->formvars);
							# login case 17
						}
						else { # new password is not ok
							$GUI->debug->write('Neues Password ist nicht valid. Zurück zur Anmeldung mit Fehlermeldung.', 4, $GUI->echo);
							$GUI->Fehlermeldung = $new_password_err . '!<br>';
							$show_login_form = true;
							$go = 'login_new_password';
							# login case 6
						}
					}
					else {
						if ($permission['reason'] == 'password_expired' AND is_temporary_password_expired($GUI->user)) {
							$GUI->add_message('error', 'Dieser Link zur Passwortvergabe ist nicht mehr gültig. Bitte fordern Sie bei Ihrem Administrator einen neuen Link an.');
							$show_login_form = true;
							$go = 'login';
						}
						else {
							$GUI->debug->write('Passwort ist abgelaufen. Frage neues ab.', 4, $GUI->echo);
							if ($GUI->formvars['format'] == 'json') {
								header('Content-Type: application/json; charset=utf-8');
								$json = json_encode(
									array(
										'success' => false,
										'err_msg' => $permission['errmsg']
									)
								);
								echo utf8_decode($json);
								exit;
							}
							else {
								$GUI->add_message('error', $permission['errmsg']);
							}
							$show_login_form = true;
							$go = 'login_new_password';
							# login case 19
						}
					}
				}
				else {
					$GUI->debug->write('Passwort ist nicht abgelaufen.', 4);
					$GUI->add_message('error', $permission['errmsg'] . '<br>' . $permission['reason']);
					$GUI->Stelle = new stelle($GUI->user->stelle_id, $GUI->database);
					$go = 'Stelle_waehlen';
					$GUI->formvars['csrf_token'] = $_SESSION['csrf_token'];
					# login case 14
				}
			}
		}
	}
}

if (is_logged_in()) {
	if (
		!defined('AGREEMENT_MESSAGE') OR
		!is_file(AGREEMENT_MESSAGE) OR
		AGREEMENT_MESSAGE == '' OR
		is_agreement_accepted($GUI->user)
	) {
		$GUI->debug->write('Agreement ist akzeptiert.', 4, $GUI->echo);
		# login case 4
	}
	else {
		$GUI->debug->write('Agreement wurde noch nicht akzeptiert.', 4, $GUI->echo);
		if (array_key_exists('agreement', $GUI->formvars)) {
			if ($GUI->formvars['agreement_accepted'] == '1') {
				$GUI->debug->write('Nutzer bestätigt Agreement. Trage das ein.', 4, $GUI->echo);
				$GUI->user->update_agreement_accepted($GUI->formvars['agreement_accepted']);
				# login case 18
			}
			else {
				$GUI->debug->write('Agreement wurde abgelehnt, logout.', 4, $GUI->echo);
				unset($GUI->formvars['agreement']);
				logout();
				$show_login_form = true;
				$go = 'login';
				# login case 16
			}
		}
		else {
			if (file_exists(AGREEMENT_MESSAGE)) {
				$GUI->debug->write('Frage Agreement beim Nutzer ab.', 4, $GUI->echo);
				$show_login_form = true;
				$go = 'login_agreement';
			}
			else {
				logout();
				$show_login_form = true;
				$GUI->add_message('error', 'Die in der Konfiguration angegebene Datei ' . AGREEMENT_MESSAGE . ' für die Zustimmungserklärung konnte nicht gefunden werden. Informieren Sie den Administrator.');
				$go = 'login';
			}
		}
	}
}
else {
	$GUI->debug->write('is_logged_in liefert false', 4, $GUI->echo);
}
# $show_login_form = true nach login cases 3, 6, 7, 8, 9, 10, 11
if ($show_login_form) {
	$GUI->debug->write('Zeige Login-Form', 4, $GUI->echo);
	$GUI->user->rolle = new stdClass();
	$GUI->user->rolle->querymode = 0;
}
else {
	$GUI->debug->write('Lade Stelle und ordne Rolle dem User zu.', 4, $GUI->echo);

	$GUI->debug->write('Ordne Nutzer: ' . $GUI->user->id . ' Stelle: ' . $GUI->user->stelle_id . ' zu.', 4, $GUI->echo);
	$GUI->user->setRolle($GUI->user->stelle_id);

	# Alles was man immer machen muss bevor die go's aufgerufen werden
	if (new_options_sent($GUI->formvars)) {
		$GUI->debug->write('Speicher neue Stellenoptionen.', 4, $GUI->echo);
		$GUI->setLayerParams('options_');
		$GUI->user->setOptions($GUI->user->stelle_id, $GUI->formvars);
		$GUI->user->rolle->readSettings();
	}
	#echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
	# Rollenbezogene Stellendaten zuweisen
	$GUI->loadMultiLingualText($GUI->user->rolle->language);

	# Ausgabe der Zugriffsinformationen in debug-Datei
	$GUI->debug->write('User: ' . $GUI->user->login_name, 4);
	$GUI->debug->write('Name: ' . $GUI->user->Name.' '.$GUI->user->Vorname, 4);
	$GUI->debug->write('Stelle_ID: ' . $GUI->Stelle->id, 4);
	$GUI->debug->write('Stellenbezeichnung: ' . $GUI->Stelle->Bezeichnung, 4);
	$GUI->debug->write('Host_ID: ' . getenv("REMOTE_ADDR"), 4);

	if (defined('BEARBEITER') AND BEARBEITER == 'true') {
		define('BEARBEITER_NAME', 'Bearbeiter: ' . $GUI->user->Name);
	}

	$GUI->pgdatabase = $GUI->baudatabase = new pgdatabase();
	if (!$GUI->pgdatabase->open(POSTGRES_CONNECTION_ID)) {
		echo $GUI->pgdatabase->err_msg;
		exit;
	}

	if (!in_array($go, $non_spatial_cases)) {	// für fast_cases, die keinen Raumbezug haben, die Trafos weglassen
		$GUI->epsg_codes = $GUI->pgdatabase->read_epsg_codes(false);
		# Umrechnen der für die Stelle eingetragenen Koordinaten in das aktuelle System der Rolle
		# wenn die EPSG-Codes voneinander abweichen
		if ($GUI->Stelle->epsg_code != $GUI->user->rolle->epsg_code) {
			$user_epsg = $epsg_codes[$GUI->user->rolle->epsg_code];
			if ($user_epsg['minx'] != '') {
				// Koordinatensystem ist räumlich eingegrenzt
				if ($GUI->Stelle->epsg_code != 4326) {
					$projFROM = ms_newprojectionobj("init=epsg:".$GUI->Stelle->epsg_code);
					$projTO = ms_newprojectionobj("init=epsg:4326");
					$GUI->Stelle->MaxGeorefExt->project($projFROM, $projTO); // max. Stellenextent wird in 4326 transformiert
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
			else {
				# Umrechnen der maximalen Kartenausdehnung der Stelle
				$projFROM = ms_newprojectionobj("init=epsg:" . $GUI->Stelle->epsg_code);
				$projTO = ms_newprojectionobj("init=epsg:" . $GUI->user->rolle->epsg_code);
				$GUI->Stelle->MaxGeorefExt->project($projFROM, $projTO);
			}
		}
	}

	if ($_SESSION['login_routines'] == true) {
		define('AFTER_LOGIN', true);
		$mapdb = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
		# hier befinden sich Routinen, die beim einloggen des Nutzers einmalig durchgeführt werden
		# Löschen der Rollenfilter
		$mapdb->deleteRollenFilter();
		# Löschen der Rollenlayer
		$rollenlayerset = $mapdb->read_RollenLayer(NULL, 'search', 1);
		for($i = 0; $i < @count($rollenlayerset); $i++){
			$mapdb->deleteRollenLayer($rollenlayerset[$i]['id']);
			$mapdb->delete_layer_attributes(-$rollenlayerset[$i]['id']);
		}
		# Zurücksetzen des histtimestamps
		if ($GUI->user->rolle->hist_timestamp_de != '') {
			$GUI->setHistTimestamp();
		}
		# Zurücksetzen der veränderten Klassen
		#$GUI->user->rolle->resetClasses();
		if (defined('LOGIN_ROUTINE') AND LOGIN_ROUTINE != '' AND file_exists(LOGIN_ROUTINE) AND is_file(LOGIN_ROUTINE)) {
			include(LOGIN_ROUTINE);
		}
		$_SESSION['login_routines'] = false;
	}
	else {
		define('AFTER_LOGIN', false);
	}

	# Anpassen der Kartengröße an das Browserfenster
	if ($GUI->user->rolle->auto_map_resize AND $GUI->formvars['browserwidth'] != '') {
		$GUI->resizeMap2Window();
	}

	if (isset($_FILES)) {
		$forbidden_files = array();
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
* Functions
**/

function is_logout($formvars) {
	return (array_key_exists('go', $formvars) AND $formvars['go'] == 'logout');
}

function is_logged_in() {
	return (
		array_key_exists('angemeldet', $_SESSION) AND
		$_SESSION['angemeldet'] === true AND
		$_SESSION['login_name'] != ''
	);
}

function is_logged_in_stelle() {
	return (
		array_key_exists('stelle_angemeldet', $_SESSION) AND
		$_SESSION['stelle_angemeldet'] === true
	);
}

function is_logged_out() {
	return !is_logged_in();
}

function is_gast_login($formvars, $gast_stellen) {
	return array_key_exists('gast', $formvars) AND $formvars['gast'] != '' AND $formvars['login_name'] == '' AND in_array($formvars['gast'], $gast_stellen);
}

function has_width_and_height($var) {
	return (array_key_exists('browserwidth', $var) AND array_key_exists('browserheight', $var));
}

function width_or_height_empty($var) {
	return (intval($var['browserwidth']) == 0 OR intval($var['browserheight']) == 0);
}

function set_width_or_height_default($var) {
	if (intval($var['browserwidth']) == 0) {
		$var['browserwidth'] = '800';
	}
	if (intval($var['browserheight']) == 0) {
		$var['browserheight'] = '600';
	}
	return $var;
}

function is_login($formvars) {
	return array_key_exists('login_name', $formvars) AND $formvars['login_name'] != '' AND array_key_exists('passwort', $formvars) AND $formvars['passwort'] != '';
}

function is_agreement_accepted($user) {
	return $user->agreement_accepted == 1;
}

function is_new_stelle($formvars, $user) {
	return (array_key_exists('Stelle_ID', $formvars) AND $formvars['Stelle_ID'] != '' AND $formvars['Stelle_ID'] != $user->stelle_id);
}

function is_user_member_in_stelle($user_stelle_id, $allowed_stellen_ids) {
	if($allowed_stellen_ids == NULL)return false;
	return in_array($user_stelle_id, $allowed_stellen_ids);
}

function get_permission_in_stelle($GUI) {
	$GUI->debug->write('start get permission in stelle', 4, $GUI->echo);
	$allowed = true;
	$reason = '';
	$errmsg = '';

	if (is_user_member_in_stelle($GUI->Stelle->id, $GUI->user->Stellen['ID'])) {
		$GUI->debug->write('Nutzer gehört zur Stelle ' . $GUI->Stelle->id, 4, $GUI->echo);

		$expiration_info = is_password_expired($GUI->user, $GUI->Stelle);
		if ($expiration_info === 'not_expired') {
			$GUI->debug->write('Passwort ist nicht abgelaufen.', 4, $GUI->echo);

			if (CHECK_CLIENT_IP) {
				$GUI->debug->write('Es wird geprüft ob IP-Adressprüfung in der Stelle durchgeführt werden muss.', 4);

				if ($GUI->Stelle->checkClientIpIsOn()) {
					$GUI->debug->write('IP-Adresse des Clients wird in dieser Stelle geprüft.', 4);

					if ($GUI->user->clientIpIsValide(get_remote_ip()) == false) {
						$GUI->debug->write('IP-Adresse des Clients ist in der Stelle valid.', 4);
						$allowed = false;
						$reason = 'IP not allowed';
						$errmsg = 'Sie haben keine Berechtigung von dem Rechner mit der IP: ' . getenv('REMOTE_ADDR') . ' auf die Stelle zuzugreifen.';
					}
				}
			}
		}
		else  {
			$GUI->debug->write('Passwort ist abgelaufen.', 4, $GUI->echo);
			$allowed = false;
			$reason = $expiration_info;
			$errmsg = 'Das Passwort des Nutzers ' . $GUI->user->login_name . ' ist ';
			switch ($expiration_info) {
				case 'password_age_expired' : {
					$errmsg .= 'in der Stelle ' . $GUI->stelle->Bezeichnung . ' abgelaufen. Passwörter haben in dieser Stelle nur eine Gültigkeit von ' . $GUI->Stelle->allowedPasswordAge . ' Monaten.';
				} break;
				case 'password_expired' : {
					$errmsg .= 'abgelaufen und muss neu gesetzt werden.';
				} break;
				default : {
					$errmsg .= 'abgelaufen.';
				}
			}
			$errmsg .= ' Geben Sie jetzt ein neues Passwort ein und notieren Sie es sich bevor Sie sich hier wieder anmelden.';
		}
	}
	else {
		$GUI->debug->write('Nutzer gehört nicht zur Stelle ' . $GUI->Stelle->id, 4, $GUI->echo);
		if($GUI->user->Stellen['ID'] == NULL){
			$reason = 'Der Nutzer ist keiner aktiven Stelle zugeordnet.';
		}
		else{
			$reason = 'Der Nutzer ist nicht der Stelle mit der ID: ' . $GUI->Stelle->id . ' zugeordnet oder es gibt diese Stelle in der Anwendung nicht.';
		}
		$errmsg = 'Anmeldung in der Stelle fehlgeschlagen.';
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
	if ($user->password_expired) {
		return 'password_expired';
	}
	if ($stelle->checkPasswordAge) {
		$remainingDays = checkPasswordAge($user->password_setting_time, $stelle->allowedPasswordAge);
		#echo '<br>Passwort setting time: ' . $user->password_setting_time . ' erlaubt iin Monat: ' . $stelle->allowedPasswordAge . ' Verbleibende Tage: ' . $remainingDays;
		if ($remainingDays <= 0) {
			return 'password_age_expired';
		}
	}
	return 'not_expired';
}

function is_temporary_password_expired($user){
	return (((time() - strtotime($user->password_setting_time)) / 3600) > 72);
}

function is_registration($formvars) {
	return array_key_exists('go', $formvars) AND strpos($formvars['go'], 'invitation') === false AND array_key_exists('token', $formvars) AND $formvars['token'] != '' AND $formvars['email'] != '' AND $formvars['Stelle_ID'] != '';
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
	if ($check == 0 AND $invitation->get('completed') != '') {
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
	if ($check == 0 AND $params['Stelle_ID'] == '') {
		$registration_errors[] = 'Parameter stellen_id fehlt.';
		$check = 1;
	}

	# Prüft ob stelle_id zum token passt
	if ($check == 0 AND $params['Stelle_ID'] != $invitation->get('stelle_id')) {
		$registration_errors[] = 'stelle_id: ' . $params['Stelle_ID'] . ' passt nicht zu<br>token: ' . $params['token'] . '.';
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

function new_options_sent($formvars) {
	return (array_key_exists('gui', $formvars) AND $formvars['gui'] != '');
}

function logout() {
	session_start();
	$_SESSION = array();
	if (ini_get("session.use_cookies")){
		$params = session_get_cookie_params();
		$params['path'] = explode(';', $params['path'])[0];
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}
	session_destroy();
}

function update_password($GUI) {
	$GUI->user->setNewPassword($GUI->formvars['new_password']);
	$GUI->add_message('notice', 'Password ist erfolgreich geändert worden.');
}

function set_session_vars($formvars) {
	$_SESSION['angemeldet'] = true;
	$_SESSION['login_name'] = $formvars['login_name'];
	$_SESSION['login_routines'] = true;
	$_SESSION['csrf_token'] = md5(uniqid(mt_rand(), true));
}

/**
*	Here we switch from the old md5 to the new sha1 password encryption method.
*	The new password reside in the new attribut password (with d at the end)
*	This function set the password in attribut password with method sha1
*	when password match with md5 method in attribut passwort.
*	This function is to prepare the use of sha1 password encryption in kvwmap
*	If any user have been switched to the new sha1 method, this function and as well
*	the attribut passwort (with t at the end) will become useless and can be removed.
*/
function prepare_sha1($login_name, $password) {
	global $GUI;
	$sql = "
		UPDATE
			user
		SET
			password = SHA1('" . $password . "'),
			passwort = NULL
		WHERE
			passwort = MD5('" . $password . "') AND
			(password IS NULL OR password = '')
	";
	#echo "SQL to update the password with method sha1: ", $sql;
	$GUI->debug->write("<p>file:users.php class:user->prepare_sha1 - Setzen des Passworthash in Attribut password mit SHA1 Methode:<br>", 3);
	$ret = $GUI->database->execSQL($sql, 4, 0, true);
	if (!$ret['success']) { $GUI->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>' . $GUI->database->mysqli->error, 4); return 0; }
	return $ret['success'];
}
?>