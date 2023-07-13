BEGIN;

-- FUNCTION: alkis.execute_hist_operations()

-- DROP FUNCTION IF EXISTS alkis.execute_hist_operations();

CREATE OR REPLACE FUNCTION alkis.execute_hist_operations(
	)
    RETURNS void
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE
AS $BODY$
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
		s := 'UPDATE alkis.' || r.typename || ' SET endet = ''' || substr(r.replacedby, 17) || '''';
		IF r.context='update' AND r.anlass IS NOT NULL THEN
			s := s || ',anlass=array_cat(anlass,''{' || array_to_string(r.anlass,',') || '}'')';
		END IF;
		s := s || ' WHERE gml_id=''' || r.featureid || '''' || ' AND beginnt < ''' || substr(r.replacedby, 17) || ''' AND (endet IS NULL OR endet = ''' || substr(r.replacedby, 17) || ''')';
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
$BODY$;


COMMIT;
