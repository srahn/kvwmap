BEGIN;

UPDATE 
  kvwmap.ddl_elemente
SET
  border = 1
WHERE
  name like '%geom' and (font = '' or font is null) and width is not null;

COMMIT;
