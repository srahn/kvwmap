BEGIN;

CREATE OR REPLACE FUNCTION public.gdi_subtract_geometries_from_schematables(base_geom geometry, schema_table_with_buffer_list text[], min_area double precision)
 RETURNS geometry
 LANGUAGE plpgsql
AS $function$
DECLARE
  schema_name text;
  table_name text;
  buffer_m double precision;
  schema_table text;
  sql text;
  filter text;
  sub_geom geometry;
  result_geom geometry := base_geom;
  base_srid int := ST_SRID(base_geom);
BEGIN
  FOREACH schema_table IN ARRAY schema_table_with_buffer_list LOOP
    -- Aufteilen: 'schema.table:buffer'
    schema_name := split_part(schema_table, '.', 1);
    table_name  := split_part(split_part(schema_table, '.', 2), ':', 1);
    buffer_m    := split_part(schema_table, ':', 2)::double precision;
    filter      := split_part(schema_table, ':', 3)::text;

    -- Dynamisches SQL: transformieren auf base_srid, puffern, unionieren, bbox-Filter
    sql := format(
      $f$
      SELECT ST_Union(ST_Buffer(ST_Transform(the_geom, %s), %L))
      FROM %I.%I
      WHERE %s the_geom && ST_Transform(ST_Envelope($1), ST_SRID(the_geom))
      $f$, base_srid, buffer_m, schema_name, table_name, CASE WHEN filter = '' THEN '' ELSE filter || ' AND ' END
    );

    EXECUTE sql INTO sub_geom USING result_geom;

    IF sub_geom IS NOT NULL THEN
      result_geom := ST_Difference(result_geom, sub_geom);
    END IF;
  END LOOP;

  -- Zerlegen, filtern, wieder zusammenbauen
  RETURN (
    SELECT ST_Multi(ST_Union(dump.geom))
    FROM (
      SELECT (ST_Dump(result_geom)).geom
    ) AS dump
    WHERE ST_Area(dump.geom) >= min_area
  );
END;
$function$;

COMMIT;