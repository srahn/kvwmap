BEGIN;
	ALTER TABLE `rollenlayer` ADD `wms_auth_username` varchar(100);
	ALTER TABLE `rollenlayer` ADD `wms_auth_password` varchar(50);
COMMIT;
