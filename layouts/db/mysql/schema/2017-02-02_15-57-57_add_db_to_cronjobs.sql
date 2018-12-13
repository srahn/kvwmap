BEGIN;

ALTER TABLE `cron_jobs` ADD `dbname` varchar(68);

COMMIT;

