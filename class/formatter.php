<?php
class formatter {
	
  # Konstruktor
  function formatter($data, $format, $content_type, $callback) {
		$this->data = $data;
		$this->format = $format;
    $this->content_type = $content_type;
		$this->callback = $callback;
  }
	
	function output() {
    if (in_array($this->format, array('json', 'jsonp', 'dump', 'html', 'print_r')))
      $format = $this->format;
    else
      $format = 'dump';
    
		eval("\$output = \$this->output_".$format."();");
		return $output;
	}
	
	function output_dump() {
		header('Content-Type: text/html; charset=utf-8');        
		return var_dump($this->data);
	}
   
  function output_html() {
    $recursive = true;
    $null = '&nbsp;';
    // Sanity check
    if (empty($this->data) || !is_array($this->data))
      return false;

    if (!isset($this->data[0]) || !is_array($this->data[0]))
      $this->data = array($this->data);
    
		header('Content-Type: text/html; charset=utf-8');    
    $html  = '<html>';
    $html .= '<head>';
    $html .= '</head>';
    $html .= '<body>';

    // Start the table
    $html = "<table>\n";

    // The header
    $html .= "\t<tr>";
    // Take the keys from the first row as the headings
    foreach (array_keys($this->data[0]) as $heading) {
      $html .= '<th>' . $heading . '</th>';
    }
    $html .= "</tr>\n";

    // The body
    foreach ($this->data as $row) {
      $html .= "\t<tr>" ;
      foreach ($row as $cell) {
        $html .= '<td>';
        // Cast objects
        if (is_object($cell)) {
          $cell = (array) $cell;
        }
        if ($recursive === true && is_array($cell) && !empty($cell)) {
          // Recursive mode
          $html .= "\n" . array2table($cell, true, true) . "\n";
        }
        else {
          $html .= (strlen($cell) > 0) ? htmlspecialchars((string) $cell) : $null;
        }
        $html .= '</td>';
      }
      $html .= "</tr>\n";
    }
    $html .= '</table>';
    $html .= '</body>';
    $html .= '</html>';
  
    return $html;
  }  
	
	function output_json() {
		header('Content-Type: '.$this->content_type.'; charset=utf-8');
		$this->data ? $json = json_encode($this->data) : $json = '{}';
		return utf8_decode($json);
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