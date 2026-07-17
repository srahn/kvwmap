BEGIN;

ALTER TABLE kvwmap.rolle ALTER COLUMN minx TYPE float8 USING minx::float8;
ALTER TABLE kvwmap.rolle ALTER COLUMN miny TYPE float8 USING miny::float8;
ALTER TABLE kvwmap.rolle ALTER COLUMN maxx TYPE float8 USING maxx::float8;
ALTER TABLE kvwmap.rolle ALTER COLUMN maxy TYPE float8 USING maxy::float8;

COMMIT;
