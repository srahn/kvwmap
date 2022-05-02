BEGIN;

CREATE OR REPLACE FUNCTION alkis.delete_object_tables()
  RETURNS character varying AS
$BODY$
DECLARE
	c RECORD;
	r varchar;
	d varchar;
BEGIN
	r := '';
	d := '';

	FOR c IN
		SELECT distinct 
			pg_class.relname as table_name
		FROM 
			pg_catalog.pg_class
		INNER JOIN
			pg_catalog.pg_attribute ON pg_class.oid = pg_attribute.attrelid
		INNER JOIN
			pg_catalog.pg_namespace ON pg_namespace.oid = pg_class.relnamespace
		WHERE
			pg_class.relkind = 'r'
			AND pg_namespace.nspname = 'alkis'
			AND (substr(pg_class.relname,1,3) IN ('ax_','ap_','aa_' ) AND pg_attribute.attname = 'gml_id'
			OR pg_class.relname IN ('import','delete') )
		ORDER BY
			pg_class.relname
	LOOP
		r := r || d || c.table_name || ' wurde geleert.';
		EXECUTE 'DELETE FROM alkis.'||c.table_name;
		d := E'\n';
	END LOOP;

	RETURN r;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
