BEGIN;

  ALTER TABLE `datasources` CHANGE `name` `name` VARCHAR(100) NULL;

COMMIT;