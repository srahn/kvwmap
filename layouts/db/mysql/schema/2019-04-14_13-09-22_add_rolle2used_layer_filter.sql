BEGIN;

	ALTER TABLE `u_rolle2used_layer` ADD `rollenfilter` text;
	ALTER TABLE `rollenlayer` ADD `rollenfilter` text;

COMMIT;
