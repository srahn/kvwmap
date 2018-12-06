BEGIN;

ALTER TABLE `user` CHANGE `login_name` `login_name` VARCHAR( 100 ) NOT NULL DEFAULT '';

COMMIT;
