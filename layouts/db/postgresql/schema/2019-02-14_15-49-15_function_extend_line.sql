BEGIN;

	CREATE OR REPLACE FUNCTION public.gdi_extendline(
			geom geometry,
			start_rate double precision,
			start_constant double precision,
			end_rate double precision,
			end_constant double precision)
		RETURNS geometry AS
	$BODY$
	-- Extends a linestring.
	-- nach https://gis.stackexchange.com/questions/33055/postgis-extrapolate-a-line
	-- end als end und start als start bezeichnet
	--
	-- References:
	-- http://blog.cleverelephant.ca/2015/02/breaking-linestring-into-segments.html
	-- https://gis.stackexchange.com/a/104451/44921
	-- https://gis.stackexchange.com/a/16701/44921
	WITH segment_parts AS (
	SELECT
	(pt).path[1]-1 as segment_num
	,
	CASE
	WHEN
		(nth_value((pt).path, 2) OVER ()) = (pt).path
	AND
		(last_value((pt).path) OVER ()) = (pt).path
	THEN
		3
	WHEN
		(nth_value((pt).path, 2) OVER ()) = (pt).path
	THEN
		1
	WHEN
		(last_value((pt).path) OVER ()) = (pt).path
	THEN
		2
	ELSE
		0
	END AS segment_flag
	,
	(pt).geom AS a
	,
	lag((pt).geom, 1, NULL) OVER () AS b
	FROM ST_DumpPoints($1) pt
	)
	,
	extended_segment_parts
	AS
	(
	SELECT
		*
		,
		ST_Azimuth(a,b) AS az1
		,
		ST_Azimuth(b,a) AS az2
		,
		ST_Distance(a,b) AS len
	FROM
	segment_parts
	where b IS NOT NULL
	)
	,
	expanded_segment_parts
	AS
	(
	SELECT
		segment_num
		,
		CASE
		WHEN
			bool(segment_flag & 2)
		THEN
			ST_Translate(b, sin(az2) * (len * start_rate + start_constant), cos(az2) * (len * start_rate + start_constant))
		ELSE
			a
		END
		AS a
		,
		CASE
		WHEN
			bool(segment_flag & 1)
		THEN
			ST_Translate(a, sin(az1) * (len * end_rate + end_constant), cos(az1) * (len * end_rate + end_constant))
		ELSE
			b
		END
		AS b
	FROM extended_segment_parts
	)
	,
	expanded_segment_lines
	AS
	(
	SELECT
		segment_num
		,
		ST_MakeLine(a, b) as geom
	FROM
	expanded_segment_parts
	)
	SELECT
		ST_LineMerge(ST_Collect(geom ORDER BY segment_num)) AS geom
	FROM expanded_segment_lines
	;
	$BODY$
	LANGUAGE sql VOLATILE
	COST 100;

COMMIT;
