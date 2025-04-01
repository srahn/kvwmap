BEGIN;
INSERT INTO xplankonverter.errors(error_id,name,beschreibung)
SELECT 9,'Plangeltungsbereichsfehler','Fehler beim Überprüfen der Plangeltungsbereichsgeometrie. Die Geomtrie weicht mindestens 5% von der Fläche der Gebietseinheit ab.'
WHERE
	NOT EXISTS (
		SELECT error_id FROM xplankonverter.errors WHERE error_id = 9	
	);
COMMIT;
