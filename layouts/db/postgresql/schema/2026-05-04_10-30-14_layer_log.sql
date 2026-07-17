BEGIN;

ALTER TABLE kvwmap.layer ADD created_at timestamp(0) NULL;
ALTER TABLE kvwmap.layer ADD created_by integer NULL;
ALTER TABLE kvwmap.layer ADD edited_at timestamp(0) NULL;
ALTER TABLE kvwmap.layer ADD edited_by integer NULL;

COMMIT;
