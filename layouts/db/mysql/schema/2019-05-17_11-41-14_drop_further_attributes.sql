BEGIN;

  ALTER TABLE layer DROP COLUMN further_attribute_table;
  ALTER TABLE layer DROP COLUMN id_column;

COMMIT;