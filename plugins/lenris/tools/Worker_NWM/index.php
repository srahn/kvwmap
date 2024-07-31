<?

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));

include('settings.php');

$neue_nachweise = array();
$geaenderte_nachweise = array();
$geloeschte_nachweise = array();

switch ($_REQUEST['go']) {
	case 'LENRIS_get_all_nachweise' : case 'LENRIS_get_new_nachweise' : {
		ini_set('memory_limit', '8192M');
		set_time_limit(3600);
		$con = pg_connect('host=' . $host . ' dbname=' . $db . ' user=' . $user . ' password=' . $pass) or die ("Could not connect to server\n");
		pg_set_client_encoding($con, 'UNICODE');
		leereEntpacktOrdner($data_nwm, $con);
		entpackeZIP($data_nwm);
		verarbeiteXML($data_nwm, $con, $_REQUEST['go']);
		pg_close($con);
		echo (!empty($neue_nachweise) ? json_encode($neue_nachweise) : '');
		file_put_contents($data_nwm . '/changed.json', (!empty($geaenderte_nachweise) ? json_encode($geaenderte_nachweise) : ''));
		file_put_contents($data_nwm . '/deleted.json', (!empty($geloeschte_nachweise) ? json_encode($geloeschte_nachweise) : ''));
	}break;
	case 'LENRIS_get_changed_nachweise' : {
		if (file_exists($data_nwm . '/changed.json')) {
			readfile($data_nwm . '/changed.json');
		}
	}break;
	case 'LENRIS_get_deleted_nachweise' : {
		if (file_exists($data_nwm . '/deleted.json')) {
			readfile($data_nwm . '/deleted.json');
		}
	}break;
	case 'LENRIS_get_document' : {
		$document = substr($_REQUEST['document'], 0, -4);
		if (strpos($document, $data_nwm) !== false AND strpos($document, '..') === false AND file_exists($document)) {
			readfile($document);
		}
	}break;
	case 'LENRIS_confirm_new_nachweise' : {
		LENRIS_confirm_new_nachweise($data_nwm, $_REQUEST['ids']);
	}break;
	case 'LENRIS_confirm_changed_nachweise' : {
		LENRIS_confirm_changed_nachweise($data_nwm, $_REQUEST['ids']);
	}break;
	case 'LENRIS_confirm_deleted_nachweise' : {
		LENRIS_confirm_deleted_nachweise($data_nwm, $_REQUEST['ids']);
	}break;
}

function leereEntpacktOrdner($data_nwm, $con){
	$xml_files = array();
	$xml_files = rsearch($data_nwm . '/entpackt', '/.*\.gml/');
	if (empty($xml_files)) {
		$sql = "
			SELECT 
				count(client_id)
			FROM
				lenris.zu_holende_dokumente
			WHERE
				client_id = 6;"; 
		$ret = pg_query($con, $sql) or die("Cannot execute query: $sql\n"); 
		$rs = pg_fetch_row($ret);
		if ($rs[0] === '0') {
			exec('rm -R ' . $data_nwm . '/entpackt/*');
		}
	}
}

function entpackeZIP($data_nwm){
	$zip_files = array();
	$zip_files = rsearch($data_nwm . '/home_nwm', '/.*\.zip/');
	foreach ($zip_files as $zip_file) {
		exec('unzip -o "' . $zip_file . '" -d ' . $data_nwm . '/entpackt', $output, $ec);
		if ($ec == 0) {
			unlink($zip_file);
			rmdir(dirname($zip_file));
		}
	}
}

class RecursiveDotFilterIterator extends  RecursiveFilterIterator {
	public function accept(){
		return '.' !== substr($this->current()->getFilename(), 0, 1);
	}
}

function rsearch($folder, $regPattern) {
	$dir = new RecursiveDotFilterIterator(new RecursiveDirectoryIterator($folder));
	$ite = new RecursiveIteratorIterator($dir);
	$files = new RegexIterator($ite, $regPattern, RegexIterator::GET_MATCH);
	$fileList = array();
	foreach($files as $file) {
			$fileList = array_merge($fileList, $file);
	}
	return $fileList;
}

function getNodeValue($domNode, $tagname){
	$value = $domNode->getElementsByTagName($tagname);
	if ($value->length > 0) {
		return $value->item(0)->nodeValue;
	}
}

function verarbeiteXML($data_nwm, $con, $go){
	global $neue_nachweise;
	global $geaenderte_nachweise;
	global $geloeschte_nachweise;
	global $objektarten;
	global $dokumentarten;
	$client_nachweis_ids = array();
	$doc = new DOMDocument('1.0', 'UTF-8');
	$xml_files = array();
	$xml_files = rsearch($data_nwm . '/entpackt', '/.*\.gml/');
	if (!empty($xml_files)) {
		if ($go == 'LENRIS_get_new_nachweise') {
			# vorhandene ids abfragen
			$sql = "
				SELECT 
					client_nachweis_id
				FROM
					lenris.client_nachweise
				WHERE
					client_id = 6;"; 
			$ret = pg_query($con, $sql) or die("Cannot execute query: $sql\n"); 
			while ($rs = pg_fetch_assoc($ret)){
				$client_nachweis_ids[$rs['client_nachweis_id']] = 1;
			}
		}
		# XML-Dateien parsen
		$reader = new XMLReader();
		foreach ($xml_files as $xml_file) {
			$reader->open($xml_file);
			while ($reader->read()) {
				if (array_key_exists($reader->name, $objektarten) AND $reader->nodeType == XMLReader::ELEMENT) {
					$nachweis = array();
					$domNode = $reader->expand();
					$gesamtschluessel = getNodeValue($domNode, 'Gesamtschluessel');
					$nachweis['id'] = getNodeValue($domNode, 'Datei_BLOB_ID');
					$nachweis['link_datei'] = dirname($xml_file) . '/' . $nachweis['id'] . '.pdf';
					$nachweis['stammnr'] = getNodeValue($domNode, 'Geschaeftsnummer');
					if (!$nachweis['art'] = $dokumentarten[getNodeValue($domNode, 'Typ')]) {
						$nachweis['art'] = $objektarten[$reader->name];
					}
					$nachweis['erstellungszeit'] = getNodeValue($domNode, 'beginn');
					$endet = getNodeValue($domNode, 'endet');
					$nachweis['flurid'] = substr($gesamtschluessel, 0, 9);
					$nachweis['fortfuehrung'] = (int)substr($gesamtschluessel, 11, 4);
					$nachweis['rissnummer'] = (int)substr($gesamtschluessel, 15, 4);
					$nachweis['blattnummer'] = (int)substr($gesamtschluessel, 19, 3);					
				}				
				if ($reader->name == 'rvw:Position' AND $reader->nodeType == XMLReader::ELEMENT) {
					$gml_geom = $reader->readInnerXML();
					if ($gml_geom != '') {
						$nachweis['the_geom'] = "ST_GeomFromGML('" . $gml_geom . "')";
					}
				}
				if (array_key_exists($reader->name, $objektarten) AND $reader->nodeType == XMLReader::END_ELEMENT) {
					if (!empty($nachweis) AND $nachweis['id'] != '') {
						if (!array_key_exists($nachweis['id'], $client_nachweis_ids)){
							if ($endet == '' AND $nachweis['the_geom'] != '') {
								$neue_nachweise[] = $nachweis;
								$client_nachweis_ids[$nachweis['id']] = 1;
							}
						}
						else {
							if ($endet != '') {
								$geloeschte_nachweise[] = array('id_nachweis' => $nachweis['id']);
							}
							else {
								$geaenderte_nachweise[] = $nachweis;
							}
						}
					}
				}
			}
		}
	}
}

function loescheXMLDateien($data_nwm){
	$xml_files = array();
	$xml_files = rsearch($data_nwm . '/entpackt', '/.*\.gml/');
	$ret = true;
	if (!empty($xml_files)) {
		foreach ($xml_files as $xml_file) {
			if ($ret) {
				$ret = unlink($xml_file);
			}
		}
	}
	return $ret;
}

function LENRIS_confirm_new_nachweise($data_nwm, $ids){
	$ret = loescheXMLDateien($data_nwm);
	if ($ret) {
		echo substr_count($ids, ',') + 1;
	}
}

function LENRIS_confirm_changed_nachweise($data_nwm, $ids){
	loescheXMLDateien($data_nwm);
	$ret = unlink($data_nwm . '/changed.json');
	if ($ret) {
		echo substr_count($ids, ',') + 1;
	}
}

function LENRIS_confirm_deleted_nachweise($data_nwm, $ids){
	loescheXMLDateien($data_nwm);
	$ret = unlink($data_nwm . '/deleted.json');
	if ($ret) {
		echo substr_count($ids, ',') + 1;
	}
}

?>