BEGIN;

CREATE OR REPLACE FUNCTION public.filter_rings(
	geometry,
	double precision)
    RETURNS geometry
    LANGUAGE 'sql'
    COST 100
    IMMUTABLE
AS $BODY$
SELECT ST_Collect(b.final_geom) AS filtered_geom
  FROM (SELECT ST_MakePolygon((/* Get outer ring of polygon */
    SELECT ST_ExteriorRing(a.the_geom) AS outer_ring /* ie the outer ring */
    ),  ARRAY(/* Get all inner rings > a particular area */
     SELECT ST_ExteriorRing(b.geom) AS inner_ring
       FROM (SELECT (ST_DumpRings(a.the_geom)).*) b
      WHERE b.path[1] > 0 /* ie not the outer ring */
        AND ST_Area(b.geom) > $2
    ) ) AS final_geom
         FROM (SELECT ST_GeometryN(ST_Multi($1),/*ST_Multi converts any Single Polygons to MultiPolygons */
                                   generate_series(1,ST_NumGeometries(ST_Multi($1)))
                                   ) AS the_geom
               ) a
       ) b
$BODY$;

COMMENT ON FUNCTION public.filter_rings(geometry, double precision)
    IS 'Filtert die inneren Ringe aus Multipolygonen wenn deren Fläche kleiner als der zweite übergebene Parameter ist. Funktion übernommen von Quelle: https://spatialdbadvisor.com/postgis_tips_tricks/92/filtering-rings-in-polygon-postgis';


COMMIT;
