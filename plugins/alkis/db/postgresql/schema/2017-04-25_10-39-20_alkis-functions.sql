BEGIN;
SET search_path = alkis, public;

-- hinzugefügt am 10.03.2016 --
-- Funktion zum Löschen von kleinen Löchern in (Multi-)Polygonen --
-- gefunden hier: http://giswiki.hsr.ch/PostGIS_-_Tipps_und_Tricks#Eliminate_sliver_polygons --
 CREATE OR REPLACE FUNCTION public.Filter_Rings(geometry,float)
 RETURNS geometry AS
 $$
 SELECT ST_Collect( CASE WHEN d.inner_rings is NULL OR NOT st_within(st_collect(d.inner_rings), ST_MakePolygon(c.outer_ring)) THEN ST_MakePolygon(c.outer_ring) ELSE ST_MakePolygon(c.outer_ring, d.inner_rings) END) as final_geom		-- am 20.07.2016 angepasst
  FROM (/* Get outer ring of polygon */
        SELECT ST_ExteriorRing(b.the_geom) as outer_ring
          FROM (SELECT (ST_DumpRings((ST_Dump($1)).geom)).geom As the_geom, path(ST_DumpRings((ST_Dump($1)).geom)) as path) b
          WHERE b.path[1] = 0 /* ie the outer ring */
        ) c,
       (/* Get all inner rings > a particular area */
        SELECT ST_Accum(ST_ExteriorRing(b.the_geom)) as inner_rings
          FROM (SELECT (ST_DumpRings((ST_Dump($1)).geom)).geom As the_geom, path(ST_DumpRings((ST_Dump($1)).geom)) as path) b
          WHERE b.path[1] > 0 /* ie not the outer ring */
            AND ST_Area(b.the_geom) > $2
        ) d
 $$
 LANGUAGE 'sql' IMMUTABLE;

 COMMIT;
