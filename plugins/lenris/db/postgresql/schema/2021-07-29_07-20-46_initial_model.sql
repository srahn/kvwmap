BEGIN;

CREATE SCHEMA lenris;

SET search_path = lenris, public;


CREATE TABLE clients
(
  client_id integer NOT NULL,
  bezeichnung character varying NOT NULL,
	url character varying,
	nachweis_primary_attribute character varying,
	nachweis_secondary_attribute character varying,
	nachweis_unique_attributes character varying[],
	last_sync date,
	sync_time time,
	status integer NOT NULL DEFAULT 0,
	doc_download boolean,
	kurzform character varying(6),
  CONSTRAINT pk_clients_id PRIMARY KEY (client_id)
);


INSERT INTO clients VALUES 
	(1, 'Landkreis Rostock',											NULL,	'stammnr',		NULL,						'{"gemarkung", "flur", "stammnr", "art", "blattnr"}'),
	(2, 'Landkreis Mecklenburgische Seenplatte',	NULL,	'rissnummer',	NULL,						'{"gemarkung", "flur", "rissnummer", "art", "blattnr"}'),
	(3, 'Landkreis Vorpommern-Rügen',							NULL,	'rissnummer', 'fortfuehrung', '{"gemarkung", "flur", "rissnummer", "fortfuehrung", "blattnr", "art"}'),
	(4, 'Landkreis Vorpommern-Greifswald',				NULL,	'rissnummer',	NULL,						'{"gemarkung", "flur", "rissnummer", "art", "blattnr"}'),
	(5, 'Landkreis Ludwigslust-Parchim',					NULL,	'rissnummer',	NULL,						'{"gemarkung", "flur", "rissnummer", "blattnr"}'),
	(6, 'Landkreis Nordwestmecklenburg',					NULL,	NULL,					NULL,						NULL),
	(7, 'Hansestadt Rostock',											NULL,	NULL,					NULL,						NULL);


CREATE TABLE client_nachweise
(
  nachweis_id integer NOT NULL,
  client_nachweis_id integer NOT NULL,
	client_id integer NOT NULL,
	document_last_modified timestamp without time zone,
  CONSTRAINT pk_client_nachweise PRIMARY KEY (nachweis_id)
);


CREATE TABLE zu_holende_dokumente
(
  client_id integer NOT NULL,
	client_nachweis_id integer NOT NULL,
	dokument character varying,
  CONSTRAINT pk_zu_holende_dokumente PRIMARY KEY (client_id, client_nachweis_id)
	-- order ?
);


CREATE TABLE client_dokumentarten
(
  dokumentart_id integer NOT NULL,
  client_dokumentart_id integer NOT NULL,
	client_id integer NOT NULL
);


CREATE TABLE client_vermessungsstellen
(
  vermstelle_id integer NOT NULL,
  client_vermstelle_id integer NOT NULL,
	client_id integer NOT NULL
);


COMMIT;
