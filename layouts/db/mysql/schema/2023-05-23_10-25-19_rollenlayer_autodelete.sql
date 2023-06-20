BEGIN;

ALTER TABLE `rollenlayer` ADD `autodelete` BOOLEAN NOT NULL DEFAULT TRUE AFTER `wms_auth_password`;

COMMIT;
