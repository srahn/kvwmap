BEGIN;
-- requested for BLP-Server due to type (typo exists in the 5.4 objektartenkatalog, but is fixed in version xplan 6+)
UPDATE xplan_gml.enum_bp_planart
SET beschreibung = 'Vorhabenbezogener Bebauungsplan nach §12 BauGB'
WHERE wert = 3000;

COMMIT;
