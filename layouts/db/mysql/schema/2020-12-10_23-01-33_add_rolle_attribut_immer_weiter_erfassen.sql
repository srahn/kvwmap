BEGIN;
	ALTER TABLE `rolle` ADD COLUMN `immer_weiter_erfassen` BOOLEAN DEFAULT false;
COMMIT;