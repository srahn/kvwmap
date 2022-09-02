BEGIN;

  CREATE TABLE IF NOT EXISTS xplankonverter.xplanvalidator_reports (
    id serial NOT NULL PRIMARY KEY,
    konvertierung_id integer,
    version character varying,
    filename character varying,
    name character varying,
    box_minx double precision,
    box_miny double precision,
    box_maxx double precision,
    box_maxy double precision,
    box_crs character varying,
    datetime timestamp,
    valid boolean,
    externalreferences character varying[],
    wmsurl character varying,
    rulesmetadata_version character varying,
    rulesmetadata_source character varying,
    semantisch_valid boolean,
    geometrisch_valid boolean,
    geometrisch_errors character varying[],
    geometrisch_warnings character varying[],
    syntaktisch_valid boolean,
    syntaktisch_messages character varying[]
  );
  COMMENT ON TABLE xplankonverter.xplanvalidator_reports IS 'Berichte über die Validierung von XPlanGML-Dokumenten von xplanungsplattform.de/xplan-validator/';

  CREATE TABLE IF NOT EXISTS xplankonverter.xplanvalidator_semantische_results (
    xplanvalidator_report_id integer,
    name character varying,
    isvalid boolean,
    message text,
    invalidefeatures character varying[]
  );
  ALTER TABLE IF EXISTS xplankonverter.xplanvalidator_semantische_results
    ADD CONSTRAINT fk_xplanvalidator_semantische_results FOREIGN KEY (xplanvalidator_report_id)
    REFERENCES xplankonverter.xplanvalidator_reports (id) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;
  COMMENT ON TABLE xplankonverter.xplanvalidator_reports IS 'Ergebnisse der semantischen Prüfungen der xplanvalidator-Berichte.';

  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'GML-Validierung abgeschlossen' AFTER 'GML-Erstellung abgeschlossen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'GML-Validierung mit Fehlern' AFTER 'GML-Validierung abgeschlossen';
COMMIT;