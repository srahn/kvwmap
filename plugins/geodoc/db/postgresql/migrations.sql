--- migration 2014-08-03 00:00:00

BEGIN;

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;


CREATE SCHEMA geodoc;

SET search_path = geodoc, public;
SET default_tablespace = '';
SET default_with_oids = true;


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
	filelastmodified timestamp without time zone,
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
  bezeichnung varchar(255) NOT NULL,
  kurzbezeichnung varchar(50),
  ueberbegriff varchar(255),
  CONSTRAINT gaz_begriffe_pkey PRIMARY KEY (id)
) 
WITH OIDS;
SELECT AddGeometryColumn('geodoc', 'gaz_begriffe', 'wgs_geom', 4326, 'POINT', 2);
CREATE INDEX gaz_begriffe_gist ON gaz_begriffe USING GIST (wgs_geom );

COMMIT;