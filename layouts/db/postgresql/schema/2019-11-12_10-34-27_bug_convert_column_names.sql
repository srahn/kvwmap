BEGIN;

CREATE OR REPLACE FUNCTION convert_column_names(
    schemaname character varying,
    tablename character varying)
  RETURNS void AS
$BODY$
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
			    -- replace all spaces with _, xX and Xx becomes x_x
			    regexp_replace(
			      -- First, replace spaces with an _
			      REPLACE(replace(column_name, ' ', '_'), '.', '_'),
			      '([[:lower:]])([[:upper:]])',
			      '\1_\2',
			      'xg'
			    )
			  ) as new_column_name

			FROM information_schema.columns
			WHERE table_schema = schemaname
			AND table_name = tablename
		) as foo
		WHERE new_column_name != column_name), ' ');
	IF s IS NOT NULL THEN
		EXECUTE s;
	END IF;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
