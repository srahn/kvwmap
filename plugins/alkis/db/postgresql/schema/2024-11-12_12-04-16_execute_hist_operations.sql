BEGIN;

CREATE OR REPLACE FUNCTION alkis.execute_hist_operations()
    RETURNS void
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
AS $BODY$
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
		s = 'delete from
				alkis.' || r.typename || ' o
			using (
				select 
					unnest((array_agg(ogc_fid))[1:count(*) - 1]) as ogc_fid
				from 
					alkis.' || r.typename || ' 
				where 
					gml_id = ''' || r.featureid || '''
				group by
					gml_id, beginnt 
				having 
					count(beginnt) > 1
			) as d
			where
				d.ogc_fid = o.ogc_fid';
		EXECUTE s;
		--RAISE INFO '%', s;
		GET DIAGNOSTICS n = ROW_COUNT;
		IF n > 0 THEN
			RAISE INFO '% doppelte Objektversionen von % in % gelöscht.', n, r.featureid, r.typename;
		END IF;

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
$BODY$;

COMMIT;
