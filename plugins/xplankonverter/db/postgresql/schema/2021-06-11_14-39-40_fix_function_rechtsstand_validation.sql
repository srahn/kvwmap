BEGIN;
-- Nach Email Projekt 2021-06-11
/*Für den Rechtsstand  3000 gilt:
Planstatus ist 3000 und Pflicht ist Datum des Satzungsbeschlusses.
Für Rechtskraft gilt: Rechtsstand 4000 oder höher und Planstatus 4000 und höher --> Pflichtattribut "Datum des Inkrafttretens".
Ich bitte um kurzfristige Korrektur.*/
CREATE OR REPLACE FUNCTION xplankonverter.check_rechtsstand_and_datum()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
DECLARE
  sql text;
  msg text = '';
BEGIN
	IF (new.rechtsstand = '2400'::xplan_gml.bp_rechtsstand AND (new.auslegungsstartdatum IS NULL OR new.auslegungsenddatum IS NULL)) THEN
			msg = 'Wenn der Rechtsstand Öffentliche Auslegung ausgewählt ist, muss auch ein Start und ein Enddatum für die Auslegung angegeben werden.';
		END IF;
	IF (new.rechtsstand = '3000'::xplan_gml.bp_rechtsstand AND (new.status).id = '3000' AND new.satzungsbeschlussdatum IS NULL) THEN
		msg = 'Wenn der Rechtsstand Satzung ausgewählt ist, muss auch ein Satzungsbeschlussdatum angegeben werden.';
	END IF;
	IF (new.rechtsstand >= '4000'::xplan_gml.bp_rechtsstand AND (new.status).id >= '4000' AND new.inkrafttretensdatum IS NULL) THEN
		msg = 'Wenn der Rechtsstand in Kraft getreten oder höher und der Status Rechtskraft oder höher ausgewählt ist, muss auch ein Datum des Inkrafttretens angegeben werden.';
	END IF;
	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

ALTER FUNCTION xplankonverter.check_rechtsstand_and_datum()
    OWNER TO kvwmap;
COMMIT;