BEGIN;

CREATE OR REPLACE FUNCTION clone_schema_to_log_queries(
    source_schema text,
    dest_schema text)
  RETURNS void AS
$BODY$
DECLARE 
  source_table text;
BEGIN
    EXECUTE 'DROP SCHEMA IF EXISTS ' || dest_schema || ' CASCADE';
    EXECUTE 'CREATE SCHEMA ' || dest_schema ;
    EXECUTE 'CREATE TABLE ' || dest_schema || '.queries ( query text, created_at timestamp without time zone default current_timestamp )'; 
    EXECUTE 'CREATE OR REPLACE FUNCTION ' || dest_schema || '.log_query() RETURNS TRIGGER AS $body$ BEGIN INSERT INTO ' || dest_schema || '.queries VALUES (replace(current_query(), '' ' || dest_schema || '.'', '' ' || source_schema || '.'')); RETURN NEW; END; $body$ LANGUAGE plpgsql';
 
    FOR source_table IN
        SELECT TABLE_NAME::text FROM information_schema.TABLES WHERE table_schema = source_schema
    LOOP
        EXECUTE 'CREATE VIEW ' || dest_schema || '.' || source_table || ' AS SELECT * FROM ' || source_schema || '.' || source_table;
        EXECUTE 'CREATE TRIGGER log_query INSTEAD OF INSERT OR UPDATE OR DELETE ON ' || dest_schema || '.' || source_table || ' FOR EACH ROW EXECUTE PROCEDURE ' || dest_schema || '.log_query()';
    END LOOP; 
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION clone_schema_to_log_queries(text, text)
  OWNER TO kvwmap;
COMMENT ON FUNCTION clone_schema_to_log_queries(text, text) IS 'Legt ein neues Schema dest_schema an und darin eine Triggerfunktion und eine Tabelle zum loggen von queries. Zusätzlich wird für jede Tabelle aus source_schema ein view in dest_schema angelegt auf dem ein Instead Trigger liegt, der die current_query in die log-Tabelle schreibt.';

COMMIT;
