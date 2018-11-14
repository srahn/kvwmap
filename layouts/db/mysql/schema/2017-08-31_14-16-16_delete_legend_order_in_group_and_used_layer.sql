BEGIN;

ALTER TABLE `u_groups` DROP COLUMN `legendorder`;
ALTER TABLE `used_layer` DROP COLUMN `legendorder`;

COMMIT;
