BEGIN;
  -- Tabelle für planaufstellende Gemeinden
  CREATE TABLE xplankonverter.planaufstellende_gebietseinheiten WITH OIDS AS
  SELECT * FROM xplankonverter.gebietseinheiten;
  ALTER TABLE xplankonverter.planaufstellende_gebietseinheiten
  ADD CONSTRAINT planaufstellende_gebietseinheiten_pkey PRIMARY KEY (id_ot);
  CREATE INDEX planaufstellende_gebietseinheiten_stelle_id_idx ON xplankonverter.planaufstellende_gebietseinheiten
  USING btree (stelle_id ASC NULLS LAST);

  -- Umstellung auf extra Datentyp für planaufstellende Gemeinden
  CREATE TYPE xplan_gml.xp_planaufstellendegemeinde AS
  (
  	ags character varying,
  	rs character varying,
  	gemeindename character varying,
  	ortsteilname character varying
  );

  ALTER TABLE xplan_gml.bp_plan
  ALTER COLUMN planaufstellendegemeinde TYPE xplan_gml.xp_planaufstellendegemeinde[]
  USING planaufstellendegemeinde::text::xplan_gml.xp_planaufstellendegemeinde[];

  ALTER TABLE xplan_gml.fp_plan
  ALTER COLUMN planaufstellendegemeinde TYPE xplan_gml.xp_planaufstellendegemeinde[]
  USING planaufstellendegemeinde::text::xplan_gml.xp_planaufstellendegemeinde[];

  ALTER TABLE xplan_gml.so_plan
  ALTER COLUMN planaufstellendegemeinde TYPE xplan_gml.xp_planaufstellendegemeinde[]
  USING planaufstellendegemeinde::text::xplan_gml.xp_planaufstellendegemeinde[];

COMMIT;
