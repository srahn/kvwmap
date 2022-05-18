BEGIN;

ALTER TABLE u_polygon ALTER COLUMN the_geom TYPE geometry(GEOMETRY, 25833) USING ST_Transform(the_geom,25833);

COMMIT;
