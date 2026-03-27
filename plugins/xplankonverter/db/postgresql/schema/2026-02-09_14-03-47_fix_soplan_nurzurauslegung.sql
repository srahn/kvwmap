BEGIN;

-- FUNCTION: xplankonverter.filter_nurzurauslegung(date[], date[], xplankonverter.xp_spezexternereferenzauslegung[])

-- DROP FUNCTION IF EXISTS xplankonverter.filter_nurzurauslegung(date[], date[], xplankonverter.xp_spezexternereferenzauslegung[]);

CREATE OR REPLACE FUNCTION xplankonverter.filter_nurzurauslegung(
	auslegungsstartdatum date[],
	auslegungsenddatum date[],
	externereferenzen_mit_nurzurauslegung xplankonverter.xp_spezexternereferenzauslegung[])
    RETURNS xplan_gml.xp_spezexternereferenz[]
    LANGUAGE 'plpgsql'
    COST 100
    STABLE PARALLEL UNSAFE
AS $BODY$
		DECLARE
			_sql text; 
			_result xplan_gml.xp_spezexternereferenz[];
			i integer = 0;
			n integer = array_length(externereferenzen_mit_nurzurauslegung, 1);
			today date = (now() at time zone 'Europe/Berlin')::date;
			is_in_date_range boolean = false;
		BEGIN
			IF(externereferenzen_mit_nurzurauslegung IS NULL)
			THEN
				RETURN NULL;
			END IF;
			IF (n > 0)
				THEN
					FOR i IN 1..n LOOP
						is_in_date_range = (today BETWEEN auslegungsstartdatum[i] AND auslegungsenddatum[i]);
						EXIT WHEN is_in_date_range;
					END LOOP;
			END IF;
			
			SELECT
				ARRAY_AGG(xplankonverter.to_spezexternereferenz(e))
			FROM
				(
					SELECT
						unnest(externereferenzen_mit_nurzurauslegung) AS e
				) foo
			WHERE
				(e).nurzurauslegung IS NULL OR
				NOT (e).nurzurauslegung OR
				(
				(e).nurzurauslegung AND
				is_in_date_range
				)
			INTO _result;
			RETURN _result;
		END;
	
$BODY$;

ALTER FUNCTION xplankonverter.filter_nurzurauslegung(date[], date[], xplankonverter.xp_spezexternereferenzauslegung[])
    OWNER TO kvwmap;


COMMIT;
