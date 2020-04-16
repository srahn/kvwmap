BEGIN;

	ALTER TABLE `cron_jobs` ADD `user` enum('root', 'gisadmin') NOT NULL DEFAULT 'gisadmin';

COMMIT;
