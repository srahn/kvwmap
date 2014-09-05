
--- migration 2014-08-03 00:00:00

-- Version 2.0.0

BEGIN;

CREATE SCHEMA anliegerbeitraege;

SET search_path = anliegerbeitraege, public;


--# Anlegen der Tabellen für die Fachscale Anliegerbeiträge

CREATE TABLE anliegerbeitraege_bereiche
(
  id serial NOT NULL,
  flaeche real,
  kommentar character varying(255),
  CONSTRAINT anliegerbeitraege_bereiche_pkey PRIMARY KEY (id)
) 
WITH OIDS;
SELECT AddGeometryColumn('anliegerbeitraege', 'anliegerbeitraege_bereiche','the_geom',2398,'GEOMETRY', 2);

CREATE TABLE anliegerbeitraege_strassen
(
  id serial NOT NULL,
  CONSTRAINT anliegerbeitraege_strassen_pkey PRIMARY KEY (id)
) 
WITH OIDS;
SELECT AddGeometryColumn('anliegerbeitraege', 'anliegerbeitraege_strassen','the_geom',2398,'GEOMETRY', 2);

COMMIT;