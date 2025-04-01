BEGIN;

CREATE OR REPLACE FUNCTION alkis.delete_duplicates()
  RETURNS void AS
$BODY$
DECLARE
	r alkis.delete%ROWTYPE;
	s varchar;
	n integer;
	duplicates record;
BEGIN
	FOR r IN
		select *
		from alkis.delete 
		where ignored = false
		order by typename, featureid, endet
	LOOP
		n = 0;
		FOR duplicates IN
			EXECUTE '
				select 
					count(*) as count,
					a.beginnt
				from 
					alkis.' || r.typename || ' a
				where 
					gml_id = ''' || r.featureid || '''
				group by
					gml_id, beginnt 
				having 
					count(beginnt) > 1'
		LOOP
			s = '
				delete from
					alkis.' || r.typename || ' o
				where
					ogc_fid IN (
						select 
							ogc_fid
						from
							alkis.' || r.typename || ' o
						where 
							gml_id = ''' || r.featureid || ''' AND 
							beginnt = ''' || duplicates.beginnt ||  '''
						LIMIT ' || (duplicates.count - 1) || '
					);
			';
			EXECUTE s;
	
			s = '		
				delete from
					alkis.delete
				where
					ogc_fid IN (
						select
							ogc_fid
						from
							alkis.delete
						where
							typename = ''' || r.typename || ''' AND
							featureid = ''' || r.featureid || ''' AND 
							endet::timestamp = ''' || duplicates.beginnt ||  '''
						LIMIT ' || (duplicates.count - 1) || '
					);
			';
			EXECUTE s;
			n = n + (duplicates.count - 1);
		END LOOP;
		--RAISE INFO '%', s;
		IF n > 0 THEN
			RAISE INFO '% doppelte Objektversionen von % in % gelöscht.', n, r.featureid, r.typename;
		END IF;
	END LOOP;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;


CREATE OR REPLACE FUNCTION alkis.log_hist_operations()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
DECLARE
	n INTEGER;
	s varchar;
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
				s = '
					select 
						max(beginnt)
					from (
						SELECT 
							a.beginnt
						FROM 
							alkis.' || NEW.typename || ' a
						WHERE 
							a.gml_id = ''' || featureid || '''
						
						EXCEPT ALL
						
						SELECT
							delete.endet::timestamp
						FROM
							alkis.delete 
						WHERE
							delete.featureid = ''' || featureid || ''' AND 
							delete.typename = ''' || NEW.typename || ''' 
					) foo
				';
				EXECUTE s INTO NEW.endet;
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
$BODY$;



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
		where ignored = false
		order by typename, featureid, endet
	LOOP
		s = 'UPDATE alkis.' || r.typename || ' SET endet = ''' || r.endet || '''';
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
				RAISE INFO 'Es gab mehrere Objektversionen von % die jetzt alle beendet wurden: %', r.featureid, s;
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


COMMIT;
