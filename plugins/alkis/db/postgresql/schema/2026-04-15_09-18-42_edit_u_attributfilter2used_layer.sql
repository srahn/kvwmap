BEGIN;

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
	    nullif(MAX(value) FILTER (WHERE name = 'LAYER_ID_FLURSTUECKE'), ''),
	    nullif(MAX(value) FILTER (WHERE name = 'LAYER_ID_EIGENTUEMER'), '')
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

COMMIT;
