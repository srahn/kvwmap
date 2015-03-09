<?php

class wfs{
	
	function wfs($url, $version, $typename){
		$this->url = $url;
		$this->version = $version;
		$this->typename = $typename;
	}
	
	function get_feature_request($bbox, $filter, $maxfeatures){
		$request = $this->url.'&service=WFS&request=GetFeature&version='.$this->version.'&typename='.$this->typename;
		if($bbox != ''){$request .= '&bbox='.$bbox;}
		if($filter != ''){$request .= '&filter='.urlencode($filter);}
		if($maxfeatures != ''){$request .= '&maxfeatures='.$maxfeatures;}
		#$this->gml = utf8_decode(file_get_contents($request));
		#echo $request;
    $this->gml = url_get_contents($request);
	}
	
	function describe_featuretype_request(){
		$request = $this->url.'&service=WFS&request=DescribeFeatureType&version='.$this->version.'&typename='.$this->typename;
		#echo $request;
		$this->gml = url_get_contents($request);
	}
	
	function create_filter($attributenames, $operators, $values){
		# Diese Funktion generiert aus Attributnamen, Operatoren und Werten einen über 'And' verknüpften WFS-Filterstring
		$count = count($attributenames);
		$filter = '<Filter>';
		if($count > 1){
			$filter .= '<And>';
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
				
				/*case 'LIKE' : {									# geht noch nicht, weil man den requeststring hierfür url-encoden muss und dann ist er zu lang 
					$operator = 'PropertyIsLike';
					$operator_attributes = " wildCard='*' singleChar='.' escape='!'";
					$values[$i] = str_replace('%', '*', $values[$i]);
				}break;
				*/
			}
			
			$filter .= '<'.$operator.$operator_attributes.'>';
			$filter .= '<PropertyName>'.$attributenames[$i].'</PropertyName>';
			$filter .= '<Literal>'.$values[$i].'</Literal>';
			$filter .= '</'.$operator.'>';
		}
		if($count > 1){
			$filter .= '</And>';
		}
		$filter .= '</Filter>';
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
	
	function parse_gml($parse_object){
		# Diese Funktion parst das GML und sucht nach dem übergebenem Tagnamen '$parse_object' und erstellt ein Array von Objekten, die diesem Namen entsprechen
		# Jedes Objekt ist wiederum ein Array, deren Elemente die im Objekt enthaltenen XML-Tags sind
		# Jeder XML-Tag ist auch ein Array, dessen Elemente die verschiedenen Eigenschaften des Tags sind (tag,value,type,level,attributes)
		# attributes ist ein Array, welches die Attribute des Tags enthält
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $this->gml, $values, $tags);
    xml_parser_free($parser);

		foreach($tags as $key=>$val){
			if(strpos($key, $parse_object) !== false){
      	$ranges = $val;
        for($i=0; $i < count($ranges); $i+=2){
        	$offset = $ranges[$i] + 1;
          $len = $ranges[$i + 1] - $offset;
          $objects[] = array_slice($values, $offset, $len);
        }
    	}
    }
    $this->objects = $objects;
	}
	
/*	function xml2array($xml){
    $opened = array();
    $opened[1] = 0;
    $xml_parser = xml_parser_create();
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($xml_parser, $xml, $xmlarray);
    xml_parser_free($xml_parser);
    $array = array_shift($xmlarray);
    unset($array["level"]);
    unset($array["type"]);
    $arrsize = sizeof($xmlarray);
    for($j=0;$j<$arrsize;$j++){
        $val = $xmlarray[$j];
        switch($val["type"]){
            case "open":
                $opened[$val["level"]]=0;
            case "complete":
                $index = "";
                for($i = 1; $i < ($val["level"]); $i++)
                    $index .= "[" . $opened[$i] . "]";
                $path = explode('][', substr($index, 1, -1));
                $value = &$array;
                foreach($path as $segment)
                    $value = &$value[$segment];
                $value = $val;
                unset($value["level"]);
                unset($value["type"]);
                if($val["type"] == "complete")
                    $opened[$val["level"]-1]++;
            break;
            case "close":
                $opened[$val["level"]-1]++;
                unset($opened[$val["level"]]);
            break;
        }
    }
    return $array;
	} 
*/
		
	function extract_features(){
		# liefert die Datensätze einer getfeature-Abfrage (zuvor muss get_feature_request() und parse_gml() ausgeführt werden)
		for($i=0; $i < count($this->objects); $i++){		# durchläuft alle Objekte
			for($j = 0; $j < count($this->objects[$i]); $j++){		# durchläuft alle Tags im Objekt
				# Boundingbox entnehmen und ins aktuelle System transformieren
				if($this->objects[$i][$j]["tag"] == 'gml:coordinates' AND $features[$i]['geom'] == ''){
					#4495561.758,5997768.92 4495532.625,5997774.389 4495517.732,5997697.398 4495530.82,5997694.958 4495538.126,5997693.31 4495545.292,5997691.136 4495547.163,5997690.416 4495561.758,5997768.92
					$coords = $this->objects[$i][$j]["value"];
					if(strpos($coords, ',') === false){						# GML 3 ohne Kommas
						$explosion = explode(' ', $coords);
						$num_coords = count($explosion)/2;
						for($e = 0; $e < count($explosion); $e=$e+2){
							$coord_pair[] = $explosion[$e].' '.$explosion[$e+1];
						}
						$coords = implode(', ', $coord_pair);
					}
					else{
						$coords = str_replace(' ', '_', trim($coords));
						$coords = str_replace(',', ' ', $coords);
						$coords = str_replace('_', ',', $coords);
						$num_coords = substr_count($coords, ' ');
					}
					if($num_coords > 1)$features[$i]['geom'] = 'LINESTRING('.$coords.')';
					else $features[$i]['geom'] = 'POINT('.$coords.')';
				}
				if($this->objects[$i][$j]["type"] == 'complete' AND $this->objects[$i][$j]["tag"] != 'gml:coordinates'){			# alle kompletten Tags und keine Geometrie-Tags
		  		$features[$i]['tag'][] = $this->objects[$i][$j]["tag"];
		  		$features[$i]['value'][] = $this->objects[$i][$j]["value"];
				}
			}
  	}
	  return $features;
	}
	
	function get_attributes(){
		# liefert die Sachattribute eines WFS-Layers (zuvor muss describe_featuretype_request() und parse_gml() ausgeführt werden) 
		for($j = 0; $j < count($this->objects[0]); $j++){
			# nur offene oder komplette element-Tags
			if(strpos($this->objects[0][$j]["tag"], 'element') !== false AND ($this->objects[0][$j]["type"] == 'complete' OR $this->objects[0][$j]["type"] == 'open')){
				# und keine Geometrie-Tags
				if($this->objects[0][$j]["attributes"]["type"] != 'gml:GeometryPropertyType' AND $this->objects[0][$j]["attributes"]["type"] != 'gml:MultiPolygonPropertyType' AND $this->objects[0][$j]["attributes"]["type"] != 'gml:PolygonPropertyType' AND $this->objects[0][$j]["attributes"]["type"] != 'gml:PointPropertyType'){
	  			$attributes['name'][] = $this->objects[0][$j]["attributes"]["name"];
				}
			}
		}
	  return $attributes;
	}
}
?>