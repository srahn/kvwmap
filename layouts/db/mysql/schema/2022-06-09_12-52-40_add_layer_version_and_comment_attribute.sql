BEGIN;

  ALTER TABLE `stelle` ADD COLUMN `version` varchar(10) NOT NULL DEFAULT '1.0.0';
  ALTER TABLE `stelle` ADD COLUMN `comment` text;

  ALTER TABLE `layer` ADD COLUMN `version`  varchar(10) NOT NULL DEFAULT '1.0.0';
  ALTER TABLE `layer` ADD COLUMN `comment` text;

COMMIT;
