BEGIN;

CREATE OR REPLACE FUNCTION alkis.execute_hist_operations()
  RETURNS void AS
$BODY$
DECLARE
	r alkis.delete%ROWTYPE;
	s varchar;
	n integer;
BEGIN
	FOR r IN
		select *
		from alkis.delete 
		order by typename, featureid, context, endet
	LOOP
		s := 'UPDATE alkis.' || r.typename || ' SET endet=''' || r.endet || '''';
		IF r.context='update' AND r.anlass IS NOT NULL THEN
			s := s || ',anlass=array_cat(anlass,''{' || array_to_string(r.anlass,',') || '}'')';
		END IF;
		s := s || ' WHERE gml_id=''' || r.featureid || '''' || ' AND beginnt < ''' || r.endet || ''' AND (endet IS NULL OR endet = ''' || r.endet || ''')';
		EXECUTE s;
		--RAISE INFO '%', s;
		GET DIAGNOSTICS n = ROW_COUNT;
		IF n=0 THEN
			RAISE NOTICE 'Beenden des Objektes % schlug fehl: %', r.featureid, s;
		ELSE
			IF n>1 THEN
				RAISE NOTICE 'Es gab mehrere Objektversionen von % die jetzt alle beendet wurden: %', r.featureid, s;
			END IF;
			s := 'DELETE FROM alkis.delete WHERE ogc_fid = ' || r.ogc_fid;
			EXECUTE s;
			GET DIAGNOSTICS n = ROW_COUNT;
			IF n<>1 THEN
				RAISE EXCEPTION 'Löschen des Eintrags in der delete-Tabelle schlug fehl: %', s;
			END IF;
		END IF;
	END LOOP;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
	

CREATE OR REPLACE FUNCTION alkis.ignore_dublicates()
  RETURNS trigger AS
$BODY$
DECLARE
	dublicate boolean;
BEGIN
	EXECUTE 'SELECT true FROM alkis.'||TG_TABLE_NAME||' WHERE gml_id = '''||new.gml_id||''' AND beginnt = '''||new.beginnt||'''' INTO dublicate;
	if dublicate THEN
		RAISE NOTICE 'Objekt in % mit gml_id: % und beginnt: % wird ignoriert, da bereits vorhanden.', TG_TABLE_NAME, NEW.gml_id, NEW.beginnt;
		RETURN NULL;
	ELSE 
		RETURN NEW;
	END IF;
	
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;


CREATE TRIGGER ignore_dublicates
BEFORE INSERT
ON alkis.ax_person
FOR EACH ROW
EXECUTE PROCEDURE alkis.ignore_dublicates();
ALTER TABLE alkis.ax_person DISABLE TRIGGER ignore_dublicates;

CREATE TRIGGER ignore_dublicates
BEFORE INSERT
ON alkis.ax_namensnummer
FOR EACH ROW
EXECUTE PROCEDURE alkis.ignore_dublicates();
ALTER TABLE alkis.ax_namensnummer DISABLE TRIGGER ignore_dublicates;

CREATE TRIGGER ignore_dublicates
BEFORE INSERT
ON alkis.ax_anschrift
FOR EACH ROW
EXECUTE PROCEDURE alkis.ignore_dublicates();
ALTER TABLE alkis.ax_anschrift DISABLE TRIGGER ignore_dublicates;

CREATE TRIGGER ignore_dublicates
BEFORE INSERT
ON alkis.ax_buchungsstelle
FOR EACH ROW
EXECUTE PROCEDURE alkis.ignore_dublicates();
ALTER TABLE alkis.ax_buchungsstelle DISABLE TRIGGER ignore_dublicates;

CREATE TRIGGER ignore_dublicates
BEFORE INSERT
ON alkis.ax_buchungsblatt
FOR EACH ROW
EXECUTE PROCEDURE alkis.ignore_dublicates();
ALTER TABLE alkis.ax_buchungsblatt DISABLE TRIGGER ignore_dublicates;

CREATE TRIGGER ignore_dublicates
BEFORE INSERT
ON alkis.ax_lagebezeichnungmithausnummer
FOR EACH ROW
EXECUTE PROCEDURE alkis.ignore_dublicates();
ALTER TABLE alkis.ax_lagebezeichnungmithausnummer DISABLE TRIGGER ignore_dublicates;

COMMIT;
