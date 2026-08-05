BEGIN;

  INSERT INTO grstsich.cl_rechtearten (bezeichnung) VALUES
    ('Leitungsrecht'),
    ('Überflugrecht');


  INSERT INTO grstsich.cl_personenarten (personenart) VALUES
    ('Eigentümer'),
    ('Bewirtschafter'),
    ('Sonstige');

  INSERT INTO grstsich.cl_personstati (personstatus,reihenfolge) VALUES
    ('Lead',NULL),
    ('Contact',NULL),
    ('Opportunity',NULL),
    ('Customer',NULL);

  INSERT INTO grstsich.cl_projektkategorien (bezeichnung) VALUES
    ('WEA-Bebauung');

  INSERT INTO grstsich.cl_projektstati (bezeichnung,beschreibung) VALUES
    ('ALKIS-Daten in Bestellung', 'ALKIS-Daten wurden bestellt. Die Daten werden importiert und der Importjob setzt nach Erfolg auf den nächsten Status'),
    ('Eigentümerdaten vorhanden', 'Eigentümerdaten sind Eingelesen worden und können korrigiert werden.'),
    ('Eigentümerdaten korrigiert', 'Eigentümerdatenkorrektur wurde durchgeführt. Die Aquise und Freigabe kann beginnen.'),
    ('Abbruch', NULL),
    ('Teilprojekt generiert', 'Polygon erstellt'),
    ('Teilprojekt prüffähig', 'Wird in CRM übernommen und weiter bearbeitet. Fläche wird geprüft.'),
    ('Teilprojekt geeignet', 'Gebiet hat potential Eigentümerdaten können bestellt werden.');

  INSERT INTO grstsich.cl_quellen (bezeichnung) VALUES
    ('Weißflächenberechnung im WebGIS'),
    ('Manuell in QGIS');

  INSERT INTO grstsich.cl_sicherungsstaende (id, bezeichnung) VALUES
    (0, 'Nicht gesichert'),
    (1, 'In Prüfung'),
    (2, 'Gesichert');
  ALTER SEQUENCE grstsich.cl_sicherungsstaende_id_seq RESTART 2;

COMMIT;