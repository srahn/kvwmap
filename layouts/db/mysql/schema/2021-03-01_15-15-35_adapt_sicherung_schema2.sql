BEGIN;
	ALTER TABLE `sicherungen` ADD `active` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Ist die Sicherung aktiv?';
	ALTER TABLE `sicherungsinhalte` ADD `active` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Ist die Sicherung aktiv?';
COMMIT;
