BEGIN;
	CREATE OR REPLACE FUNCTION public.gdi_in_date_ranges(
		day date,
		startdates date[],
		enddates date[])
	    RETURNS boolean
	    LANGUAGE 'sql'
	    COST 100
	    STABLE
	AS $BODY$
		SELECT
	  		COALESCE(true = ANY(array_agg(daterange(startdate, CASE WHEN startdate > enddate THEN NULL ELSE enddate END) @> now()::date)), true)
		FROM
			(
				SELECT
	  				unnest(startdates) AS startdate,
	  				(unnest(enddates) + interval '1' day)::date AS enddate
			) foo
	$BODY$;

	COMMENT ON FUNCTION public.gdi_in_date_ranges(date, date[], date[])
	    IS 'Liefert den Wert wahr zurück, wenn sich der übergebene Zeitpunkt innerhalb der gegebeenen Start und End Datumsangaben befindet und wenn keine Zeitfenster angegeben sind. Ist ein Startdatum größer als das dazugehörige Enddatum, wird das Enddatum ignoriert.';

COMMIT;
