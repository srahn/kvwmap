BEGIN;

  CREATE TABLE IF NOT EXISTS xplankonverter."konvertierungsstati" (
    id serial NOT NULL PRIMARY KEY,
    total_status text NOT NULL,
    import_status text NOT NULL,
    editiersperre boolean
  );

  INSERT INTO xplankonverter."konvertierungsstati" (total_status, import_status, editiersperre) VALUES
	('in Erstellung', 'beim Hochladen', true),
	('erstellt', 'beim Hochladen', true),
	('Angaben vollständig', 'beim Hochladen', true),
	('Planupload abgebrochen', 'Fehler beim Hochladen', false),
	('Plan hochgeladen', 'Plan erfolgreich hochgeladen', false),
	('Planimport abgebrochen', 'Fehler beim Hochladen', false),
	('Plan importiert', 'Plan erfolgreich hochgeladen', true),
  ('Validierung abgebrochen', 'Fehler beim Hochladen', false),
  ('Plan validiert', 'Plan erfolgreich hochgeladen', true),
	('Reindizierung abgebrochen', 'Fehler beim Import', false),
	('Plan reindiziert', 'Plan erfolgreich hochgeladen', true),
	('Import reindizierter Plan abgebrochen', 'Fehler beim Import', false),
	('Reindizierter Plan importiert', 'Plan erfolgreich hochgeladen', true),
	('Validierung importierter Plan abgebrochen', 'Fehler beim Import', false),
	('Importierter Plan validiert', 'Plan erfolgreich hochgeladen', true),
	('Planerzeugung abgebrochen', 'Fehler beim Import', false),
	('Plan angelegt', 'Plan erfolgreich hochgeladen', true),
  ('Planerzeugung abgeschlossen', 'Plan erfolgreich hochgeladen', true),
	('Überprüfung der Klassifizierung abgebrochen', 'Fehler beim Import', false),
	('Klassifizierung überprüft', 'Import erfolgreich abgeschlossen', true),
	('Erzeugung von Metadaten abgebrochen', 'Fehler beim Import', false),
	('Metadaten erzeugt', 'Plan erfolgreich hochgeladen', true),
	('Erzeugung Geowebservice abgebrochen', 'Fehler beim Import', false),
	('Geowebservice erzeugt', 'Plan erfolgreich hochgeladen', true),
	('Originalplan zugeordnet', 'Plan erfolgreich hochgeladen', false),
	('Importjob angelegt', 'Plan erfolgreich hochgeladen', true),
	('in Konvertierung', 'Plan erfolgreich hochgeladen', true),
	('Konvertierung abgeschlossen', 'Plan erfolgreich hochgeladen', true),
	('Konvertierung abgebrochen', 'Fehler beim Import', false),
	('in GML-Erstellung', 'Plan erfolgreich hochgeladen', true),
	('GML-Erstellung abgeschlossen', 'Import erfolgreich abgeschlossen', true),
	('GML-Erstellung abgebrochen', 'Fehler beim Import', false),
	('INSPIRE-GML-Erstellung abgeschlossen', 'Import erfolgreich abgeschlossen', true),
	('INSPIRE-GML-Erstellung abgebrochen', 'Fehler beim Import', false),
	('in INSPIRE-GML-Erstellung', 'Plan erfolgreich hochgeladen', true),
	('GML-Validierung abgeschlossen', 'Import erfolgreich abgeschlossen', true),
	('GML-Validierung mit Fehlern', 'Fehler beim Import', false);
COMMIT;