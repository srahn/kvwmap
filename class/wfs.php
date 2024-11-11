<?php

class wfs{
	
	function __construct($url, $version, $typename, $namespace, $epsg, $username = NULL, $password = NULL){
		$this->url = $url;
		$this->version = $version;
		$this->typename = $typename;
		$this->username = $username;
		$this->password = $password;
		$this->namespace = $namespace;
		$this->epsg = $epsg;
	}
	
	function get_feature_request($request, $bbox, $filter, $maxfeatures){
		# entweder wird eine fertige request-URL übergeben oder an Hand der bbox bzw. des Filters gebildet
		if($request == NULL){
			$request = $this->url.'&service=WFS&request=GetFeature&version='.$this->version.'&typename='.$this->typename;
			$request .= '&srsName=EPSG:'.$this->epsg;
			if($bbox != ''){
				$request .= '&bbox='.$bbox;
				if($this->version == '1.1.0')$request .= ',EPSG:'.$this->epsg;
			}
			if ($filter != '') {
				$request .= '&filter='.urlencode($filter);
			}
			if ($maxfeatures != '') {
				$request .= '&maxfeatures='.$maxfeatures;
			}
		}
		#echo $request;
		$this->gml = url_get_contents($request, $this->username, $this->password);
		return $request;
	}

	function describe_featuretype_request() {
		$request = $this->url . (substr(trim($this->url), -1) == '?' ? '' : '&') . 'SERVICE=WFS&REQUEST=DescribeFeatureType&VERSION=' . $this->version . '&TYPENAME=' . $this->typename;
		$this->gml = url_get_contents($request, $this->username, $this->password);
	}

	function getTargetNamespace(){
		$exp = explode('targetNamespace="', $this->gml);
		$this->targetnamespace = substr($exp[1], 0, strpos($exp[1], '"'));
	}
	
	function create_filter($attributenames, $operators, $values){
		# Diese Funktion generiert aus Attributnamen, Operatoren und Werten einen über 'And' verknüpften WFS-Filterstring
		if($this->namespace != '')$namespace = $this->namespace.':';
		$count = count_or_0($attributenames);
		if($count > 0){
			$filter = '<ogc:Filter xmlns:ogc="http://www.opengis.net/ogc">';
			if($count > 1){
				$filter .= '<ogc:And>';
			}
			for($i = 0; $i < $count; $i++){
				$operator_attributes = '';
				switch ($operators[$i]){
					case '=' : {
						$operator = 'PropertyIsEqualTo';
					}break;
					
					case '!=' : {
						$operator = 'PropertyIsNotEqualTo';
					}break;
					
					case '<' : {
						$operator = 'PropertyIsLessThan';
					}break;
					
					case '>' : {
						$operator = 'PropertyIsGreaterThan';
					}break;
					
					case 'LIKE' : {									# geht noch nicht, weil man den requeststring hierfür url-encoden muss und dann ist er zu lang 
						$operator = 'PropertyIsLike';
						$operator_attributes = " wildCard='*' singleChar='.' escape='!'";
						if (strpos($values[$i], '%') === false) {
							$values[$i] = '%' . $values[$i] . '%';
						}
						$values[$i] = str_replace('%', '*', $values[$i]);
					}break;
				}
				
				$filter .= '<ogc:'.$operator.$operator_attributes.'>';
				$filter .= '<ogc:PropertyName>'.$namespace.$attributenames[$i].'</ogc:PropertyName>';
				$filter .= '<ogc:Literal>'.$values[$i].'</ogc:Literal>';
				$filter .= '</ogc:'.$operator.'>';
			}
			if($count > 1){
				$filter .= '</ogc:And>';
			}
			$filter .= '</ogc:Filter>';
			#$filter .= '&namespace=xmlns('.$this->namespace.'='.$this->targetnamespace.')';
		}
		return $filter;
				
		
   /*     <Filter>
    * 			<AND>
    * 				<PropertyIsLike wildcard='*' singleChar='.' escape='!'>
								<PropertyName>NAME</PropertyName>
								<Literal>Syd*</Literal>
							</PropertyIsLike>
							<PropertyIsEqualTo>
								<PropertyName>POP_RANGE</PropertyName>
								<Literal>4</Literal>
							</PropertyIsEqualTo>
						</AND>
					</Filter>
			*/
	}

	/**
	* Diese Funktion parst das GML und sucht nach dem übergebenem Tagnamen '$parse_object' und erstellt ein Array von Objekten, die diesem Namen entsprechen
	* Jedes Objekt ist wiederum ein Array, deren Elemente die im Objekt enthaltenen XML-Tags sind
	* Jeder XML-Tag ist auch ein Array, dessen Elemente die verschiedenen Eigenschaften des Tags sind (tag,value,type,level,attributes)
	* attributes ist ein Array, welches die Attribute des Tags enthält
	*/
	function parse_gml($parse_object) {
		$objects = array();
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $this->gml, $values, $tags);
		xml_parser_free($parser);
		foreach ($tags as $key => $val) {
			if (strpos($key, $parse_object) !== false) {
				$ranges = $val;
				for ($i = 0; $i < count($ranges); $i += 2) {
					$offset = $ranges[$i] + 1;
					$len = $ranges[$i + 1] - $offset;
					$objects[] = array_slice($values, $offset, $len);
				}
			}
		}
		$this->objects = $objects;
	}

	function rename_features(){
		$this->gml = preg_replace('/\w+_feature/', 'gml:featureMember', $this->gml);
	}

	function extract_features(){
		$features = array();
		# liefert die Datensätze einer getfeature-Abfrage (zuvor muss get_feature_request() ausgeführt werden)
		if (strpos($this->gml, 'gmlx:featureMember') !== false) {
			$this->parse_gml('gmlx:featureMember');
		}
		else {
			$this->parse_gml('gml:featureMember');
		}
		if (strpos($this->gml, 'gmlx:posList') !== false) {
			$geomtag = 'gmlx:posList';
		}
		elseif (strpos($this->gml, 'gml:posList') !== false) {
			$geomtag = 'gml:posList';
		}
		elseif (strpos($this->gml, 'gml:coordinates') !== false) {
			$geomtag = 'gml:coordinates';
		}
		else {
			$geomtag = 'gml:pos';
		}
		for ($i = 0; $i < count_or_0($this->objects); $i++) {
			# durchläuft alle Objekte
			for ($j = 0; $j < count($this->objects[$i]); $j++) {
				# durchläuft alle Tags im Objekt
				$coord_pair = array();
				# Boundingbox entnehmen und ins aktuelle System transformieren
				if ($this->objects[$i][$j]["tag"] == $geomtag AND $features[$i]['geom'] == '') {
					#4495561.758,5997768.92 4495532.625,5997774.389 4495517.732,5997697.398 4495530.82,5997694.958 4495538.126,5997693.31 4495545.292,5997691.136 4495547.163,5997690.416 4495561.758,5997768.92
					$coords = $this->objects[$i][$j]["value"];
					if ($coords != '') {
						if (strpos($coords, ',') === false) {
							# GML 3 ohne Kommas
							$explosion = explode(' ', $coords);
							$num_coords = count($explosion) / 2;
							for ($e = 0; $e < count($explosion); $e = $e + 2) {
								if ($explosion[$e] != '') {
									$coord_pair[] = $explosion[$e] . ' ' . $explosion[$e + 1];
								}
							}
							$coords = implode(', ', $coord_pair);
						}
						else {
							$coords = str_replace(' ', '_', trim($coords));
							$coords = str_replace(',', ' ', $coords);
							$coords = str_replace('_', ',', $coords);
							$num_coords = substr_count($coords, ' ');
						}
						$features[$i]['geom'] = ($num_coords > 1 ? 'LINESTRING' : 'POINT') . '(' . $coords . ')';
					}
				}
				if ($this->objects[$i][$j]["type"] == 'complete' AND $this->objects[$i][$j]["tag"] != $geomtag) {
					# alle kompletten Tags und keine Geometrie-Tags
					# evtl. Namespace davor entfernen
					$this->objects[$i][$j]["tag"] = str_replace($this->namespace . ':', '', $this->objects[$i][$j]["tag"]);
		  		$features[$i]['value'][$this->objects[$i][$j]["tag"]] = $this->objects[$i][$j]["value"];
				}				
			}
			if ($features[$i]['value']['gml:lowerCorner'] != '') {
				$lc = explode(' ', $features[$i]['value']['gml:lowerCorner']);
				$uc = explode(' ', $features[$i]['value']['gml:upperCorner']);
				$features[$i]['bbox'] = 'POLYGON((' . 
																	$features[$i]['value']['gml:lowerCorner'] . ', ' . 
																	$uc[0] . ' ' . $lc[1] . ', ' . 
																	$features[$i]['value']['gml:upperCorner'] . ', ' . 
																	$lc[0] . ' ' . $uc[1] . ', ' . 
																	$features[$i]['value']['gml:lowerCorner'] . 
																'))';
			}
  	}
	  return $features;
	}

	/*
	* liefert die Sachattribute eines WFS-Layers (zuvor muss describe_featuretype_request() ausgeführt werden) 
	* erstmal den richtigen Featuretype finden, falls mehrere geliefert wurden
	*/
	function get_attributes() {
		$attributes = array();
		$this->parse_gml('schema');
		$index = -1;
		if (count($this->objects) > 0) {
			for ($j = 0; $j < count($this->objects[0]); $j++) {
				if (in_array($this->objects[0][$j]["tag"], ['element', 'xsd:complexType']) AND in_array($this->objects[0][$j]["type"], ['open', 'complete']) AND $this->objects[0][$j]["level"] == 2) {
					$index++;
					if ($this->objects[0][$j]["attributes"]['name'] == str_replace($this->namespace . ':', '', $this->typename) OR
							$this->objects[0][$j]["attributes"]['name'] == str_replace($this->namespace . ':', '', $this->typename. 'Type')) {
						break;
					}
				}
			}
			$this->parse_gml('sequence');
			if (array_key_exists($index, $this->objects)) {
				for ($j = 0; $j < count($this->objects[$index]); $j++) {
					# nur offene oder komplette element-Tags
					if(strpos($this->objects[$index][$j]["tag"], 'element') !== false AND ($this->objects[$index][$j]["type"] == 'complete' OR $this->objects[$index][$j]["type"] == 'open')) {
						# und keine Geometrie-Tags
						if (
							$this->objects[$index][$j]["attributes"]["type"] != 'gml:GeometryPropertyType' AND
							$this->objects[$index][$j]["attributes"]["type"] != 'gml:MultiPolygonPropertyType' AND
							$this->objects[$index][$j]["attributes"]["type"] != 'gml:PolygonPropertyType' AND
							$this->objects[$index][$j]["attributes"]["type"] != 'gml:PointPropertyType'
						) {
			  			$attribute['name'] = $this->objects[$index][$j]["attributes"]["name"];
							$attributes[] = $attribute;
						}
					}
				}
			}
		}
		return $attributes;
	}
}
?>