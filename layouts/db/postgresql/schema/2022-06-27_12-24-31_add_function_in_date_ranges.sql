BEGIN;
	CREATE OR REPLACE FUNCTION public.gdi_in_date_ranges(
		day date,
		startdates date[],
		enddates date[]
	)
	RETURNS boolean
	LANGUAGE 'sql'
	COST 100
	VOLATILE PARALLEL UNSAFE
	AS $BODY$
		SELECT
			COALESCE(true = ANY(array_agg(results)), true)
		FROM
			(
				SELECT
					daterange(unnest(startdates) , (unnest(enddates) + interval '1' day)::date) @> day AS results
			) AS foo
	$BODY$;

	COMMENT ON FUNCTION public.gdi_in_date_ranges(date, date[], date[]) IS 'Liefert den Wert wahr zurück, wenn sich der übergebene Zeitpunkt innerhalb der gegebeenen Start und End Datumsangaben befindet und wenn keine Zeitfenster angegeben sind.';

COMMIT;
