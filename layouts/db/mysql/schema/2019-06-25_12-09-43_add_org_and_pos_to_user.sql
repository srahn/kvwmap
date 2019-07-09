BEGIN;

	ALTER TABLE `user` ADD `organisation` varchar(255);
	ALTER TABLE `user` ADD `position` varchar(255);

COMMIT;
