--######################################
-- Tabellen für Jagdkataster-Fachschale



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
  status boolean
)
WITH (
  OIDS=TRUE
);
ALTER TABLE jagdbezirke
  ADD CONSTRAINT jagdbezirke_pkey PRIMARY KEY(oid);
COMMENT ON COLUMN jagdbezirke.concode IS 'entspricht tbJagdbezirk.BCode in condition';
COMMENT ON COLUMN jagdbezirke.conname IS 'entspricht tbJagdbezirk.BBezeichnung in condition';

SELECT AddGeometryColumn('public', 'jagdbezirke','the_geom',2398,'MULTIPOLYGON', 2);
ALTER TABLE jagdbezirke DROP CONSTRAINT enforce_geotype_the_geom;
ALTER TABLE jagdbezirke ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);

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
  paechterid integer NOT NULL,
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





########################################
# SQL für die Erzeugung von EJB-Verdachtsflächen


# Tabellendefinition
####################

CREATE TABLE ejb_verdachtsflaechen
(
  eigentuemer character varying,
  flaeche integer
)
WITH (
  OIDS=TRUE
);
SELECT AddGeometryColumn('public', 'ejb_verdachtsflaechen','the_geom',2398,'POLYGON', 2);
ALTER TABLE ejb_verdachtsflaechen DROP CONSTRAINT enforce_geotype_the_geom;
ALTER TABLE ejb_verdachtsflaechen ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);

CREATE INDEX ixejbverd_the_geom_gist
  ON ejb_verdachtsflaechen
  USING gist
  (the_geom);



# SQL-Abfrage 
####################

DROP INDEX ixejbverd_the_geom_gist;

TRUNCATE ejb_verdachtsflaechen;

INSERT INTO ejb_verdachtsflaechen 
SELECT 
 eigentuemer, round(area(st_buffer(the_geom, -10))) as flaeche, st_buffer(the_geom, -10) as the_geom 
FROM (
  select 
   (st_dump(st_memunion(st_buffer(o.the_geom,10)))).geom as the_geom, 
   array_to_string(array(
     select rtrim(name1,',') 
     from alb_g_eigentuemer ee, alb_g_namen nn 
     where ee.lfd_nr_name=nn.lfd_nr_name 
     and ee.bezirk=e.bezirk 
     and ee.blatt=e.blatt order by rtrim(name1,',')),' || '
   ) as eigentuemer 
  from alb_g_namen n, alb_g_eigentuemer e, alb_g_buchungen b, alknflst f, alkobj_e_fla o 
  where e.lfd_nr_name=n.lfd_nr_name 
  and e.bezirk=b.bezirk 
  and e.blatt=b.blatt 
  and b.flurstkennz=f.flurstkennz 
  and f.objnr=o.objnr 
  group by e.bezirk, e.blatt
 ) as foo 
WHERE area(st_buffer(the_geom, -10))>750000

VACUUM ANALYZE ejb_verdachtsflaechen;

CREATE INDEX ixejbverd_the_geom_gist
ON ejb_verdachtsflaechen
USING gist
(the_geom );
