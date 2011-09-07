<?php

class wfs{
	
	function wfs($url, $version, $typename){
		$this->url = $url;
		$this->version = $version;
		$this->typename = $typename;
	}
	
	function get_feature_request($bbox, $filter, $maxfeatures){
		$request = $this->url.'&service=WFS&request=getfeature&version='.$this->version.'&typename='.$this->typename;
		if($bbox != ''){$request .= '&bbox='.$bbox;}
		if($filter != ''){$request .= '&filter='.$filter;}
		if($maxfeatures != ''){$request .= '&maxfeatures='.$maxfeatures;}
		$this->gml = utf8_decode(file_get_contents($request));
	}
	
	function describe_featuretype_request(){
		$request = $this->url.'&service=WFS&request=describefeaturetype&version='.$this->version.'&typename='.$this->typename;
		$this->gml = file_get_contents($request);
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
	
	function extract_features(){
		# liefert die Datensätze einer getfeature-Abfrage (zuvor muss get_feature_request() und parse_gml() ausgeführt werden)
		for($i=0; $i < count($this->objects); $i++){		# durchläuft alle Objekte
			for($j = 0; $j < count($this->objects[$i]); $j++){		# durchläuft alle Tags im Objekt
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