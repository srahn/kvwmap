BEGIN;

	ALTER TABLE `user` add `password_expired` boolean NOT NULL DEFAULT false AFTER `password`;

COMMIT;
