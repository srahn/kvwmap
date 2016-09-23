BEGIN;

CREATE TABLE xplankonverter.validierungen
(
  id integer NOT NULL DEFAULT nextval('xplankonverter.validierung_id_seq'::regclass),
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
  id serial NOT NULL,
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