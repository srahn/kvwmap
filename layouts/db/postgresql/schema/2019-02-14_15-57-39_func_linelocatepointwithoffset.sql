BEGIN;

CREATE OR REPLACE FUNCTION public.gdi_linelocatepointwithoffset(
    line_geom geometry,
    point_geom geometry)
  RETURNS record AS
$BODY$
		DECLARE
			rec RECORD;
		BEGIN
			EXECUTE '
				SELECT
					ST_LineInterpolatePoint($1, ST_LineLocatePoint($1, $2)) AS foot_point,
					(ST_LineLocatePoint($1, $2) * ST_Length($1))::NUMERIC AS ordinate,
					(CASE WHEN ST_Distance(ST_OffsetCurve($1, ST_Distance($1, $2)), $2) > ST_Distance($1, $2)
					THEN 1
					ELSE -1
					END * ST_Distance($1, $2))::NUMERIC AS abscissa
			'
			USING line_geom, point_geom
			INTO rec;

			RETURN rec;
		END;
	$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
