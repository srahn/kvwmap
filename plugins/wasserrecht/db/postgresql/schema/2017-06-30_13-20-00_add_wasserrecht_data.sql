BEGIN;

--INSERT INTO spatial_ref_sys VALUES (35833, 'EPSG', 35833, 'PROJCS["ETRS89/UTM 33N RW+33.000.000 MV",GEOGCS["ETRS89",DATUM["European_Terrestrial_Reference_System_1989",SPHEROID["GRS1980",6378137,298.257222101,AUTHORITY["EPSG","7019"]],AUTHORITY["EPSG","6258"]],PRIMEM["Greenwich",0,AUTHORITY["EPSG","8901"]],UNIT["degree",0.01745329251994328,AUTHORITY["EPSG","9122"]],AUTHORITY["EPSG","4258"]],PROJECTION["Transverse_Mercator"],PARAMETER["latitude_of_origin",0],PARAMETER["central_meridian",15],PARAMETER["scale_factor",0.9996],PARAMETER["false_easting",33500000],PARAMETER["false_northing",0],UNIT["metre",1,AUTHORITY["EPSG","9001"]],AUTHORITY["EPSG","25833"]]', '+proj=tmerc +lat_0=0 +lon_0=15 +k=0.9996 +x_0=33500000 +y_0=0 +ellps=GRS80 +units=m +no_defs towgs84=0,0,0<>');

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO wasserrecht.fiswrv_adresse (id, strasse, hausnummer, plz, ort) VALUES (9001, 'Lübecker Str.', '283', 19059, 'Schwerin');
INSERT INTO wasserrecht.fiswrv_adresse (id, strasse, hausnummer, plz, ort) VALUES (9002, 'Marienplatz', '1', 19053, 'Schwerin');
INSERT INTO wasserrecht.fiswrv_adresse (id, strasse, hausnummer, plz, ort) VALUES (9003, 'Am Wall', '3-5', 18273, 'Güstrow');
INSERT INTO wasserrecht.fiswrv_adresse (id, strasse, hausnummer, plz, ort) VALUES (9004, 'Putlitzer Str.', '25', 19370, 'Parchim');
INSERT INTO wasserrecht.fiswrv_konto (name, iban, bic, bankname, verwendungszweck, personenkonto, kassenzeichen) VALUES ('Testkonto 1', 'DE 124455678990', '123456789', 'Dt. Bundesbank, Filiale Rostock', 'Test Verwendungszweck 1', 'r12345678', '12345');
INSERT INTO wasserrecht.fiswrv_konto (name, iban, bic, bankname, verwendungszweck, personenkonto, kassenzeichen) VALUES ('Testkonto 2', 'DE 098765432100', '987654332', 'Sparkasse', 'Test Verwendungszweck 2', 'r98765432', '98765');

INSERT INTO wasserrecht.fiswrv_betriebszustand (name) VALUES ('WFBB');
INSERT INTO wasserrecht.fiswrv_messtischblatt (nummer) VALUES (1445);

INSERT INTO wasserrecht.fiswrv_mengenbestimmung (name) VALUES ('Messung');
INSERT INTO wasserrecht.fiswrv_mengenbestimmung (name) VALUES ('Berechnung');
INSERT INTO wasserrecht.fiswrv_mengenbestimmung (name) VALUES ('Schätzung');

INSERT INTO wasserrecht.fiswrv_behoerde_art(name, abkuerzung) VALUES ('Untere Wasserbehörde', 'UWB');
INSERT INTO wasserrecht.fiswrv_behoerde_art(name, abkuerzung) VALUES ('Staatliches Amt für Landwirtschaft und Umwelt', 'StALU');
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art, adresse, konto) VALUES ('Landrat Landkreis Rostock', 'LR LRO', 1, 9003, 1);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art, adresse, konto) VALUES ('Landrat Ludwigslust-Parchim', 'LR LUP', 1, 9004, 2);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Landrat Mecklenburgische Seenplatte', 'LR MSE', 1);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Landrat Nordwestmecklenburg', 'LR NWM', 1);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Landrat Vorpommern-Greifswald', 'LR VG', 1);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Landrat Vorpommern-Rügen', 'LR VR', 1);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Oberbürgermeister Schwerin', 'OB SN', 1);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Oberbürgermeister Rostock', 'OB HRO', 1);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Staatliches Amt für Landwirtschaft und Umwelt Mittleres Mecklenburg', 'StALU MM', 2);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Staatliches Amt für Landwirtschaft und Umwelt Mecklenburgische Seenplatte', 'StALU MS', 2);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Staatliches Amt für Landwirtschaft und Umwelt Vorpommern', 'StALU VP', 2);
INSERT INTO wasserrecht.fiswrv_behoerde (name, abkuerzung, art) VALUES ('Staatliches Amt für Landwirtschaft und Umwelt Westmecklenburg', 'StALU WM', 2);

INSERT INTO wasserrecht.fiswrv_archivnummer (nummer) VALUES (188);
INSERT INTO wasserrecht.fiswrv_archivnummer (nummer) VALUES (189);
INSERT INTO wasserrecht.fiswrv_archivnummer (nummer) VALUES (209);
INSERT INTO wasserrecht.fiswrv_archivnummer (nummer) VALUES (210);

INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Wasserrechtliche Erlaubnis');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Gehobene Wasserrechtliche Erlaubnis');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Wasserrechtliche Bewilligung');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Planfeststellungsverfahren');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Sonstige Zulassungen');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Anzeige einer Gewässerbenutzung');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Wasserrechtliche Nutzungsgenehmigung');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Wasserrechtliche Nutzungsgenehmigung (historisch)');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus (name) VALUES ('Anpassungsbescheid nach § 13 LWaG');

INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus (name) VALUES ('Änderungsbescheides vom');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus (name) VALUES ('Anpassungsbescheides vom');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus (name) VALUES ('Ergänzungsbescheides vom');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus (name) VALUES ('Nachtrags vom');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus (name) VALUES ('Berichtigung vom');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus (name) VALUES ('Schreibens vom');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus (name) VALUES ('Anzeige des Eigentümerwechsels');

INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_auswahl (name) VALUES ('In der Fassung der');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_auswahl (name) VALUES ('In der Fassung des');

INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_status (name) VALUES ('Abschriften von WrZ');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_status (name) VALUES ('LUNG-Datenbanken');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_status (name) VALUES ('Erstbefüllungsdaten');

INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund (name) VALUES ('Fristablauf');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund (name) VALUES ('Widerruf');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund (name) VALUES ('Verzicht');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund (name) VALUES ('Änderung');

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnehmen und Ableiten von Wasser aus oberirdischen Gewässern (§ 9 Satz 1 Nr. 1 WHG)');
/*INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Aufstauen und Absenken von oberirdischen Gewässern (§ 9 Satz 1 Nr. 2 WHG)');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnehmen fester Stoffe aus oberirdischen Gewässern... (§ 9 Satz 1 Nr. 3 WHG)');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Einbringen und Einleiten von Stoffen in Gewässer (§ 9 Satz 1 Nr. 4 WHG)');*/
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnehmen, Zutagefördern, Zutageleiten und Ableiten von Grundwasser (§ 9 Satz 1 Nr. 5 WHG)');
/*INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Aufstauen, Absenken und Umleiten von Grundwasser  (§ 9 Satz 2 Nr. 1 WHG)');*/

/*
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Maßnahmen [...] die [...] nachteilige Veränderungen der Wasserbeschaffenheit herbei [...] führen (§ 9 Satz 2 Nr. 1 WHG)');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Wasser aus der Peene');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Oberflächenwasser mittels eines stationären Pumpenhauses aus der Peene');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Grundwasser');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Einleiten von industriellem Abwasser');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme und Zutageförderung von Grundwasser - Ziegeleiwiese Güstrow');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Grundwasserabsenkung');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Grundwasser aus 7 Brunnen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Grundwasser aus zehn bestehenden Brunnen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Grundwasser aus einem Brunnen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnehmen von Grundwasser');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme und Zutageförderung von Grundwasser');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Grundwasser aus Brunnen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Grundwasser aus 11 Bohrbrunnen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnehmen von Oberflächenwasser ... über einen offenen Zuleitungskanal (Neuer Kanal) aus der Peene');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Oberflächengewässer mittels einer mobilen Pumpe');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Zutagefördern von Grundwasser aus vier Bohrbrunnen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Oberflächenwasser aus dem Groß Wariner See');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Oberflächenwasser');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnehmen von Brauchwasser aus dem Hafenbecken B des Seehafens Rostock');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Grundwasserentnahme');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme und Eileiten von Oberflächenwasser');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art (name) VALUES ('Entnahme von Wasser aus dem Grundwasser');
*/

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art_benutzung (name, abkuerzung) VALUES ('Grundwasser', 'GW');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_art_benutzung (name, abkuerzung) VALUES ('Oberflächenwasser', 'OW');

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (1, 'Beregnung auf landwirtschaftlich genutzten Flächen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (2, 'Bewässerung nur der Hof- und Hausgärten (Nicht auf Feldern!)');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (3, 'Bewässern von öffentlichen Plätzen, Grünanlagen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (4, 'Anmachwasser für Pflanzenschutzmittel');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (5, 'Zwecke der Fischerei');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (6, 'Grundwasserabsenkung');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (7, 'öffentliche Trinkwasserversorgung');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (8, 'Wasserversorgung betriebszugehöriger Personen, die auf dem Hof, auch außerhalb des Haushalts des Betriebsinhabers wohnen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (9, 'Heilquellennutzung');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (10, 'Abfüllen von Mineralwasser');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (11, 'Tränken von Vieh auf der Hofstelle');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (12, 'Viehtränke außerhalb der Hofstelle');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (13, 'Säubern von Unterkünften und Stallungen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (14, 'Betrieb, Waschen und Reinigen techn. Anlagen und Maschinen');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (15, 'Kühlwassernutzung');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (16, 'Wasserkraftnutzung');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (17, 'Wärmegewinnung');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (18, 'gewerbliche Nutzung (wenn also keine Gärtnerei betrieben wird)');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (19, 'Brauchwasserversorgung');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (nummer, name) VALUES (20, 'Sonstiges');

/*
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Brauchwasserversorgung für Aquakultur nicht nach § 16 Satz 2 Nummer 5', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Öffentliche Trink- und Brauchwasserversorgung', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Bewässerung von öffentlichen Plätzen', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Kühlwasserversorgung', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Brauchwasserversorgung (ohne Landwirtschaft)', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Brauchwasserversorgung (ohne Landwirtschaft)', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Bewässerung von Sport- und Spielplätzen', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Betrieb von Wasserspielen und künstlichen Gewässern', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Tränk- und Brauchwasserversorgung für die Landwirtschaft', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Brauchwasserversorgung für den Gartenbau', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Bewässerung land- und forstwirtschaftlicher sowie gartenbaulicher Kulturen nicht nach § 16 Satz 2 Nummer 5', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wärmegewinnung nicht nach § 16 Satz 2 Nummer 3 oder 4 LWaG', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wärmegewinnung nach § 16 Satz 2 Nummer 3 LWaG', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wärmegewinnung nach § 16 Satz 2 Nummer 4 LWaG', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Beregnung landwirtschaftlicher sowie erwerbsgärtnerischer Kulturen nach § 16 Satz 2 Nummer 5 LWaG', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreiher Eigentümer- und Anliegergebrauch nach § 26 WHG', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wasserkraftnutzung nach § 16 Satz 2 Nummer 6 LWaG', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wasserkraftnutzung nicht nach § 16 Satz 2 Nummer 6 LWaG', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Brauchwasserversorgung für Fischerei nach § 16 Satz 2 Nummer 5 LWaG', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Herstellung von Erfrischungsgetränken; Gewinnung natürlicher Mineralwässer', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreihe Benutzung nach § 46 Satz 1 Nummer 1 WHG (Hofbetrieb etc.)', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreihe Benutzung nach § 46 Satz 1 Nummer 2 WHG (Bodenentwässerung)', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreihe Benutzung nach § 32 Satz 2 LWaG nicht gewerblicher Gartenbau', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreihe Benutzung nach § 32 Satz 2 LWaG Erhaltung der Bodenfruchtbarkeit', DEFAULT);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_zweck (name,freitext) VALUES ('Heilquellennutzung nach § 16 Satz 2 Nummer 2 LWaG', DEFAULT);
*/

/*INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2010-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2011-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2012-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2013-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2014-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2014-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2015-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2016-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2017-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2018-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2019-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2020-01-01', 0.02, 0.10);*/

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_gw_befreit,satz_gw_zugelassen,satz_gw_nicht_zugelassen,satz_gw_zugelassen_ermaessigt,satz_gw_nicht_zugelassen_ermaessigt,satz_ow_befreit,satz_ow_zugelassen,satz_ow_nicht_zugelassen,satz_ow_zugelassen_ermaessigt,satz_ow_nicht_zugelassen_ermaessigt) VALUES ('2015-01-01', 0.00, 0.05, 0.10, 0.005, 0.010, 0.00, 0.02, 0.04, 0.002, 0.004);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz (jahr,satz_gw_befreit,satz_gw_zugelassen,satz_gw_nicht_zugelassen,satz_gw_zugelassen_ermaessigt,satz_gw_nicht_zugelassen_ermaessigt,satz_ow_befreit,satz_ow_zugelassen,satz_ow_nicht_zugelassen,satz_ow_zugelassen_ermaessigt,satz_ow_nicht_zugelassen_ermaessigt) VALUES ('2016-01-01', 0.00, 0.10, 0.20, 0.010, 0.020, 0.00, 0.02, 0.04, 0.002, 0.004);

INSERT INTO wasserrecht.fiswrv_teilgewaesserbenutzungen_art (name) VALUES ('Erklärung');
INSERT INTO wasserrecht.fiswrv_teilgewaesserbenutzungen_art (name) VALUES ('Schätzung');

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO wasserrecht.fiswrv_koerperschaft_art (name) VALUES ('Trinkwasser');
INSERT INTO wasserrecht.fiswrv_koerperschaft_art (name) VALUES ('Abwasser');

INSERT INTO wasserrecht.fiswrv_koerperschaft (name, art) VALUES ('MUSTER ABWASSER KOERPERSCHAFT', 2);
INSERT INTO wasserrecht.fiswrv_koerperschaft (name, art) VALUES ('MUSTER TRINKWASSER KOERPERSCHAFT', 1);

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, behoerde, adresse, wrzadressat) VALUES (9001, 'MAX MUSTERMANN', 'MM', 1, 9001, 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, behoerde, adresse, wrzadressat) VALUES (9002, 'FRAU MUSTERMANN', 'FM', 2, 9002, 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, behoerde, adresse, wrzadressat) VALUES (9003, 'TEST ADRESSAT', 'TA', 1, 9003, 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, bearbeiter) VALUES (9004, 'MUSTER BEARBEITER', 'MB', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, betreiber) VALUES (9005, 'MUSTER BETREIBER', 'MB', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, wrzaussteller) VALUES (9006, 'MUSTER WRZ AUSSTELLER', 'MWA', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, betreiber) VALUES (9007, 'Agargenossenschaft Holldorf', 'AH', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, betreiber) VALUES (9008, 'Boddenland', 'BL', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, betreiber) VALUES (9009, 'Danisco Sugar GmbH', 'DSG', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, betreiber) VALUES (9010, 'Denissen Landwirtschaft Wöbbelin', 'DLW', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, betreiber) VALUES (9011, 'DMK Altentreptow', 'DA', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, betreiber) VALUES (9012, 'EURAWASSER Nord', 'EN', 'ja');
INSERT INTO wasserrecht.fiswrv_personen (id, name, abkuerzung, betreiber) VALUES (9013, 'Gartenbau Warin', 'GW', 'ja');

INSERT INTO wasserrecht.fiswrv_personen_status (name) VALUES ('[aktuell]');
INSERT INTO wasserrecht.fiswrv_personen_status (name) VALUES ('[historisch 1994–2011]');
INSERT INTO wasserrecht.fiswrv_personen_status (name) VALUES ('[historisch DDR]');

INSERT INTO wasserrecht.fiswrv_personen_typ (name) VALUES ('Körperschaft des öffentlichen Rechts');
INSERT INTO wasserrecht.fiswrv_personen_typ (name) VALUES ('Körperschaft des Privatrechts');

INSERT INTO wasserrecht.fiswrv_personen_klasse (name) VALUES ('Kieswerk');

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO wasserrecht.fiswrv_anlagen_klasse (name) VALUES ('Industriebetrieb');
INSERT INTO wasserrecht.fiswrv_anlagen_klasse (name) VALUES ('Diensleistungsbetrieb');
INSERT INTO wasserrecht.fiswrv_anlagen_klasse (name) VALUES ('Wasserwerk');
INSERT INTO wasserrecht.fiswrv_anlagen_klasse (name) VALUES ('Landwirtschaftsbetrieb');
INSERT INTO wasserrecht.fiswrv_anlagen_klasse (name) VALUES ('Sport- und Erhohlungsanlage');

INSERT INTO wasserrecht.fiswrv_anlagen (name, klasse, zustaend_uwb, zustaend_stalu, betreiber, abwasser_koerperschaft, trinkwasser_koerperschaft,kommentar,the_geom) VALUES ('Musterholzwerk Musterstadt', 1, 1, 1, 9005, 1, 2, NULL, ST_Transform(ST_GeomFromText('POINT(12 54)', 4326), 35833));
INSERT INTO wasserrecht.fiswrv_anlagen (name, klasse, zustaend_uwb, zustaend_stalu, abwasser_koerperschaft, trinkwasser_koerperschaft,kommentar,the_geom) VALUES ('Wasserwerk Musterstadt', 3, 5, 2, 1, 2, NULL, ST_Transform(ST_GeomFromText('POINT(12 53.5)', 4326), 35833));

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO wasserrecht.fiswrv_ort (name) VALUES ('Güstrow');
INSERT INTO wasserrecht.fiswrv_ort (name) VALUES ('Rostock');
INSERT INTO wasserrecht.fiswrv_ort (name) VALUES ('Hansestadt Rostock');

INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen (typus, aktenzeichen, ort, datum, ausstellbehoerde, zustaendige_behoerde, status, gueltig_seit, befristet_bis, aktuell, bearbeiter, adressat, anlage, fassung_nummer, fassung_datum) VALUES (1, '§ 1', 1, current_date, 1, 1, 2, '2016-07-01', '2017-07-06', true, 9002, 9002, 1, 1, '2017-07-01');
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen (typus, aktenzeichen, ort, datum, ausstellbehoerde, zustaendige_behoerde, status, gueltig_seit, befristet_bis, aktuell, bearbeiter, adressat, anlage) VALUES (1, '§ 2', 1, current_date, 1, 1, 2, '2017-07-01', '2017-07-06', false, 9002, 9002, 2);
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen (typus, aktenzeichen, ort, datum, ausstellbehoerde, zustaendige_behoerde, status, gueltig_seit, befristet_bis, aktuell, bearbeiter, adressat, anlage) VALUES (1, '§ 3', 1, current_date, 2, 2, 2, '2017-07-01', '2017-07-06', false, 9002, 9001, 2);
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen (typus, aktenzeichen, ort, datum, ausstellbehoerde, zustaendige_behoerde, status, gueltig_seit, befristet_bis, aktuell, bearbeiter, adressat, anlage) VALUES (1, '§ 4', 1, current_date, 2, 2, 2, '2017-07-01', '2017-12-31', false, 9002, 9001, 1);
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen (typus, aktenzeichen, ort, datum, ausstellbehoerde, zustaendige_behoerde, status, gueltig_seit, aktuell, bearbeiter, adressat, anlage) VALUES (1, '§ 5', 1, current_date, 1, 1, 2, '2017-07-01', false, 9002, 2, 1);
INSERT INTO wasserrecht.fiswrv_wasserrechtliche_zulassungen (typus, aktenzeichen, ort, datum, ausstellbehoerde, zustaendige_behoerde, status, gueltig_seit, befristet_bis, aktuell, bearbeiter, adressat, anlage, fassung_nummer, fassung_datum) VALUES (1, '§ 6', 1, current_date, 1, 1, 2, '2016-01-01', '2019-07-06', true, 9002, 9003, 1, 15, '2017-09-01');

UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET freigegeben = true WHERE id = 1;
UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET freigegeben = true WHERE id = 2;
UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET freigegeben = true WHERE id = 3;
UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET freigegeben = true WHERE id = 4;
UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET freigegeben = true WHERE id = 5;

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen (kennnummer, wasserbuchnummer, freitext_art, art, freitext_zweck, zweck, max_ent_wee, wasserrechtliche_zulassungen) VALUES ('1-2-1-1-1', '3572', 'Test Freitext Art 1', 1, 'Test Freitext Zweck 1', 6, 100000, 1);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen (kennnummer, wasserbuchnummer, freitext_art, art, freitext_zweck, zweck, max_ent_wee, wasserrechtliche_zulassungen) VALUES ('1-2-2-2-2', '0000', 'Test Freitext Art 2', 2, 'Test Freitext Zweck 2', 6, 100000, 2);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen (kennnummer, wasserbuchnummer, freitext_art, art, freitext_zweck, zweck, max_ent_wee, wasserrechtliche_zulassungen) VALUES ('2-1-2-3-3', '3572', 'Test Freitext Art 3', 1, 'Test Freitext Zweck 3', 6, 100000, 3);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen (kennnummer, wasserbuchnummer, freitext_art, art, freitext_zweck, zweck, max_ent_wee, wasserrechtliche_zulassungen) VALUES ('2-1-1-4-4', '2425', 'Test Freitext Art 4', 1, 'Test Freitext Zweck 4', 6, 100000, 4);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen (kennnummer, wasserbuchnummer, freitext_art, art, freitext_zweck, zweck, max_ent_wee, wasserrechtliche_zulassungen) VALUES ('1-2-1-5-5', '53535', 'Test Freitext Art 5', 1, 'Test Freitext Zweck 5', 6, 100000, 5);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen (kennnummer, wasserbuchnummer, freitext_art, art, freitext_zweck, zweck, max_ent_wee, wasserrechtliche_zulassungen) VALUES ('1-2-1-6-6', '53536', 'Test Freitext Art 6', 1, 'Test Freitext Zweck 6', 6, 100000, 6);

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_lage (name, gewaesserbenutzungen, the_geo) VALUES('Test Gewaesserbenutzungen Lage 1', 1, ST_Transform(ST_GeomFromText('POINT(12.5 54)', 4326), 35833));
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_lage (name, gewaesserbenutzungen, the_geo) VALUES('Test Gewaesserbenutzungen Lage 2', 1, ST_Transform(ST_GeomFromText('POINT(12.5 53.5)', 4326), 35833));

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten (name, abkuerzung) VALUES ('Kubikmeter pro Stunde', 'm³/h');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten (name, abkuerzung) VALUES ('Kubikmeter pro Tag', 'm³/d');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten (name, abkuerzung) VALUES ('Kubikmeter pro Woche', 'm³/wo');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten (name, abkuerzung) VALUES ('Kubikmeter pro Monat', 'm³/mon');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten (name, abkuerzung) VALUES ('Kubikmeter pro Jahr', 'm³/a');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten (name, abkuerzung) VALUES ('Liter pro Sekunde', 'l/s');

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Maximale Entnahmemenge pro Sekunde', 'max_ent_s');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Maximale Entnahmemenge pro Stunde', 'max_ent_h');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Maximale Entnahmemenge pro Tag', 'max_ent_d');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Maximale Entnahmemenge pro Woche', 'max_ent_w');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Maximale Entnahmemenge pro Monat', 'max_ent_m');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Maximale Entnahmemenge pro Jahr', 'max_ent_a');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Mittlere Entnahmemenge pro Sekunde', 'max_ent_s');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Mittlere Entnahmemenge pro Stunde', 'max_ent_h');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Mittlere Entnahmemenge pro Tag', 'max_ent_d');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Mittlere Entnahmemenge pro Woche', 'max_ent_w');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Mittlere Entnahmemenge pro Monat', 'max_ent_m');
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name (name, abkuerzung) VALUES ('Mittlere Entnahmemenge pro Jahr', 'max_ent_a');

INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang (name, wert, einheit, gewaesserbenutzungen) VALUES(6, 80000, 5, 1);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang (name, wert, einheit, gewaesserbenutzungen) VALUES(6, 80000, 5, 2);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang (name, wert, einheit, gewaesserbenutzungen) VALUES(6, 80000, 5, 3);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang (name, wert, einheit, gewaesserbenutzungen) VALUES(6, 80000, 5, 4);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang (name, wert, einheit, gewaesserbenutzungen) VALUES(6, 80000, 5, 5);
INSERT INTO wasserrecht.fiswrv_gewaesserbenutzungen_umfang (name, wert, einheit, gewaesserbenutzungen) VALUES(6, 80000, 5, 6);

INSERT INTO wasserrecht.fiswrv_teilgewaesserbenutzungen (erhebungsjahr, art, zweck, umfang, wiedereinleitung_nutzer, wiedereinleitung_bearbeiter,mengenbestimmung,art_benutzung,befreiungstatbestaende,entgeltsatz,teilgewaesserbenutzungen_art, gewaesserbenutzungen) VALUES(2016,1,1,2,true,true,3,2,false,2,2,1);

COMMIT;