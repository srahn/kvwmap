CREATE TABLE fortfuehrungslisten.aa_anlassart
(
  code character varying,
  name text,
  modellarten character varying,
  status character varying
)
WITH (
  OIDS=FALSE
);

INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('000000', 'Ersteinrichtung', 'alle', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010307', 'Eintragung des Flurstückes', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010308', 'Löschen des Flurstückes', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010309', 'Veränderung der Gemeindezugehörigkeit einzelner Flurstücke', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010310', 'Veränderung der Gemeindezugehörigkeit', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010312', 'Veränderung der Flurstücksnummer ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010316', 'Flurstücksbestimmung gemäß § 12 Abs.2 Satz 2 VermGeoG LSA', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010317', 'Gebäudeeinmessung nach § 14 Abs. 2 Satz 2 VermGeoG LSA', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010400', 'Veränderung der Beschreibung des Flurstücks', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010401', 'Veränderung der besonderen Flurstücksgrenze', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010402', 'Veränderung der Lage', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010403', 'Veränderung der tatsächlichen Nutzung mit Änderung der Wirtschaftsart', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010404', 'Veränderung des Anliegervermerks', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010405', 'Veränderung der tatsächlichen Nutzung ohne Änderung der Wirtschaftsart ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010500', 'Berichtigung der Flurstücksangaben', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010501', 'Berichtigung der Flächenangabe', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010502', 'Berichtigung eines Zeichenfehlers', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010503', 'Berichtigung eines Katastrierungsfehlers', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300420', 'Veränderung der Bodenschätzung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010511', 'Berichtigung der Flächenangabe mit Veränderung des Flurstückskennzeichens', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('050300', 'Berichtigung aufgrund Erwerbsvorgängen außerhalb des Grundbuchs', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060000', 'Grundstücke buchen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060100', 'Abschreibung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060101', 'Abschreibung auf neues Buchungsblatt', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010600', 'Bodenordnungsmaßnahmen ', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010601', 'Verfahren nach dem Flurbereinigungsgesetz', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010602', 'Verfahren nach dem Baugesetzbuch', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010611', 'Flurbereinigung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010612', 'Flurbereinigung-freiwilliger Landtausch', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010613', 'Änderung auf Grund des Landwirtschaftsanpassungsgesetzes', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010614', 'Änderung auf Grund des Eisenbahnneuordnungsgesetzes', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010615', 'Übernahme von wichtigen Terminen im Ablauf eines Bodenordnungsverfahrens ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010616', 'Vermessung der Verfahrensgrenze des Flurbereinigungsgebietes', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010619', 'Übernahme von Flurbereinigungsergebnissen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010621', 'Umlegung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010622', 'Umlegung nach § 76 BauGB', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010623', 'Vereinfachte Umlegung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010700', 'Katastererneuerung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010701', 'Katastererneuerung - vereinfachte Neuvermessung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010702', 'Erneuerung der Lagekoordinaten ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010703', 'Veränderung aufgrund der Qualitätsverbesserung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010704', 'Qualitätssicherung und Datenpflege', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010801', 'Zuschreibung eines Flurstückes (Gebietsreform)', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010802', 'Abschreibung eines Flurstückes (Gebietsreform)', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010900', 'Grenzfeststellung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010901', 'Grenzvermessung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010902', 'Grenzwiederherstellung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010903', 'Grenzbestimmung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010904', 'Grenzabmarkung ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020000', 'Verzeichnisse außerhalb des Grundbuches fortführen', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020100', 'Katasterliche Buchungsdaten fortführen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020101', 'Katasterliche Buchung eines noch nicht im Grundbuch gebuchten Grundstücks oder Rechts (Erwerber- oder Pseudoblatt) (2)', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020102', 'Katasterliche Buchung eines buchungsfreien Grundstücks', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020200', 'Namensnummer von katasterlichen Buchungsstellen verändern', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020201', 'Katasterliche Namensnummer und Rechtsgemeinschaft fortführen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020300', 'Katasterliche Personendaten fortführen', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020301', 'Veränderung der Personendaten', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020302', 'Veränderung der Personengruppe', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020303', 'Veränderung der Anschrift aufgrund katasterlicher Erhebung (2)', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020304', 'Veränderung der Verwaltung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('020305', 'Veränderung der Vertretung ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('030000', 'Grundbuchblattbezeichnung ändern', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('030100', 'Umnummerierung (infolge Zuständigkeitsänderungen am Grundbuch)', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('040000', 'Beschreibung der Buchungsstelle ändern', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('040100', 'Änderungen am Wohnungseigentum', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('040200', 'Änderungen am Wohnungserbbaurecht', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('040300', 'Änderungen am Wohnungsuntererbbaurecht', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('040500', 'Änderungen an den Beziehungen zwischen den Buchungsstellen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('040600', 'Änderungen in der Ergänzung, Beschreibung sowie der Bemerkung zu Buchungen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('050000', 'Angaben zu Eigentümer oder Erbbauberechtigten verändern', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('050100', 'Eigentumsänderung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('050101', 'Kompletter Eigentumswechsel im Grundbuchblatt', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('050102', 'Teilweiser Eigentumswechsel im Grundbuchblatt', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('050200', 'Berichtigung aufgrund Erbnachweis', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060102', 'Abschreibung auf bestehendes Buchungsblatt', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060200', 'Teilung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060201', 'Buchung der neuen Grundstücke in einem bestehenden Buchungsblatt', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060400', 'Vereinigung (§ 890 I BGB, § 5 GBO)', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060500', 'Bestandteilszuschreibung (§ 890 II BGB, § 6 GBO)', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060600', 'Vollziehung einer Verschmelzung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060700', 'Buchung aufgrund Veränderung der Grundstücksbezeichnung oder der Größe (Spalten 3 + 4 des BV)', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060800', 'Buchung nach § 3 Abs.4 GBO aufheben', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060801', 'Buchung des Grundstücks auf ein neues Buchungsblatt (1)', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060802', 'Buchung des Grundstücks auf ein bestehendes Buchungsblatt der Miteigentümer', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060900', 'Aufhebung eines Wohnungseigentums', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('062000', 'Grundbuchblatt schließen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070000', 'Rechte buchen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070100', 'Erbbaurecht anlegen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070200', 'Erbbaurecht aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070300', 'Herrschvermerk buchen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070400', 'Herrschvermerk aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070500', 'Untererbbaurecht anlegen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070600', 'Untererbbaurecht aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070700', 'Sonstige Rechte anlegen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070800', 'Sonstige Rechte aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('070900', 'Wohnungserbbaurecht aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('071000', 'Wohnungsuntererbbaurecht aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('071100', 'Teilung Herrschvermerk aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('071200', 'Teilung sonstiges Recht aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080000', 'Anteile buchen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080100', 'Buchung nach § 3 Abs.4 GBO', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080200', 'Anlegen von Wohnungseigentum', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080300', 'Anlegen von Wohnungserbbaurecht', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080400', 'Anlegen von Wohnungsuntererbbaurecht', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080500', 'Teilung eines Herrschvermerks', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080600', 'Teilung eines sonstigen Rechts', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080650', 'Auflösung ungetrennter Hofräume', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080700', 'Teilung am ungetrennten Hofraum aufheben', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080800', 'Teilung einer Buchung § 3 Abs. 4 GBO nach Wohnungseigentumsgesetz', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('080900', 'Teilung eines Anteils am ungetrennten Hofraum nach Wohnungseigentumsgesetz', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090000', 'Ändern in Verzeichnissen ohne Grundbucheintragung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090100', 'Verwaltung eintragen oder ändern', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090200', 'Personengruppe eintragen oder ändern', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090300', 'Änderung der Anschrift', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090400', 'Änderung der Personendaten', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090500', 'Entstehung eines Festpunkts (erstmalige Aufnahme in AFIS)', 'DFGM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090510', 'Änderung von Koordinaten, Höhe und/oder Schwerewert eines Festpunkts', 'DFGM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090520', 'Veränderung der Vermarkung und/oder der beschreibenden Angaben eines Festpunkts', 'DFGM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090530', 'Zerstörung der Vermarkung eines Festpunkts', 'DFGM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('090540', 'Untergang eines Festpunktes', 'DFGM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('001000', 'Grundaktualisierung', 'Basis-DLM
DTK10
DTK25
DLM50
DLM250
DLM1000', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('100000', 'Veränderung der Angaben zu den Nutzerprofilen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('002000', 'Spitzenaktualisierung', 'Basis-DLM
DTK10
DTK25
DLM50
DLM250
DLM1000', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('200000', 'Veränderung von Gebäudedaten', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('040400', 'Buchung § 3 Abs.4 ff GBO ändern', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('200100', 'Eintragen eines Gebäudes', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('200200', 'Veränderung der Gebäudeeigenschaften', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('200300', 'Löschen eines Gebäudes', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300000', 'Sonstige Daten fortführen', 'DLKM
Basis-DLM
DLM50
DLM250
DLM1000
DHM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300100', 'Veränderungen der Angaben zum Netzpunkt', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300200', 'Veränderung der Angaben zum Objektartenbereich ''Bauwerke, Einrichtungen und sonstigen Angaben''', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300300', 'Veränderung der Angaben zum Objektartenbereich ''Tatsächlichen Nutzung''', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300400', 'Veränderung der Angaben zum Objektartenbereich ''Gesetzliche Festlegungen, Gebietseinheiten, Kataloge''', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300410', 'Veränderung der öffentlich-rechtlichen und sonstigen Festsetzungen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300421', 'Erfassung der Bodenschätzung ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300430', 'Veränderung der Bewertung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300440', 'Veränderung der Gebietseinheiten', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300500', 'Veränderung der Geometrie auf Grund der Homogenisierung', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300501', 'Veränderung aufgrund der Homogenisierung ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300600', 'Veränderung der Reservierung von Fachkennzeichen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300700', 'Veränderung von Katalogeinträgen ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300800', 'Veränderung von Metadaten', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('300900', 'Veränderung der Geometrie durch Implizitbehandlung', 'DLKM
DFGM
Basis-DLM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('004000', 'Automatische Kartographische Generalisierung', 'DTK10
DTK25
DTK50
DTK100
DTK250
DTK1000', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('004100', 'Automatische Kartographische Generalisierung mit Konflikt', 'DTK10
DTK25
DTK50
DTK100
DTK250
DTK1000', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('005000', 'Interaktive Kartographische Generalisierung', 'Basis-DLM
DTK10
DTK25
DTK50
DTK100
DTK250
DTK1000', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('006000', 'Veränderung an der Landesgrenze und des Gebietes', 'DLKM
Basis-DLM
DTK10
DTK25
DLM50
DTK50
DTK100
DLM250
DTK250
DLM1000
DTK1000', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('009999', 'Sonstiges', 'DFGM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('003000', 'Angaben zur Grundbuchfortführung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010000', 'Flurstücksdaten fortführen', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010100', 'Veränderungen am Flurstück ohne Änderung der Umfangsgrenzen des Grundstücks', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010101', 'Zerlegung oder Sonderung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010102', 'Verschmelzung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010103', 'Zerlegung und Verschmelzung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010104', 'Flurstückszerlegung mit Eigentumsübergang nach Straßengesetzen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010105', 'Zerlegung ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010107', 'Sonderung ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010199', 'Verschmelzung von Flurstücken auf unterschiedlichen Beständen/Buchungsstellen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010200', 'Veränderung am Flurstück mit Änderung der Umfangsgrenzen des Grundstücks', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010201', 'Veränderung aufgrund der Vorschriften des Straßenrechts', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010202', 'Veränderung aufgrund der Vorschriften des Wasserrechts', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010203', 'Veränderung am Flurstück mit Änderung der Umfangsgrenzen ', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010205', 'Veränderung aufgrund Berichtigung eines Aufnahmefehlers', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010206', 'Veränderung aufgrund gerichtlicher Entscheidung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010207', 'Veränderung aufgrund Berichtigung eines Grenzbestimmungsfehlers', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010300', 'Veränderung der Bezeichnung oder der Zugehörigkeit des Flurstücks', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010301', 'Veränderung der Flurstücksbezeichnung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010302', 'Veränderung der Gemarkungszugehörigkeit (1)', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010303', 'Veränderung der Gemeindezugehörigkeit ganzer Gemarkungen', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010304', 'Übernahme von Flurstücken eines anderen Katasteramtes', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010305', 'Veränderung der Flurzugehörigkeit', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('010306', 'Abgabe von Flurstücken an ein anderes Katasteramt', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('050400', 'Namensänderung', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060901', 'Buchung des Grundstücks auf ein neues Buchungsblatt (2)', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('060902', 'Buchung des Grundstücks auf ein bestehendes Buchungsblatt', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('061000', 'Umschreibung des Grundbuchs (§§ 28 ff, 68 GBV)', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('061100', 'Neufassung des Grundbuchs (§§ 33, 69 GBV)', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('061200', 'Erstbuchung eines Grundstücks', 'DLKM', 'Gültig');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('061300', 'Ausbuchung eines Grundstücks nach § 3 (3) GBO', 'DLKM', 'Veraltet');
INSERT INTO fortfuehrungslisten.aa_anlassart (code, name, modellarten, status) VALUES ('061400', 'Aufhebung von Anteilen am ungetrennten Hofraum', 'DLKM', 'Gültig');