BEGIN;
  ALTER TABLE xplankonverter.import_jobs ADD COLUMN IF NOT EXISTS upload_file varchar NULL;

  CREATE OR REPLACE FUNCTION plandigitalisierung.update_plankennzeichnung()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
  BEGIN
    IF ((TG_OP = 'INSERT' OR TG_OP = 'UPDATE') AND NEW.rechtsstand::text = '4000') THEN
      --RAISE NOTICE 'Update Plankennzeichnung';
      INSERT INTO
        plandigitalisierung.plankennzeichnung (gml_id_origin,konvertierung_id_origin,stelle_id,marked_for_digitalization)
      SELECT
        x.gml_id AS gml_id_origin,
        x.konvertierung_id AS konvertierung_id_origin,
        x.stelle_id AS stelle_id,
        FALSE as marked_fordigitalization
      FROM
        (
          SELECT bp.gml_id,bp.konvertierung_id,k.stelle_id
          FROM
            xplan_gml.bp_plan bp LEFT JOIN
            xplankonverter.konvertierungen k ON bp.konvertierung_id = k.id
          WHERE
            rechtsstand::text = '4000' AND
            gml_id NOT IN (
              SELECT gml_id_origin FROM plandigitalisierung.plankennzeichnung
            )
        ) AS x;
      -- This update has to be performed, because there is an after trigger in kvwmap.php for the xplankonverter plugin
      -- that could change konvertierung_id after the original insert in plnakennzeichnung was performed
      UPDATE plandigitalisierung.plankennzeichnung
      SET konvertierung_id_origin = subquery.konvertierung_id
      FROM (SELECT konvertierung_id,gml_id FROM xplan_gml.bp_plan) AS subquery
      WHERE gml_id_origin = subquery.gml_id AND gml_id_origin = NEW.gml_id;
    END IF;
    IF (TG_OP = 'DELETE') THEN
      DELETE FROM plandigitalisierung.plankennzeichnung WHERE gml_id_origin = OLD.gml_id;
    END IF;
  RETURN NULL;
  END;
  $function$;

  DROP TRIGGER update_plankennzeichnung ON xplan_gml.bp_plan;
  CREATE TRIGGER update_plankennzeichnung AFTER
  INSERT
    OR
  UPDATE
    OR
  DELETE
    ON xplan_gml.bp_plan FOR EACH ROW EXECUTE FUNCTION plandigitalisierung.update_plankennzeichnung();

COMMIT;