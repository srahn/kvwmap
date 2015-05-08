BEGIN;

ALTER TABLE  `rolle_last_query` CHANGE  `sql`  `sql` LONGTEXT NOT NULL;

COMMIT;
