BEGIN;

CREATE SCHEMA wasserrecht;

CREATE TABLE wasserrecht.anlagen(
	id serial,
	name varchar(255)
) WITH OIDS;

COMMIT;