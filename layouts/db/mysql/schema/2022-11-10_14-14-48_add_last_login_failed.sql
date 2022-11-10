BEGIN;

	ALTER TABLE `user` add `last_login_failed` timestamp AFTER `num_login_failed`;

COMMIT;
