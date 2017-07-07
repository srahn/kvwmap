BEGIN;

--INSERT INTO spatial_ref_sys VALUES (35833, 'EPSG', 35833, 'PROJCS["ETRS89/UTM 33N RW+33.000.000 MV",GEOGCS["ETRS89",DATUM["European_Terrestrial_Reference_System_1989",SPHEROID["GRS1980",6378137,298.257222101,AUTHORITY["EPSG","7019"]],AUTHORITY["EPSG","6258"]],PRIMEM["Greenwich",0,AUTHORITY["EPSG","8901"]],UNIT["degree",0.01745329251994328,AUTHORITY["EPSG","9122"]],AUTHORITY["EPSG","4258"]],PROJECTION["Transverse_Mercator"],PARAMETER["latitude_of_origin",0],PARAMETER["central_meridian",15],PARAMETER["scale_factor",0.9996],PARAMETER["false_easting",33500000],PARAMETER["false_northing",0],UNIT["metre",1,AUTHORITY["EPSG","9001"]],AUTHORITY["EPSG","25833"]]', '+proj=tmerc +lat_0=0 +lon_0=15 +k=0.9996 +x_0=33500000 +y_0=0 +ellps=GRS80 +units=m +no_defs towgs84=0,0,0<>');

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO wasserrecht.adresse (strasse, hausnummer, plz, ort) VALUES ('Lübecker Str.', '253', 19053, 'Schwerin');

INSERT INTO wasserrecht.betriebszustand (name) VALUES ('WFBB');
INSERT INTO wasserrecht.messtischblatt (nummer) VALUES (1445);
INSERT INTO wasserrecht.wasserbuch (nummer) VALUES (3572);

INSERT INTO wasserrecht.behoerde (name, abkuerzung, status) VALUES ('Untere Wasserbehoerde', 'uwb', DEFAULT);
INSERT INTO wasserrecht.behoerde (name, abkuerzung, status) VALUES ('Staatliches Amt für Landwirtschaft und Umwelt', 'stalu', DEFAULT);

INSERT INTO wasserrecht.archivnummer (nummer) VALUES (188);
INSERT INTO wasserrecht.archivnummer (nummer) VALUES (189);
INSERT INTO wasserrecht.archivnummer (nummer) VALUES (209);
INSERT INTO wasserrecht.archivnummer (nummer) VALUES (210);

INSERT INTO wasserrecht.anlagen_klasse (name) VALUES ('Industriebetrieb');
INSERT INTO wasserrecht.anlagen_klasse (name) VALUES ('Diensleistungsbetrieb');
INSERT INTO wasserrecht.anlagen_klasse (name) VALUES ('Wasserwerk');
INSERT INTO wasserrecht.anlagen_klasse (name) VALUES ('Landwirtschaftsbetrieb');
INSERT INTO wasserrecht.anlagen_klasse (name) VALUES ('Sport- und Erhohlungsanlage');

INSERT INTO wasserrecht.personen (name, behoerde) VALUES ('MAX MUSTERMANN', 1);
INSERT INTO wasserrecht.personen (name, behoerde) VALUES ('FRAU MUSTERMANN', 2);
INSERT INTO wasserrecht.personen (name, bearbeiter) VALUES ('MUSTER BEARBEITER', true);
INSERT INTO wasserrecht.personen (name, betreiber) VALUES ('MUSTER BETREIBER', true);

INSERT INTO wasserrecht.personen_status (name) VALUES ('[aktuell]');
INSERT INTO wasserrecht.personen_status (name) VALUES ('[historisch 1994–2011]');
INSERT INTO wasserrecht.personen_status (name) VALUES ('[historisch DDR]');

INSERT INTO wasserrecht.personen_typ (name) VALUES ('Körperschaft des öffentlichen Rechts');
INSERT INTO wasserrecht.personen_typ (name) VALUES ('Körperschaft des Privatrechts');

INSERT INTO wasserrecht.personen_klasse (name) VALUES ('Kieswerk');

INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Wasserrechtliche Erlaubnis');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Gehobene Wasserrechtliche Erlaubnis');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Wasserrechtliche Bewilligung');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Planfeststellungsverfahren');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Sonstige Zulassungen');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Anzeige einer Gewässerbenutzung');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Wasserrechtliche Nutzungsgenehmigung');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Wasserrechtliche Nutzungsgenehmigung (historisch)');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse (name) VALUES ('Anpassungsbescheid nach § 13 LWaG');

INSERT INTO wasserrecht.wasserrechtliche_zulassungen_fassung_klasse (name) VALUES ('Änderungsbescheides vom');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_fassung_auswahl (name) VALUES ('In der Fassung des');

INSERT INTO wasserrecht.wasserrechtliche_zulassungen_status (name) VALUES ('Abschriften von WrZ');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_status (name) VALUES ('LUNG-Datenbanken');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_status (name) VALUES ('[aktuelle]');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_status (name) VALUES ('Erstbefüllungsdaten');

INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ungueltig_aufgrund (name) VALUES ('Fristablauf');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ungueltig_aufgrund (name) VALUES ('Widerruf');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ungueltig_aufgrund (name) VALUES ('Verzicht');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_ungueltig_aufgrund (name) VALUES ('Änderung');

INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnehmen und Ableiten von Wasser aus oberirdischen Gewässern (§ 9 Satz 1 Nr. 1 WHG)', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Aufstauen und Absenken von oberirdischen Gewässern (§ 9 Satz 1 Nr. 2 WHG)', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnehmen fester Stoffe aus oberirdischen Gewässern... (§ 9 Satz 1 Nr. 3 WHG)', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Einbringen und Einleiten von Stoffen in Gewässer (§ 9 Satz 1 Nr. 4 WHG)', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnehmen, Zutagefördern, Zutageleiten und Ableiten von Grundwasser (§ 9 Satz 1 Nr. 5 WHG)', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Aufstauen, Absenken und Umleiten von Grundwasser  (§ 9 Satz 2 Nr. 1 WHG)', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Maßnahmen [...] die [...] nachteilige Veränderungen der Wasserbeschaffenheit herbei [...] führen (§ 9 Satz 2 Nr. 1 WHG)', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Wasser aus der Peene', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Oberflächenwasser mittels eines stationären Pumpenhauses aus der Peene', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Grundwasser', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Einleiten von industriellem Abwasser', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme und Zutageförderung von Grundwasser - Ziegeleiwiese Güstrow', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Grundwasserabsenkung', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Grundwasser aus 7 Brunnen', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Grundwasser aus zehn bestehenden Brunnen', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Grundwasser aus einem Brunnen', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnehmen von Grundwasser', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme und Zutageförderung von Grundwasser', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Grundwasser aus Brunnen', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Grundwasser aus 11 Bohrbrunnen', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnehmen von Oberflächenwasser ... über einen offenen Zuleitungskanal (Neuer Kanal) aus der Peene', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Oberflächengewässer mittels einer mobilen Pumpe', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Zutagefördern von Grundwasser aus vier Bohrbrunnen', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Oberflächenwasser aus dem Groß Wariner See', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Oberflächenwasser', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnehmen von Brauchwasser aus dem Hafenbecken B des Seehafens Rostock', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Grundwasserentnahme', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme und Eileiten von Oberflächenwasser', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('test', DEFAULT, DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_art (name,freitext,wgh) VALUES ('Entnahme von Wasser aus dem Grundwasser', DEFAULT, DEFAULT);

INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Brauchwasserversorgung für Aquakultur nicht nach § 16 Satz 2 Nummer 5', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Öffentliche Trink- und Brauchwasserversorgung', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Bewässerung von öffentlichen Plätzen', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Kühlwasserversorgung', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Brauchwasserversorgung (ohne Landwirtschaft)', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Brauchwasserversorgung (ohne Landwirtschaft)', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Bewässerung von Sport- und Spielplätzen', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Betrieb von Wasserspielen und künstlichen Gewässern', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Tränk- und Brauchwasserversorgung für die Landwirtschaft', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Brauchwasserversorgung für den Gartenbau', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Bewässerung land- und forstwirtschaftlicher sowie gartenbaulicher Kulturen nicht nach § 16 Satz 2 Nummer 5', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wärmegewinnung nicht nach § 16 Satz 2 Nummer 3 oder 4 LWaG', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wärmegewinnung nach § 16 Satz 2 Nummer 3 LWaG', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wärmegewinnung nach § 16 Satz 2 Nummer 4 LWaG', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Beregnung landwirtschaftlicher sowie erwerbsgärtnerischer Kulturen nach § 16 Satz 2 Nummer 5 LWaG', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreiher Eigentümer- und Anliegergebrauch nach § 26 WHG', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wasserkraftnutzung nach § 16 Satz 2 Nummer 6 LWaG', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Wasserkraftnutzung nicht nach § 16 Satz 2 Nummer 6 LWaG', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Trink- und Brauchwasserversorgung für Fischerei nach § 16 Satz 2 Nummer 5 LWaG', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Herstellung von Erfrischungsgetränken; Gewinnung natürlicher Mineralwässer', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreihe Benutzung nach § 46 Satz 1 Nummer 1 WHG (Hofbetrieb etc.)', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreihe Benutzung nach § 46 Satz 1 Nummer 2 WHG (Bodenentwässerung)', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreihe Benutzung nach § 32 Satz 2 LWaG nicht gewerblicher Gartenbau', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Erlaubnisfreihe Benutzung nach § 32 Satz 2 LWaG Erhaltung der Bodenfruchtbarkeit', DEFAULT);
INSERT INTO wasserrecht.gewaesserbenutzungen_zweck (name,freitext) VALUES ('Heilquellennutzung nach § 16 Satz 2 Nummer 2 LWaG', DEFAULT);

INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2010-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2011-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2012-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2013-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2014-01-01', 0.02, 0.05);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2014-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2015-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2016-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2017-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2018-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2019-01-01', 0.02, 0.10);
INSERT INTO wasserrecht.gewaesserbenutzungen_wee_satz (jahr,satz_ow,satz_gw) VALUES ('2020-01-01', 0.02, 0.10);

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO wasserrecht.koerperschaft_art (name) VALUES ('Trinkwasser');
INSERT INTO wasserrecht.koerperschaft_art (name) VALUES ('Abwasser');

INSERT INTO wasserrecht.koerperschaft (name, art) VALUES ('MUSTER ABWASSER KOERPERSCHAFT', 2);
INSERT INTO wasserrecht.koerperschaft (name, art) VALUES ('MUSTER TRINKWASSER KOERPERSCHAFT', 1);

INSERT INTO wasserrecht.wasserrechtliche_zulassungen_gueltigkeit (gueltig_seit, gueltig_bis) VALUES('2017-07-01', '2017-07-06');
INSERT INTO wasserrecht.wasserrechtliche_zulassungen_gueltigkeit (gueltig_seit, gueltig_bis) VALUES('2017-07-01', current_date);

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO wasserrecht.anlagen (name, klasse, zustaend_uwb, zustaend_stalu, bearbeiter, objektid_geodin, wrz_aktuell, wrz_historisch, betreiber, abwasser_koerperschaft, trinkwasser_koerperschaft,kommentar,the_geom) VALUES ('Musterholzwerk Musterstadt', 1, 1, 1, 3, NULL, true, true, 4, 1, 2, NULL, ST_Transform(ST_GeomFromText('POINT(12 54)', 4326), 35833));
INSERT INTO wasserrecht.wasserrechtliche_zulassungen (name, ausstellbehoerde, status, adresse, gueltigkeit, sachbearbeiter, adressat, anlage) VALUES ('Test Wasserrechtliche Zulassung', 1, 2, 1, 1, 2, 2, 1);
INSERT INTO wasserrecht.gewaesserbenutzungen (kennnummer, art, wasserbuch, zweck, gruppe_wee, wasserrechtliche_zulassungen) VALUES ('1-1-2-1', 4, 1, 6, false, 1);

COMMIT;