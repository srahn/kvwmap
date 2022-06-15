BEGIN;

CREATE TABLE lenris.logs
(
	id serial NOT NULL,
	client_id integer NOT NULL,
  time timestamp without time zone,
  message text,
  CONSTRAINT pk_logs PRIMARY KEY (id)
);

CREATE TABLE lenris.errors
(
	id serial NOT NULL,
	client_id integer NOT NULL,
  time timestamp without time zone,
  message text,
  CONSTRAINT pk_errors PRIMARY KEY (id)
);

COMMIT;
