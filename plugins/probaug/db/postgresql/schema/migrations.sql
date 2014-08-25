
--- migration 2014-08-03 00:00:00

-- Version 2.0.0

BEGIN;

CREATE SCHEMA probaug;

SET search_path = probaug, public;

--# Tabelle zur Speicherung der Gemarkungsnummer-zu-Gemarkungsschlüssel-Beziehung für die Bauauskunft

CREATE TABLE bau_gemarkungen
(
  nummer int8 NOT NULL,
  schluessel int8 NOT NULL
) 
WITH OIDS;


--###########################
--# Tabelle für Bauaktendaten
--# 2006-01-26 pk
CREATE TABLE bau_akten
(
  feld1 integer,
  feld2 integer,
  feld3 integer,
  feld4 text,
  feld5 text,
  feld6 text,
  feld7 text,
  feld8 text,
  feld9 text,
  feld10 text,
  feld11 text,
  feld12 text,
  feld13 text,
  feld14 text,
  feld15 text,
  feld16 text,
  feld17 text,
  feld18 text,
  feld19 text,
  feld20 text,
  feld21 text,
  feld22 text,
  feld23 integer,
  feld24 text,
  feld25 text,
  feld26 text
)
WITH (
  OIDS=TRUE
);

--# Hinzufügen der Tabellen bau_verfahrensart und bau_vorhaben, in denen die zur Auswahl stehenden Werte für das Vorhaben und die Verfahrensart bei der Bauauskunftssuche gespeichert sind
CREATE TABLE bau_verfahrensart
(
  verfahrensart text,
  id serial NOT NULL
) 
WITH OIDS;

CREATE TABLE bau_vorhaben
(
  vorhaben text,
  id serial NOT NULL
) 
WITH OIDS;

COMMIT;