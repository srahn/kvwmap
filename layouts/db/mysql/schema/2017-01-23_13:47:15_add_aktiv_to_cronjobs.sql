BEGIN;

ALTER TABLE `cron_jobs` ADD `aktiv` BOOLEAN NOT NULL DEFAULT false AFTER `stelle_id`;

COMMIT;
