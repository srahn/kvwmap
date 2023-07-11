BEGIN;

ALTER TABLE `datatypes`
  DROP `dbname`,
  DROP `host`,
  DROP `port`;

COMMIT;
