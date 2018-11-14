BEGIN;

	ALTER TABLE `user` ADD COLUMN num_login_failed INTEGER NOT NULL DEFAULT 0 COMMENT 'Anzahl der nacheinander fehlgeschlagenen Loginversuche mit diesem login_namen';

COMMIT;
