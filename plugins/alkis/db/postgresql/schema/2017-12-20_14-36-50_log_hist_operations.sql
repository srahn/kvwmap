CREATE OR REPLACE FUNCTION alkis.log_hist_operations()
  RETURNS trigger AS
$BODY$
DECLARE
	n INTEGER;
	featureid varchar(16);
BEGIN
	NEW.context := coalesce(lower(NEW.context),'delete');

	IF NOT (length(NEW.featureid)=32 OR length(NEW.featureid)=16) THEN
		RAISE EXCEPTION '%: Identifikator gescheitert.', NEW.featureid;
	END IF;

	featureid = substr(NEW.featureid, 1, 16);   -- kurze gml_id ohne timestamp

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

			IF (length(NEW.featureid)=16) THEN		-- Zeitstempel ist nicht in der featureid enthalten
				EXECUTE 'SELECT max(a.beginnt) FROM ' || NEW.typename || ' a'
					|| ' LEFT JOIN alkis.delete ON delete.featureid = a.gml_id AND delete.typename = ''' || NEW.typename || ''' and delete.endet::timestamp = a.beginnt'
					|| ' WHERE a.gml_id=''' || featureid || ''' AND a.endet IS NULL'
					|| ' AND delete.featureid IS NULL'
					INTO NEW.endet;

			ELSE						-- Zeitstempel ist in der featureid enthalten
				EXECUTE 'SELECT min(beginnt) FROM ' || NEW.typename || ' a'
					|| ' WHERE gml_id=''' || featureid || ''' AND beginnt > '''|| substr(NEW.featureid, 17) ||''''
					INTO NEW.endet;
			END IF;
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
