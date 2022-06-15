BEGIN;
  ALTER TABLE `u_groups` ADD COLUMN `icon` VARCHAR(255);
  ALTER TABLE `layer` ADD COLUMN `icon` VARCHAR(255) AFTER `metalink`;
  ALTER TABLE `stelle` ADD COLUMN `minzoom` integer NOT NULL DEFAULT 8 AFTER `maxymax`;
COMMIT;
