BEGIN;

CREATE OR REPLACE FUNCTION nachweisverwaltung.lenris_log_action()
  RETURNS trigger AS
$BODY$
DECLARE
	n integer;
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
	EXECUTE 'SELECT id_nachweis FROM nachweisverwaltung.n_nachweisaenderungen WHERE db_action = ''INSERT'' AND id_nachweis = ' || OLD.id;
	GET DIAGNOSTICS n = ROW_COUNT;
	IF n=1 THEN
		EXECUTE 'DELETE FROM nachweisverwaltung.n_nachweisaenderungen WHERE id_nachweis = ' || OLD.id;
	ELSE
	        INSERT INTO nachweisverwaltung.n_nachweisaenderungen(
                log_time,
                id_nachweis,
                db_action)
            VALUES(
                now(),
                OLD.id,
                'DELETE');
        END IF;
        RETURN OLD;
    END IF;
    RETURN null;
  END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
