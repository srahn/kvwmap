BEGIN;

ALTER TABLE kvwmap.stelle ADD totp_authentication bool DEFAULT false NOT NULL;

COMMIT;
