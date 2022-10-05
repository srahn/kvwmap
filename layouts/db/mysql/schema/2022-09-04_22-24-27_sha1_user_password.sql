BEGIN;

	ALTER TABLE `user` add `password` varchar(40) AFTER `passwort`;
	ALTER TABLE `user` change `passwort` `passwort` varchar(32) NULL;

COMMIT;
