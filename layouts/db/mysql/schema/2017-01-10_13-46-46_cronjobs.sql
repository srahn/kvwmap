BEGIN;

CREATE TABLE `cron_jobs` (
	`id` bigint(20) UNSIGNED NOT NULL,
	`bezeichnung` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
	`beschreibung` TEXT,
	`time` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0 6 1 * *',
	`query` text COLLATE utf8_unicode_ci,
	`function` varchar(255) COLLATE utf8_unicode_ci,
	`url` varchar(1000) COLLATE utf8_unicode_ci,
	`user_id` int(11),
	`stelle_id` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `cron_jobs` ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `cron_jobs` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

COMMIT;
