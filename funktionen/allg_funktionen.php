<?php
/**
 * hier befindet sich ein lose Sammlung von Funktionen, die so oder ähnlich im php
 * Funktionenumfang nicht existieren, in älteren Versionen nicht existiert haben,
 * nicht gefunden wurden, nicht verstanden wurden oder zu umfrangreich waren.
 */
if (MAPSERVERVERSION < 800) {
	if (!function_exists('msGetErrorObj')) {
		function msGetErrorObj() {
			return ms_GetErrorObj();
		}
	}

	if (!function_exists('msResetErrorList')) {
		function msResetErrorList(){
			return ms_ResetErrorList();
		}
	}
}

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

/**
 * Funktion wandelt die gegebene MapServer-Expression in einen SQL-Ausdruck um
 * der in WHERE-Klauseln für die Klassifizierung von Datensätzen verwendet werden kann
 * @param string $exp Die MapServer-Expression
 * @param string $classitem Optional Das Classitem, welches in der MapServer-Expression verwendet wird.
 * @return String Die aus der MapServer-Expression erzeugte SQL-Expression
 */
function mapserverExp2SQL($exp, $classitem) {
	$exp = str_replace(array("'[", "]'", '[', ']'), '', $exp);
	$exp = str_replace(' eq ', ' = ', $exp);
	$exp = str_replace(' ne ', ' != ', $exp);
	$exp = str_replace(' ge ', ' >= ', $exp);
	$exp = str_replace(' le ', ' <= ', $exp);
	$exp = str_replace(' gt ', ' > ', $exp);
	$exp = str_replace(' lt ', ' < ', $exp);
	$exp = str_replace(" = ''", ' IS NULL', $exp);
	$exp = str_replace('\b', '\y', $exp);
	if (strpos($exp, ' IN ') != false) {
		$array = get_first_word_after($exp, ' IN');
		$exp = str_replace(' IN ', ' = ANY(', $exp);
		$exp = str_replace($array, $array . ')', $exp);
	}

	if ($exp != '' AND substr($exp, 0, 1) != '(' AND $classitem != '') { # Classitem davor setzen
		if (strpos($exp, '/') === 0) { # regex
			$operator = '~';
			$exp = str_replace('\/', 'escaped_slash', $exp);
			$exp = str_replace('/', '', $exp);
			$exp = str_replace('escaped_slash', '/', $exp);
		}
		else {
			$operator = '=';
		}
		if (substr($exp, 0, 1) != "'") {
			$quote = "'";
		}
		$exp = '"' . $classitem . '"::text ' . $operator . ' ' . $quote . $exp . $quote;
	}
	return $exp;
}

function add_csrf($url){
	if (strpos($url, 'javascript:') === false AND strpos($url, 'go=') !== false) {
		$url = (strpos($url, '#') === false ? $url . '&csrf_token=' . $_SESSION['csrf_token'] : str_replace('#', '&csrf_token=' . $_SESSION['csrf_token'] . '#', $url));
	}
	return $url;
}

function urlencode2($str){
	$str = rawurlencode($str);
	$str = str_replace('%3F', '?', $str);
	$str = str_replace('%26', '&', $str);
	$str = str_replace('%3D', '=', $str);
	$str = str_replace('%3A', ':', $str);
	$str = str_replace('%2F', '/', $str);
	$str = str_replace('%23', '#', $str);
	$str = str_replace('%25', '%', $str);
	return $str;
}

/**
 * die Konstante URL kann durch diese Funktion ersetzt werden
 */
function get_url() {
	return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
}

/**
 * Function enclose $var with single quotes when $type is text or varchar
 * and elsewhere if $var has a numerical value
 * @param any $var The value that has to be enclosed with quotas or not
 * @param string $type optional type of var, default empty string
 * @return any $var as string with enclosed quotes or as it is if not.
 */
function quote($var, $type = '') {
	switch ($type) {
		case 'text' : case 'varchar' : {
			return "'" . $var . "'";
		} break;
		default : {
			return is_numeric($var) ? $var : "'" . $var . "'";
		}
	}
}

function quote_or_null($var) {
	return (($var === '' OR $var === null) ? 'NULL' : quote($var));
}

function value_or_null($var) {
	return (($var === '' OR $var === null) ? 'NULL' : $var);
}

function append_slash($var) {
	return $var . ((trim($var) != '' AND substr(trim($var), -1) != '/') ? '/' : '');
}

function pg_quote($column) {
	return (is_valid_pg_name($column) OR strpos($column, "'") === 0) ? $column : '"' . $column . '"';
}

function is_valid_pg_name($name) {
	return preg_match('/^[a-z_][a-z0-9_]*$/', $name);
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

function format_human_filesize($bytes, $precision = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%." . $precision. "f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor] . 'B';
}

function human_filesize($file) {
	$bytes = @filesize($file);
	return format_human_filesize($bytes);
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

/**
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

function url2filepath($url, $doc_path, $doc_url) {
	if ($doc_path == '') {
		$doc_path = CUSTOM_IMAGE_PATH;
	}
	$url_parts = explode($doc_url, $url);
	return $doc_path . $url_parts[1];
}

/**
 * Function return information about path to file named in $document_attribute_value.
 * @param String $document_attribute_value The value of document attribute in the form /var/www/data/upload/test_125487.txt&original_name=test.txt
 * @return Array  The result array contains dirname, basename, extension, filename and the original_basename, original_filename and original_extension.
 */
function document_info($document_attribute_value, $flag = NULL) {
	$value_parts = explode('&original_name=', $document_attribute_value);
	if ($flag === null) {
		$document_info = pathinfo($value_parts[0]);
		if (count($value_parts) > 1) {
			$original_info = pathinfo($value_parts[1]);
			$document_info['original_basename'] = $original_info['basename'];
			$document_info['original_filename'] = $original_info['filename'];
			$document_info['original_extension'] = $original_info['extension'];
		}
		else {
			$document_info['original_basename'] = '';
			$document_info['original_filename'] = '';
			$document_info['original_extension'] = '';
		}
		return $document_info;
	}
	if (strpos($flag, 'original_') === 0) {
		$path = (count($value_parts) > 1 ? $value_parts[1] : '');
		$flag = str_replace('original_', '', $flag);
	}
	else {
		$path = $value_parts[0];
	}

	switch ($flag) {
		case 'path' : $document_info = $path; break;
		case 'dirname' : $document_info =   pathinfo($path, PATHINFO_DIRNAME); break;
		case 'basename' : $document_info =  pathinfo($path, PATHINFO_BASENAME); break;
		case 'filename' : $document_info =  pathinfo($path, PATHINFO_FILENAME); break;
		case 'extension' : $document_info = pathinfo($path, PATHINFO_EXTENSION); break;
		default : $document_info = $path;
	}
	return $document_info;
}

function exif_identify_data($file) {
	$lines = '';
	$result = '';
	$exif = array();
	exec("identify -verbose " . $file . " | grep -E 'exif:GPSLatitude:|exif:GPSLongitude:|exif:GPSImgDirection|DateTimeOriginal'", $lines, $result);
	foreach ($lines AS $line) {
		$line_parts = explode(': ', trim($line));
		$key = $line_parts[0];
		$values = $line_parts[1];
		switch ($key) {
			case 'exif:GPSLatitude' : $exif['GPSLatitude'] = explode(', ', $values); break;
			case 'exif:GPSLongitude' : $exif['GPSLongitude'] = explode(', ', $values); break;
			case 'exif:GPSImgDirection' : $exif['GPSImgDirection'] = $values; break;
			case 'exif:DateTimeOriginal' : $exif['DateTimeOriginal'] = $values; break;
		}
	}
	return $exif;
}

/**
*	function read exif and gps data from file given in $img_path and return GPS-Position, Direction and creation Time
*	It uses php to read exif data per default. If coordinates are not found it try to read the values with identify command
*
*	@param string $img_path Absolute Path of file with Exif Data to read
*
*	@param boolean $force_identify Forces to use only function identify to read exif data from image, default is false
*
*	@return array Array with success true if read was successful, LatLng the GPS-Position where the foto was taken Richtung and Erstellungszeit.
*/
function get_exif_data($img_path, $force_identify = false) {
	if ($img_path != '') {
		if ($force_identify) {
			$exif = exif_identify_data($img_path);
		}
		else {
			$exif = @exif_read_data($img_path, 'EXIF, GPS');
			if ((is_array($exif) && !array_key_exists('GPSLatitude', $exif)) OR (is_array($exif) && !array_key_exists('GPSLongitude', $exif))) {
				$exif = exif_identify_data($img_path);
			}
		}
		if ($exif === false) {
			return array(
				'success' => false,
				'err_msg' => 'Keine Exif-Daten im Header der Bilddatei ' . $img_path . ' gefunden!'
			);
		}
		else {
			return array(
				'success' => true,
				'LatLng' => ((array_key_exists('GPSLatitude', $exif) AND array_key_exists('GPSLongitude', $exif)) ? (
					floatval(substr($exif['GPSLatitude' ][0], 0, strlen($exif['GPSLatitude' ][0]) - 2))
					+ float_from_slash_text($exif['GPSLatitude' ][1]) / 60
					+ float_from_slash_text($exif['GPSLatitude' ][2]) / 3600
				) . ' ' . (
					floatval(substr($exif['GPSLongitude'][0], 0, strlen($exif['GPSLongitude'][0]) - 2))
					+ float_from_slash_text($exif['GPSLongitude'][1]) / 60
					+ float_from_slash_text($exif['GPSLongitude'][2]) / 3600
				) : NULL),
				'Richtung' => (array_key_exists('GPSImgDirection', $exif) ? float_from_slash_text($exif['GPSImgDirection']) : NULL),
				'Erstellungszeit' => ((array_key_exists('DateTimeOriginal', $exif) AND substr($exif['DateTimeOriginal'], 0 , 4) != '0000') ? (
						substr($exif['DateTimeOriginal'], 0 , 4) . '-'
					. substr($exif['DateTimeOriginal'], 5, 2) . '-'
					. substr($exif['DateTimeOriginal'], 8, 2) . ' '
					. substr($exif['DateTimeOriginal'], 11)
				) : NULL)
			);
		}
	}
}

/**
 * Function write exif data to file given in $img_path
 * It uses php function iptcembed to write the exif data
 * @param string $img_path Absolute Path of file with Exif Data to write
 * @param array $iptc Array with iptc tags to write. See https://exiftool.org/TagNames/IPTC.html#ApplicationRecord for tag names and meanings
 * @return void
 */
function set_exif_data($img_path, $iptc = array()) {
	if (count($iptc) == 0) {
		return;
	}
	$data = '';
	foreach ($iptc as $tag => $string) {
		$tag = substr($tag, 2);
		$data .= iptc_make_tag(2, $tag, $string);
	}
	$content = iptcembed($data, $img_path);
	$fp = fopen($img_path, "wb");
	fwrite($fp, $content);
	fclose($fp);
}

/**
 * Create an IPTC tag
 * originaly created by Thies C. Arntzen
 */
function iptc_make_tag($rec, $data, $value) {
	// echo 'iptc_make_tag: ' . $rec . ', ' . $data . ', ' . $value . '<br>';
	$length = strlen($value);
	$retval = chr(0x1C) . chr($rec) . chr($data);

	if ($length < 0x8000) {
		$retval .= chr($length >> 8) .  chr($length & 0xFF);
	}
	else {
		$retval .= chr(0x80) . 
							 chr(0x04) . 
							 chr(($length >> 24) & 0xFF) . 
							 chr(($length >> 16) & 0xFF) . 
							 chr(($length >> 8) & 0xFF) . 
							 chr($length & 0xFF);
	}
	return $retval . $value;
}

/**
* Function create a float value from a text
* where numerator and denominator are delimited by a slash e.g. 23/100
*
* @param string $slash_text First part of the string is numerator, second part is denominator.
*
* @return float The float value calculated from numerator divided by denominator.
* 				Return Null if string is empty or NULL.
*				Return numerator if only one part exists after explode by slash
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
	$a['Name_or_alias'] = strtoupper($a['Name_or_alias']);
	$b['Name_or_alias'] = strtoupper($b['Name_or_alias']);
	$a['Name_or_alias'] = str_replace('Ä', 'A', $a['Name_or_alias']);
	$a['Name_or_alias'] = str_replace('Ü', 'U', $a['Name_or_alias']);
	$a['Name_or_alias'] = str_replace('Ö', 'O', $a['Name_or_alias']);
	$a['Name_or_alias'] = str_replace('ß', 's', $a['Name_or_alias']);
	$b['Name_or_alias'] = str_replace('Ä', 'A', $b['Name_or_alias']);
	$b['Name_or_alias'] = str_replace('Ü', 'U', $b['Name_or_alias']);
	$b['Name_or_alias'] = str_replace('Ö', 'O', $b['Name_or_alias']);
	$b['Name_or_alias'] = str_replace('ß', 's', $b['Name_or_alias']);
	return strcmp($a['Name_or_alias'], $b['Name_or_alias']);
}

function compare_names($a, $b){
	return strcmp($a['name'], $b['name']);
}

function compare_orders($a, $b){
	if($a->order > $b->order)return 1;
  else return 0;
}

function compare_orders2($a, $b){
	if($a['order'] > $b['order'])return 1;
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

function pg_escape_string_or_array($data){
	if (is_array($data)) {
		array_walk($data, function(&$value, $key) {
			$value = pg_escape_string($value);
		});
	}
	else {
		$data = pg_escape_string($data);
	}
	return $data;
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
		return 39.3701 * degree2meter($center_y);
	}
}

function degree2meter($center_y) {
	if($center_y != 0.0){
		$cos_lat = cos(pi() * $center_y/180.0);
		$lat_adj = sqrt(1 + $cos_lat * $cos_lat)/sqrt(2);
	}
	return 111319 * $lat_adj;
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

if (!function_exists('mb_strrpos')) {		# Workaround, falls es die Funktion nicht gibt
	function mb_strrpos($str, $search, $offset = 0, $encoding = 'UTF-8') {
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
		$number = str_replace(',', '.', $number);
		$explo = explode('.', $number);
		$formated_number = number_format((float)$explo[0], 0, ',', '.');
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

function buildsvgpolygonfromwkt($wkt){
	if ($wkt != '') {
		$type = substr($wkt, 0, 9);
		if ($type == 'MULTIPOLY') {
			$start = 15;
			$end = strlen($wkt) - 18;
			$delim = ')),((';
		}
		else{			// POLYGON
			$start = 9;
			$end = strlen($wkt) - 11;
			$delim = '),(';
		}
		$wkt = substr($wkt, $start, $end);
		$parts = explode($delim, $wkt);
		for ($j = 0; $j < count($parts); $j++) {
			$parts[$j] = str_replace(',', ' ', $parts[$j]);
		}
		$svg = "M " . implode(' M ', $parts);
		return $svg;
	}
	else{
		return '';
	}
}

function transformCoordsSVG($path){
	$path = str_replace([',', 'cx=', 'cy=', '"', ','], [' ', ''], $path);		# bei MULTIPOINTs mit drin
	$path = str_replace('L ', '', $path);		# neuere Postgis-Versionen haben ein L mit drin
	if (strpos($path, 'M') === false) {
		$path = 'M ' . $path;
	}
  $svgcoords = explode(' ',$path);
	$newsvgcoords = [];
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
  for($i = 1; $i < count_or_0($newsvgcoords); $i++){
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

/**
 * convert decimal degree value to degree and decimal minutes
 */
function dec2dmin($number){
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
  $point = new PointObj();
	$point->setXY($x,$y);
	$projFROM = new projectionObj("init=epsg:".$from_epsg);
  $projTO = new projectionObj("init=epsg:".$to_epsg);
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
* Code wurde abgeleitet von [http://scripts.franciscocharrua.com/check-password.php](http://scripts.franciscocharrua.com/check-password.php) und
* [http://www.vbforums.com/showthread.php?p=2347960](http://www.vbforums.com/showthread.php?p=2347960) und wurde stark verändert und ergänzt.
* Vielen Dank trotzdem an die Autoren.
*
* Reihenfolge: Übersichtssatz - Kommentar - Tags.
*
* @param string password Zu prüfendes Password als Text
*
* @return string Fehlermeldung zur Beschreibung, was an dem Password schlecht ist, oder leerer String, wenn Password gut ist.
*
* @see    createRandomPassword(), checkPasswordAge, $GUI, $user, $stelle
*/
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

/**
* Erzeugt an Hand der Einstellungen für die Passwortstärke einen Hilfetext für die
* Vergabe eines neuen Passwortes
*/
function password_erstellungs_hinweis($language) {
	include_once(LAYOUTPATH . 'languages/allg_funktionen_' . $language . '.php');
	$condition = array();
	$msg = '';
	if (substr(PASSWORD_CHECK, 0, 1) == '0') {
		$msg = $strPasswordCheck0;
	}
	else {
		if (substr(PASSWORD_CHECK, 1, 1) == '1') {
			$conditions[] = $strLCLetters;
		}
		if (substr(PASSWORD_CHECK, 2, 1) == '1') {
			$conditions[] = $strUCLetters;
		}
		if (substr(PASSWORD_CHECK, 3, 1) == '1') {
			$conditions[] = $strNumbers;
		}
		if (substr(PASSWORD_CHECK, 4, 1) == '1') {
			$conditions[] = $strSpecialCharacters;
		}

		$msg = $strMinimum . ' ' . implode(', ', $conditions) . '.';
	}
	return $msg;
}

/**
* Erzeugen eines zufälligen Passwortes
*
* Diese Funktion erzeugt ein zufälliges sicheres Password. Die Funktion wurde von Totally PHP übernommen und mit zusätzlichen Zeichen versehen
* siehe: [http://www.totallyphp.co.uk/code/create_a_random_password.htm](http://www.totallyphp.co.uk/code/create_a_random_password.htm)
* Vielen Dank an den Autor.
*
* Reihenfolge: Übersichtssatz - Kommentar - Tags.
*	@param Integer $passwordLength Die Länge des zu erzeugenden Passworts.
* @param String $sonderzeichen Die Sonderzeichen, die im Passwort vorkommen dürfen. 
* @return string ein Password der Länge $passwordLength, mindestens 8, maximal 24 Zeichen.
*
* @see    isPasswordValide(), checkPasswordAge, $GUI, $user, $stelle
*/
function createRandomPassword($passwordLength, $sonderzeichen = "()_+*-.:,;!§$%&=#()_+*-.:") {
	if ($passwordLength < 8) {
		$passwordLength = 8;
	}
	if ($passwordLength > 24) {
	  $passwordLength = 24;
	}
  $chars[0] = "abcdefghijkmnopqrstuvwxyz";
  $chars[1] = "ABCDEFGHIJKMNOPQRSTUVWXYZ";
  $chars[2] = "0234567890234567890234567";
  $chars[3] = $sonderzeichen;
  $password = '';
  $charListNumbers = array();
  $charListNumber = rand(0,3);
  $loops = 0;
  while (strlen($password)<$passwordLength AND $loops++ < 100) {
  	while (count($charListNumbers)<4) {
  		if (!in_array($charListNumber,$charListNumbers)) { # wenn die charListNumber noch nicht in der Liste ist
  			$charListNumbers[] = $charListNumber; # charListNumber in die Liste aufnehmen
  			$char = substr($chars[$charListNumber], rand(0, 24), 1); # Character aus der Characterliste mit charListNumber entnehmen
  			#if ($char==' ') $char='_'; # darf nur auf keinen Fall ein Leerzeichen beinhalten
  			$password .= $char;
  			#echo '<br>'.strlen($password).' '.$password;
  		}
  		$charListNumber = rand(0,3);
  	}
  	$charListNumbers = array();
  }
  return $password;
}

function get_remote_ip() {
	$ip = '172.0.0.1';
	if (strpos(getenv('REMOTE_ADDR'), '172.') !== 0) {
		$ip = getenv('REMOTE_ADDR');
	}
	else {
		$ip = $_SERVER['HTTP_X_REAL_IP'];
		if ($ip == '') {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	}
	return $ip;
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

/**
 * Function return the content of $text that is between the first occurence of tag
 * and its first ending tag. If tag not exists it returns an empty string.
 */
function get_tag_content($text, $tag) {
	$start_parts = explode('<' . $tag . '>', $text);
	$end_parts = explode('</' . $tag . '>', $start_parts[1]);
	return $end_parts[0];
}

function drawColorBox($color,$outlinecolor) {
	# Funktion liefert eine Box als überlagerte Div in html,
	# die die Farbe $color und die Border $outlinecolor hat.
	$c = explode(' ',trim($color));
	$bgcolor = '#';
	for ($i = 0; $i < 3; $i++) {
		$bgcolor.=strtoupper(str_pad(dechex($c[$i]), 2, 0, STR_PAD_LEFT));
	}
	$oc = explode(' ',trim($outlinecolor));
	$bordercolor = '#';
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

function rgb2hex($rgb) {
	$c = explode(' ',trim($rgb));
	$hex = '#';
	for ($i = 0; $i < 3; $i++) {
		$hex .= strtoupper(str_pad(dechex($c[$i]), 2, 0, STR_PAD_LEFT));
	}
	return $hex;
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
 * 
 * @package     PHP_Compat
 * 
 * @link        http://php.net/function.str_split
 * 
 * @author      Aidan Lister <aidan@php.net>
 * 
 * @version     $Revision: 1.13 $
 * 
 * @since       PHP 5
 * 
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

function unzip($src_file, $dest_dir = false, $create_zip_name_dir = true, $overwrite = true){
	# 1. Methode über unzip (nur Linux) rausgenommen, da Umlaute kaputt gehen
	$entries = NULL;
	$success = false;
	if ($dest_dir === false) {
		$dest_dir = dirname($src_file);
	}
	$zip = new ZipArchive;
	if ($zip->open($src_file)) {
		for ($i = 0; $i < $zip->numFiles; $i++) {
			$entries[] = $zip->getNameIndex($i);
		}
		$success = $zip->extractTo($dest_dir); 
		$zip->close(); 
	}
	return array(
		'success' => $success,
		'files' => $entries
	);
}

/**
* is_zip_file
* 
* function check if file is a valid zip file. It check if
* - file exists and is readable
* - file extension is zip
* - file has correct header
*
* @param $file The path and filename of the file to be tested
*
* @return true If it is a zip file else false
*/
function is_zip_file($file) {
	//check is valid file or not and readable
	if (is_readable($file) == false) {
		return false;
	}
	//check file extension match with .zip or not
	if (pathinfo($file, PATHINFO_EXTENSION ) != 'zip') {
		return false;
	}
	$fileHeader = "\x50\x4b\x03\x04";
	$data = file_get_contents($file);
	if (strpos($data, $fileHeader) === false) {
		return false;
	}
	return true;
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
			$array[$i] = str_replace('ä', 'A', $array[$i]);
	  	$array[$i] = str_replace('ü', 'U', $array[$i]);
	  	$array[$i] = str_replace('ö', 'O', $array[$i]);
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

function umlaute_umwandeln($name) {
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
	return $name;
}

function sonderzeichen_umwandeln($name) {
	$name = umlaute_umwandeln($name);
	$name = str_replace('.', '', $name);
	$name = str_replace(':', '', $name);
	$name = str_replace('(', '', $name);
	$name = str_replace(')', '', $name);
	$name = str_replace('[', '', $name);
	$name = str_replace(']', '', $name);
	$name = str_replace('/', '-', $name);
	$name = str_replace(' ', '_', $name);
	$name = str_replace('-', '_', $name);
	$name = str_replace('?', '_', $name);
	$name = str_replace('+', '_', $name);
	$name = str_replace(',', '_', $name);
	$name = str_replace('*', '_', $name);
	$name = str_replace('$', '', $name);
	$name = str_replace('&', '_', $name);
	$name = str_replace('#', '_', $name);
	$name = iconv("UTF-8", "UTF-8//IGNORE", $name);
	return $name;
}

function sonderzeichen_umwandeln_reverse($name){
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

/**
 * liefert ein Array mit den Pfaden aller Dateien im Verzeichnis
 */
function searchdir($path, $recursive){
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

function extract_select_clause($sql) {
	$sql = trim($sql);
	$len = strlen($sql);
	$buffer = '';
	$klammer = 0;
	$inString = false;
	$i = 0;

	// Groß-/Kleinschreibung ignorieren
	$upperSql = strtoupper($sql);

	// Stelle sicher, dass es mit SELECT beginnt
	if (substr($upperSql, 0, 6) !== "SELECT") {
			throw new Exception("Not a SELECT query");
	}

	// Start nach "SELECT "
	$i = 6;
	while ($i < $len) {
			$char = $sql[$i];
			$prev = $i > 0 ? $sql[$i - 1] : '';

			// String beginnen / beenden
			if ($char === "'" && $prev !== "\\") {
					$inString = !$inString;
					$buffer .= $char;
					$i++;
					continue;
			}

			// Klammern zählen
			if (!$inString) {
					if ($char === '(') $klammer++;
					if ($char === ')') $klammer--;
			}

			// Prüfen, ob hier ein FROM kommt (außerhalb von Strings/Klammern)
			if (!$inString && $klammer === 0) {
					// Prüfe 4 Zeichen ab Position
					if (strtoupper(substr($sql, $i, 4)) === "FROM") {
							break; // oberster FROM gefunden
					}
			}

			$buffer .= $char;
			$i++;
	}

	return trim($buffer);
}

function get_select_parts($select) {
	$parts = [];
	$buffer = '';
	$klammer = 0;        // Verschachtelte Klammern
	$inString = false;   // Innerhalb von Hochkommas
	$len = strlen($select);

	for ($i = 0; $i < $len; $i++) {
			$char = $select[$i];
			$prev = $i > 0 ? $select[$i - 1] : '';

			// String beginnen / beenden (Hochkomma)
			if ($char === "'" && $prev !== "\\") {
					$inString = !$inString;
					$buffer .= $char;
					continue;
			}

			// Klammern zählen, aber nur außerhalb von Strings
			if (!$inString) {
					if ($char === '(') $klammer++;
					if ($char === ')') $klammer--;
			}

			// Komma als Trennzeichen nur außerhalb von Klammern und Strings
			if ($char === ',' && !$inString && $klammer === 0) {
					$parts[] = trim($buffer);
					$buffer = '';
			} else {
					$buffer .= $char;
			}
	}

	// Letzten Teil hinzufügen
	if (trim($buffer) !== '') {
			$parts[] = trim($buffer);
	}

	return $parts;
}

function microtime_float(){
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}


function copy_file_to_tmp($frompath, $dateiname = ''){
	$dateityp = pathinfo($frompath)['extension'];
  $dateipfad=IMAGEPATH;
  if($dateiname == '')$dateiname=rand(100000,999999).'.'.$dateityp;
  if(copy($frompath, $dateipfad.$dateiname) == true){
    return TEMPPATH_REL.$dateiname;
  }
  else{
    echo 'Datei '.$frompath.' konnte nicht nach '.$dateipfad.$dateiname.' kopiert werden.';
  }
	#exec('ln -s '.$frompath.' '.$dateipfad.$dateiname);
	#return TEMPPATH_REL.$dateiname;
}

/**
 * Löscht ein Verzeichnis und alle darin befindlichen Dateien
 * Sicherheitsabfrage: Verzeichnis muß unter /var/www/data/ liegen und dort auch existieren
 * @param string $dir Pfad des zu löschenden Verzeichnisses
 * @return boolean true wenn Verzeichnis gelöscht wurde, sonst false
 */
function delete_dir_with_files($dir) {
	if ($dir == '' OR strpos($dir, '/var/www/data/') === false OR !is_dir($dir)) {
		return false;
	}

	foreach (glob($dir . '/*') as $file) {
		if (is_file($file)) {
			unlink($file);
		}
	}
	return rmdir($dir);
}

function read_epsg_codes($database) {
  $epsg_codes = $database->read_epsg_codes();
  return $epsg_codes;
}

function read_colors($database){
  $colors = $database->read_colors();
  return $colors;
}

function delete_files($target, $exceptions, $output){
	if (is_dir($target)) {
		$sourcedir = opendir($target);
		while (false !== ($filename = readdir($sourcedir))) {
			if (!in_array($filename, $exceptions)) {
				if ($output) {
					echo "Processing: " . $target . "/" . $filename . "<br>";
				}
				if (is_dir($target . "/" . $filename)) {
					// recurse subdirectory; call of function recursive
					delete_files($target . "/" . $filename, $exceptions, 0);
				}
				else if(is_file($target . "/" . $filename)) {
					// unlink file
					unlink($target . "/" . $filename);
				}
			}
		}
		closedir($sourcedir);
		if (rmdir($target)) {
			return true;
		}
		else {
			return false;
		}
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

/**
 * Zeigt Text in einem Java-Script Alarmfenster an
 */
function showAlert($text) {
  ?>
  <script type="text/javascript">
    alert("<?php echo str_replace('"', '\"', $text); ?>");
  </script><?php
}

/**
 * Funktion wandelt UNIX Zeichen in DOS Zeichen um für Konvertierung WLDGE-Dateien
 */
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

/**
 * Funktion bricht $text in Wörtern in ein Array von Zeilen der Länge $laenge um
 * 
 * Beispiel:
 * $block=zeilenumbruch('Dies ist ein Beilspiel.',12);
 * echo $block[0]; # liefert "Dies ist ein"
 * echo $block[1]; # liefert "Beispiel"
 */
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

/**
 * Funktion führt eine Lauflängenkodierung des Arrays aus
 * und komprimiert somit das Array von Einzelwerten
 * array runLenComp(array array);
 * von den vorkommenden ganzen Zahlen werden von-bis Intervalle gebildet
 * sortieren der Liste
 */
function runLenComp($liste) {
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

/**
 * Funktion, die prüft, ob Datum sinnvoll ist.
 */
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

/**
 * Funktion teilt eine EDBS Datei in einzelne Dateien
 * Jeder Auftrag wird in eine separate Datei geschrieben
 * Öffnen der EDBS Datei
 */
function split_edbs_file($pfad,$filename) {
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

/**
 * Prüft eine E-Mail adresse auf richtige schreibweise
 */
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

  $postfix = strlen(strrchr($email, ".")) - 1;

  if (!($postfix > 1 AND $postfix < 8)) {
    #echo " postfix ist zu kurz oder zu lang";
    $Meldung.='<br>E-Mail ist zu kurz oder zu lang.';
  }
  return $Meldung;
}

function buildExpressionString($str) {
  $intervalle = explode(';', $str);
  $anzInt = count($intervalle);
  if ($intervalle[$anzInt-1] == '') { $anzInt--; }
  # Beginne mit der Erstellung des Ausdrucks
  $expr.= '(';
  # man neheme das erste Intervall
  # Zerlege es in Anfang und Ende
  $grenzen = explode('-', $intervalle[0]);
  # Teste ob es überhaupt ein Ende gibt, oder nur einen einzelnen Wert
  if (count($grenzen) == 1) {
    # Wenn ja, wird die erste einschränkung geschrieben.
    $expr.= '[ID] = ' . $grenzen[0];
  }
  else {
    # Wenn es Anfang und Ende gibt, müssen zwei Bedingungen geschrieben werden
    $expr.='([ID] > '.$grenzen[0].' AND [ID] < ' . $grenzen[1] . ')';
  }
  # weiter geht es mit den nächsten Intervallen
  for ($i = 1; $i < $anzInt; $i++) {
    # wieder Zerlegen in Anfang und Ende
    $grenzen = explode('-', $intervalle[$i]);
    if (count($grenzen) == 1) {
      # Es gibt nur einen Wert
      $expr.=' OR [ID] = ' . $grenzen[0];
    }
    else {
      # Es gibt Anfang und Ende im Intervall
      $expr.=' OR ([ID] > ' . $grenzen[0] . ' AND [ID] < ' . $grenzen[1] . ')';
    }
  }
  # Beenden des Ausdrucks
  $expr .= ')';
  return $expr;
}

function getNumPagesPdf($filepath){
	exec('gs -q -I / -dNODISPLAY -c "('.$filepath.') (r) file runpdfbegin pdfpagecount = quit"', $output);
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
		if ($useragent) {
			$ctx['http']['header'] = 'User-Agent: ' . $useragent;
		}
		if ($username) {
			$ctx['http']['header'].= "Authorization: Basic ".base64_encode($username . ':' . $password);
		}
		$proxy = getenv('HTTP_PROXY');
		if ($proxy != '' AND $hostname != 'localhost') {
			$ctx['http']['proxy'] = $proxy;
			$ctx['http']['request_fulluri'] = true;
			$ctx['ssl']['SNI_server_name'] = $hostname;
			$ctx['ssl']['SNI_enabled'] = true;
		}
		$context = stream_context_create($ctx);
		$response =  @file_get_contents($url, false, $context);
		if ($response === false) {
			$error = 'Fehler beim Abfragen der URL mit file_get_contents(' . $url . ')';
			GUI::add_message_('error', $error);
			throw new Exception($error);
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
	if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 404) {
		$result = "Fehler 404: File not found. Die Resource konnte mit der URL: ".$url." nicht auf dem Server gefunden werden!";
	}
	if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 401) {
		$result = "Fehler 401: Unauthorized. Auf die Resource konnte mit der URL: ".$url." nicht zugegriffen werden!";
	}
	curl_close($ch);
	return $result;
}

function debug_write($msg, $var = NULL, $user_id = NULL) {
	global $GUI;
	if ($user_id AND $user_id == $GUI->user->id) {
		echo "<p>Debug msg: " . $msg . ' val: ' . print_r($var, true) . ' type: ' . getType($var);
	}
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

/**
* This function removes or keeps elements defined in $strip_list from $fromvars array
*
* If elements from strip_list shall be removed (default), all other will be keeped in the formvars array.
* If elements shall be keeped, all other will be removed in the formars array.
* strip_type 'remove' delete formvars defined in strip_list.
* strip_type ('keep' | any other than 'remove') keep formvars defined in strip_list.
*
* @param $formvars array
*
* @param $strip_list comma separated string or array - formvar names to keep or to remove
*
* @param $strip_type string - Tells the function to keep or remove the formvars
*/
function formvars_strip($formvars, $strip_list, $strip_type = 'remove') {
	#echo '<br>formvars vorher: ' . print_r($formvars, true);
	#echo '<br>strip_list: ' . print_r($strip_list, true);
	#echo '<br>strip_type: ' . $strip_type;

	$strip_array = (is_array($strip_list) ? $strip_list : explode(', ', $strip_list));
	$stripped_formvars = array();

	foreach($formvars AS $key => $value) {
		$pos = false;
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
			if (is_string($value)) {
				$pos = strpos($value, '[');
				if ($pos !== false AND $pos == 0) {
					$stripped_formvars[$key] = arrStrToArr(stripslashes($value), ',');
				}
				else {
					$stripped_formvars[$key] = stripslashes($value);
				}
			}
			else {
				$stripped_formvars[$key] = $value;
			}
		}
		else {
			#echo "<br>Strip {$key} from formvars.";
		}

	}
	#echo '<br>formvars nachher: ' . print_r($stripped_formvars, true);
	return $stripped_formvars;
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

function replace_params_link($str, $params, $layer_id) {
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, '<a href="javascript:void(0)" onclick="getLayerParamsForm(' . $layer_id .  ')">' . $value . '</a>', $str);
		}
	}
	return $str;
}

/**
* Funktion sendet e-mail mit Dateien im Anhang
* siehe [http://www.php-einfach.de/codeschnipsel_1114.php](http://www.php-einfach.de/codeschnipsel_1114.php)
*
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
function mail_att($from_name, $from_email, $to_email, $cc_email, $reply_email, $subject, $message, $attachement, $mode, $smtp_server, $smtp_port, $to_name = 'Empfänger', $reply_name = 'WebGIS-Server', $bcc = null) {
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
		case 'PHPMailer' : {
			require WWWROOT. APPLVERSION . THIRDPARTY_PATH . 'PHPMailer/src/Exception.php';
			require WWWROOT. APPLVERSION . THIRDPARTY_PATH . 'PHPMailer/src/PHPMailer.php';
			require WWWROOT. APPLVERSION . THIRDPARTY_PATH . 'PHPMailer/src/SMTP.php'; // Yes, this exists
			$mail = new PHPMailer();
			// $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
			$mail->isSMTP();
			$mail->Host = $smtp_server . ':' . $smtp_port;
			$mail->SMTPAuth = true;
			$mail->Username = MAILSMTPUSER;
			$mail->Password = MAILSMTPPASSWORD;
			$mail->SMTPSecure = 'tls';
			$mail->From = $from_email;
			$mail->FromName = $from_name;
			$mail->addAddress($to_email, $to_name);
			$mail->addReplyTo($reply_email, $reply_name);
			if ($cc_email) {
				$mail->addCC($cc_email);
			}
			if ($bcc_email) {
				$mail->addBCC($bcc_email);
			}
			// $mail->WordWrap = 50;
			// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(false);
			$mail->CharSet = "UTF-8";
			$mail->Subject = $subject;
			$mail->Body    = $message;
			// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			$result = $mail->send();

			return $result;
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

/**
 * Function implode $list with $delimiter but with $last_delimiter for the last conjunction.
 * If $list is a String it will be exploded with $delimiter first.
 * @param Array|String $list The list of values to implode.
 * @param String $delimiter
 * @param String $last_delimiter
 * @return String The imploded string
 */
function natural_join($list, $delimiter =', ', $last_delimiter = ' und ') {
	if (gettype($list) === 'string') {
		$list = explode($delimiter, $list);
	}
  $last = array_pop($list);
  if ($list) {
    return implode($delimiter, $list) . $last_delimiter . $last;
  }
  return $last;
}

/**
* function replaced square brackets at the beginning and the end of the string
* and return the elements of the string as array separated by the delimmiter.
* The elements of the string will be replaced by slashes and timed from white spaces and ".
*/
function arrStrToArr($str, $delimiter, $brackets = '[]') {
#	if(is_string($delimiter) and in_array())
#	echo gettype($delimiter);
	$arr = explode($delimiter, trim($str, $brackets));
	foreach ($arr as &$value) {
		$value = trim(stripslashes($value), '"' . $brackets . '"');
	}
	return $arr;
}

/**
* @param string $form_field_name - used also als form field id
*
* @param array $data - array with values and options per array element
*
* @param string $selected_value
*
* @param string $onchange - javascript to execute on change
*
* @param string $title - of the select field
*
* @param string $null_option - create a first option with value = '' and the text of $null_option
*
* @param string $style - css Style for the select element
*
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

/**
* Die Funktion liefert das erste Wort, welches nach $word in $str gefunden wird.
* Über die optionalen Parameter $delim1 und $delim2 kann man die Trennzeichen vor und nach dem Wort angeben.
* Wenn der optionale Parameter $last true ist, wird das letzte Vorkommen des Wortes verwendet.
* Wenn das Wort nicht vorkommt, wird false zurückgeben.
*/
function get_first_word_after($str, $word, $delim1 = ' ', $delim2 = ' ', $last = false) {
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
	return '';
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

/**
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
		$html .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) .'">';
	}
	return $html;
}

/**
* Function liefert eine Liste von Parametern von einem verschachtelten array
* z.B. wir aus:
*
* Array('a' => 1, 'b' => Array(0 => 2, 1 => Array('c' => 3, 'd' => 4)))
* Array('a' => 1, 'b[0]' => 2, 'b[1][c]' => 3, 'b[1][d]' => 4)
* Diese Funktion kann verwendet werden, um formvars in Parameter zu wandeln,
* die in hidden input Feldern ausgegeben werden soll.
*
* @param $array_key String Name des Layer_Parameter_speichern
*
* @param $array Array Das Array mit den default-Werten
*
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
*
*  @license MIT <http://eyecatchup.mit-license.org/>
*
*  @link https://gist.github.com/eyecatchup/f26300ffd7e50a92bc4d
*
*  @param   string   $url          Takes the remote object's URL.
*
*  @param   boolean  $formatSize   Whether to return size in bytes or formatted.
*
*  @param   boolean  $useHead      Whether to use HEAD requests. If false, uses GET.
*
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

/**
* Function returns a readable message of sql errors optionally with word $find replaced by asterists *****
*/
function err_msg($file, $line, $msg, $find = '') {
	return "<br>Abbruch in " . $file . " Zeile: " . $line . "<br>wegen: " . ($find != '' ? str_replace($find, '*****', $msg) : $msg). "<p>" . INFO1;
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

/**
 * Prüft ob der Wert $key im Array $array existiert und gibt den Wert zurück.
 * Wenn der Wert nicht existiert, wird ein leerer String zurückgegeben.
 */
function value_of($array, $key) {
	if (!is_array($array)) {
		$array = array();
	}
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
* [https://pageconfig.com/post/replace-last-occurrence-of-a-string-php](https://pageconfig.com/post/replace-last-occurrence-of-a-string-php)
* In case the $search is not found inside the $str, the function returns the original untouched string $str.
* This behavior is compatible with the default behavior of str_replace PHP’s builtin function that replaces all
* occurrances of a string inside another string.
*
* @param string $search keeps the string to be searched for
*
* @param string $replace Is the replacement string
*
* @param string $str Is the subject string, commonly known as haystack
*/
function str_replace_last($search , $replace, $str) {
  if (($pos = strrpos($str, $search)) !== false) {
    $search_length  = strlen( $search );
    $str    = substr_replace( $str , $replace , $pos , $search_length );
  }
  return $str;
}

/**
 * Liefert den Namen der Thumb-Datei vom Originalnamen in $path
 * @param String $path Name of original file.
 * @return String Name of thumb file.
 */
function get_thumb_from_name($path) {
	return before_last($path, '.') . '_thumb.jpg';
}

/**
 * Liefert den Basename der Originaldatei vom Namen der Thumb-Datei
 * @param String $thumb Name of the thumb file.
 * @return String Basename of the original File.
 */
function get_name_from_thumb($thumb) {
	return before_last($thumb, '_thumb.jpg');
}

/**
 * Function return the most likely delimiter of $line
 * @param string $line The line to test.
 * @return string The detected delimiter.
 */
function detect_delimiter($line) {
	$delimiters = [',', ';', "\t", '|', ':'];
	$delimiter_counts = [];
	foreach ($delimiters as $delimiter) {

		$delimiter_counts[$delimiter] = substr_count($line, $delimiter);
	}
	// Find the delimiter with the highest count
	$most_likely_delimiter = array_keys($delimiter_counts, max($delimiter_counts));
	return $most_likely_delimiter[0];
}

/**
* Funktion liefert Teilstring von $txt vor dem letzten vorkommen von $delimiter
* Kann z.B. verwendet werden zum extrahieren der Originaldatei vom Namen eines Thumbnails
* z.B. before_last('MeineDatei_abc_1.Ordnung-345863_thumb.jpg', '_') => MeineDatei_abc_1.Ordnung-345863
*
* @param string $txt Der Text von dem der Teilstring extrahiert werden soll.
*
* @param string $delimiter Der Text, der den Text trennt in davor und danach.
*
* @return string Der Teilstring vor dem letzten Vorkommen von $delimiter
* oder ein Leerstring wenn $txt $delimiter nicht enthält oder $delimiter leer ist
*/
function before_last($txt, $delimiter) {
	#echo '<br>Return the part of ' . $txt . ' before the last occurence of ' . $delimiter;
	if (!$delimiter) {
		return '';
	}
	$pos = strrpos($txt, $delimiter);
  return $pos === false ? $txt : substr($txt, 0, $pos);
}

/**
 * Function returns the parts of a mapserver data statement
 * @param string $data Mapserver data statement
 * @return Array (geom, inner select, using)
 */
function getDataParts($data){
	$first_space_pos = strpos($data, ' ');
	$geom = substr($data, 0, $first_space_pos);					# geom am Anfang
	$rest = substr($data, $first_space_pos);
	$usingposition = stripos($rest, 'using');
	$from = substr($rest, 0, $usingposition);						# from (alles zwischen geom und using)
	$using = substr($rest, $usingposition);							# using ...
	if(strpos($from, '(') === false){		# from table
		$select = 'select * '.$from.' where 1=1';
	}
	else{		# from (select ... from ...) as foo
		$select = stristr($from,'(');
		$select = before_last($select, ')');
		$select = ltrim($select, '(');
	}
	return [
		'geom' => $geom,
		'select' => $select,
		'using' => $using
	];
}

/**
 * Function return the alias of the first $schema_name.$table_name in from expression of $sql.
 * Returns an empty string if $schema_name.$table_name not exists in $sql.
 * Returns $table_name if $schema_name.$table_name exists but no alias for it.
 * Befor parsing the sql all select expressions will be replaced by *
 * @param string $sql The SQL-Statement to parse.
 * @param string $schema_name The schema name of the table.
 * @param string $table_name The table name.
 * @return String Empty if $schema_name.$table_name not exists, alias if exists else $table_name
 */
function get_table_alias($sql, $schema_name, $table_name) {
	include_once(WWWROOT . APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLParser.php');

	// sql für parser aufbereiten, select ausdrücke durch * ersetzen.
	$words = preg_split('/\s+/', $sql); // in Wörter zerhacken
	$sql = '';
	for ($i = count($words) - 1; $i > -1; $i--) {
		$sql = $words[$i] . ' ' . $sql;
		// sql auffüllen mit Wörtern der from - Klausel
		if (strtolower($words[$i]) == 'from') {
			// Schluss bei from select * davor, damit es ein valides sql wird
			$sql = 'select * ' . $sql;
			$i = -1; // Abbruch
		}
	};
	$parser = new PHPSQLParser($sql, true);

	// Extrahiere den from-Ausdruck der zu $schema.$table_name passt
	$table_expression = array_filter(
		$parser->parsed['FROM'],
		function($from) use ($schema_name, $table_name) {
			return $from['table'] == $schema_name . '.' . $table_name;
		}
	);

	if (count($table_expression) == 0) {
		// $schema_name.$table_name kommt nicht im $sql FROM vor
		return '';
	}

	// $schema_name.$table kommt in FROM vor, nimmt den ersten
	$table_expression = $table_expression[0];

	if ($table_expression['alias']) {
		return $table_expression['alias']['name']; // wenn es einen alias gibt
	}
	else {
		return $table_name; // wenn es keinen gibt
	}
}

function get_requires_options($sql, $requires) {
	include_once(WWWROOT . APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLParser.php');
	include_once(WWWROOT . APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLCreator.php');
	# Entfernt requires Tag damit kein Syntax-Fehler im sql ist.
	$sql = str_replace(['<requires>', '</requires>'],	'',	$sql);
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

/**
* This function convert an assosiative array with 1-dim vectors of values of the same length
* to an array with associative arrays e.g.
*
* from: array(
* 	'id' => array(1,2,3),
* 	'name' => array('a', 'b', 'c');
* );
* to: array(
* 	array('id' => 1, 'name' => 'a'),
* 	array('id' => 2, 'name' => 'b'),
* 	array('id' => 3, 'name' => 'c')
* )
*/
function vectors_to_assoc_array($vectors) {
	$keys = array_keys($vectors);
	if (count($keys) == 0) {
		return array();
	}
	$first_vector = $vectors[$keys[0]];
	$result = array();
	foreach ($first_vector AS $id => $value) {
		$assoc = array();
		foreach ($keys AS $key) {
			$assoc[$key] = $vectors[$key][$id];  
		}
		$result[] = $assoc;
	}
	return $result;
}

function sql_from_parse_tree($parse_tree) {
	$sql = array();
	foreach ($parse_tree as $node) {
		if ($node['sub_tree'] != '') {
			$sql[] .= sql_from_parse_tree($node['sub_tree']);
		}
		else {
			$sql[] .= $node['base_expr'];
		}
	}
	return implode(' ', $sql);
}

/**
 * Function sanitizes $value based on its $type and returns the sanitized value.
 * If $type or $value is empty or $type unknown, $value will not be sanitized at all.
 * If $value is an array all elements will be sanitized with $type.
 * @param any $value Value to sanitize
 * @param string $type The type of the value
 * @param bool $removeTT If true, floats and integers are converted by the function removeTausenderTrenner
 * @return any The sanitized value
 */
function sanitize(&$value, $type, $removeTT = false) {
	if (empty($type)) {
		return $value;
	}

	if (is_array($value)) {
		foreach ($value AS &$single_value) {
			sanitize($single_value, $type, $removeTT);
		}
		return $value;
	}

	if (empty($value)) {
		return $value;
	}

	switch ($type) {
		case 'integer' :
		case 'int' :
		case 'int4' :
		case 'oid' :
		case 'boolean' :
		case 'int8' : {
			$value = (int) ($removeTT ? removeTausenderTrenner($value) : $value);
		} break;

		case 'int_csv' : {
			$value = explode(',', (string)$value);
			foreach ($value AS &$single_value) {
				sanitize($single_value, 'int');
			}
			$value = implode(',', $value);
		} break;

		case 'numeric' :
		case 'float4' :
		case 'float8' :
		case 'float' : {
			$value = (float) ($removeTT ? removeTausenderTrenner($value) : $value);
		} break;

		case 'text' :
		case 'geometry' :
		case 'timestamp' :
		case 'date' :
		case 'unknown' :
		case 'varchar' : {
			$value = pg_escape_string($value);
		} break;

		case 'bool': {
			$value = (is_string($value) ? pg_escape_string($value) : (int) $value);
		} break;

		default : {
			// let $value as it is
		}
	}
	return $value;
}

/**
*	Function determine the max allowed file size in MB
*	If php configuration parameter are defined with G the values
*	will be multiplied by 1024
*/
function get_max_file_size() {
	$post_max_size_txt = ini_get('post_max_size');
	$post_max_size = 0;
	$upload_max_filesize = 0;
	if (strpos($post_max_size_txt, 'G') !== false) {
		$post_max_size = (int)$post_max_size_txt * 1024;
	}
	$upload_max_filesize_txt = ini_get('upload_max_filesize');
	if (strpos($upload_max_filesize_txt, 'G') !== false) {
		$upload_max_filesize = (int)$upload_max_filesize_txt * 1024;
	}
	if ($post_max_size == 0 AND $upload_max_filesize == 0) {
		return MAXUPLOADSIZE;
	}
	else {
		return min($post_max_size, $upload_max_filesize);
	}
}

/**
*	Function searches for $value in $array and if exists
*	remove it and put it at first position in array
*
*	@param $array, Array
*
*	@param $value, Any
*
*	@return Array
*/
function put_value_first($array, $value) {
	$key = array_search($value, $array);
	if ($key !== false) {
		unset($array[$key]);
	}
	array_unshift($array, $value);
	return $array;
}

/**
*	Convert German date format 25.12.2020
*	to English date format 2022-12-25
*/
function en_date($date_de) {	
	return date('Y-m-d', strtotime($date_de));
}

/**
*	Convert English date format 2022-12-25
*	to German date format 25.12.2022
*/
function de_date($date_en) {	
	if (strlen($date_en) > 10) {
		return date('d.m.Y G:i:s', strtotime($date_en));
	}
	else {
		return date('d.m.Y', strtotime($date_en));
	}
}

function layer_name_with_alias($name, $alias, $options = array()) {
	$default_options = array(
		'alias_first' => false,
		'delimiter' => ' ',
		'brace_type' => 'sharp'
	);
	$brace = array(
		'sharp' => array('[', ']'),
		'round' => array('(', ')'),
		'curly' => array('{', '}'),
		'no' => array('', '')
	);
	$options = array_merge($default_options, $options);
	if ($options['alias_first']) {
		return $alias . $options['delimiter'] . $brace[$options['brace_type']][0] . $name . $brace[$options['brace_type']][1];
	}
	else {
		return $name . ($alias != '' ? $options['delimiter'] . $brace[$options['brace_type']][0] . $alias . $brace[$options['brace_type']][1] : '');
	}
}

/**
 * Function read all files recursively from a directory
 * @param string $dir - The directory
 * @return Array $files - The files in the directory and below
 */
function getAllFiles($dir) {
	$files = [];

	if (substr($dir, -1) != '/') {
		$dir .= '/';
	}

	// Get all files and directories within the directory
	$items = glob($dir . '*', GLOB_MARK);

	foreach ($items AS $item) {
		if (is_dir($item)) {
			$files = array_merge($files, getAllFiles($item));
		}
		else {
			// $files[pathinfo($item, PATHINFO_EXTENSION)][] = $item;
			$files[] = $item;
		}
	}
	return $files;
}

function in_date_range($startzeiten, $endzeiten, $x) {
	$num_start = count($startzeiten);
	$num_ende = count($endzeiten);
	if (
		($num_start == 0 AND $num_ende == 0) OR
		($num_start == 0 AND $num_ende > 0 AND $endzeiten[0] > $x) OR # vor dem Ende
		($num_ende == 0 AND $num_start > 0 AND $startzeiten[$num_start - 1] < $x) # nach dem Anfang
	) {
		return true;
	}

	if ($num_start != $num_ende) {
		return false;
	}

	for ($i = 0; $i < $num_start; $i++) {
		if ($startzeiten[$i] <= $x AND $x <= $endzeiten[$i]) return true;
	}

	return false;
}

/**
 * Function check if in $files exists files with any of $required extensions.
 * Default is to check if all required shape files extensions exists in $files
 * @param Array $files A list of filenames.
 * @param Array $required (optional) A list of extensions.
 */
function required_shape_files_exists($files, $required = array('shp', 'shx', 'dbf')) {
	$existing = array_intersect(
		$required,
		array_map(
			function($file) {
				return strtolower(pathinfo($file, PATHINFO_EXTENSION));
			},
			$files
		)
	);

	if ($required == $existing) {
		return array(
			'success' => true,
			'msg' => 'Alle erforderlichen Dateien vorhanden.'
		);
	}
	else {
		$missing = array_diff($required, $existing);
		return array(
			'success' => false,
			'msg' => 'In der ZIP-Datei ' . (count($missing) == 1 ? 'fehlt die Datei mit der Endung' : 'fehlen die Dateien mit den Endungen') . ' ' . implode(', ', $missing)
		);
	}
}

function set_href($text) {
	if (strpos($text, ';http') !== false) {
		$parts = explode(';http', $text);
		$text = '<a href="http' . $parts[1] . '" target="Urheber" title="' . $parts[0] . '">' . $parts[0] .'</a>';
	}
	return $text;
}

/**
 * Function return the option value of attribute at index $i for option key $option_key
 * It searches first in options_json if exists else in attributes array directly
 * ToDo pk: Zusammenführen mit der Funktion get_options und get_SubFormFK_options in LayerAttributes.php
 * @param Array $attributes The attributes array.
 * @param Integer $i The index of the attribute.
 * @param String $option_key The option key to get the value for.
 * @return mixed The option value or null if not exists.
 */
function get_attribute_option($attributes, $i, $option_key) {
	$option = null;
	if (is_array($attributes)) {
		if (
			array_key_exists('options_json', $attributes) AND
			is_array($attributes['options_json']) AND
			array_key_exists($option_key, $attributes['options_json'][$i])
		) {
			$option = $attributes['options_json'][$i][$option_key];
		}
		if (
			$option === null AND
			array_key_exists($option_key, $attributes) AND
			is_array($attributes[$option_key])
		) {
			$option = $attributes[$option_key][$i];
		}
	}
	return $option;
}

?>
