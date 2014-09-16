
BEGIN;

CREATE SCHEMA jagdkataster;

SET search_path = jagdkataster, public;

-- Tabelle Jagdbezirke
-- ####################### 

CREATE TABLE jagdbezirke
(
  id character varying(10),
  art character varying(15),
  flaeche numeric,
  name character varying(50),
  concode character varying(5), -- entspricht tbJagdbezirk.BCode in condition
  conname character varying(40), -- entspricht tbJagdbezirk.BBezeichnung in condition
  jb_zuordnung character varying(10),
  status boolean,
  verzicht boolean NOT NULL DEFAULT false
)
WITH (
  OIDS=TRUE
);
ALTER TABLE jagdbezirke
  ADD CONSTRAINT jagdbezirke_pkey PRIMARY KEY(oid);
COMMENT ON COLUMN jagdbezirke.concode IS 'entspricht tbJagdbezirk.BCode in condition';
COMMENT ON COLUMN jagdbezirke.conname IS 'entspricht tbJagdbezirk.BBezeichnung in condition';

SELECT AddGeometryColumn('jagdkataster', 'jagdbezirke','the_geom',2398,'GEOMETRY', 2);

CREATE INDEX jagdbezirke_the_geom_gist
  ON jagdbezirke
  USING gist
  (the_geom );




-- Tabelle Jagdbezirkarten
-- #######################

CREATE TABLE jagdbezirkart
(
  art character varying(10),
  bezeichnung character varying(30)
)
WITH (
  OIDS=TRUE
);

INSERT INTO jagdbezirkart VALUES ('ejb', 'EJB im Verfahren');
INSERT INTO jagdbezirkart VALUES ('ajb', 'Abgerundeter EJB');
INSERT INTO jagdbezirkart VALUES ('gjb', 'Gemeinschaftlicher Jagdbezirk');
INSERT INTO jagdbezirkart VALUES ('tjb', 'Teiljagdbezirk');
INSERT INTO jagdbezirkart VALUES ('sf', 'Sonderfläche');
INSERT INTO jagdbezirkart VALUES ('agf', 'Angliederungsfläche');
INSERT INTO jagdbezirkart VALUES ('atf', 'Abtrennungsfläche');
INSERT INTO jagdbezirkart VALUES ('slf', 'Anpachtfläche');
INSERT INTO jagdbezirkart VALUES ('jbe', 'Enklave');
INSERT INTO jagdbezirkart VALUES ('jbf', 'Jagdbezirksfreie Fläche');




-- Tabelle Jagdpaechter
-- #######################

CREATE TABLE jagdpaechter
(
  id integer NOT NULL, -- entspricht Waffenbesitzer.Code in condition
  anrede character varying(10),
  nachname character varying(50),
  vorname character varying(50),
  geburtstag character varying(20),
  geburtsort character varying(50),
  strasse character varying(50),
  plz character varying(5),
  ort character varying(50),
  telefon character varying(50)
)
WITH (
  OIDS=TRUE
);
ALTER TABLE jagdpaechter
  ADD CONSTRAINT jagdpaechter_pkey PRIMARY KEY(id);
COMMENT ON COLUMN jagdpaechter.id IS 'entspricht Waffenbesitzer.Code in condition';



-- Tabelle Zuordnung der Paechter zur den Jagdbezirken
-- #######################

CREATE TABLE jagdpaechter2bezirke
(
  bezirkid integer NOT NULL,
  paechterid integer NOT NULL
)
WITH (
  OIDS=TRUE
);
ALTER TABLE jagdpaechter2bezirke
  ADD CONSTRAINT jagdpaechter2bezirke_pkey PRIMARY KEY(oid);


-- View zu den Jagdbezirken
-- #######################

CREATE OR REPLACE VIEW jagdbezirk_paechter AS 
 SELECT jb.oid, jb.id, jb.name, jb.art, jb.flaeche, jpb.bezirkid, jb.concode, jb.jb_zuordnung, jb.status, jb.the_geom
   FROM jagdbezirke jb
   LEFT JOIN jagdpaechter2bezirke jpb ON jb.concode = cast(jpb.bezirkid as text)
  GROUP BY jb.oid, jb.id, jb.name, jb.art, jb.flaeche, jpb.bezirkid, jb.concode, jb.jb_zuordnung, jb.status, jb.the_geom;

COMMIT;

