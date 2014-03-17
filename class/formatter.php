<?php
class formatter {
	
  # Konstruktor
  function formatter($data, $format, $callback) {
		$this->data = $data;
		$this->format = $format;
		$this->callback = $callback;
  }
	
	function output() {
		eval("\$output = \$this->output_".$this->format."();");
		return $output;
	}
	
	function output_dump() {
		return var_dump($this->data);
	}
	
	function output_json() {
		header('Content-Type: application/json; charset=utf-8');
		$this->data ? $json = json_encode($this->data) : $json = '{}';
		return utf8_decode($json);
	}

	function output_jsonp() {
		header('Content-Type: application/json; charset=utf-8');
		$this->data ? $json = json_encode($this->data) : $json = '{}';
		$jsonp = "{$this->callback}($json)";
		return utf8_decode($jsonp);
	}	
	
	function output_jsonp() {
		header('Content-Type: application/json; charset=utf-8');
		$this->data ? $json = json_encode($this->data) : $json = '{}';
		$jsonp = "{$this->callback}($json)";
		return utf8_decode($jsonp);
  }    
	
	function output_print_r() {
		return print_r($this->data, true);
	}
}
?>