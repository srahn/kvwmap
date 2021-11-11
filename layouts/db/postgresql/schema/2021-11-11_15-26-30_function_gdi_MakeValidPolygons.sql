BEGIN;

	CREATE OR REPLACE FUNCTION public.gdi_MakeValidPolygons(
		par_geom geometry,
		par_buffer double precision DEFAULT 0.005,
		par_point_distance_threshold double precision DEFAULT 0.002,
		par_area_threshold double precision DEFAULT 0.1,
		par_debug boolean DEFAULT false
	)
	RETURNS geometry
	LANGUAGE 'plpgsql'
	COST 100
	VOLATILE PARALLEL UNSAFE
	AS $BODY$
	DECLARE
		var_output record;
		var_sql text;
	BEGIN
		IF par_debug THEN
			RAISE NOTICE 'Input Params: geom: %, buffer: %, point_distance_threshold: %, area_threshold: %, par_debug: %', ST_AsText(par_geom), par_buffer, par_point_distance_threshold, par_area_threshold, par_debug;
		END IF;

		var_sql = FORMAT(
			'
				SELECT
					ST_Collect(
						ST_MakePolygon(
							ST_ExteriorRing(a.geom),
							ARRAY(
								SELECT
									ST_ExteriorRing(
										ST_CollectionExtract(
											ST_RemoveRepeatedPoints(
												ST_Difference(b.geom, ST_Buffer(ST_ExteriorRing(a.geom), %2$s)),
												%3$s
											),
											3
										)
									) AS inner_ring
								FROM
									(
										SELECT (
											ST_DumpRings(a.geom)).*
									) b
								WHERE
									b.path[1] > 0 AND
									ST_Area(
										ST_CollectionExtract(
											ST_RemoveRepeatedPoints(
												ST_Difference(
													b.geom,
													ST_Buffer(
														ST_ExteriorRing(a.geom),
														%2$s
													)
												),
												%3$s
											),
											3
										)
									) > %4$s
							 )
						)
					) AS geom
				FROM
					(
						SELECT ST_GeometryN(%1$L, generate_series(1, ST_NumGeometries(ST_Multi(%1$L)))) AS geom
					) AS a
			', par_geom, par_buffer, par_point_distance_threshold, par_area_threshold
		);
		EXECUTE var_sql INTO var_output;
		IF par_debug THEN
			RAISE NOTICE 'SQL: %s', var_sql;
		END IF;
		RETURN var_output.geom;
	END;
	$BODY$;

	COMMENT ON FUNCTION public.gdi_MakeValidPolygons(geometry, double precision, double precision, double precision, boolean)
		IS 'The function clips the inner rings with a buffer of par_buffer meters around the linestring of the outer ring so that the inner rings garanty do not touch the outer ring any more. Points on resulting inner rings with a distance of par_point_distance_threshold to others will be removed with function ST_RemoveRepeatedPoints. After this resulting inner rings will be removed completely if they have an area less than par_area_threshold.';

COMMIT;