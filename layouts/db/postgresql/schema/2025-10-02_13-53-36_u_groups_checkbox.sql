BEGIN;

ALTER TABLE kvwmap.u_groups ADD COLUMN checkbox boolean NOT NULL DEFAULT false;

COMMIT;
