BEGIN;

DROP FUNCTION IF EXISTS st_area_utm(geometry, integer, numeric, integer);

DROP FUNCTION IF EXISTS st_length_utm(geometry, integer, numeric, integer);

COMMIT;
