BEGIN;

CREATE TABLE n_nachweisaenderungen
(
  id serial NOT NULL,
  log_time timestamp without time zone NOT NULL,
  db_action character(10),
  id_nachweis integer NOT NULL DEFAULT 0,
  CONSTRAINT n_nachweisaenderungen_pkey PRIMARY KEY (id)
);

CREATE OR REPLACE FUNCTION lenris_log_action()
  RETURNS trigger AS
$BODY$
  BEGIN
	IF (TG_OP = 'INSERT') THEN
        INSERT INTO n_nachweisaenderungen(
                log_time,
                id_nachweis,
                db_action)
            VALUES(
                now(),
                NEW.id,
                'INSERT');
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
	        INSERT INTO n_nachweisaenderungen(
                log_time,
                id_nachweis,
                db_action)
            VALUES(
                now(),
                NEW.id,
                'UPDATE');
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
	        INSERT INTO n_nachweisaenderungen(
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
  ON risse
  FOR EACH ROW
  EXECUTE PROCEDURE lenris_log_action();	

COMMIT;
