BEGIN;

ALTER TABLE nachweisverwaltung.lenris_worker RENAME TO n_nachweisaenderungen;

CREATE OR REPLACE FUNCTION nachweisverwaltung.lenris_log_action()
  RETURNS trigger AS
$BODY$
  BEGIN
	IF (TG_OP = 'INSERT') THEN
        INSERT INTO nachweisverwaltung.n_nachweisaenderungen(
                log_time,
                id_nachweis,
                db_action)
            VALUES(
                now(),
                NEW.id,
                'INSERT');
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
	        INSERT INTO nachweisverwaltung.n_nachweisaenderungen(
                log_time,
                id_nachweis,
                db_action)
            VALUES(
                now(),
                NEW.id,
                'UPDATE');
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
	        INSERT INTO nachweisverwaltung.n_nachweisaenderungen(
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

COMMIT;
