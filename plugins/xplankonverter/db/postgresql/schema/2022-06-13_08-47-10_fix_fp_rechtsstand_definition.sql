BEGIN;
/* in Xplanung up until 6.0, the definition of 2100 for fp_rechtsstand is wrong.*/
UPDATE xplan_gml.enum_fp_rechtsstand
SET beschreibung = 'Die frühzeitige Beteiligung der Behörden wird durchgeführt.'
WHERE wert = 2100;
COMMIT;