BEGIN;
	ALTER TABLE `notifications` ADD `user_filter` text NULL AFTER `stellen_filter`;
COMMIT;