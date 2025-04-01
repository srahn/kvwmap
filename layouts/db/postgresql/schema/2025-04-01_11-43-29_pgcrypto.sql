BEGIN;

SET search_path TO public;

DO $$
BEGIN
   execute 'ALTER DATABASE '||current_database()||' SET search_path TO public;';
END
$$;

CREATE EXTENSION IF NOT EXISTS pgcrypto;

COMMIT;
