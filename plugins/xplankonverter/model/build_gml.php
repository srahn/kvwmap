<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2004  Peter Korduan                               #
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
#############################
# Klasse Gml_builder #
#############################

class Gml_builder {

	static $XPLANKONVERTER_NON_XPLAN_FIELDS = array(
		"konvertierung_id",
		"created_at",
		"updated_at"
	);

	function __construct($database) {
		global $debug;
		$this->debug = $debug;
		$this->database = $database;
		$this->typeInfo = new TypeInfo($database);
		$this->errmsg = '';

		$this->tmpFile = tmpfile();
	}

	function __destruct() {
		fclose($this->tmpFile);
	}

	/*
	* Diese Funktion erzeugt einen gml-Text aus den
	* der Konvertierung und dem Plan zugeordneten Einträge
	* des GML-Mappings. Aufgrund der Größe der entstehenden
	* GML-Texte wird deren Inhalt in eine temporäre Datei
	* geschrieben. Die temporäre Datei wird vom Gml_builder
	* Objekt verwaltet.
	* Mit der Funktion save($path) kann die temporäre Datei in
	* eine Zieldatei gespeichert werden
	*/
	function build_gml($konvertierung, $plan) {
		# set encoding to UTF-8
		$old_encoding = mb_internal_encoding();
		mb_internal_encoding('UTF-8');

		# clear tempfile
		ftruncate($this->tmpFile, 0);

		$xplan_ns_prefix = XPLAN_NS_PREFIX ? XPLAN_NS_PREFIX.':' : '';

		// plan muss nochmal abgerufen werden, weil die select-Anweisung nochmal ergänzt werden musste
		$plan->select = "
			*,
			ST_AsGML(
				3,
				ST_Reverse(
					ST_Transform(
						raeumlichergeltungsbereich,
						{$konvertierung->get('output_epsg')}
					)
				),
				{$konvertierung->get('geom_precision')},
				0,
				null,
				'GML_' || gml_id::text || '_geom'
			) AS gml_raeumlichergeltungsbereich,
			ST_AsGML(
				3,
				ST_Transform(
					raeumlichergeltungsbereich,
					{$konvertierung->get('output_epsg')}
				),
				{$konvertierung->get('geom_precision')},
				32,
				null,
				'GML_' || gml_id::text || '_envelope'
			) AS envelope
		";
		$plan->find_by('konvertierung_id', $konvertierung->get('id'));
		$plan->filter_nurzurauslegung($this);

		# XPlan XSD's sind derzeit unter: http://xplan-raumordnung.de/devk/model/2016-05-06_XSD/ hinterlegt
		fwrite(
		$this->tmpFile,
			"<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>\n" .
			"<XPlanAuszug gml:id=\"GML_{$plan->data['gml_id']}_auszug\"\n" .
			"  xmlns=\"".XPLAN_NS_URI."\"\n" .
			"  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n" .
			"  xmlns:wfs=\"http://www.opengis.net/wfs\"\n" .
			"  xmlns:gml=\"http://www.opengis.net/gml/3.2\"\n" .
			"  xmlns:xlink=\"http://www.w3.org/1999/xlink\"\n" .
			(XPLAN_NS_PREFIX==""
				? ""
				: "  xmlns:".XPLAN_NS_PREFIX."=\"".XPLAN_NS_URI."\"\n").
					"  xsi:schemaLocation=\"".XPLAN_NS_URI." ".XPLAN_NS_SCHEMA_LOCATION."\"\n" .
			">\n" .

			# TODO: <boundedBy> sollte für jeden Plan definiert oder festgehalten werden,
			# z.B. über Informationen in XP_Plan:raeumlicherGeltungsbereich oder eine
			# Erfassung vor der Konvertierung.
			$this->formatXML($this->wrapWithElement('gml:boundedBy', $plan->get('envelope')))
		);

		// das Plan Element anlegen
		$gmlElemInner = "";
		$gmlElemOpenTag = "<{$xplan_ns_prefix}{$plan->umlName}";
		$gmlElemOpenTag .= " gml:id=\"GML_{$plan->data['gml_id']}\"";

		// fetch information about attributes and their properties
		//$typeInfo = new TypeInfo($this->database);
		$plan_attribs = $this->typeInfo->getInfo($plan->tableName);

		// Entfernt ,t oder ,f oder nur , am Ende: {"(,,Dokument,,A,,,X,,1000,f)","(,,Dokument,,X,,,A,,5000,t)"} => {"(,,Dokument,,A,,,X,,1000)","(,,Dokument,,X,,,A,,5000)"}
		$plan->data['externereferenz'] = str_replace(array(',)"', ',f)"', ',t)"'), ')"', $plan->data['externereferenz']);
		// foreach ($plan_attribs AS $i => $plan_attribut) {
		// 	if ($plan_attribut['type'] == 'xp_spezexternereferenzauslegung' AND $plan_attribut['type_type'] == 'c') {
		// 		$plan_attribs[$i]['type'] = 'xp_spezexternereferenz';
		// 	}
		// };

		// alle Attribute vom Planobjekt ausgeben
		$gmlElemInner .= $this->generateGmlForAttributes($plan->data, $plan_attribs, XPLAN_MAX_NESTING_DEPTH);

		$xplan_gml = $gmlElemOpenTag . ">" . $gmlElemInner;

		// alle Bereiche suchen, die zur Konvertierung gehören
		// TODO: Ist es sinnvoller die Bereiche abzufragen, die dem Plan
		// zugeordnet sind, oder ist es sauberer die Bereiche nach Ihrer
		// Zugehörigkeit zur Konvertierung auszuwählen?
		$sql = "
			SELECT
				b.*,
				ST_AsGML(
					3,
					ST_Reverse(
						ST_Transform(
							b.geltungsbereich,
							{$konvertierung->get('output_epsg')}
						)
					),
					{$konvertierung->get('geom_precision')},
					0,
					null,
					'GML_' || b.gml_id::text || '_geom'
				) AS gml_geltungsbereich,
				ST_AsGML(
					3,
					ST_Transform(
						b.geltungsbereich,
						{$konvertierung->get('output_epsg')}
					),
					{$konvertierung->get('geom_precision')},
					32,
					null,
					'GML_' || b.gml_id::text || '_envelope'
				) AS envelope
			FROM
				" . XPLANKONVERTER_CONTENT_SCHEMA . "." . $plan->bereichTableName . " b JOIN
				" . XPLANKONVERTER_CONTENT_SCHEMA . "." . $plan->tableName . " p ON (b.gehoertzuplan::text = p.gml_id::text)
			WHERE
				p.konvertierung_id = " .$konvertierung->get('id') . " 
		";
		#echo $sql."\n";
		$bereiche = pg_query($this->database->dbConn, $sql);

		# iterating bereiche in two passes:
		# first pass: complete Plan element by iteratively inserting
		# xlink-references to each Bereich element
		while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
			$xplan_gml .= "<{$xplan_ns_prefix}bereich xlink:href=\"#GML_" . $bereich['gml_id'] . "\"/>";
		}
		# close and write Plan element
		fwrite($this->tmpFile, $this->formatXML($this->wrapWithFeatureMember($xplan_gml . "</{$xplan_ns_prefix}{$plan->umlName}>")));

		#
		# second pass: iteratively building and writing gml for each Bereich element
		#
		// fetch information about attributes and their properties
		$bereich_attribs = $this->typeInfo->getInfo($plan->planartAbk . '_bereich', false);
		$xp_bereich_attribs = $this->typeInfo->getInfo('xp_bereich', false);

		pg_result_seek($bereiche, 0);
		while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
			$gmlElemOpenTag = "<{$xplan_ns_prefix}{$plan->bereichUmlName}";
			$gmlElemOpenTag .= " gml:id=\"GML_{$bereich['gml_id']}\"";
			$bereich_gml = $gmlElemOpenTag . ">";

			// alle von XP_Bereich geerbten Attribute ausgeben
			$bereich_gml .= $this->generateGmlForAttributes($bereich, $xp_bereich_attribs, XPLAN_MAX_NESTING_DEPTH);

			// alle gml_ids von Fachobjekten finden die mit dem Bereich verknüpft sind
			// und im Bereich Element verlinken
			$sql = "
				SELECT
					gml_id
				FROM
					" . XPLANKONVERTER_CONTENT_SCHEMA . ".xp_objekt
				WHERE
					gehoertzubereich = $1
			";
			#echo '<br>Sql: ' . $sql;
			$xp_objekte = pg_query_params($this->database->dbConn, $sql, array($bereich['gml_id']));
			# complete Bereich element by iteratively inserting
			# xlink-references to each associated Objekt element
			while ($xp_objekt = pg_fetch_array($xp_objekte, NULL, PGSQL_ASSOC)) {
				$bereich_gml .= "<{$xplan_ns_prefix}planinhalt xlink:href=\"#GML_" . $xp_objekt['gml_id'] . "\"/>";
			}
			// die übrigen in Bereich definierten Attribute ausgeben
			$bereich_gml .= $this->generateGmlForAttributes($bereich, $bereich_attribs, XPLAN_MAX_NESTING_DEPTH);

			// Rueckbezug zu Plan
			$bereich_gml .= "<{$xplan_ns_prefix}gehoertZuPlan xlink:href=\"#GML_{$bereich['gehoertzuplan']}\"/>";

			# close and write Bereich element
			fwrite($this->tmpFile, "\n" . $this->formatXML($this->wrapWithFeatureMember($bereich_gml . "</{$xplan_ns_prefix}{$plan->bereichUmlName}>")));
		}

		// und nun alle Fachobjekte generieren
		// dazu alle Fachobjektklassen finden die mit der Konvertierung verknüpft sind
		$class_names = $konvertierung->get_class_names();
		# zu jeder Objektklassse die Objekte holen, die mit der Konvertierung verknüpft sind
		foreach ($class_names AS $class_name) {
			$object = new XP_Object($konvertierung, $class_name);
			$object_rows = $object->get_object_rows();

			// fetch information about attributes and their properties
			$objekt_attribs = $this->typeInfo->getInfo($class_name);

			foreach ($object_rows AS $object_row) {
				# element anlegen und gml_id als attribut eintragen
				$objekt_gml = "<{$xplan_ns_prefix}{$class_name} gml:id=\"GML_{$object_row['gml_id']}\">";


				# alle uebrigen Attribute ausgeben
				$objekt_gml .= $this->generateGmlForAttributes($object_row, $objekt_attribs, XPLAN_MAX_NESTING_DEPTH);
				# close and write FeatureMember
				$objekt_gml .= "</{$xplan_ns_prefix}{$class_name}>";

				fwrite($this->tmpFile, "\n" . $this->formatXML($this->wrapWithFeatureMember($objekt_gml)));
			}
		}

		// for textabschnitte
		//checks if any exist first

		$textabschnitte_names = $konvertierung->get_textabschnitt_names();
		//$textabschnitte_names = array("BP_TextAbschnitt", "FP_TextAbschnitt", "RP_TextAbschnitt", "SO_TextAbschnitt");
		foreach($textabschnitte_names AS $textabschnitt_name) {
			$object = new XP_Object($konvertierung, $textabschnitt_name);
			$object_rows = $object->get_textabschnitt_rows();
			$objekt_attribs = $this->typeInfo->getInfo($textabschnitt_name);
			foreach ($object_rows AS $object_row) {
					# element anlegen und gml_id als attribut eintragen
					$objekt_gml = "<{$xplan_ns_prefix}{$textabschnitt_name} gml:id=\"GML_{$object_row['gml_id']}\">";


					# alle uebrigen Attribute ausgeben
					$objekt_gml .= $this->generateGmlForAttributes($object_row, $objekt_attribs, XPLAN_MAX_NESTING_DEPTH);
					# close and write FeatureMember
					$objekt_gml .= "</{$xplan_ns_prefix}{$textabschnitt_name}>";

					fwrite($this->tmpFile, "\n" . $this->formatXML($this->wrapWithFeatureMember($objekt_gml)));
				}
		}

		# close XPlanAuszug
		fwrite($this->tmpFile, "\n</XPlanAuszug>");

		# reset internal string encoding
		mb_internal_encoding($old_encoding);
		return true;
	}

	function wrapWithElement($tag,$inner) {
		return "<$tag>$inner</$tag>";
	}

	function wrapWithElementAndAttribute($tag, $inner, $attributename, $attributevalue) {
		$str = "<" . $tag . " " . $attributename . '="' . $attributevalue . '">' . $inner . "</" . $tag . ">";
		return $str;
	}

	function wrapWithFeatureMember($inner) {
		return $this->wrapWithElement("gml:featureMember",$inner);
	}

	function generateGmlForAttributes($gml_object, $uml_attribute_info, $depth) {
		$this->err_msg = '';
		if (($depth) < 0) {
			return '';
		}
		$xplan_ns_prefix = XPLAN_NS_PREFIX ? XPLAN_NS_PREFIX.':' : '';
		$gmlStr = '';
		$sequence_attr = 0;

		foreach ($uml_attribute_info as $uml_attribute) {
			# Rueckverweise auf etwaige Bereiche hinzufügen
			# index 10 is the current position (XPlanGML 5.0.1) for gehoertzubereich association to be implemented
			# the association index for XP_AbstraktesPraesentationsobjekt is 3 for gehoertzubereich
			# the association index can be found in the XSD-sequence of the attribute (count starts at 0)
			# TODO Make this more generic to reflect possible changes in index and support all associations
			# Might need a change in xplan_uml generation to contain association sequenceorder/index
			# TODO This might not work for XP_Praesentationsobjekt, as it has no attributes on its own and therefore would not have a sequence 4
			
			# for XP_AbstraktesPraesentationsobjekt and children
			$abstraktes_praesentationsobjekt_children = array('XP_Praesentationsobjekt','XP_PPO','XP_FPO','XP_LPO','XP_TPO');
			$prefixes_or_signs_to_remove = array('#','GML_','Gml_','gml_','{','}');
			
			if ($sequence_attr == 4 && in_array($uml_attribute['origin'], $abstraktes_praesentationsobjekt_children)) {
				$aggregated_bereiche_gml_ids = explode(',', str_replace($prefixes_or_signs_to_remove, '', $gml_object['bereiche_gml_ids']));
				// entnimmt mögliche doppelte Werte
				$aggregated_bereiche_gml_ids = array_unique($aggregated_bereiche_gml_ids);
				foreach ($aggregated_bereiche_gml_ids as $bereich_gml_id) {
					if (!empty($bereich_gml_id)) {
						$gmlStr .= "<{$xplan_ns_prefix}gehoertZuBereich xlink:href=\"#GML_{$bereich_gml_id}\"/>";
					}
				}
			}
			# for XP_Objekt and children
			# XP_Objekt has index elements after association so can always work with parent object
			if ($sequence_attr == 10 && $uml_attribute['origin'] == 'XP_Objekt') {
				$aggregated_bereiche_gml_ids = explode(',', str_replace($prefixes_or_signs_to_remove, '', $gml_object['bereiche_gml_ids']));
				// entnimmt mögliche doppelte Werte
				$aggregated_bereiche_gml_ids = array_unique($aggregated_bereiche_gml_ids);
				foreach ($aggregated_bereiche_gml_ids as $bereich_gml_id) {
					if (!empty($bereich_gml_id)) {
						$gmlStr .= "<{$xplan_ns_prefix}gehoertZuBereich xlink:href=\"#GML_{$bereich_gml_id}\"/>";
					}
				}
			}

			# Association wirdDargestelltDurch
			# has to be below gehoertzubereich according to XSD
			if ($sequence_attr == 10 && $uml_attribute['origin'] == 'XP_Objekt') {
				$aggregated_praesentationsobjekte_gml_ids = explode(',', str_replace($prefixes_or_signs_to_remove, '', $gml_object['wirddargestelltdurch']));
				// entnimmt mögliche doppelte Werte
				$aggregated_praesentationsobjekte_gml_ids = array_unique($aggregated_praesentationsobjekte_gml_ids);
				foreach ($aggregated_praesentationsobjekte_gml_ids as $praesentationsobjekt_gml_id) {
					if (!empty($praesentationsobjekt_gml_id)) {
						$gmlStr .= "<{$xplan_ns_prefix}wirdDargestelltDurch xlink:href=\"#GML_{$praesentationsobjekt_gml_id}\"/>";
					}
				}
			}
			# Association dientZurDarstellungVon
			# has to be below gehoertzubereich according to XSD
			if ($sequence_attr == 4 && in_array($uml_attribute['origin'], $abstraktes_praesentationsobjekt_children)) {
				$aggregated_objekte_gml_ids = explode(',', str_replace($prefixes_or_signs_to_remove, '', $gml_object['dientzurdarstellungvon']));
				// entnimmt mögliche doppelte Werte
				$aggregated_objekte_gml_ids = array_unique($aggregated_objekte_gml_ids);
				foreach ($aggregated_objekte_gml_ids as $objekt_gml_id) {
					if (!empty($objekt_gml_id)) {
						$gmlStr .= "<{$xplan_ns_prefix}dientZurDarstellungVon xlink:href=\"#GML_{$objekt_gml_id}\"/>";
					}
				}
			}

			# Association refTextInhalt (always +12 for XP_Objekt ( in XPlanung 5.4)
			# Posisition 2 after rechtscharakter in BP_Objekt
			# Posisition 4 after rechtscharakter, spezifischePraegung, vonGenehmigungAusgenommen in FP_Objekt
			# Posisition 3 after rechtscharakter, sonstRechtscharakter in SO_Objekt
			# Posisition 7 after rechtscharakter, konkretisierung,g ebietstyp, kuestenmeer, bedeutsamkeit, ist zweckbindung in RP_Objekt
			if (!empty($gml_object['reftextinhalt']) &&
				(($sequence_attr == 14 && substr($uml_attribute['origin'],0,2) == 'BP') ||
				 ($sequence_attr == 16 && substr($uml_attribute['origin'],0,2) == 'FP') ||
				 ($sequence_attr == 15 && substr($uml_attribute['origin'],0,2) == 'RP') ||
				 ($sequence_attr == 19 && substr($uml_attribute['origin'],0,2) == 'SO'))
			) {
				$gml_object_reftextinhalt = str_replace(array('#','GML_','Gml_','gml_'), '', $gml_object['reftextinhalt']);
				$gmlStr .= "<{$xplan_ns_prefix}refTextInhalt xlink:href=\"#GML_" . $gml_object_reftextinhalt . "\"/>";
			}

			#$gmlStr .= "<note>attribut sequenznummer: " . $sequence_attr ."</note>";
			$sequence_attr++;
			// leere Felder auslassen
			if ($gml_object[$uml_attribute['col_name']] == '' OR $gml_object[$uml_attribute['col_name']] == '{}') {
				continue;
			}

			// special arraycheck as some attributes with same name can appear twice in same class due to unknown hierarchical depth
			// only known example currently (xplan 5.1) is: 
			// XP_Plan: <xplan:name>  and --><xplan:plangeber>--><xplan:XP_Plangeber>--><xplan:name>
			// plangeber sub-elements has the field kennziffer, which doesn't exist on plan-hierarchy-level
			// TODO consider solving this in a generic fashion
			if (is_array($gml_object[$uml_attribute['col_name']]) and array_key_exists('kennziffer', $gml_object)) {
				$gml_object[$uml_attribute['col_name']] = $gml_object[$uml_attribute['col_name']][0];
			}

			#$gmlStr .= '<note>attributname: ' . $uml_attribute['name'] . ' type_type: ' . $uml_attribute['type_type'] . ' stereotype: ' . $uml_attribute['stereotype'] . ' uml-attribute-type: '. $uml_attribute['type'] . ' uml-attribute-datatype: '. $uml_attribute['uml_dtype'] . '</note>';
			switch ($uml_attribute['type_type']) {
				case 'c': // custom datatype
					switch ($uml_attribute['stereotype']){
						case NULL:
							break;
						case "CodeList":
							$gml_value_array = is_array($gml_object[$uml_attribute['col_name']])
								? $gml_object[$uml_attribute['col_name']]
								: explode(',',substr($gml_object[$uml_attribute['col_name']], 1, -1));
							//ltrim in case of array field, e.g. zweckbestimmung[*] that may also exist as non-array field e.g. zweckbestimmung[1]
							$codeSpaceUri = ltrim($gml_value_array[0], '"(');
							$code_value = $gml_value_array[1];
							if(!empty($codeSpaceUri) || !empty($code_value)) {
								$gmlStr .= "<{$xplan_ns_prefix}{$uml_attribute['uml_name']} codeSpace=\"$codeSpaceUri\">$code_value</{$xplan_ns_prefix}{$uml_attribute['uml_name']}>";
							}
							break;
						case "DataType" :
							$gml_attrib_str = '';
							// check whether attribute value is already parsed into an array
							if (is_array($gml_object[$uml_attribute['col_name']])) {
								$value_array = $gml_object[$uml_attribute['col_name']];
							}
							else { // parse attribute value if not yet done
								$value_array = $this->parseCompositeDataType($gml_object[$uml_attribute['col_name']]);
							}
							// fetch information about attributes and their properties
							$datatype_attribs = $this->typeInfo->getInfo($uml_attribute['type']);

							// retrieve attribute names
							$value_array_keys = array_column($datatype_attribs, 'col_name');

							// Adds an extra Association for XP_VerbundenerPlan as they are not present with sequences in xplan_uml
							if($uml_attribute['col_name'] == 'aendert') {
								array_push($value_array_keys, 'verbundenerplan');
							}
							if($uml_attribute['col_name'] == 'wurdegeaendertvon') {
								array_push($value_array_keys, 'verbundenerplan');
							}

							// wrap singular values into an array in order to
							// unify the processing of singular and multiple values
							if (!$uml_attribute['is_array']) {
								$aux_array = array(0 => $value_array);
								$value_array = $aux_array;
							}
							// leere Datentypen auslassen
							if (!$value_array) {
								break;
							}
							// process composite data type
							foreach ($value_array as $single_value) {
								// associate values with attribute names
								#$gmlStr .= '<note>single_value: ' . implode(', ', $single_value) . '</note>';
								#$gmlStr .= '<note>value_array_keys: ' . implode(', ', $value_array_keys) . '</note>';
								// Null-Array-Werte auslassen
								if ($single_value == null) {
									continue;
								}
								$single_value = array_combine($value_array_keys, $single_value);
								// generate GML output (!!! recursive !!!)
								$gml_attrib_str .= $this->generateGmlForAttributes($single_value, $datatype_attribs, $depth - 1);
								// leere Datentypen auslassen
								if (strlen($gml_attrib_str) == 0) break;
								$typeElementName = end($datatype_attribs)['origin'];
								$gmlStr .= $this->wrapWithElement(
								"{$xplan_ns_prefix}{$uml_attribute['uml_name']}",

								// wrap all data-types with their data-type-element-tag
								$this->wrapWithElement("{$xplan_ns_prefix}{$typeElementName}", $gml_attrib_str));
								$gml_attrib_str = '';
							}
							break;
						default:
					}
					break;
				case 'b': // built-in datatype
					switch ($uml_attribute['type']) {
						case 'geometry' : {
							$gml_value = $gml_object['gml_'.$uml_attribute['col_name']];
							// unify gml_ids by appending a sequential number to the id
							$seq_number = 0; $unified_gml = "";
							$haystack = $gml_value;
							while (!empty($haystack)) {
								$currpos = strpos($haystack, "_geom");
								if (!$currpos) break;
								$unified_gml .= substr($haystack, 0, $currpos);
								$unified_gml .= "_geom_". $seq_number++;
								$currpos = strpos($haystack, '"', $currpos);
								if (substr($haystack, $currpos + 1, 1) == '"') {
									$currpos++;
								}
								if (!$currpos) { // should never happen!!!
									$currpos += 5;
									$unified_gml .= '"';
									continue;
								}
								$haystack = substr($haystack, $currpos);
							}
							$unified_gml .= $haystack;
							#echo $unified_str . "\n";
							$gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}", $unified_gml);
						} break;
						case 'date' : {
							$gml_value = $gml_object[$uml_attribute['col_name']];
							// check for array values
							if($gml_value[0] == '{' && substr ($gml_value,-1) == '}') {
								$gml_value_array = explode(',', substr($gml_value,1,-1));
								for ($j = 0; $j < count($gml_value_array); $j++) {
									$timestamp = strtotime($gml_value_array[$j]);
									if (!$timestamp) {
										$this->errmsg = "Ungueltige Datumsangabe im Attribut " . $uml_attribute['col_name'] . ": " . $gml_value_array[$j];
										break;
									}
									$iso_date_str = date("Y-m-d", $timestamp);
									$gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}", $iso_date_str);
								}
							} else {
								$timestamp = strtotime($gml_value);
								if (!$timestamp) {
									$this->errmsg = "Ungueltige Datumsangabe im Attribut " . $uml_attribute['col_name'] . ": " . $gml_value;
									break;
								}
								$iso_date_str = date("Y-m-d", $timestamp);
								$gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}", $iso_date_str);
							}
						} break;
						case 'bool' : {
							switch ($gml_object[$uml_attribute['col_name']]) {
								case 't' : $value = 'true'; break;
								case 'f' : $value = 'false'; break;
								default : $value = '';
							};
							$gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}", $value);
						} break;
						case 'float8': {
							// Handles float values for angles, volume, area,length, decimal. Angle, volume, area, length elements need an XML uom-attribute, decimal has no uom-attribute
							// values are defined by Konformitaetsbedingung 2.1.2 (%, grad, m3, m2, m, (decimal does not have uom Attribute and is not specified: here, also m))
							$xml_attributename = "uom";
								switch ($uml_attribute['uml_dtype']) {
									case 'Angle':	{$xml_attributevalue = "grad";} break;
									case 'Volume':	{$xml_attributevalue = "m3";} break;
									case 'Area': 	{$xml_attributevalue = "m2";} break;
									case 'Length':	{$xml_attributevalue = "m";} break;
									//case 'Decimal':	{$xml_attributevalue = "m";} break;
									default: 		{$xml_attributevalue = "m";} break;
								}
							$gml_value = trim($gml_object[$uml_attribute['col_name']]);
							// check for array values
							if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
								$gml_value_array = explode(',',substr($gml_value, 1, -1));
								for ($j = 0; $j < count($gml_value_array); $j++){
									$gmlStr .= $this->wrapWithElementAndAttribute(
										"{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
										htmlspecialchars($gml_value_array[$j],ENT_QUOTES|ENT_XML1,"UTF-8"),
										$xml_attributename,
										$xml_attributevalue
									);
								}
							} else {
								// no uom here
								if($uml_attribute['uml_dtype'] == 'Decimal') {
									$gmlStr .= $this->wrapWithElement(
									"{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
									htmlspecialchars($gml_value,ENT_QUOTES|ENT_XML1,"UTF-8"));
								} else {
									$gmlStr .= $this->wrapWithElementAndAttribute(
										"{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
										htmlspecialchars($gml_value,ENT_QUOTES|ENT_XML1,"UTF-8"),
										$xml_attributename,
										$xml_attributevalue
									);
								}
							}
						} break;
						default: {
							$gml_value = trim($gml_object[$uml_attribute['col_name']]);
							 // check for array values
							if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
								$gml_value_array = explode(',',substr($gml_value, 1, -1));
								for ($j = 0; $j < count($gml_value_array); $j++){
									$gmlStr .= $this->wrapWithElement(
									"{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
									htmlspecialchars($gml_value_array[$j],ENT_QUOTES|ENT_XML1|ENT_SUBSTITUTE,"UTF-8"));
								}
							} else {
								$gmlStr .= $this->wrapWithElement(
								"{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
								htmlspecialchars($gml_value,ENT_QUOTES|ENT_XML1|ENT_SUBSTITUTE,"UTF-8"));
							}
						}
					} break;

				case 'e': // enum type
				default: {
					$gml_value = trim($gml_object[$uml_attribute['col_name']]);
					// check for array values
					if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
						$gml_value_array = explode(',',substr($gml_value, 1, -1));
						for ($j = 0; $j < count($gml_value_array); $j++){
							$gmlStr .= $this->wrapWithElement(
								"{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
								htmlspecialchars($gml_value_array[$j],ENT_QUOTES|ENT_XML1,"UTF-8"));
							}
						} else
							$gmlStr .= $this->wrapWithElement(
								"{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
								htmlspecialchars($gml_value,ENT_QUOTES|ENT_XML1,"UTF-8")
							);
					}
			}
		}

		# Workaround for association verbundener Plan
		# TODO Make this more generic to reflect possible changes in index and support all associations
		if(array_key_exists('verbundenerplan', $gml_object)) {
				# Trim prefix in different variants if exists
				if(!empty($gml_object['verbundenerplan'])) {
					$gml_object_verbundenerplan = str_replace(array('#','GML_','Gml_','gml_'), '', $gml_object['verbundenerplan']);
					$gmlStr .= "<{$xplan_ns_prefix}verbundenerPlan xlink:href=\"#GML_" . $gml_object_verbundenerplan . "\"/>";
				}
		}
		return $gmlStr;
	}

  /*
  * Diese Funktion speichert den Inhalt der temporären Datei
  * an einen gegebenen Pfad im Dateisystem. Die temporäre Datei
  * bleibt bestehen, und kann mehrfach gespeichert werden. Die
  * Freigabe/Löschung der temporären Date wird vom Gml_builder
  * Objekt verwaltet.
  */
  function save($path){
    rewind($this->tmpFile);
    $file = fopen($path,'w');

    stream_copy_to_stream($this->tmpFile,$file);

    fclose($file);
  }

  /*
  * Diese Funktion formatiert den Inhalt des übergebenen XML-Strings
  * und gibt den formatierten String zurück.
  */
  function formatXML($unformattedXMLString){
    // Quelle: http://www.daveperrett.com/articles/2007/04/05/format-xml-with-php/
    $unformattedXMLString = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $unformattedXMLString);
    $token      = strtok($unformattedXMLString, "\n");
    $result     = '';
    $pad        = 0;
    $matches    = array();
    while ($token !== false) :
        if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
          $indent=0;
        elseif (preg_match('/^<\/\w/', $token, $matches)) :
          $pad-=2;
          $indent = 0;
        elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
          $indent=2;
        else :
          $indent = 0;
        endif;
        $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
        $result .= $line . "\n";
        $token   = strtok("\n");
        $pad    += $indent;
    endwhile;
    return $result;
  }

	/*
	* Diese Funktion parses den Inhalt des übergebenen
	* Query-Results für Spalten vom Typ Composite Datatype
	* und gibt die Werte in einem (ggf. verschachtelten)
	* Array zurück.
	*/
	function parseCompositeDataType($data_string) {
		// First replace commas within strings (delimited by \" with an uncommon delimiter
		// Also make sure to not use codelist-types (also delimited by "/ but followed with a ( when opening or prefixed with a ) when opening
		$char_array = str_split($data_string);
		for($i = 0; $i < count($char_array); $i++) {
			switch ($char_array[$i]) {
				case '\\':
					if ($char_array[$i + 1] == '"' and ($char_array[$i + 2] != '(' or $char_array[$i - 1] != ')')) {
						$component_is_string = !$component_is_string;
					}
					break;
				case ',':
					if ($component_is_string and $char_array[$i + 1] != '\\') {
						// Replaces the string pos with an alternate delimiter in the original string
						$data_string[$i] = '|';
					}
					break;
				case '(':
					if ($component_is_string) {
						// Replaces the string pos with an alternate delimiter in the original string
						$data_string[$i] = '^';
					}
					break;
				case ')':
					if ($component_is_string) {
						// Replaces the string pos with an alternate delimiter in the original string
						$data_string[$i] = '$';
					}
					break;
				default:
					break;
			}
		}
		$value_array = array();
		$stack = array();
		$curr_value = '';
		// prepare data string
		$value_str = substr($data_string, 1, -1);
		// Kommata innerhalb Text werden derzeit über die Konverteroberflaeche nicht zugelassen,
		//d.h. \", oder ,\" innerhalb eines CompositeDataTypes ist derzeit nicht moeglich
		$value_str = str_replace('\"(', '(', $value_str);
		$value_str = str_replace(')\"', ')', $value_str);
		$value_str = str_replace('(\"','(', $value_str);
		$value_str = str_replace('\")',')', $value_str);
		$value_str = str_replace(',\"',',', $value_str);
		$value_str = str_replace('\",',',', $value_str);
		$value_str = str_replace('\"', '&quot;', $value_str);
		$value_str = str_replace('"', '', $value_str);
		// return if no data
		if (strlen($value_str) == 0) return;
		// cast string to array
		$value_str_array = str_split($value_str);
		// parse character by character
		foreach ($value_str_array as $char) {
			switch ($char) {
				case '(':
					array_push($stack,$value_array);
					$value_array = array();
					break;
				case ')':
					$value_array[] = $curr_value;
					$curr_value = $value_array;
					$value_array = array_pop($stack);
					break;
				case ',':
					$value_array[] = $curr_value;
					$curr_value = '';
					break;
				default:
					$curr_value .= $char;
			}
		}
		$value_array[] = $curr_value;

		// Revert pipe escape for commas within strings
		$value_array_2 = array();
		foreach($value_array as $value_new) {
			$value_new = str_replace('|',',', $value_new);
			$value_new = str_replace('^','(', $value_new);
			$value_new = str_replace('$',')', $value_new);
			$value_array_2[] = $value_new;
		}
		return $value_array_2;
	}
}
?>
