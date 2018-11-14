BEGIN;

ALTER TABLE `layer` ADD `document_url` TEXT NULL AFTER `document_path`;

COMMIT;
