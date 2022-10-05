BEGIN;

  CREATE OR REPLACE FUNCTION gdi_get_deltas(table_schema character varying, table_name character varying)
  RETURNS table (sql_output text)
  LANGUAGE 'plpgsql'
  COST 100
  VOLATILE 
  AS $BODY$
  DECLARE
    sql text;
    msg text[];
    statements text[];
    insert_ids text;
    insert_record record;
    insert_statement record;
    update_ids text;
    update_record record;
    update_statement record;
    delete_ids text;
    delete_record record;
    delete_statement record;
    ref refcursor;
  BEGIN
   /******************************
    * INSERT für neue Datensätze *
    ******************************/
    sql = FORMAT('
        SELECT
          string_agg(v.gid::text, '', '')
        FROM
          %1$s.%2$s AS v LEFT JOIN
          %1$s.tab_%2$s AS t ON v.gid = t.gid
        WHERE
          t.gid IS NULL
      ',
      table_schema,
      table_name
    );
    --RAISE NOTICE 'Abfrage der ids für INSERT: %', sql;
    EXECUTE sql INTO insert_ids;

    IF insert_ids != '' THEN
     /*******************
      * Get Column Info *
      *******************/
      sql = FORMAT('
          SELECT
            string_agg(column_name, '', '') AS columns,
            ''concat_ws('''', '''', '' || string_agg(''coalesce('''''''''''''''' ||'' || column_name || ''::text || '''''''''''''''', ''''NULL'''')'', '', '') || '')''::text AS values
          FROM
            information_schema.columns
          WHERE
            table_schema = %1$L AND
            table_name = %2$L
        ',
        table_schema,
        table_name
      );
      --RAISE NOTICE 'Abfrage der Attribute und Werte für INSERT: %', sql;
      EXECUTE sql INTO insert_record;
      sql = format('
          SELECT
            ''INSERT INTO %1$s.tab_%2$s (%3$s) VALUES ('' || %4$s || '')'' AS sql
          FROM
            %1$s.%2$s
          WHERE
            gid IN (%5$s)
        ',
        table_schema,
        table_name,
        insert_record.columns,
        insert_record.values,
        insert_ids
      );
      --RAISE NOTICE 'Abfrage der Statements für INSERT: %', sql;
      FOR insert_statement IN EXECUTE sql LOOP
        --RAISE NOTICE 'SQL für INSERT: %', insert_statement.sql;
        statements = array_append(statements, insert_statement.sql);
      END LOOP;
      msg = array_append(msg, 'Datensätze hinzugefügt, gid: ' || insert_ids || '.');
    ELSE
      msg = array_append(msg, 'Keine Datensätze hinzugefügt.');
    END IF;

   /***********************************
    * UPDATE für geänderte Datensätze *
    ***********************************/

    sql = FORMAT('
        SELECT
          string_agg(column_name, '', '') AS column_str,
          string_agg(''coalesce(v.'' || column_name || ''::text, '''''''') != coalesce(t.'' || column_name || ''::text, '''''''')'', '' OR '') where_str,
          string_agg(''coalesce('''''''''''''''' || '' || column_name || ''::text || '''''''''''''''', ''''NULL'''')'', '' || '''', '''' || '') AS set_str
        FROM
          information_schema.columns
        WHERE
          table_schema = %1$L AND
          table_name = %2$L AND
          column_name != ''gid''
      ',
      table_schema,
      table_name
    );
    --RAISE NOTICE 'Abfrage der Attribute für UPDATE: %', sql;
    EXECUTE sql INTO update_record;

    sql = FORMAT('
        SELECT
          string_agg(v.gid::text, '', '') AS gids
        FROM
          %1$s.%2$s v JOIN
          %1$s.tab_%2$s t ON v.gid = t.gid
        WHERE
          %3$s
      ',
      table_schema,
      table_name,
      update_record.where_str
    );
    --RAISE NOTICE 'Abfrage der ids für UPDATE: %', sql;
    EXECUTE sql INTO update_ids;

    IF update_ids != '' THEN
      -- Abfrage der UPDATE-Statements
      sql = FORMAT('
         SELECT
           ''UPDATE %1$s.tab_%2$s SET (%4$s) = ('' || %5$s || '') WHERE gid = '' || gid AS sql
         FROM
           %1$s.%2$s
         WHERE
           gid IN (%3$s)
        ',
        table_schema,
        table_name,
        update_ids,
        update_record.column_str,
        update_record.set_str
      );
      --RAISE NOTICE 'Abfrage der Statements für UPDATE: %', sql;
      FOR update_statement IN EXECUTE sql LOOP
        --RAISE NOTICE 'SQL für UPDATE: %', update_statement.sql;
        statements = array_append(statements, update_statement.sql);
      END LOOP;
      msg = array_append(msg, 'Datensätze geändert gid: ' || update_ids || '.');
    ELSE
      msg = array_append(msg, 'Keine Datensätze geändert.');
    END IF;

  /***********************************
    * DELETE für gelöschte Datensätze *
    ***********************************/
    sql = FORMAT('
        SELECT
          string_agg(t.gid::text, '', '')
        FROM
          %1$s.tab_%2$s AS t LEFT JOIN
          %1$s.%2$s AS v ON t.gid = v.gid
        WHERE
          v.gid IS NULL
      ',
      table_schema,
      table_name
    );
    --RAISE NOTICE 'Abfrage der zu löschenden ids: %', sql;
    EXECUTE sql INTO delete_ids;

    IF delete_ids != '' THEN   
      sql = FORMAT('
          SELECT
            ''DELETE FROM %1$s.tab_%2$s WHERE gid = '' || gid AS sql
          FROM
            %1$s.tab_%2$s
          WHERE
            gid IN (%3$s)
        ',
        table_schema,
        table_name,
        delete_ids
      );
      --RAISE NOTICE 'Abfrage der Statements für DELETE: %', sql;
      FOR delete_statement IN EXECUTE sql LOOP
        --RAISE NOTICE 'SQL für DELETE: %', delete_statement.sql;
        statements = array_append(statements, delete_statement.sql);
      END LOOP;
      msg = array_append(msg, 'Datensätze gelöscht, gid: ' || delete_ids || '.');
    ELSE
      msg = array_append(msg, 'Keine Datensätze gelöscht.');
    END IF;

    RAISE NOTICE '%', array_to_string(msg, ', ');
    RETURN QUERY SELECT unnest(statements);
  END
  $BODY$;
  COMMENT ON FUNCTION gdi_get_deltas(character varying, character varying)
  IS 'Funktion liefert die deltas der Datensätze zwischen der angegebenen Tabelle oder View und der dazugehörigen Tabelle mit dem Prefix tab_. Zurückgeliefert werden Zeile für Zeile INSERT, UPDATE und DELETE Statements, die die Zieltabelle mit dem Namen $table_schema.tab_$table_name auf den gleichen Stand bringen können wie die Originaltabelle oder View $table_schema.$table_name. Als Notice wird ein Text mit einem Protokoll über die eingefügten, geänderten und gelöschten Datensätze ausgegeben. Die Zieltabelle muss die gleichen Attribute haben wie die Originaltabelle und beide müssen eine Primarschlüsselattribut mit dem Namen gid besitzen.';

COMMIT;
