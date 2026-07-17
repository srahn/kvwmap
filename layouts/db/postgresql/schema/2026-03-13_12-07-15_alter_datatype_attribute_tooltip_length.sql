BEGIN;
  ALTER TABLE kvwmap.datatype_attributes ALTER COLUMN tooltip TYPE text;
  
COMMIT;