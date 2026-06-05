BEGIN;
  CREATE OR REPLACE FUNCTION xplankonverter.get_generattr_value(attrs xplan_gml.xp_generattribut_erweitert[], name varchar)
  RETURNS varchar
  LANGUAGE plpgsql
  AS
  $$
  DECLARE
    attr xplan_gml.xp_generattribut_erweitert;
  BEGIN

    FOREACH attr IN ARRAY attrs
    LOOP
      IF attr.name = name THEN
        RETURN attr.wert;
      END IF;
    END LOOP;

    RETURN NULL;

  END;
  $$;

  COMMENT ON FUNCTION xplankonverter.get_generattr_value(xplan_gml.xp_generattribut_erweitert[], varchar)
  IS 'Liefert den Wert des Elements mit dem übergebenen Namen aus einem Array vom Typ xp_generattribut_erweitert zurück.';

  DROP VIEW IF EXISTS xplankonverter.alle_plaene;
  CREATE VIEW xplankonverter.alle_plaene AS
  SELECT
    gml_id,
    plantyp,
    anzeigename,
    geom,
    konvertierung_id
  FROM
    (
      SELECT
        p.gml_id,
        'B-Plan' AS plantyp,
        xplankonverter.bplan_anzeigename(p.name, p.planart, p.nummer, (p.gemeinde[1]).gemeindename) anzeigename,
        p.raeumlichergeltungsbereich AS geom,
        p.konvertierung_id
      FROM
        xplan_gml.bp_plan p
      UNION
      SELECT
        f.gml_id,
        'F-Plan' AS plantyp,
        xplankonverter.fplan_anzeigename(f.name, f.planart, f.nummer, (f.gemeinde[1]).gemeindename) anzeigename,
        f.raeumlichergeltungsbereich AS geom,
        f.konvertierung_id
      FROM
        xplan_gml.fp_plan f
      UNION
      SELECT
        so.gml_id,
        'SO-Plan' AS plantyp,
        xplankonverter.soplan_anzeigename(so.name, so.planart, so.nummer, (so.gemeinde[1]).gemeindename) anzeigename,
        so.raeumlichergeltungsbereich AS geom,
        so.konvertierung_id
      FROM
        xplan_gml.so_plan so
    ) alle;
COMMIT;