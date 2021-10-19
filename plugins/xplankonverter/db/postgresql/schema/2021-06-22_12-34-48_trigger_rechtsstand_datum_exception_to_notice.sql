BEGIN;
--delete triggerfuntion rechtsstand and datum, as now checked through check_bp_rechtsstand_datum_reference()
DROP FUNCTION IF EXISTS xplankonverter.check_rechtsstand_and_datum();

-- Change function from Exception to notice
CREATE OR REPLACE FUNCTION xplankonverter.check_bp_rechtsstand_datum_reference()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
DECLARE
	sql text;
	msg text = '';
BEGIN
-- Check if rechtsstand and status fit
	IF (new.rechtsstand = '1000'::xplan_gml.bp_rechtsstand AND (new.status).id != '1000') THEN
		msg = msg || E'\nWenn der Rechtsstand Aufstellungsbeschluss (1000) ausgewählt ist, muss auch der Status Aufstellung (1000) angegeben werden.';
	ELSIF (new.rechtsstand = '2000'::xplan_gml.bp_rechtsstand AND (new.status).id != '2000') THEN
		msg = msg || E'\nWenn der Rechtsstand Entwurf (2000) ausgewählt ist, muss auch der Status Entwurf (2000) angegeben werden.';
	ELSIF (new.rechtsstand = '2100'::xplan_gml.bp_rechtsstand AND (new.status).id != '2000') THEN
		msg = msg || E'\nWenn der Rechtsstand Frühzeitige Behördenbeteiligung hat stattgefunden (2100) ausgewählt ist, muss auch der Status Entwurf (2000) angegeben werden.';
	ELSIF (new.rechtsstand = '2200'::xplan_gml.bp_rechtsstand AND (new.status).id != '2000') THEN
		msg = msg || E'\nWenn der Rechtsstand Frühzeitige Beteiligung der Öffentlichkeit hat stattgefunden (2200) ausgewählt ist, muss auch der Status Entwurf (2000) angegeben werden.';
	ELSIF (new.rechtsstand = '2300'::xplan_gml.bp_rechtsstand AND (new.status).id != '2000') THEN
		msg = msg || E'\nWenn der Rechtsstand Beteiligung der Behörden hat stattgefunden (2300) ausgewählt ist, muss auch der Status Entwurf (2000) angegeben werden.';
	ELSIF (new.rechtsstand = '2400'::xplan_gml.bp_rechtsstand AND (new.status).id != '2000') THEN
		msg = msg || E'\nWenn der Rechtsstand Plan hat öffentlich ausgelegen (2400) ausgewählt ist, muss auch der Status Entwurf (2000) angegeben werden.';
	ELSIF (new.rechtsstand = '3000'::xplan_gml.bp_rechtsstand AND (new.status).id != '3000') THEN
		msg = msg || E'\nWenn der Rechtsstand Satzung ausgewählt (3000) ist, muss auch der Status Satzung angegeben werden.';
	ELSIF (new.rechtsstand = '4000'::xplan_gml.bp_rechtsstand AND (new.status).id != '4000') THEN
		msg = msg || E'\nWenn der Rechtsstand in Kraft getreten (4000) ausgewählt ist, muss auch der Status Rechtskraft (4000) angegeben werden.';
	ELSIF (new.rechtsstand = '4500'::xplan_gml.bp_rechtsstand AND (new.status).id != '4000') THEN
		msg = msg || E'\nWenn der Rechtsstand Plan ist teilweise untergegangen ausgewählt (4500) ist, muss auch der Status Rechtskraft (4000) angegeben werden.';
	ELSIF (new.rechtsstand = '5000'::xplan_gml.bp_rechtsstand AND (new.status).id != '5000') THEN
		msg = msg || E'\nWenn der Rechtsstand Plan wurde aufgehoben oder für nichtig erklärt (5000) ausgewählt ist, muss auch der Status Unwirksamkeit,Aufhebung (5000) angegeben werden.';
	END IF;		

-- check if rechtsstand  and date fit
	IF (new.rechtsstand = '1000'::xplan_gml.bp_rechtsstand AND new.aufstellungsbeschlussdatum IS NULL) THEN
		msg = msg || E'\nWenn der Rechtsstand Aufstellungsbeschluss (1000) ausgewählt ist, muss auch ein Aufstellungsbeschlussdatum angegeben werden.';
	ELSIF (new.rechtsstand = '2000'::xplan_gml.bp_rechtsstand AND new.ausfertigungsdatum IS NULL) THEN
		msg = msg || E'\nWenn der Rechtsstand Entwurf (2000) ausgewählt ist, muss auch ein Datum der Ausfertigung angegeben werden.';
	ELSIF (new.rechtsstand = '2100'::xplan_gml.bp_rechtsstand AND (new.traegerbeteiligungsstartdatum IS NULL OR new.traegerbeteiligungsenddatum IS NULL)) THEN
		msg = msg || E'\nWenn der Rechtsstand Frühzeitige Behördenbeteiligung hat stattgefunden (2100) ausgewählt ist, muss auch ein TÖB-Auslegung-Startdatum und -Enddatum angegeben werden.';
	ELSIF (new.rechtsstand = '2200'::xplan_gml.bp_rechtsstand AND (new.auslegungsstartdatum IS NULL OR new.auslegungsenddatum IS NULL)) THEN
		msg = msg || E'\nWenn der Rechtsstand Frühzeitige Beteiligung der Öffentlichkeit hat stattgefunden (2200) ausgewählt ist, muss auch ein Start und ein Enddatum für die Auslegung angegeben werden.';
	ELSIF (new.rechtsstand = '2300'::xplan_gml.bp_rechtsstand AND (new.traegerbeteiligungsstartdatum IS NULL OR new.traegerbeteiligungsenddatum IS NULL)) THEN
		msg = msg || E'\nWenn der Rechtsstand Beteiligung der Behörden hat stattgefunden (2300) ausgewählt ist, muss auch ein TÖB-Auslegung-Startdatum und -Enddatum angegeben werden.';
	ELSIF (new.rechtsstand = '2400'::xplan_gml.bp_rechtsstand AND (new.auslegungsstartdatum IS NULL OR new.auslegungsenddatum IS NULL)) THEN
			msg = msg || E'\nWenn der Rechtsstand Plan hat öffentlich ausgelegen (2400) ausgewählt ist, muss auch ein Start und ein Enddatum für die Auslegung angegeben werden.';
	ELSIF (new.rechtsstand = '3000'::xplan_gml.bp_rechtsstand AND new.satzungsbeschlussdatum IS NULL) THEN
		msg = msg || E'\nWenn der Rechtsstand Satzung (3000) ausgewählt ist, muss auch ein Satzungsbeschlussdatum angegeben werden.';
	ELSIF (new.rechtsstand = '4000'::xplan_gml.bp_rechtsstand AND new.inkrafttretensdatum IS NULL) THEN
		msg = msg || E'\nWenn der Rechtsstand in Kraft getreten (4000) ausgewählt ist, muss auch ein Datum des Inkrafttretens angegeben werden.';
	ELSIF (new.rechtsstand = '5000'::xplan_gml.bp_rechtsstand AND new.untergangsdatum IS NULL) THEN
		msg = msg || E'\nWenn der Rechtsstand Plan wurde aufgehoben oder für nichtig erklärt (5000) ausgewählt ist, muss auch ein Untergangsdatum angegeben werden.';
	END IF;

-- check plandokumente are available
	IF (new.rechtsstand = '1000'::xplan_gml.bp_rechtsstand AND to_json(new.externereferenz)::text NOT LIKE '%"typ":"5000"%') THEN
		msg = msg || E'\nWenn der Rechtsstand Aufstellungsbeschluss (1000) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Bekanntmachung (5000) vorhanden sein.';
	ELSIF (new.rechtsstand = '2000'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' OR to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand Entwurf (2000) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010) und ein Dokument mit dem Typ Satzung (1060) vorhanden sein.';
	ELSIF (new.rechtsstand = '2100'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' OR to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand Frühzeitige Behördenbeteiligung hat stattgefunden (2100) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010) und ein Dokument mit dem Typ Satzung (1060) vorhanden sein.';
	ELSIF (new.rechtsstand = '2200'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' OR to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%' or to_json(new.externereferenz)::text NOT LIKE '%"typ":"2800"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand Frühzeitige Beteiligung der Öffentlichkeit hat stattgefunden (2200) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010), ein Dokument mit dem Typ Satzung (1060) und ein Dokument mit dem Typ Beschluss (2800) vorhanden sein.';
	ELSIF (new.rechtsstand = '2300'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' OR to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand Beteiligung der Behörden hat stattgefunden (2300) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010) und ein Dokument mit dem Typ Satzung (1060) vorhanden sein.';
	ELSIF (new.rechtsstand = '2400'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' OR to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%' OR to_json(new.externereferenz)::text NOT LIKE '%"typ":"2800"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand Plan hat öffentlich ausgelegen (2400) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010), ein Dokument mit dem Typ Satzung (1060) und ein Dokument mit dem Typ Beschluss (2800) vorhanden sein.';
	ELSIF (new.rechtsstand = '3000'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' or to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand Satzung (3000) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010) und ein Dokument mit dem Typ Satzung (1060) vorhanden sein.';
	ELSIF (new.rechtsstand = '4000'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' or to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand in Kraft getreten (4000) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010) und ein Dokument mit dem Typ Satzung (1060) vorhanden sein.';
	ELSIF (new.rechtsstand = '4500'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' or to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand Plan ist teilweise untergegangen (4500) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010) und ein Dokument mit dem Typ Satzung (1060) vorhanden sein.';
	ELSIF (new.rechtsstand = '5000'::xplan_gml.bp_rechtsstand AND (to_json(new.externereferenz)::text NOT LIKE '%"typ":"1010"%' or to_json(new.externereferenz)::text NOT LIKE '%"typ":"1060"%')) THEN
		msg = msg || E'\nWenn der Rechtsstand Plan wurde aufgehoben oder für nichtig erklärt (5000) ausgewählt ist, muss mindestens ein Dokument mit dem Typ Begründung (1010) und ein Dokument mit dem Typ Satzung (1060) vorhanden sein.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE NOTICE 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

ALTER FUNCTION xplankonverter.check_bp_rechtsstand_datum_reference()
    OWNER TO kvwmap;

CREATE OR REPLACE FUNCTION xplankonverter.check_fp_rechtsstand_datum_reference()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
DECLARE
	sql text;
	msg text = '';
BEGIN
-- check if rechtsstand  and date fit
	IF (new.rechtsstand = '2400'::xplan_gml.fp_rechtsstand AND (new.auslegungsstartdatum IS NULL OR new.auslegungsenddatum IS NULL)) THEN
		msg = msg || E'\nWenn der Rechtsstand Öffentliche Auslegung (2400) ausgewählt ist, muss auch ein Start und ein Enddatum für die Auslegung angegeben werden.';
	ELSIF (new.rechtsstand >= '3000'::xplan_gml.fp_rechtsstand AND new.wirksamkeitsdatum IS NULL) THEN
		msg = msg || E'\nWenn der Rechtsstand 3000 oder größer ist, muss auch ein Datum der Wirksamkeit des Planes angegeben werden.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE NOTICE 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

ALTER FUNCTION xplankonverter.check_fp_rechtsstand_datum_reference()
    OWNER TO kvwmap;

COMMIT;