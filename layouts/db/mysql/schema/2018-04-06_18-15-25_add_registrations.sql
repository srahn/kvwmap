BEGIN;

CREATE TABLE `registrations` (
	`name` varchar(255) NOT NULL,
	`vorname` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`token` varchar(255) NOT NULL,
	`stelle_id` int(11),
	`inviter_id` int(11),
  `completed` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `registrations` ADD PRIMARY KEY( `email`, `token`, `stelle_id`);

COMMIT;
