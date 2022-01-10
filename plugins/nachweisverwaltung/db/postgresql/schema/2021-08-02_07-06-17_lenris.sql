BEGIN;

SET search_path = nachweisverwaltung;

CREATE TABLE lenris_worker
(
  id serial NOT NULL,
  log_time timestamp without time zone NOT NULL,
  db_action character(10),
  id_nachweis integer NOT NULL DEFAULT 0,
  CONSTRAINT lenris_worker_pkey PRIMARY KEY (id)
);

CREATE OR REPLACE FUNCTION nachweisverwaltung.lenris_log_action()
  RETURNS trigger AS
$BODY$
  BEGIN
	IF (TG_OP = 'INSERT') THEN
        INSERT INTO nachweisverwaltung.lenris_worker(
                log_time,
                id_nachweis,
                db_action)
            VALUES(
                now(),
                NEW.id,
                'INSERT');
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
	        INSERT INTO nachweisverwaltung.lenris_worker(
                log_time,
                id_nachweis,
                db_action)
            VALUES(
                now(),
                NEW.id,
                'UPDATE');
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
	        INSERT INTO nachweisverwaltung.lenris_worker(
                log_time,
                id_nachweis,
                db_action)
            VALUES(
                now(),
                OLD.id,
                'DELETE');
        RETURN OLD;
    END IF;
    RETURN null;
  END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
	
CREATE TRIGGER lenris
  AFTER INSERT OR UPDATE OR DELETE
  ON nachweisverwaltung.n_nachweise
  FOR EACH ROW
  EXECUTE PROCEDURE lenris_log_action();	

COMMIT;
