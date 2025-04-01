BEGIN;
UPDATE xplan_gml.so_planart
SET value = 'Sanierungssatzung(SAS)'
WHERE id = '1100';
UPDATE xplan_gml.so_planart
SET value = 'Sonstige Bausatzung(SOS)'
WHERE id = '9999';
INSERT INTO
	xplan_gml.so_planart (codespace, id, value)
SELECT
	'http://bauleitplaene-mv.de/codelist/SO_PlanArt/SO_PlanArt.xml' AS codespace,
	'1999' AS id,
	'Erhaltungssatzung(SOS)' AS value
WHERE
	NOT EXISTS (
		SELECT id FROM xplan_gml.so_planart WHERE id = '1999'
	);
COMMIT;