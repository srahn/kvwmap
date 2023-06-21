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
    datetime timestamp without time zone,
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
  COMMENT ON TABLE xplankonverter.xplanvalidator_reports
    IS 'Ergebnisse der Prüfungen der xplanvalidator-Berichte.';
  ALTER TABLE IF EXISTS xplankonverter.xplanvalidator_reports
    ADD CONSTRAINT fk_xplanvalidator_reports FOREIGN KEY (konvertierung_id)
    REFERENCES xplankonverter.konvertierungen (id) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;


  CREATE TABLE IF NOT EXISTS xplankonverter.xplanvalidator_semantische_results (
    id serial NOT NULL PRIMARY KEY,
    xplanvalidator_report_id integer,
    name character varying,
    isvalid boolean,
    message text,
    invalidefeatures character varying[]
  );
  COMMENT ON TABLE xplankonverter.xplanvalidator_semantische_results
    IS 'Ergebnisse der semantischen Prüfungen der xplanvalidator-Berichte.';
		
	-- DELETE AND recreate foreign key if it exists already
	ALTER TABLE xplankonverter.xplanvalidator_semantische_results DROP CONSTRAINT IF EXISTS fk_xplanvalidator_semantische_results;
  ALTER TABLE xplankonverter.xplanvalidator_semantische_results
    ADD CONSTRAINT fk_xplanvalidator_semantische_results FOREIGN KEY (xplanvalidator_report_id)
    REFERENCES xplankonverter.xplanvalidator_reports (id) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;


COMMIT;
