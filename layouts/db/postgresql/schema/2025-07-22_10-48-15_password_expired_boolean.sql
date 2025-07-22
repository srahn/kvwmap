BEGIN;
  ALTER TABLE kvwmap."user" ALTER COLUMN password_expired DROP DEFAULT;
  ALTER TABLE kvwmap."user" ALTER COLUMN password_expired TYPE boolean USING password_expired = 1;
  ALTER TABLE kvwmap."user" ALTER COLUMN password_expired SET DEFAULT false;
COMMIT;