BEGIN;


CREATE TABLE privilege_backup
(
  privilege text
);


CREATE OR REPLACE FUNCTION public.privilege_backup(schema text, relation text)
  RETURNS void AS
$BODY$
BEGIN
	EXECUTE $$
	truncate privilege_backup;

	insert into privilege_backup
	SELECT 'GRANT ' || privilege_type || ' ON $$ || schema || $$.$$ || relation || $$ TO ' || grantee || ';'
	FROM information_schema.role_table_grants 
	WHERE table_schema = '$$ || schema || $$' and table_name = '$$ || relation || $$'$$;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
	
	
CREATE OR REPLACE FUNCTION public.privilege_restore(schema text, relation text)
  RETURNS void AS
$BODY$
declare
    r record;
BEGIN
    for r in select * from privilege_backup
    loop 
	EXECUTE r.privilege;
    end loop;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;	


COMMIT;
