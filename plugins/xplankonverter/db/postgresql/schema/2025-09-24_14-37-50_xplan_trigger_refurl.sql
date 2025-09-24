BEGIN;
CREATE OR REPLACE FUNCTION xplankonverter.check_xp_spezexternereferenz()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
DECLARE
	sql text;
	msg text = '';
BEGIN
-- Check if beschreibung is filled
	IF (to_json(new.externereferenz)::text IS NOT NULL 
		AND to_json(new.externereferenz)::text  LIKE '%"beschreibung":null%') THEN
		msg = msg || E'\nWenn ein Dokument(Externe Referenz) hinterlegt wurde, muss auch eine Beschreibung des Dokuments hinterlegt werden.';
	END IF;
-- Check if refurl is filled
	IF (to_json(new.externereferenz)::text IS NOT NULL 
		AND to_json(new.externereferenz)::text  LIKE '%"referenzurl":null%') THEN
		msg = msg || E'\nWenn ein Dokument(Externe Referenz) hinterlegt wurde, muss auch eine Datei oder URL (referenzurl) hinterlegt werden.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

ALTER FUNCTION xplankonverter.check_xp_spezexternereferenz()
    OWNER TO postgres;

  CREATE OR REPLACE TRIGGER check_xp_spezexternereferenz BEFORE
  INSERT OR UPDATE ON xplan_gml.bp_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_xp_spezexternereferenz();

  CREATE OR REPLACE  TRIGGER check_xp_spezexternereferenz BEFORE
  INSERT OR UPDATE ON xplan_gml.fp_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_xp_spezexternereferenz();

  CREATE OR REPLACE  TRIGGER check_xp_spezexternereferenz BEFORE
  INSERT OR UPDATE ON xplan_gml.so_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_xp_spezexternereferenz();

COMMIT;
