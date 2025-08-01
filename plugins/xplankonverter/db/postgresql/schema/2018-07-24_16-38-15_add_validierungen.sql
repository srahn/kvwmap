BEGIN;

SELECT setval('xplankonverter.validierungen_id_seq', (SELECT max(id) FROM xplankonverter.validierungen));

INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Regel-XPlan-Wertespektrum-Prüfung', 'Prüft ob Attributwerte den Wertespektren des Attributtyps (z.B. Enumeration, CharacterString, Enumeration) entsprechen.', 'NULL', 'Alle Attribute sind im korrekten Wertespektrum', 'NULL', 'Attribut XYZ ist nicht im korrekten Wertespektrum', 'Geben Sie für Attribut XYZ der Klasse XYZ (zugewiesen in Regel XYZ) einen erlaubten Wert an.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Regel-XPlan-Wertespektrum-Prüfung'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Korrekte Geometriefläche: Bei Aufteilung einer Fläche in Flächenstücke (Patches) sind die Patches frei von Überlappungen und zusammenhängend', 'Aus Konformitätsbedingung 2.2.2', 'NULL', 'Mögliche Patches sind frei von Überlappung und zusammenhängend', 'NULL', 'Patches der Flächengeometrie in der Klasse XYZ aus Shape XYZ überlappen', 'Stellen Sie sicher, dass Patches der Geometrie von Shape XYZ/Klasse XYZ nicht überlappen.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Korrekte Geometriefläche: Bei Aufteilung einer Fläche in Flächenstücke (Patches) sind die Patches frei von Überlappungen und zusammenhängend'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Geometrie innerhalb des Plan-Geltungsbereichs', 'Prüft, ob die Geometrien von Shapes/Klassen ausserhalb des Geltungsbereichs eines Plans liegen (Konformitätsbedingung 2.2.3)', 'geom_within_plan', 'Geometrien innerhalb von Plan-Geltungsbereich', 'Nicht erfüllt', 'Nicht erfüllt', 'Stellen Sie sicher, dass Geometrien von Klassen innerhalb des Geltungsbereichs des Plans liegen'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Geometrie innerhalb des Plan-Geltungsbereichs'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Korrekte Geometriefläche: Der erste und der letzte Flächenstützpunkt sind identisch', 'Aus Konformitätsbedingung 2.2.2', 'NULL', 'Erste und letzte Flächenstützpunkte sind identisch', 'NULL', 'Erste und letzte Flächenstützpunkte der Flächengeometrie für Klasse XYZ aus Shape XYZ sind nicht identisch', 'Stellen Sie sicher, dass erste und letzte Flächenstützpunkte der Geometrie von Shape XYZ/Klasse XYZ nicht identisch sind'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Korrekte Geometriefläche: Der erste und der letzte Flächenstützpunkt sind identisch'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Korrekte Geometriefläche: Die Flächen haben einen korrekten Umlaufsinn, d.h.: im Gegen-Uhrzeigersinn bei Aussenkonturen, im Uhrzeigersinn bei Inselflächen', 'Aus Konformitätsbedingung 2.2.2', 'NULL', 'Alle Flächen haben einen korrekten Umlaufsinn', 'NULL', 'Flächengeometrie der Klasse XYZ aus Shape XYZ hat keinen korrekten Umlauf', 'Stellen Sie sicher, dass die Geometrie von Shape XYZ/Klasse XYZ einen korrekten Umlauf hat'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Korrekte Geometriefläche: Die Flächen haben einen korrekten Umlaufsinn, d.h.: im Gegen-Uhrzeigersinn bei Aussenkonturen, im Uhrzeigersinn bei Inselflächen'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Shape-File enthält Daten', 'Es wird geprüft ob die Shape-Files Datensätze enthalten', 'NULL', 'Alle Shapes enthalten Daten', 'Shape-Datei XYZ enthält keine Daten', 'NULL', 'Stellen Sie sicher, dass Shape-Datei XYZ Daten enthält (SHX, SHP & DBF)'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Shape-File enthält Daten'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Regel-Shape-Attribut Prüfung', 'Prüft, ob in der Regel verwendete Shape-Attribute vorhanden sind', 'NULL', 'Shape-Attribute vorhanden', 'NULL', 'Shape-Attribut XYZ in Shape-XYZ in Regel-zeile XYZ existiert nicht', 'Überprüfen Sie ihre Shape-Attribut-Struktur und passen Sie die Regeln an'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Regel-Shape-Attribut Prüfung'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Regel-XPlan-Attribute-Prüfung', 'Prüft, ob in der Regel verwendete XPlan-Attribute existieren', 'NULL', 'XPlan-Attribute vorhanden', 'NULL', 'XPlan-Attribut XYZ in Zeile XYZ der Regel XYZ existiert nicht', 'Überprüfen Sie die XPlan-Attribute der Regel XYZ und passen Sie die Regel an'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Regel-XPlan-Attribute-Prüfung'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Regel-Pflichtattribute', 'Prüft, ob die in der Regel verwendete Zuordnung die Pflichtattribute position (Geometrie), rechtscharakter und, bei zentralen Orten, ZentralerOrtTyp, korrekt befüllt', 'NULL', 'Pflichtattribute befüllt', 'NULL', 'Das Pflichtattribut XYZ muss für die Klasse XYZ befüllt werden', 'Befüllen Sie das Pflichtattribut XYZ in Regeln XYZ'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Regel-Pflichtattribute'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Regel-Externe Referenzen und Codelisten-Test', 'Prüft, ob angegebene URLs in der Regel aufrufbar sind. Gibt eine Warnung, aber keinen Fehler aus', 'NULL', 'URLs vorhanden', 'URL XYZ ist nicht vorhanden!', 'NULL', 'Hinterlegen Sie eine existierende URL für Attribut XYZ'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Regel-Externe Referenzen und Codelisten-Test'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Doppelte Klassen', 'Es wird geprüft, ob Klassen doppelt vorkommen (z.B. weil eine Regel 2x verwendet wurde)', 'NULL', 'Klassen kommen nicht doppelt vor', 'Klasse XYZ ist mehrfach vorhanden', 'NULL', 'Überprüfen Sie Ihre Regeln, ob Klasse XYZ mehrfach zugewiesen wird.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Doppelte Klassen'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Korrekte Geometriefläche: Keine doppelten Stützpunkte', 'Aus Konformitätsbedingung 2.2.2', 'NULL', 'Keine doppelten Stützpunkte von Geometrieflächen vorhanden', 'NULL', 'Flächengeometrie der Klasse XYZ aus Shape XYZ hat doppelte Stützpunkte', 'Stellen Sie sicher, dass die Geometrie von Shape XYZ/Klasse XYZ keine doppelten Stützpunkte hat'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Korrekte Geometriefläche: Keine doppelten Stützpunkte'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Koordinaten liegen im Koordinatensystem', 'Es wird geprüft ob alle Koordinaten im richtigen Koordinatensystem liegen', 'NULL', 'Alle Koordinaten liegen im korrekten Koordinatensystem', 'Die Koordinaten entsprechen nicht dem Referenzsystem EPSG: XYZ', 'NULL', 'Shape-Datei XYZ muss in das richtige Koordinatensystem konvertiert und neu hochgeladen werden'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Koordinaten liegen im Koordinatensystem'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Korrekte Geometriefläche: Keine Selbst-Überschneidung oder Berührung von Linien- oder Kreisbogensegmenten', 'Aus Konformitätsbedingung 2.2.2', 'NULL', 'Keine Selbst-überschneidung oder Berührung von Linien- oder Kreisbogensegmenten', 'NULL', 'Flächengeometrie der Klasse XYZ aus Shape XYZ hat Selbstüberschneidung oder Berührung von Linien- oder Kreisbogensegmenten', 'Stellen Sie sicher, dass die Geometrie von Shape XYZ/Klasse XYZ keine Selbstüberschneidung oder Berührung von Linien- oder Kreisbogensegmenten hat'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Korrekte Geometriefläche: Keine Selbst-Überschneidung oder Berührung von Linien- oder Kreisbogensegmenten'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Bereich-Geltungsbereich ist, falls vorhanden, kleiner oder gleich dem Geltungsbereichs des Plans', 'Der Geltungsbereich eines Bereichs muss, falls vorhanden, immer kleiner oder gleich dem Geltungsbereich des Plans sein.', 'NULL', 'Geltungsbereiche von Bereichen kleiner oder gleich Geltungsbereich von Plan', 'Geltungsbereich von Bereich XYZ ist größer als Geltungsbereich des Plans', 'NULL', 'Passen Sie den Geltungsbereich des Bereichs XYZ an, damit dieser nicht größer als der Geltungsbereich des Plans ist.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Bereich-Geltungsbereich ist, falls vorhanden, kleiner oder gleich dem Geltungsbereichs des Plans'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Überprüfung Datumsangaben in Formularen für Pläne und Bereiche', 'Es wird überprüft, ob Datumseingaben für Pläne und Bereiche möglicherweise falsche Eingaben enthalten, z.B. keine Daten vor 1945 und keine Daten nach 2025.', 'NULL', 'Korrekte Datumseingaben', 'Datumseingabe für Attribut XYZ der Klasse XYZ ist ausserhalb des wahrscheinlichen Wertebereichs', 'NULL', 'Überprüfen Sie die Datumseingabe für Attribut XYZ der Klasse XYZ'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Überprüfung Datumsangaben in Formularen für Pläne und Bereiche'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Geometrien sind innerhalb des Bereich-Geltungsbereichs', 'Prüft, ob die Geometrien von Shapes/Klassen ausserhalb des Geltungsbereichs des referenzierten Bereichs liegen (Konformitätsbedingung 2.2.3)', 'geom_within_bereich', 'Geometrien innerhalb von Bereich-Geltungsbereich', 'NULL', 'Geometrie von Shape XYZ liegt (teilweise) außerhalb des Geltungsbereichs des assoziierten Bereichs', 'Stellen Sie sicher, dass Geometrien von Klassen innerhalb des Geltungsbereichs des Bereichs liegen'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Geometrien sind innerhalb des Bereich-Geltungsbereichs'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Regeln vorhanden', 'Es wird überprüft ob mindestens eine Regel für die Konvertierung besteht.', 'regel_existiert', 'Es ist mindestens eine Regel für die Konvertierung angegeben.', 'Es sind keine Regeln für die Konvertierung angegeben.', 'NULL', 'Wenn keine Regeln angegeben sind, werden keine Planobjekte angelegt, sondern nur der Plan und falls vorhanden Bereiche. Um XPlan-Objekte für einen Plan erzeugen zu können muss mindesten eine Regel auf Plan oder auf Bereichsebene definiert sein.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Regeln vorhanden'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Regel ausführbar', 'Es wird geprüft ob das SQL einer Regel ausführbar ist oder Fehlermeldungen in der Datenbank erzeugt.', 'sql_ausfuehrbar', 'NULL', 'NULL', 'Die Ausführung der Regel ist fehlgeschlagen.', 'Korrigieren Sie das SQL-Statement der Regel.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Regel ausführbar'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Alle Regeln ausführbar', 'Es wird geprüft ob alle Regeln ausführbar waren.', 'alle_sql_ausfuehrbar', 'Alle Regeln konnten ausgeführt werden.', 'NULL', 'Wenn diese Validierung fehltgeschlagen ist, werden statt dessen einzelne Fehlermeldungen für jede einzelne Regel ausgegeben.', 'NULL'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Alle Regeln ausführbar'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Regel vorhanden', 'Es wird geprüft ob das SQL einer Regel vorhanden ist.', 'sql_vorhanden', 'Die Regeln hat ein SQL-Statement', 'NULL', 'Die Regel hat kein SQL-Statement', 'Bitte fügen Sie ein SQL-Statement zur Regel hinzu'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Regel vorhanden'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Geometrie ist vorhanden', 'Es wird geprüft ob für die Objekte Geometrien vorhanden sind.', 'geometrie_vorhanden', 'Geometrie ist vorhanden', 'Keine Geometrie vorhanden', 'NULL', 'Bitte erzeugen Sie für das Objekt eine Geometrie. Sie können die Geometrie entweder hier im Geometrieeditor erzeugen oder Sie ändern Ihre Shape-Datei und laden diese neu hoch.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Geometrie ist vorhanden'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Geometrie ist valide', 'Es wird geprüft ob die Geometrie valide ist.', 'geometrie_isvalid', 'Geometrie ist valide', 'NULL', 'NULL', 'Korrigieren Sie die Geometrie.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Geometrie ist valide'
);
INSERT INTO xplankonverter.validierungen (name, beschreibung, functionsname, msg_success, msg_warning, msg_error, msg_correcture)
SELECT 'Konsistenz der Attribute bedeutung und detaillierteBedeutung in XP_Bereich', 'Wenn das Attribut detaillierteBedeutung belegt ist, muss auch das Attribut bedeutung
belegt sein.', 'detaillierte_requires_bedeutung', 'Die detaillierte Bedeutung des Bereichs hat auch eine Angabe in Bedeutung.', 'NULL', 'NULL', 'Geben Sie eine Bedeutung für den Bereich an oder Löschen die detaillierte Beeutung des Bereiches.'
WHERE NOT EXISTS (
	SELECT * FROM xplankonverter.validierungen WHERE name = 'Konsistenz der Attribute bedeutung und detaillierteBedeutung in XP_Bereich'
);

COMMIT;
