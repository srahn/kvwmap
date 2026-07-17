BEGIN;

CREATE OR REPLACE FUNCTION kvwmap.create_stelle_gemeinden_entries(p_stelle_id integer, polygon_id integer, tablename character varying, operation varchar)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
DECLARE
	
BEGIN
	EXECUTE format(
	    'DELETE FROM kvwmap.%I WHERE stelle_id = $1',
	    tablename
	)
	USING p_stelle_id;

	IF operation != 'DELETE' THEN
		EXECUTE format(
		$SQL$
		
		WITH flurstuecke AS (
			SELECT
				f.land||f.gemeindezugehoerigkeit_regierungsbezirk||f.gemeindezugehoerigkeit_kreis||f.gemeindezugehoerigkeit_gemeinde AS gemeinde_id,
				f.land||f.gemarkungsnummer AS gemarkung,
				f.flurnummer AS flur,
				f.zaehler,
				f.nenner
			FROM kvwmap.u_polygon p
			JOIN alkis.ax_flurstueck f
				ON f.wkb_geometry && p.the_geom
				AND ST_Intersects(p.the_geom, f.wkb_geometry)
			WHERE p.id = $2
			  AND f.endet IS NULL
		),
		
		gesamt AS (
			SELECT
				f.land||f.gemeindezugehoerigkeit_regierungsbezirk||f.gemeindezugehoerigkeit_kreis||f.gemeindezugehoerigkeit_gemeinde AS gemeinde_id,
				f.land||f.gemarkungsnummer AS gemarkung,
				f.flurnummer AS flur,
				COUNT(*) AS cnt
			FROM alkis.ax_flurstueck f
			WHERE endet IS NULL
			GROUP BY 1,2,3
		),
		
		teil AS (
			SELECT
				gemeinde_id,
				gemarkung,
				flur,
				COUNT(*) AS cnt
			FROM flurstuecke
			GROUP BY 1,2,3
		),
		
		gemeinden AS (
			SELECT t.gemeinde_id
			FROM (
				SELECT gemeinde_id, SUM(cnt) AS cnt
				FROM teil
				GROUP BY gemeinde_id
			) t
			JOIN (
				SELECT gemeinde_id, SUM(cnt) AS cnt
				FROM gesamt
				GROUP BY gemeinde_id
			) g USING (gemeinde_id)
			WHERE t.cnt = g.cnt
		),
		
		gemarkungen AS (
			SELECT t.gemeinde_id, t.gemarkung
			FROM (
				SELECT gemeinde_id, gemarkung, SUM(cnt) AS cnt
				FROM teil
				GROUP BY 1,2
			) t
			JOIN (
				SELECT gemeinde_id, gemarkung, SUM(cnt) AS cnt
				FROM gesamt
				GROUP BY 1,2
			) g USING (gemeinde_id, gemarkung)
			WHERE t.cnt = g.cnt
			AND NOT EXISTS (
				SELECT 1 FROM gemeinden gm
				WHERE gm.gemeinde_id = t.gemeinde_id
			)
		),
		
		fluren AS (
			SELECT t.gemeinde_id, t.gemarkung, t.flur
			FROM teil t
			JOIN gesamt g
				USING (gemeinde_id, gemarkung, flur)
			WHERE t.cnt = g.cnt
			AND NOT EXISTS (
				SELECT 1 FROM gemeinden gm
				WHERE gm.gemeinde_id = t.gemeinde_id
			)
			AND NOT EXISTS (
				SELECT 1 FROM gemarkungen gk
				WHERE gk.gemeinde_id = t.gemeinde_id
				  AND gk.gemarkung = t.gemarkung
			)
		),
		
		flurstuecke_rest AS (
			SELECT f.*
			FROM flurstuecke f
			WHERE NOT EXISTS (
				SELECT 1 FROM gemeinden gm
				WHERE gm.gemeinde_id = f.gemeinde_id
			)
			AND NOT EXISTS (
				SELECT 1 FROM gemarkungen gk
				WHERE gk.gemeinde_id = f.gemeinde_id
				  AND gk.gemarkung = f.gemarkung
			)
			AND NOT EXISTS (
				SELECT 1 FROM fluren fl
				WHERE fl.gemeinde_id = f.gemeinde_id
				  AND fl.gemarkung = f.gemarkung
				  AND fl.flur = f.flur
			)
		)
		
		INSERT INTO kvwmap.%I (
			stelle_id, gemeinde_id, gemarkung, flur, flurstueck
		)
		SELECT $1, gemeinde_id::integer, NULL::integer, NULL::integer, NULL
		FROM gemeinden
		UNION ALL
		SELECT $1, gemeinde_id::integer, gemarkung::integer, NULL::integer, NULL
		FROM gemarkungen
		UNION ALL
		SELECT $1, gemeinde_id::integer, gemarkung::integer, flur::integer, NULL
		FROM fluren
		UNION ALL
		SELECT
			$1,
			gemeinde_id::integer,
			gemarkung::integer,
			flur::integer,
			zaehler || COALESCE('/' || nenner, '')
		FROM flurstuecke_rest
		
		$SQL$,
		tablename
		)
		USING p_stelle_id, polygon_id;
	END IF;

END;
$function$
;



CREATE OR REPLACE FUNCTION kvwmap.edit_u_attributfilter2used_layer()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
	layer_id_flurstuecke INTEGER;
	layer_id_eigentuemer INTEGER;
	rec RECORD;
BEGIN
    rec := COALESCE(NEW, OLD);
	SELECT
	    MAX(value) FILTER (WHERE name = 'LAYER_ID_FLURSTUECKE'),
	    MAX(value) FILTER (WHERE name = 'LAYER_ID_EIGENTUEMER')
	INTO
	    layer_id_flurstuecke,
	    layer_id_eigentuemer
	FROM kvwmap.config;

	IF rec.layer_id = layer_id_flurstuecke AND rec.type = 'geometry' THEN
		PERFORM kvwmap.create_stelle_gemeinden_entries(rec.stelle_id, rec.attributvalue::integer, 'stelle_gemeinden', TG_OP);
	END IF;

	IF rec.layer_id = layer_id_eigentuemer AND rec.type = 'geometry' THEN 
		PERFORM kvwmap.create_stelle_gemeinden_entries(rec.stelle_id, rec.attributvalue::integer, 'stelle_gemeinden_eigentuemer', TG_OP);
	END IF;
	
	RETURN NULL;
END;
$function$
;


create trigger edit_u_attributfilter2used_layer after
insert
    or
update
		or
delete
    on
    kvwmap.u_attributfilter2used_layer for each row execute function kvwmap.edit_u_attributfilter2used_layer();


COMMIT;
