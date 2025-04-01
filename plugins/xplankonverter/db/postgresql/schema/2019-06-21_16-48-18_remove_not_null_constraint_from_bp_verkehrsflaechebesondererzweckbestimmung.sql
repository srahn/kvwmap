BEGIN;
ALTER TABLE
    xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung
ALTER COLUMN
    flaechenschluss DROP NOT NULL;
COMMIT;
