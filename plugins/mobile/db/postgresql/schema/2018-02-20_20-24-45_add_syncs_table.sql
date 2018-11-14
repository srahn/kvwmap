BEGIN;

CREATE TABLE syncs (
  id serial NOT NULL,
  client_id character varying,
  username character varying,
  schema_name character varying,
  table_name character varying,
  client_time timestamp without time zone,
  pull_from_version integer,
  pull_to_version integer,
  push_from_version integer,
  push_to_version integer,
  CONSTRAINT syncs_pkey PRIMARY KEY (id)
);

COMMIT;
