BEGIN;
  DROP VIEW xplankonverter.auslegung_dokumente;
  DROP VIEW xplankonverter.auslegungen;
  CREATE OR REPLACE VIEW xplankonverter.auslegungen
  AS SELECT auslegungen.planart,
      auslegungen.plan_gml_id,
      auslegungen.lfdnr,
      auslegungen.startdatum,
      auslegungen.enddatum,
      k.veroeffentlichungsdatum
    FROM ( SELECT
              b.konvertierung_id,
              'BP-Plan'::text AS planart,
              b.gml_id AS plan_gml_id,
              i.i AS lfdnr,
              b.auslegungsstartdatum[i.i] AS startdatum,
              b.auslegungsenddatum[i.i] AS enddatum
            FROM xplan_gml.bp_plan b
              CROSS JOIN LATERAL generate_subscripts(b.auslegungsstartdatum, 1) i(i)
          UNION
          SELECT
              f.konvertierung_id, 
              'FP-Plan'::text AS planart,
              f.gml_id AS plan_gml_id,
              i.i AS nr,
              f.auslegungsstartdatum[i.i] AS startdatum,
              f.auslegungsenddatum[i.i] AS enddatum
            FROM xplan_gml.fp_plan f
              CROSS JOIN LATERAL generate_subscripts(f.auslegungsstartdatum, 1) i(i)
      ) auslegungen JOIN
      xplankonverter.konvertierungen k ON auslegungen.konvertierung_id = k.id
    WHERE
      k.veroeffentlicht;

  CREATE OR REPLACE VIEW xplankonverter.auslegung_dokumente
  AS SELECT docs.plan_gml_id,
      docs.lfdnr,
      docs.doc_art,
      docs.doc_url,
      docs.doc_beschreibung,
      docs.doc_datum,
      docs.typ_wert,
      t.beschreibung AS typ_beschreibung
    FROM ( SELECT a.plan_gml_id,
              a.lfdnr,
              (unnest(COALESCE(b.externereferenz, f.externereferenz))).art AS doc_art,
              (unnest(COALESCE(b.externereferenz, f.externereferenz))).referenzurl AS doc_url,
              (unnest(COALESCE(b.externereferenz, f.externereferenz))).beschreibung AS doc_beschreibung,
              (unnest(COALESCE(b.externereferenz, f.externereferenz))).datum AS doc_datum,
              (unnest(COALESCE(b.externereferenz, f.externereferenz))).typ::text::integer AS typ_wert
            FROM xplankonverter.auslegungen a
              LEFT JOIN xplan_gml.bp_plan b ON a.plan_gml_id = b.gml_id
              LEFT JOIN xplan_gml.fp_plan f ON a.plan_gml_id = f.gml_id) docs
      LEFT JOIN xplan_gml.enum_xp_externereferenztyp t ON docs.typ_wert = t.wert;

  ALTER TABLE xplankonverter.veroeffentlichungsnachweise DROP CONSTRAINT IF EXISTS veroeffentlichungsnachweise_pk;
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise ADD COLUMN IF NOT EXISTS id serial NOT NULL;
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise ADD CONSTRAINT veroeffentlichungsnachweise_pk PRIMARY KEY (id);
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise ADD COLUMN IF NOT EXISTS pruefcode integer;
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise DROP CONSTRAINT IF EXISTS fk_veroeffentlichungsnachweise_pruefcode;
  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle DROP CONSTRAINT IF EXISTS fk_veroeffentlichungsprotokolle_pruefcode;
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise RENAME COLUMN pruefzeit TO pruefstunde;

  CREATE TABLE IF NOT EXISTS xplankonverter.pruefcodes (
    code integer not null Primary Key,
    bezeichnung varchar,
    beschreibung text
  );
  TRUNCATE TABLE xplankonverter.pruefcodes;
  INSERT INTO xplankonverter.pruefcodes(code, bezeichnung, beschreibung) VALUES
  (0, 'Plan veröffentlicht', 'Plan Gml-ID auf Detail-Seite des Plans im Portal gefunden.'),
  (1, 'Plan nicht gefunden', 'Plan Gml-ID nicht auf der abgefragen Web-Seite des Portals gefunden.'),
  (2, 'Web-Seite nicht erreichbar', 'Http-Fehler-Code != 200'),
  (3, 'keine Verbindung', 'Es konnte keine Verbindung zum Portral aufgebaut werden.'),
  (4, 'Unerwartete Antwort', 'In der Antwort des Server steht weder die GML-ID des Plans noch der Text Plan nicht gefunden!');

  DO $$
  BEGIN
    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE 
          table_schema = 'xplankonverter' AND
          table_name = 'veroeffentlichungsnachweise' AND
          column_name = 'pruefergebnis'
    ) THEN
        EXECUTE '
          UPDATE
            xplankonverter.veroeffentlichungsnachweise n
          SET
            pruefcode = c.code
          FROM
            xplankonverter.pruefcodes c
          WHERE
            c.bezeichnung = n.pruefergebnis
        ';
    END IF;
  END $$;

  ALTER TABLE xplankonverter.veroeffentlichungsnachweise
    ADD CONSTRAINT fk_veroeffentlichungsnachweise_pruefcode FOREIGN KEY (pruefcode) REFERENCES xplankonverter.pruefcodes(code) ON UPDATE CASCADE ON DELETE NO ACTION,
    DROP COLUMN IF EXISTS pruefergebnis,
    ADD COLUMN IF NOT EXISTS protokoll_id integer;

  UPDATE xplankonverter.veroeffentlichungsnachweise v
  SET protokoll_id = p.id
  FROM xplankonverter.veroeffentlichungsprotokolle p
  WHERE
    v.plan_gml_id = p.plan_gml_id AND
    v.lfdnr = p.lfdnr;
  
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise ALTER COLUMN protokoll_id SET NOT NULL;
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise DROP CONSTRAINT IF EXISTS fk_veroeff_protokoll_id;
  ALTER TABLE xplankonverter.veroeffentlichungsnachweise ADD CONSTRAINT fk_veroeff_protokoll_id FOREIGN KEY (protokoll_id) REFERENCES xplankonverter.veroeffentlichungsprotokolle(id) ON UPDATE CASCADE ON DELETE CASCADE;

  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle ADD COLUMN IF NOT EXISTS last_pruefcode int4 NULL;
  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle ADD COLUMN IF NOT EXISTS last_pruefung timestamp without time zone NULL;
  ALTER TABLE xplankonverter.veroeffentlichungsprotokolle ADD CONSTRAINT fk_veroeffentlichungsprotokolle_pruefcode FOREIGN KEY (last_pruefcode) REFERENCES xplankonverter.pruefcodes(code) ON UPDATE CASCADE ON DELETE NO ACTION;

  CREATE TABLE IF NOT EXISTS xplankonverter.veroeffentlichungsnachweis_luecken (
    id serial4 NOT NULL,
    protokoll_id int4 NULL,
    gap_start timestamp without time zone NULL,
    gap_end timestamp without time zone NULL,
    CONSTRAINT nachweisluecken_pkey PRIMARY KEY (id)
  );
  ALTER TABLE xplankonverter.veroeffentlichungsnachweis_luecken DROP CONSTRAINT IF EXISTS fk_veroeff_luecken_protokoll_id;
  ALTER TABLE xplankonverter.veroeffentlichungsnachweis_luecken ADD CONSTRAINT fk_veroeff_luecken_protokoll_id FOREIGN KEY (protokoll_id) REFERENCES xplankonverter.veroeffentlichungsprotokolle(id) ON UPDATE CASCADE ON DELETE CASCADE;

  DROP FUNCTION xplankonverter.num_veroeff_meldungen(uuid, int4);
  CREATE OR REPLACE FUNCTION xplankonverter.num_veroeff_meldungen(_protokoll_id integer)
  RETURNS integer
  LANGUAGE sql
  AS $function$
    SELECT
      count(*)
    FROM
      xplankonverter.veroeffentlichungsnachweise
    WHERE
      protokoll_id = _protokoll_id AND
      gemeldet_am IS NOT NULL;
    $function$;

  CREATE OR REPLACE FUNCTION xplankonverter.num_nachweis_luecken(_protokoll_id integer)
  RETURNS integer
  LANGUAGE sql
  AS $function$
    SELECT
      count(*)
    FROM
      xplankonverter.veroeffentlichungsnachweis_luecken
    WHERE
      protokoll_id = _protokoll_id;
    $function$;

  CREATE OR REPLACE FUNCTION xplankonverter.sum_nachweis_luecken(_protokoll_id integer)
  RETURNS integer
  LANGUAGE sql
  AS $function$
    SELECT
      EXTRACT(epoch FROM sum(gap_end - gap_start)) / 3600
    FROM
      xplankonverter.veroeffentlichungsnachweis_luecken
    WHERE
      protokoll_id = _protokoll_id;
    $function$;

  CREATE OR REPLACE FUNCTION xplankonverter.remove_auslegung(_planart varchar, _gml_id uuid, _startdatum varchar, _enddatum varchar)
  RETURNS void
  LANGUAGE plpgsql
  AS $$
  DECLARE
    del_id int;
  BEGIN

    SELECT id INTO del_id
    FROM
      xplankonverter.veroeffentlichungsprotokolle
    WHERE
      plan_gml_id = _gml_id AND
      auslegungsstartdatum = _startdatum::date AND
      auslegungsenddatum = _enddatum::date;

    DELETE FROM xplankonverter.veroeffentlichungsnachweise WHERE protokoll_id = del_id;

    DELETE FROM xplankonverter.veroeffentlichungsnachweis_luecken WHERE protokoll_id = del_id;

    DELETE FROM xplankonverter.veroeffentlichungsprotokoll_dokumente WHERE protokoll_id = del_id;

    DELETE FROM xplankonverter.veroeffentlichungsprotokolle WHERE id = del_id;

    IF (_planart = 'BP-Plan') THEN
      UPDATE
        xplan_gml.bp_plan
      SET
        auslegungsstartdatum = array_remove(auslegungsstartdatum, _startdatum::date),
        auslegungsenddatum = array_remove(auslegungsenddatum, _enddatum::date)
      WHERE
        gml_id = _gml_id;
    END IF;

    IF (_planart = 'FP-Plan') THEN
      UPDATE
        xplan_gml.fp_plan
      SET
        auslegungsstartdatum = array_remove(auslegungsstartdatum, _startdatum::date),
        auslegungsenddatum = array_remove(auslegungsenddatum, _enddatum::date)
      WHERE
        gml_id = _gml_id;
    END IF;
  END;
  $$;

COMMIT;