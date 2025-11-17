BEGIN;
ALTER TABLE xplan_gml.bp_plan
ALTER COLUMN verlaengerungveraenderungssperre
DROP DEFAULT;
COMMIT;