BEGIN;
  ALTER TABLE xplan_gml.xp_plan ADD fassungsbezeichnung varchar DEFAULT '' NOT NULL;

  CREATE OR REPLACE FUNCTION xplankonverter.check_fassungsbezeichnung()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
  DECLARE
    msg text = '';
  BEGIN
    IF (NEW.fassungsbezeichnung IS NULL OR NEW.fassungsbezeichnung = '') THEN
      msg = E'\nEs wurde keine Fassungsbezeichnung wie Ursprungsplan oder 1. Änderung angegeben!';
      RAISE EXCEPTION '%', msg;
    ELSE
      IF (NOT NEW.fassungsbezeichnung ~* '\m(Ursprungsplan|Änderung|Ergänzung|Neuaufstellung)\M') THEN 
        msg = E'\nEs muss einer der Begriffe Ursprungsplan, Änderung, Ergänzung oder Neuaufstellung in der Fassungsbezeichnung vorkommen!';
        RAISE EXCEPTION '%', msg;
      END IF;
    END IF;
    RETURN NEW;
  END;
  $function$;

  CREATE TRIGGER check_fassungsbezeichnung BEFORE
  INSERT OR UPDATE ON xplan_gml.bp_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_fassungsbezeichnung();

  CREATE TRIGGER check_fassungsbezeichnung BEFORE
  INSERT OR UPDATE ON xplan_gml.fp_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_fassungsbezeichnung();

  CREATE TRIGGER check_fassungsbezeichnung BEFORE
  INSERT OR UPDATE ON xplan_gml.so_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_fassungsbezeichnung();

CREATE OR REPLACE FUNCTION xplankonverter.check_planname_und_nummer()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
  msg text = '';
BEGIN
  IF (NEW.name ~* '\m(Änderung|Ergänzung|Neuaufstellung)\M') THEN
    msg = E'\nIm Plannamen soll die Bezeichnung der Fassung nicht auftauchen. Bitte überprüfen Sie gegebenenfalls Ihre Eingabe. Für die Bezeichnung der Fassung ist allein das Feld Planfassung vorgesehen.\nDer Planname darf nicht die Wörter Ursprungsplan, Änderung, Ergänzung oder Neuaufstellung beinhalten!';
    RAISE EXCEPTION '%', msg;
  END IF;

  IF (NEW.nummer ~* '\m(Änderung|Ergänzung|Neuaufstellung)\M') THEN
    msg = E'\nIm Feld Plannummer soll die Bezeichnung der Fassung nicht auftauchen. Bitte überprüfen Sie gegebenenfalls Ihre Eingabe. Für die Bezeichnung der Fassung ist allein das Feld Planfassung vorgesehen.\nDie Plannummer darf nicht die Wörter Ursprungsplan, Änderung, Ergänzung oder Neuaufstellung beinhalten!';
    RAISE EXCEPTION '%', msg;
  END IF;

  IF (NEW.name ILIKE '%' || (NEW.gemeinde[1]).gemeindename || '%') THEN
    msg = E'\nSie haben möglicherweise den Namen der Gemeinde im Feld Planname platziert. Bitte überprüfen Sie Ihre Eingabe. Gemeindename und Ortsteilname werden in den Attributen "Name der Gemeinde" und "Name des Ortsteils" erfasst.';
    RAISE EXCEPTION '%', msg;
  END IF;

  IF (NEW.name ILIKE '%' || (NEW.gemeinde[1]).ortsteilname || '%') THEN
    msg = E'\nSie haben möglicherweise den Namen des Ortsteils im Feld Planname platziert. Bitte überprüfen Sie Ihre Eingabe. Gemeindename und Ortsteilname werden in den Attributen "Name der Gemeinde" und "Name des Ortsteils" erfasst.';
    RAISE EXCEPTION '%', msg;
  END IF;

  IF (NEW.nummer ILIKE '%' || (NEW.gemeinde[1]).gemeindename || '%') THEN
    msg = E'\nSie haben möglicherweise den Namen der Gemeinde im Feld Plannummer platziert. Bitte überprüfen Sie Ihre Eingabe. Gemeindename und Ortsteilname werden in den Attributen "Name der Gemeinde" und "Name des Ortsteils" erfasst.';
    RAISE EXCEPTION '%', msg;
  END IF;

  IF (NEW.nummer ILIKE '%' || (NEW.gemeinde[1]).ortsteilname || '%') THEN
    msg = E'\nSie haben möglicherweise den Namen des Ortsteils im Feld Plannummer platziert. Bitte überprüfen Sie Ihre Eingabe. Gemeindename und Ortsteilname werden in den Attributen "Name der Gemeinde" und "Name des Ortsteils" erfasst.';
    RAISE EXCEPTION '%', msg;
  END IF;

  RETURN NEW;
END;
$function$;

  CREATE TRIGGER check_planname_und_nummer BEFORE
  INSERT OR UPDATE ON xplan_gml.bp_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_planname_und_nummer();

  CREATE TRIGGER check_planname_und_nummer BEFORE
  INSERT OR UPDATE ON xplan_gml.fp_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_planname_und_nummer();

  CREATE TRIGGER check_planname_und_nummer BEFORE
  INSERT OR UPDATE ON xplan_gml.so_plan FOR EACH ROW EXECUTE FUNCTION xplankonverter.check_planname_und_nummer();

COMMIT;