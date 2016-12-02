<?php
class NASLoader extends DOMDocument {
	
	static $write_debug = false;
	
	function NASLoader($gui) {
		$gui->debug->show('Create new Object NASLoader', NASLoader::$write_debug);
		$this->gui = $gui;
	}

	function load_fortfuehrungsfaelle($ff_auftrag) {
		$auftragsdatei_parts = explode('&', $ff_auftrag->get('auftragsdatei'));
		$file_name = $auftragsdatei_parts[0];
		$original_file_name_parts = explode('=', $auftragsdatei_parts[1]);
		$original_file_name = $original_file_name_parts[1];
		#echo '<br>Lade Datei: ' . $file_name;

		$file = pathinfo($file_name);
		#echo '<br>Prüfe ob Datei: ' . $file['basename'] . ' eine Zip-Datei ist.';
		if (strtolower($file['extension']) == 'zip') {
			#echo '<br>Ja Datei ist eine Zipdatei. Versuche auszupacken.';
			$unziped_files = unzip($file_name, false, false, true);
			foreach ($unziped_files AS $unziped_file) {
				$unziped_file = str_replace('\\', '/', $unziped_file);
				#echo '<br>Verarbeite ausgepackte Datei: ' . $unziped_file;
				$pathinfo_unziped_file = pathinfo($unziped_file);
				if (substr($pathinfo_unziped_file['filename'], -5) == '_2000') {
					$xml_file_name = $file['dirname'] . '/'. $unziped_file;
					$xml = file_get_contents($xml_file_name);
				}

				$rm_file = $file['dirname'] . '/' . $unziped_file;
				#echo '<br>Lösche Datei: ' . $rm_file;
				unlink(str_replace('\\', '/', $file['dirname'] . '/' . $unziped_file));

				if (!empty($pathinfo_unziped_file['dirname'])) {
					$rmdir = $file['dirname'] . '/' . $pathinfo_unziped_file['dirname'];
					#echo '<br>Lösche Verzeichnis: ' . $rmdir;
					@rmdir($rmdir);
				}
			}
			if (empty($xml_file_name)) {
				$err_msg = "Keine Datei mit der Endung _2000 in Zip-Datei gefunden. Prüfen Sie bitte ob Sie die richtige Zip-Datei hochgeladen haben.";
			}
		}
		else {
			$xml_file_name = $file_name;
			$xml = file_get_contents($xml_file_name);
		}

		if (empty($xml_file_name)) {
			$err_msg = "Keine Datei gefunden. Prüfen Sie ob Sie schon eine Datei hochgeladen haben.";
		}
		else {
			#echo '<br>Lade Datei: ' . $xml_file_name;
			$this->loadXML($xml, LIBXML_NOBLANKS);

			# Lese und speicher Attribute zum Auftrag
			$nodes = $this->getElementsByTagName('datumDerAusgabe');
			$node = $nodes[0];
			$ff_auftrag->set('datumderausgabe', $node->nodeValue);

			$nodes = $this->getElementsByTagName('AX_Fortfuehrungsauftrag');
			$auftrag_node = $nodes[0];
			foreach($auftrag_node->childNodes AS $child_node) {
				#echo '<br>node: ' . $child_node->localName;
				$tag = strtolower($child_node->localName);
				if (in_array($tag, array(
					'profilkennung',
					'auftragsnummer',
					'impliziteloeschungderreservierung',
					'verarbeitungsart',
					'geometriebehandlung',
					'mittemporaeremarbeitsbereich', 
					'mitobjektenimfortfuehrungsgebiet',
					'mitfortfuehrungsnachweis'
				))) {
					$ff_auftrag->set($tag, $child_node->nodeValue);
				}
			}

			# Finde Gebäude und deren Anlass
			$this->gebaeude_nodes = $this->getElementsByTagName('AX_Gebaeude');
			if ($this->gebaeude_nodes->length > 0) {
				foreach($this->gebaeude_nodes[0]->childNodes AS $child_node) {
					$tag = strtolower($child_node->localName);
					if ($tag == 'anlass') {
						$ff_auftrag->set('gebaeude', $child_node->nodeValue);
					}
				}
			}

			# speicher Auftrag
			$ff_auftrag->update();

			# Finde Flurstüecke und deren Anlässe
			$this->flst_nodes = $this->getElementsByTagName('AX_Flurstueck');
			$flurstuecke = array();
			foreach($this->flst_nodes AS $flst_node) {
				foreach($flst_node->childNodes AS $child_node) {
					$tag = strtolower($child_node->localName);
					if ($tag == 'flurstueckskennzeichen') {
						$flst['flurstueckskennzeichen'] = $child_node->nodeValue;
					}
					if ($tag == 'anlass') {
						$flst['anlass'] = $child_node->nodeValue;
					}
				}
				$anlaesse[$flst['flurstueckskennzeichen']] = $flst['anlass'];
			}

			# Lösche vorhandene Fälle des Auftrages
			$ff = new Fortfuehrungsfall($this->gui);
			$ff->delete_by('ff_auftrag_id', $ff_auftrag->get('id'));

			# Lege Fälle des Auftrages an
			$this->fall_nodes = $this->getElementsByTagName('AX_Fortfuehrungsfall');
			foreach($this->fall_nodes AS $fall_node) {
				$ff = new Fortfuehrungsfall($this->gui);
				$ff->set('ff_auftrag_id', $ff_auftrag->get('id'));
				foreach($fall_node->childNodes AS $child_node) {
					$tag = strtolower($child_node->localName);
					if (in_array($tag, array(
						'fortfuehrungsfallnummer',
						'laufendenummer',
						'ueberschriftimfortfuehrungsnachweis'
					))) {
						$ff->set($tag, $child_node->nodeValue);
					}
					if (in_array($tag, array(
						'zeigtaufaltesflurstueck',
						'zeigtaufneuesflurstueck'
					))) {
						$ff->set_array($tag, $child_node->nodeValue);
						if ($tag == 'zeigtaufneuesflurstueck') {
							$ff->set_array('anlassarten', $anlaesse[$child_node->nodeValue]);
						}
					}
				}
				$anlassarten = $ff->get('anlassarten');
				if (!empty($anlassarten)) {
					$ff->set('anlassart', $anlassarten[0]);
				}
				$ff->create();
				$this->fortfuehrungsfaelle[] = $ff;
			}
		}
		$result = array(
			'success' => $success,
			'err_msg' => $err_msg,
			'fortfuehrungsfaelle' => $this->fortfuehrungsfaelle
		);
		return $result;
	}

	function nodeToArray( $dom, $node) {
		if(!is_a( $dom, 'DOMDocument' ) || !is_a( $node, 'DOMNode' )) {
			return false;
		}
		$array = false;
		if( empty( trim( $node->localName ))) { // Discard empty nodes
			return false;
		}
		if( XML_TEXT_NODE == $node->nodeType ) {
			return $node->nodeValue;
		}
		foreach ($node->attributes as $attr) {
			$array['@'.$attr->localName] = $attr->nodeValue;
		}
		foreach ($node->childNodes as $childNode) {
			if ( 1 == $childNode->childNodes->length && XML_TEXT_NODE == $childNode->firstChild->nodeType ) {
				$array[$childNode->localName] = $childNode->nodeValue;
			}
			else {
				if( false !== ($a = self::nodeToArray( $dom, $childNode))) {
					$array[$childNode->localName] =	$a;
				}
			}
		}
		return $array; 
	}

}

?>
