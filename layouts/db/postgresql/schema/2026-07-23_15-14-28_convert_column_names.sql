BEGIN;

CREATE OR REPLACE FUNCTION public.convert_column_names(schemaname character varying, tablename character varying)
 RETURNS void
 LANGUAGE plpgsql
AS $function$
DECLARE
	s character varying;
BEGIN
	s := 	array_to_string(array(
		SELECT FORMAT('ALTER TABLE %I.%I RENAME %I to %I;', table_schema, table_name, column_name, new_column_name)
		from (
			SELECT 
			  table_schema,
			  table_name,
			  column_name,
			  lower(
				    regexp_replace(
				        translate(column_name, '|/. ', '____'),
				        '([[:lower:]])([[:upper:]])',
				        '\1_\2',
				        'xg'
				    )
			  ) AS new_column_name

			FROM information_schema.columns
			WHERE table_schema = schemaname
			AND table_name = tablename
		) as foo
		WHERE new_column_name != column_name AND 
		lower(column_name) NOT IN ('xmin', 'xmax')), ' ');
	IF s IS NOT NULL THEN
		EXECUTE s;
	END IF;
END;
$function$
;

COMMIT;
