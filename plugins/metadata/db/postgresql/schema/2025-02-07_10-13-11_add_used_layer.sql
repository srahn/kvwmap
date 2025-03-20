BEGIN;
  CREATE FOREIGN TABLE IF NOT EXISTS kvwmap.used_layer(
    layer_id integer,
    stelle_id integer,
    legendorder integer,
    filter text,
    privileg character
  )
  SERVER mysql_kvwmapdb
  OPTIONS (dbname 'kvwmapdb', table_name 'used_layer');
COMMIT;