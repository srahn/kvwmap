
--- migration 2014-08-03 00:00:00

-- Version 2.0.0

BEGIN;

CREATE SCHEMA nachweisverwaltung;

SET search_path = nachweisverwaltung, public;

SET default_with_oids = true;

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
    datum date,
    vermstelle character varying,
    gueltigkeit integer,
    link_datei character varying,
    art character(3),
    format character(2),
    stammnr character varying(15)
)
WITH OIDS;
SELECT AddGeometryColumn('nachweisverwaltung', 'n_nachweise','the_geom',2398,'GEOMETRY', 2);
CREATE INDEX n_nachweise_the_geom_gist ON n_nachweise USING GIST (the_geom);
ALTER TABLE n_nachweise ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);

ALTER TABLE nachweisverwaltung.n_nachweise ADD COLUMN fortfuehrung integer;
ALTER TABLE nachweisverwaltung.n_nachweise ADD COLUMN rissnummer character varying(20);
ALTER TABLE nachweisverwaltung.n_nachweise ADD COLUMN bemerkungen text;

-- Zuordnung der Nachweise zu den Antraegen
CREATE TABLE n_nachweise2antraege (
    nachweis_id integer,
    antrag_id character varying(11)
);
ALTER TABLE n_nachweise2antraege
  ADD CONSTRAINT n_nachweise2antraege_pkey PRIMARY KEY(nachweis_id, antrag_id);

-- Vermarkungsart
CREATE TABLE n_vermart (
    id serial NOT NULL PRIMARY KEY,
    art character varying(50)
);

INSERT INTO n_vermart (art) VALUES ('Gebäudeeinmessung');
INSERT INTO n_vermart (art) VALUES ('Zerlegung');
INSERT INTO n_vermart (art) VALUES ('Genzfeststellung');
INSERT INTO n_vermart (art) VALUES ('Sonderung');
INSERT INTO n_vermart (art) VALUES ('Bodenordnung');

-- Vermessungsstelle
CREATE TABLE n_vermstelle (
    id serial NOT NULL PRIMARY KEY,
    name character varying(255)
);

INSERT INTO n_vermstelle (name) VALUES ('Ahrens, Christoph');
INSERT INTO n_vermstelle (name) VALUES ('Anders, Sven');
INSERT INTO n_vermstelle (name) VALUES ('Apolony, Dieter');
INSERT INTO n_vermstelle (name) VALUES ('Bannuscher, Holger');
INSERT INTO n_vermstelle (name) VALUES ('Bauer, Lothar');
INSERT INTO n_vermstelle (name) VALUES ('Bernau, Ullrich');
INSERT INTO n_vermstelle (name) VALUES ('Bock, Friedhelm');
INSERT INTO n_vermstelle (name) VALUES ('Boerner, Norbert');
INSERT INTO n_vermstelle (name) VALUES ('Borutta, Gerd');
INSERT INTO n_vermstelle (name) VALUES ('Brandenburg, Lothar');
INSERT INTO n_vermstelle (name) VALUES ('Brandt, Hans');
INSERT INTO n_vermstelle (name) VALUES ('Brekenfelder, Andre');
INSERT INTO n_vermstelle (name) VALUES ('Buse, Annette');
INSERT INTO n_vermstelle (name) VALUES ('Dubbert, Jörg-M.');
INSERT INTO n_vermstelle (name) VALUES ('Fiebig, Wilfried');
INSERT INTO n_vermstelle (name) VALUES ('Gajek, Diethard');
INSERT INTO n_vermstelle (name) VALUES ('Golnik, Andreas');
INSERT INTO n_vermstelle (name) VALUES ('Grünhagen, Kai');
INSERT INTO n_vermstelle (name) VALUES ('Grüning, Marion');
INSERT INTO n_vermstelle (name) VALUES ('Gudat, Jürgen');
INSERT INTO n_vermstelle (name) VALUES ('Hansch, Peter');
INSERT INTO n_vermstelle (name) VALUES ('Harnisch, Thomas');
INSERT INTO n_vermstelle (name) VALUES ('Hermerding, Ralf');
INSERT INTO n_vermstelle (name) VALUES ('Herzog, Thomas');
INSERT INTO n_vermstelle (name) VALUES ('Hiersekorn, Reiner');
INSERT INTO n_vermstelle (name) VALUES ('Hilbring, Heinrich');
INSERT INTO n_vermstelle (name) VALUES ('Hiltscher, Roland');
INSERT INTO n_vermstelle (name) VALUES ('Hoffmann, Heiko');
INSERT INTO n_vermstelle (name) VALUES ('Holst, Wolfgang');
INSERT INTO n_vermstelle (name) VALUES ('Jacobs, Heinz-Dieter');
INSERT INTO n_vermstelle (name) VALUES ('Jansen, Hans-Gerd');
INSERT INTO n_vermstelle (name) VALUES ('Jeske, Andre');
INSERT INTO n_vermstelle (name) VALUES ('Kattner, Wolfgang');
INSERT INTO n_vermstelle (name) VALUES ('Klug, Andreas');
INSERT INTO n_vermstelle (name) VALUES ('Krähmer, Heike');
INSERT INTO n_vermstelle (name) VALUES ('Krätschel, Michael');
INSERT INTO n_vermstelle (name) VALUES ('Krawutschke, Holger');
INSERT INTO n_vermstelle (name) VALUES ('Kremer, Wolfgang');
INSERT INTO n_vermstelle (name) VALUES ('Lessner, Rainer');
INSERT INTO n_vermstelle (name) VALUES ('Lorenz, Änne');
INSERT INTO n_vermstelle (name) VALUES ('Lorenz, Renate');
INSERT INTO n_vermstelle (name) VALUES ('Lübcke, Holger');
INSERT INTO n_vermstelle (name) VALUES ('Lusch, Jürgen');
INSERT INTO n_vermstelle (name) VALUES ('Maaß, Klaus-Dieter');
INSERT INTO n_vermstelle (name) VALUES ('Manthey, Dirk');
INSERT INTO n_vermstelle (name) VALUES ('Matthias, Gerd');
INSERT INTO n_vermstelle (name) VALUES ('Meißner, Gerd');
INSERT INTO n_vermstelle (name) VALUES ('Meißner, Torsten');
INSERT INTO n_vermstelle (name) VALUES ('Mill, Arno');
INSERT INTO n_vermstelle (name) VALUES ('Möbius, Steffen');
INSERT INTO n_vermstelle (name) VALUES ('Müller, Hans-Jürgen');
INSERT INTO n_vermstelle (name) VALUES ('Neiseke, Jörg');
INSERT INTO n_vermstelle (name) VALUES ('Panke, Wilfried');
INSERT INTO n_vermstelle (name) VALUES ('Prestin, Jürgen');
INSERT INTO n_vermstelle (name) VALUES ('Reimers, Dietmar');
INSERT INTO n_vermstelle (name) VALUES ('Sankowsky, Mario');
INSERT INTO n_vermstelle (name) VALUES ('Schmidt, Jürgen');
INSERT INTO n_vermstelle (name) VALUES ('Schönemann, Dirk');
INSERT INTO n_vermstelle (name) VALUES ('Scholwin, Klaus-Peter');
INSERT INTO n_vermstelle (name) VALUES ('Schröder, Reinhard');
INSERT INTO n_vermstelle (name) VALUES ('Schröder, Wolfgang');
INSERT INTO n_vermstelle (name) VALUES ('Seehase, Karl-Heinz');
INSERT INTO n_vermstelle (name) VALUES ('Seehase, Stefan');
INSERT INTO n_vermstelle (name) VALUES ('Sperlich, Mirjam');
INSERT INTO n_vermstelle (name) VALUES ('Stechert, Andreas');
INSERT INTO n_vermstelle (name) VALUES ('Stechert, Werner');
INSERT INTO n_vermstelle (name) VALUES ('Sy, Torsten');
INSERT INTO n_vermstelle (name) VALUES ('Täger, Hans-Georg');
INSERT INTO n_vermstelle (name) VALUES ('Urban, Oliver');
INSERT INTO n_vermstelle (name) VALUES ('Wagner, Frank');
INSERT INTO n_vermstelle (name) VALUES ('Walther, Lothar');
INSERT INTO n_vermstelle (name) VALUES ('Weinert, Herbert');
INSERT INTO n_vermstelle (name) VALUES ('Weinke, Gunnar');
INSERT INTO n_vermstelle (name) VALUES ('Werner, Hansjoachim');
INSERT INTO n_vermstelle (name) VALUES ('Wieck, Eberhard');
INSERT INTO n_vermstelle (name) VALUES ('Winkelmann, Jörg');
INSERT INTO n_vermstelle (name) VALUES ('Zeh, Ulrich');
INSERT INTO n_vermstelle (name) VALUES ('Zeise, Petra');

-- Tabelle für andere Dokumentarten in der Nachweisverwaltung

CREATE TABLE n_dokumentarten(
   id serial NOT NULL, 
   art character varying(100)
) 
WITH OIDS;

-- Tabelle für die Zuordnung von Nachweisen zu anderen Dokumentarten

CREATE TABLE n_nachweise2dokumentarten(
   nachweis_id integer NOT NULL, 
   dokumentart_id integer NOT NULL
) 
WITH OIDS;

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
SELECT AddGeometryColumn('nachweisverwaltung', 'fp_punkte','the_geom',2398,'POINT', 3);
CREATE INDEX fp_punkte_the_geom_gist ON fp_punkte USING GIST (the_geom);

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
SELECT AddGeometryColumn('nachweisverwaltung', 'fp_punkte2','the_geom',2399,'POINT', 3);
CREATE INDEX fp_punkte2_the_geom_gist ON fp_punkte2 USING GIST (the_geom);

--####################################
--# Temporäre Tabelle für Punktdatei #
--####################################
CREATE TABLE fp_punkte_temp
(
  pkz character(16) NOT NULL,
  rw character varying(11),
  hw character varying(11),
  hoe character varying(9),
  s character varying(4),
  zst character varying(7),
  vma character varying(3),
  bem character varying(4),
  ent character varying(15),
  unt character varying(15),
  zuo character varying(15),
  tex character varying(25),
  ls character varying(3),
  lg character varying(1),
  lz character varying(1),
  lbj character varying(3),
  lah character varying(9),
  hs character varying(15),
  hg character varying(15),
  hz character varying(15),
  hbj character varying(15),
  hah character varying(15),
  pktnr character varying(5),
  art integer DEFAULT 0,
  datei character varying(50),
  verhandelt integer DEFAULT 0,
  vermarkt integer DEFAULT 0,
  the_geom geometry,
  CONSTRAINT fp_punkte_temp_pkey PRIMARY KEY (pkz ),
  CONSTRAINT enforce_dims_koordinaten CHECK (st_ndims(the_geom) = 3),
  CONSTRAINT enforce_geotype_koordinaten CHECK (geometrytype(the_geom) = 'POINT'::text OR the_geom IS NULL)
)
WITH (
  OIDS=TRUE
);

--#####################################################
--# Tabelle für die Zuordnung der Punkte zu Aufträgen #
--#####################################################

-- Table: fp_punkte2antraege

CREATE TABLE fp_punkte2antraege
(
  pkz char(16) NOT NULL,
  antrag_nr varchar(11) NOT NULL,
  zeitstempel timestamp,
  CONSTRAINT fp_punkte2antraege_pkey PRIMARY KEY (pkz, antrag_nr)
) 
WITHOUT OIDS;

COMMIT;
