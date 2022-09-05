BEGIN;

	ALTER TABLE `user` add `password` varchar(40) AFTER `passwort`;

COMMIT;
