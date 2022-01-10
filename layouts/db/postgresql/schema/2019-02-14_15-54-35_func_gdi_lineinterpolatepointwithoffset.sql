BEGIN;

CREATE OR REPLACE FUNCTION public.gdi_lineinterpolatepointwithoffset(
		line_geom geometry,
		ordinate numeric,
		abscissa numeric)
	RETURNS geometry AS
$BODY$
		DECLARE
			point_geom GEOMETRY;
			rec RECORD;
			sql text;
		BEGIN
			EXECUTE '
				SELECT
					ST_LineInterpolatePoint(
						offset_section,
						(rs - ra) / (rb - ra)
					)
				FROM (
					SELECT
						CASE
							WHEN $2 < 0 THEN ST_OffsetCurve(ST_MakeLine((p).geom, lead((p).geom) over ()), -1 * $2)
							WHEN $2 > 0 THEN ST_Reverse(ST_OffsetCurve(ST_MakeLine((p).geom, lead((p).geom) over ()), -1 * $2))
							ELSE ST_MakeLine((p).geom, lead((p).geom) over ())
						END offset_section,
						CASE WHEN $3 = 0 THEN 0 ELSE $3 / ST_Length(l) END rs,
						ST_LineLocatePoint(l, (p).geom) ra,
						ST_LineLocatePoint(l, lead((p).geom) over ()) rb
					FROM
						(
							SELECT
								ST_DumpPoints($1) p,
								$1 l
						) line_tab
					) ratio_tab
				WHERE
					rs >= ra AND rs <= rb
			'
			USING line_geom, abscissa, ordinate
			INTO point_geom;

			RETURN point_geom;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

COMMIT;
