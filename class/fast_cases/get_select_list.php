<?

function rectObj($minx, $miny, $maxx, $maxy, $imageunits = 0){
	if (MAPSERVERVERSION >= 800) {
		return new RectObj($minx, $miny, $maxx, $maxy, $imageunits);
	}
	else {
		$rect = new RectObj();
		$rect->setextent($minx, $miny, $maxx, $maxy);
		return $rect;
	}
}

function sanitize(&$value, $type) {
	switch ($type) {
		case 'int' : {
			$value = (int) $value;
		} break;
		case 'text' : {
			$value = pg_escape_string($value);
		} break;
		default : {
			// let $value as it is
		}
	}
	return $value;
}

function value_of($array, $key) {
	if(!is_array($array))$array = array();
	return (array_key_exists($key, $array) ? $array[$key] :	'');
}

function in_subnet($ip,$net) {
	$ipparts=explode('.',$ip);
	$netparts=explode('.',$net);

	# Direkter Vergleich
	if ($ip==$net) {
		return 1;
	}

  # Test auf C-Netz
	if (trim($netparts[3],'0')=='' OR $netparts[3]=='*') {
		# C-Netzvergleich
	  if ($ipparts[0].'.'.$ipparts[1].'.'.$ipparts[2]==$netparts[0].'.'.$netparts[1].'.'.$netparts[2]) {
	  	return 1;
	  }
	}

  # Test auf B-Netz
	if ((trim($netparts[3],'0')=='' OR $netparts[3]=='*') AND (trim($netparts[2],'0')=='' OR $netparts[2]=='*')) {
		# B-Netzvergleich
	  if ($ipparts[0].'.'.$ipparts[1]==$netparts[0].'.'.$netparts[1]) {
	  	return 1;
	  }
	}

  # Test auf A-Netz
	if ((trim($netparts[3],'0')=='' OR $netparts[3]=='*') AND (trim($netparts[2],'0')=='' OR $netparts[2]=='*') AND (trim($netparts[1],'0')=='' OR $netparts[1]=='*')) {
		# A-Netzvergleich
	  if ($ipparts[0]==$netparts[0]) {
	  	return 1;
	  }
	}
	return 0;
}

function checkPasswordAge($passwordSettingTime,$allowedPassordAgeMonth) {
  $passwordSettingUnixTime=strtotime($passwordSettingTime); # Unix Zeit in Sekunden an dem das Passwort gesetzt wurde
  $allowedPasswordAgeDays=round($allowedPassordAgeMonth*30.5); # Zeitintervall, wie alt das Password sein darf in Tagen
  $passwordAgeDays=round((time()-$passwordSettingUnixTime)/60/60/24); # Zeitinterval zwischen setzen des Passwortes und aktueller Zeit in Tagen
  $allowedPasswordAgeRemainDays=$allowedPasswordAgeDays-$passwordAgeDays; # Zeitinterval wie lange das Passwort noch gilt in Tagen
	return $allowedPasswordAgeRemainDays; // Passwort ist abgelaufen wenn Wert < 1  
}

/**
* Funktion ersetzt in $str die Schlüsselwörter, die in rolle::$layer_params als key enthalten sind durch deren values.
* Zusätzlich werden die vordefinierten Parameter ($USER_ID usw.) ersetzt
* Im optionalen Array $additional_params können weitere zu ersetzende key-value-Paare übergeben werden
*/
function replace_params_rolle($str, $additional_params = NULL) {
	if (strpos($str, '$') !== false) {
		$params = rolle::$layer_params;
		if (is_array($additional_params)) {
			$params = array_merge($params, $additional_params);
		}
		$str = replace_params($str, $params);
		$current_time = time();
		$str = str_replace('$CURRENT_DATE', date('Y-m-d', $current_time), $str);
		$str = str_replace('$CURRENT_TIMESTAMP', date('Y-m-d G:i:s', $current_time), $str);
		$str = str_replace('$USER_ID', rolle::$user_ID, $str);
		$str = str_replace('$STELLE_ID', rolle::$stelle_ID, $str);
		$str = str_replace('$STELLE', rolle::$stelle_bezeichnung, $str);
		$str = str_replace('$HIST_TIMESTAMP', rolle::$hist_timestamp, $str);
		$str = str_replace('$LANGUAGE', rolle::$language, $str);
		$str = str_replace('$EXPORT', rolle::$export, $str);
	}
	return $str;
}

function replace_params($str, $params) {
	if (is_array($params)) {
		foreach ($params AS $key => $value) {
			$str = str_replace('$'.$key, $value, $str);
		}
	}
	return $str;
}

//TODO: Prüfen ob die Ausgabe $msg nicht mit htmlspecialchars($msg) erfolgen muss
function sql_err_msg($title, $sql, $msg, $div_id) {
	$err_msg = "
		<div style=\"text-align: left;\">" .
		$title . "<br>" .
		$msg . "
		<div style=\"text-align: center\">
			<a href=\"#\" onclick=\"debug_t = this; $('#error_details_" . $div_id . "').toggle(); $(this).children().toggleClass('fa-caret-down fa-caret-up')\"><i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i></a>
		</div>
		<div id=\"error_details_" . $div_id . "\" style=\"display: none\">
			Aufgetreten bei SQL-Anweisung:<br>
			<textarea id=\"sql_statement_" . $div_id . "\" class=\"sql-statement\" type=\"text\" style=\"height: " . round(strlen($sql) / 2) . "px; max-height: 600px\">
				" . $sql . "
			</textarea><br>
			<button type=\"button\" onclick=\"
				copyText = document.getElementById('sql_statement_" . $div_id . "');
				copyText.select();
				document.execCommand('copy');
			\">In Zwischenablage kopieren</button>
		</div>
	</div>";
	return $err_msg;
}

class GUI {

	var $alert;
	var $gui;
	var $layout;
	var $style;
	var $mime_type;
	var $menue;
	var $pdf;
	var $addressliste;
	var $debug;
	var $flst;
	var $formvars;
	var $legende;
	var $map;
	var $mapdb;
	var $img;
	var $FormObject;
	var $StellenForm;
	var $Fehlermeldung;
	var $Hinweis;
	var $Stelle;
	var $ALB;
	var $activeLayer;
	var $nImageWidth;
	var $nImageHeight;
	var $user;
	var $qlayerset;
	var $scaleUnitSwitchScale;
	var $map_scaledenom;
	var $map_factor='';
	var $formatter;
	var $success = true;
	var $login_failed;
	var $only_main = false;
	var $class_load_level;
	var $layer_id_string;
	var $noMinMaxScaling;
	var $stelle_id;
	var $angle_attribute;
	var $titel;
	var $PasswordError;
	var $Meldung;
	var $radiolayers;
	var $show_query_tooltip;
	var $last_query;
	var $querypolygon;
	var $new_entry;
	var $search;
	var $form_field_names;
	var $editable;
	var $groupset;
	var $use_form_data;
	var $stelle;
	var $zoomed;
	var $error_position;
	var $selected_search;
	var $attributes = array();
	var $scrolldown;
	var $queryrect;
	var $notices;
	var $layers_replace_scale = array();
	static $messages = array();

  function __construct($main, $style, $mime_type) {
    # Debugdatei setzen
    global $debug;
    $this->debug=$debug;
    # Logdatei für PostgreSQL setzten
    global $log_postgres;
    $this->log_postgres=$log_postgres;
    # layout Templatedatei zur Anzeige der Daten
    if ($main!="") $this->main=$main;
    # Stylesheetdatei
    if (isset($style)) $this->style=$style;
    # mime_type html, pdf
    if (isset ($mime_type)) $this->mime_type=$mime_type;
		$this->scaleUnitSwitchScale = 239210;
  }

	function add_message($type, $msg) {
		GUI::add_message_($type, $msg);
	}

	public static function add_message_($type, $msg) {
		if (is_array($msg) AND array_key_exists('success', $msg) AND is_array($msg)) {
			$type = 'notice';
			$msg = $msg['msg'];
		}
		if ($type == 'array' or is_array($msg)) {
			foreach($msg AS $m) {
				GUI::add_message_($m['type'], $m['msg']);
			}
		}
		else {
			GUI::$messages[] = array(
				'type' => $type,
				'msg' => $msg
			);
		}
	}

	function sanitize($vars) {
		foreach ($vars as $name => $type) {
			sanitize($this->formvars[$name], $type);
		}
	}

	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.rolle::$language;
    $this->Stelle->language=$language;
    include(LAYOUTPATH.'languages/'.$language.'.php');
  }

  function get_select_list() {
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $attributenames[0] = $this->formvars['attribute'];
		if ($this->formvars['datatype_id'] != '') {
			$attributes = $mapDB->read_datatype_attributes($this->formvars['layer_id'], $this->formvars['datatype_id'], $layerdb, $attributenames);
		}
    else {
			$attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		}
		$options = array_shift(explode(';', $attributes['options'][$this->formvars['attribute']]));
    $reqby_start = strpos(strtolower($options), "<required by>");
    if ($reqby_start > 0) {
			$sql = substr($options, 0, $reqby_start);
		}
		else {
			$sql = $options;
		}
		$attributenames = explode('|', $this->formvars['attributenames']);
		$attributevalues = explode('|', $this->formvars['attributevalues']);
		$sql = str_replace('=<requires>', '= <requires>', $sql);
		for ($i = 0; $i < count($attributenames); $i++) {
			$value = ($attributevalues[$i] != '' ? "'" . $attributevalues[$i] . "'" : 'NULL');
			$sql = str_replace('= <requires>' . $attributenames[$i] . '</requires>', " IN (" . $value . ")", $sql);
			$sql = str_replace('<requires>' . $attributenames[$i] . '</requires>', $value, $sql);	# fallback
		}
		#echo $sql;
		@$ret = $layerdb->execSQL($sql, 4, 0);
		if (!$ret[0]) {
			switch($this->formvars['type']) {
				case 'select-one' : {					# ein Auswahlfeld soll mit den Optionen aufgefüllt werden 
					$html = '>';			# Workaround für dummen IE Bug
					if (pg_num_rows($ret[1]) > 1 OR $reqby_start > 0 OR $this->formvars['auswahl'] == 1) {
						$html .= '<option value="">-- Bitte Auswählen --</option>';
					}
					while($rs = pg_fetch_array($ret[1])){
						$html .= '<option value="'.$rs['value'].'">'.$rs['output'].'</option>';
					}
				}break;
				
				case 'text' : {								#  ein Textfeld soll nur mit dem ersten Wert aufgefüllt werden
					$rs = pg_fetch_array($ret[1]);
					$html = $rs['output'];
				}break;
				
				case 'hidden' : {					# ein Bild-Auswahlfeld soll mit den Optionen aufgefüllt werden 
					while($rs = pg_fetch_array($ret[1])){
						$html .= '						
						<li class="item" data-value="' . $rs['value'] . '" onclick="image_select(this);">
							<img src="data:image/jpg;base64,' . base64_encode(@file_get_contents($rs['image'])) . '">
							<span>' . $rs['output'] . '</span>
						</li>';
					}
				}break;
			}
		}
		echo $html;
  }
}

class user {
	# // TODO: Beim Anlegen eines neuen Benutzers müssen die Einstellungen für die Karte aus der Stellenbeschreibung als Anfangswerte übernommen werden

	var $id;
	var $Name;
	var $Vorname;
	var $login_name;
	var $funktion;
	var $dbConn; # Datenbankverbindungskennung
	var $Stellen;
	var $nZoomFactor;
	var $nImageWidth;
	var $nImageHeight;
	var $database;
	var $remote_addr;
	var $has_logged_in;
	var $language = 'german';
	var $debug;
	var $share_rollenlayer_allowed;

	/**
	 * Create a user object
	 * if only login_name is defined, find_by login_name only
	 * if login_name and password is defined, find_by login_name and password
	*/
	function __construct($login_name, $id, $database, $password = '', $archived = false) {
		global $debug;
		$this->debug = $debug;
		$this->database = $database;
		$this->has_logged_in = false;
		$this->login_name = $login_name;
		$this->id = (int) $id;
		$this->remote_addr = getenv('REMOTE_ADDR');
		$this->readUserDaten($this->id, $this->login_name, $password, $archived);
	}

	function readUserDaten($id, $login_name = '', $password = '', $archived) {
		$where = array();
		if ($id > 0) array_push($where, "ID = " . $id);
		if ($login_name != '') array_push($where, "login_name = '" . pg_escape_string($login_name) . "'");
		if ($password != '') array_push($where, "password = kvwmap.sha1('" . pg_escape_string($password) . "')");
		if (!$archived) array_push($where, "archived IS NULL");
		$sql = "
			SELECT
				*
			FROM
				kvwmap.user
			WHERE
				" . implode(" AND ", $where);
		#echo '<br>SQL to read user data: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>", 3);
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if(!$ret[0]) {
      $rs = pg_fetch_assoc($ret[1]);
    }
		$this->id = $rs['id'];
		$this->login_name = $rs['login_name'];
		$this->Namenszusatz = $rs['namenszusatz'];
		$this->Name = $rs['name'];
		$this->Vorname = $rs['vorname'];
		$this->stelle_id = $rs['stelle_id'];
		$this->phon = $rs['phon'];
		$this->email = $rs['email'];
		$this->organisation = $rs['organisation'];
		$this->position = $rs['position'];
		if (CHECK_CLIENT_IP) {
			$this->ips = $rs['ips'];
		}
		$this->funktion = $rs['Funktion'];
		$this->debug->user_funktion = $this->funktion;
		$this->password_setting_time = $rs['password_setting_time'];
		$this->password_expired = $rs['password_expired'] === 't';
		$this->userdata_checking_time = $rs['userdata_checking_time'];
		$this->agreement_accepted = $rs['agreement_accepted'];
		$this->start = $rs['start'];
		$this->stop = $rs['stop'];
		$this->archived = $rs['archived'];
		$this->share_rollenlayer_allowed = $rs['share_rollenlayer_allowed'];
		$this->layer_data_import_allowed = $rs['layer_data_import_allowed'];
		$this->tokens = $rs['tokens'];
		$this->num_login_failed = $rs['num_login_failed'];
		$this->login_locked_until = $rs['login_locked_until'];
	}

	function getLastStelle() {
		$sql = "
			SELECT
				stelle_id
			FROM
				kvwmap.user
			WHERE
				id= " . $this->id ."
		";
		$this->debug->write("<p>file:users.php class:user->getLastStelle - Abfragen der zuletzt genutzten Stelle:<br>" . $sql, 4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4); return 0; }
		$rs = pg_fetch_array($ret[1]);
		return $rs['stelle_id'];
	}

	function StellenZugriff($stelle_id) {
		$this->Stellen=$this->getStellen($stelle_id);
		if (count($this->Stellen['ID'])>0) {
			return 1;
		}
		return 0;
	}

	function getStellen($stelle_ID, $with_expired = false) {
		global $language;
		if ($language != '' AND $language != 'german') {
			$name_column = "
			CASE
				WHEN s.bezeichnung_" . $language . " != \"\" THEN s.bezeichnung_" . $language . "
				ELSE s.bezeichnung
			END AS bezeichnung";
		}
		else {
			$name_column = "s.bezeichnung";
		}
		$sql = "
			SELECT
				s.id,
				" . $name_column . "
			FROM
				kvwmap.stelle AS s,
				kvwmap.rolle AS r
			WHERE
				s.id = r.stelle_id AND
				r.user_id = " . $this->id .
				($stelle_ID > 0 ? " AND s.id = " . $stelle_ID : "") . 
				(!$with_expired ? "
				AND (
					(
						('" . date('Y-m-d h:i:s') . "' >= s.start OR s.start IS NULL) AND 
						('" . date('Y-m-d h:i:s') . "' <= s.stop OR s.stop IS NULL)
					)
					OR
					(s.start IS NULL AND s.stop IS NULL)
				)" : "") . "
			ORDER BY
				bezeichnung;
		";
		#debug_write('<br>sql: ', $sql, 1);
		$this->debug->write("<p>file:users.php class:user->getStellen - Abfragen der Stellen die der User einnehmen darf:", 4);
		$ret = $this->database->execSQL($sql, 4, 0, true);
		while ($rs = pg_fetch_assoc($ret[1])) {
			$stellen['ID'][]=$rs['id'];
			$stellen['Bezeichnung'][]=$rs['bezeichnung'];
		}
		return $stellen;
	}

	function clientIpIsValide($remote_addr) {
    # Prüfen ob die übergebene IP Adresse zu den für den Nutzer eingetragenen Adressen passt
    $ips=explode(';',$this->ips);
    foreach ($ips AS $ip) {
      if (trim($ip)!='') {
        $ip=trim($ip);
				if(!is_numeric(array_pop(explode('.', $ip))))$ip = gethostbyname($ip);			# für dyndns-Hosts
        if (in_subnet($remote_addr, $ip)) {
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
}

class stelle {

  var $id;
  var $Bezeichnung;
  var $debug;
  var $nImageWidth;
  var $nImageHeight;
  var $oGeorefExt;
  var $pixsize;
  var $selectedButton;
  var $database;

	function __construct($id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->id=$id;
		$this->database=$database;
		$this->Bezeichnung=$this->getName();
		$this->readDefaultValues();
	}

  function getName() {
    $sql ='SELECT ';
    if (rolle::$language != 'german' AND rolle::$language != ''){
      $sql.='bezeichnung_'.rolle::$language.' AS ';
    }
    $sql.='bezeichnung FROM kvwmap.stelle WHERE id = '.$this->id;
    #echo $sql;
    $this->debug->write("<p>file:stelle.php class:stelle->getName - Abfragen des Namens der Stelle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		$rs = pg_fetch_array($ret[1]);
    $this->Bezeichnung = $rs['bezeichnung'];
    return $rs['bezeichnung'];
  }

	function readDefaultValues() {
		global $language;
		if ($language != '' AND $language != 'german') {
			$name_column = "
			CASE
				WHEN s.bezeichnung_" . $language . " != \"\" THEN s.bezeichnung_" . $language . "
				ELSE s.bezeichnung
			END AS bezeichnung";
		}
		else {
			$name_column = "s.bezeichnung";
		}

		$sql = "
			SELECT
				id," .
				$name_column . ",
				start,
				stop, minxmax, minymax, maxxmax, maxymax, epsg_code, referenzkarte_id, Authentifizierung, ALB_status, wappen, wappen_link, logconsume,
				ows_namespace,
				ows_title,
				wms_accessconstraints,
				ows_abstract,
				ows_updatesequence,
				ows_geographicdescription,
				ows_fees,
				ows_srs,

				ows_contactorganization,
				ows_contacturl,
				ows_contactaddress,
				ows_contactpostalcode,
				ows_contactcity,
				ows_contactadministrativearea,
				ows_contactemailaddress,
				ows_contactperson,
				ows_contactposition,
				ows_contactvoicephone,
				ows_contactfacsimile,

				ows_distributionorganization,
				ows_distributionurl,
				ows_distributionaddress,
				ows_distributionpostalcode,
				ows_distributioncity,
				ows_distributionadministrativearea,
				ows_distributionemailaddress,
				ows_distributionperson,
				ows_distributionposition,
				ows_distributionvoicephone,
				ows_distributionfacsimile,

				ows_contentorganization,
				ows_contenturl,
				ows_contentaddress,
				ows_contentpostalcode,
				ows_contentcity,
				ows_contentadministrativearea,
				ows_contentemailaddress,
				ows_contentperson,
				ows_contentposition,
				ows_contentvoicephone,
				ows_contentfacsimile,

				protected, check_client_ip::int, check_password_age, allowed_password_age, use_layer_aliases, selectable_layer_params, hist_timestamp, default_user_id,
				style,
				show_shared_layers,
				reset_password_text,
				invitation_text
			FROM
				kvwmap.stelle s
			WHERE
				ID = " . $this->id . "
		";
		#echo 'SQL zum Abfragen der Stelle: ' . $sql;
		$this->debug->write('<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>', 4);
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if(!$ret[0]) {
      $rs = pg_fetch_assoc($ret[1]);
    }
		$this->data = $rs;
		$this->Bezeichnung = $rs['bezeichnung'];
		$this->MaxGeorefExt = rectObj($rs['minxmax'], $rs['minymax'], $rs['maxxmax'], $rs['maxymax']);
		$this->epsg_code = $rs['epsg_code'];
		$this->protected = $rs['protected'];
		//---------- OWS Metadaten ----------//
		$this->ows_title = $rs['ows_title'];
		$this->ows_abstract = $rs['ows_abstract'];
		$this->ows_namespace = $rs['ows_namespace'];
		$this->ows_updatesequence = $rs['ows_updatesequence'];
		$this->ows_geographicdescription = $rs['ows_geographicdescription'];
		$this->ows_fees = $rs['ows_fees'];
		$this->ows_srs = preg_replace(array('/: +/', '/ +:/'), ':', $rs['ows_srs']);

		$this->ows_contactorganization = $rs['ows_contactorganization'];
		$this->ows_contacturl = $rs['ows_contacturl'];
		$this->ows_contactaddress = $rs['ows_contactaddress'];
		$this->ows_contactpostalcode = $rs['ows_contactpostalcode'];
		$this->ows_contactcity = $rs['ows_contactcity'];
		$this->ows_contactadministrativearea = $rs['ows_contactadministrativearea'];
		$this->ows_contactemailaddress = $rs['ows_contactemailaddress'];
		$this->ows_contactperson = $rs['ows_contactperson'];
		$this->ows_contactposition = $rs['ows_contactposition'];
		$this->ows_contactvoicephone = $rs['ows_contactvoicephone'];
		$this->ows_contactfacsimile = $rs['ows_contactfacsimile'];

		$this->ows_distributionorganization = $rs['ows_distributionorganization'];
		$this->ows_distributionurl = $rs['ows_distributionurl'];
		$this->ows_distributionaddress = $rs['ows_distributionaddress'];
		$this->ows_distributionpostalcode = $rs['ows_distributionpostalcode'];
		$this->ows_distributioncity = $rs['ows_distributioncity'];
		$this->ows_distributionadministrativearea = $rs['ows_distributionadministrativearea'];
		$this->ows_distributionemailaddress = $rs['ows_distributionemailaddress'];
		$this->ows_distributionperson = $rs['ows_distributionperson'];
		$this->ows_distributionposition = $rs['ows_distributionposition'];
		$this->ows_distributionvoicephone = $rs['ows_distributionvoicephone'];
		$this->ows_distributionfacsimile = $rs['ows_distributionfacsimile'];

		$this->ows_contentorganization = $rs['ows_contentorganization'];
		$this->ows_contenturl = $rs['ows_contenturl'];
		$this->ows_contentaddress = $rs['ows_contentaddress'];
		$this->ows_contentpostalcode = $rs['ows_contentpostalcode'];
		$this->ows_contentcity = $rs['ows_contentcity'];
		$this->ows_contentadministrativearea = $rs['ows_contentadministrativearea'];
		$this->ows_contentemailaddress = $rs['ows_contentemailaddress'];
		$this->ows_contentperson = $rs['ows_contentperson'];
		$this->ows_contentposition = $rs['ows_contentposition'];
		$this->ows_contentvoicephone = $rs['ows_contentvoicephone'];
		$this->ows_contentfacsimile = $rs['ows_contentfacsimile'];

		$this->wms_accessconstraints = $rs['wms_accessconstraints'];
		$this->check_client_ip = $rs['check_client_ip'];
		$this->checkPasswordAge = $rs['check_password_age'];
		$this->allowedPasswordAge = $rs['allowed_password_age'];
		$this->useLayerAliases = $rs['use_layer_aliases'];
		$this->selectable_layer_params = $rs['selectable_layer_params'];
		$this->hist_timestamp = $rs['hist_timestamp'];
		$this->default_user_id = $rs['default_user_id'];
		$this->show_shared_layers = $rs['show_shared_layers'];
		$this->style = $rs['style'];
		$this->reset_password_text = $rs['reset_password_text'];
		$this->invitation_text = $rs['invitation_text'];
	}
}

class rolle {
	var $user_id;
	var $stelle_id;
	var $debug;
	var $database;
	var $loglevel;
	var $hist_timestamp_de;
	static $language;
	static $hist_timestamp;
	static $layer_params;
	static $user_ID;
	static $stelle_ID;
	static $stelle_bezeichnung;
	static $export;
	var $minx;
	var $newtime;
	var $gui_object;
	var $layerset;

	function __construct($user_id, $stelle_id, $database) {
		global $debug;
		global $GUI;
		$this->gui_object = $GUI;
		$this->debug = $debug;
		$this->user_id = $user_id;
		$this->stelle_id = $stelle_id;
		$this->database = $database;
		rolle::$user_ID = $user_id;
		rolle::$stelle_ID = $stelle_id;
		rolle::$stelle_bezeichnung = $this->gui_object->Stelle->Bezeichnung;
		rolle::$export = 'false';
		$this->loglevel = 0;
	}

  function readSettings() {
    # Abfragen und Zuweisen der Einstellungen der Rolle
    $sql = "
			SELECT
				*
			FROM
				kvwmap.rolle
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
		#echo '<br>Read rolle settings mit sql: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if (pg_num_rows($ret[1]) > 0){
			$rs = pg_fetch_assoc($ret[1]);
			$this->oGeorefExt = rectObj($rs['minx'],$rs['miny'],$rs['maxx'],$rs['maxy']);
			$this->nImageWidth=$rs['nimagewidth'];
			$this->nImageHeight=$rs['nimageheight'];			
			$this->mapsize=$this->nImageWidth.'x'.$this->nImageHeight;
			$this->auto_map_resize=$rs['auto_map_resize'];
			@$this->pixwidth=($rs['maxx']-$rs['minx'])/$rs['nimagewidth'];
			@$this->pixheight=($rs['maxy']-$rs['miny'])/$rs['nimageheight'];
			$this->pixsize=($this->pixwidth+$this->pixheight)/2;
			$this->nZoomFactor=$rs['nzoomfactor'];
			$this->epsg_code=$rs['epsg_code'];
			$this->epsg_code2=$rs['epsg_code2'];
			$this->coordtype=$rs['coordtype'];
			$this->last_time_id=$rs['last_time_id'];
			$this->gui=$rs['gui'];
			rolle::$language = $rs['language'];
			$this->hideMenue = ($rs['hidemenue'] == 'f'? false : true);
			$this->hideLegend = ($rs['hidelegend'] == 'f'? false : true);
			$this->tooltipquery=$rs['tooltipquery'];
			$this->scrollposition=$rs['scrollposition'];
			$this->result_color=$rs['result_color'];
			$this->result_hatching=$rs['result_hatching'];
			$this->result_transparency=$rs['result_transparency'];
			$this->always_draw=$rs['always_draw'];
			$this->runningcoords=$rs['runningcoords'];
			$this->showmapfunctions=$rs['showmapfunctions'];
			$this->showlayeroptions=$rs['showlayeroptions'];
			$this->showrollenfilter=$rs['showrollenfilter'];
			$this->menue_buttons=$rs['menue_buttons'];
			$this->singlequery=$rs['singlequery'];
			$this->querymode=$rs['querymode'];
			$this->geom_edit_first=$rs['geom_edit_first'];
			$this->dataset_operations_position = $rs['dataset_operations_position'];
			$this->immer_weiter_erfassen = $rs['immer_weiter_erfassen'];
			$this->upload_only_file_metadata = $rs['upload_only_file_metadata'];
			$this->overlayx=$rs['overlayx'];
			$this->overlayy=$rs['overlayy'];
			$this->last_query_layer=$rs['last_query_layer'];
			$this->instant_reload=$rs['instant_reload'];
			$this->menu_auto_close=$rs['menu_auto_close'];
			rolle::$layer_params = (array)json_decode('{' . $rs['layer_params'] . '}');
			$this->visually_impaired = $rs['visually_impaired'];
			$this->font_size_factor = $rs['font_size_factor'];
			$this->legendtype = $rs['legendtype'];
			$this->print_legend_separate = $rs['print_legend_separate'];
			$this->print_scale = $rs['print_scale'];
			if ($rs['hist_timestamp'] != '') {
				$this->hist_timestamp_de = $rs['hist_timestamp'];			# der wird zur Anzeige des Timestamps benutzt
				rolle::$hist_timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $rs['hist_timestamp'])->format('Y-m-d\TH:i:s\Z');	# der hat die Form, wie der timestamp in der PG-DB steht und wird für die Abfragen benutzt
			}
			else {
				rolle::$hist_timestamp = $this->hist_timestamp_de = '';
				#rolle::$hist_timestamp = '';
			}
			$this->selectedButton = $rs['selectedbutton'];
			$buttons = explode(',', $rs['buttons']);
			$this->back = in_array('back', $buttons);
			$this->forward = in_array('forward', $buttons);
			$this->zoomin = in_array('zoomin', $buttons);
			$this->zoomout = in_array('zoomout', $buttons);
			$this->zoomall = in_array('zoomall', $buttons);
			$this->recentre = in_array('recentre', $buttons);
			$this->jumpto = in_array('jumpto', $buttons);
			$this->coord_query = in_array('coord_query', $buttons);
			$this->elevation_profile = in_array('elevation_profile', $buttons);
			$this->query = in_array('query', $buttons);
			$this->queryradius = in_array('queryradius', $buttons);
			$this->polyquery = in_array('polyquery', $buttons);
			$this->touchquery = in_array('touchquery', $buttons);
			$this->measure = in_array('measure', $buttons);
			$this->punktfang = in_array('punktfang', $buttons);
			$this->freepolygon = in_array('freepolygon', $buttons);
			$this->freetext = in_array('freetext', $buttons);
			$this->freearrow = in_array('freearrow', $buttons);
			$this->gps = in_array('gps', $buttons);
			$this->geom_buttons = explode(',', str_replace(' ', '', $rs['geom_buttons']));
			$this->redline_text_color = $rs['redline_text_color'];
			$this->redline_font_family = $rs['redline_font_family'];
			$this->redline_font_size = $rs['redline_font_size'];
			$this->redline_font_weight = $rs['redline_font_weight'];
			return 1;
		}
		else {
			return 0;
		}
	}
}

class db_mapObj{
  var $debug;
  var $referenceMap;
  var $Layer;
  var $anzLayer;
  var $nurAufgeklappteLayer;
  var $Stelle_ID;
  var $User_ID;
	var $db;
	var $OhneRequires;
	var $disabled_classes;

	function __construct($Stelle_ID, $User_ID) {
		global $debug;
		global $GUI;
		$this->script_name = 'db_MapObj.php';
		$this->debug = $debug;
		$this->GUI = $GUI;
		$this->db = $GUI->pgdatabase;
		$this->Stelle_ID = $Stelle_ID;
		$this->User_ID = $User_ID;
		$this->rolle = new rolle($User_ID, $Stelle_ID, $this->db);
	}

	function getlayerdatabase($layer_id, $host) {
		#echo '<br>GUI->getlayerdatabase layer_id: ' . $layer_id;
		$layerdb = new pgdatabase();
		$rs = $this->get_layer_connection($layer_id);
		if (count($rs) == 0) {
			return null;
		}
		$layerdb->schema = ($rs['schema'] == '' ? 'public' : $rs['schema']);
		$layerdb->host = $host; # depricated since host is allways in connection table
		if (!$layerdb->open($rs['connection_id'])) {
			echo 'Die Verbindung zur PostGIS-Datenbank konnte mit connection_id: ' . $rs['connection_id'] . ' nicht hergestellt werden:';
			exit;
		}
		return $layerdb;
	}

	/**
	* Function get the postgres connection_id and the schema of the layer with given layer_id
	* @params integer $layer_id, If layer_id is negativ the connection_id is from table rollen_layer
	* @return array with integer connection_id and string schema name, return an empty array if no connection for layer_id found
	*/
	function get_layer_connection($layer_id) {
		sanitize($layer_id, 'int');
		#echo 'Class db_map Method get_layer_connection';
		# $layer_id < 0 Rollenlayer else normal layer
		$sql = "
			SELECT
				connection_id,
				" . ($layer_id < 0 ? "'" . CUSTOM_SHAPE_SCHEMA . "' AS " : "") . "schema
			FROM
				" . ($layer_id < 0 ? "kvwmap.rollenlayer" : "kvwmap.layer") . "
			WHERE
				" . ($layer_id < 0 ? "-id" : "layer_id") . " = " . $layer_id . " AND
				connectiontype = 6
		";
		#echo '<br>sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_layer_connection - Lesen der connection Daten des Layers:<br>" . $sql, 4);
		$ret = $this->db->execSQL($sql);
		if (!$ret[0]) {
			return pg_fetch_assoc($ret[1]);
		}
		else {
			$this->debug->write("<br>Abbruch beim Lesen der Layer connection in get_layer_connection, Zeile: " . __LINE__ . "<br>", 4);
			return array();
		}
	}

  function read_datatype_attributes($layer_id, $datatype_id, $datatypedb, $attributenames, $all_languages = false, $recursive = false, $replace = true){
		global $language;

		$alias_column = (
			(!$all_languages AND $language != 'german') ?
			"
				CASE
					WHEN alias_" . $language. " != '' THEN alias_" . $language . "
					ELSE alias
				END AS alias
			" :
			"
				alias
			"
		);

		if ($attributenames != NULL) {
			$einschr = " AND a.name IN ('" . implode("', '", $attributenames) . "')";
		}

		$sql = "
			SELECT " .
				$alias_column . ", alias_low_german, alias_english, alias_polish, alias_vietnamese,
				datatype_id,
				a.name,
				real_name,
				tablename,
				table_alias_name,
				type,
				d.name as typename,
				geometrytype,
				constraints,
				nullable,
				length,
				decimal_length,
				\"default\",
				form_element_type,
				options,
				tooltip,
				\"group\",
				raster_visibility,
				mandatory,
				quicksearch,
				\"order\",
				privileg,
				query_tooltip,
				visible,
				vcheck_attribute,
				vcheck_operator,
				vcheck_value,
				arrangement,
				labeling
			FROM
				kvwmap.datatype_attributes as a LEFT JOIN
				kvwmap.datatypes as d ON d.id::text = REPLACE(type, '_', '')
			WHERE
				layer_id = " . $layer_id . " AND 
				datatype_id = " . $datatype_id .
				$einschr . "
			ORDER BY
				\"order\"
		";
		/* Attributes die fehlen im Vergleich zu layer_attributes
		dont_use_for_new
		*/
		#echo '<br>Sql read_datatype_attributes: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_datatype_attributes:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		$i = 0;
		while($rs = pg_fetch_array($ret[1])){
			$attributes['datatype_id'][$i] = $rs['datatype_id'];
			$attributes['name'][$i] = $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			$attributes['real_name'][$rs['name']]= $rs['real_name'];
			if($rs['tablename']){
				if(strpos($rs['tablename'], '.') !== false){
					$explosion = explode('.', $rs['tablename']);
					$rs['tablename'] = $explosion[1];		# Tabellenname ohne Schema
					$attributes['schema_name'][$rs['tablename']] = $explosion[0];
				}
				$attributes['table_name'][$i]= $rs['tablename'];
				$attributes['table_name'][$rs['name']] = $rs['tablename'];
			}
			if($rs['table_alias_name'])$attributes['table_alias_name'][$i]= $rs['table_alias_name'];
			if($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']]= $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']]= $rs['table_alias_name'];
			$attributes['type'][$i]= $rs['type'];
			$attributes['typename'][$i]= $rs['typename'];
			$type = ltrim($rs['type'], '_');
			if($recursive AND is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($layer_id, $type, $layerdb, NULL, $all_languages, true, $replace);
			}
			if($rs['type'] == 'geometry'){
				$attributes['the_geom'] = $rs['name'];
			}
			$attributes['geomtype'][$i]= $rs['geometrytype'];
			$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
			$attributes['constraints'][$i]= $rs['constraints'];
			$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
			$attributes['nullable'][$i]= $rs['nullable'];
			$attributes['length'][$i]= $rs['length'];
			$attributes['decimal_length'][$i]= $rs['decimal_length'];
			if($datatypedb != NULL){
				if(substr($rs['default'], 0, 6) == 'SELECT'){					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
					$ret1 = $datatypedb->execSQL($rs['default'], 4, 0);
					if($ret1[0]==0){
						$attributes['default'][$i] = array_pop(pg_fetch_row($ret1[1]));
					}
				}
				else{															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den datatype in dieser Version einmal gespeichert hat
					$attributes['default'][$i]= $rs['default'];
				}
			}

			if ($replace) {
				if ($attributes['default'][$i] != '')	{					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
					$replaced_default = replace_params_rolle($attributes['default'][$i]);
					$ret1 = $layerdb->execSQL('SELECT ' . $replaced_default, 4, 0);
					if ($ret1[0] == 0) {
						$attributes['default'][$i] = @array_pop(pg_fetch_row($ret1[1]));
					}
				}
				$rs['options'] = replace_params_rolle($rs['options']);
			}

			$attributes['form_element_type'][$i]= $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']]= $rs['form_element_type'];
			$attributes['options'][$i]= $rs['options'];
			$attributes['options'][$rs['name']]= $rs['options'];
			$attributes['alias'][$i]= $rs['alias'];
			$attributes['alias_low-german'][$i]= $rs['alias_low-german'];
			$attributes['alias_english'][$i]= $rs['alias_english'];
			$attributes['alias_polish'][$i]= $rs['alias_polish'];
			$attributes['alias_vietnamese'][$i]= $rs['alias_vietnamese'];
			$attributes['tooltip'][$i]= $rs['tooltip'];
			$attributes['group'][$i]= $rs['group'];
			$attributes['arrangement'][$i]= $rs['arrangement'];
			$attributes['labeling'][$i]= $rs['labeling'];
			$attributes['raster_visibility'][$i]= $rs['raster_visibility'];
			$attributes['mandatory'][$i]= $rs['mandatory'];
			$attributes['quicksearch'][$i]= $rs['quicksearch'];
			$attributes['privileg'][$i]= $rs['privileg'];
			$attributes['query_tooltip'][$i]= $rs['query_tooltip'];
			$attributes['visible'][$i]= $rs['visible'];
			$attributes['vcheck_attribute'][$i] = $rs['vcheck_attribute'];
			$attributes['vcheck_operator'][$i] = $rs['vcheck_operator'];
			$attributes['vcheck_value'][$i] = $rs['vcheck_value'];
			$attributes['dependents'][$i] = &$dependents[$rs['name']];
			$dependents[$rs['vcheck_attribute']][] = $rs['name'];
			$attributes['arrangement'][$i]= $rs['arrangement'];
			$attributes['labeling'][$i]= $rs['labeling'];
			$i++;
		}
		return $attributes;
  }

  function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false, $recursive = false, $get_default = false, $replace = true, $replace_only = array('default', 'options', 'vcheck_value'), $attribute_values = []) {
		global $language;
		$attributes = array(
			'name' => array(),
			'tab' => array()
		);
		$einschr = '';

		$alias_column = (
			(!$all_languages AND $language != 'german') ?
			"
				CASE
					WHEN alias_" . $language. " != '' THEN alias_" . $language . "
					ELSE alias
				END AS alias
			" :
			"
				alias
			"
		);

		if ($attributenames != NULL) {
			$einschr = " AND a.name IN ('" . implode("', '", $attributenames) . "')";
		}

		$sql = "
			SELECT
				\"order\", " .
				$alias_column . ", alias_low_german, alias_english, alias_polish, alias_vietnamese,
				layer_id,
				a.name,
				real_name,
				tablename,
				table_alias_name,
				a.schema,
				type,
				d.name as typename,
				geometrytype,
				constraints,
				saveable,
				nullable,
				length,
				decimal_length,
				\"default\",
				form_element_type,
				options,
				tooltip,
				\"group\",
				tab,
				arrangement,
				labeling,
				raster_visibility,
				dont_use_for_new,
				mandatory,
				quicksearch,
				visible,
				vcheck_attribute,
				vcheck_operator,
				vcheck_value,
				\"order\",
				privileg,
				query_tooltip
			FROM
				kvwmap.layer_attributes as a LEFT JOIN
				kvwmap.datatypes as d ON d.id::text = REPLACE(type, '_', '')
			WHERE
				layer_id = " . $layer_id .
				$einschr . "
			ORDER BY
			\"order\"
		";
		// echo '<br>Sql read_layer_attributes: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes:<br>",4);
		$ret = $this->db->execSQL($sql);
		$i = 0;
		while ($rs = pg_fetch_assoc($ret[1])) {
			$attributes['enum'][$i] = array();
			$attributes['order'][$i] = $rs['order'];
			$attributes['name'][$i] = $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			if($rs['real_name'] == '')$rs['real_name'] = $rs['name'];
			$attributes['real_name'][$rs['name']] = $rs['real_name'];
			if ($rs['tablename']) {
				$attributes['table_name'][$i] = $rs['tablename'];
				$attributes['table_name'][$rs['name']] = $rs['tablename'];
				$attributes['schema'][$i] = $rs['schema'];
			}
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$i] = $rs['table_alias_name'];
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']] = $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']] = $rs['table_alias_name'];
			$attributes['type'][$i] = $rs['type'];
			$attributes['typename'][$i] = $rs['typename'];
			$type = ltrim($rs['type'], '_');
			if ($recursive AND is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($layer_id, $type, $layerdb, NULL, $all_languages, true, $replace);
			}
			if ($rs['type'] == 'geometry'){
				$attributes['the_geom'] = $rs['name'];
			}
			$attributes['geomtype'][$i]= $rs['geometrytype'];
			$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
			$attributes['constraints'][$i]= $rs['constraints'];
			$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
			if ($rs['constraints'] == 'PRIMARY KEY') {
				$attributes['pk'][] = $rs['real_name'];
			}
			$attributes['saveable'][$i]= $rs['saveable'];
			$attributes['nullable'][$i]= $rs['nullable'];
			$attributes['length'][$i]= $rs['length'];
			$attributes['decimal_length'][$i]= $rs['decimal_length'];
			$attributes['default'][$i] = $rs['default'];
			$attributes['options'][$i] = $rs['options'];
			$attributes['vcheck_attribute'][$i] = $rs['vcheck_attribute'];
			$attributes['vcheck_operator'][$i] = $rs['vcheck_operator'];
			$attributes['vcheck_value'][$i] = $rs['vcheck_value'];
			$attributes['dependents'][$i] = &$dependents[$rs['name']];
			$dependents[$rs['vcheck_attribute']][] = $rs['name'];			

			if ($replace) {
				foreach($replace_only AS $column) {
					if ($attributes[$column][$i] != '') {
						$attributes[$column][$i] = 	replace_params_rolle(
																					$attributes[$column][$i],
																					((count($attribute_values) > 0 AND $replace_only == 'default') ? $attribute_values : NULL)
																				);
					}
				}
			}

			if ($get_default AND $attributes['default'][$i] != '') {
				# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
				$ret1 = $layerdb->execSQL('SELECT ' . $attributes['default'][$i], 4, 0);
				if ($ret1[0] == 0) {
					$attributes['default'][$i] = @array_pop(pg_fetch_row($ret1[1]));
				}
			}
			$attributes['form_element_type'][$i] = $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']] = $rs['form_element_type'];
			$attributes['options'][$rs['name']] = $attributes['options'][$i];
			$attributes['alias'][$i] = $rs['alias'];
			$attributes['alias_low-german'][$i] = $rs['alias_low-german'];
			$attributes['alias_english'][$i] = $rs['alias_english'];
			$attributes['alias_polish'][$i] = $rs['alias_polish'];
			$attributes['alias_vietnamese'][$i] = $rs['alias_vietnamese'];
			$attributes['tooltip'][$i] = $rs['tooltip'];
			$attributes['group'][$i] = $rs['group'];
			$attributes['tab'][$i] = $rs['tab'];
			$attributes['arrangement'][$i] = $rs['arrangement'];
			$attributes['labeling'][$i] = $rs['labeling'];
			$attributes['raster_visibility'][$i] = $rs['raster_visibility'];
			$attributes['dont_use_for_new'][$i] = $rs['dont_use_for_new'];
			$attributes['mandatory'][$i] = $rs['mandatory'];
			$attributes['quicksearch'][$i] = $rs['quicksearch'];
			$attributes['visible'][$i] = $rs['visible'];
			$attributes['privileg'][$i] = $rs['privileg'];
			$attributes['query_tooltip'][$i] = $rs['query_tooltip'];
			if ($rs['form_element_type'] == 'Style') {
				$attributes['style'] = $rs['name'];
				$attributes['visible'][$i] = 0;
			}
			if ($rs['form_element_type'] == 'Editiersperre') {
				$attributes['Editiersperre'] = $rs['name'];
			}
			$i++;
		}
		if (value_of($attributes, 'table_name') != NULL) {
			$attributes['all_table_names'] = array_unique($attributes['table_name']);
		}
		else {
			$attributes['all_table_names'] = array();
		}
		$attributes['tabs'] = array_values(array_filter(array_unique($attributes['tab']), 'strlen'));
		return $attributes;
	}

	/*
	* Returns a list of datatypes used by layer, given in layer_ids array
	*/
	function get_datatypes($layer_ids) {
		$datatypes = array();
		$sql = "
			SELECT DISTINCT
				dt.*
			FROM
				kvwmap.layer_attributes la JOIN
				kvwmap.datatypes dt ON replace(la.type,'_', '') = dt.id::text
			WHERE
				la.layer_id IN (" . implode(', ', $layer_ids) . ")
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_datatypes - Lesen der Datentypen der Layer mit id (" . implode(', ', $layer_ids) . "):<br>" . $sql , 4);
		$ret = $this->db->execSQL($sql);
		if (!$ret['success']) {
			$this->GUI->add_message('error', err_msg($this->script_name, __LINE__, $sql));
			return $datatypes;
		}
		while ($rs = pg_fetch_assoc($ret[1])) {
			$datatypes[] = $rs;
		}
		return $datatypes;
	}

	function getall_Datatypes($order) {
		$datatypes = array();
		$order_sql = ($order != '') ? "ORDER BY " . replace_semicolon($order) : '';
		$sql = "
			SELECT
				*
			FROM
				kvwmap.datatypes
			" . $order_sql;

		$this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Datatypes - Lesen aller Datentypen:<br>" . $sql , 4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		while($rs = pg_fetch_assoc($ret[1])) {
			/*
			foreach($rs AS $key => $value) {
				$datatypes[$key][] = $value;
			}
			*/
			$datatypes[] = $rs;
		}
		return $datatypes;
	}
}

class pgdatabase {
	var $ist_Fortfuehrung;
	var $debug;
	var $loglevel;
	var $defaultloglevel;
	var $logfile;
	var $defaultlogfile;
	var $commentsign;
	var $blocktransaction;
	var $host;
	var $port;
	var $schema;
	var $pg_text_attribute_types = array('character', 'character varying', 'text', 'timestamp without time zone', 'timestamp with time zone', 'date', 'USER-DEFINED');
	var $version = POSTGRESVERSION;
	var $connection_id;

	function __construct() {
		global $debug;
		global $GUI;
		$this->gui = $GUI;
		$this->debug=$debug;
		$this->loglevel=LOG_LEVEL;
		$this->defaultloglevel=LOG_LEVEL;
		global $log_postgres;
		$this->logfile=$log_postgres;
		$this->defaultlogfile=$log_postgres;
		$this->ist_Fortfuehrung=1;
		$this->type='postgresql';
		$this->commentsign='--';
		$this->err_msg = '';
		# Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
		# START TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
		# Anweisungen nicht in Transactionsblöcken ablaufen.
		# Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
		# Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
		# und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
		# Dazu Fehlerausschriften bearchten.
		$this->blocktransaction=0;
	}

	/**
	* Open the database connection based on the given connection_id
	* @param integer, $connection_id The id of the connection defined in connections table, if 0 default connection will be used
	* @return boolean, True if success or set an error message in $this->err_msg and return false when fail to find the credentials or open the connection
	*/
  function open($connection_id = 0, $flag = NULL) {
		$this->debug->write("Open Database connection with connection_id: " . $connection_id, 4);
		$this->connection_id = $connection_id;
		$connection_string = $this->get_connection_string();
		try {
			$this->dbConn = pg_connect($connection_string . ' connect_timeout=5', $flag);
		}
		catch (Exception $e) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden connection_id: ' . $connection_id . ' '
				. implode(' ' , array_filter(explode(' ', $connection_string), function($part) { return strpos($part, 'password') === false; })) . '<br>Exception: ' . $e;
			return false;
		}

		if (!$this->dbConn) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden connection_id: ' . $connection_id . ' '
				. implode(' ' , array_filter(explode(' ', $connection_string), function($part) { return strpos($part, 'password') === false; }));
			return false;
		}
		else {
			$this->debug->write("Database connection successfully opend.", 4);
			$this->setClientEncodingAndDateStyle();
			$this->connection_id = $connection_id ?: POSTGRES_CONNECTION_ID;
			return true;
		}
	}
	
  function close() {
    $this->debug->write("<br>PostgreSQL Verbindung  schließen.",4);
    return pg_close($this->dbConn);
  }	

	/**
	* return the credential details as array from connections_table
	* or default values if no exists for connection_id
	* @param integer $connection_id The id of connection information in connection table
	* @return array $credentials array with connection details
	*/
	function get_credentials($connection_id) {
		#echo '<p>get_credentials with connection_id: ' . $connection_id;
		if ($connection_id == 0) {
			return $this->get_object_credentials();
		}
		else {
			include_once(CLASSPATH . 'Connection.php');
			$conn = Connection::find_by_id($this->gui, $connection_id);
			$this->host = $conn->get('host');
			return array(
				'host' => 		($conn->get('host')     != '' ? $conn->get('host')     : 'pgsql'),
				'port' => 		($conn->get('port')     != '' ? $conn->get('port')     : '5432'),
				'dbname' => 	($conn->get('dbname')   != '' ? $conn->get('dbname')   : 'kvwmapsp'),
				'user' => 		($conn->get('user')     != '' ? $conn->get('user')     : 'kvwmap'),
				'password' => ($conn->get('password') != '' ? $conn->get('password') : KVWMAP_INIT_PASSWORD)
			);
		}
	}

	/**
	* returns a postgres connection string used to connect to postgres with pg_connect
	* @param array $credentials array with connection details
	* @return string the postgres connection string
	*/
	function format_pg_connection_string($credentials) {
		$connection_string = '' .
			'host=' . 		$credentials['host'] 		. ' ' .
			'port=' . 		$credentials['port'] 		. ' ' .
			'dbname=' . 	$credentials['dbname'] 	. ' ' .
			'user=' . 		$credentials['user'] 		. ' ' .
			'password=' .	$credentials['password'];
		return $connection_string;
	}

	function get_connection_string() {
		return $this->format_pg_connection_string($this->get_credentials($this->connection_id));
	}

	/**
	* Set credentials to postgres object variables
	*/
	function set_object_credentials($credentials) {
		$this->host = 	$credentials['host'];
		$this->port = 	$credentials['port'];
		$this->dbName = $credentials['dbname'];
		$this->user = 	$credentials['user'];
		$this->passwd = $credentials['password'];
	}

	/**
	* Get credentials from postgres object variables
	*/
	function get_object_credentials() {
		return array(
			'host'     => $this->host ?: POSTGRES_HOST,
			'port'     => $this->port ?: 5432,
			'dbname'   => $this->dbName ?: POSTGRES_DBNAME,
			'user'     => $this->user ?: POSTGRES_USER,
			'password' => $this->passwd ?: POSTGRES_PASSWORD
		);
	}

  function setClientEncodingAndDateStyle() {
    $sql = "
			SET CLIENT_ENCODING TO '".POSTGRES_CHARSET."';
			SET datestyle TO 'German';
			";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    return $ret[1];
  }

	function execSQL($sql, $debuglevel = 4, $loglevel = 1, $suppress_err_msg = false, $prepared_params = array()) {
		if (!$this->dbConn) {
			echo '<p>pgconn: ' . $this->dbConn;
		}
		$ret = array(); // Array with results to return
		$ret['msg'] = '';
		$strip_context = true;

		switch ($this->loglevel) {
			case 0 : {
				$logsql = 0;
			} break;
			case 1 : {
				$logsql = 1;
			} break;
			case 2 : {
				$logsql = $loglevel;
			} break;
		}
		# SQL-Statement wird nur ausgeführt, wenn DBWRITE gesetzt oder
		# wenn keine INSERT, UPDATE und DELETE Anweisungen in $sql stehen.
		# (lesend immer, aber schreibend nur mit DBWRITE=1)
		if (DBWRITE OR (!stristr($sql, 'INSERT') AND !stristr($sql, 'UPDATE') AND !stristr($sql, 'DELETE'))) {
			#echo "<br>SQL in execSQL: " . $sql;
			if ($this->schema != '') {
				$sql = "SET search_path = " . $this->schema . ", public;" . $sql;
			}
			if (count($prepared_params) > 0) {
				$query_id = 'query_' . rand();
				$query = pg_prepare($this->dbConn, $query_id, $sql);
				$query = pg_execute($this->dbConn, $query_id, $prepared_params);
			}
			else {
				#echo "<br>SQL in execSQL: " . $sql;
				$query = @pg_query($this->dbConn, $sql);
			}
			//$query=0;
			if ($query === false) {
				$this->error = true;
				$ret['success'] = false;
				$ret['sql'] = $sql;
				$last_error = pg_last_error($this->dbConn);
				if ($strip_context AND strpos($last_error, 'CONTEXT: ') !== false) {
					$ret['msg'] = substr($last_error, 0, strpos($last_error, 'CONTEXT: '));
				}
				else {
					$ret['msg'] = $last_error;
				}

				if (strpos($last_error, '{') !== false AND strpos($last_error, '}') !== false) {
					# Parse als JSON String;
					$error_obj = json_decode(substr($last_error, strpos($last_error, '{'), strpos($last_error, '}') - strpos($last_error, '{') + 1), true);
					if ($error_obj) {
						if (array_key_exists('msg_type', $error_obj)) {
							$ret['type'] = $error_obj['msg_type'];
						}
						if (array_key_exists('msg', $error_obj) AND $error_obj['msg'] != '') {
							$ret['msg'] = $error_obj['msg'];
						}
					}
				}
				else {
					$ret['type'] = 'error';
				}
				$this->debug->write("<br><b>" . $last_error . "</b>", $debuglevel);
				if ($logsql) {
					$this->logfile->write($this->commentsign . ' ' . $sql . ' ' . $last_error);
				}
			}
			else {
				# Abfrage wurde zunächst erfolgreich ausgeführt
				$ret[0] = 0;
				$ret['success'] = true;
				$ret[1] = $ret['query'] = $query;

				# Prüfe ob eine Fehlermeldung in der Notice steckt
				if (PHPVERSION >= 710) {
					$last_notices = pg_last_notice($this->dbConn, PGSQL_NOTICE_ALL);
				}
				else {
					$last_notices = array(pg_last_notice($this->dbConn));
				}
				foreach ($last_notices as $last_notice) {
					if ($strip_context AND strpos($last_notice, 'CONTEXT: ') !== false) {
						$last_notice = substr($last_notice, 0, strpos($last_notice, 'CONTEXT: '));
					}
					# Verarbeite Notice nur, wenn sie nicht schon mal vorher ausgewertet wurde
					if ($last_notice != '' AND ($this->gui->notices == NULL OR !in_array($last_notice, $this->gui->notices))) {
						$this->gui->notices[] = $last_notice;
						if (strpos($last_notice, '{') !== false AND strpos($last_notice, '}') !== false) {
							# Parse als JSON String
							$notice_obj = json_decode(substr($last_notice, strpos($last_notice, '{'), strpos($last_notice, '}') - strpos($last_notice, '{') + 1), true);
							if ($notice_obj AND array_key_exists('success', $notice_obj)) {
								if (!$notice_obj['success']) {
									$ret['success'] = false;
								}
								if (array_key_exists('msg_type', $notice_obj)) {
									$ret['type'] = $notice_obj['msg_type'];
								}
								if (array_key_exists('msg', $notice_obj) AND $notice_obj['msg'] != '') {
									$ret['msg'] .= $notice_obj['msg'];
								}
							}
						}
						else {
							# Gebe Noticetext wie er ist zurück
							$ret['msg'] .= $last_notice.chr(10).chr(10);
						}
					}
				}

				# Schreibe Meldungen in Log und Debugfile
				$this->debug->write("<br>" . $sql, $debuglevel);
				if ($logsql) {
					$this->logfile->write($sql . ';');
				}
			}
			$ret[2] = $sql;
		}
		else {
			# Es werden keine SQL-Kommandos ausgeführt
			# Die Funktion liefert ret[0]=0, und zeigt damit an, daß kein Datenbankfehler aufgetreten ist,
			$ret[0] = 0;
			$ret['success'] = true;
			# jedoch hat $ret[1] keine query_ID sondern auch den Wert 0
			$ret[1] = 0;
			# Wenn $this->loglevel != 0 wird die sql-Anweisung in die logdatei geschrieben
			# zusätzlich immer in die debugdatei
			# 2006-07-04 pk $logfile ersetzt durch $this->logfile
			if ($logsql) {
				$this->logfile->write($sql . ';');
			}
			$this->debug->write("<br>" . $sql, $debuglevel);
		}

		if ($ret['success']) {
			# alles ok mach nichts weiter
		}
		else {
			# Fehler setze entsprechende Flags und Fehlermeldung
			$ret[0] = 1;
			$ret[1] = $ret['msg'];
			if ($suppress_err_msg) {
				# mache nichts, denn die Fehlermeldung wird unterdrückt
			}
			else {
				if (strpos(strtolower($this->gui->formvars['export_format']), 'json') !== false) {
					header('Content-Type: application/json; charset=utf-8');
					echo utf8_decode(json_encode($ret));
					exit;
				}
				# gebe Fehlermeldung aus.
				$ret[1] = $ret['msg'] = sql_err_msg('Fehler bei der Abfrage der PostgreSQL-Datenbank:' . $sql, $sql, $ret['msg'], 'error_div_' . rand(1, 99999));
				$this->gui->add_message($ret['type'], $ret['msg']);
				echo $sql; exit;
				header('error: true');	// damit ajax-Requests das auch mitkriegen
			}
		}
		$this->success = $ret['success'];
		return $ret;
	}

}
?>