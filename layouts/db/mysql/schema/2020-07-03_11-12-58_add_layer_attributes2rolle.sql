BEGIN;

  ALTER TABLE `layer_attributes` ENGINE=INNODB;
  ALTER TABLE `layer_attributes2stelle` ENGINE=INNODB;

  CREATE TABLE `layer_attributes2rolle` (
    `layer_id` int(11) NOT NULL,
    `attributename` varchar(255) NOT NULL,
    `stelle_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `switchable` tinyint(1) NOT NULL DEFAULT 1,
    `switched_on` tinyint(1) NOT NULL DEFAULT 1,
    `sortable` tinyint(1) NOT NULL DEFAULT 1,
    `sort_order` int(11) NOT NULL DEFAULT 1,
    `sort_direction` enum('asc', 'desc') NOT NULL DEFAULT 'asc'
  ) ENGINE=INNODB DEFAULT CHARSET=utf8;

  ALTER TABLE `layer_attributes2rolle` ADD PRIMARY KEY (`layer_id`, `attributename`, `stelle_id`, `user_id`);
  ALTER TABLE `layer_attributes2rolle` ADD FOREIGN KEY (`layer_id`, `attributename`, `stelle_id`) REFERENCES `layer_attributes2stelle` (`layer_id`, `attributename`, `stelle_id`) ON DELETE CASCADE;
  ALTER TABLE `layer_attributes2rolle` ADD FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE;

COMMIT;
