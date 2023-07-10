BEGIN;

ALTER TABLE `user` ADD `userdata_checking_time` TIMESTAMP NULL DEFAULT NULL AFTER `password_setting_time`;

COMMIT;
