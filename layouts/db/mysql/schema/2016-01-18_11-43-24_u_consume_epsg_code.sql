BEGIN;

ALTER TABLE `u_consume` ADD `epsg_code` VARCHAR(6) NULL AFTER `nimageheight`;

COMMIT;
