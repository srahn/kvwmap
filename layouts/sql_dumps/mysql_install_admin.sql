# SQL-Statements für die Einrichtung und Administration eines kvwmap Projektes
#
# Voraussetzungen/Vorarbeiten
#
# MySQL ist installiert
#
# Zusätzlich ist die kvwmap-Datenbank angelegt
#
# Die folgenden SQL-Statements in einem SQL-Fenster z.B. in phpMyAdmin ausführen

#!!!!!!!!!!!!!!!!!!!
# Bei verschiedenen SQL-Anweisungen sind vorher Konstanten für die Einträge in der Datenbank zu setzen
# Benutzer für den Zugriff auf die PostGIS-Datenbank
SET @pg_user='kvwmap';
SET @pg_dbname='kvwmapsp170';
# Benutzer in der Mysql-Datenbank für den die Eintragungen vorgenommen werden sollen
SET @user_id=1;
# Stelle in der Mysql-Datenbank, für die die Eintragungen vorgenommen werden sollen
SET @stelle_id=1;
#!!!!!!!!!!!!!!!!!!!!!!
# Beim Hinzufügen von Layern ist an Steller der Gruppenbezeichnung eine ID einzusetzen, die der Gruppe in der
# Tabelle u_groups entspricht. Wer eine neue Gruppe verwenden möchte, muss die neue Gruppe auch in die Tabelle
# u_groups eintragen.
# Generell ist es mit der neuen Stellenverwaltung auch nicht mehr notwendig die Zuordnungen der Layer zu den Stellen und Rollen
# per Hand einzutragen. Dazu nur noch den Layer in der Tabelle Layer anlegen und die Zuordnung zur Stelle über die Stellenverwaltung
# vornehmen
# Ähnliches gilt für die Menüpunkte, ein einmal in der Tabelle u_menues angelegtes Menü kann in der Stellenverwaltung zur Stellen
# zugeordnet werden.

################################################################################
# Einträge für eine neu angelegte Datenbank
# Standardnutzer, Stelle, Rolle, Referenzkarte einrichten
# Führen Sie hinterher am besten gleich alle Statements zum Anlegen von Menüpunkten
# für die hier angelegten stelle=1 und user_id=1 aus
################################################################################
# Stelle anlegen
INSERT INTO `stelle` ( `ID` , `Bezeichnung` , `start` , `stop` , `minxmax` , `minymax` , `maxxmax` , `maxymax` , `Referenzkarte_ID` , `Authentifizierung` , `ALB_status` , `wappen` , `alb_raumbezug` , `alb_raumbezug_wert` )
VALUES (
@stelle_id, 'Administration', '0000-00-00', '0000-00-00', '4440000', '5920000', '4560000', '6080000', '1', '1', '30', 'stz.png', '', ''
);
# Nutzer anlegen
INSERT INTO `user` ( `ID` , `login_name` , `Name` , `Vorname` , `passwort` , `Funktion` , `stelle_id` , `phon` , `email` )
VALUES (
@user_id, 'kvwmap', 'kvwmap', 'hans', MD5(''), 'admin', '1', '', 'admin@localhost.de'
);
# Rolle zuweisen
INSERT INTO `rolle` ( `user_id` , `stelle_id` , `nImageWidth` , `nImageHeight` , `minx` , `miny` , `maxx` , `maxy` , `nZoomFactor` , `selectedButton` , `epsg_code` )
VALUES (
@user_id, @stelle_id, '500', '500', '4440000', '5920000', '4560000', '6080000', '2', 'zoomin', '2398'
);
# Referenzkarte eintragen
INSERT INTO `referenzkarten` (`ID`,`Name` , `Dateiname` , `xmin` , `ymin` , `xmax` , `ymax` , `width` , `height` )
VALUES (
 '1','Uebersichtskarte', 'uebersicht_mv.png', '4405000', '5880000', '4662000', '6070000', '205', '146'
);

############################################################################
# Sicherheitskritische Anwendungsfälle Werte für go Variablen              #
############################################################################
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ALB_Anzeige');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ALB_Anzeige_Bestand');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Adresse_Auswaehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Adresse_Auswaehlen_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Flurstueck_Auswaehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Flurstueck_Auswaehlen_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Hausnummernkorrektur');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ZoomToFlst');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('history_move');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('tooltip_query');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Ändern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ALB_Aenderung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ALK_Fortfuehrung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Adm_Fortfuehrung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Administratorfunktionen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antraege_Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antrag_Aendern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antrag_loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antraganzeige_Festpunkte_in_KVZ_schreiben');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antraganzeige_Festpunkte_in_Karte_Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antraganzeige_Festpunkte_in_Liste_Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antraganzeige_Recherche_Ordner_packen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antraganzeige_Rechercheergebnis_in_Ordner_zusammenstellen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antraganzeige_Uebergabeprotokoll_Erzeugen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antraganzeige_Zugeordnete_Dokumente_Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Attributeditor');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Attributeditor_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Bauauskunft_Suche');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Bauauskunft_Suche_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Baudaten_aktualisieren');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Baudaten_aktualisieren_OK');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Baudatenanzeige');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Benutzer_Löschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Benutzerdaten_Als neuen Nutzer eintragen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Benutzerdaten_Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Benutzerdaten_Formular');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Benutzerdaten_Ändern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Bodenrichtwertformular');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Bodenrichtwertformular_Aendern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Bodenrichtwertformular_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Bodenrichtwertzone_Loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('BodenrichtwertzonenKopieren');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('BodenrichtwertzonenKopieren_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckausschnitt_loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckausschnitt_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckausschnittswahl');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckausschnittswahl_Drucken');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckausschnittswahl_Vorschau');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckrahmen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckrahmen_Freitexthinzufuegen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckrahmen_Freitextloeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckrahmen_Löschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckrahmen_als neuen Rahmen speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckrahmen_Änderungen Speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Druckrahmen_übernehmen >>');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ExportMapToPDF');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Externer_Druck');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Externer_Druck_Drucken');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('FestpunktDateiAktualisieren');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('FestpunktDateiUebernehmen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Festpunkte in Liste Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Festpunkte zum Antrag Hinzufügen_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('FestpunkteSkizzenZuordnung_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Festpunkte_Auswaehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Festpunkte_Auswaehlen_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Filterverwaltung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Filterverwaltung_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Flurstueck_Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Flurstuecks-CSV-Export');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Geothermie_Abfrage');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Geothermie_Eingabe');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Grundbuchblatt_Auswaehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Grundbuchblatt_Auswaehlen_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Kartenkommentar_Formular');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Kartenkommentar_Speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Kartenkommentar_Waehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Kartenkommentar_Zoom');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Kartenkommentar_loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer-Suche');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer-Suche_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer2Stelle_Editor');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer2Stelle_Editor_Speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer2Stelle_Reihenfolge');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer2Stelle_Reihenfolge_Speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer_Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer_Datensaetze_Loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layer_Löschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layerattribut-Rechteverwaltung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layerattribut-Rechteverwaltung_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layereditor');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layereditor_Als neuen Layer eintragen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layereditor_Klasse_Hinzufügen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layereditor_Klasse_Löschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layereditor_erweiterte Einstellungen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layereditor_Ändern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Metadaten_Auswaehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Metadaten_Auswaehlen_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Metadatenblattanzeige');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Metadateneingabe');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Metadateneingabe_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweis_antragsnr_form_aufrufen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweis_antragsnummer_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisanzeige');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisanzeige_aus_Auftrag_entfernen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisanzeige_zum_Auftrag_hinzufuegen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisformular');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisformular_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisloeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisrechercheformular');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisrechercheformular_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Namen_Auswaehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Namen_Auswaehlen_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('NotizKategorie_aendern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('NotizKategorie_hinzufuegen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('NotizKategorie_loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Notiz_Loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Notizenformular');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Notizenformular_KatVerwaltung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Notizenformular_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nutzung_auswaehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nutzung_auswaehlen_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('OWS');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('OWS_Exception');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('PointEditor');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('PointEditor_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('PolygonEditor');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('PolygonEditor_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('SHP_Export');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('SHP_Export_Shape-Datei erzeugen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('SHP_Import');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('SHP_Import_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Sachdaten');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Sachdaten_Festpunkte Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Sachdaten_Festpunkte zu Auftrag Hinzufügen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Sachdaten_FestpunkteSkizzenZuordnung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Sachdaten_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('StatistikAuswahl');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('StatistikAuswahl_anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Stelle Wählen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Stelle_Löschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Stellen_Anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Stelleneditor');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Stelleneditor_Als neue Stelle eintragen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Stelleneditor_Ändern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Suche_Flurstuecke_zu_Namen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Versiegelung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('WMS_Export');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('WMS_Export_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('WMS_Import');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('WMS_Import_Eintragen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Zeige_Flurstuecke_zu_Namen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('anliegerbeitraege');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('anliegerbeitraege_buffer_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('anliegerbeitraege_strasse_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('bauleitplanung');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('bauleitplanung_Loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('bauleitplanung_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('buildtopology');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('changemenue');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('changemenue_with_ajax');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('document_anzeigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('editLayerForm');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('export_ESAF64');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('export_ESAF64_Exportieren');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('export_ESAF64_Tabelle Bereinigen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('gebaeude_editor');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('gebaeude_editor_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('generischer_csv_export');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('georg_export');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('getRow');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('get_gps_position');   
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('get_legend');   
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('googlemaps');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('jagdbezirk_show_data');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('jagdbezirke_auswaehlen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('jagdbezirke_auswaehlen_Suchen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('jagdkatastereditor');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('jagdkatastereditor_Flurstuecke_Listen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('jagdkatastereditor_Loeschen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('jagdkatastereditor_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('layerfrommapfile');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('layerfrommapfile_Datei laden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('layerfrommapfile_Layer hinzufügen');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('logout');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('neuerLayer');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('neuerLayer_Senden');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('neuer_Layer_Datensatz');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('neuer_Layer_Datensatz_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('sendImage');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('showFlurstuckKoordinaten');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('simple_SHP_Import');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('simple_SHP_Import_speichern');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('spatialDocIndexing');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('spatial_processing');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('test');   
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('tmp_Adr_Tabelle_Aktualisieren');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('zoomtoPoint');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('zoomtoPolygon');
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('zoomtojagdbezirk');


####################################################################################
# Eintragen von Berechtigungen für einen Administrator zum Ausführen von Funktionen
####################################################################################
# 2006-05-12

SET @stelle_id=1;

INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ALB-Auszug 35');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ALK_Fortfuehrung');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ALB_Aenderung');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('FestpunktDateiAktualisieren');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('FestpunktDateiUebernehmen');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antrag_loeschen');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweis_antragsnr_form_aufrufen');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisanzeige_zum_Auftrag_hinzufuegen');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Antrag_Aendern');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('FestpunkteSkizzenZuordnung_Senden');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Administratorfunktionen');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Nachweisanzeige_aus_Auftrag_entfernen');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('ohneWasserzeichen');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Flurstueck_Anzeigen');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Bauakteneinsicht');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Namensuche');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('kategorienverwaltung');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layerattribut-Rechteverwaltung');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);
INSERT INTO `u_funktionen` (`bezeichnung`) VALUES ('Layerattribut-Rechteverwaltung_speichern');
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (LAST_INSERT_ID(),@stelle_id);


###########################
# Einträge der Menüpunkte #
###########################
#### gegebenenfalls vorherige Einträge löschen
# TRUNCATE u_menues;
# TRUNCATE u_menue2stelle;

# Setzen der Stelle, für die die Menüs eingetragen werden sollen
SET @stelle_id=1;
# Setzen der User_ID für die die Menüs zugeordnet werden sollen
SET @user_id=1;

# Die nachfolgenden Statements müssen in 1.5 angepasst werden
# Alle Gruppen von Menüs sind in einer separaten Tabelle u_groups enthalten und in der Tabelle u_menues erscheinen in der Spalte
# Gruppe nur noch die ID´s der Gruppen aus der Tabelle u_groups
# Wer seine Tabellen dahingehend anpassen möchte muss das entsprechende Statement aus mysql_update.php ausführen.
# siehe "Erzeugen einer neuen Tabelle groups"

INSERT INTO `u_menues` (name, links, obermenue, menueebene, target, `order`) VALUES ('Stelle w&auml;hlen', 'index.php?go=Stelle Wählen', 0, 1, NULL, 1);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,1);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);


#### Volle Ausdehnung (Übersicht) und letzte Kartenansicht
# Übersicht
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Übersicht', 'index.php?go=Full_Extent', 0, 1, NULL, 2);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,2);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Karte
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Karte', 'index.php', 0, 1, NULL, 3);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,3);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

##### Suchfunktionen
# Obermenü für die Suchfunktionen
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Suchen', 'index.php?go=changemenue', 0, 1, NULL, 4);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,10);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);


# Untermenüpunkte für die Suche
# Wenn das Obermenü schon existiert hier die ID-Angeben
# SET @last_level1menue_id=<Ihre ID>;

# Layersuche 
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layer-Suche', 'index.php?go=Layer-Suche', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,11);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Adresssuche 
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Adressen', 'index.php?go=Adresse_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,12);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Flurstückssuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Flurst&uuml;cke', 'index.php?go=Flurstueck_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,13);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Namenssuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Namen', 'index.php?go=Namen_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,14);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Metadaten
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Metadaten', 'index.php?go=Metadaten_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,15);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Grundbuchblattsuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Grundbuchblatt', 'index.php?go=Grundbuchblatt_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,16);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);


#### Stellenverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Stellenverwaltung', 'index.php?go=changemenue', 0, 1, NULL, 10);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,60);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Stellen anlegen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Stellen&nbsp;anlegen', 'index.php?go=Stelleneditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,61);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Stellen anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Stellen&nbsp;anzeigen', 'index.php?go=Stellen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,62);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layer-Rechteverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layer-Rechte', 'index.php?go=Layerattribut-Rechteverwaltung', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,63);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Nutzerverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Nutzerverwaltung', 'index.php?go=changemenue', 0, 1, NULL, 20);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,70);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Nutzer anlegen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Nutzer&nbsp;anlegen', 'index.php?go=Benutzerdaten_Formular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,71);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Nutzer anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Nutzer&nbsp;anzeigen', 'index.php?go=Benutzerdaten_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,72);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Layerverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Layerverwaltung', 'index.php?go=changemenue', 0, 1, NULL, 30);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,75);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Layer anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layer&nbsp;anzeigen', 'index.php?go=Layer_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,76);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layer erstellen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layer erstellen', 'index.php?go=Layereditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,77);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Attribut-Editor
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Attribut-Editor', 'index.php?go=Attributeditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,78);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# neuer Datensatz
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('neuer Datensatz', 'index.php?go=neuer_Layer_Datensatz', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,79);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Import/Export
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Import/Export', 'index.php?go=changemenue', 0, 1, NULL, 40);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,80);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# WMS-Export
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('WMS-Export', 'index.php?go=WMS_Export', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,81);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# WMS-Import
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('WMS-Import', 'index.php?go=WMS_Import', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,82);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Druckausgabe
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Druckausgabe', 'index.php?go=ExportMapToPDF', @last_level1menue_id, 2, '_blank');
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,83);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);



#### Druckmanager
INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target` )
VALUES ('Druckmanager', 'index.php?go=changemenue', '0', '1', NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_level1menue_id, '88');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

#### Druckrahmeneditor
INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target` )
VALUES ( 'Druckrahmeneditor', 'index.php?go=Druckrahmen', @last_level1menue_id, '2', NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_menue_id, '89');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Drucken
INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target` )
VALUES ( 'Drucken', 'index.php?go=Druckausschnittswahl', @last_level1menue_id, '2', NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_menue_id, '90');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# eine Layer-Gruppe anlegen
INSERT INTO `u_groups` (`id`, `Gruppenname`) VALUES (1, 'Administrativ');


# einen Polygon-Layer anlegen
INSERT INTO `layer` (`Layer_ID`, `Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES 
(1, 'Frei Polygon', 2, '1', 'SELECT kommentar, the_geom FROM frei_polygon WHERE 1=1', 'the_geom from frei_polygon', '', '', '', '', 0, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp168', 6, 'id', '', 3, 'meters', '2398', '', '1', 'EPSG:2398', '', '1.1.0', 'image/png', 60, '0');

INSERT INTO `classes` (`Class_ID`, `Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES 
(1, 'alle', 1, '', 1, NULL);

INSERT INTO `styles` (`Style_ID`, `symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `width`, `sizeitem`) VALUES 
(1, NULL, NULL, 1, '82 121 248', NULL, '0 0 0', NULL, NULL, NULL, '', NULL, NULL);
