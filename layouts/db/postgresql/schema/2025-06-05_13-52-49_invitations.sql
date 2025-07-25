BEGIN;

ALTER TABLE kvwmap.invitations DROP CONSTRAINT IF EXISTS invitations_ibfk_1;

ALTER TABLE kvwmap.invitations
    ALTER COLUMN stelle_id DROP DEFAULT;

ALTER TABLE kvwmap.invitations
    ALTER COLUMN stelle_id TYPE integer[] USING ARRAY[stelle_id];

ALTER TABLE kvwmap.invitations
    ADD COLUMN IF NOT EXISTS created_at date default now(),
    ADD COLUMN IF NOT EXISTS stop date;

COMMIT;
