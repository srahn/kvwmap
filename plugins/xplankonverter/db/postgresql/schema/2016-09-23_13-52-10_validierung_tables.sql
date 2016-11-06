BEGIN;

CREATE TYPE xplankonverter.validierungsstatus  AS ENUM (
	'Erfolg',
	'Warung',
	'Fehler');

CREATE TABLE xplankonverter.validierungen
(
  id serial,
  name character varying NOT NULL,
  beschreibung text,
  functionsname character varying,
  msg_success character varying,
  msg_warning character varying,
  msg_error character varying,
  msg_correcture text,
  CONSTRAINT validierung_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE xplankonverter.validierungsergebnisse
(
  id serial,
  konvertierung_id integer,
  validierung_id integer,
  status xplankonverter.validierungsstatus,
  msg text,
  created_at timestamp without time zone,
  user_id integer,
  CONSTRAINT validierungsergebnisse_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

COMMIT;