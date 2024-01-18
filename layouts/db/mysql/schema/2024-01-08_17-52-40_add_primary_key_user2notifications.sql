BEGIN;
  ALTER TABLE `user2notifications` ADD PRIMARY KEY(`notification_id`, `user_id`);
COMMIT;