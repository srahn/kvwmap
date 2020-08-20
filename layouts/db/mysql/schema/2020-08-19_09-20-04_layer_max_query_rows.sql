BEGIN;

ALTER TABLE `layer` ADD `max_query_rows` INT(11) NULL DEFAULT NULL AFTER `template`;

COMMIT;
