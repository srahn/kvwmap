BEGIN;

  INSERT INTO kvwmap.config ("name", "prefix", "value", "description", "type", "plugin", "group", "saved", "editable") VALUES (
    'GRSTSICH_RECHTE_LAYER_ID',
    '',
    '',
    'ID des Layers, der die Rechte enthält.',
    'string',
    'grundstueckssicherung',
    'Plugins/grundstueckssicherung',
    0,
    2
  );

  CREATE SCHEMA IF NOT EXISTS grstsich;

  CREATE TABLE grstsich.bundeslaender (
    id serial NOT NULL PRIMARY KEY,
    beginn date NULL,
    gf int2 NULL,
    bsg int2 NULL,
    ags varchar NULL,
    sdv_ars varchar NULL,
    bezeichnung varchar NULL,
    art varchar NULL,
    nuts varchar NULL,
    wsk date NULL,
    the_geom public.geometry(multipolygon, 25832) NULL,
    abk bpchar(2) NULL
  );
  CREATE INDEX bundeslaender_the_geom_geom_idx ON grstsich.bundeslaender USING gist (the_geom);

  CREATE TABLE grstsich.projekte (
    id serial NOT NULL PRIMARY KEY,
    bezeichnung varchar NOT NULL,
    beschreibung text NULL,
    bundesland_id integer,
    geom public.geometry(multipolygon, 5650) NULL
  );
  CREATE INDEX planung_projektgebiete_geom_gist ON grstsich.projekte USING gist (geom);

  CREATE TABLE grstsich.cl_projektkategorien (
    id serial NOT NULL PRIMARY KEY,
    bezeichnung varchar NOT NULL
  );

  CREATE TABLE grstsich.cl_projektstati (
    id serial NOT NULL PRIMARY KEY,
    bezeichnung varchar NOT NULL,
    beschreibung text NULL
  );

  CREATE TABLE grstsich.cl_quellen (
    id serial4 NOT NULL PRIMARY KEY,
    bezeichnung varchar NOT NULL
  );

  CREATE TABLE grstsich.mandanten (
    id serial NOT NULL,
    bezeichnung varchar NOT NULL,
    CONSTRAINT mandanten_pk PRIMARY KEY (id)
  );

  CREATE TABLE grstsich.vorhabensgebiete (
    id serial NOT NULL,
    bezeichnung varchar NOT NULL,
    kategorie_id int4 NULL,
    projekt_id int4 NULL,
    geom public.geometry(polygon, 25833) NULL,
    CONSTRAINT vorhabensgebiete_pk PRIMARY KEY (id)
  );
  CREATE INDEX vorhabensgebiete_geom_gist ON grstsich.vorhabensgebiete USING gist (geom);

  CREATE TABLE grstsich.cl_vorhabenskategorien (
    id serial4 NOT NULL,
    bezeichnung varchar NOT NULL,
    CONSTRAINT cl_vorhabenskategorien_pkey PRIMARY KEY (id)
  );

  ALTER TABLE grstsich.vorhabensgebiete ADD CONSTRAINT vorhabensgebiete_projekt_id_fk FOREIGN KEY (projekt_id) REFERENCES grstsich.projekte(id);
  ALTER TABLE grstsich.vorhabensgebiete ADD CONSTRAINT vorhabensgebiete_cl_vorhabenskategorien_fk FOREIGN KEY (kategorie_id) REFERENCES grstsich.cl_vorhabenskategorien(id);

  CREATE TABLE grstsich.vorhabensrechtearten (
    vorhabensgebiet_id int4 NOT NULL,
    rechteart_id int4 NOT NULL,
    id serial4 NOT NULL,
    CONSTRAINT vorhabensrechtearten_pk PRIMARY KEY (id)
  );
  ALTER TABLE grstsich.vorhabensrechtearten ADD CONSTRAINT vorhabensrechtearten_vorhabensgebiete_fk FOREIGN KEY (vorhabensgebiet_id) REFERENCES grstsich.vorhabensgebiete(id);
  ALTER TABLE grstsich.vorhabensrechtearten ADD CONSTRAINT vorhabensrechtearten_cl_rechtearten_fk FOREIGN KEY (rechteart_id) REFERENCES grstsich.cl_rechtearten(id);

  CREATE TABLE grstsich.flurstuecksrechte (
    id serial NOT NULL,
    rechteart_id integer NOT NULL,
    flurstueckskennzeichen varchar NOT NULL,
    sicherungsstand_id integer DEFAULT 0 NOT NULL,
    CONSTRAINT flurstuecksrechte_pk PRIMARY KEY (id)
  );

  ALTER TABLE grstsich.flurstuecksrechte ADD CONSTRAINT flurstuecksrechte_cl_rechtearten_fk FOREIGN KEY (rechte_art_id) REFERENCES grstsich.cl_rechtearten(id);
  ALTER TABLE grstsich.flurstuecksrechte ADD CONSTRAINT flurstuecksrechte_sicherungsstand_fk FOREIGN KEY (sicherungsstand_id) REFERENCES grstsich.cl_sicherungsstaende(id);

  CREATE TABLE grstsich.teilprojekte (
    id integer NOT NULL,
    bezeichnung varchar NOT NULL,
    vorhabensgebiet_id integer NULL,
    mandant_id integer NULL,
    geom public.geometry(multipolygon, 25833) NULL,
    CONSTRAINT teilprojekte_pk PRIMARY KEY (id)
  );
  CREATE INDEX teilprojekte_geom_gist ON grstsich.teilprojekte USING gist (geom);
  ALTER TABLE grstsich.teilprojekte ADD CONSTRAINT teilprojekte_mandanten_fk FOREIGN KEY (mandant_id) REFERENCES grstsich.mandanten(id);
  ALTER TABLE grstsich.teilprojekte ADD CONSTRAINT teilprojekte_vorhabensgebiete_fk FOREIGN KEY (vorhabensgebiet_id) REFERENCES grstsich.vorhabensgebiete(id);

  CREATE TABLE grstsich.teilprojekte_ (
    id serial NOT NULL,
    point public.geometry(point, 25833) NULL,
    weissflaeche public.geometry(multipolygon, 25833) NULL,
    bemerkung varchar NULL,
    email varchar NULL,
    range_m int4 NULL,
    bundesland_id int4 NULL,
    status_id int4 NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    landkreisnamen varchar NULL,
    gemeindenamen varchar NULL,
    "name" varchar NULL,
    nummer varchar NULL,
    flaeche_ha varchar NULL,
    bundeslandnamen text NULL,
    standortanfrage_id int4 NULL,
    min_area_qm int4 NULL,
    projekt_id int4 NULL,
    projektkategorie_id int4 NULL,
    mandant_id int4 NULL,
    quelle_id int4 NULL,
    geom public.geometry(Polygon, 25833) NULL,
    CONSTRAINT planungsgebiete_pk PRIMARY KEY (id)
  );
  CREATE INDEX planungsgebiete_geom_gist ON grstsich.teilprojekte_ USING gist (geom);
  CREATE INDEX planungsgebiete_weissflaeche_gist ON grstsich.teilprojekte_ USING gist (weissflaeche);

  ALTER TABLE grstsich.teilprojekte_ ADD CONSTRAINT planungsgebiete_projekt_id_fk FOREIGN KEY (projekt_id) REFERENCES grstsich.projekte(id);
  ALTER TABLE grstsich.teilprojekte_ ADD CONSTRAINT teilprojekte__cl_projektkategorien_fk FOREIGN KEY (projektkategorie_id) REFERENCES grstsich.cl_projektkategorien(id);
  ALTER TABLE grstsich.teilprojekte_ ADD CONSTRAINT teilprojekte__cl_projektstati_fk FOREIGN KEY (status_id) REFERENCES grstsich.cl_projektstati(id);
  ALTER TABLE grstsich.teilprojekte_ ADD CONSTRAINT teilprojekte__cl_quellen_fk FOREIGN KEY (quelle_id) REFERENCES grstsich.cl_quellen(id);
  ALTER TABLE grstsich.teilprojekte_ ADD CONSTRAINT teilprojekte__mandanten_fk FOREIGN KEY (mandant_id) REFERENCES grstsich.mandanten(id);

  CREATE OR REPLACE FUNCTION grstsich.set_bundesland_id()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
    DECLARE
    BEGIN
      IF (TG_OP = 'INSERT' AND NEW.point IS NOT NULL) OR (TG_OP = 'UPDATE' AND NOT ST_Equals(OLD.point, NEW.point)) THEN
        NEW.bundesland_id := (SELECT id FROM grstsich.bundeslaender WHERE ST_Within(ST_Transform(NEW.point, ST_Srid(the_geom)), the_geom));
        RAISE NOTICE '{"success": true, "msg_type": "notice", "msg" : "%"}', '<p>Bundesland Id ' || NEW.bundesland_id || ' zugeordnet.';
      END IF;
      IF (to_jsonb(NEW) ? 'weissflaeche') THEN
        IF (NEW.point IS NULL AND NEW.weissflaeche IS NOT NULL) THEN
          NEW.bundesland_id := (SELECT id FROM grstsich.bundeslaender WHERE ST_Within(ST_Transform(ST_PointOnSurface(NEW.weissflaeche), ST_Srid(the_geom)), the_geom));
        END IF;
      END IF;
      RETURN NEW;
    END;
  $function$;

  CREATE TRIGGER tr_05_set_bundesland_id BEFORE
  INSERT
      OR
  UPDATE
      ON
      grstsich.teilprojekte_ FOR EACH ROW EXECUTE FUNCTION grstsich.set_bundesland_id();

  CREATE TABLE grstsich.landkreise (
    gid serial NOT NULL PRIMARY KEY,
    the_geom public.geometry(multipolygon, 25833) NULL,
    kennung varchar NULL,
    gen varchar NULL,
    schluessel int4 NULL,
    zugehoerig int4 NULL,
    aufnahme varchar NULL,
    verwaltung varchar NULL
  );
  CREATE INDEX landkreise_the_geom_geom_idx ON grstsich.landkreise USING gist (the_geom);

  CREATE TABLE grstsich.gemeinden (
    id serial NOT NULL PRIMARY KEY,
    beginn date NULL,
    ags varchar NULL,
    bezeichnung varchar NULL,
    art varchar NULL,
    bem varchar NULL,
    nuts varchar NULL,
    wsk date NULL,
    the_geom public.geometry(multipolygon, 25832) NULL
  );
  CREATE INDEX gemeinden_the_geom_geom_idx ON grstsich.gemeinden USING gist (the_geom);

  CREATE OR REPLACE FUNCTION grstsich.attributberechnungen()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
    DECLARE
    BEGIN
      IF (NEW.weissflaeche IS NOT NULL) THEN
        NEW.bundeslandnamen = (SELECT string_agg(bezeichnung, ' - ') FROM grstsich.bundeslaender WHERE ST_Intersects(ST_Transform(NEW.weissflaeche, ST_Srid(the_geom)), the_geom));
        NEW.landkreisnamen = (SELECT string_agg(gen, ' - ') FROM grstsich.landkreise WHERE ST_Intersects(ST_Transform(NEW.weissflaeche, ST_Srid(the_geom)), the_geom));
        NEW.gemeindenamen = (SELECT string_agg(bezeichnung, ' - ') FROM grstsich.gemeinden WHERE ST_Transform(NEW.weissflaeche, ST_Srid(the_geom)) && the_geom AND ST_Intersects(ST_Transform(NEW.weissflaeche, ST_Srid(the_geom)), the_geom));
        NEW.flaeche_ha = round(ST_Area(NEW.weissflaeche)::numeric, 4);
      END IF;
      NEW.updated_at = now();
      RETURN NEW;
    END;
  $function$;

  CREATE TRIGGER tr_15_attributberechnung BEFORE
  INSERT
      OR
  UPDATE
      ON
      grstsich.teilprojekte_ FOR EACH ROW EXECUTE FUNCTION grstsich.attributberechnungen();

  CREATE OR REPLACE FUNCTION grstsich.update_weissflaeche()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
    DECLARE
      ausschlusstabellen text[] := (SELECT array_agg(CONCAT_WS(':', CONCAT_WS('.', schemaname, tabellenname), abstand_m, filter)) FROM grstsich.ausschlusskriterien);
      range_m_default integer := 5000;
      min_area_qm_default integer := 10000;
    BEGIN
      IF (TG_OP = 'INSERT' AND NEW.point IS NOT NULL AND NEW.weissflaeche IS NULL) THEN
        NEW.weissflaeche := public.gdi_subtract_geometries_from_schematables(
          ST_Expand(NEW.point, COALESCE(NEW.range_m, range_m_default)),
          ausschlusstabellen,
          COALESCE(NEW.min_area_qm, min_area_qm_default)::double precision
        );
        RAISE NOTICE '{"success": true, "msg_type": "notice", "msg" : "%"}', '<p>Weißfläche mit Flächengröße : ' || round(ST_Area(NEW.weissflaeche)::numeric/10000::numeric, 4) || ' ha berechnet.';
      END IF;
      IF (TG_OP = 'UPDATE' AND NEW.weissflaeche IS NOT NULL) THEN
        NEW.weissflaeche := public.gdi_subtract_geometries_from_schematables(
          NEW.weissflaeche,
          ausschlusstabellen,
          COALESCE(NEW.min_area_qm, min_area_qm_default)::double precision
        );
      END IF;
      RETURN NEW;
    END;
  $function$;


  CREATE TRIGGER tr_10_update_weissflaeche BEFORE
  UPDATE
      OF weissflaeche ON
      grstsich.teilprojekte_ FOR EACH ROW EXECUTE FUNCTION grstsich.update_weissflaeche();

  CREATE TABLE grstsich.cl_sicherungsstaende (
    id serial NOT NULL,
    bezeichnung varchar NOT NULL,
    CONSTRAINT cl_sicherungsstaende_pk PRIMARY KEY (id),
    CONSTRAINT cl_sicherungsstaende_unique UNIQUE (bezeichnung)
  );

  CREATE TABLE grstsich.nutzungsrechte (
    id serial NOT NULL,
    bezeichnung varchar NOT NULL,
    art_id int4 NULL,
    vertrag_id int4 NULL,
    flurstueck varchar NULL,
    flurstueckszuordnung varchar NULL,
    geom public.geometry(geometry, 25833) NULL,
    eigentuemerzuordnung varchar NULL,
    grundbuchperson_id varchar NULL,
    wea_id int4 NULL,
    feature_id int4 NULL,
    layer_id int4 NULL,
    teilprojekt_id integer,
    sicherungsstand_id integer NOT NULL DEFAULT 0,
    CONSTRAINT nutzungsrechte_pkey PRIMARY KEY (id)
  );
  CREATE INDEX fki_nutzungsrechte_vertraege_fkey ON grstsich.nutzungsrechte USING btree (vertrag_id);

  CREATE TABLE grstsich.cl_rechtearten (
    id serial NOT NULL,
    bezeichnung varchar NOT NULL,
    CONSTRAINT cl_rechtearten_pkey PRIMARY KEY (id)
  );

  CREATE TABLE grstsich.personen (
    id serial NOT NULL,
    unterschriftsberechtigt bool NULL,
    vormund_id int4 NULL,
    personenart_id int4 NULL,
    personstatus_id int4 NULL,
    eigentuemerflurstueck bool NULL,
    telefonnummer varchar NULL,
    email varchar NULL,
    fax varchar NULL,
    website varchar NULL,
    strasse varchar NULL,
    hausnummer varchar NULL,
    plz varchar NULL,
    gemeinde varchar NULL,
    bundesland varchar NULL,
    freie_notizen text NULL,
    sm_facebook varchar NULL,
    sm_instagram varchar NULL,
    sm_linkedin varchar NULL,
    letzteinteraktion timestamp NULL,
    person_id int4 NULL,
    CONSTRAINT personen_pkey PRIMARY KEY (id)
  );

  CREATE TABLE grstsich.cl_personenarten (
    id serial NOT NULL,
    personenart varchar NULL,
    CONSTRAINT cl_personenarten_pkey PRIMARY KEY (id)
  );

  CREATE TABLE grstsich.cl_personstati (
    id serial NOT NULL,
    personstatus varchar NULL,
    reihenfolge int4 NULL,
    CONSTRAINT cl_personstati_pkey PRIMARY KEY (id)
  );
  ALTER TABLE grstsich.personen ADD CONSTRAINT personen_personenart_fkey FOREIGN KEY (personenart_id) REFERENCES grstsich.cl_personenarten(id) ON UPDATE CASCADE;
  ALTER TABLE grstsich.personen ADD CONSTRAINT personen_personstatus_id_fkey FOREIGN KEY (personstatus_id) REFERENCES grstsich.cl_personstati(id) ON UPDATE CASCADE;
  ALTER TABLE grstsich.personen ADD CONSTRAINT vormund_id_fkey FOREIGN KEY (vormund_id) REFERENCES grstsich.personen(id) ON UPDATE CASCADE;

  CREATE TABLE grstsich.vertraege (
    id serial NOT NULL,
    bezeichnung varchar NOT NULL,
    mandant_id int4 NULL,
    person_id int4 NULL,
    grundbuchperson_id bpchar(16) NULL,
    CONSTRAINT vertraege_pkey PRIMARY KEY (id)
  );

  ALTER TABLE grstsich.vertraege ADD CONSTRAINT vertraege_mandanten_fk FOREIGN KEY (mandant_id) REFERENCES grstsich.mandanten(id);
  ALTER TABLE grstsich.vertraege ADD CONSTRAINT vertraege_personen_fk FOREIGN KEY (person_id) REFERENCES grstsich.personen(id);

  CREATE OR REPLACE FUNCTION grstsich.flurstueckszuordnung_recht()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
    DECLARE
      _sql text;
      _flurstueck record;
    BEGIN
      IF (NEW.flurstueck IS NOT NULL) THEN
        _sql = format('
          SELECT
            ''Gemkg: '' || gk.bezeichnung || '' Flur: '' || f.flurnummer || '' Flst: '' || f.zaehler || COALESCE(''/'' || f.nenner, '''') AS flurstueckszuordnung,
            p.gml_id AS grundbuchperson_id,
            CONCAT_WS('' '',
              ap.beschreibung,
              p.nachnameoderfirma,
              p.vorname,
              ''geborene: '' || p.geburtsname,
              ''(geb: '' || to_char(p.geburtsdatum::date, ''dd.mm.YYYY'') || '')'',
              a.strasse,
              a.hausnummer,
              COALESCE(a.postleitzahlpostzustellung, a.postleitzahlpostfach),
              COALESCE(a.ort_post, p.wohnortodersitz),
              ((ea.beschreibung::text || '' (''::text) || n.eigentuemerart) || '')''::text
            ) AS eigentuemerzuordnung,
            f.wkb_geometry AS geom
          FROM
            alkis.ax_flurstueck f JOIN
            alkis.ax_gemarkung gk ON f.land = gk.land AND f.gemarkungsnummer = gk.gemarkungsnummer LEFT JOIN
            alkis_auszug.ax_buchungsstelle s ON f.istgebucht = s.gml_id::bpchar OR (f.gml_id = ANY (s.verweistauf::bpchar[])) OR (f.istgebucht = ANY (s.an::bpchar[])) LEFT JOIN
            alkis_auszug.ax_buchungsblatt g ON s.istbestandteilvon::text = g.gml_id::text LEFT JOIN
            alkis_auszug.ax_namensnummer n ON g.gml_id::text = n.istbestandteilvon::text LEFT JOIN
            alkis_auszug.ax_person p ON n.benennt::text = p.gml_id::text LEFT JOIN
            alkis.ax_artderrechtsgemeinschaft_namensnummer arg ON n.artderrechtsgemeinschaft = arg.wert LEFT JOIN
            alkis.ax_eigentuemerart_namensnummer ea ON n.eigentuemerart = ea.wert LEFT JOIN
            alkis_auszug.ax_anschrift a ON a.gml_id::text = ANY (p.hat::text[]) LEFT JOIN
            alkis.ax_anrede_person ap ON p.anrede = ap.wert
          WHERE
            f.endet IS NULL AND
            gk.endet IS NULL AND
            a.processstep_organisationname = ''{Grundbuch}''::character varying[] AND
            f.flurstueckskennzeichen = ''%1$s''
        ',
        NEW.flurstueck);
        RAISE NOTICE 'SQL: %', _sql;

        EXECUTE _sql INTO _flurstueck;
        NEW.flurstueckszuordnung = _flurstueck.flurstueckszuordnung;
        NEW.grundbuchperson_id = _flurstueck.grundbuchperson_id;
        NEW.eigentuemerzuordnung = _flurstueck.eigentuemerzuordnung;
        IF (TG_OP = 'INSERT' AND NEW.geom IS NULL) THEN
          NEW.geom = _flurstueck.geom;
        END IF;
      END IF;
      RETURN NEW;
    END;
  $function$;

  CREATE TRIGGER tr_10_flurstueckszuordnung BEFORE
  INSERT OR UPDATE
    OF flurstueckszuordnung
    ON grstsich.nutzungsrechte
    FOR EACH ROW EXECUTE FUNCTION grstsich.flurstueckszuordnung_recht();

  ALTER TABLE grstsich.nutzungsrechte ADD CONSTRAINT nutzungsrechte_art_fkey FOREIGN KEY (art_id) REFERENCES grstsich.cl_rechtearten(id) ON UPDATE CASCADE;
  ALTER TABLE grstsich.nutzungsrechte ADD CONSTRAINT nutzungsrechte_vertraege_fk FOREIGN KEY (vertrag_id) REFERENCES grstsich.vertraege(id);
  ALTER TABLE grstsich.nutzungsrechte ADD CONSTRAINT nutzungsrechte_teilprojekt_fk FOREIGN KEY (teilprojekt_id) REFERENCES grstsich.teilprojekte_(id) ON UPDATE CASCADE;
  ALTER TABLE grstsich.nutzungsrechte ADD CONSTRAINT nutzungsrechte_sicherungsstand_fkey FOREIGN KEY (sicherungsstand_id) REFERENCES grstsich.cl_sicherungsstaende(id) ON UPDATE CASCADE;

  CREATE OR REPLACE FUNCTION grstsich.find_bundesland(_geom geometry)
  RETURNS character
  LANGUAGE plpgsql
  STABLE STRICT
  AS $function$

  DECLARE
    _result character(2);
  BEGIN
    SELECT ST_Transform(_geom, 25832) INTO _geom;
    SELECT
      b.abk INTO _result
    FROM
      (
        SELECT
          ST_Area(ST_Intersection(_geom, the_geom)) AS area,    
          abk
        FROM
          grstsich.bundeslaender
        WHERE
          st_intersects(_geom, the_geom)
        ORDER BY
          area DESC
        LIMIT 1
      ) b;

    -- if _result NULL suche die abk des dichtesten Bundeslandes
    IF _result IS NULL THEN
      SELECT
        b.abk INTO _result
      FROM
        (
          SELECT
            abk
          FROM
            grstsich.bundeslaender
          ORDER BY
            _geom <-> the_geom -- KNN search, faster than ST_Distance
          LIMIT 1
        ) b;
    END IF;

    return _result;
  END;
  $function$;

  CREATE OR REPLACE FUNCTION grstsich.find_gemeinde(_geom geometry)
  RETURNS character varying
  LANGUAGE plpgsql
  STABLE STRICT
  AS $function$

  DECLARE
    _result character varying;
  BEGIN
    SELECT ST_Transform(_geom, 25832) INTO _geom;
    SELECT
      b.bezeichnung INTO _result
    FROM
      (
        SELECT
          ST_Area(ST_Intersection(_geom, the_geom)) AS area,    
          bezeichnung
        FROM
          grstsich.gemeinden
        WHERE
          st_intersects(_geom, the_geom)
        ORDER BY
          area DESC
        LIMIT 1
      ) b;

    -- if _result NULL suche die bezeichnung des dichtesten Bundeslandes
    IF _result IS NULL THEN
      SELECT
        b.bezeichnung INTO _result
      FROM
        (
          SELECT
            bezeichnung
          FROM
            grstsich.gemeinden
          ORDER BY
            _geom <-> the_geom -- KNN search, faster than ST_Distance
          LIMIT 1
        ) b;
    END IF;

    return _result;
  END;
  $function$;

COMMIT;