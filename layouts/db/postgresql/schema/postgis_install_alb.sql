-- Installationsskript für die Datenbankstruktur, die kvwmap für Postgres mit PostGIS benötigt.
--
-- Voraussetzungen/Vorarbeiten
--
-- Ein Postgres version ab 4.7.3 ist installiert
--
-- Zusätzlich ist PostGIS ab 1.0 mit GEOS und Proj Unterstützung installiert
--
-- Zusätzlich ist eine Datenbank durch EDBS2WKT angelegt
--
-- Zusätzlich wurde ein Datenbank in Postgres erzeugt.
-- Für die Nutzung mit ALK sollte dieses Skript in einer fertigen
-- Datenbank des EDBS2WKT Konverters ausgeführt werden
-- Der Name der Datenbank wird in config.php angepasst.

--###########################
--# Starte eine Transaktion #
--###########################
--# START TRANSACTION;


CREATE SCHEMA custom_shapes;	-- kann auch anders heißen, ist der config.php über CUSTOM_SHAPE_SCHEMA definierbar


-- Tabelle zur Speicherung von Umringspolygonen aus uko-Dateien

CREATE TABLE uko_polygon
(
  id serial NOT NULL,
  username character varying(25),
  userid integer
)
WITH OIDS;
select AddGeometryColumn ('public','uko_polygon','the_geom',2398,'GEOMETRY',2);  -- oder 2399


CREATE OR REPLACE FUNCTION linefrompoly(geometry)
  RETURNS geometry AS
$BODY$SELECT 
	geomfromtext(
		replace(
			replace(
				replace(
					replace(
						replace(
							asText($1),'MULTIPOLYGON','MULTILINESTRING'
						),'POLYGON','MULTILINESTRING'
					), '(((', '(('
				), ')))', '))'
			), ')),((', '),('
		), srid($1)
	)$BODY$
  LANGUAGE 'sql' IMMUTABLE STRICT;
COMMENT ON FUNCTION linefrompoly(geometry) IS 'Liefert eine LINESTRING Gemetrie von einer MULTIPOLYGON oder POLYGON Geometrie zurück';



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
  thema character varying(20),
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

--###############################################################
--# Zusätzliche Funktionen zum Selektieren von einzelnen        #
--# Liniensegmenten aus einem Polygon 2007-07-17 pk             #
--# Die Funktionen müssen in dieser Reihenfolge erzeugt werden! #
--###############################################################


-- Function: linen(geometry, int4)
-- Liefert die n-te Linien innerhalb eines Polygon als Geometry zurück
-- DROP FUNCTION linen(geometry, int4);
CREATE OR REPLACE FUNCTION linen(geometry, int4)
  RETURNS geometry AS
  $BODY$SELECT st_geomfromtext('LINESTRING('||X(pointn(linefrompoly($1),$2))||' '||Y(pointn(linefrompoly($1),$2))||','||X(pointn(linefrompoly($1),$2+1))||' '||Y(pointn(linefrompoly($1),$2+1))||')',srid($1))$BODY$
  LANGUAGE 'sql' IMMUTABLE STRICT;

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

COMMENT ON FUNCTION snapline(geometry, geometry) IS 'Liefert die einzelne Kante eines LINESTRINGS mit der Geometry1, welche am dichtesten am Punkt mit der Geometrie 2 liegt als Geometry';
-- Beispiel zur Abfrage der Gebäudekante des gegebenen Objektes, welches am dichtesten zum gegebenen Punkt liegt und dessen Azimutwinkel.
-- SELECT AsText(snapline(linefrompoly(the_geom),st_geomfromtext('Point(4516219.4 6013803.0)',2398))) AS Segment
-- ,azimuth(pointn(snapline(linefrompoly(the_geom),st_geomfromtext('Point(4516219.4 6013803.0)',2398)),1),pointn(snapline(linefrompoly(the_geom),st_geomfromtext('Point(4516219.4 6013803.0)',2398)),2)) AS winkel
-- FROM alkobj_e_fla WHERE objnr = 'D0009O1'


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
CREATE INDEX bp_aenderungen_the_geom_gist ON bp_aenderungen USING GIST (the_geom);
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
CREATE INDEX u_polygon_the_geom_gist ON u_polygon USING GIST (the_geom);



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
CREATE INDEX q_fehlerellipsen_the_geom_gist ON q_fehlerellipsen USING GIST (the_geom);

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
  kategorie_id integer,
  person varchar(100),
  datum date
) 
WITH OIDS;
SELECT AddGeometryColumn('public', 'q_notizen','the_geom',2398,'POLYGON', 2);
CREATE INDEX q_notizen_the_geom_gist ON q_notizen USING GIST (the_geom);
ALTER TABLE q_notizen DROP CONSTRAINT enforce_geotype_the_geom;
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
WITH OIDS;

CREATE INDEX q_notizen_kategorie_id_idx ON q_notizen USING btree (kategorie_id);
CREATE INDEX q_notiz_kategorie2stelle_stelle_idx ON q_notiz_kategorie2stelle USING btree (stelle);
CREATE INDEX q_notiz_kategorie2stelle_kat_id_idx ON q_notiz_kategorie2stelle USING btree (stelle);
ALTER TABLE q_notiz_kategorie2stelle ADD CONSTRAINT q_notiz_kategorie2stelle_pkey PRIMARY KEY(stelle, kat_id);


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
CREATE INDEX md_metadata_the_geom_gist ON md_metadata USING GIST (the_geom);

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
CREATE INDEX ve_versiegelung_the_geom_gist ON ve_versiegelung USING GIST (the_geom);
ALTER TABLE ve_versiegelung DROP CONSTRAINT enforce_geotype_the_geom;
ALTER TABLE ve_versiegelung ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);


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
CREATE INDEX gt_bohrpunkte_bohrpunkt_gist ON gt_bohrpunkte USING GIST (bohrpunkt);

-- Erdwaermesonden
CREATE TABLE gt_erdwaermesonden (
    id serial NOT NULL PRIMARY KEY,
    bohrtiefe numeric(5,2),
    effizienz_wm numeric(6,2),
    ellipse_halbachse_a numeric(5,2),
    ellipse_halbachse_b numeric(5,2)
);
SELECT AddGeometryColumn('public', 'gt_erdwaermesonden','bohrpunkt',2398,'POINT', 2);
CREATE INDEX gt_erdwaermesonden_bohrpunkt_gist ON gt_erdwaermesonden USING GIST (bohrpunkt);

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
    
 
 
 
    
   

--##########################
--# Beende die Transaktion #
--##########################
--# COMMIT TRANSACTION;
