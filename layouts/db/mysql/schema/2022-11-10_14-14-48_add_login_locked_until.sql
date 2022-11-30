BEGIN;

	ALTER TABLE `user` add `login_locked_until` timestamp AFTER `num_login_failed`;

COMMIT;
