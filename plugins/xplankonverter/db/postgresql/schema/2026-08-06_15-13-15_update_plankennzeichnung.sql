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

  CREATE OR REPLACE FUNCTION plandigitalisierung.update_import_job()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
  BEGIN
    IF (NEW.status != OLD.status AND NEW.status = 'Importjob angelegt') THEN
      INSERT INTO xplankonverter.import_jobs (import_service_id, import_type, upload_file)
      SELECT
        id AS import_service_id,
        'gml' AS import_type,
        'digitalisierte_plaene/' || stelle_id || '/' || dateiname || '.zip' AS upload_file
      FROM
        plandigitalisierung.uploads f
      WHERE
        dateiname = NEW.dateiname AND
        NOT EXISTS (
          SELECT 1
          FROM 
            xplankonverter.import_jobs i
          WHERE
            i.upload_file LIKE 'digitalisierte_plaene/%/' || NEW.dateiname || '.zip'
        );
    END IF;
    RETURN NEW;
  END;
  $function$;

  DROP TRIGGER IF EXISTS update_import_job ON plandigitalisierung.uploads;
  CREATE TRIGGER update_import_job AFTER
  UPDATE OF status
  ON plandigitalisierung.uploads FOR EACH ROW
  EXECUTE FUNCTION plandigitalisierung.update_import_job();

  ALTER TABLE plandigitalisierung.uploads ALTER COLUMN status SET DEFAULT 'Plan hochgeladen'::character varying;
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Originalplan zugeordnet' AFTER 'Plan hochgeladen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Importjob angelegt' AFTER 'Originalplan zugeordnet';
COMMIT;