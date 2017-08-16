BEGIN;
CREATE OR REPLACE FUNCTION exec_sql(
	sql text
)
RETURNS void AS
$BODY$
DECLARE
BEGIN
	EXECUTE sql;
END;
$BODY$
LANGUAGE plpgsql VOLATILE
COST 100;
COMMENT ON FUNCTION exec_sql(text) IS 'Führt den übergebenen Text als sql aus.';
COMMIT;
