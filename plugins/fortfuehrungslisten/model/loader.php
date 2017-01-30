<?php
class NASLoader extends DOMDocument {
	
	static $write_debug = false;
	public $messages = array();
	
	function NASLoader($gui) {
		$gui->debug->show('Create new Object NASLoader', NASLoader::$write_debug);
		$this->gui = $gui;
	}

	function load_fortfuehrungsfaelle($ff_auftrag) {
		$success = true;
		$file_name = $ff_auftrag->get_file_name();
		$original_file_name = $ff_auftrag->get_original_file_name();
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
				$success = false;
				$this->messages[] = array(
					'msg' => "Keine Datei mit der Endung _2000 in Zip-Datei gefunden. Prüfen Sie bitte ob Sie die richtige Zip-Datei hochgeladen haben.",
					'type' => 'error'
				);
			}
		}
		else {
			$xml_file_name = $file_name;
			$xml = file_get_contents($xml_file_name);
		}

		if (empty($xml_file_name)) {
			$success = false;
			$this->messages[] = array(
				'msg' => "Keine Datei gefunden. Prüfen Sie ob Sie schon eine Datei hochgeladen haben.",
				'type' => 'error'
			);
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
				if ($tag == 'antragsnummer') {
					$antragsnummer_datei = $child_node->nodeValue;
				}
			}

			if ($antragsnummer_datei != $ff_auftrag->get('antragsnr')) {
				$success = false;
				$this->messages[] = array(
					'msg' => "Die Antragsnummer in der Auftragsdatei (" . $antragsnummer_datei . ") stimmt nicht mit der Antragsnr im Formular (" . $ff_auftrag->get('antragsnr') . ") überein.<br>Prüfen Sie die Eingabe und die Datei<br>und laden Sie ggf. eine neue Datei hoch!",
					'type' => 'warning'
				);
			}
			else {
				# Suche alle Gemarkungsnummern von Flurstücken raus.
				$this->gemkg_nummern = $this->getElementsByTagName('gemarkungsnummer');
				if ($this->gemkg_nummern->length > 0) {
					foreach($this->gemkg_nummern AS $gemkg_nummer) {
						if ($gemkg_nummer->nodeValue != $ff_auftrag->get('gemkgnr')) {
							$success = ($ff_auftrag->get('an_pruefen') == 't' ? false : true);
							$msg  = "In der Auftragsdatei wurde die Gemarkungsnummer: " . $gemkg_nummer->nodeValue . " gefunden. Diese Nummer stimmt nicht mit der im Formular oben angegebenen Gemarkungsnummer: " . $ff_auftrag->get('gemkgnr') . ' überein.';
							if (!$success) {
								$msg .= "<br>Korrigieren Sie die Gemarkungsnummer im Formular, prüfen Sie ob die Auftragsdatei korrekt ist oder speichern Sie vorher, dass der Datensatz nicht geprüft werden soll.";
							}
							$this->messages[] = array(
								'msg' => $msg,
								'type' => ($ff_auftrag->get('an_pruefen') == 't' ? 'error' : 'warning')
							);
							break;
						}
					}
				}

				if ($success) {
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

							if ($tag == 'zeigtaufaltesflurstueck') {
								$ff->set_array($tag, $child_node->nodeValue);
							}
							if ($tag == 'zeigtaufneuesflurstueck') {
								# Speichert neues Flurstück nur, wenn es nicht mit altem übereinstimmt
								if ($child_node->nodeValue != $ff->get('zeigtaufaltesflurstueck')[0]) {
									# Ab 2017 prüfe ob es eine höhere Flurstücksnummer in ALKIS gibt als die, die eingetragen werden soll
									if ($ff_auftrag->get('jahr') > 2016) {
										$result = $this->is_last_nenner($child_node->nodeValue, $ff->get('fortfuehrungsfallnummer'));
										if ($result['success']) {
											if (!empty($result['bigger_kennz'])) {
												$this->messages[] = array(
													'msg' => $result['msg'],
													'type' => 'warning'
												);
											}
										}
										else {
											$this->messages[] = array(
												'msg' => $result['msg'],
												'type' => 'error'
											);
										}
									}

									$ff->set_array('anlassarten', $anlaesse[$child_node->nodeValue]);
									$ff->set_array($tag, $child_node->nodeValue);
								}
								else {
									$this->messages[] = array(
										'msg' => 'Im Fortführungsfall Nr.: ' . $ff->get('fortfuehrungsfallnummer') . ' ist die alte Flurstücksnummer:<br>' . $ff->get('zeigtaufaltesflurstueck')[0] . ' identisch mit der neuen Nummer.<br>In dem Fall wird die neue Nummer nicht gespeichert.',
										'type' => 'warning'
									);
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
			}
		}

		$result = array(
			'success' => $success,
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

	/*
	* Diese Funktion liefert ein Array mit success = true wenn das im flurstueckskennzeichen übergebene Flurstück von der
	* zählweise her das letzte Flurstück ist. Ansonsten ist success = false und bigger_kennz enthält ein Array aller
	* flurstueckskennzeichen, die eine höhere Nummerierung haben und in msg eine Message, die das beschreibt.
	* Tritt ein Fehler bei der Abfrag auf ist success auch  false und msg enthält eine Fehlermeldung.
	* @param $flurstueckskennzeichen String
	* @return Array('success', 'msg', 'bigger_kennz')
	*/
	function is_last_nenner($flurstueckskennzeichen, $fortfuehrungsfallnummer) {
		$success = true;
		$msg = '';
		$bigger_kennz = array();

		$bis_zahler = substr($flurstueckskennzeichen, 0, 14); 
		$sql = "
			SELECT
				flurstueckskennzeichen
			FROM
				alkis.ax_flurstueck
			WHERE
				flurstueckskennzeichen like '{$bis_zahler}%' AND
				flurstueckskennzeichen > '{$flurstueckskennzeichen}'
			LIMIT 1
		";
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 0, true);

		if ($ret[0]) {
			$success = false;
			$msg = 'Fehler bei der Abfrage der Datenbank in SQL-Statement:<br>' . $sql;
		}
		else {
			$num_rows = pg_num_rows($ret[1]);
			if ($num_rows > 0) {
				$bigger_kennz = array_map(
					function($row) {
						return $row['flurstueckskennzeichen'];
					},
					pg_fetch_all($ret[1])
				);
				$msg = 'Im Fortführungsfall Nr.: ' . $fortfuehrungsfallnummer . ' hat das Flurstück: ' . $flurstueckskennzeichen . ' einen kleineren Nenner als folgende Flurstücke aus ALKIS: ' . implode(', ', $bigger_kennz);
			}
		}
		return array(
			'success' => $success,
			'msg' => $msg,
			'bigger_kennz' => $bigger_kennz
		);
	}

}

?>
