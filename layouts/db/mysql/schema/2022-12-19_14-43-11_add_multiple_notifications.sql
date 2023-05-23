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

	AlTER TABLE user2notifications
	ADD CONSTRAINT notification_id_fk 
	FOREIGN KEY(notification_id)
	REFERENCES notifications(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE;

	AlTER TABLE user2notifications
	ADD CONSTRAINT notification_user_id_fk
	FOREIGN KEY(user_id)
	REFERENCES user(ID)
	ON DELETE CASCADE
	ON UPDATE CASCADE;
COMMIT;
