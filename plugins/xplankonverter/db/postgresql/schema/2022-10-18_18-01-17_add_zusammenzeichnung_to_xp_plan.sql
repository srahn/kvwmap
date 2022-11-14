BEGIN;
  ALTER TABLE xplan_gml.xp_plan ADD COLUMN zusammenzeichnung boolean NOT NULL DEFAULT false;
  COMMENT ON COLUMN xplan_gml.fp_plan.zusammenzeichnung IS 'zusammenzeichnung boolean 0..1: Kennzeichnet einen Plan als eine Zusammenzeichnung von rechtsgültigen Planelementen.';
  UPDATE xplan_gml.xp_plan SET zusammenzeichnung = true WHERE name LIKE '%Zusammenzeichnung%';

  CREATE OR REPLACE VIEW xplankonverter.num_plaene AS
  SELECT
    k.stelle_id,
    k.planart,
    p.zusammenzeichnung,
    count(p.gml_id) AS num_aenderungsplaene
  FROM
    xplan_gml.fp_plan p JOIN
    xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
  GROUP BY
    k.stelle_id,
    k.planart,
    p.zusammenzeichnung;
COMMIT;
