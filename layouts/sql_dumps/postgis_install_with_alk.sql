-- Installationsskript für die Datenbankstruktur, die kvwmap für Postgres mit PostGIS benötigt.
--
-- Voraussetzungen/Vorarbeiten
--
-- Ein Postgres version ab 4.7.3 ist installiert
--
-- Zusätzlich ist PostGIS ab 1.0 mit GEOS und Proj Unterstützung installiert
--
-- Zusätzlich wurde ein Datenbank in Postgres erzeugt.
-- Der Name der Datenbank wird in config.php angepasst.

--###########################
--# Starte eine Transaktion #
--###########################
--# START TRANSACTION;

--###############################################################
--# Zusätzliche Funktionen zum Selektieren von einzelnen        #
--# Liniensegmenten aus einem Polygon 2007-07-17 pk             #
--# Die Funktionen müssen in dieser Reihenfolge erzeugt werden! #
--###############################################################

-- Tabelle für andere Dokumentarten in der Nachweisverwaltung

CREATE TABLE n_dokumentarten
(
   id serial NOT NULL, 
   art character varying(100)
) 
WITH OIDS;
ALTER TABLE n_dokumentarten OWNER TO kvwmap;

-- Tabelle für die Zuordnung von Nachweisen zu anderen Dokumentarten

CREATE TABLE n_nachweise2dokumentarten
(
   nachweis_id integer NOT NULL, 
   dokumentart_id integer NOT NULL
) 
WITH OIDS;
ALTER TABLE n_nachweise2dokumentarten OWNER TO kvwmap;


--# Tabelle für die Aliasnamen der Koordinatensysteme
CREATE TABLE spatial_ref_sys_alias
(
  srid integer NOT NULL,
  alias character varying(256),
  CONSTRAINT spatial_ref_sys_alias_pkey PRIMARY KEY (srid)
)
WITH OIDS;

-- Tabelle für Metainformationen

CREATE TABLE tabelleninfo
(
  thema character varying(10),
  datum character varying(10)
)
WITH OIDS;

-- Tabelle für Adressänderungen

CREATE TABLE alb_g_namen_temp
(
  neu_name3 character varying(52),
  neu_name4 character varying(52),
  user_id integer,
  datum timestamp without time zone,
  name1 character varying(52),
  name2 character varying(52),
  name3 character varying(52),
  name4 character varying(52)
)
WITH OIDS;

--# Anlegen der Tabellen für die Fachscale Anliegerbeiträge

CREATE TABLE anliegerbeitraege_bereiche
(
  id serial NOT NULL,
  flaeche real,
  kommentar character varying(255),
  CONSTRAINT anliegerbeitraege_bereiche_pkey PRIMARY KEY (id)
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'anliegerbeitraege_bereiche','the_geom',2398,'GEOMETRY', 2);

CREATE TABLE anliegerbeitraege_strassen
(
  id serial NOT NULL,
  CONSTRAINT anliegerbeitraege_strassen_pkey PRIMARY KEY (id)
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'anliegerbeitraege_strassen','the_geom',2398,'GEOMETRY', 2);


--# Anlegen der Tabelle zum Speichern von beliebigen Polygonen

CREATE TABLE frei_polygon
(
  id serial NOT NULL,
  kommentar character varying(255)
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'frei_polygon','the_geom',2398,'GEOMETRY', 2);

CREATE TABLE shp_import_tables
(
  tabellenname character varying(255) NOT NULL
) 
WITH OIDS;

--# Tabellen für Dokumente
CREATE TABLE doc_doc2geoname
(
  doc_id int8 NOT NULL,
  geoname_id int8 NOT NULL
) 
WITHOUT OIDS;

CREATE TABLE doc_documents
(
  id serial NOT NULL,
  filename varchar(255),
  CONSTRAINT doc_documents_pkey PRIMARY KEY (id)
) 
WITHOUT OIDS;

CREATE TABLE doc_tempwords
(
  begriff varchar(75)
) 
WITHOUT OIDS;

CREATE TABLE doc_words
(
  begriff varchar(75) NOT NULL
) 
WITHOUT OIDS;

CREATE TABLE gaz_begriffe
(
  id serial NOT NULL,
  bezeichnung varchar(75) NOT NULL,
  kurzbezeichnung varchar(50),
  ueberbegriff int4,
  CONSTRAINT gaz_begriffe_pkey PRIMARY KEY (id)
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'gaz_begriffe', 'wgs_geom', 4326, 'POINT', 2);
CREATE INDEX gaz_begriffe_gist ON gaz_begriffe USING GIST (wgs_geom GIST_GEOMETRY_OPS );

-- Function: linefrompoly(geometry)
-- Liefert eine LINESTRING Gemetrie von einer MULTIPOLYGON oder POLYGON Geometrie zurück
-- DROP FUNCTION linefrompoly(geometry); 
CREATE OR REPLACE FUNCTION linefrompoly(geometry)
  RETURNS geometry AS
  $BODY$SELECT GeomFromText(replace(replace(replace(asText($1),'MULTIPOLYGON','LINESTRING'),'(((','('),')))',')'),srid($1))$BODY$
  LANGUAGE 'sql' IMMUTABLE STRICT;
ALTER FUNCTION linefrompoly(geometry) OWNER TO postgres;
COMMENT ON FUNCTION linefrompoly(geometry) IS 'Liefert eine LINESTRING Gemetrie von einer MULTIPOLYGON oder POLYGON Geometrie zurück';

-- Function: linen(geometry, int4)
-- Liefert die n-te Linien innerhalb eines Polygon als Geometry zurück
-- DROP FUNCTION linen(geometry, int4);
CREATE OR REPLACE FUNCTION linen(geometry, int4)
  RETURNS geometry AS
  $BODY$SELECT GeomFromText('LINESTRING('||X(pointn(linefrompoly($1),$2))||' '||Y(pointn(linefrompoly($1),$2))||','||X(pointn(linefrompoly($1),$2+1))||' '||Y(pointn(linefrompoly($1),$2+1))||')',srid($1))$BODY$
  LANGUAGE 'sql' IMMUTABLE STRICT;
ALTER FUNCTION linen(geometry, int4) OWNER TO postgres;
COMMENT ON FUNCTION linen(geometry, int4) IS 'Liefert die n-te Linien innerhalb eines Polygon als Geometry zurück';

-- Function: snapline(geometry, geometry)
-- Liefert die einzelne Kante eines LINESTRINGS mit der Geometry1, welche am dichtesten am Punkt mit der Geometrie 2 liegt als Geometry
-- DROP FUNCTION snapline(geometry, geometry);
CREATE OR REPLACE FUNCTION snapline(geometry, geometry)
  RETURNS geometry AS
  $BODY$DECLARE
  i integer;
  mindist float;
  rs RECORD;
  output geometry;
  BEGIN
    mindist = 1000;
    FOR i IN 1..NumPoints($1) LOOP
      SELECT INTO rs linen($1,i) AS linegeom, distance(linen($1,i),$2) AS dist;
      IF rs.dist < mindist THEN
        BEGIN
          mindist := rs.dist;
          output := rs.linegeom;
        END;
      END IF;
    END LOOP;
    RETURN output;
  END;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION snapline(geometry, geometry) OWNER TO postgres;
COMMENT ON FUNCTION snapline(geometry, geometry) IS 'Liefert die einzelne Kante eines LINESTRINGS mit der Geometry1, welche am dichtesten am Punkt mit der Geometrie 2 liegt als Geometry';
-- Beispiel zur Abfrage der Gebäudekante des gegebenen Objektes, welches am dichtesten zum gegebenen Punkt liegt und dessen Azimutwinkel.
-- SELECT AsText(snapline(linefrompoly(the_geom),GeomFromText('Point(4516219.4 6013803.0)',2398))) AS Segment
-- ,azimuth(pointn(snapline(linefrompoly(the_geom),GeomFromText('Point(4516219.4 6013803.0)',2398)),1),pointn(snapline(linefrompoly(the_geom),GeomFromText('Point(4516219.4 6013803.0)',2398)),2)) AS winkel
-- FROM alkobj_e_fla WHERE objnr = 'D0009O1'

--###########################
--# Tabellen für Jagdkataster
--# 2006-07-26 pk
--# Tabelle zur Speicherung der Jagdbezirke
CREATE TABLE jagdbezirke
(
  id int4 NOT NULL,
  art varchar, -- mögliche Werte gjb, ejb, tjb
  jagdbezirk int4,
  flaeche float4,
  name varchar(255),
  CONSTRAINT jagdbezirke_pkey PRIMARY KEY (id),
  CONSTRAINT art CHECK (art::text = 'gjb'::text OR art::text = 'ejb'::text OR art::text = 'tjb'::text OR art::text = 'sf'::text)
) 
WITH OIDS;
COMMENT ON TABLE jagdbezirke IS 'Befriedete und unbefriedete, unterteilte und nicht unterteilte Jagdbezirke, Eigenjagdbezirke oder Teiljagdbezirke';
COMMENT ON COLUMN jagdbezirke.art IS 'mögliche Werte gjb, ejb, tjb';
SELECT AddGeometryColumn('public', 'jagdbezirke','the_geom',2398,'POLYGON', 2);
CREATE INDEX jagdbezirke_the_geom_gist ON jagdbezirke USING GIST (the_geom GIST_GEOMETRY_OPS);
ALTER TABLE jagdbezirke DROP CONSTRAINT enforce_geotype_the_geom;
ALTER TABLE jagdbezirke ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);

--# Tabelle zur Speicherung der Jagdpaechter
CREATE TABLE jagdpaechter
(
  id serial NOT NULL,
  name varchar(255),
  weiteres varchar(255),
  CONSTRAINT jagdpaechter_pkey PRIMARY KEY (id)
) 
WITH OIDS;
COMMENT ON TABLE jagdpaechter IS 'Paechter von Jagdbezirken';

--# Tabelle zur Speicherung der Zuordnung der Paechter zur den Jagdbezirken
CREATE TABLE jagdpaechter2bezirke
(
  bezirkid int4 NOT NULL,
  paechterid int4 NOT NULL,
  CONSTRAINT jagdpaechter2bezirke_pkey PRIMARY KEY (bezirkid, paechterid)
) 
WITH OIDS;

--# Tabelle zur Speicherung der Jagdabschussplanung
CREATE TABLE jagdabschussplanung
(
  bezirkid int4 NOT NULL,
  von int4 NOT NULL,
  bis int4 NOT NULL,
  rehwild int4,
  damwild int4,
  schwarzwild int4,
  muffelwild int4,
  antragsdatum date,
  genehmigung varchar(20),
  wiederspruchsdatum date,
  CONSTRAINT jagdabschussplanung_pkey PRIMARY KEY (bezirkid, von, bis)
) 
WITH OIDS;


--# Tabelle zur Speicherung der Bauleitplanungsänderungen

CREATE TABLE bp_aenderungen
(
  id serial NOT NULL,
  username varchar(255),
  datum date,
  hinweis varchar(255),
  bemerkung varchar(255),
  loeschdatum timestamp,
  loeschusername varchar(255),
  CONSTRAINT bp_aenderungen_pkey PRIMARY KEY (id)
) 
WITH OIDS;

SELECT AddGeometryColumn('public', 'bp_aenderungen','the_geom',2398,'POLYGON', 2);
CREATE INDEX bp_aenderungen_the_geom_gist ON bp_aenderungen USING GIST (the_geom GIST_GEOMETRY_OPS);
ALTER TABLE bp_aenderungen DROP CONSTRAINT enforce_geotype_the_geom;
ALTER TABLE bp_aenderungen ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);


-- # Hinzufügen einer Tabelle u_polygon zur Speicherung von Polygonen

CREATE TABLE u_polygon
(
  id serial NOT NULL,
  CONSTRAINT u_polygon_pkey PRIMARY KEY (id)
) 
WITH OIDS;

SELECT AddGeometryColumn('public', 'u_polygon','the_geom',2398,'MULTIPOLYGON', 2);
CREATE INDEX u_polygon_the_geom_gist ON u_polygon USING GIST (the_geom GIST_GEOMETRY_OPS);


--# Tabelle zur Speicherung der Gemarkungsnummer-zu-Gemarkungsschlüssel-Beziehung für die Bauauskunft

CREATE TABLE bau_gemarkungen
(
  nummer int8 NOT NULL,
  schluessel int8 NOT NULL
) 
WITHOUT OIDS;


--###########################
--# Tabelle für Bauaktendaten
--# 2006-01-26 pk
CREATE TABLE bau_akten
(
  feld1 int4,
  feld2 int4,
  feld3 int4,
  feld4 varchar(255),
  feld5 varchar(255),
  feld6 varchar(255),
  feld7 varchar(255),
  feld8 varchar(255),
  feld9 varchar(255),
  feld10 varchar(255),
  feld11 varchar(255),
  feld12 varchar(20),
  feld13 varchar(20),
  feld14 varchar(20),
  feld15 varchar(10),
  feld16 varchar(10),
  feld17 varchar(20),
  feld18 varchar(20),
  feld19 varchar(30),
  feld20 varchar(30),
  feld21 varchar(30),
  feld22 varchar(6),
  feld23 int4,
  feld24 varchar(30),
  dummy varchar(1)
) 
WITH OIDS;

--# Hinzufügen der Tabellen bau_verfahrensart und bau_vorhaben, in denen die zur Auswahl stehenden Werte für das Vorhaben und die Verfahrensart bei der Bauauskunftssuche gespeichert sind
CREATE TABLE bau_verfahrensart
(
  verfahrensart text,
  id serial NOT NULL
) 
WITHOUT OIDS;

CREATE TABLE bau_vorhaben
(
  vorhaben text,
  id serial NOT NULL
) 
WITHOUT OIDS;

--##################################################
--# Tabelle für Fehlerellipsen
--# 2005-11-29 Korduan
CREATE TABLE q_fehlerellipsen
(
  pkz varchar(15) NOT NULL,
  rw numeric(15,4),
  hw numeric(15,4),
  hoe numeric(8,4),
  mfge numeric(6,2),
  ls integer,
  phi numeric(5,2),
  a numeric(6,2),
  b numeric(6,2),
  CONSTRAINT q_fehlerellipsen_pkey PRIMARY KEY (pkz)
)
WITH OIDS;
SELECT AddGeometryColumn('public', 'q_fehlerellipsen','the_geom',2398,'POINT', 2);
CREATE INDEX q_fehlerellipsen_the_geom_gist ON q_fehlerellipsen USING GIST (the_geom GIST_GEOMETRY_OPS);

--##################################
--# Qualität der Flurstücksflächen #
--##################################
CREATE TABLE q_alknflst
(
  objnr varchar(7) NOT NULL DEFAULT ''::character varying,
  verhandelt integer NOT NULL DEFAULT 0,
  vermarkt integer NOT NULL DEFAULT 0,
  CONSTRAINT q_alknflst_pkey PRIMARY KEY (objnr)
)
WITHOUT OIDS;

--##################################
--# Qualität der Flurstücksgrenzen
CREATE TABLE q_alkngrenze
(
  anfang varchar(7),
  ende varchar(7),
  verhandelt int2 NOT NULL DEFAULT 0,
  lz int2,
  lg int2
) 
WITH OIDS;

--##################################################
--# Tabelle für Notizen
CREATE TABLE q_notizen
(
  notiz text,
  kategorie_id varchar(100),
  person varchar(100),
  datum date
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'q_notizen','the_geom',2398,'POLYGON', 2);
CREATE INDEX q_notizen_the_geom_gist ON q_notizen USING GIST (the_geom GIST_GEOMETRY_OPS);
ALTER TABLE q_notizen DROP CONSTRAINT enforce_geotype_the_geom;
ALTER TABLE q_notizen ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);
-- ALTER TABLE q_notizen DROP CONSTRAINT enforce_geotype_position;


--# 2006-02-03

CREATE TABLE q_notiz_kategorien
(
  id serial NOT NULL,
  kategorie text,
  CONSTRAINT q_notiz_kategorien_pkey PRIMARY KEY (id)
) 
WITH OIDS;

INSERT INTO q_notiz_kategorien (id, kategorie) VALUES (1, 'Testkategorie');

CREATE TABLE q_notiz_kategorie2stelle
(
  stelle int8 NOT NULL,
  kat_id int8 NOT NULL,
  lesen bool NOT NULL DEFAULT false,
  anlegen bool NOT NULL DEFAULT false,
  aendern bool DEFAULT false
) 
WITHOUT OIDS;


--#####################
--# Metadatentabellen #
--#####################
--#2005-11-29_pk
CREATE TABLE md_metadata
(
  id serial NOT NULL,
  mdfileid varchar(255) NOT NULL,
  mdlang varchar(25) NOT NULL DEFAULT 'de'::character varying,
  mddatest date NOT NULL DEFAULT ('now'::text)::date,
  mdcontact int4,
  spatrepinfo int4,
  refsysinfo int4,
  mdextinfo int4,
  dataidinfo int4,
  continfo int4,
  distinfo int4,
  idtype text,
  restitle varchar(256),
  idabs text,
  tpcat varchar(255),
  reseddate date,
  validfrom date,
  validtill date,
  westbl varchar(25),
  eastbl varchar(25),
  southbl varchar(25),
  northbl varchar(25),
  identcode text,
  rporgname text,
  postcode int4,
  city text,
  delpoint text,
  adminarea text,
  country text,
  linkage text,
  servicetype text,
  spatialtype text,
  serviceversion varchar(255),
  vector_scale int4,
  databinding bool,
  solution varchar(255),
  status text,
  onlinelinke text,
  cyclus text,
  sparefsystem text,
  sformat text,
  sformatversion text,
  download text,
  onlinelink text,
  accessrights text,
  datalang varchar(25),
  CONSTRAINT md_metadata_pkey PRIMARY KEY (id)
) 
WITH OIDS;
COMMENT ON TABLE md_metadata IS 'Metadatendokumente';

SELECT AddGeometryColumn('public', 'md_metadata','the_geom',2398,'POLYGON', 2);
CREATE INDEX md_metadata_the_geom_gist ON md_metadata USING GIST (the_geom GIST_GEOMETRY_OPS);

--# Diese Tabellen sind für ein normalisiertes Datenbankmodell für Metadaten geplant
--# und werden noch nicht verwendet 
CREATE TABLE md_identification
(
  id serial NOT NULL,
  idcitation int4 NOT NULL,
  idabs text,
  idpurp text,
  descKeysTheme varchar(255)[],
  descKeysPlace varchar(255)[],  
  idtype varchar(25)
) 
WITH OIDS;
COMMENT ON TABLE md_identification IS 'Identifikations Informationen';

CREATE TABLE md_dataidentification
(
  id serial NOT NULL,
  datalang varchar(25),
  tpcat text NOT NULL
) 
WITH OIDS;
COMMENT ON TABLE md_dataidentification IS 'Datenidentifizierungs Informationen';

CREATE TABLE md_ci_citation
(
  id serial NOT NULL,
  restitle varchar(255),
  resrefdate int4,
  reseddate int4,
  citrespparty int4
) 
WITH OIDS;
COMMENT ON TABLE md_ci_citation IS 'Quellenangaben und Verantwortliche Einrichtung oder Person';

CREATE TABLE md_ci_responsibleparty
(
  id serial NOT NULL,
  rporgname varchar(255),
  rpcntinfo int4
) 
WITH OIDS;
COMMENT ON TABLE md_ci_responsibleparty IS 'Verantwortliche Einrichtung oder Person';

--# Hinzufügen der Tabelle md_keywords
--#2005-11-29_pk
CREATE TABLE md_keywords
(
  id serial NOT NULL,
  keyword varchar(255) NOT NULL,
  keytyp varchar(25),
  thesaname int4,
  CONSTRAINT md_keywords_pkey PRIMARY KEY (id)
) 
WITHOUT OIDS;
COMMENT ON TABLE md_keywords IS 'Beschreibende Schlagwörter';

--# Hinzufügen der Tabelle mn_keywords2metadata für die Verknüpfung zwischen Metadaten und Schlagwörtern
--#2005-11-29_pk
CREATE TABLE md_keywords2metadata
(
  keyword_id int4 NOT NULL,
  metadata_id int4 NOT NULL,
  CONSTRAINT md_keywords2metadata_pkey PRIMARY KEY (keyword_id, metadata_id),
  CONSTRAINT "fkKWD" FOREIGN KEY (keyword_id) REFERENCES md_keywords (id) ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT "fkMD" FOREIGN KEY (metadata_id) REFERENCES md_metadata (id) ON UPDATE NO ACTION ON DELETE CASCADE
) 
WITHOUT OIDS;

--####################################
--# Tabellen für die Bodenrichtwerte #
--####################################

CREATE TABLE bw_bodenrichtwertzonen (
  gemeinde_id int8 NOT NULL DEFAULT 0,
  zonennr int8 NOT NULL DEFAULT 0,
  standort varchar(255),
  richtwertdefinition varchar(50),
  bodenwert float4,
  erschliessungsart varchar(50),
  sanierungsgebiete varchar(50),
  sichtbarkeit bool NOT NULL DEFAULT true,
  datum date
)
WITH OIDS;

SELECT AddGeometryColumn('public', 'bw_bodenrichtwertzonen','the_geom',2398,'POLYGON', 2);
CREATE INDEX bw_bodenrichtwertzonen_the_geom_gist ON bw_bodenrichtwertzonen USING GIST (the_geom GIST_GEOMETRY_OPS);
SELECT AddGeometryColumn('public', 'bw_bodenrichtwertzonen','textposition',2398,'POINT', 2);
CREATE INDEX bw_bodenrichtwertzonen_textposition_gist ON bw_bodenrichtwertzonen USING GIST (textposition GIST_GEOMETRY_OPS);

--####################################
--# Tabellen für Ver- und Entsorgung #
--####################################

-- Versiegelungsflaechen
CREATE TABLE ve_versiegelung
(
  id serial NOT NULL PRIMARY KEY,	-- eindeutige ID
  grad varchar(15),					-- Versiegelungsgrad
  area numeric(8,1),				-- Flaeche
  datum date,						-- Datum
  art varchar(25),					-- Versiegelungsart
  text varchar(50)					-- Text
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 've_versiegelung','the_geom',2398,'POLYGON', 2);
CREATE INDEX ve_versiegelung_the_geom_gist ON ve_versiegelung USING GIST (the_geom GIST_GEOMETRY_OPS);
ALTER TABLE ve_versiegelung DROP CONSTRAINT enforce_geotype_the_geom;
ALTER TABLE ve_versiegelung ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);

--#######################################
--# Tabellen für die Nachweisverwaltung #
--#######################################

-- Antraege
CREATE TABLE n_antraege (
    antr_nr varchar(11) NOT NULL PRIMARY KEY,
    vermart integer,
    vermstelle integer,
    datum date
);

-- Nachweise
CREATE TABLE n_nachweise (
    id serial NOT NULL PRIMARY KEY,
    flurid integer NOT NULL,
    blattnummer character varying NOT NULL,
    datum character varying,
    vermstelle character varying,
    gueltigkeit integer,
    link_datei character varying,
    art character(3),
    format character(2),
    stammnr character varying(8)
);
SELECT AddGeometryColumn('public', 'n_nachweise','the_geom',2398,'POLYGON', 2);
CREATE INDEX n_nachweise_the_geom_gist ON n_nachweise USING GIST (the_geom GIST_GEOMETRY_OPS);
ALTER TABLE n_nachweise DROP CONSTRAINT enforce_geotype_the_geom;
ALTER TABLE n_nachweise ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);

-- Zuordnung der Nachweise zu den Antraegen
CREATE TABLE n_nachweise2antraege (
    nachweis_id integer,
    antrag_id character varying(8)
);
ALTER TABLE n_nachweise2antraege
  ADD CONSTRAINT n_nachweise2antraege_pkey PRIMARY KEY(nachweis_id, antrag_id);

-- Vermarkungsart
CREATE TABLE n_vermart (
    id serial NOT NULL PRIMARY KEY,
    art character varying(50)
);

-- Vermessungsstelle
CREATE TABLE n_vermstelle (
    id serial NOT NULL PRIMARY KEY,
    name character varying(255)
);

--##########################################################
--# Tabellen für die Punktdatei des Liegenschaftskatasters #
--##########################################################

-- Festpunkte
CREATE TABLE fp_punkte
(
  pkz char(16) NOT NULL PRIMARY KEY,
  rw varchar(11),
  hw varchar(11),
  hoe varchar(9),
  s varchar(4),
  zst varchar(7),
  vma varchar(3),
  bem varchar(4),
  ent varchar(15),
  unt varchar(15),
  zuo varchar(15),
  tex varchar(18),
  ls varchar(3),
  lg varchar(1),
  lz varchar(1),
  lbj varchar(3),
  lah varchar(9),
  hs varchar(15),
  hg varchar(15),
  hz varchar(15),
  hbj varchar(15),
  hah varchar(15),
  pktnr varchar(5),
  art int4 DEFAULT 0,
  datei varchar(50),
  verhandelt int4 DEFAULT 0,
  vermarkt int4 DEFAULT 0
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'fp_punkte','the_geom',2398,'POINT', 3);
CREATE INDEX fp_punkte_the_geom_gist ON fp_punkte USING GIST (the_geom GIST_GEOMETRY_OPS);

CREATE TABLE fp_punkte2
(
  pkz char(16) NOT NULL PRIMARY KEY,
  rw varchar(11),
  hw varchar(11),
  hoe varchar(9),
  s varchar(4),
  zst varchar(7),
  vma varchar(3),
  bem varchar(4),
  ent varchar(15),
  unt varchar(15),
  zuo varchar(15),
  tex varchar(25),
  ls varchar(3),
  lg varchar(1),
  lz varchar(1),
  lbj varchar(3),
  lah varchar(9),
  hs varchar(15),
  hg varchar(15),
  hz varchar(15),
  hbj varchar(15),
  hah varchar(15),
  pktnr varchar(5),
  art int4 DEFAULT 0,
  datei varchar(50),
  verhandelt int4 DEFAULT 0,
  vermarkt int4 DEFAULT 0
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'fp_punkte2','the_geom',2399,'POINT', 3);
CREATE INDEX fp_punkte2_the_geom_gist ON fp_punkte2 USING GIST (the_geom GIST_GEOMETRY_OPS);

--####################################
--# Temporäre Tabelle für Punktdatei #
--####################################
CREATE TABLE fp_punkte_temp
(
  pkz char(16) NOT NULL PRIMARY KEY,
  rw varchar(11),
  hw varchar(11),
  hoe varchar(9),
  s varchar(4),
  zst varchar(7),
  vma varchar(3),
  bem varchar(4),
  ent varchar(15),
  unt varchar(15),
  zuo varchar(15),
  tex varchar(18),
  ls varchar(3),
  lg varchar(1),
  lz varchar(1),
  lbj varchar(3),
  lah varchar(9),
  hs varchar(15),
  hg varchar(15),
  hz varchar(15),
  hbj varchar(15),
  hah varchar(15),
  pktnr varchar(5),
  art int4 DEFAULT 0,
  datei varchar(50),
  verhandelt int4 DEFAULT 0,
  vermarkt int4 DEFAULT 0
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'fp_punkte_temp','the_geom',2398,'POINT', 3);
CREATE INDEX fp_punkte_temp_the_geom_gist ON fp_punkte_temp USING GIST (the_geom GIST_GEOMETRY_OPS);
ALTER TABLE fp_punkte_temp DROP CONSTRAINT enforce_srid_the_geom;

--#####################################################
--# Tabelle für die Zuordnung der Punkte zu Aufträgen #
--#####################################################

-- Table: fp_punkte2antraege

CREATE TABLE fp_punkte2antraege
(
  pkz char(16) NOT NULL,
  antrag_nr varchar(8) NOT NULL,
  zeitstempel timestamp,
  CONSTRAINT fp_punkte2antraege_pkey PRIMARY KEY (pkz, antrag_nr)
) 
WITHOUT OIDS;

--###########################
--# Tabellen der Geothermie #
--###########################

-- Abfragen zur Geothermie
CREATE TABLE gt_abfragen (
    id serial NOT NULL PRIMARY KEY,
    user_id integer,
    flstkennz character varying(23),
    entzugsleistung_soll numeric(5,0),
    datum date
);

-- Bohrpunkte
CREATE TABLE gt_bohrpunkte (
    gid serial NOT NULL,
    id integer,
    locid double precision,
    xcoord double precision,
    ycoord double precision,
    zcoordb double precision,
    zcoorde double precision,
    longname character varying,
    gwruhe double precision,
    gw_nn double precision,
    identnr double precision,
    ort character varying
);
SELECT AddGeometryColumn('public', 'gt_bohrpunkte','bohrpunkt',2398,'POINT', 2);
CREATE INDEX gt_bohrpunkte_bohrpunkt_gist ON gt_bohrpunkte USING GIST (bohrpunkt GIST_GEOMETRY_OPS);

-- Erdwaermesonden
CREATE TABLE gt_erdwaermesonden (
    id serial NOT NULL PRIMARY KEY,
    bohrtiefe numeric(5,2),
    effizienz_wm numeric(6,2),
    ellipse_halbachse_a numeric(5,2),
    ellipse_halbachse_b numeric(5,2)
);
SELECT AddGeometryColumn('public', 'gt_erdwaermesonden','bohrpunkt',2398,'POINT', 2);
CREATE INDEX gt_erdwaermesonden_bohrpunkt_gist ON gt_erdwaermesonden USING GIST (bohrpunkt GIST_GEOMETRY_OPS);

--##################################
--# Tabellen für die Daten des ALB #
--##################################

--
-- TOC entry 12 (OID 23848)
-- Name: alb_fortfuehrung; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_fortfuehrung (
    lfdnr serial NOT NULL,
    grundausstattung date DEFAULT '0001-01-01 BC'::date NOT NULL,
    ffzeitraum_von timestamp without time zone DEFAULT '0001-01-01 00:00:00 BC'::timestamp without time zone NOT NULL,
    ffzeitraum_bis timestamp without time zone DEFAULT '0001-01-01 00:00:00 BC'::timestamp without time zone NOT NULL,
    ff_timestamp timestamp without time zone NOT NULL
);


--
-- TOC entry 13 (OID 23865)
-- Name: alb_grundbuecher; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_grundbuecher (
    bezirk integer DEFAULT 0 NOT NULL,
    blatt character varying(6) DEFAULT ''::character varying NOT NULL,
    pruefzeichen character(1),
    aktualitaetsnr character varying(4),
    zusatz_eigentuemer text,
    bestandsflaeche integer
);


--
-- TOC entry 14 (OID 23874)
-- Name: alb_f_adressen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_adressen (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    gemeinde integer DEFAULT 0 NOT NULL,
    strasse character varying(5) DEFAULT ''::character varying NOT NULL,
    hausnr character varying(8) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 15 (OID 23880)
-- Name: alb_f_anlieger; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_anlieger (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    kennung character(1),
    anlflstkennz character varying(23),
    anlflstpruefz character(1)
);


--
-- TOC entry 16 (OID 23883)
-- Name: alb_f_baulasten; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_baulasten (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    blattnr character varying(10) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 17 (OID 23889)
-- Name: alb_f_hinweise; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_hinweise (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    hinwzflst character(2) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 18 (OID 23895)
-- Name: alb_f_historie; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_historie (
    vorgaenger character varying(23) DEFAULT ''::character varying NOT NULL,
    nachfolger character varying(23) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 19 (OID 23901)
-- Name: alb_f_klassifizierungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_klassifizierungen (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    tabkenn character(2) DEFAULT ''::bpchar NOT NULL,
    klass character(3) DEFAULT ''::bpchar NOT NULL,
    flaeche integer DEFAULT 0 NOT NULL,
    angaben character varying(23)
);


--
-- TOC entry 20 (OID 23907)
-- Name: alb_f_lage; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_lage (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    lfdnr character(2) DEFAULT ''::bpchar NOT NULL,
    lagebezeichnung character varying(30)
);


--
-- TOC entry 21 (OID 23913)
-- Name: alb_f_nutzungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_nutzungen (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    nutzungsart character(3) DEFAULT ''::bpchar NOT NULL,
    flaeche integer
);


--
-- TOC entry 22 (OID 23917)
-- Name: alb_f_texte; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_texte (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    lfdnr character(2) DEFAULT ''::bpchar NOT NULL,
    text character varying(52)
);


--
-- TOC entry 23 (OID 23923)
-- Name: alb_f_verfahren; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_f_verfahren (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    ausfstelle character varying(5),
    verfnr character varying(6),
    verfbem character(2)
);


--
-- TOC entry 24 (OID 23926)
-- Name: alb_g_buchungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_g_buchungen
(
  flurstkennz varchar(23) NOT NULL DEFAULT ''::character varying,
  bezirk int4 NOT NULL DEFAULT 0,
  blatt varchar(6) NOT NULL DEFAULT ''::character varying,
  bvnr varchar(4) NOT NULL DEFAULT ''::character varying,
  erbbaurechtshinw char(1) NOT NULL DEFAULT ''::bpchar,
  CONSTRAINT alb_g_buchungen_pkey PRIMARY KEY (flurstkennz, bezirk, blatt, bvnr)
) 
WITH OIDS;

--
-- TOC entry 25 (OID 23934)
-- Name: alb_g_eigentuemer; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_g_eigentuemer
(
  bezirk int4 NOT NULL DEFAULT 0,
  blatt varchar(6) NOT NULL DEFAULT ''::character varying,
  namensnr varchar(16) NOT NULL DEFAULT ''::character varying,
  eigentuemerart char(2) NOT NULL DEFAULT ''::bpchar,
  anteilsverhaeltnis varchar(25) NOT NULL DEFAULT ''::character varying,
  lfd_nr_name int4 NOT NULL DEFAULT 0,
  CONSTRAINT alb_g_eigentuemer_pkey PRIMARY KEY (bezirk, blatt, namensnr)
)
WITH OIDS;

--
-- TOC entry 26 (OID 23944)
-- Name: alb_g_grundstuecke; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_g_grundstuecke (
    bezirk integer DEFAULT 0 NOT NULL,
    blatt character varying(6) DEFAULT ''::character varying NOT NULL,
    bvnr character varying(4) DEFAULT ''::character varying NOT NULL,
    buchungsart character(1) DEFAULT ''::bpchar NOT NULL,
    anteil character varying(24),
    auftplannr character varying(12),
    sondereigentum text
);


--
-- TOC entry 27 (OID 23957)
-- Name: alb_g_namen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_g_namen
(
  lfd_nr_name serial NOT NULL,
  name1 varchar(52) NOT NULL DEFAULT ''::character varying,
  name2 varchar(52) NOT NULL DEFAULT ''::character varying,
  name3 varchar(52) NOT NULL DEFAULT ''::character varying,
  name4 varchar(52) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT alb_g_namen_pkey PRIMARY KEY (lfd_nr_name)
) 
WITH OIDS;

--
-- TOC entry 28 (OID 23966)
-- Name: alb_v_amtsgerichte; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_amtsgerichte (
    amtsgericht character varying(4) DEFAULT ''::character varying NOT NULL,
    name character varying(80)
);


--
-- TOC entry 29 (OID 23971)
-- Name: alb_v_ausfuehrendestellen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_ausfuehrendestellen (
    ausfstelle character varying(5) DEFAULT ''::character varying NOT NULL,
    name text
);


--
-- TOC entry 30 (OID 23979)
-- Name: alb_v_bemerkgzumverfahren; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_bemerkgzumverfahren (
    verfbem character(2) DEFAULT ''::bpchar NOT NULL,
    bezeichnung text
);


--
-- TOC entry 31 (OID 23987)
-- Name: alb_v_buchungsarten; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_buchungsarten (
    buchungsart character(1) DEFAULT '0'::bpchar NOT NULL,
    bezeichnung character varying(60)
);


--
-- TOC entry 32 (OID 23992)
-- Name: alb_v_eigentuemerarten; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_eigentuemerarten (
    eigentuemerart character(2) DEFAULT ''::bpchar NOT NULL,
    bezeichnung character varying(80)
);


--
-- TOC entry 33 (OID 23997)
-- Name: alb_v_finanzaemter; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_finanzaemter (
    finanzamt smallint DEFAULT 0::smallint NOT NULL,
    name text
);


--
-- TOC entry 34 (OID 24005)
-- Name: alb_v_forstaemter; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_forstaemter (
    forstamt smallint DEFAULT 0::smallint NOT NULL,
    name character varying(78)
);


--
-- TOC entry 35 (OID 24010)
-- Name: alb_v_gemarkungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_gemarkungen (
    gemkgschl integer DEFAULT 0 NOT NULL,
    gemeinde integer DEFAULT 0,
    amtsgericht character varying(4),
    gemkgname character varying(255)
);


--
-- TOC entry 36 (OID 24016)
-- Name: alb_v_gemeinden; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_gemeinden (
    gemeinde integer DEFAULT 0 NOT NULL,
    gemeindename character varying(26) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 37 (OID 24022)
-- Name: alb_v_grundbuchbezirke; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_grundbuchbezirke (
    grundbuchbezschl integer DEFAULT 0 NOT NULL,
    amtsgericht character varying(4) DEFAULT '0'::character varying NOT NULL,
    bezeichnung character varying(50) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 38 (OID 24029)
-- Name: alb_v_hinweise; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_hinweise (
    hinwzflst character(2) DEFAULT ''::bpchar NOT NULL,
    bezeichnung character varying(80) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 39 (OID 24035)
-- Name: alb_v_katasteraemter; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_katasteraemter (
    katasteramt character varying(4) DEFAULT ''::character varying NOT NULL,
    artamt character varying(26),
    name text
);


--
-- TOC entry 40 (OID 24043)
-- Name: alb_v_klassifizierungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_klassifizierungen (
    tabkenn character(2) DEFAULT ''::bpchar NOT NULL,
    klass character(3) DEFAULT ''::bpchar NOT NULL,
    bezeichnung character varying(90) DEFAULT ''::character varying NOT NULL,
    abkuerzung character varying(12) DEFAULT ''::character varying NOT NULL,
    bez1 character varying(30),
    kurz1 character varying(4),
    bez2 character varying(30),
    kurz2 character varying(4) DEFAULT ''::character varying NOT NULL,
    bez3 character varying(30),
    kurz3 character varying(4)
);


--
-- TOC entry 41 (OID 24052)
-- Name: alb_v_kreise; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_kreise (
    kreis integer DEFAULT 0 NOT NULL,
    kreisname character varying(26)
);


--
-- TOC entry 42 (OID 24057)
-- Name: alb_v_nutzungsarten; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_nutzungsarten (
    nutzungsart character(3) DEFAULT ''::bpchar NOT NULL,
    bezeichnung character varying(90),
    abkuerzung character varying(12)
);


--
-- TOC entry 43 (OID 24062)
-- Name: alb_v_strassen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_v_strassen (
    gemeinde integer DEFAULT 0 NOT NULL,
    strasse character varying(5) DEFAULT ''::character varying NOT NULL,
    strassenname character varying(30) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 44 (OID 24078)
-- Name: alb_x_grundbuecher; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_grundbuecher (
    bezirk integer DEFAULT 0 NOT NULL,
    blatt character varying(6) DEFAULT ''::character varying NOT NULL,
    pruefzeichen character(1),
    aktualitaetsnr character varying(4),
    zusatz_eigentuemer text,
    bestandsflaeche integer
);


--
-- TOC entry 45 (OID 24087)
-- Name: alb_x_f_adressen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_adressen (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    gemeinde integer DEFAULT 0 NOT NULL,
    strasse character varying(5) DEFAULT ''::character varying NOT NULL,
    hausnr character varying(8) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 46 (OID 24093)
-- Name: alb_x_f_anlieger; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_anlieger (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    kennung character(1),
    anlflstkennz character varying(23),
    anlflstpruefz character(1)
);


--
-- TOC entry 47 (OID 24096)
-- Name: alb_x_f_baulasten; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_baulasten (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    blattnr character varying(10) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 48 (OID 24102)
-- Name: alb_x_f_hinweise; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_hinweise (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    hinwzflst character(2) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 49 (OID 24108)
-- Name: alb_x_f_historie; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_historie (
    vorgaenger character varying(23) DEFAULT ''::character varying NOT NULL,
    nachfolger character varying(23) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 50 (OID 24114)
-- Name: alb_x_f_klassifizierungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_klassifizierungen (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    tabkenn character(2) DEFAULT ''::bpchar NOT NULL,
    klass character(3) DEFAULT ''::bpchar NOT NULL,
    flaeche integer DEFAULT 0 NOT NULL,
    angaben character varying(23)
);


--
-- TOC entry 51 (OID 24120)
-- Name: alb_x_f_lage; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_lage (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    lfdnr character(2) DEFAULT ''::bpchar NOT NULL,
    lagebezeichnung character varying(30)
);


--
-- TOC entry 52 (OID 24126)
-- Name: alb_x_f_nutzungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_nutzungen (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    nutzungsart character(3) DEFAULT ''::bpchar NOT NULL,
    flaeche integer
);


--
-- TOC entry 53 (OID 24130)
-- Name: alb_x_f_texte; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_texte (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    lfdnr character(2) DEFAULT ''::bpchar NOT NULL,
    text character varying(52)
);


--
-- TOC entry 54 (OID 24136)
-- Name: alb_x_f_verfahren; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_f_verfahren (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    ausfstelle character varying(5),
    verfnr character varying(6),
    verfbem character(2)
);


--
-- TOC entry 55 (OID 24139)
-- Name: alb_x_g_buchungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_g_buchungen
(
  flurstkennz varchar(23) NOT NULL DEFAULT ''::character varying,
  bezirk int4 NOT NULL DEFAULT 0,
  blatt varchar(6) NOT NULL DEFAULT ''::character varying,
  bvnr varchar(4) NOT NULL DEFAULT ''::character varying,
  erbbaurechtshinw char(1) NOT NULL DEFAULT ''::bpchar,
  CONSTRAINT alb_x_g_buchungen_pkey PRIMARY KEY (flurstkennz, bezirk, blatt, bvnr)
) 
WITH OIDS;

--
-- TOC entry 56 (OID 24147)
-- Name: alb_x_g_eigentuemer; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_g_eigentuemer
(
  bezirk int4 NOT NULL DEFAULT 0,
  blatt varchar(6) NOT NULL DEFAULT ''::character varying,
  namensnr varchar(16) NOT NULL DEFAULT ''::character varying,
  eigentuemerart char(2) NOT NULL DEFAULT ''::bpchar,
  anteilsverhaeltnis varchar(25) NOT NULL DEFAULT ''::character varying,
  lfd_nr_name int4 NOT NULL DEFAULT 0,
  CONSTRAINT alb_x_g_eigentuemer_pkey PRIMARY KEY (bezirk, blatt, namensnr)
)
WITH OIDS;

--
-- TOC entry 57 (OID 24157)
-- Name: alb_x_g_grundstuecke; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_g_grundstuecke (
    bezirk integer DEFAULT 0 NOT NULL,
    blatt character varying(6) DEFAULT ''::character varying NOT NULL,
    bvnr character varying(4) DEFAULT ''::character varying NOT NULL,
    buchungsart character(1) DEFAULT ''::bpchar NOT NULL,
    anteil character varying(24),
    auftplannr character varying(12),
    sondereigentum text
);


--
-- TOC entry 58 (OID 24170)
-- Name: alb_x_g_namen; Type: TABLE; Schema: public; Owner: kvwmap
-- # letzte Änderung 2005-12-07 Korduan

CREATE TABLE alb_x_g_namen
(
  lfd_nr_name serial NOT NULL,
  name1 varchar(52) NOT NULL DEFAULT ''::character varying,
  name2 varchar(52) NOT NULL DEFAULT ''::character varying,
  name3 varchar(52) NOT NULL DEFAULT ''::character varying,
  name4 varchar(52) NOT NULL DEFAULT ''::character varying,
  lfd_nr_name_alt int4 NOT NULL DEFAULT 0,
  CONSTRAINT alb_x_g_namen_pkey PRIMARY KEY (lfd_nr_name)
) 
WITH OIDS;

--
-- TOC entry 59 (OID 24179)
-- Name: alb_x_v_amtsgerichte; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_amtsgerichte (
    amtsgericht character varying(4) DEFAULT ''::character varying NOT NULL,
    name character varying(80)
);

--
-- TOC entry 60 (OID 24184)
-- Name: alb_x_v_ausfuehrendestellen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_ausfuehrendestellen (
    ausfstelle character varying(5) DEFAULT ''::character varying NOT NULL,
    name text
);


--
-- TOC entry 61 (OID 24192)
-- Name: alb_x_v_bemerkgzumverfahren; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_bemerkgzumverfahren (
    verfbem character(2) DEFAULT ''::bpchar NOT NULL,
    bezeichnung text
);


--
-- TOC entry 62 (OID 24200)
-- Name: alb_x_v_buchungsarten; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_buchungsarten (
    buchungsart character(1) DEFAULT '0'::bpchar NOT NULL,
    bezeichnung character varying(60)
);


--
-- TOC entry 63 (OID 24205)
-- Name: alb_x_v_eigentuemerarten; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_eigentuemerarten (
    eigentuemerart character(2) DEFAULT ''::bpchar NOT NULL,
    bezeichnung character varying(80)
);


--
-- TOC entry 64 (OID 24210)
-- Name: alb_x_v_finanzaemter; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_finanzaemter (
    finanzamt smallint DEFAULT 0::smallint NOT NULL,
    name text
);


--
-- TOC entry 65 (OID 24218)
-- Name: alb_x_v_forstaemter; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_forstaemter (
    forstamt smallint DEFAULT 0::smallint NOT NULL,
    name character varying(78)
);


--
-- TOC entry 66 (OID 24223)
-- Name: alb_x_v_gemarkungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_gemarkungen (
    gemkgschl integer DEFAULT 0 NOT NULL,
    gemeinde integer DEFAULT 0,
    amtsgericht character varying(4),
    gemkgname character varying(255)
);


--
-- TOC entry 67 (OID 24229)
-- Name: alb_x_v_gemeinden; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_gemeinden (
    gemeinde integer DEFAULT 0 NOT NULL,
    gemeindename character varying(26) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 68 (OID 24235)
-- Name: alb_x_v_grundbuchbezirke; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_grundbuchbezirke (
    grundbuchbezschl integer DEFAULT 0 NOT NULL,
    amtsgericht character varying(4) DEFAULT '0'::character varying NOT NULL,
    bezeichnung character varying(50) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 69 (OID 24242)
-- Name: alb_x_v_hinweise; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_hinweise (
    hinwzflst character(2) DEFAULT ''::bpchar NOT NULL,
    bezeichnung character varying(80) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 70 (OID 24248)
-- Name: alb_x_v_katasteraemter; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_katasteraemter (
    katasteramt character varying(4) DEFAULT ''::character varying NOT NULL,
    artamt character varying(26),
    name text
);


--
-- TOC entry 71 (OID 24256)
-- Name: alb_x_v_klassifizierungen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_klassifizierungen (
    tabkenn character(2) DEFAULT ''::bpchar NOT NULL,
    klass character(3) DEFAULT ''::bpchar NOT NULL,
    bezeichnung character varying(90) DEFAULT ''::character varying NOT NULL,
    abkuerzung character varying(12) DEFAULT ''::character varying NOT NULL,
    bez1 character varying(30),
    kurz1 character varying(4),
    bez2 character varying(30),
    kurz2 character varying(4) DEFAULT ''::character varying NOT NULL,
    bez3 character varying(30),
    kurz3 character varying(4)
);


--
-- TOC entry 72 (OID 24265)
-- Name: alb_x_v_kreise; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_kreise (
    kreis integer DEFAULT 0 NOT NULL,
    kreisname character varying(26)
);


--
-- TOC entry 73 (OID 24270)
-- Name: alb_x_v_nutzungsarten; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_nutzungsarten (
    nutzungsart character(3) DEFAULT ''::bpchar NOT NULL,
    bezeichnung character varying(90),
    abkuerzung character varying(12)
);


--
-- TOC entry 74 (OID 24275)
-- Name: alb_x_v_strassen; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_v_strassen (
    gemeinde integer DEFAULT 0 NOT NULL,
    strasse character varying(5) DEFAULT ''::character varying NOT NULL,
    strassenname character varying(30) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 75 (OID 1004498)
-- Name: alb_x_flurstuecke; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_x_flurstuecke (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    gemkgschl integer DEFAULT 0 NOT NULL,
    flurnr text,
    pruefzeichen text,
    status text,
    entsteh text,
    letzff text,
    flaeche integer,
    aktunr smallint,
    karte text,
    baublock text,
    koorrw numeric(12,3) DEFAULT 0 NOT NULL,
    koorhw numeric(12,3) DEFAULT 0 NOT NULL,
    forstamt smallint,
    finanzamt smallint,
    erbbau text,
    CONSTRAINT alb_x_flurstuecke_pkey PRIMARY KEY (flurstkennz)    
);

SELECT AddGeometryColumn( 'alb_x_flurstuecke', 'the_geom', 2398, 'POINT', 2);

--
-- TOC entry 76 (OID 1004507)
-- Name: alb_flurstuecke; Type: TABLE; Schema: public; Owner: kvwmap
--

CREATE TABLE alb_flurstuecke (
    flurstkennz character varying(23) DEFAULT ''::character varying NOT NULL,
    gemkgschl integer DEFAULT 0 NOT NULL,
    flurnr text,
    pruefzeichen text,
    status text,
    entsteh text,
    letzff text,
    flaeche integer,
    aktunr smallint,
    karte text,
    baublock text,
    koorrw numeric(12,3) DEFAULT 0 NOT NULL,
    koorhw numeric(12,3) DEFAULT 0 NOT NULL,
    forstamt smallint,
    finanzamt smallint,
    erbbau text,
    CONSTRAINT alb_flurstuecke_pkey PRIMARY KEY (flurstkennz)    
);

SELECT AddGeometryColumn( 'alb_flurstuecke', 'the_geom', 2398, 'POINT', 2);

--
-- TOC entry 83 (OID 1133722)
-- Name: alb_z_fluren; Type: TABLE; Schema: public; Owner: kvwmap
--

-- Table: alb_z_fluren

-- DROP TABLE alb_z_fluren;

CREATE TABLE alb_z_fluren
(
  gemkgschl varchar(6) NOT NULL,
  flurnr varchar(6) NOT NULL,
  CONSTRAINT alb_z_fluren_pkey PRIMARY KEY (gemkgschl, flurnr)
) 
WITH OIDS;

-- Table: alb_tmp_adressen

-- DROP TABLE alb_tmp_adressen;

CREATE TABLE alb_tmp_adressen
(
  quelle char(3) NOT NULL DEFAULT ''::bpchar,
  gemeinde int4 NOT NULL DEFAULT 0,
  gemeindename varchar(255),
  strasse varchar(5) NOT NULL DEFAULT ''::character varying,
  strassenname varchar(255),
  hausnr varchar(8) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT alb_tmp_adressen_pkey PRIMARY KEY (gemeinde, strasse, hausnr)
) 
WITH OIDS;

--
-- TOC entry 162 (OID 1264765)
-- Name: gist_x_flurstuecke; Type: INDEX; Schema: public; Owner: kvwmap
--

CREATE INDEX gist_x_flurstuecke ON alb_x_flurstuecke USING gist (the_geom);


--
-- TOC entry 163 (OID 1264766)
-- Name: gist_flurstuecke; Type: INDEX; Schema: public; Owner: kvwmap
--

CREATE INDEX gist_flurstuecke ON alb_flurstuecke USING gist (the_geom);


--
-- TOC entry 109 (OID 23854)
-- Name: alb_fortfuehrung_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_fortfuehrung
    ADD CONSTRAINT alb_fortfuehrung_pkey PRIMARY KEY (lfdnr);


--
-- TOC entry 110 (OID 23872)
-- Name: alb_grundbuecher_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_grundbuecher
    ADD CONSTRAINT alb_grundbuecher_pkey PRIMARY KEY (bezirk, blatt);

--
-- TOC entry 111 (OID 23887)
-- Name: alb_f_baulasten_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_f_baulasten
    ADD CONSTRAINT alb_f_baulasten_pkey PRIMARY KEY (flurstkennz, blattnr);


--
-- TOC entry 112 (OID 23893)
-- Name: alb_f_hinweise_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_f_hinweise
    ADD CONSTRAINT alb_f_hinweise_pkey PRIMARY KEY (flurstkennz, hinwzflst);


--
-- TOC entry 113 (OID 23899)
-- Name: alb_f_historie_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_f_historie
    ADD CONSTRAINT alb_f_historie_pkey PRIMARY KEY (vorgaenger, nachfolger);


--
-- TOC entry 114 (OID 23911)
-- Name: alb_f_lage_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_f_lage
    ADD CONSTRAINT alb_f_lage_pkey PRIMARY KEY (flurstkennz, lfdnr);


--
-- TOC entry 115 (OID 23921)
-- Name: alb_f_texte_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_f_texte
    ADD CONSTRAINT alb_f_texte_pkey PRIMARY KEY (flurstkennz, lfdnr);


--
-- TOC entry 118 (OID 23953)
-- Name: alb_g_grundstuecke_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_g_grundstuecke
    ADD CONSTRAINT alb_g_grundstuecke_pkey PRIMARY KEY (bezirk, blatt, bvnr);


--
-- TOC entry 120 (OID 23969)
-- Name: alb_v_amtsgerichte_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_amtsgerichte
    ADD CONSTRAINT alb_v_amtsgerichte_pkey PRIMARY KEY (amtsgericht);


--
-- TOC entry 121 (OID 23977)
-- Name: alb_v_ausfuehrendestellen_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_ausfuehrendestellen
    ADD CONSTRAINT alb_v_ausfuehrendestellen_pkey PRIMARY KEY (ausfstelle);


--
-- TOC entry 122 (OID 23985)
-- Name: alb_v_bemerkgzumverfahren_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_bemerkgzumverfahren
    ADD CONSTRAINT alb_v_bemerkgzumverfahren_pkey PRIMARY KEY (verfbem);


--
-- TOC entry 123 (OID 23990)
-- Name: alb_v_buchungsarten_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_buchungsarten
    ADD CONSTRAINT alb_v_buchungsarten_pkey PRIMARY KEY (buchungsart);


--
-- TOC entry 124 (OID 23995)
-- Name: alb_v_eigentuemerarten_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_eigentuemerarten
    ADD CONSTRAINT alb_v_eigentuemerarten_pkey PRIMARY KEY (eigentuemerart);


--
-- TOC entry 125 (OID 24003)
-- Name: alb_v_finanzaemter_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_finanzaemter
    ADD CONSTRAINT alb_v_finanzaemter_pkey PRIMARY KEY (finanzamt);


--
-- TOC entry 126 (OID 24008)
-- Name: alb_v_forstaemter_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_forstaemter
    ADD CONSTRAINT alb_v_forstaemter_pkey PRIMARY KEY (forstamt);


--
-- TOC entry 127 (OID 24014)
-- Name: alb_v_gemarkungen_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_gemarkungen
    ADD CONSTRAINT alb_v_gemarkungen_pkey PRIMARY KEY (gemkgschl);


--
-- TOC entry 128 (OID 24020)
-- Name: alb_v_gemeinden_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_gemeinden
    ADD CONSTRAINT alb_v_gemeinden_pkey PRIMARY KEY (gemeinde);


--
-- TOC entry 129 (OID 24027)
-- Name: alb_v_grundbuchbezirke_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_grundbuchbezirke
    ADD CONSTRAINT alb_v_grundbuchbezirke_pkey PRIMARY KEY (grundbuchbezschl);


--
-- TOC entry 130 (OID 24033)
-- Name: alb_v_hinweise_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_hinweise
    ADD CONSTRAINT alb_v_hinweise_pkey PRIMARY KEY (hinwzflst);


--
-- TOC entry 131 (OID 24041)
-- Name: alb_v_katasteraemter_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_katasteraemter
    ADD CONSTRAINT alb_v_katasteraemter_pkey PRIMARY KEY (katasteramt);


--
-- TOC entry 132 (OID 24050)
-- Name: alb_v_klassifizierungen_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_klassifizierungen
    ADD CONSTRAINT alb_v_klassifizierungen_pkey PRIMARY KEY (tabkenn, klass);


--
-- TOC entry 133 (OID 24055)
-- Name: alb_v_kreise_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_kreise
    ADD CONSTRAINT alb_v_kreise_pkey PRIMARY KEY (kreis);


--
-- TOC entry 134 (OID 24060)
-- Name: alb_v_nutzungsarten_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_nutzungsarten
    ADD CONSTRAINT alb_v_nutzungsarten_pkey PRIMARY KEY (nutzungsart);


--
-- TOC entry 135 (OID 24067)
-- Name: alb_v_strassen_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_v_strassen
    ADD CONSTRAINT alb_v_strassen_pkey PRIMARY KEY (gemeinde, strasse);


--
-- TOC entry 136 (OID 24085)
-- Name: alb_x_grundbuecher_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_grundbuecher
    ADD CONSTRAINT alb_x_grundbuecher_pkey PRIMARY KEY (bezirk, blatt);


--
-- TOC entry 137 (OID 24100)
-- Name: alb_x_f_baulasten_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_f_baulasten
    ADD CONSTRAINT alb_x_f_baulasten_pkey PRIMARY KEY (flurstkennz, blattnr);


--
-- TOC entry 138 (OID 24106)
-- Name: alb_x_f_hinweise_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_f_hinweise
    ADD CONSTRAINT alb_x_f_hinweise_pkey PRIMARY KEY (flurstkennz, hinwzflst);


--
-- TOC entry 139 (OID 24112)
-- Name: alb_x_f_historie_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_f_historie
    ADD CONSTRAINT alb_x_f_historie_pkey PRIMARY KEY (vorgaenger, nachfolger);


--
-- TOC entry 140 (OID 24124)
-- Name: alb_x_f_lage_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_f_lage
    ADD CONSTRAINT alb_x_f_lage_pkey PRIMARY KEY (flurstkennz, lfdnr);


--
-- TOC entry 141 (OID 24134)
-- Name: alb_x_f_texte_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_f_texte
    ADD CONSTRAINT alb_x_f_texte_pkey PRIMARY KEY (flurstkennz, lfdnr);

--
-- TOC entry 144 (OID 24166)
-- Name: alb_x_g_grundstuecke_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_g_grundstuecke
    ADD CONSTRAINT alb_x_g_grundstuecke_pkey PRIMARY KEY (bezirk, blatt, bvnr);

--
-- TOC entry 146 (OID 24182)
-- Name: alb_x_v_amtsgerichte_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_amtsgerichte
    ADD CONSTRAINT alb_x_v_amtsgerichte_pkey PRIMARY KEY (amtsgericht);


--
-- TOC entry 147 (OID 24190)
-- Name: alb_x_v_ausfuehrendestellen_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_ausfuehrendestellen
    ADD CONSTRAINT alb_x_v_ausfuehrendestellen_pkey PRIMARY KEY (ausfstelle);


--
-- TOC entry 148 (OID 24198)
-- Name: alb_x_v_bemerkgzumverfahren_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_bemerkgzumverfahren
    ADD CONSTRAINT alb_x_v_bemerkgzumverfahren_pkey PRIMARY KEY (verfbem);


--
-- TOC entry 149 (OID 24203)
-- Name: alb_x_v_buchungsarten_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_buchungsarten
    ADD CONSTRAINT alb_x_v_buchungsarten_pkey PRIMARY KEY (buchungsart);


--
-- TOC entry 150 (OID 24208)
-- Name: alb_x_v_eigentuemerarten_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_eigentuemerarten
    ADD CONSTRAINT alb_x_v_eigentuemerarten_pkey PRIMARY KEY (eigentuemerart);


--
-- TOC entry 151 (OID 24216)
-- Name: alb_x_v_finanzaemter_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_finanzaemter
    ADD CONSTRAINT alb_x_v_finanzaemter_pkey PRIMARY KEY (finanzamt);


--
-- TOC entry 152 (OID 24221)
-- Name: alb_x_v_forstaemter_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_forstaemter
    ADD CONSTRAINT alb_x_v_forstaemter_pkey PRIMARY KEY (forstamt);


--
-- TOC entry 153 (OID 24227)
-- Name: alb_x_v_gemarkungen_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_gemarkungen
    ADD CONSTRAINT alb_x_v_gemarkungen_pkey PRIMARY KEY (gemkgschl);


--
-- TOC entry 154 (OID 24233)
-- Name: alb_x_v_gemeinden_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_gemeinden
    ADD CONSTRAINT alb_x_v_gemeinden_pkey PRIMARY KEY (gemeinde);


--
-- TOC entry 155 (OID 24240)
-- Name: alb_x_v_grundbuchbezirke_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_grundbuchbezirke
    ADD CONSTRAINT alb_x_v_grundbuchbezirke_pkey PRIMARY KEY (grundbuchbezschl);


--
-- TOC entry 156 (OID 24246)
-- Name: alb_x_v_hinweise_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_hinweise
    ADD CONSTRAINT alb_x_v_hinweise_pkey PRIMARY KEY (hinwzflst);


--
-- TOC entry 157 (OID 24254)
-- Name: alb_x_v_katasteraemter_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_katasteraemter
    ADD CONSTRAINT alb_x_v_katasteraemter_pkey PRIMARY KEY (katasteramt);


--
-- TOC entry 158 (OID 24263)
-- Name: alb_x_v_klassifizierungen_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_klassifizierungen
    ADD CONSTRAINT alb_x_v_klassifizierungen_pkey PRIMARY KEY (tabkenn, klass);


--
-- TOC entry 159 (OID 24268)
-- Name: alb_x_v_kreise_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_kreise
    ADD CONSTRAINT alb_x_v_kreise_pkey PRIMARY KEY (kreis);


--
-- TOC entry 160 (OID 24273)
-- Name: alb_x_v_nutzungsarten_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_nutzungsarten
    ADD CONSTRAINT alb_x_v_nutzungsarten_pkey PRIMARY KEY (nutzungsart);


--
-- TOC entry 161 (OID 24280)
-- Name: alb_x_v_strassen_pkey; Type: CONSTRAINT; Schema: public; Owner: kvwmap
--

ALTER TABLE ONLY alb_x_v_strassen
    ADD CONSTRAINT alb_x_v_strassen_pkey PRIMARY KEY (gemeinde, strasse);


CREATE TABLE alkfolien (
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    bezeichnung character varying(60) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alkfolien OWNER TO kvwmap;

--
-- Name: TABLE alkfolien; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkfolien IS 'Fachliche Ebenen, Folien';


--
-- Name: COLUMN alkfolien.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkfolien.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkfolien.bezeichnung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkfolien.bezeichnung IS 'Name oder Bezeichnung der Folie';


--
-- Name: alkgebschrwink; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkgebschrwink (
    objartvon smallint NOT NULL,
    objartbis smallint NOT NULL,
    schratyp smallint
);


ALTER TABLE public.alkgebschrwink OWNER TO kvwmap;

--
-- Name: TABLE alkgebschrwink; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkgebschrwink IS 'Typen von Gebaeuden fuer Schraffurwinkel';


--
-- Name: COLUMN alkgebschrwink.objartvon; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgebschrwink.objartvon IS 'Objektart (Beginn des Schlüsselbereiches)';


--
-- Name: COLUMN alkgebschrwink.objartbis; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgebschrwink.objartbis IS 'Objektart (Ende des Schlüsselbereiches)';


--
-- Name: COLUMN alkgebschrwink.schratyp; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgebschrwink.schratyp IS '1=Wohn-o.öff.Geb., 2=Wirtsch.o.Industrie, 0=keine Schraffur';


--
-- Name: alkgeom; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkgeom (
    gid serial NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    artgeo character varying(2) DEFAULT ''::character varying NOT NULL,
    arw double precision NOT NULL,
    ahw double precision NOT NULL,
    objart smallint NOT NULL,
    darken character varying(1),
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    umdrehn boolean NOT NULL,
    aecht boolean NOT NULL,
    eecht boolean NOT NULL,
    lageparam text,
    erw double precision NOT NULL,
    ehw double precision NOT NULL,
    lmeri character varying(1) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alkgeom OWNER TO kvwmap;

--
-- Name: TABLE alkgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkgeom IS 'Geometrie der ALK';


--
-- Name: COLUMN alkgeom.gid; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.gid IS 'bei der Konvertierung generierte ID (künstlicher Schlüssel)';


--
-- Name: COLUMN alkgeom.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkgeom.artgeo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.artgeo IS 'Art der Geometrie';


--
-- Name: COLUMN alkgeom.arw; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.arw IS 'Anfangspunkt Rechtswert (x)';


--
-- Name: COLUMN alkgeom.ahw; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.ahw IS 'Anfangspunkt Hochwert (y)';


--
-- Name: COLUMN alkgeom.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.objart IS 'Objektart (Linie)';


--
-- Name: COLUMN alkgeom.darken; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.darken IS 'Darstellungskennung für Linien';


--
-- Name: COLUMN alkgeom.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkgeom.umdrehn; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.umdrehn IS 'ALK-Linie umdrehen?  Nein: Objekt liegt rechts.  Ja: Objekt liegt links.';


--
-- Name: COLUMN alkgeom.aecht; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.aecht IS 'Anfangspunkt echt?  Ja: P. hat fachl. Bedeutung, wie Eingabe.  Nein: Linie innerhalb der DB geteilt.';


--
-- Name: COLUMN alkgeom.eecht; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.eecht IS 'Endpunkt echt?  Ja: P. hat fachl. Bedeutung, wie Eingabe.  Nein: Linie innerhalb der DB geteilt.';


--
-- Name: COLUMN alkgeom.lageparam; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.lageparam IS '(Liste der..) Lageparameter (Zwischenpunkte, Kreismittelpunkt)';


--
-- Name: COLUMN alkgeom.erw; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.erw IS 'Endpunkt Rechtswert (x)';


--
-- Name: COLUMN alkgeom.ehw; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.ehw IS 'Endpunkt Hochwert (y)';


--
-- Name: COLUMN alkgeom.lmeri; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeom.lmeri IS 'Meridianstreifensystem der Linie';


--
-- Name: alkgeom_gid_seq; Type: SEQUENCE SET; Schema: public; Owner: kvwmap
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('alkgeom', 'gid'), 650789, true);


--
-- Name: alkgeomart; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkgeomart (
    artgeo character varying(2) DEFAULT ''::character varying NOT NULL,
    artgeo_txt character varying(80) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alkgeomart OWNER TO kvwmap;

--
-- Name: TABLE alkgeomart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkgeomart IS 'Art der Geometrie (Schluesseltabelle)';


--
-- Name: COLUMN alkgeomart.artgeo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeomart.artgeo IS 'Art der Geometrie';


--
-- Name: COLUMN alkgeomart.artgeo_txt; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkgeomart.artgeo_txt IS 'Art der Geometrie (entschlüsselt)';


--
-- Name: alkinfoart; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkinfoart (
    artinfo smallint NOT NULL,
    artinfo_txt character varying(100) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alkinfoart OWNER TO kvwmap;

--
-- Name: TABLE alkinfoart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkinfoart IS 'Art der bes. Inf. zum Objekt (Schluesseltabelle)';


--
-- Name: COLUMN alkinfoart.artinfo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkinfoart.artinfo IS 'Art der Besonderen Information, DLOB2101';


--
-- Name: COLUMN alkinfoart.artinfo_txt; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkinfoart.artinfo_txt IS 'Art der bes. Inf. zum Objekt (entschlüsselt)';


--
-- Name: alknamobj; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alknamobj (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    artinfo smallint NOT NULL,
    fdat character varying(2) DEFAULT ''::character varying NOT NULL,
    objnam character varying(33) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alknamobj OWNER TO kvwmap;

--
-- Name: TABLE alknamobj; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alknamobj IS 'Objektnamen zu Objekten (Schluessel aus ext. Fachdateien)';


--
-- Name: COLUMN alknamobj.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknamobj.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alknamobj.artinfo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknamobj.artinfo IS 'Art der Besonderen Information, DLOB2101';


--
-- Name: COLUMN alknamobj.fdat; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknamobj.fdat IS 'Fachdateikennung';


--
-- Name: COLUMN alknamobj.objnam; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknamobj.objnam IS 'Objektname';


--
-- Name: alknflst; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alknflst (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    gemkgschl character varying(6) DEFAULT ''::character varying NOT NULL,
    flurstkennz character varying(27) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alknflst OWNER TO kvwmap;

--
-- Name: TABLE alknflst; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alknflst IS 'Flurstueckskennzeichen (Objektnamen) aus dem ALB';


--
-- Name: COLUMN alknflst.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknflst.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alknflst.gemkgschl; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknflst.gemkgschl IS 'Gemarkungsschlüssel';


--
-- Name: COLUMN alknflst.flurstkennz; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknflst.flurstkennz IS 'Flurstückskennzeichen im ALB-Format';


--
-- Name: alknflur; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alknflur (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    gemkgschl character varying(6) DEFAULT ''::character varying NOT NULL,
    flur character varying(3) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alknflur OWNER TO kvwmap;

--
-- Name: TABLE alknflur; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alknflur IS 'Fluren (Objektnamen) aus dem ALB';


--
-- Name: COLUMN alknflur.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknflur.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alknflur.gemkgschl; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknflur.gemkgschl IS 'Gemarkungsschlüssel';


--
-- Name: COLUMN alknflur.flur; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknflur.flur IS 'Flurnummer innerhalb einer Gemarkung';


--
-- Name: alknhaus; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alknhaus (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    gemeinde integer NOT NULL,
    strasse character varying(5) DEFAULT ''::character varying NOT NULL,
    hausnr character varying(8) DEFAULT ''::character varying NOT NULL,
    lfdnr character varying(8) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alknhaus OWNER TO kvwmap;

--
-- Name: TABLE alknhaus; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alknhaus IS 'Gebaeude (Objektnamen), verkn. mit f_Adressen aus ALB-Info';


--
-- Name: COLUMN alknhaus.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknhaus.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alknhaus.gemeinde; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknhaus.gemeinde IS 'Gemeindeschlüssel';


--
-- Name: COLUMN alknhaus.strasse; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknhaus.strasse IS 'Straßenschlüssel';


--
-- Name: COLUMN alknhaus.hausnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknhaus.hausnr IS 'Hausnummer mit Zusatz (Nr. in Stelle 1-4 rechtsbündig)';


--
-- Name: COLUMN alknhaus.lfdnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknhaus.lfdnr IS 'laufende Nummer der Gebäude zu einer Adresse';


--
-- Name: alknpunkt; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alknpunkt (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    nbz character varying(8) DEFAULT ''::character varying NOT NULL,
    pat character varying(1) DEFAULT ''::character varying NOT NULL,
    pnr character varying(5) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alknpunkt OWNER TO kvwmap;

--
-- Name: TABLE alknpunkt; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alknpunkt IS 'Punkte (Objektnamen) aus der ALK-Punktdatei';


--
-- Name: COLUMN alknpunkt.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknpunkt.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alknpunkt.nbz; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknpunkt.nbz IS 'Nummerierungsbezirk';


--
-- Name: COLUMN alknpunkt.pat; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknpunkt.pat IS 'Punktart';


--
-- Name: COLUMN alknpunkt.pnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alknpunkt.pnr IS 'Punktnummer';


SET default_with_oids = true;

--
-- Name: alkobj_a_lin; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_a_lin (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    artinfo smallint NOT NULL,
    objkartyp character varying(2),
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'MULTILINESTRING'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_a_lin OWNER TO kvwmap;

--
-- Name: TABLE alkobj_a_lin; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_a_lin IS 'Objekte der ALK - Ausgestaltung: Linien';


--
-- Name: COLUMN alkobj_a_lin.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_lin.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_a_lin.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_lin.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_a_lin.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_lin.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_a_lin.artinfo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_lin.artinfo IS 'Art der Besonderen Information, DLOB2101';


--
-- Name: COLUMN alkobj_a_lin.objkartyp; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_lin.objkartyp IS 'Kartentyp (Ausgabe nur bei ..)';


--
-- Name: COLUMN alkobj_a_lin.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_lin.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_a_lin.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_lin.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_a_lin.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_lin.the_geom IS 'WKT Geometrie';


--
-- Name: alkobj_a_sym; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_a_sym (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    artinfo smallint NOT NULL,
    objkartyp character varying(2),
    winkel real,
    groesse real NOT NULL,
    artgeo character varying(2) DEFAULT ''::character varying NOT NULL,
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'POINT'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_a_sym OWNER TO kvwmap;

--
-- Name: TABLE alkobj_a_sym; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_a_sym IS 'Objekte der ALK - Ausgestaltung: Symbole';


--
-- Name: COLUMN alkobj_a_sym.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_a_sym.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_a_sym.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_a_sym.artinfo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.artinfo IS 'Art der Besonderen Information, DLOB2101';


--
-- Name: COLUMN alkobj_a_sym.objkartyp; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.objkartyp IS 'Kartentyp (Ausgabe nur bei ..)';


--
-- Name: COLUMN alkobj_a_sym.winkel; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.winkel IS 'Rotation, Drehwinkel zur Darstellung';


--
-- Name: COLUMN alkobj_a_sym.groesse; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.groesse IS 'Masstabsfaktor für das Symbol';


--
-- Name: COLUMN alkobj_a_sym.artgeo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.artgeo IS 'Art der Geometrie';


--
-- Name: COLUMN alkobj_a_sym.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_a_sym.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_a_sym.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_a_sym.the_geom IS 'WKT Geometrie';


--
-- Name: alkobj_d_fla; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_d_fla (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'MULTILINESTRING'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_d_fla OWNER TO kvwmap;

--
-- Name: TABLE alkobj_d_fla; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_d_fla IS 'Objekte der ALK - Darstellung: Flaechenbegrenzung';


--
-- Name: COLUMN alkobj_d_fla.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_fla.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_d_fla.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_fla.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_d_fla.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_fla.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_d_fla.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_fla.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_d_fla.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_fla.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_d_fla.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_fla.the_geom IS 'WKT Geometrie';


--
-- Name: alkobj_d_lin; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_d_lin (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    darken character varying(1),
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'LINESTRING'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_d_lin OWNER TO kvwmap;

--
-- Name: TABLE alkobj_d_lin; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_d_lin IS 'Objekte der ALK - Darstellung: Linien';


--
-- Name: COLUMN alkobj_d_lin.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_lin.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_d_lin.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_lin.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_d_lin.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_lin.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_d_lin.darken; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_lin.darken IS 'Darstellungskennung für Linien';


--
-- Name: COLUMN alkobj_d_lin.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_lin.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_d_lin.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_lin.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_d_lin.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_d_lin.the_geom IS 'WKT Geometrie';


--
-- Name: alkobj_e_fla; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_e_fla (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_e_fla OWNER TO kvwmap;

--
-- Name: TABLE alkobj_e_fla; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_e_fla IS 'Objekte der ALK - Elementarobjekte: Flaechen';


--
-- Name: COLUMN alkobj_e_fla.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_fla.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_e_fla.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_fla.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_e_fla.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_fla.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_e_fla.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_fla.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_e_fla.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_fla.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_e_fla.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_fla.the_geom IS 'WKT Geometrie';


--
-- Name: alkobj_e_lin; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_e_lin (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'MULTILINESTRING'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_e_lin OWNER TO kvwmap;

--
-- Name: TABLE alkobj_e_lin; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_e_lin IS 'Objekte der ALK - Elementarobjekte: Linien';


--
-- Name: COLUMN alkobj_e_lin.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_lin.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_e_lin.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_lin.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_e_lin.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_lin.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_e_lin.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_lin.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_e_lin.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_lin.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_e_lin.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_lin.the_geom IS 'WKT Geometrie';


--
-- Name: alkobj_e_pkt; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_e_pkt (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'POINT'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_e_pkt OWNER TO kvwmap;

--
-- Name: TABLE alkobj_e_pkt; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_e_pkt IS 'Objekte der ALK - Elementarobjekte: Punkte';


--
-- Name: COLUMN alkobj_e_pkt.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_pkt.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_e_pkt.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_pkt.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_e_pkt.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_pkt.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_e_pkt.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_pkt.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_e_pkt.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_pkt.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_e_pkt.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_e_pkt.the_geom IS 'WKT Geometrie';


--
-- Name: alkobj_t_lin; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_t_lin (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    artinfo smallint NOT NULL,
    objkartyp character varying(2),
    label character varying(35),
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'LINESTRING'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_t_lin OWNER TO kvwmap;

--
-- Name: TABLE alkobj_t_lin; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_t_lin IS 'Objekte der ALK - Texte: Liniengeometrie';


--
-- Name: COLUMN alkobj_t_lin.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_t_lin.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_t_lin.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_t_lin.artinfo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.artinfo IS 'Art der Besonderen Information, DLOB2101';


--
-- Name: COLUMN alkobj_t_lin.objkartyp; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.objkartyp IS 'Kartentyp (Ausgabe nur bei ..)';


--
-- Name: COLUMN alkobj_t_lin.label; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.label IS 'Text zu einem ALK-Objekt';


--
-- Name: COLUMN alkobj_t_lin.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_t_lin.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_t_lin.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_lin.the_geom IS 'WKT Geometrie';


--
-- Name: alkobj_t_pkt; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobj_t_pkt (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    artinfo smallint NOT NULL,
    objkartyp character varying(2),
    label character varying(35),
    winkel real,
    mver boolean,
    objgeom text,
    bemerkung text,
    the_geom geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((geometrytype(the_geom) = 'POINT'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((srid(the_geom) = 2398))
);


ALTER TABLE public.alkobj_t_pkt OWNER TO kvwmap;

--
-- Name: TABLE alkobj_t_pkt; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobj_t_pkt IS 'Objekte der ALK - Texte: Punktgeometrie';


--
-- Name: COLUMN alkobj_t_pkt.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobj_t_pkt.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobj_t_pkt.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobj_t_pkt.artinfo; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.artinfo IS 'Art der Besonderen Information, DLOB2101';


--
-- Name: COLUMN alkobj_t_pkt.objkartyp; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.objkartyp IS 'Kartentyp (Ausgabe nur bei ..)';


--
-- Name: COLUMN alkobj_t_pkt.label; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.label IS 'Text zu einem ALK-Objekt';


--
-- Name: COLUMN alkobj_t_pkt.winkel; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.winkel IS 'Rotation, Drehwinkel zur Darstellung';


--
-- Name: COLUMN alkobj_t_pkt.mver; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.mver IS 'Standardposition, Label maßstabsabhängig verschieben';


--
-- Name: COLUMN alkobj_t_pkt.objgeom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.objgeom IS 'Geometrie des Objektes, WKT-Format';


--
-- Name: COLUMN alkobj_t_pkt.bemerkung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.bemerkung IS 'Besonderheiten bei der Konvertierung';


--
-- Name: COLUMN alkobj_t_pkt.the_geom; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobj_t_pkt.the_geom IS 'WKT Geometrie';


SET default_with_oids = false;

--
-- Name: alkobjekte; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobjekte (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    objaktu character varying(2) DEFAULT ''::character varying NOT NULL,
    entstehung date,
    objtyp character varying(1) DEFAULT ''::character varying NOT NULL,
    objr double precision NOT NULL,
    objh double precision NOT NULL
);


ALTER TABLE public.alkobjekte OWNER TO kvwmap;

--
-- Name: TABLE alkobjekte; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobjekte IS 'Objekte der ALK - Objektverwaltung';


--
-- Name: COLUMN alkobjekte.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjekte.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobjekte.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjekte.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkobjekte.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjekte.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkobjekte.objaktu; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjekte.objaktu IS 'Zaehler für Fortführungen';


--
-- Name: COLUMN alkobjekte.entstehung; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjekte.entstehung IS 'Entstehungsdatum';


--
-- Name: COLUMN alkobjekte.objtyp; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjekte.objtyp IS 'ALK-Objekttyp';


--
-- Name: COLUMN alkobjekte.objr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjekte.objr IS 'Objekt-Koordinate Rechtswert (x)';


--
-- Name: COLUMN alkobjekte.objh; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjekte.objh IS 'Objekt-Koordinate Hochwert (y)';


--
-- Name: alkobjswin; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkobjswin (
    objnr character varying(7) DEFAULT ''::character varying NOT NULL,
    gebwink smallint NOT NULL,
    schrawink smallint,
    schratyp smallint
);


ALTER TABLE public.alkobjswin OWNER TO kvwmap;

--
-- Name: TABLE alkobjswin; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkobjswin IS 'Schraffurwinkel zu Gebaeudeflaechen';


--
-- Name: COLUMN alkobjswin.objnr; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjswin.objnr IS 'Objektnummer (eindeutiger interner Schlüssel eines ALK-Objektes)';


--
-- Name: COLUMN alkobjswin.gebwink; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjswin.gebwink IS 'Schraffurwinkel (Einheit Grad)';


--
-- Name: COLUMN alkobjswin.schrawink; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjswin.schrawink IS 'Schraffurwinkel (Einheit Grad)';


--
-- Name: COLUMN alkobjswin.schratyp; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkobjswin.schratyp IS '1=Wohn-o.öff.Geb., 2=Wirtsch.o.Industrie, 0=keine Schraffur';


--
-- Name: alkstdtxt; Type: TABLE; Schema: public; Owner: kvwmap; Tablespace: 
--

CREATE TABLE alkstdtxt (
    folie character varying(3) DEFAULT ''::character varying NOT NULL,
    objart smallint NOT NULL,
    standardtext character varying(40) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.alkstdtxt OWNER TO kvwmap;

--
-- Name: TABLE alkstdtxt; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON TABLE alkstdtxt IS 'ALK Standardtexte zu Objektarten';


--
-- Name: COLUMN alkstdtxt.folie; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkstdtxt.folie IS 'fachliche Ebene, Folie';


--
-- Name: COLUMN alkstdtxt.objart; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkstdtxt.objart IS 'Objektart laut OSKA (Objektschlüsselkatalog des Bundeslandes)';


--
-- Name: COLUMN alkstdtxt.standardtext; Type: COMMENT; Schema: public; Owner: kvwmap
--

COMMENT ON COLUMN alkstdtxt.standardtext IS 'Label, anzuzeigender Text (zur Objektart)';



--##########################
--# Beende die Transaktion #
--##########################
--# COMMIT TRANSACTION;
