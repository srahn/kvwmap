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
		RAISE INFO '%', s;
		GET DIAGNOSTICS n = ROW_COUNT;
		IF n<>1 THEN
			RAISE NOTICE 'Beenden des Objektes % schlug fehl: %', r.featureid, s;
		ELSE
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
	
	
CREATE OR REPLACE FUNCTION alkis.log_hist_operations()
  RETURNS trigger AS
$BODY$
DECLARE
	n INTEGER;
	featureid varchar(16);
BEGIN
	NEW.context := coalesce(lower(NEW.context),'delete');

	IF (length(NEW.featureid)=32) THEN
		featureid = substr(NEW.featureid, 1, 16);   -- kurze gml_id ohne timestamp
	ELSEIF NOT (length(NEW.featureid)=16) THEN
		RAISE EXCEPTION '%: Identifikator gescheitert.', NEW.featureid;
	END IF;	

	IF NEW.context='delete' THEN
		NEW.endet := to_char(CURRENT_TIMESTAMP AT TIME ZONE 'UTC','YYYY-MM-DD"T"HH24:MI:SS"Z"');

	ELSIF NEW.context='update' THEN
		IF NEW.endet IS NULL THEN
			RAISE EXCEPTION '%: Endedatum nicht gesetzt', NEW.featureid;
		END IF;

	ELSIF NEW.context='replace' THEN
		NEW.safetoignore := lower(NEW.safetoignore);
		IF NEW.safetoignore IS NULL THEN
			RAISE EXCEPTION '%: safeToIgnore nicht gesetzt.', NEW.featureid;
		ELSIF NEW.safetoignore<>'true' AND NEW.safetoignore<>'false' THEN
			RAISE EXCEPTION '%: safeToIgnore ''%'' ungültig (''true'' oder ''false'' erwartet).', NEW.featureid, NEW.safetoignore;
		END IF;

		-- Ermittlung von NEW.endet: Entspricht "beginnt" der gerade eingefügten Objektversion und "endet" der Vorgängerobjektversion
		IF length(NEW.replacedby)=32 AND NEW.replacedby<>NEW.featureid THEN
			NEW.endet := substr(NEW.replacedby, 17)::timestamp without time zone;
		END IF;

		IF NEW.endet IS NULL THEN
			EXECUTE 'SELECT min(beginnt) FROM ' || NEW.typename || ' a'
				|| ' WHERE gml_id=''' || featureid || ''' AND beginnt > '''|| substr(NEW.featureid, 17) ||''''
				INTO NEW.endet;
		ELSE
			EXECUTE 'SELECT count(*) FROM ' || NEW.typename
				|| ' WHERE gml_id=''' || featureid || ''''
				|| ' AND beginnt=''' || NEW.endet || ''''
				INTO n;
			IF n<>1 THEN
				RAISE EXCEPTION '%: Ersatzobjekt % % nicht gefunden.', NEW.featureid, NEW.replacedby, NEW.endet;
			END IF;
		END IF;

		IF NEW.endet IS NULL THEN
			IF NEW.safetoignore='false' THEN
				RAISE EXCEPTION '%: Beginn des Ersatzobjekts % nicht gefunden.', NEW.featureid, NEW.replacedby;
				-- RAISE NOTICE '%: Beginn des ersetzenden Objekts % nicht gefunden.', NEW.featureid, NEW.replacedby;
			END IF;

			NEW.ignored=true;
			RETURN NEW;
		END IF;

	ELSE
		RAISE EXCEPTION '%: Ungültiger Kontext % (''delete'', ''replace'' oder ''update'' erwartet).', NEW.featureid, NEW.context;

	END IF;

	new.featureid = featureid;

	NEW.ignored := false;
	RETURN NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
