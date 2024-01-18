BEGIN;
  --ALTER TABLE `user2notifications` ADD PRIMARY KEY(`notification_id`, `user_id`); -- Erzeugt ein Fehler. Ist wohl doch schon bei den meisten dabei Key
COMMIT;