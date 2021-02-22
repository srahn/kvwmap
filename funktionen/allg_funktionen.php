<?php
/* hier befindet sich ein lose Sammlung von Funktionen, die so oder ähnlich im php
 * Funktionenumfang nicht existieren, in älteren Versionen nicht existiert haben,
 * nicht gefunden wurden, nicht verstanden wurden oder zu umfrangreich waren.
 */

$errors = array();

function quote($var){
	return is_numeric($var) ? $var : "'".$var."'";
}

function pg_quote($column){
	return (ctype_lower($column) OR strpos($column, "'") === 0) ? $column : '"'.$column.'"';
}

function get_din_formats() {
	$din_formats = array(
		'A5hoch' => array('value' => 'A5hoch', 'output' => 'A5 hoch', 'size' => '(420 x 595)'),
		'A5quer' => array('value' => 'A5quer', 'output' => 'A5 quer', 'size' => '(595 x 420)'),
		'A4hoch' => array('value' => 'A4hoch', 'output' => 'A4 hoch', 'size' => '(595 x 842)'),
		'A4quer' => array('value' => 'A4quer', 'output' => 'A4 quer', 'size' => '(842 x 595)'),
		'A3hoch' => array('value' => 'A3hoch', 'output' => 'A3 hoch', 'size' => '(842 x 1191)'),
		'A3quer' => array('value' => 'A3quer', 'output' => 'A3 quer', 'size' => '(1191 x 842)'),
		'A2hoch' => array('value' => 'A2hoch', 'output' => 'A2 hoch', 'size' => '(1191 x 1684)'),
		'A2quer' => array('value' => 'A2quer', 'output' => 'A2 quer', 'size' => '(1684 x 1191)'),
		'A1hoch' => array('value' => 'A1hoch', 'output' => 'A1 hoch', 'size' => '(1684 x 2384)'),
		'A1quer' => array('value' => 'A1quer', 'output' => 'A1 quer', 'size' => '(2384 x 1684)'),
		'A0hoch' => array('value' => 'A0hoch', 'output' => 'A0 hoch', 'size' => '(2384 x 3370)'),
		'A0quer' => array('value' => 'A0quer', 'output' => 'A0 quer', 'size' => '(3370 x 2384)'),
	);
	return $din_formats;
}

function str_replace_first($search, $replace, $subject){
	$newstring = $subject;
	$pos = strpos($subject, $search);
	if($pos !== false){
		$newstring = substr_replace($subject, $replace, $pos, strlen($search));
	}
	return $newstring;
}

function replace_tags($text, $tags) {
	$first_right = strpos($text, '>');
	if ($first_right !== false) {
		$text = preg_replace("#<\s*\/?(" . $tags . ")\s*[^>]*?>#im", '', $text);
/*		$first_left = strpos($text, '<');
		if ($first_left !== false and $first_right < $first_left) {
			# >...<
			$last_right = strrpos($text, '>');
			if ($last_right !== false and $last_right > $first_left) {
				# >...<...>
				# entferne $first_right, $last_right und alles dazwischen
				$text = substr_replace($text, '', $first_right, $last_right - $first_right + 1);
			}
		}*/
	}
	return $text;
}

function human_filesize($file){
	$bytes = @filesize($file);
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.2f", $bytes / pow(1024, $factor)).' '.@$sz[$factor].'B';
}

function MapserverErrorHandler($errno, $errstr, $errfile, $errline){
	global $errors;
	if (!(error_reporting() & $errno)) {
		// This error code is not included in error_reporting
		return;
	}
	$errors[] = $errstr;
	/* Don't execute PHP internal error handler */
	return true;
}

function versionFormatter($version) {
  return substr(
    str_pad(
      str_replace(
        '.', 
        '',
        $version
      ),
      3,
      '0',
      STR_PAD_RIGHT
    ),
    0,
    3
  );
}

/*
* This function return the absolute path to a document in the file system of the server
* @param string $document_attribute_value The value of the document attribute stored in the dataset. Can be a path and original name or an url.
* @param string $layer_document_path The document path of the layer the attribute belongs to.
* @param string $layer_document_url optional, default '' The document url of the layer the attribute belongs to. If empty the $document_attribute_value containing an url
* @return string The absolute path to the document
*/
function get_document_file_path($document_attribute_value, $layer_document_path, $layer_document_url = '') {
	$value_part = explode('&original_name=', $document_attribute_value);
	if ($layer_document_url != '') {
		return url2filepath($value_part[0], $layer_document_path, $layer_document_url);
	}
	else {
		return $value_part[0];
	}
}

function url2filepath($url, $doc_path, $doc_url){
	if($doc_path == '')$doc_path = CUSTOM_IMAGE_PATH;
	$url_parts = explode($doc_url, $url);
	return $doc_path.$url_parts[1];
}

/*
* function read exif and gps data from file given in $img_path and return GPS-Position, Direction and creation Time
* @param string $img_path Absolute Path of file with Exif Data to read
* @return array Array with success true if read was successful, LatLng the GPS-Position where the foto was taken Richtung and Erstellungszeit.
*/
function get_exif_data($img_path) {
	$exif = exif_read_data($img_path, 'EXIF, GPS');
	if ($exif === false) {
		return array(
			'success' => false,
			'err_msg' => 'Keine Exif-Daten im Header der Bilddatei ' . $img_path . ' gefunden!'
		);
	}
	else {
#		echo '<br>' . print_r($exif['GPSLatitude'], true);
#		echo '<br>' . print_r($exif['GPSLongitude'], true);
#		echo '<br>' . print_r($exif['GPSImgDirection'], true);
		return array(
			'success' => true,
			'LatLng' => ((array_key_exists('GPSLatitude', $exif) AND array_key_exists('GPSLongitude', $exif)) ? (
				floatval(substr($exif['GPSLatitude' ][0], 0, strlen($exif['GPSLatitude' ][0]) - 2))
				+ float_from_slash_text($exif['GPSLatitude' ][1]) / 60
				+ float_from_slash_text($exif['GPSLatitude' ][2]) / 6000
			) . ' ' . (
				floatval(substr($exif['GPSLongitude'][0], 0, strlen($exif['GPSLongitude'][0]) - 2))
				+ float_from_slash_text($exif['GPSLongitude'][1]) / 60
				+ float_from_slash_text($exif['GPSLongitude'][2]) / 6000
			) : NULL),
			'Richtung' => (array_key_exists('GPSImgDirection', $exif) ? float_from_slash_text($exif['GPSImgDirection']) : NULL),
			'Erstellungszeit' => (array_key_exists('DateTimeOriginal', $exif) ? (
					substr($exif['DateTimeOriginal'], 0 , 4) . '-'
				. substr($exif['DateTimeOriginal'], 5, 2) . '-'
				. substr($exif['DateTimeOriginal'], 8, 2) . ' '
				. substr($exif['DateTimeOriginal'], 11)
			) : NULL)
		);
	}
}

/**
* Function create a float value from a text
* where numerator and denominator are delimited by a slash e.g. 23/100
* @params string $slash_text First part of the string is numerator, second part is denominator.
* @return float The float value calculated from numerator divided by denominator.
* Return Null if string is empty or NULL.
* Return numerator if only one part exists after explode by slash
*/
function float_from_slash_text($slash_text) {
	$parts = explode('/', $slash_text);
	if ($parts[0] == '0') {
		return floatval(0);
	}

	$numerator = floatval($parts[0]);
	if ($numerator == 0) {
		return NULL;
	}

	if (count($parts) == 1) {
		return $numerator;
	}

	$denominator = floatval($parts[1]);
	if ($denominator == 0) {
		return $numerator;
	}

	return $parts[0] / $parts[1];
}

function compare_layers($a, $b){
	$a['alias'] = strtoupper($a['alias']);
	$b['alias'] = strtoupper($b['alias']);
	$a['alias'] = str_replace('Ä', 'A', $a['alias']);
	$a['alias'] = str_replace('Ü', 'U', $a['alias']);
	$a['alias'] = str_replace('Ö', 'O', $a['alias']);
	$a['alias'] = str_replace('ß', 's', $a['alias']);
	$b['alias'] = str_replace('Ä', 'A', $b['alias']);
	$b['alias'] = str_replace('Ü', 'U', $b['alias']);
	$b['alias'] = str_replace('Ö', 'O', $b['alias']);
	$b['alias'] = str_replace('ß', 's', $b['alias']);
	return strcmp($a['alias'], $b['alias']);
}

function compare_names($a, $b){
	return strcmp($a['name'], $b['name']);
}

function compare_orders($a, $b){
	if($a->order > $b->order)return 1;
  else return 0;
}

function compare_groups($a, $b){
  if($a->group > $b->group)return 1;
  else return 0;
}

function compare_legendorder($a, $b){
	if($a['legendorder'] > $b['legendorder'])return 1;
	else return 0;
}

function strip_pg_escape_string($string){
	$string = str_replace("''", "'", $string);
	$string = str_replace('\\\\', '\\', $string);		# \\ wir durch \ ersetzt
	return $string;
}

function replace_semicolon($text) {
  return str_replace(';', '', $text);
}

function InchesPerUnit($unit, $center_y){
	if($unit == MS_METERS){
		return 39.3701;
	}
	elseif($unit == MS_DD){
		if($center_y != 0.0){
			$cos_lat = cos(pi() * $center_y/180.0);
			$lat_adj = sqrt(1 + $cos_lat * $cos_lat)/sqrt(2);
		}
		return 4374754 * $lat_adj;
	}
}

function ie_check(){
	$browser = $_SERVER['HTTP_USER_AGENT'];
	if(preg_match("/MSIE/i", $browser) OR preg_match("/rv:11.0/i", $browser) OR preg_match("/Edge/i", $browser)){
			return TRUE;
	}
	else{
			return FALSE;
	}
}

if(!function_exists('mb_strrpos')){		# Workaround, falls es die Funktion nicht gibt
	function mb_strrpos($str, $search, $offset = 0, $encoding){
		return strrpos($str, $search, $offset);
	}
}

if(!function_exists('mb_substr')){		# Workaround, falls es die Funktion nicht gibt
	function mb_substr($str, $start, $length, $enc = NULL){
		return substr($str, $start, $length);
	}
}


function formatFlurstkennzALKIS($FlurstKennzListe){
	$Flurstuecke = explode(';', $FlurstKennzListe);
	for($i = 0; $i < count($Flurstuecke); $i++){
		if($Flurstuecke[$i] != ''){
			$FlurstKennz = $Flurstuecke[$i];
			if(strpos($FlurstKennz, '/') !== false OR strpos($FlurstKennz, '-') !== false){
				if(strpos($FlurstKennz, '/') !== false){		# ALB-Schreibweise 131234-001-00234/005.00
					$explosion = explode('-', $FlurstKennz);
					$gem = trim($explosion[0]);
					$flur = trim($explosion[1]);
					$flurst = trim($explosion[2]);
					$explosion = explode('/',$flurst);
					$zaehler = $explosion[0];
					$nenner = $explosion[1];
					if($nenner != '000.00'){
						$explosion = explode('.',$nenner);
						$vorkomma = str_pad($explosion[0], 4, '0', STR_PAD_LEFT);
					}
					else $vorkomma = '';
				}
				elseif(strpos($FlurstKennz, '-') !== false){		# Kurzschreibweise 13-1234-1-234-5
					$explosion = explode('-', $FlurstKennz);
					$land = $explosion[0];
					$gem = $explosion[1];
					$flur = str_pad($explosion[2], 3, '0', STR_PAD_LEFT);
					$zaehler = str_pad($explosion[3], 5, '0', STR_PAD_LEFT);
					$nenner = $explosion[4];
					if($nenner != ''){
						$explosion = explode('.',$nenner);
						$vorkomma = str_pad($explosion[0], 4, '0', STR_PAD_LEFT);
					}
					else $vorkomma = '';
				}
				$FlurstKennz = $land.$gem.$flur.$zaehler.$vorkomma;
			}
			$Flurstuecke[$i] = str_pad($FlurstKennz, 20, '_', STR_PAD_RIGHT);
		}
	}
  return implode(';', $Flurstuecke);
}

function formatFlurstkennzALKIS_0To_($FlurstKennzListe){
	$Flurstuecke = explode(';', $FlurstKennzListe);
	for ($i = 0; $i < count($Flurstuecke); $i++) {
		$Flurstuecke[$i] = str_pad(
			substr(
				$Flurstuecke[$i],
				0,
				(intval(substr($Flurstuecke[$i], 14, 4)) == 0 ? 14 : 18)
			),
			20,
			'_',
			STR_PAD_RIGHT
		);
	}
  return implode(';', $Flurstuecke);
}

function formatFlurstkennzALK($FlurstKennz){
	$gem = substr($FlurstKennz, 0, 6);
	$flur = substr($FlurstKennz, 6, 3);
	$zaehler = substr($FlurstKennz, 9, 5);
	$vorkomma = str_pad(intval(substr($FlurstKennz, 15, 3)), 3, '0', STR_PAD_LEFT);
  $FlurstKennz = $gem.'-'.$flur.'-'.$zaehler.'/'.$vorkomma.'.00';
  return $FlurstKennz;
}

function tausenderTrenner($number){
	if($number != ''){
		$explo = explode('.', $number);
		$formated_number = number_format($explo[0], 0, ',', '.');
		if($explo[1] != '')$formated_number .= ','.$explo[1];
		return $formated_number;
	}
}

function removeTausenderTrenner($number){
	if($number != ''){
		$number = str_replace('.', '', $number);		# Punkt entfernen
		$number = str_replace(',', '.', $number);		# Komma in Punkt umwandeln
		return $number;
	}
}

function transformCoordsSVG($path){
	$path = str_replace('L ', '', $path);		# neuere Postgis-Versionen haben ein L mit drin
  $svgcoords = explode(' ',$path);
  $anzahl = count($svgcoords);
  for($i = 0; $i < count($svgcoords); $i++){
    if($svgcoords[$i] == 'M'){
    	$newsvgcoords[] = 'M';
    	$last_startcoordx = $svgcoords[$i+1];
    	$last_startcoordy = -1 * $svgcoords[$i+2];
    }
    if($svgcoords[$i] != 'M' AND $svgcoords[$i] != 'Z' AND $svgcoords[$i] != ''){
    	$newsvgcoords[] = $svgcoords[$i];
      $newsvgcoords[] = -1 * $svgcoords[$i+1];
      $i++;
    }
    if($svgcoords[$i] == 'Z'){			# neuere Postgis-Versionen liefern bei asSVG ein Z zum Schließen des Rings anstatt der Startkoordinate
    	$newsvgcoords[] = $last_startcoordx;
    	$newsvgcoords[] = $last_startcoordy;
    }
  }
  $svgresult = 'M';
  for($i = 1; $i < count($newsvgcoords); $i++){
    $svgresult .= ' '.$newsvgcoords[$i];
  }
  return $svgresult;
}

function dms2dec($number){
	$part1 = explode('°', $number);
	$degrees = $part1[0];
	$part2 = explode("'", $part1[1]);
	$minutes = $part2[0];
	$seconds = trim($part2[1], '"');
	$seconds = $seconds / 60;
	$minutes = ($minutes+$seconds) / 60;
	return $degrees + $minutes;
}

function dec2dms($number){
	$part1 = explode('.', $number);
	$degrees = $part1[0];
	$minutes = ('0.'.$part1[1]) * 60;
	$part2 = explode('.', $minutes);
	$minutes = $part2[0];
	$seconds = round(('0.'.$part2[1]) * 60);
	return $degrees."°".$minutes."'".$seconds.'"';
}

function dec2dmin($number){
	# convert decimal degree value to degree and decimal minutes
	$part1 = explode('.', $number);
	$degrees = $part1[0];
	$minutes = ('0.'.$part1[1]) * 60;
	return $degrees."°".round($minutes,3);
}

function allocateImageColors($image, $colors) {
	$imageColors = Array();
	foreach($colors AS $colorName => $rgbValues) {
		$imageColors[$colorName] = ImageColorAllocate($image, $rgbValues[0], $rgbValues[1], $rgbValues[2]);
	}
	return $imageColors;
}

function rgb2hsl($r, $g, $b){
	$oldR = $r;
	$oldG = $g;
	$oldB = $b;
	$r /= 255;
	$g /= 255;
	$b /= 255;
	$max = max( $r, $g, $b );
	$min = min( $r, $g, $b );
	$h;
	$s;
	$l = ( $max + $min ) / 2;
	$d = $max - $min;
	if($d == 0){
		$h = $s = 0; // achromatic
	}
	else{
		$s = $d / ( 1 - abs( 2 * $l - 1 ) );
		switch($max){
			case $r:
				$h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
				if($b > $g)$h += 360;
			break;
			case $g:
				$h = 60 * ( ( $b - $r ) / $d + 2 );
			break;
			case $b:
				$h = 60 * ( ( $r - $g ) / $d + 4 );
			break;
		}
	}
	return array(round($h, 2), round($s, 2), round($l, 2));
}

function hsl2rgb($h, $s, $l){
	$r;
	$g;
	$b;
	$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
	$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
	$m = $l - ( $c / 2 );
	if($h < 60){
		$r = $c;
		$g = $x;
		$b = 0;
	}
	elseif($h < 120){
		$r = $x;
		$g = $c;
		$b = 0;
	}
	elseif($h < 180){
		$r = 0;
		$g = $c;
		$b = $x;
	}
	elseif($h < 240){
		$r = 0;
		$g = $x;
		$b = $c;
	}
	elseif($h < 300){
		$r = $x;
		$g = 0;
		$b = $c;
	}
	else{
		$r = $c;
		$g = 0;
		$b = $x;
	}
	$r = ($r + $m) * 255;
	$g = ($g + $m) * 255;
	$b = ($b + $m) * 255;
  return array(floor($r), floor($g), floor($b));
}

if(!function_exists('imagerotate')){
	function imagerotate($source_image, $angle, $bgd_color){
		$angle = 360-$angle; // GD rotates CCW, imagick rotates CW
    $file1 = IMAGEPATH.'imagick_' . rand( 10000,99999 ) . '.png';
    $file2 = IMAGEPATH.'imagick_' . rand( 10000,99999 ) . '.png';
    if(@imagepng($source_image, $file1)){
    	exec(IMAGEMAGICKPATH.'convert -rotate ' . $angle . ' ' . $file1 . ' ' . $file2 );
      if(file_exists($file2)){
        $new_image = imagecreatefrompng($file2);
        unlink($file1);
        unlink($file2);
        return $new_image;
      }
      else{
      	echo 'Bildrotation mit ImageMagick fehlgeschlagen.';
      	return $source_image;
      }
    }
    else{
    	echo 'Kann temporäre Bilddateien nicht anlegen.';
    	return $source_image;
    }
	}
}


function st_transform($x,$y,$from_epsg,$to_epsg) {
	#$x = 12.099281283333;
	#$y = 54.075214183333;
  $point = ms_newPointObj();
	$point->setXY($x,$y);
	$projFROM = ms_newprojectionobj("init=epsg:".$from_epsg);
  $projTO = ms_newprojectionobj("init=epsg:".$to_epsg);
  $point->project($projFROM, $projTO);
  return $point;
}

function checkPasswordAge($passwordSettingTime, $allowedPassordAgeMonth) {
  $passwordSettingUnixTime = strtotime($passwordSettingTime); # Unix Zeit in Sekunden an dem das Passwort gesetzt wurde
  $allowedPasswordAgeDays = round($allowedPassordAgeMonth * 30.5); # Zeitintervall, wie alt das Password sein darf in Tagen
  $passwordAgeDays = round((time()-$passwordSettingUnixTime)/60/60/24); # Zeitinterval zwischen setzen des Passwortes und aktueller Zeit in Tagen
  $allowedPasswordAgeRemainDays = $allowedPasswordAgeDays-$passwordAgeDays; # Zeitinterval wie lange das Passwort noch gilt in Tagen
	return $allowedPasswordAgeRemainDays; // Passwort ist abgelaufen wenn Wert < 1
}

/**
* Prüft ob ein Passwort ein gutes Passwort ist.
*
* Diese Funktion prüft die Länge, Anzahl wiederholter Zeichen und einfachheit von Passwörtern
* Code wurde abgeleitet von http://scripts.franciscocharrua.com/check-password.php und
* http://www.vbforums.com/showthread.php?p=2347960 und wurde stark verändert und ergänzt.
* Vielen Dank trotzdem an die Autoren.
*
* Reihenfolge: Übersichtssatz - Kommentar - Tags.
*
* @param string password Zu prüfendes Password als Text
* @return string Fehlermeldung zur Beschreibung, was an dem Password schlecht ist, oder leerer String, wenn Password gut ist.
* @see    createRandomPassword(), checkPasswordAge, $GUI, $user, $stelle
*/
# Passwortprüfung
function isPasswordValide($oldPassword, $newPassword, $newPassword2) {
	#echo '<p>allg_funktionen isPasswortValide old: ' . $oldPassword . ' new1: ' . $newPassword . ' new2: ' . $newPassword2;
  $password_errors = array();
  $check = 0;

	# Prüft ob das Password eingegeben wurde
	if (strlen($newPassword) == 0) {
		$password_errors[] = "ist leer";
		$check = 1;
	}

	# Prüft ob die Passwortwiederholung eingegeben wurde.
	if ($check == 0 AND strlen($newPassword2) == 0) {
		$password_errors[] = " hat keine Wiederholung";
		$check = 1;
	}

	# Wenn oldPassword angegeben wurde
	if ($check == 0 AND strlen($oldPassword) > 0) {
		# Prüft ob neues Passwort genau dem alten Passwort entspricht
		if ($check == 0 and $oldPassword==$newPassword) {
			$password_errors[] = "muss sich vom alten unterscheiden";
			$check = 1;
		}
	}

  # Prüft ob neues Passwort der Wiederholung entspricht
  if ($check == 0 and $newPassword!=$newPassword2) {
    $password_errors[] = "muss mit der Wiederholung übereinstimmen";
    $check = 1;
  }

  # Prüft die Länge des Passwortes
  $strlen = mb_strlen($newPassword);
  if($check == 0 and $strlen <= 5) {
    $password_errors[] = "ist zu kurz";
    $check = 1;
  }

  if($check == 0 and $strlen > PASSWORD_MAXLENGTH) {
    $password_errors[] = "ist zu lang (maximal ".PASSWORD_MAXLENGTH." Zeichen)";
    $check = 1;
  }

  if($check == 0 and $strlen < PASSWORD_MINLENGTH) {
    $password_errors[] = "ist zu kurz (mindestens ".PASSWORD_MINLENGTH." Zeichen)";
    $check = 1;
  }

  # Prüft die Anzahl unterschiedlicher Zeichen
  $count_chars = count_chars($newPassword, 3);
  if($check == 0 and strlen($count_chars) < $strlen / 2) {
    $password_errors[] = "hat zu viele gleiche Zeichen";
    $check = 1;
  }

  if($check == 0) {

    # Prüft die Stärke des Passworts
    if (substr(PASSWORD_CHECK,0,1)=='0') {
      $strength = 0;
      $patterns = array('#[a-z]#','#[A-Z]#','#[0-9]#','/[¬!"£$%^&*()`{}\[\]:@~;\'#<>?,.\/\\-=_+\|]/');
      foreach($patterns as $pattern) {
      	if(preg_match($pattern,$newPassword,$matches)) {
          $strength++;
        }
      }
      // strength=
      // 1 - weak
      // 2 - not weak
      // 3 - acceptable
      // 4 - strong
      if ($strength<3) {
      	$password_errors[] = "ist zu schwach";
      }
    }

    # Prüft auf Kleinbuchstaben
    if (substr(PASSWORD_CHECK,0,1)=='1' and substr(PASSWORD_CHECK,1,1)=='1') {
      if(!preg_match('/[a-z]/',$newPassword)) {
        $password_errors[] = "weist keine Kleinbuchstaben auf";
      }
    }

    # Prüft auf Großbuchstaben
    if (substr(PASSWORD_CHECK,0,1)=='1' and substr(PASSWORD_CHECK,2,1)=='1') {
      if(!preg_match('/[A-Z]/',$newPassword)) {
        $password_errors[] = "weist keine Großbuchstaben auf";
      }
    }

    # Prüft auf Zahlen
    if (substr(PASSWORD_CHECK,0,1)=='1' and substr(PASSWORD_CHECK,3,1)=='1') {
      if(!preg_match('/[0-9]/',$newPassword)) {
        $password_errors[] = "weist keine Zahlen auf";
      }
    }

    # Prüft auf Sonderzeichen
    if (substr(PASSWORD_CHECK,0,1)=='1' and substr(PASSWORD_CHECK,4,1)=='1') {
      if(!preg_match('/[¬!"£$%^&*()`{}\[\]:@~;\'#<>?,.\/\\-=_+\|]/',$newPassword)) {
        $password_errors[] = "weist keine Sonderzeichen auf";
      }
    }

  }

  //Zusammenstellung der Fehlermeldung - wenn kein Fehler vorlag: Rückgabe eines leeren Strings
  $return_string = "";
  $anzErrors=count($password_errors);
  for($i=0;$i<$anzErrors;$i++) {
    if($i==0) {
      $return_string.="Das neue Passwort ";
    }
    else {
    	if($i<$anzErrors-1)
        $return_string.=", ";
      if($i==$anzErrors-1)
        $return_string.=" und ";
    }
    $return_string.=$password_errors[$i];
  }

  return $return_string;
}

/*
* Erzeugt an Hand der Einstellungen für die Passwortstärke einen Hilfetext für die
* Vergabe eines neuen Passwortes
*/
function password_erstellungs_hinweis($lang) {
	$condition = array();
	$msg = '';
	if (substr(PASSWORD_CHECK, 0, 1) == '0') {
		$msg = 'Das Passwort muss 3 der 4 Kriterien: Kleinbuchstaben, Großbuchstaben, Zahlen und Sonderzeichen enthalten.';
	}
	else {
		if (substr(PASSWORD_CHECK, 1, 1) == '1') {
			$conditions[] = 'ein Kleinbuchstaben';
		}
		if (substr(PASSWORD_CHECK, 2, 1) == '1') {
			$conditions[] = 'ein Großbuchstaben';
		}
		if (substr(PASSWORD_CHECK, 3, 1) == '1') {
			$conditions[] = 'eine Zahl';
		}
		if (substr(PASSWORD_CHECK, 4, 1) == '1') {
			$conditions[] = 'ein Sonderzeichen';
		}

		$msg = 'Das Passwort muss mindestens ';
		$num_conditions = count($conditions);
		for ($i = 0; $i < $num_conditions; $i++) {
			$msg .= $conditions[$i];
			if ($i < $num_conditions - 2) {
				$msg .= ', ';
			}
			else {
				if ($i < $num_conditions - 1) {
					$msg .= ' ' . $lang['strAnd'] . ' ';
				}
			}
		}
		$msg .= ' beinhalten.';
	}
	return $msg;
}

/**
* Erzeugen eines zufälligen Passwortes
*
* Diese Funktion erzeugt ein zufälliges sicheres Password. Die Funktion wurde von Totally PHP übernommen und mit zusätzlichen Zeichen versehen
* siehe: http://www.totallyphp.co.uk/code/create_a_random_password.htm
* Vielen Dank an den Autor.
*
* Reihenfolge: Übersichtssatz - Kommentar - Tags.
*
* @return string ein achtstelliges Password
* @see    isPasswordValide(), checkPasswordAge, $GUI, $user, $stelle
*/
function createRandomPassword($passwordLength) {
	if ($passwordLength<8)
		$passwordLength=8;
	if ($passwordLength>16)
	  $passwordLength=16;
  $chars[0]= "abcdefghijkmnopqrstuvwxyz";
  $chars[1]= "ABCDEFGHIJKMNOPQRSTUVWXYZ";
  $chars[2]= "0234567890234567890234567";
  $chars[3]= "()_+*-.:,;!§$%&=#()_+*-.:";
  $password='';
  $charListNumbers=array();
  $charListNumber=rand(0,3);
  $loops=0;
  while (strlen($password)<$passwordLength AND $loops++ < 100) {
  	while (count($charListNumbers)<4) {
  		if (!in_array($charListNumber,$charListNumbers)) { # wenn die charListNumber noch nicht in der Liste ist
  			$charListNumbers[]=$charListNumber; # charListNumber in die Liste aufnehmen
  			$char=substr($chars[$charListNumber],rand(0,24),1); # Character aus der Characterliste mit charListNumber entnehmen
  			#if ($char==' ') $char='_'; # darf nur auf keinen Fall ein Leerzeichen beinhalten
  			$password.=$char;
  			#echo '<br>'.strlen($password).' '.$password;
  		}
  		$charListNumber=rand(0,3);
  	}
  	$charListNumbers = array();
  }
  return $password;
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

function stripScript($request) {
	# Definition von Tags, die gestripped werde sollen
	$search = array('@<script[^>]*?>.*?</script>@si');
	foreach($request AS $key => $value) {
		if (is_array($value)) {
			$ret[$key]=stripScript($value);
		}
		else {
			$ret[$key]=preg_replace($search, '', $value);
		}
	}
	return $ret;
}

function isTag($word) {
	if (substr($word,1)=='<') {
		if (substr($word,-1)=='>') {
			return true;
		}
	}
	return false;
}

function drawColorBox($color,$outlinecolor) {
  # Funktion liefert eine Box als überlagerte Div in html,
  # die die Farbe $color und die Border $outlinecolor hat.
  $c=explode(' ',trim($color));
  $bgcolor='#';
  for ($i=0;$i<3;$i++) {
    $bgcolor.=strtoupper(str_pad(dechex($c[$i]), 2, 0, STR_PAD_LEFT));
  }
  $oc=explode(' ',trim($outlinecolor));
  $bordercolor='#';
  for ($i=0;$i<3;$i++) {
    $bordercolor.=strtoupper(str_pad(dechex($oc[$i]), 2, 0, STR_PAD_LEFT));
  }
  ?>
<div id="Layer1" style="position:relative; width:22px; height:12px; z-index:1; left: 0px; top: 0px; background-color: <?php echo $bordercolor; ?>; layer-background-color: #00CCFF; ?>; border: 0px none;">
  <div id="Layer2" style="position:relative; width:20px; height:10px; z-index:2; left: 1px; top: 1px; background-color: <?php echo $bgcolor; ?>; layer-background-color: #00CCFF; border: 0px none;">
  </div>
</div>
  <?php
}


// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Aidan Lister <aidan@php.net>                                |
// +----------------------------------------------------------------------+
//
// $Id: str_split.php,v 1.13 2004/11/21 14:21:17 aidan Exp $

/**
 * Replace str_split()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.str_split
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.13 $
 * @since       PHP 5
 * @require     PHP 4.0.1 (trigger_error)
 */
if (!function_exists('str_split')) {
    function str_split($string, $split_length = 1)
    {
        if (!is_scalar($split_length)) {
            trigger_error('str_split() expects parameter 2 to be long, ' . gettype($split_length) . ' given', E_USER_WARNING);
            return false;
        }

        $split_length = (int) $split_length;
        if ($split_length < 1) {
            trigger_error('str_split() The length of each segment must be greater than zero', E_USER_WARNING);
            return false;
        }

        preg_match_all('/.{1,' . $split_length . '}/s', $string, $matches);
        return $matches[0];
    }
}

function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true){
	# 1. Methode über unzip (nur Linux)
	$output = array();
	$entries = NULL;
	exec('export LD_LIBRARY_PATH=;unzip -l "'.$src_file.'" -d '.dirname($src_file), $output);
	#echo '<br>unzip -l "'.$src_file.'" -d '.dirname($src_file);
	for($i = 3; $i < count($output)-2; $i++){
  		$entries[] = array_pop(explode('   ', $output[$i]));
	}
	if($entries != NULL){
		exec('export LD_LIBRARY_PATH=;unzip -o "'.$src_file.'" -d '.dirname($src_file));
	}
	# 2. Methode über php_zip Extension
	else{
	  if ($zip = zip_open($src_file)){
	    if ($zip){
	      $splitter = ($create_zip_name_dir === true) ? "." : "/";
	      if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
	      @mkdir($dest_dir);
	      while ($zip_entry = zip_read($zip)){
	        $entries[] = zip_entry_name($zip_entry);
	        $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
	        if ($pos_last_slash !== false){
	          @mkdir($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
	        }
	        if (zip_entry_open($zip,$zip_entry,"r")){
	          $file_name = $dest_dir.zip_entry_name($zip_entry);
	          if ($overwrite === true || $overwrite === false && !is_file($file_name)){
	            $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							$fp = fopen($file_name, 'w');
	            fwrite($fp, $fstream );
	            fclose($fp);
	            chmod($file_name, 0777);
	          }
	          zip_entry_close($zip_entry);
	        }
	      }
	      zip_close($zip);
	    }
	  }
	}
	return $entries;
}

function html_umlaute($string){
	$string = str_replace('ä', '&auml;', $string);
	$string = str_replace('ü', '&uuml;', $string);
	$string = str_replace('ö', '&ouml;', $string);
	$string = str_replace('Ä', '&Auml;', $string);
	$string = str_replace('Ü', '&Uuml;', $string);
	$string = str_replace('Ö', '&Ouml;', $string);
	$string = str_replace('ß', '&szlig;', $string);
	$string = str_replace('ø', '&oslash;', $string);
	$string = str_replace('æ', '&aelig;', $string);
	return $string;
}

function umlaute_html($string){
	$string = str_replace('&auml;', 'ä', $string);
	$string = str_replace('&uuml;', 'ü', $string);
	$string = str_replace('&ouml;', 'ö', $string);
	$string = str_replace('&Auml;', 'Ä', $string);
	$string = str_replace('&Uuml;', 'Ü', $string);
	$string = str_replace('&Ouml;', 'Ö', $string);
	$string = str_replace('&szlig;', 'ß', $string);
	$string = str_replace('&oslash;', 'ø', $string);
	$string = str_replace('&aelig;', 'æ', $string);
	$string = str_replace('&nbsp;', ' ', $string);
	return $string;
}

/**
* Diese Funktion sortiert das Array $array unter Berücksichtigung von Umlauten.
* Zusätzlich läßt sich ein zweites Array $second_array übergeben, welches genauso viele
* Elemente haben muß wie das erste und dessen Elemente entsprechend der Sortierung des
* ersten Arrays angeordnet werden, dadurch bleiben die Index-Beziehungen beider Arrays erhalten.
* Außerdem werden alle Array-Elemente unabhängig von Groß/Kleinschreibung sortiert.
*/
function umlaute_sortieren($array, $second_array) {
	if(is_array($array)){
		$oldarray = $array;
		for($i = 0; $i < count($array); $i++){
			$array[$i] = strtoupper($array[$i]);
	  	$array[$i] = str_replace('Ä', 'A', $array[$i]);
	  	$array[$i] = str_replace('Ü', 'U', $array[$i]);
	  	$array[$i] = str_replace('Ö', 'O', $array[$i]);
	  	$array[$i] = str_replace('ß', 's', $array[$i]);
		}
		@asort($array);
		if($second_array != NULL){
			for($i = 0; $i < count($array); $i++){
				$newarray[] = $oldarray[key($array)];
				$new_second_array[] = $second_array[key($array)];
				next($array);
			}
			$arrays['array'] = $newarray;
			$arrays['second_array'] = $new_second_array;
			return $arrays;
		}
		else{
			for($i = 0; $i < count($array); $i++){
				$newarray[] = $oldarray[key($array)];
				next($array);
			}
			return $newarray;
		}
	}
}

function umlaute_umwandeln($name){
	$name = str_replace('ä', 'ae', $name);
	$name = str_replace('ü', 'ue', $name);
	$name = str_replace('ö', 'oe', $name);
	$name = str_replace('Ä', 'Ae', $name);
	$name = str_replace('Ü', 'Ue', $name);
	$name = str_replace('Ö', 'Oe', $name);
	$name = str_replace('a?', 'ae', $name);
	$name = str_replace('u?', 'ue', $name);
	$name = str_replace('o?', 'oe', $name);
	$name = str_replace('A?', 'ae', $name);
	$name = str_replace('U?', 'ue', $name);
	$name = str_replace('O?', 'oe', $name);
	$name = str_replace('ß', 'ss', $name);
	$name = str_replace('.', '', $name);
	$name = str_replace(':', '', $name);
	$name = str_replace('(', '', $name);
	$name = str_replace(')', '', $name);
	$name = str_replace('/', '-', $name);
	$name = str_replace(' ', '_', $name);
	$name = str_replace('-', '_', $name);
	$name = str_replace('?', '_', $name);
	$name = str_replace('+', '_', $name);
	$name = str_replace(',', '_', $name);
	$name = str_replace('*', '_', $name);
	$name = str_replace('$', '', $name);
	$name = str_replace('&', '_', $name);
	$name = iconv("UTF-8", "UTF-8//IGNORE", $name);
	return $name;
}

function umlaute_umwandeln_reverse($name){
  $name = str_replace('ae', 'ä', $name);
  $name = str_replace('ue', 'ü', $name);
  $name = str_replace('oe', 'ö', $name);
  $name = str_replace('Ae', 'Ä', $name);
  $name = str_replace('Ue', 'Ü', $name);
  $name = str_replace('Oe', 'Ö', $name);
  $name = str_replace('_', ' ', $name);
  $name = str_replace('aü', 'aue', $name);
  return $name;
}

function umlaute_javascript($text){
	$text = str_replace("ä", "%E4", $text);
	$text = str_replace("ö", "%F6", $text);
	$text = str_replace("ü", "%FC", $text);
	$text = str_replace("Ä", "%C4", $text);
	$text = str_replace("Ö", "%D6", $text);
	$text = str_replace("Ü", "%DC", $text);
	$text = str_replace("ß", "%DF", $text);
	$text = str_replace("²", "%B2", $text);
	$text = str_replace('"', '%A8', $text);
	$text = str_replace('&', '%26', $text);
	return $text;
}


function stringrpos($haystack,$needle){   # findet das letzte Vorkommen eines Strings (gibs erst ab php 5)
   return strlen($haystack)- strpos( strrev($haystack) , strrev($needle) , NULL)- strlen($needle);
}

function rotate($polygon, $angle){
  for($i = 0; $i < count($polygon); $i++){
    $newpolygon[$i] = cos(deg2rad($angle))*$polygon[$i] - sin(deg2rad($angle))*$polygon[$i+1];
    $newpolygon[$i+1] = sin(deg2rad($angle))*$polygon[$i] + cos(deg2rad($angle))*$polygon[$i+1];
    $i++;
  }
  return $newpolygon;
}

function translate($polygon, $transx, $transy){
  for($i = 0; $i < count($polygon); $i++){
    $newpolygon[$i] = $polygon[$i] + $transx;
    $newpolygon[$i+1] = $polygon[$i+1] + $transy;
    $i++;
  }
  return $newpolygon;
}

function is_dir_empty($path){
	$handle = opendir($path);
	$one = readdir($handle);		# .
	$two = readdir($handle);		# ..
	$three = readdir($handle);	# ???
	closedir($handle);
	if($three == ''){
		return true;
	}
	else{
		return false;
	}
}

function searchdir($path, $recursive){
    # liefert ein Array mit den Pfaden aller Dateien im Verzeichnis
    if (substr($path, strlen($path) - 1 ) != '/' ){
      $path .= '/';
    }
    $dirlist = array() ;
    //$dirlist[] = $path ;
    if ($handle = opendir($path)){
      while ( false !== ($file = readdir($handle))){
        if ($file != '.' && $file != '..' ){
          $file = $path.$file;
          if (!is_dir($file)){
            $dirlist[] = $file;
          }
          elseif($recursive == true){
            $result = searchdir($file . '/', true) ;
            $dirlist = array_merge($dirlist,$result) ;
          }
        }
      }
      closedir ( $handle ) ;
    }
    sort($dirlist);
    return ($dirlist);
}

function get_select_parts($select) {
	$column = explode(',', $select); # an den Kommas splitten
	for($i = 0; $i < count($column); $i++) {
		$klammerauf = substr_count($column[$i], '(');
		$klammerzu = substr_count($column[$i], ')');
		$hochkommas = substr_count($column[$i], "'");
		# Wenn ein Select-Teil eine ungerade Anzahl von Hochkommas oder mehr Klammern auf als zu hat,
		# wurde hier entweder ein Komma im einem String verwendet (z.B. x||','||y) oder eine Funktion (z.B. round(x, 2)) bzw. eine Unterabfrage mit Kommas verwendet
		if ($hochkommas % 2 != 0 OR $klammerauf > $klammerzu) {
			$column[$i] = $column[$i] . ',' . $column[$i + 1];
			array_splice($column, $i + 1, 1);
			$i--; # und nochmal prüfen, falls mehrere Kommas drin sind
		}
	}
	return $column;
}

function microtime_float(){
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}


function copy_file_to_tmp($frompath, $dateiname = ''){
  $dateityp = explode('.',$frompath);
  $dateipfad=IMAGEPATH;
  if($dateiname == '')$dateiname=rand(100000,999999).'.'.$dateityp[1];
  if(copy($frompath, $dateipfad.$dateiname) == true){
    return TEMPPATH_REL.$dateiname;
  }
  else{
    echo 'Datei '.$frompath.' konnte nicht nach '.$dateipfad.$dateiname.' kopiert werden.';
  }
	#exec('ln -s '.$frompath.' '.$dateipfad.$dateiname);
	#return TEMPPATH_REL.$dateiname;
}

function read_epsg_codes($database){
  $epsg_codes = $database->read_epsg_codes();
  return $epsg_codes;
}

function read_colors($database){
  $colors = $database->read_colors();
  return $colors;
}

function delete_files($target, $exceptions, $output){
	if(is_dir($target)){
	   $sourcedir = opendir($target);
	   while(false !== ($filename = readdir($sourcedir)))
	   {
	       if(!in_array($filename, $exceptions))
	       {
	           if($output)
	           { echo "Processing: ".$target."/".$filename."<br>"; }
	           if(is_dir($target."/".$filename))
	           {
	               // recurse subdirectory; call of function recursive
	               delete_files($target."/".$filename, $exceptions,0);
	           }
	           else if(is_file($target."/".$filename))
	           {
	               // unlink file
	               unlink($target."/".$filename);
	           }
	       }
	   }
	   closedir($sourcedir);
	   if(rmdir($target))
	   { return true; }
	   else
	   { return false; }
	}
}

function str_space($string, $split_length = 1) {
  # str_split...include the pieces of the reversed string splittet by the str_split function
  # e.g. 12345 mit split_length=3 -> piece[0]...543, piece[1]...21
  $pieces=str_split(strrev($string), 3);
  # amtpieces... amount of pieces created from str_split function
  $amtpieces=count($pieces);
  # returnstr... include the string composed with the backward reversed string pieces
  # and the required space character between ($returnstr... 12 345)
  $returnstr=strrev($pieces[$amtpieces-1]);
  for ($i=$amtpieces-1;$i>0;$i--) {
    $returnstr.=' '.strrev($pieces[$i-1]);
  }
  return $returnstr;
}

########### Zeigt Text in einem Java-Script Alarmfenster an
function showAlert($text) {
  ?>
  <script type="text/javascript">
    alert("<?php echo str_replace('"', '\"', $text); ?>");
  </script><?php
}

########### Funktion wandelt UNIX Zeichen in DOS Zeichen um für Konvertierung WLDGE-Dateien
function unix2dos($text) {
   $search  = array ("{", "|", "}", "~","'","[","\\","]","@");
   $replace = array ("ä", "ö", "ü", "ß","\"","Ä","Ö","Ü","§");
   return str_replace($search, $replace, $text);
}

function ANSII2DOS($text) {
  $search  = array ('"',chr(132),chr(142),chr(148),chr(153),chr(129),chr(154),chr(225));
  $replace = array ('\'','ä','Ä','ö','Ö','ü','Ü','ß');
  return str_replace($search, $replace, $text);
}

function convertDBFCodePage($filename) {
  $dbfid=dbase_open ($filename,2);
  if ($dbfid==0) {
    echo "<b>Fehler beim öffnen der dbf-Tabelle!</b>";
    return 0;
  }
  echo "<br>Beginne mit schreiben der Tabelle ".$filename."...";
  for ($i=1;$i<=dbase_numrecords($dbfid);$i++) {
    $dbfrs=dbase_get_record ($dbfid,$i);
    echo "<br>";
    for ($j=0;$j<dbase_numfields($dbfid);$j++) {
      $dbfrs[$j]=trim(ANSII2DOS($dbfrs[$j]));
      echo $dbfrs[$j]." ";
    }
    # Löschen des letzten Arrayelements (deleted)
    array_pop($dbfrs);
    if (!dbase_replace_record($dbfid,$dbfrs,$i)) {
      echo "<br><b>Fehler beim umschreiben der dbf-Tabelle in Zeile ".$i."!</b>";
    }
  }
  echo "<br>...fertig";
  echo "<br>".$i." Zeilen in neue dbf-Tabelle geschrieben";
  dbase_close ($dbfid);
}

# Funktion bricht $text in Wörtern in ein Array von Zeilen der Länge $laenge um
# Beispiel:
# $block=zeilenumbruch('Dies ist ein Beilspiel.',12);
# echo $block[0]; # liefert "Dies ist ein"
# echo $block[1]; # liefert "Beispiel"
function zeilenumbruch($text,$laenge) {
  $wort=explode(' ',$text);
  $ausgabetext=$wort[0];
  for ($i=1;$i<count($wort);$i++) {
    if ((strlen($ausgabetext)+strlen($wort[$i])+1)>$laenge) {
      $ausgabe[]=$ausgabetext;
      $ausgabetext=$wort[$i];
    }
    else {
      $ausgabetext.=' '.$wort[$i];
    }
  }
  $ausgabe[]=$ausgabetext;
  return $ausgabe;
}

function runLenComp($liste) {
  # Funktion führt eine Lauflängenkodierung des Arrays aus
  # und komprimiert somit das Array von Einzelwerten
  # array runLenComp(array array);
  # von den vorkommenden ganzen Zahlen werden von-bis Intervalle gebildet
  # sortieren der Liste
  sort($liste);
  $anz=count($liste);
  $comp=$liste[0];
  $intervalwidth=0;
  for ($i=1;$i<$anz;$i++) {
    if ($liste[$i]==$liste[$i-1]+1) {
      # der nächste Wert ist eins größer im gleichen Intervall
      $intervalwidth++;
      # wenn das letzte Element erreicht ist Intervall schließen
      if ($i==$anz-1) {
        $comp.='-'.$liste[$i];
      }
    }
    else {
      # der nächste Wert ist mehr als eins größer, Intervallende
      # wenn das Interval bis hierhin nur 1 war wird das Ende nicht geschrieben
      # sonst wird das Intervalende geschrieben.
      if ($intervalwidth>1) {
        $comp.='-'.$liste[$i-1];
      }
      # neues Intervall beginnen.
      $comp.=';'.$liste[$i];
      $intervalwidth=1;
    }
  }
  return $comp;
}

#**** Funktion, die prüft, ob Datum sinnvoll ist.

function date_ok($date) {

   $today = date(Ymd);

   $ty = substr($today, 0,4);
   $tm = substr($today, 4,2);
   $td = substr($today, 6,2);


   $yy = strtok($date,"-");

   $ok = True;



   /* Stringoperationen */

   // Datum muss richtig formatiert sein ("YYYY-MM-DD").

   $c = strlen($date);

   if($c != 10) $ok= False;


   if(!ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $date)) $ok =False;



   /* Numerische Operationen */

   //Datum sollte möglich sein

   if($ok == True)
   {
       $date_tst = $yy.$mm.'01';
       $date_tst = strtotime($date_tst);

       $max_day = date(t,$date_tst);

       settype($yy, "integer");
       settype($mm, "integer");
       settype($dd, "integer");
       settype($ty, "integer");
       settype($tm, "integer");
       settype($td, "integer");

       #if($yy>2037) $ok = False;  // Sonst ausserhalb des Gültigkeitsbereiches des Timestamps, siehe auch PHP-Manual "date()".


       if(($max_day<$dd)||(1>$dd)) $ok =False;;

       if(($mm>12)||($mm<1)) $ok =False;

   }


   // Datum darf nicht in Vergangenheit liegen

   if($ok==True)
   {
       if($yy==$ty)
       {
           if($mm<$tm) $ok =False;
           if(($mm==$tm)&&($dd<$td)) $ok =False;
       }

       if($yy<$ty) $ok =False;
   }


   return $ok;

}

function split_edbs_file($pfad,$filename) {
  # Funktion teilt eine EDBS Datei in einzelne Dateien
  # Jeder Auftrag wird in eine separate Datei geschrieben
  # Öffnen der EDBS Datei
  $fp=fopen($pfad.$filename,'r');
  $i=1;
  $fpteil=fopen($pfad.'EDBS_teil_'.$i.'.edbs','w');
  echo '<p>Öffne EDBS_teil_'.$i.'.edbs<br>';
  $zaehler=0;
  while (!feof($fp)) {
    $zaehler++;
    # Lesen der Zeile
    $line=fgets($fp);
    # Schreiben der Zeile in aktuell offene Teildatei
    fwrite($fpteil,$line);
    echo ' *';
    # Wenn die Zeile den String zur Beendigung des Auftrages enthält, Datei schließen und neue unter neuen namen Öffnen
    if (ereg('EDBS00240000AEND000000  0000',$line)>0 AND $zaehler>250000) {
      $zaehler=0;
      # Schließen der Datei
      fclose ($fpteil);
      $i++;
      $fpteil=fopen($pfad.'EDBS_teil_'.$i.'.edbs','w');
      echo '<p>Öffne EDBS_teil_'.$i.'.edbs<br>';
    }
  }
  fclose($fp);
}

############################################################################
# Prüft eine E-Mail adresse auf richtige schreibweise
############################################################################
function emailcheck($email) {
  $Meldung='';
  # enthält die Adresse ein Leerzeichen?
  if (strstr(trim($email)," ")) {
    $Meldung.='<br>E-Mail enthält Leerzeichen.';
  }

  # hat die Adresse ein @
  if (!strstr($email,"@")) {
    $Meldung.='<br>E-Mail enthält kein @.';
  }

  $postfix=strlen(strrchr($email,"."))-1;
  if (!($postfix > 1 AND $postfix < 5)) {
    #echo " postfix ist zu kurz oder zu lang";
    $Meldung.='<br>E-Mail ist zu kurz oder zu lang.';
  }
  return $Meldung;
}

function buildExpressionString($str) {
  $intervalle=explode(';',$str);
  $anzInt=count($intervalle);
  if ($intervalle[$anzInt-1]=='') { $anzInt--; }
  # Beginne mit der Erstellung des Ausdrucks
  $expr.='(';
  # man neheme das erste Intervall
  # Zerlege es in Anfang und Ende
  $grenzen=explode('-',$intervalle[0]);
  # Teste ob es überhaupt ein Ende gibt, oder nur einen einzelnen Wert
  if (count($grenzen)==1) {
    # Wenn ja, wird die erste einschränkung geschrieben.
    $expr.='[ID]='.$grenzen[0];
  }
  else {
    # Wenn es Anfang und Ende gibt, müssen zwei Bedingungen geschrieben werden
    $expr.='([ID]>'.$grenzen[0].' AND [ID]<'.$grenzen[1].')';
  }
  # weiter geht es mit den nächsten Intervallen
  for ($i=1;$i<$anzInt;$i++) {
    # wieder Zerlegen in Anfang und Ende
    $grenzen=explode('-',$intervalle[$i]);
    if (count($grenzen)==1) {
      # Es gibt nur einen Wert
      $expr.=' OR [ID]='.$grenzen[0];
    }
    else {
      # Es gibt Anfang und Ende im Intervall
      $expr.=' OR ([ID]>'.$grenzen[0].' AND [ID]<'.$grenzen[1].')';
    }
  }
  # Beenden des Ausdrucks
  $expr.=')';
  return $expr;
}

function getNumPagesPdf($filepath){
	exec('gs -q -dNODISPLAY -c "('.$filepath.') (r) file runpdfbegin pdfpagecount = quit"', $output);
	return $output[0];
}

function WKT2UKO($wkt){
	$uko = str_replace('MULTIPOLYGON(((', 'TYP UPO 2'.chr(10).'KOO ', $wkt);
	$uko = str_replace(')),((', chr(10).'FL+'.chr(10).'KOO ', $uko);
	$uko = str_replace('),(', chr(10).'FL-'.chr(10).'KOO ', $uko);
	$uko = str_replace(',', chr(10).'KOO ', $uko);
	$uko = str_replace(')))', '', $uko);
	return $uko;
}

function rectObj2WKTPolygon($rect) {
  $polygon="";
  if (is_object($rect)) {
    if ($rect->minx>0) {
      $polygon ='POLYGON(('.$rect->minx.' '.$rect->miny.','.$rect->maxx.' '.$rect->miny;
      $polygon.=','.$rect->maxx.' '.$rect->maxy.','.$rect->minx.' '.$rect->maxy;
      $polygon.=','.$rect->minx.' '.$rect->miny.'))';
    }
  }
  return $polygon;
}

function output_handler($img) {
   header('Content-type: image/png');
   header('Content-Length: ' . strlen($img));
   return $img;
}
function getArrayOfChars() {
	$characters = array();
	$characterNumbers = array();

	for ($i=48; $i<=57; $i++) {
	  $characterNumbers[]=$i; # Zahlen
	}

	for ($i=65; $i<=90; $i++) {
	  $characterNumbers[]=$i; # Großbuchstaben
	}
	for ($i=97; $i<=122; $i++) {
	  $characterNumbers[]=$i; # Kleinbuchstaben
	}

	array_push($characterNumbers,223,196,228,214,246,220,252); # Sonderzeichen

	foreach ($characterNumbers as $characterNumber) {
	  $characters[] = chr($characterNumber);
	}
	return $characters;
}

function url_get_contents($url, $username = NULL, $password = NULL, $useragent = NULL) {
	$hostname = parse_url($url, PHP_URL_HOST);
	try {
		$ctx['http']['timeout'] = 20;
		#$ctx['http']['header'] = 'Referer: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];		// erstmal wieder rausgenommen, da sonst Authorization nicht funktioniert
		if($useragent)$ctx['http']['header'] = 'User-Agent: '.$useragent;
		if($username)$ctx['http']['header'].= "Authorization: Basic ".base64_encode($username.':'.$password);
		$proxy = getenv('HTTP_PROXY');
		if($proxy != '' AND $hostname != 'localhost'){
			$ctx['http']['proxy'] = $proxy;
			$ctx['http']['request_fulluri'] = true;
			$ctx['ssl']['SNI_server_name'] = $hostname;
			$ctx['ssl']['SNI_enabled'] = true;
		}
		$context = stream_context_create($ctx);
		$response =  file_get_contents($url, false, $context);
		if ($response === false) {
			throw new Exception("Fehler beim Abfragen der URL mit file_get_contents(".$url.")");
		}
	}
	catch (Exception $e) {
		$response = curl_get_contents($url, $username, $password);
	}
	return $response;
}

function curl_get_contents($url, $username = NULL, $password = NULL) {
	$url_parts = explode('?',  $url);
	parse_str($url_parts[1], $get_array);
  $ch = curl_init($url_parts[0]);		# url
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if($username)curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
	curl_setopt($ch, CURLOPT_POST, true);
	#curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $get_array);
	$result = curl_exec($ch);
  if (curl_getinfo($ch, CURLINFO_HTTP_CODE)==404) {
		$result = "Fehler 404: File not found. Die Resource konnte mit der URL: ".$url." nicht auf dem Server gefunden werden!";
  }
  curl_close($ch);
  return $result;
}

function debug_write($msg, $debug = false) {
  #$fp = fopen(LOGPATH.'debug.htm','a+');
	#$log = getTimestamp().":\n";
	#$msg = "- ".$msg."\n";
	#fwrite($fp,$log.$msg);
	#fclose($fp);
}

function getTimestamp($format = 'd.m.Y H:i:s', $digits = 4) {
	$microtime = microtime(true);
	return date($format) . substr($microtime - floor($microtime),1 , $digits + 1);
}

function formatBytes($size, $precision = 2) {
  $base = log($size) / log(1024);
  $suffixes = array('', 'kB', 'MB', 'GB', 'TB');
  return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

function get_upload_error_message($code) {
  switch ($code) {
    case UPLOAD_ERR_INI_SIZE:
        $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
        break;
    case UPLOAD_ERR_FORM_SIZE:
        $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
        break;
    case UPLOAD_ERR_PARTIAL:
        $message = "The uploaded file was only partially uploaded";
        break;
    case UPLOAD_ERR_NO_FILE:
        $message = "No file was uploaded";
        break;
    case UPLOAD_ERR_NO_TMP_DIR:
        $message = "Missing a temporary folder";
        break;
    case UPLOAD_ERR_CANT_WRITE:
        $message = "Failed to write file to disk";
        break;
    case UPLOAD_ERR_EXTENSION:
        $message = "File upload stopped by extension";
        break;

    default:
        $message = "Unknown upload error";
        break;
	}
  return $message;
}

/*
* This function removes or keeps elements defined in $strip_list from $fromvars array
* If elements from strip_list shall be removed (default), all other will be keeped in the formvars array.
* If elements shall be keeped, all other will be removed in the formars array.
* strip_type 'remove' delete formvars defined in strip_list.
* strip_type ('keep' | any other than 'remove') keep formvars defined in strip_list.
*
* @param $formvars array
* @param $strip_list comma separated string or array - formvar names to keep or to remove
* @param $strip_type string - Tells the function to keep or remove the formvars
*/
function formvars_strip($formvars, $strip_list, $strip_type = 'remove') {
	#echo '<br>formvars vorher: ' . print_r($formvars, true);
	#echo '<br>strip_list: ' . print_r($strip_list, true);
	#echo '<br>strip_type: ' . $strip_type;

	$strip_array = (is_array($strip_list) ? $strip_list : explode(', ', $strip_list));
	$stripped_formvars = array();

	foreach($formvars AS $key => $value) {

		if ($strip_type == 'remove') {
			# strip key if in strip_list
			$strip = in_array($key, $strip_array);
		}
		else {
			# do not strip key if in strip_list
			$strip = !in_array($key, $strip_array);
		}

		if (!$strip) {
			#echo "<br>Keep {$key} in formvars.";
			$pos = strpos($value, '[');
			if ($pos !== false AND $pos == 0) {
				$stripped_formvars[$key] = arrStrToArr(stripslashes($value), ',');
			}
			else {
				$stripped_formvars[$key] = stripslashes($value);
			}
		}
		else {
			#echo "<br>Strip {$key} from formvars.";
		}

	}
	#echo '<br>formvars nachher: ' . print_r($stripped_formvars, true);
	return $stripped_formvars;
}

/*
* Funktion ersetzt in $str die Schlüsselwörter, die in $params
* als key übergeben werden durch die values von $params und zusätzlich die Werte der
* Variablen aus den Parametern 3 bis n wenn welche übergeben wurden
*/
function replace_params($str, $params, $user_id = NULL, $stelle_id = NULL, $hist_timestamp = NULL, $language = NULL, $duplicate_criterion = NULL, $scale = NULL) {
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, $value, $str);
		}
	}
	$str = str_replace('$current_date', date('Y-m-d'), $str);
	$str = str_replace('$current_timestamp', date('Y-m-d G:i:s'), $str);
	if (!is_null($user_id))							$str = str_replace('$user_id', $user_id, $str);
	if (!is_null($stelle_id))						$str = str_replace('$stelle_id', $stelle_id, $str);
	if (!is_null($hist_timestamp))			$str = str_replace('$hist_timestamp', $hist_timestamp, $str);
	if (!is_null($language))						$str = str_replace('$language', $language, $str);
	if (!is_null($duplicate_criterion))	$str = str_replace('$duplicate_criterion', $duplicate_criterion, $str);
	if (!is_null($scale))								$str = str_replace('$scale', $scale, $str);
	return $str;
}

/**
* Funktion sendet e-mail mit Dateien im Anhang
* siehe http://www.php-einfach.de/codeschnipsel_1114.php
* @param $anhang Array mit den Elementen "name", "size" und "data" oder Array mit Elementen solcher Arrays
* $pfad = array();
* $pfad[] = "ordner/datei1.exe";
* $pfad[] = "ordner/datei2.zip";
* $pfad[] = "ordner/datei3.gif";
*
* $anhang = array();
* foreach($pfad AS $name) {
*   $name = basename($name);
*   $size = filesize($name);
*   $data = implode("",file($name));
*   if (function_exists("mime_content_type"))
*     $type = mime_content_type($name);
*   else
*     $type = "application/octet-stream";
*     $anhang[] = array("name"=>$name, "size"=>$size, "type"=>$type, "data"=>$data);
* }
* mail_att("empf@domain","Email mit Anhang","Im Anhang sind mehrere Datei",$anhang);
**/
function mail_att($from_name, $from_email, $to_email, $cc_email, $reply_email, $subject, $message, $attachement, $mode, $smtp_server, $smtp_port) {
	$success = false;
	switch ($mode) {
		case 'sendEmail async': {
			# Erstelle Befehl für sendEmail und schreibe in mail queue Verzeichnis.
			$str = array('to_email' => $to_email, 'from_email' => $from_email, 'from_name' => $from_name, 'cc_email' => $cc_email, 'subject' => $subject, 'message' => $message, 'attachment' => $attachement);
			if(!is_dir(MAILQUEUEPATH)){
				mkdir(MAILQUEUEPATH);
				chmod(MAILQUEUEPATH, 'g+w');
			}
			$file = MAILQUEUEPATH . 'email' . date('YmdHis', time()) . '_' . uniqid('', false) . '.txt';
			$success = file_put_contents(
				$file,
				json_encode($str)
			);
		} break;
		default : {
			$grenze = "---" . md5(uniqid(mt_rand(), 1)) . "---";

			$headers ="MIME-Version: 1.0\r\n";
			$headers .= 'From: ' . $from_email . "\r\n";
			$headers .= 'Reply-To: ' . $reply_email . "\r\n";
			if (!empty($cc_email)) $headers .= 'Cc: ' . $cc_email . "\r\n";
			$headers .= "Content-Type: multipart/mixed;\n\tboundary=$grenze\r\n";

			$botschaft = "\n--$grenze\n";
			$botschaft.="Content-transfer-encoding: 7BIT\r\n";
			$botschaft.="Content-type: text/plain; charset=UTF-8\n\n";
			$botschaft.= $message;

			if ($attachement) {
				$botschaft.="\n\n";
				$botschaft.="\n--$grenze\n";

				$botschaft.="Content-Type: application/octetstream;\n\tname=" . basename($attachement) . "\n";
				$botschaft.="Content-Transfer-Encoding: base64\n";
				$botschaft.="Content-Disposition: attachment;\n\tfilename=" . basename($attachement) . "\n\n";

				$zeiger_auf_datei=fopen($attachement,"rb");
				$inhalt_der_datei=fread($zeiger_auf_datei,filesize($attachement));
				fclose($zeiger_auf_datei);

				$inhalt_der_datei=chunk_split(base64_encode($inhalt_der_datei));
				$botschaft.=$inhalt_der_datei;
				$botschaft.="\n\n";
				$botschaft.="--$grenze";
			}
			#  echo 'to_email: '.$to_email.'<br>';
			#  echo 'subject: '.$subject.'<br>';
			#  echo 'botschaft: '.$botschaft.'<br>';
			#  echo 'headers: '.$headers.'<br>';
			$success = @mail($to_email, $subject, $botschaft, $headers);
		}
	}
	if ($success)
		return 1;
	else
		return 0;
}

/*
* function replaced square brackets at the beginning and the end of the string
* and return the elements of the string as array separated by the delimmiter.
* The elements of the string will be replaced by slashes and timed from white spaces and ".
*/
function arrStrToArr($str, $delimiter) {
#	if(is_string($delimiter) and in_array())
#	echo gettype($delimiter);
	$arr = explode($delimiter, trim($str, '[]'));
	foreach ($arr as &$value) {
		$value = trim(stripslashes($value), '"[]"');
	}
	return $arr;
}

/**
* @param string $form_field_name - used also als form field id
* @param array $data - array with values and options per array element
* @param string $selected_value
* @param string $onchange - javascript to execute on change
* @param string $title - of the select field
* @param string $null_option - create a first option with value = '' and the text of $null_option
* @param string $style - css Style for the select element
* @return string - the html representing the select form element
* */
function output_select($form_field_name, $data, $selected_value = null, $onchange = null, $title = null, $null_option = null, $style = null) {
	if (!empty($onchange)) {
		$onchange = " onchange=\"{$onchange}\"";
	}
	if (!empty($style)) {
		$style = " style=\"{$style}\"";
	}
	$html = "<select id=\"{$form_field_name}\" name=\"{$form_field_name}\"{$onchange}{$style}>\n";
	if (!empty($null_option)) {
		$html .= "\t<option value=\"\">{$null_option}</option>\n";
	}
	foreach ($data AS $option) {
		$selected = ($option['value'] == $selected_value ? ' selected' : '');
		$html .= "\t<option value=\"{$option['value']}\"{$selected}>{$option['output']}</option>\n";
	}
	$html .= "</select>\n";
	return $html;
}

/*
* Die Funktion liefert das erste Word, welches nach $word in $str gefunden wird.
* Über die optionalen Parameter $delim1 und $delim2 kann man die Trennzeichen vor und nach dem Wort angeben.
* Wenn der optionale Parameter $last true ist, wird das letzte Vorkommen des Wortes verwendet.
*/
function get_first_word_after($str, $word, $delim1 = ' ', $delim2 = ' ', $last = false){
	if ($last) {
		$word_pos = strripos($str, $word);
	}
	else {
		$word_pos = stripos($str, $word);
	}
	if ($word_pos !== false) {
		$str_from_word_pos = substr($str, $word_pos + strlen($word));
		$parts = explode($delim2, trim($str_from_word_pos, $delim1));
		return trim($parts[0]);
	}
}

function geometrytype_to_datatype($geometrytype) {
	if (stripos($geometrytype, 'POINT') !== false) {
		$datatype = 0;
	}
	elseif (stripos($geometrytype, 'LINESTRING') !== false) {
		$datatype = 1;
	}
	elseif( stripos($geometrytype, 'POLYGON') !== false) {
		$datatype = 2;
	}
	return $datatype;
}

/*
* Function erzeugt von den übergebenen formvars hidden input fields
* außer von denen, dessen keys im except array stehen.
*/
function hidden_formvars_fields($formvars, $except = array()) {
	$html = '';
	$params = array();
	foreach ($formvars AS $key => $value) {
		if (!in_array($key, $except)) {
			if (is_array($value)) {
				$params = array_merge($params, values_from_array($key, $value));
			}
			else {
				$params[$key] = $value;
			}
		}
	}
	foreach($params AS $key => $value) {
		$html .= '<input type="hidden" name="' . $key . '" value="' . $value .'">';
	}
	return $html;
}

/*
* Function liefert eine Liste von Parametern von einem verschachtelten array
* z.B. wir aus:
* Array('a' => 1, 'b' => Array(0 => 2, 1 => Array('c' => 3, 'd' => 4)))
* Array('a' => 1, 'b[0]' => 2, 'b[1][c]' => 3, 'b[1][d]' => 4)
* Diese Funktion kann verwendet werden, um formvars in Parameter zu wandeln,
* die in hidden input Feldern ausgegeben werden soll.
* @param $array_key String Name des Layer_Parameter_speichern
* @params $array Array Das Array mit den default-Werten
* @return Die Liste der Parameter
*/
function values_from_array($array_key, $array) {
	$params = array();
	foreach ($array AS $key => $value) {
		if (is_array($value)) {
			$params = array_merge($params, values_from_array($array_key . '[' . $key . ']', $value));
		}
		else {
			$params[$array_key . '[' . $key . ']'] = $value;
		}
	}
	return $params;
}

function uuid() {
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),
		mt_rand(0, 0xffff),
		mt_rand(0, 0x0fff) | 0x4000,
		mt_rand(0, 0x3fff) | 0x8000,
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
	);
}

/**
*  Get the file size of any remote resource (using get_headers()), 
*  either in bytes or - default - as human-readable formatted string.
*
*  @author  Stephan Schmitz <eyecatchup@gmail.com>
*  @license MIT <http://eyecatchup.mit-license.org/>
*  @url     <https://gist.github.com/eyecatchup/f26300ffd7e50a92bc4d>
*
*  @param   string   $url          Takes the remote object's URL.
*  @param   boolean  $formatSize   Whether to return size in bytes or formatted.
*  @param   boolean  $useHead      Whether to use HEAD requests. If false, uses GET.
*  @return  string                 Returns human-readable formatted size
*                                  or size in bytes (default: formatted).
*/
function get_remote_filesize($url, $formatSize = true, $useHead = true) {
	if (false !== $useHead) {
		stream_context_set_default(array('http' => array('method' => 'HEAD')));
	}
	$head = array_change_key_case(get_headers($url, 1));
	// content-length of download (in bytes), read from Content-Length: field
	$clen = isset($head['content-length']) ? $head['content-length'] : 0;

	// cannot retrieve file size, return "-1"
	if (!$clen) {
		return -1;
	}

	if (!$formatSize) {
		return $clen; // return size in bytes
	}

	$size = $clen;
	switch ($clen) {
		case $clen < 1024:
		$size = $clen .' B'; break;
		case $clen < 1048576:
		$size = round($clen / 1024, 2) .' KiB'; break;
		case $clen < 1073741824:
		$size = round($clen / 1048576, 2) . ' MiB'; break;
		case $clen < 1099511627776:
		$size = round($clen / 1073741824, 2) . ' GiB'; break;
	}

	return $size; // return formatted size
}

/*
* Function returns a readable message of sql errors optionally with word $find replaced by asterists *****
*/
function err_msg($file, $line, $msg, $find = '') {
	return "<br>Abbruch in " . $file . " Zeile: " . $line . "<br>wegen: " . ($find != '' ? str_replace($find, '*****', $msg) : $msg). "<p>" . INFO1;
}

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

function send_image_not_found($img) {
	$empty_img = imagecreate(600, 45);
	$background = imagecolorallocate($empty_img, 255, 139, 129);
	$text_colour = imagecolorallocate($empty_img, 0, 0, 0);
	$line_colour = imagecolorallocate($empty_img, 255, 255, 0);
	imagestring($empty_img, 4, 30, 15, "Bild " . $img . " nicht gefunden!", $text_colour);
	imagestring($empty_img, 4, 90, 55, ";-(", $text_colour);
	imagesetthickness ( $empty_img, 1);
	imageline($empty_img, 2, 2, 2, 42, $line_colour);
	imageline($empty_img, 2, 2, 597, 2, $line_colour);
	imageline($empty_img, 2, 42, 597, 42, $line_colour);
	imageline($empty_img, 597, 2, 597, 42, $line_colour);
	header("Content-type: image/png");
	imagepng($empty_img);
	imagecolordeallocate($line_color);
	imagecolordeallocate($text_color);
	imagecolordeallocate($background);
	imagedestroy($empty_img);
}

function value_of($array, $key) {
	if(!is_array($array))$array = array();
	return (array_key_exists($key, $array) ? $array[$key] :	'');
}

function is_true($val) {
	if (
		$val === true OR
		$val === 1 OR
		$val === 't' OR
		strtolower($val) == 'true'
	) {
		return true;
	}
	else {
		return false;
	}
}

function count_or_0($val) {
	if (is_null($val) OR !is_array($val)) {
		return 0;
	}
	else {
		return count($val);
	}
}

/**
* Replacing part of a string with another string is straight forward, but what if you have to replace last occurrence of a character or string with another string.
* https://pageconfig.com/post/replace-last-occurrence-of-a-string-php
* In case the $search is not found inside the $str, the function returns the original untouched string $str.
* This behavior is compatible with the default behavior of str_replace PHP’s builtin function that replaces all
* occurrances of a string inside another string.
* @param string $search keeps the string to be searched for
* @param string $replace Is the replacement string
* @param string $str Is the subject string, commonly known as haystack
*/
function str_replace_last($search , $replace, $str) {
  if (($pos = strrpos($str, $search)) !== false) {
    $search_length  = strlen( $search );
    $str    = substr_replace( $str , $replace , $pos , $search_length );
  }
  return $str;
}

/*
* Liefert den Originalnamen vom Namen der Thumb-Datei
*/
function get_name_from_thump($thumb) {
	return before_last($thumb, '_thumb.jpg');
}

/*
* Funktion liefert Teilstring von $txt vor dem letzten vorkommen von $delimiter
* Kann z.B. verwendet werden zum extrahieren der Originaldatei vom Namen eines Thumpnails
* z.B. before_last('MeineDatei_abc_1.Ordnung-345863_thump.jpg', '_') => MeineDatei_abc_1.Ordnung-345863
* @param string $txt Der Text von dem der Teilstring extrahiert werden soll.
* @param string $delimiter Der Text, der den Text trennt in davor und danach.
* @return string Der Teilstring vor dem letzten Vorkommen von $delimiter
* oder ein Leerstring wenn $txt $delimiter nicht enthält oder $delimiter leer ist
*/
function before_last($txt, $delimiter) {
	#echo '<br>Return the part of ' . $txt . ' before the last occurence of ' . $delimiter;
	if (!$delimiter) {
		return '';
	}
	$parts = explode($delimiter, $txt);
	array_pop($parts);
	return implode($delimiter , $parts);
}

function attributes_from_select($sql) {
	include_once(WWWROOT. APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLParser.php');
	$parser = new PHPSQLParser($sql, true);
	$attributes = array();
	foreach ($parser->parsed['SELECT'] AS $key => $value) {
		$name = $alias = '';
		if (
			is_array($value['alias']) AND
			array_key_exists('no_quotes', $value['alias']) AND
			$value['alias']['no_quotes'] != ''
		) {
			$name = $value['alias']['no_quotes'];
			$alias = $value['alias']['no_quotes'];
		}
		else {
			$name = $alias = $value['base_expr'];
		}
		$attributes[$name] = array(
			'base_expr' => $value['base_expr'],
			'alias' => $alias
		);
	}
	return $attributes;
}

function get_requires_options($sql, $requires) {
	include_once(WWWROOT . APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLParser.php');
	include_once(WWWROOT . APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLCreator.php');
	# Entfernt requires Tag damit kein Syntax-Fehler im sql ist.
	$sql = str_replace(
		'<requires>',
		'',
		str_replace(
			'</requires>',
			'',
			$sql
		)
	);
	$parser = new PHPSQLParser($sql, true);
	# Füge das Requires Attribut zum Select hinzu
	array_unshift(
		$parser->parsed['SELECT'],
		array(
			'expr_type' => 'colref',
			'alias' => array(
				'as' => 1,
				'name' => 'requires',
				'base_expr' => 'AS requires',
				'no_quotes' => 'requires',
			),
			'base_expr' => $requires,
			'no_quotes' => $requires,
			'delim' => ', '
		)
	);
	# Entferne die WHERE Klausel
	unset($parser->parsed['WHERE']);
	$creator = new PHPSQLCreator($parser->parsed);
	return $creator->created;
}
?>
