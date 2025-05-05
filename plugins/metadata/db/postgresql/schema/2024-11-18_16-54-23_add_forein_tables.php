<?php
  $sql = "
    CREATE EXTENSION IF NOT EXISTS mysql_fdw;
    CREATE SCHEMA IF NOT EXISTS kvwmap;
    CREATE SERVER mysql_kvwmapdb FOREIGN DATA WRAPPER mysql_fdw OPTIONS (host 'mysql', port '3306');
    CREATE USER MAPPING FOR kvwmap SERVER mysql_kvwmapdb OPTIONS (username 'kvwmap', password '" . MYSQL_PASSWORD . "');
    CREATE FOREIGN TABLE IF NOT EXISTS kvwmap.layer(
      layer_id integer,
      gruppe integer,
      name character varying,
      alias character varying,
      schema character varying,
      maintable character varying,
      connectiontype integer,
      datentyp integer
    )
    SERVER mysql_kvwmapdb
    OPTIONS (dbname 'kvwmapdb', table_name 'layer');
    ALTER FOREIGN TABLE kvwmap.layer OWNER TO kvwmap;
    GRANT ALL ON TABLE kvwmap.layer TO kvwmap;

    CREATE FOREIGN TABLE IF NOT EXISTS kvwmap.stellen(
      id integer,
      bezeichnung character varying(255)
    )
    SERVER mysql_kvwmapdb
    OPTIONS (dbname 'kvwmapdb', table_name 'stelle');
    ALTER FOREIGN TABLE kvwmap.stellen OWNER TO kvwmap;
    GRANT ALL ON TABLE kvwmap.stellen TO kvwmap;
  ";
  $result = $this->pgdatabase->execSQL($sql);
  if (!$result['success']) {
    echo '<br>Fehler beim Anlegen der Foreign Data Tables.<br>';
  }
?>