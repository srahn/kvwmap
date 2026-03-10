BEGIN;
  CREATE TABLE IF NOT EXISTS xplankonverter.veroeffentlichungsnachweise (
    planart varchar NOT NULL DEFAULT 'bplan',
    plan_gml_id uuid NOT NULL,
    lfdnr integer NOT NULL,
    pruefzeit timestamp WITHOUT time ZONE NOT NULL,
    pruefergebnis varchar,
    gemeldet_am timestamp WITHOUT time ZONE
  );
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise ADD CONSTRAINT veroeffentlichungsnachweise_pk PRIMARY KEY (planart, plan_gml_id, lfdnr, pruefzeit);

  CREATE TABLE IF NOT EXISTS xplankonverter.veroeffentlichungsprotokolle (
    plan_gml_id uuid NOT NULL,
    auslegungsstartdatum date NOT NULL,
    auslegungsenddatum date NOT NULL,
    observationstart timestamp,
    observationend timestamp
  );
  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle ADD CONSTRAINT veroeffentlichungsprotokolle_pk PRIMARY KEY (plan_gml_id,auslegungsstartdatum,auslegungsenddatum);

  CREATE OR REPLACE VIEW xplankonverter.auslegungen AS
  SELECT
    *
  FROM 
    (
      SELECT
        'bplan' AS planart,
        b.gml_id,
        i AS lfdnr,
        b.auslegungsstartdatum[i] AS startdatum,
        b.auslegungsenddatum[i] AS enddatum
      FROM
        xplan_gml.bp_plan b CROSS JOIN LATERAL
        generate_subscripts(b.auslegungsstartdatum, 1) AS i
      UNION
      SELECT
        'fplan' AS planart,
        f.gml_id,
        i AS nr,
        f.auslegungsstartdatum[i] AS startdatum,
        f.auslegungsenddatum[i] AS enddatum
      FROM
        xplan_gml.fp_plan f CROSS JOIN LATERAL
        generate_subscripts(f.auslegungsstartdatum, 1) AS i
    ) AS auslegungen;

COMMIT;