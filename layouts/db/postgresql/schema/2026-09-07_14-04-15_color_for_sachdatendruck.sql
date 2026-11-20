BEGIN;
  ALTER TABLE kvwmap.druckfreilinien ADD COLUMN linecolor integer;
  ALTER TABLE kvwmap.druckfreirechtecke RENAME COLUMN color TO bgrcolor;
  ALTER TABLE kvwmap.druckfreirechtecke ADD COLUMN linecolor integer;
COMMIT;