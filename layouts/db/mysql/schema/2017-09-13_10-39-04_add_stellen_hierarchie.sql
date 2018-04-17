BEGIN;

CREATE TABLE `stellen_hierarchie` (
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `child_id` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY(`parent_id`, `child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

COMMIT;
