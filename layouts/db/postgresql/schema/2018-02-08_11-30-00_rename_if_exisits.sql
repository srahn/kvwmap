BEGIN;

CREATE OR REPLACE FUNCTION rename_if_exists(schemaname varchar, tablename varchar, columnname varchar)  RETURNS void AS
$BODY$
DECLARE
	n integer;
BEGIN
	EXECUTE 
		'SELECT 1 
		FROM information_schema.columns 
		WHERE table_schema='''||schemaname||''' AND table_name='''||tablename||''' AND column_name='''||columnname||''';';
	GET DIAGNOSTICS n = ROW_COUNT;
	IF n=1 THEN
		EXECUTE 'ALTER TABLE ' || schemaname || '.' || tablename || ' RENAME "'||columnname||'" TO '||columnname||'_';
	END IF;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;