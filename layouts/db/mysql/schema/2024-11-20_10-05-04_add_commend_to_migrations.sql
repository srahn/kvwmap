BEGIN;

  ALTER TABLE `migrations` ADD COLUMN `comment` TEXT;

COMMIT;