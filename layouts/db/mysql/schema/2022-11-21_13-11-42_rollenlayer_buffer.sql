BEGIN;

ALTER TABLE `rollenlayer` ADD `buffer` INT(11) NULL AFTER `transparency`;

COMMIT;
