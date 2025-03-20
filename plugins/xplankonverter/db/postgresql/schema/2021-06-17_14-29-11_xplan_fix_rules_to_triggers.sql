BEGIN;
-- EXECUTE FUNCTION only works in Postgres 11 or higher, therefore use EXECUTE PROCEDURE for backwards compatibility

-- Rewrites rules to triggers as triggers won't work with returning and it's currently a mix of each
DROP RULE IF EXISTS check_text_reftext_on_update ON xplan_gml.xp_textabschnitt;
DROP RULE IF EXISTS check_text_reftext_on_insert ON xplan_gml.xp_textabschnitt;
DROP RULE IF EXISTS check_text_reftext_on_insert ON xplan_gml.xp_begruendungabschnitt;
DROP RULE IF EXISTS check_text_reftext_on_update ON xplan_gml.xp_begruendungabschnitt;

DROP RULE IF EXISTS check_inkrafttretensdatum_on_insert ON xplan_gml.bp_plan;
DROP RULE IF EXISTS check_auslegungsdatum_on_insert ON xplan_gml.bp_plan;
DROP RULE IF EXISTS check_bedeutung_on_insert ON xplan_gml.bp_bereich;
DROP RULE IF EXISTS check_bedeutung_on_update ON xplan_gml.bp_bereich;
DROP RULE IF EXISTS check_text_reftext_on_update ON xplan_gml.bp_textabschnitt;
DROP RULE IF EXISTS check_text_reftext_on_insert ON xplan_gml.bp_textabschnitt;

DROP RULE IF EXISTS check_wirksamkeitsdatum_on_update ON xplan_gml.fp_plan;
DROP RULE IF EXISTS check_auslegungsdatum_on_insert ON xplan_gml.fp_plan;
DROP RULE IF EXISTS check_auslegungsdatum_on_update ON xplan_gml.fp_plan;
DROP RULE IF EXISTS check_wirksamkeitsdatum_on_insert ON xplan_gml.fp_plan;
DROP RULE IF EXISTS check_bedeutung_on_insert ON xplan_gml.fp_bereich;
DROP RULE IF EXISTS check_bedeutung_on_update ON xplan_gml.fp_bereich;
DROP RULE IF EXISTS check_text_reftext_on_insert ON xplan_gml.fp_textabschnitt;
DROP RULE IF EXISTS check_text_reftext_on_update ON xplan_gml.fp_textabschnitt;

DROP RULE IF EXISTS check_bedeutung_on_insert ON xplan_gml.rp_bereich;
DROP RULE IF EXISTS check_bedeutung_on_update ON xplan_gml.rp_bereich;
DROP RULE IF EXISTS check_text_reftext_on_insert ON xplan_gml.rp_textabschnitt;
DROP RULE IF EXISTS check_text_reftext_on_update ON xplan_gml.rp_textabschnitt;

DROP RULE IF EXISTS check_bedeutung_on_insert ON xplan_gml.so_bereich;
DROP RULE IF EXISTS check_bedeutung_on_update ON xplan_gml.so_bereich;
DROP RULE IF EXISTS check_text_reftext_on_insert ON xplan_gml.so_textabschnitt;
DROP RULE IF EXISTS check_text_reftext_on_update ON xplan_gml.so_textabschnitt;


-- fix trigger so it only runs for bp, change to more correct name and correct linebreak internally
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
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

ALTER FUNCTION xplankonverter.check_bp_rechtsstand_datum_reference()
    OWNER TO kvwmap;


DROP TRIGGER IF EXISTS validate_rechtsstand_and_datum ON xplan_gml.bp_plan; -- this may be the old name
DROP TRIGGER IF EXISTS validate_rechtsstand_datum_reference ON xplan_gml.bp_plan;
CREATE TRIGGER validate_rechtsstand_datum_reference
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.bp_plan
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_bp_rechtsstand_datum_reference();

-------------------------------------------------------------------------------------------------------------------

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
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

ALTER FUNCTION xplankonverter.check_fp_rechtsstand_datum_reference()
    OWNER TO kvwmap;

DROP TRIGGER IF EXISTS validate_rechtsstand_datum_reference ON xplan_gml.fp_plan;
DROP TRIGGER IF EXISTS validate_rechtsstand_and_datum ON xplan_gml.fp_plan;
CREATE TRIGGER validate_rechtsstand_datum_reference
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.fp_plan
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_fp_rechtsstand_datum_reference();

-------------------------------------------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION xplankonverter.check_bp_bereich_bedeutung()
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
	IF (new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL) THEN
		msg = msg || E'\nWenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_bedeutung ON xplan_gml.bp_bereich;
CREATE TRIGGER validate_bedeutung
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.bp_bereich
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_bp_bereich_bedeutung();


CREATE OR REPLACE FUNCTION xplankonverter.check_fp_bereich_bedeutung()
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
	IF (new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL) THEN
		msg = msg || E'\nWenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_bedeutung ON xplan_gml.fp_bereich;
CREATE TRIGGER validate_bedeutung
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.fp_bereich
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_fp_bereich_bedeutung();

CREATE OR REPLACE FUNCTION xplankonverter.check_rp_bereich_bedeutung()
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
	IF (new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL) THEN
		msg = msg || E'\nWenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_bedeutung ON xplan_gml.rp_bereich;
CREATE TRIGGER validate_bedeutung
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.rp_bereich
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_rp_bereich_bedeutung();

CREATE OR REPLACE FUNCTION xplankonverter.check_so_bereich_bedeutung()
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
	IF (new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL) THEN
		msg = msg || E'\nWenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_bedeutung ON xplan_gml.so_bereich;
CREATE TRIGGER validate_bedeutung
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.so_bereich
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_so_bereich_bedeutung();

-------------------------------------------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION xplankonverter.check_bp_textabschnitt_reftext_on_insert()
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
	IF (new.text IS NOT NULL AND new.reftext IS NOT NULL) THEN
		msg = msg || E'\nKonformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_text_reftext_on_insert ON xplan_gml.bp_textabschnitt;
CREATE TRIGGER validate_text_reftext_on_insert
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.bp_textabschnitt
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_bp_textabschnitt_reftext_on_insert();

CREATE OR REPLACE FUNCTION xplankonverter.check_fp_textabschnitt_reftext_on_insert()
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
	IF (new.text IS NOT NULL AND new.reftext IS NOT NULL) THEN
		msg = msg || E'\nKonformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_text_reftext_on_insert ON xplan_gml.fp_textabschnitt;
CREATE TRIGGER validate_text_reftext_on_insert
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.fp_textabschnitt
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_fp_textabschnitt_reftext_on_insert();

CREATE OR REPLACE FUNCTION xplankonverter.check_rp_textabschnitt_reftext_on_insert()
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
	IF (new.text IS NOT NULL AND new.reftext IS NOT NULL) THEN
		msg = msg || E'\nKonformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_text_reftext_on_insert ON xplan_gml.rp_textabschnitt;
CREATE TRIGGER validate_text_reftext_on_insert
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.rp_textabschnitt
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_rp_textabschnitt_reftext_on_insert();

CREATE OR REPLACE FUNCTION xplankonverter.check_so_textabschnitt_reftext_on_insert()
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
	IF (new.text IS NOT NULL AND new.reftext IS NOT NULL) THEN
		msg = msg || E'\nKonformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_text_reftext_on_insert ON xplan_gml.so_textabschnitt;
CREATE TRIGGER validate_text_reftext_on_insert
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.so_textabschnitt
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_so_textabschnitt_reftext_on_insert();

CREATE OR REPLACE FUNCTION xplankonverter.check_xp_textabschnitt_reftext_on_insert()
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
	IF (new.text IS NOT NULL AND new.reftext IS NOT NULL) THEN
		msg = msg || E'\nKonformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_text_reftext_on_insert ON xplan_gml.xp_textabschnitt;
CREATE TRIGGER validate_text_reftext_on_insert
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.xp_textabschnitt
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_xp_textabschnitt_reftext_on_insert();

CREATE OR REPLACE FUNCTION xplankonverter.check_xp_begruendungabschnitt_reftext_on_insert()
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
	IF (new.text IS NOT NULL AND new.reftext IS NOT NULL) THEN
		msg = msg || E'\nKonformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein.';
	END IF;

	--raise notice 'msg: %', msg;
	IF (msg != '') THEN
	  RAISE EXCEPTION 'Validierung nicht bestanden: %', msg;
	END IF;
  RETURN NEW;
END;
$BODY$;

DROP TRIGGER IF EXISTS validate_text_reftext_on_insert ON xplan_gml.xp_begruendungabschnitt;
CREATE TRIGGER validate_text_reftext_on_insert
    BEFORE INSERT OR UPDATE 
    ON xplan_gml.xp_begruendungabschnitt
    FOR EACH ROW
    EXECUTE PROCEDURE xplankonverter.check_xp_begruendungabschnitt_reftext_on_insert();

COMMIT;