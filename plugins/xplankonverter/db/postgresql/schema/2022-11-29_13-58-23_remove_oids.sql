BEGIN;

  ALTER TABLE xplankonverter.konvertierungen SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.regeln SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.shapefiles SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.validierungen SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.validierungsergebnisse SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.xplanvalidator_reports SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.xplanvalidator_semantische_results SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.uml_class2konformitaeten SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.planaufstellende_gebietseinheiten SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.mappingtable_standard_shp_to_db SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.mappingtable_gmlas_to_gml SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.layer_colors SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.konformitaetsbedingungen SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.inspire_regeln SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.gebietseinheiten SET WITHOUT OIDS;
  ALTER TABLE xplankonverter.flaechenschlussobjekte SET WITHOUT OIDS;

  CREATE TABLE IF NOT EXISTS xplankonverter.geltungsbereiche(
    nummer serial PRIMARY KEY NOT NULL,
    konvertierung_id integer,
    plan_gml_id character varying,
    plan_name character varying,
    plan_nummer character varying,
    stand date,
    geom geometry(MultiPolygon)
  );

	TRUNCATE xplankonverter.geltungsbereiche;

  INSERT INTO xplankonverter.geltungsbereiche (konvertierung_id, plan_gml_id, plan_name, plan_nummer, stand, geom)
  SELECT
    k.id,
    p.gml_id,
    p.name,
    p.nummer,
    p.wirksamkeitsdatum,
    p.raeumlichergeltungsbereich
  FROM
    xplankonverter.konvertierungen k JOIN
    xplan_gml.fp_plan p ON k.id = p.konvertierung_id
  WHERE
    NOT p.zusammenzeichnung;

  INSERT INTO xplankonverter.geltungsbereiche (konvertierung_id, plan_gml_id, plan_name, plan_nummer, stand, geom)
  SELECT
    k.id,
    p.gml_id,
    p.name,
    p.nummer,
    p.inkrafttretensdatum,
    p.raeumlichergeltungsbereich
  FROM
    xplankonverter.konvertierungen k JOIN
    xplan_gml.bp_plan p ON k.id = p.konvertierung_id
  WHERE
    NOT p.zusammenzeichnung;

  INSERT INTO xplankonverter.geltungsbereiche (konvertierung_id, plan_gml_id, plan_name, plan_nummer, stand, geom)
  SELECT
    k.id,
    p.gml_id,
    p.name,
    p.nummer,
    p.datumdesinkrafttretens,
    p.raeumlichergeltungsbereich
  FROM
    xplankonverter.konvertierungen k JOIN
    xplan_gml.rp_plan p ON k.id = p.konvertierung_id
  WHERE
    NOT p.zusammenzeichnung;

  INSERT INTO xplankonverter.geltungsbereiche (konvertierung_id, plan_gml_id, plan_name, plan_nummer, stand, geom)
  SELECT
    k.id,
    p.gml_id,
    p.name,
    p.nummer,
    p.genehmigungsdatum,
    p.raeumlichergeltungsbereich
  FROM
    xplankonverter.konvertierungen k JOIN
    xplan_gml.so_plan p ON k.id = p.konvertierung_id
  WHERE
    NOT p.zusammenzeichnung;

COMMIT;
