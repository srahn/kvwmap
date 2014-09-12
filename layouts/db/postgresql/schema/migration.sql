
BEGIN;

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
	st_geomfromtext(
		replace(
			replace(
				replace(
					replace(
						replace(
							st_asText($1),'MULTIPOLYGON','MULTILINESTRING'
						),'POLYGON','MULTILINESTRING'
					), '(((', '(('
				), ')))', '))'
			), ')),((', '),('
		), st_srid($1)
	)$BODY$
  LANGUAGE 'sql' IMMUTABLE STRICT;
COMMENT ON FUNCTION linefrompoly(geometry) IS 'Liefert eine LINESTRING Gemetrie von einer MULTIPOLYGON oder POLYGON Geometrie zurück';



--# Tabelle für die Aliasnamen der Koordinatensysteme
CREATE TABLE spatial_ref_sys_alias
(
  srid integer NOT NULL,
  alias character varying(256),
	minx integer,
  miny integer,
  maxx integer,
  maxy integer,
  CONSTRAINT spatial_ref_sys_alias_pkey PRIMARY KEY (srid)
)
WITH OIDS;

CREATE INDEX spatial_ref_sys_srid_idx
  ON spatial_ref_sys
  USING btree
  (srid);

CREATE INDEX spatial_ref_sys_alias_srid_idx
  ON spatial_ref_sys_alias
  USING btree
  (srid);


-- Tabelle für andere Dokumentarten in der Nachweisverwaltung



-- Tabelle für Adressänderungen

CREATE TABLE shp_import_tables
(
  tabellenname character varying(255) NOT NULL
) 
WITH OIDS;

--# Tabellen für Dokumente
-- # Hinzufügen einer Tabelle u_polygon zur Speicherung von Polygonen

CREATE TABLE u_polygon
(
  id serial NOT NULL,
  CONSTRAINT u_polygon_pkey PRIMARY KEY (id)
) 
WITH OIDS;

SELECT AddGeometryColumn('public', 'u_polygon','the_geom',2398,'GEOMETRY', 2);
CREATE INDEX u_polygon_the_geom_gist ON u_polygon USING GIST (the_geom);


-- Tabelle für Metainformationen

CREATE TABLE tabelleninfo
(
  thema character varying(20),
  datum character varying(10)
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
SELECT AddGeometryColumn('public', 'q_notizen','the_geom',2398,'GEOMETRY', 2);
CREATE INDEX q_notizen_the_geom_gist ON q_notizen USING GIST (the_geom);
-- ALTER TABLE q_notizen DROP CONSTRAINT enforce_geotype_the_geom;
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

COMMIT;

