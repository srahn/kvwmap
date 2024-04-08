BEGIN;
	CREATE OR REPLACE FUNCTION public.gdi_codelist_json_to_text(
		codelist json,
		pg_type regtype
	)
	RETURNS text
	LANGUAGE 'plpgsql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		DECLARE
			sql text;
			result text;
		BEGIN
				IF (codelist->>'value' IS NULL) THEN
					sql = FORMAT('
						SELECT
							CONCAT_WS('', '', ''codespace: '' || codespace, ''id: '' || id, ''value: '' || value)
						FROM
							%2$s
						WHERE
							id LIKE %1$L;       
					', codelist->>'id', pg_type::text);
					RAISE NOTICE 'sql: %', sql;
					
					--SELECT codelist->>'id' FROM xplan_gml.fp_detailartderbaulnutzung LIMIT 1 INTO result;
					EXECUTE sql INTO result;
				ELSE
					SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(json_strip_nulls(codelist)::text, '['::text, ''::text), ']', ''), '{', ''), '}', ''), '"', ''), ', ', ','), ',', ', '), ':', ': ') INTO result;
				END IF;
				RETURN result;
		END
	$BODY$;
	
COMMIT;