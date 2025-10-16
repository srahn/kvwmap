BEGIN;

  ALTER TABLE kvwmap.layer
    ADD COLUMN ows_publication boolean,
    ADD COLUMN ows_mapfile_name varchar,
    ADD COLUMN ows_wrapper_name varchar,
    ADD COLUMN ows_stelle_id integer;

COMMIT;