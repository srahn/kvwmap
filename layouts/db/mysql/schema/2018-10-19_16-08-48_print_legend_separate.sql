BEGIN;

ALTER TABLE `rolle` ADD `print_legend_separate` BOOLEAN NOT NULL DEFAULT FALSE;

COMMIT;
