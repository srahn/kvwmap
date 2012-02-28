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
SET @pg_dbname='kvwmapsp144';
# Benutzer in der Mysql-Datenbank für den die Eintragungen vorgenommen werden sollen
SET @user_id=2;
# Stelle in der Mysql-Datenbank, für die die Eintragungen vorgenommen werden sollen
SET @stelle_id=3;
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
INSERT INTO `stelle` ( `ID` , `Bezeichnung` , `start` , `stop` , `minxmax` , `minymax` , `maxxmax` , `maxymax` , `Referenzkarte_ID` , `Authentifizierung` , `ALB_status` , `wappen` , `selectedButton` , `alb_raumbezug` , `alb_raumbezug_wert` )
VALUES (
'', 'Administration', '0000-00-00', '0000-00-00', '4440000', '5920000', '4560000', '6080000', '1', '1', '30', 'stz.gif', 'zoomin', '', ''
);
# Nutzer anlegen
INSERT INTO `user` ( `ID` , `login_name` , `Name` , `Vorname` , `passwort` , `Funktion` , `stelle_id` , `phon` , `email` )
VALUES (
'', 'korduan', 'Korduan', 'Peter', '', 'admin', '1', '03814982164', 'peter.korduan@uni-rostock.de'
);
# Rolle zuweisen
INSERT INTO `rolle` ( `user_id` , `stelle_id` , `nImageWidth` , `nImageHeight` , `minx` , `miny` , `maxx` , `maxy` , `nZoomFactor` , `selectedButton` , `epsg_code` , `active_head` )
VALUES (
'1', '1', '500', '500', '4440000', '5920000', '4560000', '6080000', '2', 'zoomin', '2398', '0'
);
# Referenzkarte eintragen
INSERT INTO `referenzkarten` (`ID`,`Name` , `Dateiname` , `xmin` , `ymin` , `xmax` , `ymax` , `width` , `height` )
VALUES (
 '1','Uebersichtskarte', 'uebersicht.png', '4440000', '5920000', '4560000', '6080000', '200', '200'
);

############################################################################
# Sicherheitskritische Anwendungsfälle Werte für go Variablen              #
############################################################################
INSERT INTO `u_funktionen` (`id`, `bezeichnung`, `link`) VALUES
(1, 'ALB-Auszug 35', NULL),
(2, 'FestpunktDateiAktualisieren', NULL),
(3, 'FestpunktDateiUebernehmen', NULL),
(4, 'Antrag_loeschen', NULL),
(5, 'Nachweisanzeige_zum_Auftrag_hinzufuegen', NULL),
(6, 'Antrag_Aendern', NULL),
(7, 'FestpunkteSkizzenZuordnung_Senden', NULL),
(8, 'Nachweisanzeige_aus_Auftrag_entfernen', NULL),
(9, 'ohneWasserzeichen', NULL),
(10, 'Flurstueck_Anzeigen', NULL),
(11, 'Bauakteneinsicht', NULL),
(12, 'Namensuche', NULL),
(13, 'ALB-Auszug 40', NULL),
(14, 'Nachweisloeschen', NULL),
(15, 'ALB-Auszug 20', NULL),
(16, 'ALB-Auszug 25', NULL),
(17, 'Externer_Druck', NULL),
(18, 'Adressaenderungen', NULL),
(19, 'sendeFestpunktskizze', NULL),
(20, 'Nachweise_bearbeiten', NULL),
(21, 'ALB-Auszug 30', NULL);


####################################################################################
# Eintragen von Berechtigungen für einen Administrator zum Ausführen von Funktionen
####################################################################################
# 2006-05-12

SET @stelle_id=1;

INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (1,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (2,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (3,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (4,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (5,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (6,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (7,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (8,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (9,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (10,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (11,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (12,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (13,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (14,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (15,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (16,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (17,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (18,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (19,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (20,@stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (21,@stelle_id);

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
/*
#### Volle Ausdehnung (Übersicht) und letzte Kartenansicht
# Übersicht
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Übersicht', 'index.php?go=Full_Extent', 0, 1, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,1);

# Karte
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Karte', 'index.php', 0, 1, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,2);

# Notizen
INSERT INTO u_menues (name, links,menueebene)
 VALUES ('Notizen', 'index.php?go=Notizenformular',1);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,5);

##### Suchfunktionen
# Obermenü für die Suchfunktionen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Suchen', 'index.php?go=changemenue', 0, 1, NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,10);


# Untermenüpunkte für die Suche
# Wenn das Obermenü schon existiert hier die ID-Angeben
# SET @last_level1menue_id=<Ihre ID>;

# Adresssuche 
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Adressen', 'index.php?go=Adresse_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,11);

# Flurstückssuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Flurstücke', 'index.php?go=Flurstueck_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,12);

# Namenssuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Namen', 'index.php?go=Namen_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,13);

# Metadaten
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Metadaten', 'index.php?go=Metadaten_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,14);

# Grundbuchblattsuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Grundbuchblatt', 'index.php?go=Grundbuchblatt_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,15);

######### Aktualisierungsfunktionen 
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Fortführung', 'index.php?go=changemenue', 0, 1, NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,20);

# ALK-Fortführung (shape-Dateien)
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('ALK&nbsp;&Auml;nderung', 'index.php?go=ALK_Fortfuehrung', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,21);

# ALB Fortführung (WLDGE2SQL)
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('ALB&nbsp;&Auml;nderung', 'index.php?go=ALB_Aenderung', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,22);

# Punktdatei einlesen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Punktdatei&nbsp;Einlesen', 'index.php?go=FestpunktDateiAktualisieren', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,23);

# Punktdatei übernehmen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Punktdatei&nbsp;&Uuml;bernehmen', 'index.php?go=FestpunktDateiUebernehmen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,24);


######## Katasternachweisverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Vorbereitung', 'index.php?go=changemenue', 0, 1, NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,30);


# Vermessungsanträge
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Antr&auml;ge&nbsp;anzeigen', 'index.php?go=Antraege_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,31);

# Vermessungsantrag eingeben 
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Antragsnummer&nbsp;eingeben', 'index.php?go=Nachweis_antragsnr_form_aufrufen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,32);

# Suche nach Katasternachweisen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Nachweise&nbsp;Suchen', 'index.php?go=Nachweisrechercheformular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,33);

# Katasternachweis hinzufügen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Nachweis&nbsp;Einf&uuml;gen', 'index.php?go=Nachweisformular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,34);

# Festpunkte Suchen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Festpunkte&nbsp;Suchen', 'index.php?go=Festpunkte_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,35);


#### Gutachterausschuß
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Gutachterausschuss', 'index.php?go=changemenue', 0, 1, NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,40);


# Bodenrichtwerterfassung
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Richtwertzone&nbsp;Erfassen', 'index.php?go=Bodenrichtwertformular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,41);

# Stichtag übernahme
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Alle&nbsp;von&nbsp;Stichtag&nbsp;kopieren', 'index.php?go=BodenrichtwertzonenKopieren', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,42);


#### weitere Anwendungen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Anwendungen', 'index.php?go=changemenue', 0, 1, NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,50);


# Metadateneingabe
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Metadateneingabe', 'index.php?go=Metadateneingabe', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,52);

# Flächenversiegelung
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Fl&auml;chenversiegelung', 'index.php?go=Versiegelung', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,53);

# Geothermie
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Geothermie', 'index.php?go=Geothermie_Abfrage', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,54);

#### Bauauskunft
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Bauauskunft', 'index.php?go=changemenue', 0, 1, NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,18);
# Suche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Suche', 'index.php?go=Bauauskunft_Suche', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,19);


#### Projektverwaltung/Konfiguration
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Projekt', 'index.php?go=changemenue', 0, 1, NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,60);

# Stellen anlegen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Stellen&nbsp;anlegen', 'index.php?go=Stelleneditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,61);

# Stellen anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Stellen&nbsp;anzeigen', 'index.php?go=Stellen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,62);

# Nutzer anlegen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Nutzer&nbsp;anlegen', 'index.php?go=Benutzerdaten_Formular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,61);

# Nutzer anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Nutzer&nbsp;anzeigen', 'index.php?go=Benutzerdaten_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,62);

# Layer anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layer&nbsp;anzeigen', 'index.php?go=Layer_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,61);

# Layer erstellen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layer erstellen', 'index.php?go=Layereditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,62);


# WMS-Export
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('WMS-Export', 'index.php?go=WMS_Export', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,63);

# WMS-Import
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('WMS-Import', 'index.php?go=WMS_Import', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,63);

# Druckausgabe
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Druckausgabe', 'index.php?go=ExportMapToPDF', @last_level1menue_id, 2, '_blank');
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,64);


#### Hilfe
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Hilfe', 'index.php?go=changemenue', 0,1,'_blank');
SET @last_level1menue_id=LAST_INSERT_ID();
SET @hilfe_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,100);



#### Hilfe (Allgemein)
INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target` )
VALUES ( 'Hilfe (Allgemein)', 'https://kvwmap.geoinformatik.uni-rostock.de/kvwmap_docs/index.htm', @hilfe_id, '2', NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_level1menue_id, '705');


#### Hilfe (Dokumente)
INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target` )
VALUES ( 'Hilfe (Dokumente)', 'index.php?go=hilfe_dokumente#Allgemeine Beschreibung der Dokumentenverwaltung', @hilfe_id, '2', NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_level1menue_id, '720');


#### Dokumente
INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target` )
VALUES ('Dokumente', 'index.php?go=changemenue', '0', '1', NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_level1menue_id, '88');

#### Druckrahmen
INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target` )
VALUES ( 'Druckrahmen', 'index.php?go=Druckrahmen', @last_level1menue_id, '2', NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_level1menue_id, '89');
*/

#####################################################
# ! Neu ab der Version 1.5!
# Die Untermenüs können auf und zugeklappt werden. Der aktuelle Status wird für jeden Benutzer
# in der Tabelle u_menue2rolle gespeichert. Dazu sind für jede Zuordnung von rolle zur Stelle alle Menue
# id´s, die der Stelle zugeordnet sind jetzt auch den jeweiligen Nutzern der Stelle zugeordnet werden.
# Am besten diese Tabelle u_menue2rolle erstmal anlegen (mit Script aus mysql_update) dann nachstehendes
# sql-Statement ausführen
# Füllen der Tabelle u_menue2rolle auf Basis der Werte, die in u_menue2stelle und rolle stehen
insert into u_menue2rolle select rolle.user_id, rolle.stelle_id, u_menue2stelle.menue_id, 0 from u_menue2stelle, rolle where rolle.stelle_id = u_menue2stelle.stelle_id

##################################################
# Eintraege fuer Fachschale Bodenrichtwertzonen: #
##################################################
# Eintragen eines komplett neuen Layer mit neuem Style und Label.
# Wenn Style und Label schon vorhanden sind und genutzt werden sollen
# Die Insert-Statements für Style und Label auskommentieren und 
# die Variablen @last_style_id und @last_label_id auf die entsprechenden
# vorhandenen IDs setzen!

# Setzen von Konstanten für die Einträge in der Datenbank
# Benutzer für den Zugriff auf die PostGIS-Datenbank
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp144';
# Benutzer und Stelle in mysql-Datenbank
SET @user_id=2;
SET @stelle_id=3;
SET @group_id=3;
SET @drawingorder=20;

# Festlegen für welchen Stichtag der Layer angelegt werden soll.
# Der Stichtag kann später in kvwmap über einen Anwendungsfall "Stichtage_festlegen" eingestellt werden.
# Achtung Jahreszahl anpassen.
SET @stichtag='2004-12-31';

# Anlegen eines neuen Layers
# Der lange Eintrag für Data kommt von der komplizierten Bildung der Beschriftung der Zonen
INSERT INTO layer SET Name=@stichtag,Datentyp='2',Gruppe=@group_id
,pfad=CONCAT("SELECT oid,*,AsText(the_geom) AS umringtxt,AsText(textposition) AS textpositiontxt
 FROM bw_bodenrichtwertzonen WHERE datum='",@stichtag,"'")
,Data="the_geom from (select oid,*,
case 
when erschliessungsart='[ortsuebliche Erschl.]' then '[' 
when erschliessungsart='(vollerschlossen)' then '(' 
else '' 
end 
|| bodenwert || 
case 
when erschliessungsart='[ortsuebliche Erschl.]' then ']' 
when erschliessungsart='(vollerschlossen)' then ')' 
else '' 
end
||
case
when sanierungsgebiete='Sanierungsanfangswert' then ' SanA ;     '
when sanierungsgebiete='Sanierungsendwert' then ' SanE ;     '
when sanierungsgebiete='ohne' then ';'
end
|| richtwertdefinition
as beschriftung from bw_bodenrichtwertzonen) as foo using unique oid using srid=2398"
,labelitem='beschriftung'
,`connection`=CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname),connectiontype='6'
,classitem='datum',filteritem='datum',tolerance='3';

# Abfragen des dabei erzeugten Autowertes für die Layer_id
# und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

# Zuweisung des Layers zu einer Stelle
INSERT INTO used_layer SET Stelle_ID=@stelle_id,Layer_ID=@last_layer_id,template='Bodenrichtwerte.php',drawingorder=@drawingorder";

# Zuweisung des Layers zur Rolle, der Benutzer darf den Layer innerhalb der Stelle sehen.
INSERT INTO u_rolle2used_layer (user_id,stelle_id,layer_id,aktivStatus,queryStatus)
 VALUES (@user_id,@stelle_id,@last_layer_id,'0','0');

# Anlegen einer Klasse für die Darstellung des Layers
INSERT INTO classes SET Layer_ID=@last_layer_id,Expression=CONCAT("('[datum]' eq '",@stichtag,"')");
# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();

# Anlegen eines Styles für die Darstellung der Klasse
INSERT INTO styles (symbol,size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (9,2, '-1 -1 -1', NULL, '255 0 0',2,2);
# Abfrage der dabei erzeugten Style_ID
SET @last_style_id=LAST_INSERT_ID();

# Zuweisung des Styles zur Classe in Tabelle u_styles2classes
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);

#Anlegen eines Labels für die Darstellung des Textes der Klasse
INSERT INTO labels (color,outlinecolor,size,minsize,maxsize,position,wrap,the_force)
 VALUES ('255 0 0','255 255 255',10,5,10,8,59,1); 
# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();

# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

####################################
# Eintraege fuer den Layer Notizen #
####################################
# Benutzer für den Zugriff auf die PostGIS-Datenbank
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp';
# Benutzer und Stelle in mysql-Datenbank
SET @user_id=2;
SET @stelle_id=3;
SET @drawingorder=200;
SET @epsg_code=2398;

# Anlegen eines neuen Layers
INSERT INTO layer SET `Name`='Notizen',`Datentyp`='0',`Gruppe`='1'
,`pfad`=CONCAT("SELECT q_notiz_kategorien.id,q_notizen.oid,notiz,q_notiz_kategorien.kategorie,person,datum,AsText(the_geom) AS textposition FROM q_notizen,q_notiz_kategorien WHERE q_notiz_kategorien.id = q_notizen.kategorie_id")
,`Data`=CONCAT("the_geom from (select oid,notiz,kategorie_id,person,datum,the_geom from q_notizen) as foo using unique oid using srid=",@epsg_code)
,`labelitem`='notiz'
,`connection`=CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname),connectiontype='6'
,`classitem`='kategorie_id',`filteritem`='kategorie_id',`tolerance`='3';

# Abfragen des dabei erzeugten Autowertes für die Layer_id
SET @last_layer_id=LAST_INSERT_ID();

# Zuweisung des Layers zu einer Stelle
INSERT INTO `used_layer` SET `Stelle_ID`=@stelle_id,`Layer_ID`=@last_layer_id
,`maxscale`='100000',`filter`='(1=1)',`symbolscale`='5000',`drawingorder`=@drawingorder;

# Zuweisung des Layers zur Rolle, der Benutzer darf den Layer innerhalb der Stelle sehen.
INSERT INTO `u_rolle2used_layer` (`user_id`,`stelle_id`,`layer_id`,`aktivStatus`,`queryStatus`)
 VALUES (@user_id,@stelle_id,@last_layer_id,'0','0');

# Anlegen einer Klasse für die Darstellung des Layers
INSERT INTO classes SET `Layer_ID`=@last_layer_id,`Name`='alle';
# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();

# Anlegen eines Styles für die Darstellung der Klasse
INSERT INTO styles (`symbol`,`color`,`outlinecolor`,`size`,`minsize`,`maxsize`)
 VALUES (37,'255 255 150','100 100 100',30,20,40);
# Abfrage der dabei erzeugten Style_ID
SET @last_style_id=LAST_INSERT_ID();

# Zuweisung des Styles zur Classe in Tabelle u_styles2classes
INSERT INTO u_styles2classes (`class_id`, `style_id`,`drawingorder`) VALUES (@last_class_id,@last_style_id,1);

#Anlegen eines Labels für die Darstellung des Textes der Klasse
INSERT INTO `labels` (`color`,`outlinecolor`,`backgroundcolor`,`backgroundshadowcolor`
,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`wrap`,`the_force`)
 VALUES ('0 0 0','255 255 150','255 255 150','100 100 100',2,2,8,5,8,1,-3,-3,59,0);
# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();

# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO `u_labels2classes` (`class_id`,`label_id`) VALUES (@last_class_id,@last_label_id);

#################################################################
# SQL für das Hinzufügen von Layern, Classen, Styles und Labels zu Grenz- und Festpunkten befinden sich
# in der Datei mysql_install_festpunkte.sql
#################################################################


#############################################################
# Eintragen eines Layers zur Anzeige des Koordinatengitters #
#############################################################
SET @stelle_id=3;
SET @user_id=2;
# Zuweisen der Zeichnungsreihenfolge im Layer. Sollte über allen anderen gezeichnet werden.
SET @drawingorder=100;

# Anlegen eines neuen Layers für das Gitternetz
INSERT INTO layer (Name,Datentyp,Gruppe) VALUES ('Gitternetz','1','Topographie');
# Abfragen des dabei erzeugten Autowertes für die Layer_id und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();
# Anlegen einer neuen Klasse für das Gitternetz
INSERT INTO classes (Name,Layer_ID) VALUES ('',@last_layer_id);
SET @last_class_id=LAST_INSERT_ID();
# Anlegen eines neuen Labels für die Beschriftung des Gitternetzes
INSERT INTO labels (font,type,color,outlinecolor,size,position,buffer,partials) 
VALUES ('arial','truetype','255 0 0','255 255 255',8,'AUTO',5,'FALSE');
SET @last_label_id=LAST_INSERT_ID();
# Anlegen eines Grids für das Gitternetz
INSERT INTO m_grids () VALUES ();
SET @last_grid_id=LAST_INSERT_ID();
# Zuordnen des Labels zur Klasse
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);
# Zuordnen des Layers zur Stelle
INSERT INTO used_layer (Stelle_ID,Layer_ID,queryable,maxscale,drawingorder) 
VALUES (@stelle_id,@last_layer_id,'0','250000',@drawingorder);
# Zuordnen des Grids zum used_layer
INSERT INTO m_grids2used_layer (grid_id,stelle_id,layer_id)
 VALUES (@last_grid_id,@stelle_id,@last_layer_id);
# Zuordnen des used_layers zu Benutzer
INSERT INTO u_rolle2used_layer (user_id,stelle_id,layer_id) 
VALUES (@user_id,@stelle_id,@last_layer_id);

##################################################
# Eintraege fuer Fachschale Flächenversiegelung: #
##################################################
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp144';
SET @stelle_id=3;
SET @user_id=2;
SET @drawingorder=150;

# anlegen eines neues layers:
INSERT INTO layer (Name , Datentyp , Gruppe , pfad , Data , tileindex , tileitem , labelitem , labelmaxscale , labelminscale , connection , connectiontype , classitem , filteritem , tolerance ) 
VALUES ('Versiegelungsgrad', '2', 'Ver- u. Entsorgung', "SELECT oid,*,AsText(the_geom) AS umringtxt FROM ve_versiegelung WHERE (1=1)" , 'the_geom from ve_versiegelung', NULL , NULL , NULL , NULL , '0'
, CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), '6', 'grad', 'gard', '3');

# Abfragen des dabei erzeugten Autowertes für die Layer_id
# und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

# zuordung zu einer vorhandenen stelle: 
INSERT INTO used_layer (Stelle_ID,Layer_ID,drawingorder,minscale,maxscale,offsite,Filter) 
VALUES (@stelle_id, @last_layer_id, @drawingorder, NULL , NULL , NULL , NULL);

# Zuweisung des Layers zur Rolle, der Benutzer darf den Layer innerhalb der Stelle sehen.
# Maximale Ausdehnung BBox anpassen.
INSERT INTO u_rolle2used_layer (user_id,stelle_id,layer_id,aktivStatus,queryStatus) 
VALUES (@user_id,@stelle_id,@last_layer_id, '0', '0');

# Eintragen der Styles und Klassen
# style für Bauwerke:
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 0 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für Bauwerke:
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('Bauwerke', @last_layer_id, '(''[grad]''eq''1'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

# style für Asphalt
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 85 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für Asphalt
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('Asphalt', @last_layer_id, '(''[grad]''eq''2'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

# style für Beton
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 190 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für Beton
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('Beton', @last_layer_id, '(''[grad]''eq''3'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

# style für Pflaster
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 255 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für Pflaster
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('Pflaster',  @last_layer_id, '(''[grad]''eq''4'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

#style für Rasengitter
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '165 255 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für Rasengitter
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('Rasengitter', @last_layer_id, '(''[grad]''eq''5'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

# style für Wassergebunden
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '0 255 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für Wassergebunden
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('Wassergebunden', @last_layer_id, '(''[grad]''eq''6'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

##################################################
# Eintraege fuer Fachschale Nachweisverwaltung:  #
##################################################
# 2006-01-27 pk
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp144';
SET @stelle_id=1;
SET @user_id=1;
SET @drawingorder=160;

# Anlegen einer Datenzeile für den Benutzer zur Nutzung der Nachweisfachschale in der Stelle 
INSERT INTO rolle_nachweise (user_id,stelle_id) 
VALUES (@user_id,@stelle_id);

# Setzen der Gruppe in dem der Layer angelegt werden soll
# Entweder gruppen_id fest eingeben wenn schon vorhanden 
#SET @layer_group=2;
# oder neue Gruppe eintragen
INSERT INTO `u_groups` (`Gruppenname`) VALUES ('Kataster');
SET @layer_group=LAST_INSERT_ID();

# anlegen eines neues layers:
INSERT INTO layer (Name , Datentyp , Gruppe , pfad , Data , tileindex , tileitem , labelitem , labelmaxscale , labelminscale , connection , connectiontype , classitem , filteritem , tolerance ) 
VALUES ('Nachweise', '2',@layer_group, "SELECT oid,*,AsText(the_geom) AS umringtxt FROM n_nachweise WHERE (1=1)" , 'the_geom from n_nachweise', NULL , NULL , NULL , NULL , '0'
, CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), '6', 'art', 'gueltigkeit', '3');

# Abfragen des dabei erzeugten Autowertes für die Layer_id
# und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

# zuordung zu einer vorhandenen stelle: 
INSERT INTO used_layer (Stelle_ID,Layer_ID,drawingorder,minscale,maxscale,offsite,Filter) 
VALUES (@stelle_id, @last_layer_id, @drawingorder, NULL , NULL , NULL , NULL);

# Zuweisung des Layers zur Rolle, der Benutzer darf den Layer innerhalb der Stelle sehen.
# Maximale Ausdehnung BBox anpassen.
INSERT INTO u_rolle2used_layer (user_id,stelle_id,layer_id,aktivStatus,queryStatus)
 VALUES (@user_id,@stelle_id,@last_layer_id, '0', '0');

# Zuordnen der Gruppe zur Rolle
INSERT INTO u_groups2rolle (user_id,stelle_id,id,status) VALUES (@user_id,@stelle_id,@layer_group,0);

# Eintragen der Styles und Klassen
# style für Fortführungsrisse (FFR):
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 0 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für FFR:
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('FFR', @last_layer_id, '(''[art]''eq''100'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

# style für Koordinatenverzeichnisse (KVZ)
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 85 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für KVZ
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('KVZ', @last_layer_id, '(''[art]''eq''010'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

# style für Grenzniederschriften (GN)
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 190 0', '', '0 0 0');
SET @last_style_id=LAST_INSERT_ID();

# klasse für GN
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('GN', @last_layer_id, '(''[art]''eq''001'')', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

############################################
# Angaben für Metadatenlayer               #
############################################
# Anlegen des Layers
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp144';
SET @stelle_id=1;
SET @user_id=1;
SET @drawingorder=150;

# Anlegen eines neues layers:
INSERT INTO layer (Name, Datentyp, Gruppe, pfad, Data, tileindex, tileitem, labelitem,
labelmaxscale, labelminscale, labelrequires, `connection` connectiontype, classitem, filteritem,
tolerance, toleranceunits, transparency, epsg_code, ows_srs, wms_name, wms_server_version, wms_format,
wms_connectiontimeout) 
VALUES ('Metadaten','2','Administrativ',"SELECT oid,*,AsText(the_geom) AS umringtxt FROM md_metadata WHERE (1=1)",
'the_geom from md_metadata',NULL,NULL,'restitle',NULL,NULL,NULL,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname),
'6','idtype','idtype','3','pixels',NULL,'2398','EPSG:2398','Metadaten','1.1.0','image/png','60');

# Abfragen des dabei erzeugten Autowertes für die Layer_id
# und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

# zuordung zu einer vorhandenen stelle: 
INSERT INTO used_layer (Stelle_ID,Layer_ID,drawingorder,minscale,maxscale,offsite,Filter) 
VALUES (@stelle_id, @last_layer_id, @drawingorder, NULL , NULL , NULL , NULL);

# Zuweisung des Layers zur Rolle, der Benutzer darf den Layer innerhalb der Stelle sehen.
# Maximale Ausdehnung BBox anpassen.
INSERT INTO u_rolle2used_layer (user_id,stelle_id,layer_id,aktivStatus,queryStatus)
VALUES (@user_id,@stelle_id,@last_layer_id, '0', '0');

##### Klasse Services
# style für Services (Gebiete die von Services abgedeckt werden)
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 0 0', '0 255 0', '0 0 255');
SET @last_style_id=LAST_INSERT_ID();

# klasse für Sevices
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('Services', @last_layer_id, "('[idtype]''eq''service')", '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

##### Klasse Datensätze
# style für Datensäte
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 255 0', '255 255 0', '0 255 255');
SET @last_style_id=LAST_INSERT_ID();

# klasse für Datensätze
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('Datensätze', @last_layer_id, '(1=1)', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

##### Klasse für sonstiges
# style für sonstige Metadatentypen
INSERT INTO styles (symbol , symbolname , size , color , backgroundcolor , outlinecolor ) 
VALUES ('0', '', '0', '255 255 0', '255 255 0', '0 255 255');
SET @last_style_id=LAST_INSERT_ID();

# klasse für sonstige Metadatentypen
INSERT INTO classes (Name , Layer_ID , Expression , drawingorder ) 
VALUES ('sonstiges', @last_layer_id, '(1=1)', '0');
SET @last_class_id=LAST_INSERT_ID();

INSERT INTO u_styles2classes (class_id , style_id , drawingorder ) 
VALUES (@last_class_id, @last_style_id, '1');

################################################################################
# ALK - Fluren aus Postgresql Datenbank
##############################################################################
# Konstanten
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp@Veraeusserung_id';
SET @stelle_id=3;
SET @user_id=1;
SET @drawingorder_layer=140;
SET @maxscale=0;
SET @symbolscale=10000;
SET @epsg_code=2398;
SET @group_id=5;

# Layer
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`tileindex`,`tileitem`
,`labelitem`,`labelmaxscale`,`labelminscale`,`connection`,`connectiontype`,`classitem`
,`filteritem`,`tolerance`,`toleranceunits`,`transparency`,`labelrequires`,`epsg_code`) 
VALUES ('Fluren','2',@group_id
,CONCAT("SELECT o.objnr AS oid,o.objart,o.folie,AsText(o.the_geom) AS umring,fl.flur,fl.gemkgschl FROM alkobj_e_fla AS o,alknflur AS fl WHERE o.folie='002' AND o.objnr=fl.objnr")
,CONCAT("the_geom from (select o.objnr as oid,o.objart,o.folie,o.the_geom,fl.flur,fl.gemkgschl from alkobj_e_fla AS o,alknflur as fl WHERE o.folie='002' AND o.objnr=fl.objnr) as foo using unique oid using srid=",@epsg_code)
,NULL,NULL,'objart',NULL,NULL,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname),'6','objart','objart'
,'3','pixels',NULL,NULL,@epsg_code
);
SET @last_layer_id=LAST_INSERT_ID();

# Zuordnung des Layers zur Stelle
INSERT INTO used_layer (`Stelle_ID`,`Layer_ID`,`queryable`,`drawingorder`,`minscale`,`maxscale`,`symbolscale`)
VALUES (@stelle_id,@last_layer_id,'1',@drawingorder_layer
,'0',@maxscale,@symbolscale);

# Zuordnung der Rolle zur Beziehung zwischen Layer und Stelle
INSERT INTO u_rolle2used_layer (`user_id`,`stelle_id`,`layer_id`)
VALUES (@user_id,@stelle_id,@last_layer_id);

# Klasse
INSERT INTO classes (`Name`,`Layer_ID`) 
VALUES ('alle',@last_layer_id);
SET @last_class_id=LAST_INSERT_ID();

# Style
INSERT INTO styles (`symbol`,`size`,`color`,`outlinecolor`,`minsize`,`maxsize`) 
VALUES ('10','3','-1 -1 -1','0 0 0', '1', '3');
SET @last_style_id=LAST_INSERT_ID();

# Zuordnung Style zur Classe
INSERT INTO u_styles2classes (`class_id`,`style_id`) 
VALUES (@last_class_id,@last_style_id);

################################################################################
# ALK - Flurstucke aus Postgresql Datenbank
##############################################################################
# Konstanten
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp@Veraeusserung_id';
SET @stelle_id=1;
SET @user_id=1;
SET @group_id=2;
SET @drawingorder_layer=150;
SET @maxscale=50000;
SET @symbolscale=1000;
SET @epsg_code=2398;

# 2006-02-11 pk group_id statt groupname, label statt objart, 
# Layer
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`tileindex`,`tileitem`,`labelitem`
,`labelmaxscale`,`labelminscale`,`connection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`
,`toleranceunits`,`transparency`,`labelrequires`,`epsg_code`) 
VALUES ('Flurstücke','2',@group_id
,CONCAT("select o.objnr as oid,o.objart,o.folie,AsText(o.the_geom) AS umring,f.flurstkennz,f.gemkgschl from alkobj_e_fla AS o,alknflst as f WHERE o.folie='001' AND  o.the_geom && GeometryFromText('xxxx',",@epsq_code,") AND  NOT Disjoint(o.the_geom,GeometryFromText('xxxx',",@epsg_code,")) AND o.folie='001' AND o.objnr=f.objnr")
,CONCAT("the_geom from (select o.objnr as oid,o.objart,o.folie,o.the_geom,f.flurstkennz,f.gemkgschl,t.label from alkobj_e_fla AS o,alknflst as f,alkobj_t_pkt AS t WHERE o.folie='001' AND o.objnr=f.objnr AND o.objnr=t.objnr) as foo using unique oid using srid=",@epsg_code)
,NULL,NULL,'label',NULL,NULL,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname),'6','objart','objart'
,'3','pixels',NULL,NULL,@epsg_code
);
SET @last_layer_id=LAST_INSERT_ID();
# Man kann auch den Text aus der Abfrage f001_t_pkt_a benutzen für die Textanzeige dann würde die Abfrage in dem Data feld so aussehen
# select o.objnr as oid,o.objart,o.folie,o.the_geom,f.flurstkennz,f.gemkgschl,t.label from alkobj_e_fla AS o,alknflst as f,f001_t_pkt_a AS t WHERE o.folie='001' AND o.objnr=f.objnr AND o.objnr=t.objnr

# Zuordnung des Layers zur Stelle
INSERT INTO used_layer (`Stelle_ID`,`Layer_ID`,`queryable`,`drawingorder`
,`minscale`,`maxscale`,`symbolscale`)
VALUES (@stelle_id,@last_layer_id,'1',@drawingorder_layer
,'0',@maxscale,@symbolscale);

# Zuordnung der Rolle zur Beziehung zwischen Layer und Stelle
INSERT INTO u_rolle2used_layer (`user_id`,`stelle_id`,`layer_id`)
VALUES (@user_id,@stelle_id,@last_layer_id);

# Klasse
INSERT INTO classes (`Name`,`Layer_ID`) 
VALUES ('alle',@last_layer_id);
SET @last_class_id=LAST_INSERT_ID();

# Style
INSERT INTO styles (`symbol`,`size`,`color`,`outlinecolor`,`minsize`,`maxsize`) 
VALUES ('25','2','-1 -1 -1','0 0 0', '1', '2');
SET @last_style_id=LAST_INSERT_ID();

# Zuordnung Style zur Classe
INSERT INTO u_styles2classes (`class_id`,`style_id`) 
VALUES (@last_class_id,@last_style_id);

# 2006-02-11 pk
# Eintragen des Labels für die Beschriftung der Flurstücke mit Nr
INSERT INTO labels (color,font,outlinecolor,size,minsize,maxsize,position,the_force)
 VALUES ('0 0 0','verdana','255 255 255',10,5,12,7,0);
# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();

# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

################################################################################
# ALK - Qualität der Flurstücke verhandelt oder nicht aus Postgresql Datenbank
##############################################################################
# Konstanten
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp@Veraeusserung_id';
SET @stelle_id=3;
SET @user_id=1;
SET @drawingorder_layer=@Verpachtung_id;
SET @maxscale=50000;
SET @symbolscale=1000;
SET @epsg_code=2398;

# Layer
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`Data`,`tileindex`,`tileitem`,`labelitem`
,`labelmaxscale`,`labelminscale`,`connection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`
,`toleranceunits`,`transparency`,`labelrequires`,`epsg_code`) 
VALUES ('Flst-verhandelt','2','ALK'
,CONCAT("the_geom from (select o.objnr as oid,o.the_geom,q.verhandelt from alkobj_e_fla AS o,q_alknflst as q WHERE o.objnr=q.objnr) as foo using unique oid using srid=",@epsg_code)
,NULL,NULL,'oid',NULL,NULL,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname),'6','verhandelt','oid'
,'3','pixels',NULL,NULL,@epsg_code
);
SET @last_layer_id=LAST_INSERT_ID();

# Zuordnung des Layers zur Stelle
INSERT INTO used_layer (`Stelle_ID`,`Layer_ID`,`queryable`,`drawingorder`
,`minscale`,`maxscale`,`symbolscale`)
VALUES (@stelle_id,@last_layer_id,'0',@drawingorder_layer
,'0',@maxscale,@symbolscale);

# Zuordnung der Rolle zur Beziehung zwischen Layer und Stelle
INSERT INTO u_rolle2used_layer (`user_id`,`stelle_id`,`layer_id`)
VALUES (@user_id,@stelle_id,@last_layer_id);

# Klasse vollständig verhandelt
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`) 
VALUES ('ja',@last_layer_id,'([verhandelt] = 1)');
SET @last_class_id=LAST_INSERT_ID();

# Style
INSERT INTO styles (`symbol`,`size`,`color`,`outlinecolor`,`minsize`,`maxsize`) 
VALUES ('0','2','50 255 50','0 0 0', '1', '2');
SET @last_style_id=LAST_INSERT_ID();

# Zuordnung Style zur Classe
INSERT INTO u_styles2classes (`class_id`,`style_id`) 
VALUES (@last_class_id,@last_style_id);

# Klasse noch nicht vollständig verhandelt
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`) 
VALUES ('nein',@last_layer_id,'([verhandelt] = 0)');
SET @last_class_id=LAST_INSERT_ID();

# Style
INSERT INTO styles (`symbol`,`size`,`color`,`outlinecolor`,`minsize`,`maxsize`) 
VALUES ('0','2','255 50 50','0 0 0', '1', '2');
SET @last_style_id=LAST_INSERT_ID();

# Zuordnung Style zur Classe
INSERT INTO u_styles2classes (`class_id`,`style_id`) 
VALUES (@last_class_id,@last_style_id);

################################################################################
# ALK - Gebäude aus Postgresql Datenbank
##############################################################################
# Konstanten
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp@Veraeusserung_id';
SET @stelle_id=3;
SET @user_id=1;
SET @group_id=2;
SET @drawingorder_layer=160;
SET @maxscale=50000;
SET @symbolscale=1000;
SET @epsg_code=2398;

# Layer
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`tileindex`,`tileitem`,`labelitem`
,`labelmaxscale`,`labelminscale`,`connection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`
,`toleranceunits`,`transparency`,`labelrequires`,`epsg_code`)
 VALUES ('Gebäude','2',@group_id
,CONCAT("SELECT o.objnr AS oid,o.objart,o.folie,o.objart,o.the_geom,h.gemeinde,h.strasse,h.hausnr,h.lfdnr FROM alkobj_e_fla AS o,alknhaus as h WHERE o.folie='011' AND  o.the_geom && GeometryFromText('xxxx',",@epsq_code,") AND NOT Disjoint(o.the_geom,GeometryFromText('xxxx',",@epsq_code,")) AND o.objnr=h.objnr")
,CONCAT("the_geom from (SELECT o.objnr AS oid,o.objart,o.folie,o.the_geom,h.gemeinde,h.strasse,h.hausnr,h.lfdnr FROM alkobj_e_fla AS o,alknhaus as h WHERE o.folie='011' AND  o.objnr=h.objnr) as foo using unique oid using srid=",@epsg_code)
,NULL,NULL,'objart',NULL,NULL,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname),'6','objart','objart'
,'3','pixels',NULL,NULL,@epsg_code
);
SET @last_layer_id=LAST_INSERT_ID();

# Zuordnung des Layers zur Stelle
INSERT INTO used_layer (`Stelle_ID`,`Layer_ID`,`queryable`,`drawingorder`
,`minscale`,`maxscale`,`symbolscale`)
VALUES (@stelle_id,@last_layer_id,'1',@drawingorder_layer
,'0',@maxscale,@symbolscale);

# Zuordnung der Rolle zur Beziehung zwischen Layer und Stelle
INSERT INTO u_rolle2used_layer (`user_id`,`stelle_id`,`layer_id`)
VALUES (@user_id,@stelle_id,@last_layer_id);

##### Klasse Wohngebäude
INSERT INTO classes (`Name`,`Layer_ID`,`EXPRESSION`)
VALUES ('Wohngeb.',@last_layer_id,'(([OBJART] = 1003) or ([OBJART] >= 1101 and [OBJART] <= 1499) or ([OBJART] = 1781) or ([OBJART] >= 2101 and [OBJART] <= 2199) or ([OBJART] = 2311) or ([OBJART] = 2711) or ([OBJART] >= 2731 and [OBJART] <= 2738) or ([OBJART] >= 2801 and [OBJART] <= 2811) or ([OBJART] >= 2821 and [OBJART] <= 2829) or ([OBJART] >= 2841 and [OBJART] <=  2871) or ([OBJART] = 2881) or ([OBJART] >= 2891 and [OBJART] <=  2899))');
SET @last_class_id=LAST_INSERT_ID();

# Style
INSERT INTO styles (`symbol`,`size`,`color`,`outlinecolor`,`minsize`,`maxsize`) 
VALUES ('0','1','255 50 50','0 0 0', '1', '1');
SET @last_style_id=LAST_INSERT_ID();

# Zuordnung Style zur Classe
INSERT INTO u_styles2classes (`class_id`,`style_id`,`drawingorder`) 
VALUES (@last_class_id,@last_style_id,1);

##### Klasse Wirtschaftsgebäude
INSERT INTO classes (`Name`,`Layer_ID`,`EXPRESSION`) 
VALUES ('Wirtsch.Geb.',@last_layer_id,'(([OBJART] = 1004) or ([OBJART] = 1006) or ([OBJART] >= 1701 and [OBJART] <= 1779) or ([OBJART] = 1799) or ([OBJART] = 2301) or ([OBJART] >= 2313 and [OBJART] <= 2362) or ([OBJART] = 2366) or ([OBJART] >= 2368 and [OBJART] <= 2522) or ([OBJART] >= 2528 and [OBJART] <= 2701) or ([OBJART] >= 2721 and [OBJART] <= 2729) or ([OBJART] = 2799) or ([OBJART] >= 2812 and [OBJART] <= 2819) or ([OBJART] >= 2872 and [OBJART] <= 2879)or ([OBJART] >= 2882 and [OBJART] <= 2889) or ([OBJART] = 2921))');
SET @last_class_id=LAST_INSERT_ID();

# Style
INSERT INTO styles (`symbol`,`size`,`color`,`outlinecolor`,`minsize`,`maxsize`) 
VALUES ('0','1','255 150 150','0 0 0', '1', '1');
SET @last_style_id=LAST_INSERT_ID();

# Zuordnung Style zur Classe
INSERT INTO u_styles2classes (`class_id`,`style_id`,`drawingorder`) 
VALUES (@last_class_id,@last_style_id,2);

##### Klasse sonstige Gebäude
INSERT INTO classes (`Name`,`Layer_ID`,`EXPRESSION`) 
VALUES ('sonst.Geb.',@last_layer_id,'(([OBJART] = 905) or ([OBJART] = 1001) or ([OBJART] = 1005) or ([OBJART] = 1911) or ([OBJART] = 1913) or ([OBJART] = 2312) or ([OBJART] = 2363) or ([OBJART] = 2367)  or ([OBJART] = 2523) or ([OBJART] = 2741) or ([OBJART] = 2742) or ([OBJART] = 2748) or ([OBJART] = 2831))');
SET @last_class_id=LAST_INSERT_ID();

# Style
INSERT INTO styles (`symbol`,`size`,`color`,`outlinecolor`,`minsize`,`maxsize`) 
VALUES ('0','1','255 220 220','0 0 0', '1', '1');
SET @last_style_id=LAST_INSERT_ID();

# Zuordnung Style zur Classe
INSERT INTO u_styles2classes (`class_id`,`style_id`,`drawingorder`) 
VALUES (@last_class_id,@last_style_id,2);


SELECT h.objnr AS oid,p.the_geom,h.hausnr,p.winkel FROM alkobj_t_pkt AS p,alknhaus AS h WHERE
 p.objnr=h.objnr AND lfdnr='001'


########################################
# Leeren aller ALB-Tabellen
########################################
TRUNCATE Flurstuecke;
TRUNCATE Grundbuecher;
TRUNCATE f_Adressen;
TRUNCATE f_Anlieger;
TRUNCATE f_Baulasten;
TRUNCATE f_Hinweise;
TRUNCATE f_Historie;
TRUNCATE f_Klassifizierungen;
TRUNCATE f_Lage;
TRUNCATE f_Nutzungen;
TRUNCATE f_Texte;
TRUNCATE f_Verfahren;
TRUNCATE g_Buchungen;
TRUNCATE g_Eigentuemer;
TRUNCATE g_Grundstuecke;
TRUNCATE g_Namen;
TRUNCATE v_Katasteraemter;
TRUNCATE v_Gemarkungen;
TRUNCATE v_Grundbuchbezirke;
TRUNCATE v_Kreise;
TRUNCATE v_Gemeinden;
TRUNCATE v_Strassen;
TRUNCATE v_Amtsgerichte;
TRUNCATE v_EigentuemerArten;
TRUNCATE v_Buchungsarten;
TRUNCATE v_Forstaemter;
TRUNCATE v_Finanzaemter;
TRUNCATE v_Hinweise;
TRUNCATE v_Nutzungsarten;
TRUNCATE v_Klassifizierungen;
TRUNCATE v_AusfuehrendeStellen;
TRUNCATE v_BemerkgZumVerfahren;
TRUNCATE ALB_Fortfuehrung;

################################################################################
# Beschriftung der Strassen über die ALK
##############################################################################
# Konstanten
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp@Veraeusserung_id';
SET @stelle_id=1;
SET @user_id=1;
SET @drawingorder_layer=150;
SET @maxscale=50000;
SET @symbolscale=1000;
SET @epsg_code=2398;
SET @layer_group=10;

# Layer
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`Data`,`labelangleitem`,`labelitem`
,`labelmaxscale`,`connection`,`connectiontype`,`classitem`,`filteritem`,`labelrequires`,`epsg_code`) 
VALUES ('Beschriftung',0,@layer_group
,CONCAT("the_geom FROM (SELECT t.objnr AS oid,t.label,t.objart,t.winkel,t.the_geom FROM alkobj_t_pkt AS t WHERE t.objart=5101 AND t.folie = '022' AND t.the_geom IS NOT NULL) as foo using unique oid using srid=",@epsg_code)
,'winkel','label',@maxscale,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname),'6','objart','objart'
,'',@epsg_code);
SET @last_layer_id=LAST_INSERT_ID();

# Klasse
INSERT INTO classes (`Name`,`Layer_ID`) 
VALUES ('Strassennamen',@last_layer_id);
SET @last_class_id=LAST_INSERT_ID();

#Anlegen eines Labels für die Darstellung des Textes der Klasse
INSERT INTO labels (color,outlinecolor,size,minsize,maxsize,position,the_force)
 VALUES ('0 0 0','255 255 255',10,6,10,8,1); 
# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();

# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

# Style
INSERT INTO styles (`symbol`,`size`,`color`,`outlinecolor`,`minsize`,`maxsize`) 
VALUES ('0','0','-1 -1 -1','-1 -1 -1', '0', '0');
SET @last_style_id=LAST_INSERT_ID();

# Zuordnung Style zur Classe
INSERT INTO u_styles2classes (`class_id`,`style_id`) 
VALUES (@last_class_id,@last_style_id);

##########################################################################################
# Hinzufügen der WMS als Layer vom Amt für Geoinformation, Vermessungs- und Katasterwesen
##########################################################################################
# Anlegen der Layer in der Tabelle 'layer'
#INSERT INTO layer (`Layer_ID`, `Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, #`labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `transparency`, `epsg_code`, #`ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) 
#VALUES 
#('', 'DTK-10 LVA', 3, 2, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DTK10f?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DTK10f&SRS=EPSG:2398&FORMAT=image/png', 7, '', '', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'Orthophotos-LVA', '1.1.0', 'image/png', 60, NULL),
#('', 'DTK50v (farbig)', 3, 12, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DTK50Vf?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DTK50Vf&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'DTK50v (s/w)', 3, 12, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DTK50Vg?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DTK50Vg&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'DTK25v (farbig)', 3, 12, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DTK25Vf?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DTK25Vf&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'DTK25v (s/w)', 3, 12, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DTK25Vg?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DTK25Vg&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'DTK10v (s/w)', 3, 12, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DTK10g?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DTK10g&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'DTK10v (farbig)', 3, 12, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DTK10f?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DTK10f&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'UEK1000 (farbig)', 3, 13, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/UEK1000f?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=UEK1000f&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', #0, '2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'UEK1000 (s/w)', 3, 13, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/UEK1000g?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=UEK1000g&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', #0, '2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'UEK750 (s/w)', 3, 13, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/UEK750g?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=UEK750g&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'UEK750 (farbig)', 3, 13, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/UEK750f?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=UEK750f&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'UEK250 (farbig)', 3, 13, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/UEK250f?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=UEK250f&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'UEK250 (s/w)', 3, 13, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/UEK250g?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=UEK250g&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'DKK100 (farbig)', 3, 13, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DKK100f?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DKK100f&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'DKK100 (s/w)', 3, 13, '', '', '', '', '', '', 0, 0, '', #'http://www.gaia-mv.de/dienste/DKK100g?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DKK100g&SRS=EPSG:2398&FORMAT=image/png', 7, '', 'ID', 3, 'pixels', 0, #'2398', 'EPSG:2398', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#('', 'DOP', 3, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, #'http://www.gaia-mv.de/dienste/DOP?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DOP&SRS=EPSG:25833&FORMAT=image/jpeg', 7, NULL, 'ID', 3, 'pixels', 0, #'25833', 'EPSG:25833', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL),
#(', 'DOP+DLM', 3, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, #'http://www.gaia-mv.de/dienste/DOPDLM?REQUEST=GetMap&VERSION=1.1.1&SERVICE=WMS&LAYERS=DOPDLM&SRS=EPSG:25833&FORMAT=image/jpeg', 7, NULL, 'ID', 3, 'pixels', #0, '25833', 'EPSG:25833', 'DTK50v (RGB)', '1.1.1', 'image/png', 60, NULL);

#######################################################
# Hinzufügen von Classen und Styles von Nutzungsarten #
#######################################################
SET @layer_id=5;
INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('Landwirtschaft', @layer_id, '([Objart] >= 6000 AND [Objart] <= 6900)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '224 224 208', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('Forst', @layer_id, '([Objart] >= 7000 AND [Objart] <= 7600)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '0 128 255', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('Verkehr', @layer_id, '([OBJART] >= 5000 AND [Objart] <= 5940)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '255 192 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('Grünanlage', @layer_id, '([Objart] >= 4200 AND [Objart] <= 4300)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '255 128 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('ERH SPO', @layer_id, '([Objart] >= 4000 AND [Objart] <= 4190)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '255 192 192', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('BFU', @layer_id, '([Objart] >= 3600 AND [Objart] <= 3690)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 128 192', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('BFES', @layer_id, '([Objart] >= 3500 AND [Objart] <= 3590)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 128 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('BFVS', @layer_id, '([Objart] >= 3400 AND [Objart] <= 3490)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 128 255', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('BFLP', @layer_id, '([Objart] >= 3300 AND [Objart] <= 3390)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '128 192 192', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('BFHA', @layer_id, '([Objart] >= 3200 AND [Objart] <= 3290)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 192 255', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('BFAB', @layer_id, '([Objart] >= 3100 AND [Objart] <= 3190)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 192 64', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('BF', @layer_id, '([Objart] = 3000)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '128 208 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFU', @layer_id, '([Objart] >= 2900 AND [Objart] <= 2990)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 255 192', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFF', @layer_id, '([Objart] >= 2800 AND [Objart] <= 2890)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 192 192', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFLF', @layer_id, '([Objart] >= 2700 AND [Objart] <= 2790)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '128 208 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFES', @layer_id, '([Objart] >= 2600 AND [Objart] <= 2690)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '255 255 192', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFVS', @layer_id, '([Objart] >= 2500 AND [Objart] <= 2590)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 192 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFVK', @layer_id, '([Objart] >= 2300 AND [Objart] <= 2390)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '208 208 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFMI', @layer_id, '([Objart] >= 2100 AND [Objart] <= 2190)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '160 128 160', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFHD', @layer_id, '([Objart] >= 1400 AND [Objart] <= 1490)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '255 160 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GF', @layer_id, '([Objart] = 1000)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '208 208 208', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFGI', @layer_id, '([Objart] >= 1700 AND [Objart] <= 1790)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '128 255 255', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFW', @layer_id, '([Objart] >= 1210 AND [Objart] <= 1390)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '128 255 128', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('Wasser', @layer_id, '([Objart] >= 8000 AND [Objart] <= 8900)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '0 160 160', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('GFÖ', @layer_id, '([Objart] >= 1100 AND [Objart] <= 1190)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '192 255 0', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`) VALUES ('sonstiges', @layer_id, '([Objart] >= 9000 AND [Objart] <= 9900)', 0);
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`) VALUES (0, '', 0, '128 192 0', '', '0 0 0', NULL, NULL, 0, '');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes VALUES (@last_class_id,@last_style_id, NULL);
######### Ende des Hinzufügen der Classen und Styles für Nutzungsarten ##############################

######################
####### TeRaLie ######
######################
#
START TRANSACTION;
#

SET @group_id = ????;  # <-- Gruppenid eintragen

### Layer ###
#
# Erbbaurecht
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Erbbaurecht', 2, @group_id, 'SELECT lagebezeichnung, flaeche, (CASE WHEN ebb.vorname IS NOT NULL THEN ebb.vorname||'' '' ELSE '''' END||CASE WHEN ebb.name IS NOT NULL THEN ebb.name||'' '' ELSE '''' END||CASE WHEN ebb.str IS NOT NULL THEN ebb.str||'' '' ELSE '''' END||CASE WHEN ebb.hsnr IS NOT NULL THEN ebb.hsnr||'''' ELSE '''' END||CASE WHEN ebb.hausnr_zusatz IS NOT NULL THEN ebb.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN ebb.pstlz IS NOT NULL THEN ebb.pstlz||'' '' ELSE '''' END||CASE WHEN ebb.ort IS NOT NULL THEN ebb.ort||'' '' ELSE '''' END||CASE WHEN ebb.ortsteil IS NOT NULL THEN ebb.ortsteil ELSE '''' END) as erbbauberechtigter, (CASE WHEN lie_festsetzungen.betrag IS NOT NULL THEN lie_festsetzungen.betrag||'' '' ELSE '''' END||CASE WHEN faellig_txt IS NOT NULL THEN faellig_txt ELSE '''' END||CASE WHEN lie_festsetzungen.ab IS NOT NULL THEN  '' ab ''||lie_festsetzungen.ab ELSE '''' END) as festsetzung, (CASE WHEN notare.vorname IS NOT NULL THEN notare.vorname||'' '' ELSE '''' END||CASE WHEN notare.name IS NOT NULL THEN notare.name||'' '' ELSE '''' END||CASE WHEN notare.str IS NOT NULL THEN notare.str||'' '' ELSE '''' END||CASE WHEN notare.hsnr IS NOT NULL THEN notare.hsnr||'''' ELSE '''' END||CASE WHEN notare.hausnr_zusatz IS NOT NULL THEN notare.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN notare.pstlz IS NOT NULL THEN notare.pstlz||'' '' ELSE '''' END||CASE WHEN notare.ortsteil IS NOT NULL THEN notare.ortsteil ELSE '''' END) as notar, (CASE WHEN zahlers.vorname IS NOT NULL THEN zahlers.vorname||'' '' ELSE '''' END||CASE WHEN zahlers.name IS NOT NULL THEN zahlers.name||'' '' ELSE '''' END||CASE WHEN zahlers.str IS NOT NULL THEN zahlers.str||'' '' ELSE '''' END||CASE WHEN zahlers.hsnr IS NOT NULL THEN zahlers.hsnr||'''' ELSE '''' END||CASE WHEN zahlers.hausnr_zusatz IS NOT NULL THEN zahlers.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN zahlers.pstlz IS NOT NULL THEN zahlers.pstlz||'' '' ELSE '''' END||CASE WHEN zahlers.ortsteil IS NOT NULL THEN zahlers.ortsteil ELSE '''' END) as zahler, erbbaurecht.lfdnr, erbbaurecht.flurstkennz, erbbaurecht.status, erbbaurecht.erbbaulage, erbbaurecht.vertragsflaeche, erbbaurecht.vertrag_vom, erbbaurecht.vertragsbeginn, erbbaurecht.vertragsende, erbbaurecht.urkunde_vom, erbbaurecht.urkunde_az, erbbaurecht.grundbuchst, erbbaurecht.eintrag_am, erbbaurecht.erste_faelligkeit_am, erbbaurecht.pknr_notar, erbbaurecht.pknr_zahler, erbbaurecht.erb_form, erbbaurecht.liegenschaftsart, erbbaurecht.bereich, erbbaurecht.projekt, (SELECT alkobj_e_fla.the_geom from alkobj_e_fla where lie_d_erbbaurecht.lfdnr = erbbaurecht.lfdnr AND lie_d_erbbaurecht.flurstkennz = erbbaurecht.flurstkennz AND alkobj_e_fla.objnr = alknflst.objnr AND alknflst.flurstkennz = lie_d_erbbaurecht.flurstkennz) as the_geom FROM lie_d_erbbaurecht as erbbaurecht LEFT JOIN lie_v_erb_formen ON lie_v_erb_formen.erb_form = erbbaurecht.erb_form LEFT JOIN lie_eigentuemerangaben notare ON notare.pknr = erbbaurecht.pknr_notar LEFT JOIN lie_eigentuemerangaben zahlers ON zahlers.pknr = erbbaurecht.pknr_zahler LEFT JOIN alb_f_lage ON alb_f_lage.flurstkennz = erbbaurecht.flurstkennz LEFT JOIN alb_flurstuecke ON alb_flurstuecke.flurstkennz = erbbaurecht.flurstkennz LEFT JOIN lie_erb_eb LEFT JOIN lie_eigentuemerangaben ebb ON ebb.pknr = lie_erb_eb.pknr_eb ON lie_erb_eb.lfdnr = erbbaurecht.lfdnr AND lie_erb_eb.flurstkennz = erbbaurecht.flurstkennz AND lie_erb_eb.lfdnr_eb = (SELECT MAX(lfdnr_eb) from lie_erb_eb where lie_erb_eb.flurstkennz = erbbaurecht.flurstkennz AND lie_erb_eb.lfdnr = erbbaurecht.lfdnr) LEFT JOIN lie_festsetzungen LEFT JOIN lie_v_faelligkeitsarten ON lie_v_faelligkeitsarten.faellig = lie_festsetzungen.faellig ON lie_festsetzungen.flurstkennz = erbbaurecht.flurstkennz AND lie_festsetzungen.lfdnr = erbbaurecht.lfdnr AND lie_festsetzungen.ab = (SELECT MAX(ab) from lie_festsetzungen where lie_festsetzungen.flurstkennz = erbbaurecht.flurstkennz AND lie_festsetzungen.lfdnr = erbbaurecht.lfdnr ) WHERE 1=1', 'the_geom from (select oid,lfdnr, the_geom from alkobj_e_fla,lie_d_erbbaurecht where alkobj_e_fla.objnr = alknflst.objnr and alknflst.flurstkennz = lie_d_erbbaurecht.flurstkennz) as foo using unique oid using srid=2398', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Erbbaurecht_id=LAST_INSERT_ID();

# Veräußerung
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Veräußerung', 2, @group_id, 'SELECT lagebezeichnung, flaeche, (CASE WHEN kaeufers.vorname IS NOT NULL THEN kaeufers.vorname||'' '' ELSE '''' END||CASE WHEN kaeufers.name IS NOT NULL THEN kaeufers.name||'' '' ELSE '''' END||CASE WHEN kaeufers.str IS NOT NULL THEN kaeufers.str||'' '' ELSE '''' END||CASE WHEN kaeufers.hsnr IS NOT NULL THEN kaeufers.hsnr||'''' ELSE '''' END||CASE WHEN kaeufers.hausnr_zusatz IS NOT NULL THEN kaeufers.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN kaeufers.pstlz IS NOT NULL THEN kaeufers.pstlz||'' '' ELSE '''' END||CASE WHEN kaeufers.ort IS NOT NULL THEN kaeufers.ort ELSE '''' END) as kaeufer, (CASE WHEN notare.vorname IS NOT NULL THEN notare.vorname||'' '' ELSE '''' END||CASE WHEN notare.name IS NOT NULL THEN notare.name||'' '' ELSE '''' END||CASE WHEN notare.str IS NOT NULL THEN notare.str||'' '' ELSE '''' END||CASE WHEN notare.hsnr IS NOT NULL THEN notare.hsnr||'''' ELSE '''' END||CASE WHEN notare.hausnr_zusatz IS NOT NULL THEN notare.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN notare.pstlz IS NOT NULL THEN notare.pstlz||'' '' ELSE '''' END||CASE WHEN notare.ort IS NOT NULL THEN notare.ort ELSE '''' END) as notar, veraeusserung.lfdnr, veraeusserung.flurstkennz, veraeusserung.status, veraeusserung.verkaufsflaeche, veraeusserung.bebaut, veraeusserung.bereich, veraeusserung.projekt, veraeusserung.veraeuserung_durch, veraeusserung.beschluss1_vom, veraeusserung.beschluss1_az, veraeusserung.beschluss2_vom, beschluss2_az, veraeusserung.vermessungsantrag_vom, veraeusserung.messungsanerkennung_am, veraeusserung.vn, veraeusserung.vertrag_vom, veraeusserung.vertrag_az, veraeusserung.pknr_notar, veraeusserung.urkunde_vom, veraeusserung.urkunde_az, veraeusserung.gbeintrag_am, veraeusserung.grundbuchst, veraeusserung.besitzuebergang_am, veraeusserung.kaufpreis, veraeusserung.preis_ges, veraeusserung.preis_qm_o_nk, veraeusserung.preis_qm_m_nk, veraeusserung.erschliessungskosten, veraeusserung.nebenkosten, alkobj_e_fla.the_geomFROM lie_eigentuemerangaben as kaeufers, lie_veraeusserung_kaeufer, lie_d_veraeusserung as veraeusserung LEFT JOIN alknflst LEFT JOIN alkobj_e_fla ON alkobj_e_fla.objnr = alknflst.objnr ON alknflst.flurstkennz = veraeusserung.flurstkennz LEFT JOIN lie_eigentuemerangaben notare ON notare.pknr = veraeusserung.pknr_notar LEFT JOIN alb_f_lage ON alb_f_lage.flurstkennz = veraeusserung.flurstkennz LEFT JOIN alb_flurstuecke ON alb_flurstuecke.flurstkennz = veraeusserung.flurstkennz WHERE lie_veraeusserung_kaeufer.flurstkennz = veraeusserung.flurstkennz AND lie_veraeusserung_kaeufer.lfdnr = veraeusserung.lfdnr AND lie_veraeusserung_kaeufer.lfdnr_kaeuf = (SELECT MAX(lfdnr_kaeuf) from lie_veraeusserung_kaeufer where lie_veraeusserung_kaeufer.flurstkennz = veraeusserung.flurstkennz AND lie_veraeusserung_kaeufer.lfdnr = veraeusserung.lfdnr) AND kaeufers.pknr = lie_veraeusserung_kaeufer.pknr_kaeuf', 'the_geom from (select oid,lfdnr, the_geom from alkobj_e_fla,lie_d_veraeusserung where alkobj_e_fla.objnr = alknflst.objnr and alknflst.flurstkennz = lie_d_veraeusserung.flurstkennz) as foo using unique oid using srid=2398', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Veraeusserung_id=LAST_INSERT_ID();

# Verpachtung
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Verpachtung', 2, @group_id, 'SELECT DISTINCT ARRAY(SELECT lie_pacht2flurstuecke.flurstkennz from lie_pacht2flurstuecke where lie_pacht2flurstuecke.lfdnr = pacht2flurst.lfdnr) as flurstuecke, (CASE WHEN pae.vorname IS NOT NULL THEN pae.vorname||'' '' ELSE '''' END||CASE WHEN pae.name IS NOT NULL THEN pae.name||'' '' ELSE '''' END||CASE WHEN pae.str IS NOT NULL THEN pae.str||'' '' ELSE '''' END||CASE WHEN pae.hsnr IS NOT NULL THEN pae.hsnr||'''' ELSE '''' END||CASE WHEN pae.hausnr_zusatz IS NOT NULL THEN pae.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN pae.pstlz IS NOT NULL THEN pae.pstlz||'' '' ELSE '''' END||CASE WHEN pae.ort IS NOT NULL THEN pae.ort||'' '' ELSE '''' END||CASE WHEN pae.ortsteil IS NOT NULL THEN pae.ortsteil ELSE '''' END) as paechter, (CASE WHEN bevollm.vorname IS NOT NULL THEN bevollm.vorname||'' '' ELSE '''' END||CASE WHEN bevollm.name IS NOT NULL THEN bevollm.name||'' '' ELSE '''' END||CASE WHEN bevollm.str IS NOT NULL THEN bevollm.str||'' '' ELSE '''' END||CASE WHEN bevollm.hsnr IS NOT NULL THEN bevollm.hsnr||'''' ELSE '''' END||CASE WHEN bevollm.hausnr_zusatz IS NOT NULL THEN bevollm.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN bevollm.pstlz IS NOT NULL THEN bevollm.pstlz||'' '' ELSE '''' END||CASE WHEN bevollm.ort IS NOT NULL THEN bevollm.ort||'' '' ELSE '''' END||CASE WHEN bevollm.ortsteil IS NOT NULL THEN bevollm.ortsteil ELSE '''' END) as bevollmaechtigter, (CASE WHEN zahlers.vorname IS NOT NULL THEN zahlers.vorname||'' '' ELSE '''' END||CASE WHEN zahlers.name IS NOT NULL THEN zahlers.name||'' '' ELSE '''' END||CASE WHEN zahlers.str IS NOT NULL THEN zahlers.str||'' '' ELSE '''' END||CASE WHEN zahlers.hsnr IS NOT NULL THEN zahlers.hsnr||'''' ELSE '''' END||CASE WHEN zahlers.hausnr_zusatz IS NOT NULL THEN zahlers.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN zahlers.pstlz IS NOT NULL THEN zahlers.pstlz||'' '' ELSE '''' END||CASE WHEN zahlers.ortsteil IS NOT NULL THEN zahlers.ortsteil ELSE '''' END) as zahler, (SELECT geomunion(alkobj_e_fla.the_geom) from alkobj_e_fla where lie_pacht2flurstuecke.lfdnr = pacht2flurst.lfdnr AND alkobj_e_fla.objnr = alknflst.objnr AND alknflst.flurstkennz = lie_pacht2flurstuecke.flurstkennz) as the_geom, lie_d_pacht.objbezeichnung, lie_d_pacht.status, lie_d_pacht.pknr_bevollmaechtigter, lie_d_pacht.vertragsart, lie_d_pacht.bereich, lie_d_pacht.projekt, lie_d_pacht.vertrag_vom, lie_d_pacht.az_vertrag, lie_d_pacht.vertragsbeginn, lie_d_pacht.vertragsende, lie_d_pacht.kuend_frist, lie_d_pacht.kuend_erstmals_zum, lie_d_pacht.gekuendigt_am, lie_d_pacht.gekuendigt_zum, lie_d_pacht.verlaengerung_ab, lie_d_pacht.verlaengerung_bis, lie_d_pacht.pknr_zahler, lie_d_pacht.lfdnr FROM lie_d_pacht LEFT JOIN lie_eigentuemerangaben bevollm ON bevollm.pknr = lie_d_pacht.pknr_bevollmaechtigter LEFT JOIN lie_eigentuemerangaben zahlers ON zahlers.pknr = lie_d_pacht.pknr_zahler LEFT JOIN lie_pacht2flurstuecke pacht2flurst ON lie_d_pacht.lfdnr = pacht2flurst.lfdnr LEFT JOIN lie_pacht_pae LEFT JOIN lie_eigentuemerangaben pae ON pae.pknr = lie_pacht_pae.pknr ON lie_pacht_pae.lfdnr = lie_d_pacht.lfdnr AND lie_pacht_pae.lfdnr_pae = (SELECT MAX(lfdnr_pae) from lie_pacht_pae where lie_pacht_pae.lfdnr = lie_d_pacht.lfdnr) WHERE 1=1', 'the_geom from (SELECT geomunion(alkobj_e_fla.the_geom) as the_geom, lie_d_pacht.lfdnr, lie_d_pacht.oid FROM alkobj_e_fla, alknflst, lie_pacht2flurstuecke, lie_d_pacht WHERE lie_d_pacht.lfdnr = lie_pacht2flurstuecke.lfdnr AND alkobj_e_fla.objnr = alknflst.objnr AND alknflst.flurstkennz = lie_pacht2flurstuecke.flurstkennz group by lie_d_pacht.lfdnr, lie_d_pacht.oid) as foo using unique oid using srid=2398', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Verpachtung_id=LAST_INSERT_ID();

# Personen
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Personen', 5, @group_id, 'SELECT * from lie_eigentuemerangaben where 1=1', '', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, '', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Personen_id=LAST_INSERT_ID();

# Erbbauberechtigte
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Erbbauberechtigte', 5, @group_id, 'SELECT lie_erb_eb.*, (CASE WHEN ebb.vorname IS NOT NULL THEN ebb.vorname||'' '' ELSE '''' END||CASE WHEN ebb.name IS NOT NULL THEN ebb.name||'' '' ELSE '''' END||CASE WHEN ebb.str IS NOT NULL THEN ebb.str||'' '' ELSE '''' END||CASE WHEN ebb.hsnr IS NOT NULL THEN ebb.hsnr||'''' ELSE '''' END||CASE WHEN ebb.hausnr_zusatz IS NOT NULL THEN ebb.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN ebb.pstlz IS NOT NULL THEN ebb.pstlz||'' '' ELSE '''' END||CASE WHEN ebb.ort IS NOT NULL THEN ebb.ort||'' '' ELSE '''' END||CASE WHEN ebb.ortsteil IS NOT NULL THEN ebb.ortsteil ELSE '''' END) as erbbauberechtigter from lie_erb_eb, lie_eigentuemerangaben as ebb where lie_erb_eb.pknr_eb = ebb.pknr', '', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Erbbauberechtigte_id=LAST_INSERT_ID();

# Festsetzungen
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Festsetzungen', 5, @group_id, 'SELECT * from lie_festsetzungen where (1=1)', '', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Festsetzungen_id=LAST_INSERT_ID();

# Käufer
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Käufer', 5, @group_id, 'SELECT lie_veraeusserung_kaeufer.*, (CASE WHEN kaeufers.vorname IS NOT NULL THEN kaeufers.vorname||'' '' ELSE '''' END||CASE WHEN kaeufers.name IS NOT NULL THEN kaeufers.name||'' '' ELSE '''' END||CASE WHEN kaeufers.str IS NOT NULL THEN kaeufers.str||'' '' ELSE '''' END||CASE WHEN kaeufers.hsnr IS NOT NULL THEN kaeufers.hsnr||'''' ELSE '''' END||CASE WHEN kaeufers.hausnr_zusatz IS NOT NULL THEN kaeufers.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN kaeufers.pstlz IS NOT NULL THEN kaeufers.pstlz||'' '' ELSE '''' END||CASE WHEN kaeufers.ortsteil IS NOT NULL THEN kaeufers.ortsteil ELSE '''' END) as kaeufer FROM lie_veraeusserung_kaeufer, lie_eigentuemerangaben as kaeufers WHERE lie_veraeusserung_kaeufer.pknr_kaeuf = kaeufers.pknr', '', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Kaeufer_id=LAST_INSERT_ID();

# verpachtete_Flurstuecke
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('verpachtete_Flurstuecke', 5, @group_id, 'SELECT lie_pacht2flurstuecke.*, (CASE WHEN bezeichnung IS NOT NULL THEN bezeichnung||'' '' ELSE '''' END||CASE WHEN einheiten IS NOT NULL THEN einheiten||'' m2 '' ELSE '''' END||CASE WHEN einheitenpreis IS NOT NULL THEN ''x ''||einheitenpreis||'' '' ELSE '''' END||CASE WHEN summe IS NOT NULL THEN '' = ''||summe||'' '' ELSE '''' END) as berechnung, (CASE WHEN lie_festsetzungen.betrag IS NOT NULL THEN lie_festsetzungen.betrag||'' '' ELSE '''' END||CASE WHEN faellig_txt IS NOT NULL THEN faellig_txt||'' ab '' ELSE '''' END||CASE WHEN lie_festsetzungen.ab IS NOT NULL THEN lie_festsetzungen.ab ELSE '''' END) as festsetzung FROM lie_pacht2flurstuecke LEFT JOIN lie_festsetzungen LEFT JOIN lie_v_faelligkeitsarten ON lie_v_faelligkeitsarten.faellig = lie_festsetzungen.faellig ON lie_festsetzungen.lfdnr = lie_pacht2flurstuecke.lfdnr AND lie_festsetzungen.flurstkennz = lie_pacht2flurstuecke.flurstkennz AND lie_festsetzungen.ab = (SELECT MAX(ab) from lie_festsetzungen where lie_festsetzungen.flurstkennz = lie_pacht2flurstuecke.flurstkennz AND lie_festsetzungen.lfdnr = lie_pacht2flurstuecke.lfdnr ) LEFT JOIN lie_pacht_bereiche LEFT JOIN lie_v_pachtflaechenarten ON lie_v_pachtflaechenarten.pachtflaechenart = lie_pacht_bereiche.pachtflaechenart ON lie_pacht_bereiche.lfdnr = lie_pacht2flurstuecke.lfdnr AND lie_pacht_bereiche.lfdnr_beteil = lie_pacht2flurstuecke.lfdnr_beteil  WHERE 1=1 ', '', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @verpachtete_Flurstuecke_id=LAST_INSERT_ID();

# Berechnungen
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Berechnungen', 5, @group_id, 'SELECT * from lie_pacht_bereiche where (1=1)', '', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Berechnungen_id=LAST_INSERT_ID();

# Pächter
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('Pächter', 5, @group_id, 'SELECT lie_pacht_pae.*, \r\n(CASE WHEN pae.vorname IS NOT NULL THEN pae.vorname||'' '' ELSE '''' END||CASE WHEN pae.name IS NOT NULL THEN pae.name||'' '' ELSE '''' END||CASE WHEN pae.str IS NOT NULL THEN pae.str||'' '' ELSE '''' END||CASE WHEN pae.hsnr IS NOT NULL THEN pae.hsnr||'''' ELSE '''' END||CASE WHEN pae.hausnr_zusatz IS NOT NULL THEN pae.hausnr_zusatz||'' '' ELSE '''' END||CASE WHEN pae.pstlz IS NOT NULL THEN pae.pstlz||'' '' ELSE '''' END||CASE WHEN pae.ort IS NOT NULL THEN pae.ort||'' '' ELSE '''' END||CASE WHEN pae.ortsteil IS NOT NULL THEN pae.ortsteil ELSE '''' END) as paechter \r\nFROM lie_pacht_pae, lie_eigentuemerangaben as pae\r\nWHERE lie_pacht_pae.pknr = pae.pknr', '', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'lfdnr', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @Paechter_id=LAST_INSERT_ID();

### Classes ###
#
INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @Erbbaurecht_id, '', 2, NULL);
SET @Erbbaurecht_class_id=LAST_INSERT_ID();

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @Veraeusserung_id, '', 2, NULL);
SET @Veraeusserung_class_id=LAST_INSERT_ID();

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @Verpachtung_id, '', 2, NULL);
SET @Verpachtung_class_id=LAST_INSERT_ID();

### Styles ###
#
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `width`, `sizeitem`) VALUES (NULL, NULL, 1, '75 165 80', NULL, '000 000 000', NULL, NULL, 0, '', NULL, NULL);
SET @Erbbaurecht_style_id=LAST_INSERT_ID();

INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `width`, `sizeitem`) VALUES (NULL, NULL, 1, '125 155 170', NULL, '000 000 000', NULL, NULL, 0, '', NULL, NULL);
SET @Veraeusserung_style_id=LAST_INSERT_ID();

INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `width`, `sizeitem`) VALUES (NULL, NULL, 1, '225 195 170', NULL, '000 000 000', NULL, NULL, 0, '', NULL, NULL);
SET @Verpachtung_style_id=LAST_INSERT_ID();

### Styles2Classes ###
#
INSERT INTO u_styles2classes (`class_id`, `style_id`, `drawingorder`) VALUES (@Erbbaurecht_class_id, @Erbbaurecht_style_id, 2);
INSERT INTO u_styles2classes (`class_id`, `style_id`, `drawingorder`) VALUES (@Veraeusserung_class_id, @Veraeusserung_style_id, 2);
INSERT INTO u_styles2classes (`class_id`, `style_id`, `drawingorder`) VALUES (@Verpachtung_class_id, @Verpachtung_style_id, 2);

### layer_attributes ###
#
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'erb_form', 'Auswahlfeld', 'select erb_form_txt as output, erb_form as value from lie_v_erb_formen', 'Erbform');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'lfdnr', 'Text', '', 'laufende Nr.');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'flurstkennz', 'Text', '', 'Flurstück');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'status', 'Text', '', 'Status');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'erbbaulage', 'Text', '', 'Erbbaulage');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'vertragsflaeche', 'Text', '', 'Fläche laut Vertrag');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'liegenschaftsart', 'Auswahlfeld', 'select lie_art_txt as output, lie_art as value from lie_v_lie_arten', 'Liegenschaftsart');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'bereich', 'Auswahlfeld', 'select bereich_txt as output, bereich as value from lie_v_bereiche', 'Bereich');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'projekt', 'Auswahlfeld', 'select projekt_txt as output, projekt as value from lie_v_projekte', 'Projekt');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'vertrag_vom', 'Text', '', 'Vertrag vom');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'vertragsbeginn', 'Text', '', 'Vertragsbeginn');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'vertragsende', 'Text', '', 'Vertragsende');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'pknr_notar', 'Text', '', 'pknr des Notars');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'urkunde_vom', 'Text', '', 'Urkunde vom');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'urkunde_az', 'Text', '', 'Urkunde Aktenzeichen');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'grundbuchst', 'Text', '', 'Grundbuchstelle');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'eintrag_am', 'Text', '', 'eingetragen am');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'erste_faelligkeit_am', 'Text', '', 'Fälligkeit laut Vertrag');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'pknr_zahler', 'Text', '', 'pknr des Zahlers');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'faellig_txt', 'Text', '', 'fällig');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'ab', 'Text', '', '');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'erb_form_txt', 'Text', '', 'Erbform');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'lie_art_txt', 'Text', '', 'Liegenschaftsart');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'betrag', 'Text', '', 'Betrag');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'the_geom', 'Geometrie', '', '');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'bereich_txt', 'Text', '', 'Bereich');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'projekt_txt', 'Text', '', 'Projekt');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'erbbauberechtigter', 'SubFormPK', concat(@Erbbauberechtigte_id, ',flurstkennz,lfdnr'), 'Erbbauberechtigter');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'lagebezeichnung', 'Text', '', 'Lage');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'flaeche', 'Text', '', 'Größe im ALB');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'ebbnr', 'Text', '', 'pknr des EBB');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'notar', 'SubFormFK', concat(@Personen_id, ',pknr_notar pknr'), 'Notariat');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'zahler', 'SubFormFK', concat(@Personen_id, ',pknr_zahler pknr'), 'Zahler/Empfänger');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'faellig', 'Auswahlfeld', 'select faellig_txt as output, faellig as value from lie_v_faelligkeitsarten', 'fällig');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Erbbaurecht_id, 'festsetzung', 'SubFormPK', concat(@Festsetzungen_id, ',flurstkennz,lfdnr'), 'Festsetzung');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'the_geom', 'Geometrie', '', '');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'lfdnr', 'Text', '', 'laufende Nr.');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'flurstkennz', 'Text', '', 'Flurstück');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'status', 'Text', '', 'Status');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'verkaufsflaeche', 'Text', '', 'verkaufte Fläche');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'bebaut', 'Text', '', '');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'bereich', 'Auswahlfeld', 'select bereich_txt as output, bereich as value from lie_v_bereiche', 'Bereich');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'projekt', 'Auswahlfeld', 'select projekt_txt as output, projekt as value from lie_v_projekte', 'Projekt');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'veraeuserung_durch', 'Auswahlfeld', 'select text as output, id as value from lie_v_rechtsgeschichte ', 'Veräußerung durch');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'beschluss1_vom', 'Text', '', 'Beschluß1 vom');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'beschluss1_az', 'Text', '', 'Beschluß1 AZ');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'beschluss2_vom', 'Text', '', 'Beschluß2 vom');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'beschluss2_az', 'Text', '', 'Beschluß2 AZ');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'vermessungsantrag_vom', 'Text', '', 'Vermessungsantrag vom');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'messungsanerkennung_am', 'Text', '', 'Messungsanerkennung am');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'vn', 'Text', '', 'VN');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'vertrag_vom', 'Text', '', 'Vertrag vom');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'vertrag_az', 'Text', '', 'Vertrag AZ');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'pknr_notar', 'Text', '', '');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'urkunde_vom', 'Text', '', 'Urkunde vom');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'urkunde_az', 'Text', '', 'Urkunde AZ');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'gbeintrag_am', 'Text', '', 'G.Bucheintrag am');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'grundbuchst', 'Text', '', 'Grundbuchstelle');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'besitzuebergang_am', 'Text', '', 'Besitzübergang am');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'kaufpreis', 'Text', '', 'Kaufpreis');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'preis_ges', 'Text', '', 'Preis gesamt');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'preis_qm_o_nk', 'Text', '', 'Preis pro qm ohne NK');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'preis_qm_m_nk', 'Text', '', 'Preis pro qm mit NK');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'erschliessungskosten', 'Text', '', 'Erschließungskosten');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'nebenkosten', 'Text', '', 'Nebenkosten');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'notar', 'SubFormFK', concat(@Personen_id, ',pknr_notar pknr'), 'Notariat');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'kaeufer', 'SubFormPK', concat(@Kaeufer_id, ',flurstkennz,lfdnr'), 'Käufer');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'lagebezeichnung', 'Text', '', 'Lage');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Veraeusserung_id, 'flaeche', 'Text', '', 'Größe im ALB');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'flurstuecke', 'SubFormPK', concat(@verpachtete_Flurstuecke_id, ',lfdnr'), 'Flurstücke');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'the_geom', 'Geometrie', '', '');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'paechter', 'SubFormPK', concat(@Paechter_id, ',lfdnr'), 'Pächter');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'bevollmaechtigter', 'SubFormFK', concat(@Personen_id, ',pknr_bevollmaechtigter pknr'), 'Bevollmächtigter');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'zahler', 'SubFormFK', concat(@Personen_id, ',pknr_zahler pknr'), 'Zahler/Empfänger');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'lfdnr', 'Text', '', '');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'objbezeichnung', 'Text', '', 'Objektbezeichnung');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'status', 'Text', '', 'Status');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'pknr_bevollmaechtigter', 'Text', '', '');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'vertragsart', 'Auswahlfeld', 'select bezeichnung as output, artnr as value from lie_v_vertragsarten', 'Vertragsart');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'bereich', 'Auswahlfeld', 'select bereich_txt as output, bereich as value from lie_v_bereiche', 'Bereich');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'projekt', 'Auswahlfeld', 'select projekt_txt as output, projekt as value from lie_v_projekte', 'Projekt');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'vertrag_vom', 'Text', '', 'Vertrag vom');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'az_vertrag', 'Text', '', 'AZ Vertrag');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'vertragsbeginn', 'Text', '', 'Vertragsbeginn');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'vertragsende', 'Text', '', 'Vertragsende');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'kuend_frist', 'Text', '', 'Kündigungsfrist');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'kuend_erstmals_zum', 'Text', '', 'Kündigung erstmals zum');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'gekuendigt_am', 'Text', '', 'gekündigt am');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'gekuendigt_zum', 'Text', '', 'gekündigt zum');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'verlaengerung_ab', 'Text', '', 'Verlängerung ab');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'verlaengerung_bis', 'Text', '', 'Verlängerung bis');
INSERT INTO layer_attributes (`layer_id`, `name`, `form_element_type`, `options`, `alias`) VALUES (@Verpachtung_id, 'pknr_zahler', 'Text', '', '');

### u_menues ###
#
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('TeRaLie', 'index.php?go=changemenue', 0, 1, NULL);
SET @teralie_id=LAST_INSERT_ID();
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('Personen suchen', concat('index.php?go=Layer-Suche&selected_layer_id=', @Personen_id), @teralie_id, 2, NULL);
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('Erbbaurechte suchen', concat('index.php?go=Layer-Suche&selected_layer_id=', @Erbbaurecht_id), @teralie_id, 2, NULL);
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('Ver&auml;usserung suchen', concat('index.php?go=Layer-Suche&selected_layer_id=', @Veraeusserung_id), @teralie_id, 2, NULL);
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('Verpachtung suchen', concat('index.php?go=Layer-Suche&selected_layer_id=', @Verpachtung_id), @teralie_id, 2, NULL);
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('neue Person', concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', @Personen_id), @teralie_id, 2, NULL);
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('neues Erbbaurecht', concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', @Erbbaurecht_id), @teralie_id, 2, NULL);
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('neue Ver&auml;usserung', concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', @Veraeusserung_id), @teralie_id, 2, NULL);
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`) VALUES ('neue Verpachtung', concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', @Verpachtung_id), @teralie_id, 2, NULL);

COMMIT;


##############################
###### Anliegerbeiträge ######
##############################
#
START TRANSACTION;
#
SET @group_id = ????;  # <-- Gruppenid eintragen
#
### Layer ###
#
INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('AB_Strassen', 2, @group_id, 'select id, the_geom from anliegerbeitraege_strassen where 1=1', 'the_geom from (select oid, id, the_geom from anliegerbeitraege_strassen) as foo using unique oid using srid=2398', '', '', '', '', 50000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'id', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @strassen_id=LAST_INSERT_ID();

INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `logconsume`) VALUES ('AB_Bereiche', 2, @group_id, 'select id, flaeche, the_geom from anliegerbeitraege_bereiche where 1=1', 'the_geom from (select oid, id, flaeche||'' m2'' as flaechenangabe, the_geom from anliegerbeitraege_bereiche) as foo using unique oid using srid=2398', '', '', '', 'flaechenangabe', 10000, 0, '', 'user=kvwmap password=kvwmap dbname=kvwmapsp', 6, 'id', '', 3, 'pixels', '2398', 'EPSG:2398', '', '1.1.0', 'image/png', 60, NULL);
SET @bereiche_id=LAST_INSERT_ID();

### Classes ###
#
INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @strassen_id, '', 2, NULL);
SET @strassen_class_id=LAST_INSERT_ID();

INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @bereiche_id, '', 2, NULL);
SET @bereiche_class_id=LAST_INSERT_ID();


### Styles ###
#
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `width`, `sizeitem`) VALUES (NULL, NULL, 1, '105 135 140', NULL, '000 000 000', NULL, NULL, 0, '', NULL, NULL);
SET @strassen_style_id=LAST_INSERT_ID();

INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `width`, `sizeitem`) VALUES (NULL, NULL, 1, '225 155 170', NULL, '000 000 000', NULL, NULL, 0, '', NULL, NULL);
SET @bereiche_style_id=LAST_INSERT_ID();

### Styles2Classes ###
#
INSERT INTO u_styles2classes (`class_id`, `style_id`, `drawingorder`) VALUES (@strassen_class_id, @strassen_style_id, 2);
INSERT INTO u_styles2classes (`class_id`, `style_id`, `drawingorder`) VALUES (@bereiche_class_id, @bereiche_style_id, 2);

COMMIT;
