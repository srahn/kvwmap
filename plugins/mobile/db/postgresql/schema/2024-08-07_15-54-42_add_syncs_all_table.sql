BEGIN;
  CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

  CREATE TABLE syncs_all (
    id serial NOT NULL PRIMARY KEY,
    client_id character varying,
    username character varying,
    client_time timestamp without time zone,
    pull_from_version integer,
    pull_to_version integer
  );

  CREATE TABLE deltas_all (
    id serial NOT NULL PRIMARY KEY,
    client_id character varying,
    sql text,
    schema_name character varying,
    table_name character varying,
    version integer
  );
COMMIT;
