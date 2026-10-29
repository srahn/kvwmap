BEGIN;

CREATE INDEX rolle_stelle_id_idx
ON kvwmap.rolle (stelle_id);

COMMIT;
