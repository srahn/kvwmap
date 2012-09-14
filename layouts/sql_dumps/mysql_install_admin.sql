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

# Style-Label-Editor
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Style-u.Labeleditor', 'index.php?go=Style_Label_Editor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,79);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# neuer Datensatz
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('neuer Datensatz', 'index.php?go=neuer_Layer_Datensatz', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,80);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Import/Export
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Import/Export', 'index.php?go=changemenue', 0, 1, NULL, 40);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,81);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# WMS-Export
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('WMS-Export', 'index.php?go=WMS_Export', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,82);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# WMS-Import
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('WMS-Import', 'index.php?go=WMS_Import', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,83);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Druckausgabe
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Druckausgabe', 'index.php?go=ExportMapToPDF', @last_level1menue_id, 2, '_blank');
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,84);
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
