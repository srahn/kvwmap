<?
  ini_set('memory_limit', '8192M');
  global $GUI;
	
	$sql = "
		SELECT 
      table_name 
    FROM 
      information_schema.tables
    WHERE 
      table_schema = '" . MYSQL_DBNAME . "' AND 
      table_type='BASE TABLE';
	";
  $ret = $this->database->execSQL($sql,4, 1);
  $res = $this->database->result;
  while ($r = $res->fetch_assoc()){
    $inserts .= implode(chr(13), $this->database->create_insert_dump($r['table_name'], '', 'SELECT * FROM '. $r['table_name'])['insert']);
  }
  
  #echo $inserts;

  $sql = "
    BEGIN;
    SET search_path = kvwmap, public;
    SET session_replication_role = 'replica';
    " . $inserts . "
    SET session_replication_role = 'origin';
    COMMIT;
  ";
  $this->pgdatabase->execSQL($sql, 0, 0);

  $sql = "
    with sequences as (
      select *
      from (
        select table_schema,
              table_name,
              column_name,
              pg_get_serial_sequence(format('%I.%I', table_schema, table_name), column_name) as col_sequence
        from information_schema.columns
        where table_schema = 'kvwmap'
      ) t
      where col_sequence is not null
    ), maxvals as (
      select table_schema, table_name, column_name, col_sequence,
              (xpath('/row/max/text()',
                query_to_xml(format('select max(%I) from %I.%I', column_name, table_schema, table_name), true, true, ''))
              )[1]::text::bigint as max_val
      from sequences
    ) 
    select table_schema, 
          table_name, 
          column_name, 
          col_sequence,
          coalesce(max_val, 0) as max_val,
          setval(col_sequence, coalesce(max_val, 1)) --<< this will change the sequence
    from maxvals;";
  #$this->pgdatabase->execSQL($sql, 0, 0);

  $result[0] = true; # MIgration nicht bestätigen

?>