BEGIN;
  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle ADD COLUMN IF NOT EXISTS lfdnr integer NOT NULL;
  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle ADD COLUMN IF NOT EXISTS id serial NOT NULL;
  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle DROP CONSTRAINT IF EXISTS veroeffentlichungsprotokolle_pk;
  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle ADD CONSTRAINT veroeffentlichungsprotokolle_pk PRIMARY KEY (id);

  DROP VIEW IF EXISTS xplankonverter.auslegungen;
  CREATE VIEW xplankonverter.auslegungen AS
  SELECT
    *
  FROM 
    (
      SELECT
        'bplan' AS planart,
        b.gml_id AS plan_gml_id,
        i AS lfdnr,
        b.auslegungsstartdatum[i] AS startdatum,
        b.auslegungsenddatum[i] AS enddatum
      FROM
        xplan_gml.bp_plan b CROSS JOIN LATERAL
        generate_subscripts(b.auslegungsstartdatum, 1) AS i
      UNION
      SELECT
        'fplan' AS planart,
        f.gml_id AS plan_gml_id,
        i AS nr,
        f.auslegungsstartdatum[i] AS startdatum,
        f.auslegungsenddatum[i] AS enddatum
      FROM
        xplan_gml.fp_plan f CROSS JOIN LATERAL
        generate_subscripts(f.auslegungsstartdatum, 1) AS i
    ) AS auslegungen;

  CREATE OR REPLACE FUNCTION xplankonverter.num_veroeff_meldungen(_plan_gml_id uuid, _lfdnr int4)
  RETURNS integer
  LANGUAGE sql
  AS $function$
  SELECT
    count(*)
  FROM
    xplankonverter.veroeffentlichungsnachweise
  WHERE
    plan_gml_id = _plan_gml_id AND
    lfdnr = _lfdnr AND
    gemeldet_am IS NOT NULL;
  $function$;

  CREATE VIEW xplankonverter.auslegung_dokumente AS
  SELECT
    docs.*,
    t.beschreibung AS typ_beschreibung
  FROM
    (
      SELECT
        a.plan_gml_id,
        a.lfdnr,
        (UNNEST(COALESCE(b.externereferenz, f.externereferenz))).art doc_art,
        (UNNEST(COALESCE(b.externereferenz, f.externereferenz))).referenzurl AS doc_url,
        (UNNEST(COALESCE(b.externereferenz, f.externereferenz))).beschreibung doc_beschreibung,
        (UNNEST(COALESCE(b.externereferenz, f.externereferenz))).datum doc_datum,
        (UNNEST(COALESCE(b.externereferenz, f.externereferenz))).typ::text::integer typ_wert
      FROM
        xplankonverter.auslegungen a LEFT JOIN
        xplan_gml.bp_plan b ON a.plan_gml_id = b.gml_id LEFT JOIN
        xplan_gml.fp_plan f ON a.plan_gml_id = f.gml_id
    ) docs LEFT JOIN
    xplan_gml.enum_xp_externereferenztyp t ON docs.typ_wert = t.wert;

  CREATE TABLE IF NOT EXISTS xplankonverter.veroeffentlichungsprotokoll_dokumente (
    id serial NOT NULL PRIMARY KEY,
    protokoll_id integer NOT NULL,
    doc_art varchar NOT NULL,
    doc_url varchar NOT NULL,
    doc_beschreibung varchar,
    doc_datum date,
    typ_beschreibung varchar,
    doc_hash varchar
  );

COMMIT;