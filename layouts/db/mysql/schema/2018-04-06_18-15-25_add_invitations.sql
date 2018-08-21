BEGIN;

CREATE TABLE `invitations` (
	`token` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`stelle_id` int(11),
	`name` varchar(255) NOT NULL,
	`vorname` varchar(255) NOT NULL,
	`inviter_id` int(11),
  `completed` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `invitations` ADD PRIMARY KEY( `token`, `email`, `stelle_id`);

COMMIT;
