BEGIN;

	CREATE TABLE `notifications` (
		id int PRIMARY KEY AUTO_INCREMENT,
		notification text,
		stellen_filter text,
		veroeffentlichungsdatum date,
		ablaufdatum date
	);

	CREATE TABLE `user2notifications` (
		notification_id int,
		user_id int
	);

COMMIT;
