BEGIN;
  CREATE OR REPLACE VIEW xplankonverter.auslegungen
  AS SELECT auslegungen.planart,
      auslegungen.plan_gml_id,
      auslegungen.lfdnr,
      auslegungen.startdatum,
      auslegungen.enddatum,
      k.veroeffentlichungsdatum
    FROM ( SELECT b.konvertierung_id,
              'BP-Plan'::text AS planart,
              b.gml_id AS plan_gml_id,
              i.i AS lfdnr,
              b.auslegungsstartdatum[i.i] AS startdatum,
              b.auslegungsenddatum[i.i] AS enddatum
            FROM xplan_gml.bp_plan b
              CROSS JOIN LATERAL generate_subscripts(b.auslegungsstartdatum, 1) i(i)
          UNION
          SELECT f.konvertierung_id,
              'FP-Plan'::text AS planart,
              f.gml_id AS plan_gml_id,
              i.i AS nr,
              f.auslegungsstartdatum[i.i] AS startdatum,
              f.auslegungsenddatum[i.i] AS enddatum
            FROM xplan_gml.fp_plan f
              CROSS JOIN LATERAL generate_subscripts(f.auslegungsstartdatum, 1) i(i)) auslegungen
      JOIN xplankonverter.konvertierungen k ON auslegungen.konvertierung_id = k.id;

  CREATE OR REPLACE FUNCTION xplankonverter.check_fassungsbezeichnung()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
  DECLARE
    msg text = '';
  BEGIN
    IF (NEW.fassungsbezeichnung IS NULL OR NEW.fassungsbezeichnung = '') THEN
      msg = E'\nEs wurde keine Fassungsbezeichnung wie Ursprungsplan oder 1. Änderung angegeben!';
      RAISE EXCEPTION '%', msg;
    ELSE
      IF (NOT NEW.fassungsbezeichnung ~* '\m(Ursprungsplan|Änderung|Ergänzung|Neuaufstellung|Erweiterung)\M') THEN 
        msg = E'\nEs muss einer der Begriffe Ursprungsplan, Änderung, Ergänzung, Neuaufstellung oder Erweiterung in der Fassungsbezeichnung vorkommen!';
        RAISE EXCEPTION '%', msg;
      END IF;
    END IF;
    RETURN NEW;
  END;
  $function$;

  DELETE FROM xplankonverter.veroeffentlichungsprotokoll_dokumente d
  WHERE NOT EXISTS (
    SELECT 1
    FROM xplankonverter.veroeffentlichungsprotokolle v
    WHERE v.id = d.protokoll_id
  );
  ALTER TABLE xplankonverter.veroeffentlichungsprotokoll_dokumente DROP CONSTRAINT IF EXISTS protokoll_id_fk;
  ALTER TABLE xplankonverter.veroeffentlichungsprotokoll_dokumente ADD CONSTRAINT protokoll_id_fk FOREIGN KEY (protokoll_id) REFERENCES xplankonverter.veroeffentlichungsprotokolle(id) ON UPDATE CASCADE ON DELETE CASCADE;

  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle DROP COLUMN pruefungen_seit_observationstart;
COMMIT;