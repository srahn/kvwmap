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
    if ($this->action == 'INSERT' AND strpos($this->parsed['VALUES'][0]['base_expr'], ', -') !== false) {
      $this->adjust_value_operators();
    }
    if ($this->action == 'UPDATE') {
      $this->adjust_where_uuid_function();
    }
  }

  function adjust_value_operators() {
    $data_elements = array();
    for ($i = 0; $i < count($this->parsed['VALUES'][0]['data']); $i++) {
      $data_element = $this->parsed['VALUES'][0]['data'][$i];
      $next_element = $this->parsed['VALUES'][0]['data'][$i+1];
      if ($data_element['expr_type'] === 'operator' &&
        $data_element['base_expr'] === '-' &&
        $next_element['expr_type'] === 'const'
      ) {
        $next_element['base_expr'] = '-' . $next_element['base_expr'];
        $data_elements[] = $next_element;
        $i++;
      }
      else {
        $data_elements[] = $data_element;
      }
    }
    $this->parsed['VALUES'][0]['data'] = $data_elements;
  }

  function adjust_where_uuid_function() {
    foreach ($this->parsed['WHERE'] AS $index => $where_part) {
      if ($where_part['expr_type'] === 'function' && $where_part['base_expr'] === 'uuid') {
        $where_part['expr_type'] = 'colref';
        $where_part['no_quotes'] = 'uuid';
        $this->parsed['WHERE'][$index] = $where_part;
      }
    }
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
      CASE 'UPDATE' : {
        foreach ($this->parsed['SET'] AS $index => $set_part) {
          if ($only_names) {
            $this->attributes[$index] = $set_part['sub_tree'][0]['no_quotes'];
          }
          else {
            $this->attributes[$set_part['sub_tree'][0]['no_quotes']] = array(
              'base_expr' => $set_part['sub_tree'][0]['no_quotes'],
              'alias' => ''
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
            unset($this->parsed['SET'][$index]);
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

  function remove_empty_image_path($sql) {
    if (strpos($sql, '{,') !== false) {
      $sql = str_replace('{,', '{', $sql);
    }
    return $sql;
  }

  function to_sql() {
    $creator = new PHPSQLCreator();
    return $creator->create($this->parsed);
  }
}
?>