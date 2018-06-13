BEGIN;

-- Function: xplankonverter.update_konvertierung_state()

-- DROP FUNCTION xplankonverter.update_konvertierung_state();

CREATE OR REPLACE FUNCTION xplankonverter.update_konvertierung_state()
  RETURNS trigger AS
$BODY$
DECLARE
  _konvertierung_id integer;
  plan_assigned BOOLEAN;
  old_state character varying;
  new_state Character varying;
BEGIN
  IF (TG_OP = 'INSERT') THEN
    _konvertierung_id := NEW.konvertierung_id;
    RAISE NOTICE 'update_konvertierung_state nach insert';
  ELSIF (TG_OP = 'DELETE') THEN
    _konvertierung_id := OLD.konvertierung_id;
    RAISE NOTICE 'update_konvertierung_state nach delete';
  END IF;
  RAISE NOTICE 'for konvertierung_id: %', _konvertierung_id;

  SELECT
    status
  FROM
    xplankonverter.konvertierungen
  WHERE
    id = _konvertierung_id
  INTO
    old_state;

  SELECT distinct
    case WHEN p.gml_id IS NOT NULL THEN true ELSE false END AS plan_assigned
  FROM
    xplankonverter.konvertierungen k LEFT JOIN
    xplan_gml.rp_plan p ON k.id = p.konvertierung_id
  WHERE
    k.id = _konvertierung_id
  INTO
    plan_assigned;

  RAISE NOTICE 'Mindestens ein Plan oder Regel ist zugeordnet: %', plan_or_regel_assigned;
  RAISE NOTICE 'Alter Konvertierungsstatus: %', old_state;
  new_state := old_state;
  IF (plan_or_regel_assigned) THEN
    IF (old_state = 'in Erstellung') THEN
      new_state := 'erstellt';
    END IF;
  ELSE
    new_state := 'in Erstellung';
  END IF;
  RAISE NOTICE 'Neuer Konvertierungsstatus: %', new_state;
  UPDATE
    xplankonverter.konvertierungen
  SET
    status = new_state::xplankonverter.enum_konvertierungsstatus
  WHERE
    id = _konvertierung_id;

RETURN NULL;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE TRIGGER update_konvertierung_state
  AFTER INSERT OR DELETE
  ON xplan_gml.xp_plan
  FOR EACH ROW
  EXECUTE PROCEDURE xplankonverter.update_konvertierung_state();
	
DROP TRIGGER update_konvertierung_state ON xplankonverter.regeln;

COMMIT;
