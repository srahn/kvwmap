<?php

class SQL {

  var $query;
  var $parsed;

  function __construct($query = NULL) {
    include_once(WWWROOT. APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLParser.php');
		if($query != NULL){
			$this->parse($query);
		}
  }

  function parse($query){
    $parser = new PHPSQLParser($query, true);
    $this->query = $query;
    $this->parsed = $parser->parsed;
  }
  
  function get_attributes() {
    $attributes = array();
    foreach ($this->parsed['SELECT'] as $index => $select_part) {
      $name = $alias = '';
      if (
        is_array($select_part['alias']) AND
        array_key_exists('no_quotes', $select_part['alias']) AND
        $select_part['alias']['no_quotes'] != ''
      ) {
        $name = $alias = $select_part['alias']['no_quotes'];
      }
      else {
        $name = trim(array_pop(explode('.', $select_part['base_expr'])), '"');
      }
      $length = ($this->parsed['SELECT'][$index + 1]['position'] ?: $this->parsed['FROM'][0]['position']) - $select_part['position'];
      $base_exp = substr($this->query, $select_part['position'], $length);
      if (!$this->parsed['SELECT'][$index + 1]['position']) {
        $base_exp = substr($base_exp, 0, strripos($base_exp, 'from'));   # wenn letztes Attribut, das FROM abschneiden
      }
      $base_exp = trim($base_exp, ", \n\r\t");
      if (is_array($select_part['alias'])) {
        $base_exp = substr($base_exp, 0, -strlen($select_part['alias']['base_expr']));    # wenn alias, den alias abschneiden
      }
  
      $attributes[$name] = array(
        'base_expr' => $base_exp,
        'alias' => $alias
      );
    }
    return $attributes;
  }
  
  function get_from() {
    $from = 'FROM ' . substr($this->query, $this->parsed['FROM'][0]['position']);
    return $from;
  }

}
?>