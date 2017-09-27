BEGIN;

-- ALKIS PostNAS 0.7

-- Post Processing (pp_) Teil 1: Anlegen der Tabellen und Views

-- ACHTUNG!
-- Systemvariable vorher setzen für das Koordinatensystem, z.B.
-- EPSG=25832

-- Stand 

--  2012-02-13 PostNAS 07, Umbenennung
--  2012-02-17 Optimierung
--  2012-02-28 gkz aus View nehmen
--  2012-04-17 Flurstuecksnummern auf Standardposition
--  2012-04-23 ax_flurstueck hat keinen Unique Index mahr auf gml_id,
--             ForeignKey vorübergehend ausgeschaltet.
--  2012-04-25 simple_geom fuer pp_flur
--  2013-04-18 Kommentare.
--  2012-10-24 Neue Tabelle für die Präsentation von Straßennamen und -Klassifikationen


-- ============================
-- Tabellen des Post-Processing
-- ============================

-- Einige Informationen liegen nach der NAS-Konvertierung in der Datenbank "verstreut" vor.
-- Die dynamische Aufbereitung über Views und Functions würde zu lange dauern und somit lange 
-- Antwortzeiten in WMS, WFS, Buchauskunft oder Navigation (Suche) verursachen.

-- Im Rahmen eines "Post-Processing" werden diese Daten nach jeder Konvertierung (NBA-Aktialisierung) 
-- einmal komplett aufbereitet. Die benötigten Informationen stehen somit den Anwendungen mundgerecht zur Verfügung.

-- Die per PostProcessing gefüllten Tabellen bekommen den Prefix "pp_".

-- ToDo:

-- Muss *multi*-Polygon sein? Gibt es "zerrissene" Fluren/Gemarkungen?
-- Der View "gemeinde_gemarkung" kann entfallen, wenn Navigation umgestellt wurde.

SET client_encoding = 'UTF-8';
SET default_with_oids = true;
SET search_path = alkis, public;

-- Alles auf Anfang!

-- DROP TABLE pp_kreis;
-- DROP TABLE pp_amt;
-- DROP TABLE pp_gemeinde;
-- DROP TABLE pp_gemarkung;
-- DROP TABLE pp_flur;


-- Tabelle fuer Kreis
-- ========================

  CREATE TABLE pp_kreis (
    gid			serial,
    land		text NOT NULL,
    regierungsbezirk	text,
    kreis		text NOT NULL,
    kreisname		character varying(80),
    anz_aemter		integer,		-- Anzahl Aemter
    CONSTRAINT pp_kreis_pk PRIMARY KEY (land, kreis)
  );

CREATE UNIQUE INDEX pp_kreis_gid_ix ON pp_kreis (gid);

-- Gesamtflaeche
SELECT AddGeometryColumn('pp_kreis','the_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_kreis_gidx ON pp_kreis USING gist(the_geom);

-- vereinfachte Gesamtflaeche
SELECT AddGeometryColumn('pp_kreis','simple_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_kreis_sgidx ON pp_kreis USING gist(simple_geom);


  COMMENT ON TABLE  pp_kreis             IS 'Post-Processing: Kreis';
  COMMENT ON COLUMN pp_kreis.kreis       IS 'Kreisschlüssel';
  COMMENT ON COLUMN pp_kreis.the_geom    IS 'präzise Geometrie aus Summe aller Ämter';
  COMMENT ON COLUMN pp_kreis.simple_geom    IS 'vereinfachte Geometrie für die Suche und die Anzeige von Übersichten in kleinen Maßstäben.';


-- Tabelle fuer Aemter
-- ========================

  CREATE TABLE pp_amt (
    gid			serial,
    land		text NOT NULL,
    regierungsbezirk	text,
    kreis		text,
    amt			text NOT NULL,
    amtsname		character varying(80),
    anz_gemeinden	integer,
    CONSTRAINT pp_aemter_pk PRIMARY KEY (land, kreis, amt)
  );

CREATE UNIQUE INDEX pp_amt_gid_ix ON pp_amt (gid);

-- Gesamtflaeche
SELECT AddGeometryColumn('pp_amt','the_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_amt_gidx ON pp_amt USING gist(the_geom);

-- vereinfachte Gesamtflaeche
SELECT AddGeometryColumn('pp_amt','simple_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_amt_sgidx ON pp_amt USING gist(simple_geom);


  COMMENT ON TABLE  pp_amt            	IS 'Post-Processing: Amt';
  COMMENT ON COLUMN pp_amt.amt       	IS 'Amtsschlüssel';
  COMMENT ON COLUMN pp_amt.the_geom  	IS 'präzise Geometrie aus Summe aller Gemeinden';
  COMMENT ON COLUMN pp_amt.simple_geom    IS 'vereinfachte Geometrie für die Suche und die Anzeige von Übersichten in kleinen Maßstäben.';



-- Tabelle fuer Gemeinden
-- ========================

  CREATE TABLE pp_gemeinde (
    gid			serial,
    land		text NOT NULL,
    regierungsbezirk	text,
    kreis		text,
    gemeinde		text NOT NULL,
    gemeindename	character varying(80),
 -- gkz			character varying(03),	-- wird (noch) nicht benutzt
    anz_gemarkg		integer,		-- Anzahl Gemarkungen
    CONSTRAINT pp_gemeinde_pk PRIMARY KEY (land, kreis, gemeinde)
  );

CREATE UNIQUE INDEX pp_gemeinde_gid_ix ON pp_gemeinde (gid);

-- Gesamtflaeche
SELECT AddGeometryColumn('pp_gemeinde','the_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_gemeinde_gidx ON pp_gemeinde USING gist(the_geom);

-- vereinfachte Gesamtflaeche
SELECT AddGeometryColumn('pp_gemeinde','simple_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_gemeinde_sgidx ON pp_gemeinde USING gist(simple_geom);


  COMMENT ON TABLE  pp_gemeinde                IS 'Post-Processing: Gemeinde';
  COMMENT ON COLUMN pp_gemeinde.gemeinde       IS 'Gemeindenummer';
--COMMENT ON COLUMN pp_gemeinde.gkz            IS 'Gemeindekennziffer für Mandant';
  COMMENT ON COLUMN pp_gemeinde.the_geom       IS 'präzise Geometrie aus Summe aller Gemarkungen';
  COMMENT ON COLUMN pp_gemeinde.simple_geom    IS 'vereinfachte Geometrie für die Suche und die Anzeige von Übersichten in kleinen Maßstäben.';


-- Tabelle fuer Gemarkungen
-- ========================

-- Für die Regelung der Zugriffsberechtigung einer Gemeindeverwaltung auf die 
-- Flurstücke in ihrem Gebiet braucht man die Information, in welcher Gemeinde eine Gemarkung liegt.
-- 'ax_gemeinde' und 'ax_gemarkung' haben aber im ALKIS keinerlei Beziehung zueinander - kaum zu glauben!
-- Nur über die Auswertung der Flurstücke kann man die Zuordnung ermitteln.
-- Da nicht ständig mit 'SELECT DISTINCT' sämtliche Flurstücke durchsucht werden können, 
-- muss diese Information als (redundante) Tabelle nach dem Laden zwischengespeichert werden. 

  CREATE TABLE pp_gemarkung (
    gid			serial,
    land		text NOT NULL,
    regierungsbezirk	text,
    kreis		text,
    gemeinde		text NOT NULL,	-- fast ein Foreign-Key Constraint
    gemarkung		text NOT NULL,
		schluesselgesamt text,																				-- hinzugefügt am 21.10.2015
    gemarkungsname	character varying(80),
    anz_flur		integer,		-- Anzahl Fluren
    CONSTRAINT pp_gemarkung_pk PRIMARY KEY (land, gemarkung)
  );

CREATE UNIQUE INDEX pp_gemarkung_gid_ix ON pp_gemarkung (gid);

-- Gesamtfläche
SELECT AddGeometryColumn('pp_gemarkung','the_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_gemarkung_gidx ON pp_gemarkung USING gist(the_geom);

-- vereinfachte Gesamtfläche
SELECT AddGeometryColumn('pp_gemarkung','simple_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_gemarkung_sgidx ON pp_gemarkung USING gist(simple_geom);


COMMENT ON TABLE  pp_gemarkung               IS 'Post-Processing: Gemarkung. u.a. liegt in welcher Gemeinde';
COMMENT ON COLUMN pp_gemarkung.gemeinde      IS 'Gemeindenummer';
COMMENT ON COLUMN pp_gemarkung.gemarkung     IS 'Gemarkungsnummer';
COMMENT ON COLUMN pp_gemarkung.the_geom      IS 'präzise Geometrie aus Summe aller Fluren';
COMMENT ON COLUMN pp_gemarkung.simple_geom   IS 'vereinfachte Geometrie für die Suche und die Anzeige von Übersichten in kleinen Maßstäben.';


-- Tabelle fuer Fluren
-- ===================

  CREATE TABLE pp_flur (
    gid			serial,
    land		text NOT NULL,
    regierungsbezirk	text,
    kreis		text,
    gemarkung		text NOT NULL,
    flurnummer		integer NOT NULL,
    anz_fs		integer,		-- Anzahl Flurstücke
    CONSTRAINT pp_flur_pk PRIMARY KEY (land, gemarkung, flurnummer)
  );

-- ALTER TABLE pp_flur ADD COLUMN gid serial;
CREATE UNIQUE INDEX pp_flur_gid_ix ON pp_flur (gid);

-- Gesamtfläche
SELECT AddGeometryColumn('pp_flur','the_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_flur_gidx ON pp_flur USING gist(the_geom);

-- vereinfachte Gesamtflaeche
SELECT AddGeometryColumn('pp_flur','simple_geom',:alkis_epsg,'MULTIPOLYGON',2);
CREATE INDEX pp_flur_sgidx ON pp_flur USING gist(simple_geom);


COMMENT ON TABLE  pp_flur                IS 'Post-Processing: Flur';
COMMENT ON COLUMN pp_flur.gemarkung      IS 'Gemarkungsnummer';
COMMENT ON COLUMN pp_flur.the_geom       IS 'Geometrie aus Summe aller Flurstücke';
COMMENT ON COLUMN pp_flur.simple_geom    IS 'vereinfachte Geometrie für die Suche und die Anzeige von Übersichten in kleinen Maßstäben.';



-- Flurstuecksnummern-Position
-- ===========================
-- Die Tabelle "pp_flurstueck_nr" ersetzt den View "s_flurstueck_nr" für WMS-Layer "ag_t_flurstueck".

--DROP TABLE pp_flurstueck_nr;
  CREATE TABLE pp_flurstueck_nr (
    gid		serial,
    fsgml	varchar,
		beginnt character(20),
		endet character(20),
    fsnum	character varying(10),  -- zzzzz/nnnn
		abweichenderrechtszustand	varchar,							-- hinzugefügt am 25.06.2015
    CONSTRAINT pp_flurstueck_nr_pk  PRIMARY KEY (gid)  --,
-- Foreign Key
-- ALT:
--    CONSTRAINT pp_flurstueck_nr_gml FOREIGN KEY (fsgml)
--      REFERENCES ax_flurstueck (gml_id) MATCH SIMPLE
--      ON UPDATE CASCADE ON DELETE CASCADE
-- Durch Änderung Patch #5444 am 2012-04-23 hat 'ax_flurstueck' keinen Unique-Index mehr auf gml_id
-- Ersatzweise einen ForeignKey über 2 Felder?
  );

SELECT AddGeometryColumn('pp_flurstueck_nr','the_geom',:alkis_epsg,'POINT',2);

-- Geometrischer Index
CREATE INDEX pp_flurstueck_nr_gidx ON pp_flurstueck_nr USING gist(the_geom);

-- Foreig-Key Index
CREATE INDEX fki_pp_flurstueck_nr_gml ON pp_flurstueck_nr(fsgml);

COMMENT ON TABLE  pp_flurstueck_nr           IS 'Post-Processing: Position der Flurstücksnummer in der Karte';
COMMENT ON COLUMN pp_flurstueck_nr.fsgml     IS 'gml_id des zugehörigen Flurstücks-Objektes';
COMMENT ON COLUMN pp_flurstueck_nr.fsnum     IS 'Label, Darzustellende FS-Nummer als Bruch';
COMMENT ON COLUMN pp_flurstueck_nr.abweichenderrechtszustand	IS 'Dient der möglichen Ausblendung von Flurstücksnummern in Verfahren der Bodenordnung (siehe Objektart "Bau-, Raum- oder Bodenordnungsrecht", AA "Art der Festlegung", Werte 1750, 1770, 2100 bis 2340) bei denen ein neuer Rechtszustand eingetreten ist und das amtliche Verzeichnis der jeweiligen ausführenden Stelle maßgebend ist.'; -- hinzugefügt am 25.06.2015
COMMENT ON COLUMN pp_flurstueck_nr.the_geom  IS 'Position der Flurstücksnummer in der Karte';



-- NEU 2013-10-24
-- Tabelle für die Präsentation von Straßen-Namen und -Klassifikationen
-- Tabelle "pp_strassenname" speichert den VIEW "ap_pto_stra".

--DROP TABLE pp_strassenname;
CREATE TABLE pp_strassenname 
(   gid		serial NOT NULL,
    gml_id character(16),
 -- advstandardmodell character varying[],
    schriftinhalt character varying, -- Label: anzuzeigender Text
    hor character varying,
    ver character varying,
 -- signaturnummer character varying,
 -- darstellungsprioritaet integer,
    art character varying,
    winkel double precision,
    CONSTRAINT pp_snam_pk  PRIMARY KEY (gid)
) WITH (OIDS=FALSE);

SELECT AddGeometryColumn('pp_strassenname','the_geom',:alkis_epsg,'POINT',2);
CREATE INDEX pp_snam_gidx ON pp_strassenname USING gist(the_geom); 

  COMMENT ON TABLE  pp_strassenname                IS 'Post-Processing: Label der Straßennamen in der Karte. Auszug aus ap_pto.';

  COMMENT ON COLUMN pp_strassenname.gid            IS 'Editierschlüssel der Tabelle';
  COMMENT ON COLUMN pp_strassenname.gml_id         IS 'Objektschlüssel des Präsentationsobjektes aus ap_pto. Zur Verbindung mit Katalog.';
  COMMENT ON COLUMN pp_strassenname.schriftinhalt  IS 'Label, darzustellender Name der Straße oder Klassifikation';
  COMMENT ON COLUMN pp_strassenname.hor            IS 'Horizontale Ausrichtung des Textes zur Punkt-Koordinate: linksbündig, zentrisch, ...';
  COMMENT ON COLUMN pp_strassenname.ver            IS 'Vertikale   Ausrichtung des Textes zur Punkt-Koordinate: Basis, ..';
  COMMENT ON COLUMN pp_strassenname.art            IS 'Klasse der Straße: Straße, Weg, .. , BezKlassifizierungStrasse';
  COMMENT ON COLUMN pp_strassenname.winkel         IS 'Drehung des Textes';
  COMMENT ON COLUMN pp_strassenname.the_geom       IS 'Position (Punkt) der Labels in der Karte';


CREATE TABLE pp_zuordungspfeilspitze_flurstueck
(
  ogc_fid integer,
  winkel double precision,
	beginnt character(20),
	endet character(20),
	abweichenderrechtszustand varchar			-- eingefügt am 25.06.2015
)
WITH (
  OIDS=TRUE
);

SELECT AddGeometryColumn('pp_zuordungspfeilspitze_flurstueck','geom',:alkis_epsg,'POINT',2);
CREATE INDEX pp_zuordungspfeilspitze_flurstueckgidx ON pp_zuordungspfeilspitze_flurstueck USING gist(geom);


CREATE TABLE pp_zuordungspfeilspitze_bodensch
(
  ogc_fid integer,
  winkel double precision
)
WITH (
  OIDS=TRUE
);

SELECT AddGeometryColumn('pp_zuordungspfeilspitze_bodensch','geom',:alkis_epsg,'POINT',2);
CREATE INDEX pp_zuordungspfeilspitze_bodenschgidx ON pp_zuordungspfeilspitze_bodensch USING gist(geom);

	
-- ENDE --

COMMIT;
