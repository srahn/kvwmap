<?php
class NASLoader extends DOMDocument {
	
	static $write_debug = false;
	
	function NASLoader($gui) {
		$gui->debug->show('Create new Object NASLoader', NASLoader::$write_debug);
		$this->gui = $gui;
	}

	function load_fortfuehrungsfaelle($ff_auftrag) {
		$auftragsdatei_parts = explode('&', $ff_auftrag->get('auftragsdatei'));
		$xml = file_get_contents($auftragsdatei_parts[0]);
		$this->loadXML($xml, LIBXML_NOBLANKS);

		# Lese und speicher Attribute zum Auftrag
		$nodes = $this->getElementsByTagName('datumDerAusgabe');
		$node = $nodes[0];
		$ff_auftrag->set('datumderausgabe', $node->nodeValue);

		$nodes = $this->getElementsByTagName('AX_Fortfuehrungsauftrag');
		$auftrag_node = $nodes[0];
		foreach($auftrag_node->childNodes AS $child_node) {
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
		$ff_auftrag->update();

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
				}
			}
			$ff->create();
			$this->fortfuehrungsfaelle[] = $ff;
		}
		return $this->fortfuehrungsfaelle;
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
