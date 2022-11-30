BEGIN;

DO $$ 
    BEGIN
        BEGIN
            ALTER TABLE jagdkataster.jagdpaechter2bezirke ADD COLUMN gid serial;
        EXCEPTION
            WHEN duplicate_column THEN RAISE NOTICE 'Die Spalte gid existiert bereits in jagdkataster.jagdpaechter2bezirke.';
        END;
    END;
$$;

ALTER TABLE jagdkataster.jagdpaechter2bezirke DROP CONSTRAINT jagdpaechter2bezirke_pkey;

ALTER TABLE jagdkataster.jagdpaechter2bezirke
  ADD CONSTRAINT jagdpaechter2bezirke_pkey PRIMARY KEY(gid);

COMMIT;
