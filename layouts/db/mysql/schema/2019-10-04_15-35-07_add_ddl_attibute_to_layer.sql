BEGIN;

  ALTER TABLE layer ADD `ddl_attribute` varchar(255) AFTER document_url;

COMMIT;
