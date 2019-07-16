BEGIN;

  ALTER TABLE layer ADD COLUMN further_attribute_table varchar(255) AFTER maintable;
  ALTER TABLE layer ADD COLUMN id_column varchar(255) AFTER further_attribute_table;

COMMIT;
