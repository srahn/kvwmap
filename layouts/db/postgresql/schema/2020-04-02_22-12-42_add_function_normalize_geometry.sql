BEGIN;

CREATE OR REPLACE FUNCTION public.gdi_array_remove(
	par_array anyarray,
	par_element anyelement)
    RETURNS anyarray
    LANGUAGE 'sql'

    COST 100
    VOLATILE 
AS $BODY$
    SELECT ARRAY(
        SELECT value 
            FROM unnest(PAR_array) AS value 
            WHERE CASE WHEN PAR_element IS null THEN (value IS NOT null) ELSE (NOT value = PAR_element) END
    );
$BODY$;
COMMENT ON FUNCTION public.gdi_array_remove(anyarray, anyelement)
    IS 'Löscht Elemente aus einem Array. Wird genutzt von der Funktion gdi_normalize_geometry.';

CREATE OR REPLACE FUNCTION public.gdi_normalize_geometry(
	par_geom geometry,
	par_area_threshold double precision,
	par_angle_threshold double precision,
	par_point_distance_threshold double precision,
	par_null_area double precision,
	par_union boolean DEFAULT true)
    RETURNS geometry
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
AS $BODY$
-- Full documentation at https://gasparesganga.com/labs/postgis-normalize-geometry/
DECLARE
    REC_linestrings record;
    ARR_output      geometry[];
    ARR_parts       geometry[];
    VAR_linestring  geometry;
    VAR_is_polygon  boolean;
    VAR_is_ring     boolean;
    VAR_tot         integer;
    VAR_old_tot     integer;
    VAR_n           integer;
    VAR_p0          geometry;
    VAR_p1          geometry;
    VAR_p2          geometry;
    VAR_area        double precision;
    VAR_output      geometry;
BEGIN
    PAR_area_threshold              := abs(PAR_area_threshold);
    PAR_angle_threshold             := radians(PAR_angle_threshold);
    PAR_point_distance_threshold    := abs(PAR_point_distance_threshold);
    PAR_null_area                   := COALESCE(PAR_null_area, 0);
    
    CASE ST_GeometryType(PAR_geom)
        WHEN 'ST_LineString', 'ST_MultiLineString' THEN
            VAR_is_polygon := false;
        WHEN 'ST_Polygon', 'ST_MultiPolygon' THEN
            VAR_is_polygon := true;
        ELSE
            RETURN PAR_geom;
    END CASE;
    
    ARR_output := '{}'::geometry[];
    FOR REC_linestrings IN EXECUTE $Q$
        SELECT array_agg(COALESCE(ext_rings, (rdump).geom) ORDER BY (rdump).path[1]) AS geoms 
            FROM (
                SELECT row_number() OVER (PARTITION BY rings) AS r, COALESCE(rings, source) AS rdump 
                    FROM                ST_Dump($1)                 AS source 
                    LEFT JOIN LATERAL   ST_DumpRings(source.geom)   AS rings    ON $2 
            ) AS d 
            LEFT JOIN LATERAL ST_ExteriorRing((rdump).geom) AS ext_rings    ON $2 
            GROUP BY r 
    $Q$ USING PAR_geom, VAR_is_polygon LOOP 
        ARR_parts := '{}'::geometry[];
        FOREACH VAR_linestring IN ARRAY REC_linestrings.geoms LOOP 
            VAR_tot := ST_NPoints(VAR_linestring);
            SELECT ST_IsClosed(VAR_linestring) INTO VAR_is_ring;
            IF VAR_is_ring THEN
                VAR_linestring  := ST_RemovePoint(VAR_linestring, VAR_tot - 1);
                VAR_tot         := VAR_tot - 1;
            END IF;
            LOOP
                VAR_old_tot := VAR_tot;
                VAR_n       := 1;
                WHILE VAR_n <= VAR_tot LOOP
                    LOOP 
                        EXIT WHEN VAR_tot < 3 OR VAR_n > VAR_tot;
                        VAR_p0   := ST_PointN(VAR_linestring, CASE WHEN VAR_n = 1 THEN VAR_tot ELSE VAR_n - 1 END);
                        VAR_p1   := ST_PointN(VAR_linestring, VAR_n);
                        VAR_p2   := ST_PointN(VAR_linestring, CASE WHEN VAR_n = VAR_tot THEN 1 ELSE VAR_n + 1 END);
                        VAR_area := ST_Area(ST_MakePolygon(ST_MakeLine(ARRAY[VAR_p0, VAR_p1, VAR_p2, VAR_p0])));
                        IF VAR_area > PAR_null_area THEN
                            EXIT WHEN VAR_area > PAR_area_threshold;
                            EXIT WHEN 
                                (abs(pi() - abs(ST_Azimuth(VAR_p0, VAR_p1) - ST_Azimuth(VAR_p1, VAR_p2))) > PAR_angle_threshold) 
                                AND (ST_Distance(VAR_p0, VAR_p1) > PAR_point_distance_threshold OR abs(pi() - abs(ST_Azimuth(VAR_p1, VAR_p2) - ST_Azimuth(VAR_p2, VAR_p0))) > PAR_angle_threshold) 
                                AND (ST_Distance(VAR_p1, VAR_p2) > PAR_point_distance_threshold OR abs(pi() - abs(ST_Azimuth(VAR_p2, VAR_p0) - ST_Azimuth(VAR_p0, VAR_p1))) > PAR_angle_threshold);
                        END IF;
                        VAR_linestring  := ST_RemovePoint(VAR_linestring, (CASE WHEN NOT VAR_is_polygon AND VAR_n = 1 AND VAR_area <= GREATEST(PAR_null_area, 0) THEN VAR_n ELSE VAR_n - 1 END));
                        VAR_tot         := VAR_tot - 1;
                    END LOOP;
                    VAR_n := VAR_n + 1;
                END LOOP;
                EXIT WHEN VAR_tot < 3 OR VAR_tot = VAR_old_tot;
            END LOOP;
            IF VAR_is_ring THEN 
                IF VAR_tot >= 3 THEN 
                    ARR_parts := array_append(ARR_parts, ST_AddPoint(VAR_linestring, ST_PointN(VAR_linestring, 1)));
                ELSIF NOT VAR_is_polygon THEN 
                    ARR_parts := array_append(ARR_parts, VAR_linestring);
                END IF;
            ELSE
                ARR_parts := array_append(ARR_parts, VAR_linestring);
            END IF;
        END LOOP;
        IF VAR_is_polygon THEN
            ARR_output := array_append(ARR_output, ST_MakePolygon(ARR_parts[1], gdi_array_remove(ARR_parts[2:array_upper(ARR_parts, 1)], null)));
        ELSE
            ARR_output := array_append(ARR_output, ARR_parts[1]);
        END IF;
    END LOOP;
    
    IF PAR_union THEN 
        SELECT ST_Union(ARR_output) INTO VAR_output;
    ELSE
        SELECT ST_Collect(ARR_output) INTO VAR_output;
        IF ST_NumGeometries(VAR_output) = 1 THEN 
            SELECT (ST_Dump(VAR_output)).geom INTO VAR_output;
        END IF;
    END IF;
    RETURN VAR_output;
END;
$BODY$;

COMMENT ON FUNCTION public.gdi_normalize_geometry(geometry, double precision, double precision, double precision, double precision, boolean)
    IS 'Normalisiert die Geometrie. Funktion ist übernommen von der Funktion normalize_geometry von Gaspare Sganga. Dabei werden zu spitze Winkel und kleine Flächen zwischen benachbarten Stützpunkten berücksichtigt. Der Algorithmus ist beschrieben unter https://gasparesganga.com/labs/postgis-normalize-geometry/. Nach der Behandlung von MultiPolygonen sollte ein MakeValid aufgerufen werden. Die Funktion benötigt die Funktion gdi_array_remove.';



COMMIT;
