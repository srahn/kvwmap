<?php

class SQL {

  var $query;
  var $parsed;

  function __construct($query = NULL) {
    include_once(WWWROOT. APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLParser.php');
  	include_once(WWWROOT . APPLVERSION . THIRDPARTY_PATH . 'PHP-SQL-Parser/src/PHPSQLCreator.php');
    if ($query) {
			$this->parse($query);
		}
  }

  function parse($query){
    $parser = new PHPSQLParser($query, true);
    $this->query = $query;
    $this->parsed = $parser->parsed;
    $this->action = array_keys($this->parsed)[0];
  }

  function get_attributes($only_names = true) {
    $this->attributes = array();
    switch ($this->action) {
      case 'SELECT' : {
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
          $base_expr = substr($this->query, $select_part['position'], $length);
          if (!$this->parsed['SELECT'][$index + 1]['position']) {
            $base_expr = substr($base_expr, 0, strripos($base_expr, 'from'));   # wenn letztes Attribut, das FROM abschneiden
          }
          $base_expr = trim($base_expr, ", \n\r\t");
          if (is_array($select_part['alias'])) {
            $base_expr = substr($base_expr, 0, -strlen($select_part['alias']['base_expr']));    # wenn alias, den alias abschneiden
          }
          if ($only_names) {
            $this->attributes[$index] = $base_expr;
          }
          else {
            $this->attributes[$name] = array(
              'base_expr' => $base_expr,
              'alias' => $alias
            );
          }
        }
      } break;
      case 'INSERT' : {
        foreach ($this->parsed['INSERT'][0]['columns'] AS $index => $column) {
          if ($only_names) {
            $this->attributes[$index] = $column['base_expr'];
          }
          else {
            $this->attributes[$column['base_expr']] = array(
              'base_expr' => $column['base_expr'],
              'alias' => $column['alias']
            );
          }
        }
      } break;
      default : {}
    }
    return $this->attributes;
  }

  function get_values() {
    $this->values = array();
    switch ($this->action) {
      case 'SELECT' : {
      } break;
      case 'INSERT' : {
        foreach ($this->parsed['VALUES'][0]['data'] AS $index => $value) {
          $this->values[$index] = $value['base_expr'];
        }
      } break;
      default : {}
    }
#    return $this->parsed['VALUES'];
    return $this->values;
  }

  function get_from() {
    $from = 'FROM ' . substr($this->query, $this->parsed['FROM'][0]['position']);
    return $from;
  }

  function remove_not_allowed_columns($allowed) {
    $not_allowed_columns = array();
    switch ($this->action) {
      case 'INSERT' : {
        foreach ($this->get_attributes() AS $index => $attribute) {
          if (!in_array($attribute, $allowed)) {
            $not_allowed_columns[] = $attribute;
            unset($this->parsed[$this->action][0]['columns'][$index]);
            unset($this->parsed['VALUES'][0]['data'][$index]);
          }
        }
      } break;
      case 'UPDATE' : {
        foreach ($this->get_attributes() AS $index => $attribute) {
          if (!in_array($attribute, $allowed)) {
            $not_allowed_columns[] = $attribute;
            unset($this->parsed[$this->action][0]['columns'][$index]);
            unset($this->parsed['VALUES'][0]['data'][$index]);
          }
        }
      } break;
      default : {
      }
    }

    $this->parsed[$this->action][0]['columns'] = array_values($this->parsed[$this->action][0]['columns']);
    $this->parsed['VALUES'][0]['data'] = array_values($this->parsed['VALUES'][0]['data']);
    return $not_allowed_columns;
  }

  function to_sql() {
    $creator = new PHPSQLCreator();
    return $creator->create($this->parsed);
  }
}
?>