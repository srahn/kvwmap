<?php

class SQL {

  var $parsed;

  function __construct($sql = NULL) {
    include_once(WWWROOT. APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLParser.php');
    include_once(WWWROOT. APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLCreator.php');
		if($sql != NULL){
			$this->parse($sql);
		}
  }

  function parse($sql){
    $parser = new PHPSQLParser($sql, true);
    $this->parsed = $parser->parsed;
  }
  
  function get_attributes() {
    $creator = new PHPSQLCreator();
    $attributes = array();
    foreach ($this->parsed['SELECT'] as $value) {
      $name = $alias = '';
      if (
        is_array($value['alias']) AND
        array_key_exists('no_quotes', $value['alias']) AND
        $value['alias']['no_quotes'] != ''
      ) {
        $name = $value['alias']['no_quotes'];
        $alias = $value['alias']['no_quotes'];
      }
      else {
        $name = $alias = $value['base_expr'];
      }
      unset($value['alias']);
      $value['delim'] = '';
      $select_part['SELECT'][0] = $value;
      $base_exp = substr($creator->create($select_part), 7);
  
      $attributes[$name] = array(
        'base_expr' => $base_exp,
        'alias' => $alias
      );
    }
    return $attributes;
  }
  
  function get_from() {
    $creator = new PHPSQLCreator();
    $parsed = $this->parsed;
    $select[0] = ['expr_type' => 'const', 'base_expr' => '1'];
    $parsed['SELECT'] = $select;
    $from = substr($creator->create($parsed), 8);
    return $from;
  }

}
?>