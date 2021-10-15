BEGIN;

ALTER TABLE `belated_files` CHANGE `lastmodified` `lastmodified` BIGINT NOT NULL;

COMMIT;
