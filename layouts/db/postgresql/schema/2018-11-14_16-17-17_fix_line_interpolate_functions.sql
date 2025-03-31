BEGIN;

	--DROP FUNCTION gdi_LineInterpolatePointWithOffset(GEOMETRY, NUMERIC, NUMERIC);
	CREATE OR REPLACE FUNCTION gdi_LineInterpolatePointWithOffset(
		line_geom GEOMETRY,
		ordinate NUMERIC,
		abscissa NUMERIC
	)
	RETURNS GEOMETRY AS
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
	COMMENT ON FUNCTION gdi_LineInterpolatePointWithOffset(GEOMETRY, NUMERiC, NUMERIC) IS 'Calculate the point on or beside the line with abscissa as positive (right) or negative (left) perpendicular distance from the line_geom in the direction of the line and ordinate as the length along the line from start to the plumb foot point. Return NULL if ordinate is negative or longer than linestrings length.';

	--DROP FUNCTION gdi_LineLocatePointWithOffset(GEOMETRY, GEOMETRY);
	CREATE OR REPLACE FUNCTION gdi_LineLocatePointWithOffset(
		line_geom GEOMETRY,
		point_geom GEOMETRY
	)
	RETURNS RECORD AS
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
	COMMENT ON FUNCTION gdi_LineLocatePointWithOffset(GEOMETRY, GEOMETRY) IS 'Calculate the abscissa as positive (right) or negative (left) perpendicular distance from the given line_geom in the direction of the line and ordinate as the length along the line from start to the plumb foot point of given point geometry.';

COMMIT;
