BEGIN;

	CREATE OR REPLACE FUNCTION gdi_enum_json_to_text(value json, schema character varying, type character varying, is_array boolean)
	RETURNS text
	LANGUAGE 'plpgsql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		DECLARE
			sql text;
			result text;
		BEGIN
			IF is_array THEN
				sql = FORMAT('
					SELECT
						STRING_AGG(
							concat_ws('' '', e.abkuerzung, ''('' || e.wert || '')''),
							'', '')
					FROM
						json_array_elements(
							%1$L
						) ts(wert) JOIN
						%2$s.enum_%3$s e ON (ts #>> ''{}'')::integer = e.wert;
				', value, schema, type);
			ELSE
				sql = FORMAT('
					SELECT
						concat_ws('' '', e.abkuerzung, ''('' || e.wert || '')'')
					FROM
						%2$s.enum_%3$s e
					WHERE
						%1$L = ''"'' || e.wert || ''"''
				', value, schema, type);
			END IF;
			EXECUTE sql INTO result;
			RETURN result;
		END;
	$BODY$;

	CREATE OR REPLACE FUNCTION gdi_datatype_json_to_text(value json, is_array boolean)
	RETURNS text
	LANGUAGE 'plpgsql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		DECLARE
			sql text;
			result text;
		BEGIN
			IF is_array THEN
				sql = FORMAT('
					SELECT
						STRING_AGG(he::text, '', '')
					FROM 
						(
							SELECT json_array_elements(json_strip_nulls(%1$L)) he
						) et
				', value);
			ELSE
				sql = FORMAT('
					SELECT json_strip_nulls(%1$L)::text
				', value);
			END IF;
			EXECUTE sql INTO result;
			RETURN result;
		END;
	$BODY$;

	CREATE OR REPLACE FUNCTION gdi_codelist_json_to_text(codelist json)
	RETURNS text
	LANGUAGE 'sql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(json_strip_nulls(codelist)::text, '['::text, ''::text), ']', ''), '{', ''), '}', ''), '"', ''), ', ', ','), ',', ', '), ':', ': ')
	$BODY$;

COMMIT;
