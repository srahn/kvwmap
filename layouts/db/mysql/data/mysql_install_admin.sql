# SQL-Statements für die Einrichtung und Administration eines kvwmap Projektes
#
# Voraussetzungen/Vorarbeiten
#
# MySQL ist installiert
#
# Zusätzlich ist die kvwmap-Datenbank angelegt
#
# Die folgenden SQL-Statements in einem SQL-Fenster z.B. in phpMyAdmin ausführen
SET CHARACTER SET 'utf8';
#!!!!!!!!!!!!!!!!!!!
# Bei verschiedenen SQL-Anweisungen sind vorher Konstanten für die Einträge in der Datenbank zu setzen
# Benutzer für den Zugriff auf die PostGIS-Datenbank
SET @pg_user='kvwmap';
SET @pg_dbname='kvwmapsp';
# Benutzer in der Mysql-Datenbank für den die Eintragungen vorgenommen werden sollen
SET @user_id=1;
# Stelle in der Mysql-Datenbank, für die die Eintragungen vorgenommen werden sollen
SET @stelle_id=1;
# Password für Nutzer kvwmap bei der Anmeldung an kvwmap
SET @kvwmap_password='kvwmap';
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
INSERT INTO `stelle` ( `ID` , `Bezeichnung` , `start` , `stop` , `minxmax` , `minymax` , `maxxmax` , `maxymax` , `epsg_code`, `Referenzkarte_ID` , `Authentifizierung` , `ALB_status` , `wappen`)
VALUES (
@stelle_id, 'Administration', '0000-00-00', '0000-00-00', 201165, 5867815, 477900, 6081468, 25833, '1', '1', '30', 'Logo_GDI-Service_200x47.png');

# Nutzer anlegen
INSERT INTO `user` ( `ID` , `login_name` , `Name` , `Vorname` , `passwort` , `Funktion` , `stelle_id` , `phon` , `email` )
VALUES (
@user_id, 'kvwmap', 'kvwmap', 'hans', MD5(@kvwmap_password), 'admin', '1', '', 'admin@localhost.de'
);
# Rolle zuweisen
INSERT INTO `rolle` ( `user_id` , `stelle_id` , `nImageWidth` , `nImageHeight` , `minx` , `miny` , `maxx` , `maxy` , `nZoomFactor` , `selectedButton` , `epsg_code` )
VALUES (
@user_id, @stelle_id, '700', '500', 201165, 5867815, 477900, 6081468, '2', 'zoomin', '25833');
# Referenzkarte eintragen
INSERT INTO `referenzkarten` (`ID`,`Name` , `Dateiname` , `xmin` , `ymin` , `xmax` , `ymax` , `width` , `height`, `epsg_code`)
VALUES (
 '1','Uebersichtskarte', 'uebersicht_mv.png', 204240, 5889964, 462705, 6063324, '205', '139', 25833
);

############################################################################
# Sicherheitskritische Anwendungsfälle Werte für go Variablen              #
############################################################################
INSERT INTO `u_funktionen` (`id`, `bezeichnung`, `link`) VALUES
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
(14, 'Nachweisloeschen', NULL),
(17, 'Externer_Druck', NULL),
(18, 'Adressaenderungen', NULL),
(19, 'sendeFestpunktskizze', NULL),
(20, 'Nachweise_bearbeiten', NULL),
(22, 'Kolibristart', NULL),
(23, 'Administratorfunktionen', NULL),
(24, 'Stelle_waehlen', NULL),
(25, 'show_snippet', NULL);

####################################################################################
# Eintragen von Berechtigungen für einen Administrator zum Ausführen von Funktionen
####################################################################################
# 2006-05-12
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (2, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (3, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (4, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (5, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (6, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (7, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (8, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (9, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (10, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (11, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (12, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (14, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (17, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (18, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (19, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (20, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (22, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (23, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (24, @stelle_id);
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (25, @stelle_id);

###########################
# Einträge der Menüpunkte #
###########################
#### gegebenenfalls vorherige Einträge löschen
# TRUNCATE u_menues;
# TRUNCATE u_menue2stelle;
# TRUNCATE u_menue2rolle;

# Stellen wählen
INSERT INTO `u_menues` (name, name_english, links, obermenue, menueebene, target, `order`, `title`, `button_class`) VALUES ('Stelle wählen', 'Select working group', 'index.php?go=Stelle Wählen', 0, 1, NULL, 1, 'Zu anderen Stellen und den Einstellungen', 'optionen');

# Übersicht
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`, `title`, `button_class`) VALUES ('Übersicht', 'Overview', 'index.php?go=Full_Extent', 0, 1, NULL, 2, 'Maximale Kartenausdehnung', 'gesamtansicht');

# Karte
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`, `title`, `button_class`) VALUES ('Karte', 'Map', 'index.php', 0, 1, NULL, 3, 'Karte anzeigen', 'karte');

##### Suchfunktionen
# Obermenü für die Suchfunktionen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`) VALUES ('Suchen', 'Search', 'index.php?go=changemenue', 0, 1, NULL, 4);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,10);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);


# Untermenüpunkte für die Suche
# Wenn das Obermenü schon existiert hier die ID-Angeben
# SET @last_level1menue_id=<Ihre ID>;

# Layersuche 
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Layer-Suche', 'Layer search', 'index.php?go=Layer-Suche', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,11);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Adresssuche 
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Adressen', 'index.php?go=Adresse_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,12);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Flurstückssuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Flurstücke', 'index.php?go=Flurstueck_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,13);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Namenssuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Namen', 'index.php?go=Namen_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,14);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Grundbuchblattsuche
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Grundbuchblatt', 'index.php?go=Grundbuchblatt_Auswaehlen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,16);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Programmverwaltung
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`, `title`) VALUES ('Programmverwaltung', 'Administration', 'index.php?go=changemenue', 0, 1, NULL, 10, 'Programmverwaltung');
SET @last_level1menue_id = LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_level1menue_id, 50);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id,status) VALUES (@user_id, @stelle_id,@last_level1menue_id, 0);

# Update und Config
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`, `title`) VALUES ('Update und Config', 'Update and settings', 'index.php?go=Administratorfunktionen', @last_level1menue_id, 2, NULL, 12, 'Update und Config');
SET @last_menue_id = LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_menue_id, 51);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id,status) VALUES (@user_id, @stelle_id, @last_menue_id, 0);

# Cronjobs
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`, `title`) VALUES ('Cronjobs', 'Cronjobs', 'index.php?go=cronjobs_anzeigen', @last_level1menue_id, 2, NULL, 14, 'Cronjobs einstellen');
SET @last_menue_id = LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_menue_id, 53);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id,status) VALUES (@user_id, @stelle_id, @last_menue_id, 0);

#### Stellenverwaltung
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`, `title`) VALUES ('Stellenverwaltung', 'Manage working groups', 'index.php?go=changemenue', 0, 1, NULL, 10, 'Stellenverwaltung');
SET @last_level1menue_id = LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_level1menue_id, 60);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id,status) VALUES (@user_id, @stelle_id,@last_level1menue_id, 0);

# Stellen anlegen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Stellen anlegen', 'Create Working group', 'index.php?go=Stelleneditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,61);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Stellen anzeigen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Stellen anzeigen', 'List working group', 'index.php?go=Stellen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,62);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layer-Rechteverwaltung
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Layer-Rechte', 'Layer permissions', 'index.php?go=Layerattribut-Rechteverwaltung', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,63);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layer-Rechteverwaltung
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Filterverwaltung', 'Filter management', 'index.php?go=Filterverwaltung', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,64);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Funktionenverwaltung
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`) VALUES ('Funktionenverwaltung', 'Function management', 'index.php?go=changemenue', 0, 1, NULL, 10);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,66);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Funktion anlegen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Funktion anlegen', 'Create function', 'index.php?go=Funktionen_Formular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,67);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Funktionen anzeigen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Funktionen anzeigen', 'List functions', 'index.php?go=Funktionen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,68);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Menueverwaltung
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`, `title`) VALUES ('Menüverwaltung', 'Menue management', 'index.php?go=changemenue', 0, 1, NULL, 5, 'Alle Menüpunkte anzeigen');
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,66);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Menue anlegen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Menü anlegen', 'Create menue', 'index.php?go=Menueeditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,67);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Menues anzeigen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Menüs anzeigen', 'List menues', 'index.php?go=Menues_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,68);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Nutzerverwaltung
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`) VALUES ('Nutzerverwaltung', 'User management', 'index.php?go=changemenue', 0, 1, NULL, 20);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,70);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Nutzer anlegen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Nutzer anlegen', 'Create user', 'index.php?go=Benutzerdaten_Formular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,71);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Nutzer anzeigen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Nutzer anzeigen', 'List user', 'index.php?go=Benutzerdaten_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,72);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Nutzer einladen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Nutzer einladen', 'Invide user', 'index.php?go=Einladung_Editor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,73);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Nutzer einladen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Einladungen anzeigen', 'List invitations', 'index.php?go=Einladungen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,74);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Layerverwaltung
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`) VALUES ('Layerverwaltung', 'Layer managment', 'index.php?go=changemenue', 0, 1, NULL, 30);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,75);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Layergruppen anzeigen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Gruppen anzeigen', 'List layer groups', 'index.php?go=Layergruppen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,75);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layer anzeigen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Layer anzeigen', 'List layer', 'index.php?go=Layer_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,76);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layer erstellen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Layer erstellen', 'Create layer', 'index.php?go=Layereditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,77);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Attribut-Editor
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Attribut-Editor', 'Attribute editor', 'index.php?go=Attributeditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,78);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Style-Label-Editor
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Style-u.Labeleditor', 'Style-, Label editor', 'index.php?go=Style_Label_Editor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,79);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# neuer Datensatz
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('neuer Datensatz', 'Create dataset', 'index.php?go=neuer_Layer_Datensatz', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,80);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layergruppen
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Layergruppen', 'List layer groups', 'index.php?go=Layergruppen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 82);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id, 0);

# Layerübersicht
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Themenübersicht', 'Layer overview', 'index.php?go=Layer_Uebersicht', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 84);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id, 0);

#### Import/Export
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target, `order`) VALUES ('Import/Export', 'Import/Export', 'index.php?go=changemenue', 0, 1, NULL, 40);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id, 86);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# WMS-Export
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('WMS-Export', 'WMS export', 'index.php?go=WMS_Export', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,88);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# WMS-Import
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('WMS-Import', 'WMS import', 'index.php?go=WMS_Import', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 90);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Daten-Export
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Daten-Export', 'Data export', 'index.php?go=Daten_Export', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 92);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Daten-Import
INSERT INTO u_menues (name, name_english, links, obermenue, menueebene, target) VALUES ('Daten-Import', 'Data import', 'index.php?go=Daten_Import', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 94);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Druckausgabe
INSERT INTO u_menues (`name`, `name_english`, `links`, `obermenue`, `menueebene`, `target`, `title`, `button_class`) VALUES ('Druckausgabe', 'Print', 'index.php?go=Schnelle_Druckausgabe', @last_level1menue_id, 2, '_blank', 'Karte sofort ausdrucken', '');
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 96);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Druckmanager
INSERT INTO u_menues ( `name` , `name_english`, `links` , `obermenue` , `menueebene` , `target` )
VALUES ('Druckmanager', 'Print manager', 'index.php?go=changemenue', '0', '1', NULL);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_level1menue_id, '88');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

#### Druckrahmeneditor
INSERT INTO u_menues ( `name` , `name_english`, `links` , `obermenue` , `menueebene` , `target` )
VALUES ( 'Kartendrucklayouteditor', 'Map print layout editor', 'index.php?go=Druckrahmen', @last_level1menue_id, '2', NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_menue_id, '89');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

INSERT INTO u_menues ( `name` , `name_english`, `links` , `obermenue` , `menueebene` , `target` )
VALUES ( 'Datendrucklayouteditor', 'Data print layout editor', 'index.php?go=sachdaten_druck_editor', @last_level1menue_id, '2', NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_menue_id, '90');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Drucken
INSERT INTO u_menues ( `name` , `name_english`, `links` , `obermenue` , `menueebene` , `target`, `title`, `button_class`)
VALUES ( 'Drucken', 'Print', 'index.php?go=Druckausschnittswahl', @last_level1menue_id, '2', NULL, 'Zur Druckausschnittswahl', '');
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_menue_id, '91');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

## Metadaten
INSERT INTO u_menues (name, `name_english`, links, obermenue, menueebene, target, `order`) VALUES ('Metadaten', 'Metadata', 'index.php?go=changemenue', 0, 1, NULL, 100);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_level1menue_id, 100);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id, status) VALUES (@user_id, @stelle_id, @last_level1menue_id, 0);
# Recherche
INSERT INTO u_menues (name, `name_english`, links, obermenue, menueebene, target, `order`) VALUES ('Recherche', 'Metadata search', 'index.php?go=Metadaten_Auswaehlen', @last_level1menue_id, 2, NULL, 110);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_menue_id, 110);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id, status) VALUES (@user_id, @stelle_id, @last_menue_id, 0);
# Eingabe
INSERT INTO u_menues (name, `name_english`, links, obermenue, menueebene, target, `order`) VALUES ('Neuer Metadatensatz', 'Create metadataset', 'index.php?go=Metadateneingabe', @last_level1menue_id, 2, NULL, 120);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_menue_id, 120);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id, status) VALUES (@user_id, @stelle_id, @last_menue_id, 0);

# Layer-Gruppen anlegen
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (1, 'Hintergrundkarten', 'Backgroundmaps', 1000);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (2, 'Verwaltungsgrenzen', 'Administrative Boundaries', 900);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (3, 'Kataster', 'Cadastre', 800);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (4, 'Umwelt', 'Environment', 700);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (5, 'Bauen', 'Building', 600);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (6, 'Raumordnung', 'Planned Landuse', 500);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (7, 'Soziales', 'Social Themes', 400);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (8, 'Verkehr', 'Traffic', 300);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_english`, `order`) VALUES (9, 'Administration', 'Administration', 100);

# einen ersten WMS Layer anlegen
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `processing`, `kurzbeschreibung`, `datasource`, `dataowner_name`, `dataowner_email`, `dataowner_tel`, `metalink`) VALUES('ORKa-MV (OSM)', '', '3', '1', NULL, NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, '', 'http://www.orka-mv.de/geodienste/orkamv/wms?VERSION=1.1.1&LAYERS=orkamv&STYLES=&FORMAT=image/jpeg', '', 7, '', 3, 'pixels', '25833', '', '0', NULL, 0, NULL, NULL, '', 'EPSG:25833', 'stadtplan', '1.1.1', 'image/png', 60, '', '', '', 'radio', '0', '1.3.0', 'WMS der offenen Regionalkarte Mecklenburg-Vorpommern (ORKa.MV)', NULL, 'Hanse- und Universitätsstadt Rostock', 'presse@rostock.de', '+49 381 381-1417', 'https://www.orka-mv.de');
INSERT IGNORE INTO used_layer ( `Stelle_ID` , `Layer_ID` , `queryable` , `drawingorder` , `minscale` , `maxscale` , `offsite` , `transparency`, `Filter` , `template` , `header` , `footer` , `symbolscale`, `privileg` )VALUES ('1', '1', '0', '', '', '', '' , NULL, NULL,'' , NULL , NULL , NULL, '0');
INSERT IGNORE INTO u_groups2rolle SELECT DISTINCT 1, 1, u_groups.id, 1 FROM (SELECT @id AS id, @id := IF(@id IS NOT NULL, (SELECT obergruppe FROM u_groups WHERE id = @id), NULL) AS obergruppe FROM u_groups, (SELECT @id := (SELECT Gruppe FROM layer where layer.Layer_ID = 1)) AS vars WHERE @id IS NOT NULL	 ) AS dat	JOIN u_groups ON dat.id = u_groups.id;
INSERT IGNORE INTO u_rolle2used_layer (
	`user_id`,
	`stelle_id`,
	`layer_id`,
	`aktivStatus`,
	`queryStatus`,
	`gle_view`,
	`showclasses`,
	`logconsume`,
	`transparency`
) 
SELECT
	1, used_layer.Stelle_ID, used_layer.Layer_ID, "1", "0", NULL, "1", NULL, NULL 
FROM
	`used_layer` 
WHERE
	used_layer.Stelle_ID = 1;

# Einen ersten Druckrahmen erzeugen
INSERT INTO `druckrahmen` (`Name`, `headsrc`, `headposx`, `headposy`, `headwidth`, `headheight`, `mapposx`, `mapposy`, `mapwidth`, `mapheight`, `refmapsrc`, `refmapfile`, `refmapposx`, `refmapposy`, `refmapwidth`, `refmapheight`, `refposx`, `refposy`, `refwidth`, `refheight`, `refzoom`, `dateposx`, `dateposy`, `datesize`, `scaleposx`, `scaleposy`, `scalesize`, `oscaleposx`, `oscaleposy`, `oscalesize`, `gemarkungposx`, `gemarkungposy`, `gemarkungsize`, `flurposx`, `flurposy`, `flursize`, `legendposx`, `legendposy`, `legendsize`, `arrowposx`, `arrowposy`, `arrowlength`, `userposx`, `userposy`, `usersize`, `watermarkposx`, `watermarkposy`, `watermark`, `watermarksize`, `watermarkangle`, `watermarktransparency`, `format`, `preis`, `font_date`, `font_scale`, `font_gemarkung`, `font_flur`, `font_oscale`, `font_legend`, `font_watermark`, `font_user`) VALUES
('A4-hoch-leer', 'A4-hoch.jpg', 0, 0, 595, 842, 44, 50, 511, 714, '', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 503, 784, 11, 422, 74, 0, 422, 87, 0, 238, 54, 0, 238, 64, 0, 58, 50, 0, 540, 770, 0, 140, 800, 0, 155, 155, '', 120, 45, 77, 'A4hoch', 0, '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', NULL, '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm');
SET @last_druckrahmen_id=LAST_INSERT_ID();
INSERT INTO `druckrahmen2stelle` (`stelle_id`, `druckrahmen_id`) VALUES (@stelle_id, @last_druckrahmen_id);

# Insert default cron_jobs
INSERT INTO `cron_jobs` (`id`, `bezeichnung`, `beschreibung`, `time`, `query`, `function`, `url`, `user_id`, `stelle_id`, `aktiv`, `dbname`, `user`) VALUES
(1, 'Leere tmp Verzeichnis', 'Löscht jeden Tag Dateien die älter als 1 Tag sind aus Verzeichnis /var/www/tmp', '1 1 * * *', '', 'find /var/www/tmp -mtime +1 ! -path /var/www/tmp -exec rm -rf {} +', NULL, 0, 0, 1, '', 'gisadmin'),
(2, 'Update Let\'s Encrypt Certificate', 'Führt regelmäßig certbot-auto renew zur Aktualisierung des https Zertifikates aus.', '0 0,12 * * *', '', 'python -c \'import random; import time; time.sleep(random.random() * 3600)\' && rm -rf /etc/apt/sources.list.d/* || true && /usr/local/certbot-auto renew -q', NULL, 0, 0, 1, '', 'root'),
(3, 'wms_checker', 'Ruft das Script tools/wms_checker.php alle 10 min auf.', '10 * * * *', '', 'cd /var/www/apps/kvwmap/tools/; php /var/www/apps/kvwmap/tools/wms_checker.php > /var/www/logs/wms_checker.html 2> /var/www/logs/wms_checker.log', NULL, 0, 0, 1, '', 'gisadmin');

INSERT INTO `colors` (`id`, `name`, `red`, `green`, `blue`) VALUES
(1, NULL, 166, 206, 227),
(2, NULL, 31, 120, 180),
(3, NULL, 178, 223, 138),
(4, NULL, 51, 160, 44),
(5, NULL, 251, 154, 153),
(6, NULL, 227, 26, 28),
(7, NULL, 253, 191, 111),
(8, NULL, 255, 127, 0),
(9, NULL, 202, 178, 214),
(10, NULL, 106, 61, 154),
(11, NULL, 0, 0, 0),
(12, NULL, 122, 12, 45);

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`, `editable`) VALUES
('HEADER', 'SNIPPETS', 'header.php', '', 'string', 'Layout', '', 1, 3),
('FOOTER', 'SNIPPETS', 'footer.php', '', 'string', 'Layout', '', 1, 3),
('LOGIN', 'SNIPPETS', 'login.php', 'login.php\r\n', 'string', 'Layout', '', 1, 3),
('LAYER_ERROR_PAGE', 'SNIPPETS', 'layer_error_page.php', 'Seite zur Fehlerbehandlung, die durch fehlerhafte Layer verursacht werden; unterhalb von /snippets\r\n', 'string', 'Layout', '', 1, 3),
('AGREEMENT_MESSAGE', 'CUSTOM_PATH', '', 'Seite mit der Datenschutzerklärung, die einmalig beim Login angezeigt wird\r\nz.B. custom/ds_gvo.htm', 'string', 'Layout', '', 1, 2),
('CUSTOM_STYLE', 'CUSTOM_PATH', 'layouts/custom.css', 'hier kann eine eigene css-Datei angegeben werden\r\n', 'string', 'Layout', '', 1, 2),
('ZOOM2COORD_STYLE_ID', '', '', 'hier können eigene Styles für den Koordinatenzoom und Punktzoom definiert werden\r\n', 'string', 'Layout', '', 1, 2),
('ZOOM2POINT_STYLE_ID', '', '', '', 'string', 'Layout', '', 1, 2),
('GLEVIEW', '', '2', 'Schalter für eine zeilen- oder spaltenweise Darstellung der Attribute im generischen Layereditor  # Version 1.6.5\r\n', 'numeric', 'Layout', '', 1, 2),
('sizes', '', '{\r\n    \"layouts/gui.php\": {\r\n        \"margin\": {\r\n            \"width\": 0,\r\n            \"height\": 2\r\n        },\r\n        \"header\": {\r\n            \"height\": 32\r\n        },\r\n        \"scale_bar\": {\r\n            \"height\": 30\r\n        },\r\n        \"lagebezeichnung_bar\": {\r\n            \"height\": 30\r\n        },\r\n        \"map_functions_bar\": {\r\n            \"height\": 43\r\n        },\r\n        \"footer\": {\r\n            \"height\": 18\r\n        },\r\n        \"menue\": {\r\n            \"width\": 218,\r\n            \"hide_width\": 22\r\n        },\r\n        \"legend\": {\r\n            \"width\": 270,\r\n            \"hide_width\": 27\r\n        }\r\n    }\r\n}', 'Höhen und Breiten von Browser, Rand, Header, Footer, Menü und Legende																# Version 2.7\r\n', 'array', 'Layout', '', 1, 2),
('LEGEND_GRAPHIC_FILE', '', '', 'zusätzliche Legende; muss unterhalb von snippets liegen\r\n', 'string', 'Layout', '', 1, 2),
('legendicon_size', '', '{\r\n    \"width\": [\r\n        18,\r\n        18,\r\n        18,\r\n        18\r\n    ],\r\n    \"height\": [\r\n        18,\r\n        12,\r\n        12,\r\n        18\r\n    ]\r\n}', 'Höhe und Breite der generierten Legendenbilder für verschiedene Layertypen\r\n-> Punktlayer\r\n-> Linienlayer\r\n-> Flächenlayer\r\n-> Rasterlayer\r\n', 'array', 'Layout', '', 1, 2),
('PREVIEW_IMAGE_WIDTH', '', '250', 'Vorschaubildgröße\r\n', 'numeric', 'Layout', '', 1, 2),
('TITLE', '', 'kvwmap', 'Titel, welcher im Browser angezeigt wird\r\n', 'string', 'Layout', '', 1, 2),
('MENU_WAPPEN', '', 'oben', 'Position des Wappens (oben/unten/kein)\r\n', 'string', 'Layout', '', 1, 2),
('MENU_REFMAP', '', 'unten', 'Position der Referenzkarte (oben/unten)                   # Version 1.6.4\r\n', 'string', 'Layout', '', 1, 2),
('BG_TR', '', 'lightsteelblue', 'Hintergrundfarbe Zeile bei Listen\r\n', 'string', 'Layout', '', 1, 2),
('BG_MENUETOP', '', '#DAE4EC', 'Hintergrundfarbe Top-Menüzeilen\r\n', 'string', 'Layout', '', 1, 2),
('BG_MENUESUB', '', '#b4caed', 'Hintergrundfarbe Sub-Menüzeilen\r\n', 'string', 'Layout', '', 1, 2),
('BG_DEFAULT', '', '#95b4e6', 'Hintergrundfarbe (Kopf-/Fusszeile)\r\n', 'string', 'Layout', '', 1, 2),
('BG_FORM', '', 'lightsteelblue', 'Hintergrundfarbe (Eingabeformulare)\r\n', 'string', 'Layout', '', 1, 2),
('BG_FORMFAIL', '', 'lightpink', 'Hintergrundfarbe (Formularfehler)\r\n', 'string', 'Layout', '', 1, 2),
('BG_GLEHEADER', '', 'lightsteelblue', 'Hintergrundfarbe GLE Datensatzheader\r\n', 'string', 'Layout', '', 1, 2),
('TXT_GLEHEADER', '', '#000000', 'Schriftfarbe GLE Datensatzheader\r\n', 'string', 'Layout', '', 1, 2),
('BG_GLEATTRIBUTE', '', '#b4caed', 'Hintergrundfarbe GLE Attributnamen\r\n', 'string', 'Layout', '', 1, 2),
('POSTGRESVERSION', '', '960', 'PostgreSQL Server Version                         # Version 1.6.4\r\n', 'string', 'Administration', '', 1, 2),
('MYSQLVERSION', '', '500', 'MySQLSQL Server Version                         # Version 1.6.4\r\n', 'string', 'Administration', '', 1, 2),
('MAPSERVERVERSION', '', '761', 'Mapserver Version                             # Version 1.6.8\r\n', 'string', 'Administration', '', 1, 2),
('PHPVERSION', '', '700', 'PHP-Version\r\n', 'string', 'Administration', '', 1, 2),
('MYSQL_CHARSET', '', 'UTF8', 'Character Set der MySQL-Datenbank\r\n', 'string', 'Administration', '', 1, 2),
('POSTGRES_CHARSET', '', 'UTF8', '', 'string', 'Administration', '', 1, 2),
('PUBLISHERNAME', '', 'Kartenserver', 'Bezeichung des Datenproviders\r\n', 'string', 'Administration', '', 1, 2),
('CHECK_CLIENT_IP', '', 'true', 'Erweiterung der Authentifizierung um die IP Adresse des Nutzers\r\nTestet ob die IP des anfragenden Clientrechners dem Nutzer zugeordnet ist\r\n', 'boolean', 'Administration', '', 1, 2),
('PASSWORD_MAXLENGTH', '', '25', 'maximale Länge der Passwörter\r\n', 'numeric', 'Administration', '', 1, 2),
('PASSWORD_MINLENGTH', '', '6', 'minimale Länge der Passwörter\r\n', 'numeric', 'Administration', '', 1, 2),
('PASSWORD_CHECK', '', '01010', 'Prüfung neues Passwort\r\nAuskommentiert, wenn das Passwort vom Admin auf \"unendlichen\" Zeitraum vergeben wird\r\nerste Stelle  0 = Prüft die Stärke des Passworts (3 von 4 Kriterien müssen erfüllt sein) - die weiteren Stellen werden ignoriert\r\nerste Stelle  1 = Prüft statt Stärke die nachfolgenden Kriterien:\r\nzweite Stelle 1 = Es müssen Kleinbuchstaben enthalten sein\r\ndritte Stelle 1 = Es müssen Großbuchstaben enthalten sein\r\nvierte Stelle 1 = Es müssen Zahlen enthalten sein\r\nfünfte Stelle 1 = Es müssen Sonderzeichen enthalten sein\r\n', 'string', 'Administration', '', 1, 2),
('GIT_USER', '', 'gisadmin', 'Wenn das kvwmap-Verzeichnis ein git-Repository ist, kann diese Konstante auf den User gesetzt werden, der das Repository angelegt hat.\r\nDamit der Apache-User dann die git-Befehle als dieser User ausführen kann, muss man als root über den Befehl \"visudo\" die /etc/sudoers editieren.\r\nDort muss dann eine Zeile in dieser Form hinzugefügt werden: \r\nwww-data        ALL=(fgs) NOPASSWD: /usr/bin/git\r\nDann kann man die Aktualität des Quellcodes in der Administrationsoberfläche überprüfen und ihn aktualisieren.\r\n', 'string', 'Administration', '', 1, 2),
('MAXQUERYROWS', '', '10', 'maximale Anzahl der in einer Sachdatenabfrage zurückgelieferten Zeilen.\r\n', 'numeric', 'Administration', '', 1, 2),
('ALWAYS_DRAW', '', 'true', 'definiert, ob der Polygoneditor nach einem Neuladen\r\nder Seite immer in den Modus \"Polygon zeichnen\" wechselt\r\n', 'boolean', 'Administration', '', 1, 2),
('EARTH_RADIUS', '', '6384000', 'Parameter für die Strecken- und Flächenreduktion\r\n', 'numeric', 'Administration', '', 1, 2),
('admin_stellen', '', '[\r\n    1\r\n]', 'Adminstellen\r\n', 'array', 'Administration', '', 1, 2),
('gast_stellen', '', '[\r\n]', 'Gast-Stellen\r\n', 'array', 'Administration', '', 1, 2),
('selectable_limits', '', '[\r\n    10,\r\n    25,\r\n    50,\r\n    100,\r\n    200\r\n]', 'auswählbare Treffermengen\r\n', 'array', 'Administration', '', 1, 2),
('selectable_scales', '', '[\r\n    500,\r\n    1000,\r\n    2500,\r\n    5000,\r\n    7500,\r\n    10000,\r\n    25000,\r\n    50000,\r\n    100000,\r\n    250000,\r\n    500000,\r\n    1000000\r\n]', 'auswählbare Maßstäbe\r\n', 'array', 'Administration', '', 1, 2),
('supportedSRIDs', '', '[\r\n    4326,\r\n    2397,\r\n    2398,\r\n    2399,\r\n    31466,\r\n    31467,\r\n    31468,\r\n    31469,\r\n    32648,\r\n    25832,\r\n    25833,\r\n    325833,\r\n    35833,\r\n    32633,\r\n    325833,\r\n    15833,\r\n    900913,\r\n    28992,\r\n    5650\r\n]', 'Unterstützte SRIDs, nur diese stehen zur Auswahl bei der Stellenwahl\r\n', 'array', 'Administration', '', 1, 2),
('supportedLanguages', '', '[\r\n    \"german\"\r\n]', 'Unterstützte Sprachen, nur diese stehen zur Auswahl bei der Stellenwahl (\'german\', \'low-german\', \'english\', \'polish\', \'vietnamese\')\r\n', 'array', 'Administration', '', 1, 2),
('supportedExportFormats', '', '[\r\n    \"Shape\",\r\n    \"GML\",\r\n    \"KML\",\r\n    \"GeoJSON\",\r\n    \"CSV\"\r\n]', 'Unterstützte Exportformate\r\n', 'array', 'Administration', '', 1, 2),
('MAPFACTOR', '', '3', 'Faktor für die Einstellung der Druckqualität (MAPFACTOR * 72 dpi)     # Version 1.6.0\r\n', 'numeric', 'Administration', '', 1, 2),
('DEFAULT_DRUCKRAHMEN_ID', '', '1', 'Standarddrucklayout für den schnellen Kartendruck						# Version 1.7.4\r\n', 'numeric', 'Administration', '', 1, 2),
('MAXUPLOADSIZE', '', '200', 'maximale Datenmenge in MB, die beim Datenimport hochgeladen werden darf\r\n', 'numeric', 'Administration', '', 1, 2),
('MINSCALE', '', '0.01', 'Minmale Maßstabszahl\r\n', 'numeric', 'Administration', '', 1, 2),
('COORD_ZOOM_SCALE', '', '50000', 'Maßstab ab dem bei einem Koordinatensprung auch gezoomt wird\r\n', 'numeric', 'Administration', '', 1, 2),
('ZOOMBUFFER', '', '100', 'Puffer in der Einheit (ZOOMUNIT) der beim Zoom auf ein Objekt hinzugegeben wird\r\n', 'numeric', 'Administration', '', 1, 2),
('ZOOMUNIT', '', 'meter', 'Einheit des Puffer der beim Zoom auf ein Objekt hinzugegeben wird\r\n\'meter\' oder \'scale\'\r\n', 'string', 'Administration', '', 1, 2),
('SHOW_MAP_IMAGE', '', 'true', 'Definiert, ob das aktuelle Kartenbild separat angezeigt werden darf oder nicht\r\n', 'boolean', 'Administration', '', 1, 2),
('kvwmap_plugins', '', '[\r\n]', '', 'array', 'Administration', '', 1, 2),
('INFO1', '', 'Prüfen Sie ob Ihr Datenbankmodell aktuell ist.', 'Festlegung von Fehlermeldungen und Hinweisen\r\n', 'string', 'Administration', '', 1, 2),
('APPLVERSION', '', 'kvwmap/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('INSTALLPATH', '', '/var/www/', 'Installationspfad\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('WWWROOT', 'INSTALLPATH', 'apps/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('IMAGEPATH', 'INSTALLPATH', 'tmp/', 'Verzeichnis, in dem die temporären Bilder usw. abgelegt werden\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('URL', '', '', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('NBH_PATH', 'WWWROOT.APPLVERSION', 'tools/UTM33_NBH.lst', 'Datei mit den Nummerierungsbezirkshöhen\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('MAPSERV_CGI_BIN', 'URL', 'cgi-bin/mapserv', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('LOGPATH', 'INSTALLPATH', 'logs/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('SHAPEPATH', 'INSTALLPATH', 'data/', 'Shapepath [Pfad zum Shapefileverzeichnis]\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('CUSTOM_SHAPE_SCHEMA', '', 'custom_shapes', 'ein extra Schema in der PG-DB, in der die Tabellen der Nutzer Shapes angelegt werden\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('REFERENCEMAPPATH', 'SHAPEPATH', 'referencemaps/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('DRUCKRAHMEN_PATH', 'SHAPEPATH', 'druckrahmen/', 'Pfad zum Speichern der Kartendruck-Layouts\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('THIRDPARTY_PATH', '', '../3rdparty/', '3rdparty Pfad\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('FONTAWESOME_PATH', 'THIRDPARTY_PATH', 'font-awesome-4.6.3/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('JQUERY_PATH', 'THIRDPARTY_PATH', 'jQuery-3.6.0/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('BOOTSTRAP_PATH', 'THIRDPARTY_PATH', 'bootstrap-4.6.1/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('BOOTSTRAPTABLE_PATH', 'THIRDPARTY_PATH', 'bootstrap-table-1.20.2/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('PROJ4JS_PATH', 'THIRDPARTY_PATH', 'proj4js-2.4.3/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('POSTGRESBINPATH', '', '/usr/lib/postgresql/9.6/bin/', 'Bin-Pfad der Postgres-tools (shp2pgsql, pgsql2shp)\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('OGR_BINPATH', '', '/usr/bin/', 'Bin-Pfad der OGR-tools (ogr2ogr, ogrinfo)\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('ZIP_PATH', '', 'zip', 'Pfad zum Zip-Programm (unter Linux: \'zip -j\', unter Windows z.B. \'c:/programme/Zip/bin/zip.exe\')\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('CUSTOM_IMAGE_PATH', 'SHAPEPATH', 'Bilder/', 'Pfad für selbst gemachte Bilder\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('CACHEPATH', 'INSTALLPATH', 'cache/', 'Cachespeicherort\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('CACHETIME', '', '168', 'Cachezeit Nach welcher Zeit in Stunden sollen gecachte Dateien aktualisiert werden\r\nwird derzeit noch nicht berücksichtigt\r\n', 'numeric', 'Pfadeinstellungen', '', 1, 2),
('TEMPPATH_REL', '', '../tmp/', 'relative Pfadangabe zum Webverzeichnis mit temprären Dateien\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('IMAGEURL', '', '/tmp/', 'Imageurl\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('SYMBOLSET', 'WWWROOT.APPLVERSION', 'symbols/symbole.sym', 'Symbolset\r\n', 'string', 'Pfadeinstellungen', '', 1, 3),
('FONTSET', 'WWWROOT.APPLVERSION', 'fonts/fonts.txt', 'Fontset\r\n', 'string', 'Pfadeinstellungen', '', 1, 3),
('GRAPHICSPATH', '', 'graphics/', 'Graphics\r\n', 'string', 'Pfadeinstellungen', '', 1, 0),
('WAPPENPATH', 'CUSTOM_PATH', 'wappen/', 'Wappen\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('LAYOUTPATH', 'WWWROOT.APPLVERSION', 'layouts/', 'Layouts\r\n', 'string', 'Pfadeinstellungen', '', 1, 0),
('SNIPPETS', 'LAYOUTPATH', 'snippets/', '', 'string', 'Pfadeinstellungen', '', 1, 0),
('CLASSPATH', 'WWWROOT.APPLVERSION', 'class/', '', 'string', 'Pfadeinstellungen', '', 1, 0),
('PLUGINS', 'WWWROOT.APPLVERSION', 'plugins/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('SYNC_PATH', 'SHAPEPATH', 'synchro/', 'Synchronisationsverzeichnis                         # Version 1.7.0\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('IMAGEMAGICKPATH', '', '/usr/bin/', 'Pfad zum Imagemagick convert\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('UPLOADPATH', 'SHAPEPATH', 'upload/', 'Pfad zum Ordner für Datei-Uploads\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('DEFAULTMAPFILE', 'SHAPEPATH', 'mapfiles/defaultmapfile.map', 'Mapfile, mit dem das Mapobjekt gebildet wird\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
('REFMAPFILE', 'SHAPEPATH', 'mapfiles/refmapfile.map', '', 'string', 'Pfadeinstellungen', '', 1, 2),
('MAILMETHOD', '', 'sendEmail async', 'Methode zum Versenden von E-Mails. Mögliche Optionen:\r\nsendmail: E-Mails werden direkt mit sendmail versendet. (default)\r\nsendEmail async: E-Mails werden erst in einem temporären Verzeichnis MAILQUEUEPATH\r\nabgelegt und können später durch das Script tools/sendEmailAsync.sh\r\nversendet werden. Dort muss auch MAILQUEUEPATH eingestellt werden.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
('MAILSMTPSERVER', '', 'smtp.p4.net', 'SMTP-Server, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
('MAILSMTPPORT', '', '25', 'SMTP-Port, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.\r\n', 'numeric', 'E-Mail Einstellungen', '', 1, 2),
('MAILQUEUEPATH', '', '/var/www/logs/kvwmap/mail_queue/', 'Verzeichnis für die JSON-Dateien mit denzu versendenen E-Mails.\r\nMuss nur angegeben werden, wenn Methode sendEmail async verwendet wird.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
('MAILARCHIVPATH', '', '/var/www/logs/kvwmap/mail_archiv/', '', 'string', 'E-Mail Einstellungen', '', 1, 2),
('LAYER_IDS_DOP', '', '', '', 'string', 'Layer-IDs', '', 1, 2),
('LAYER_ID_SCHNELLSPRUNG', '', '', '', 'numeric', 'Layer-IDs', '', 1, 2),
('quicksearch_layer_ids', '', '[]', '', 'array', 'Layer-IDs', '', 1, 2),
('DEBUGFILE', '', '_debug.htm', 'Name der Datei, in der die Meldungen beim Debugen geschrieben werden\r\n', 'string', 'Logging', '', 1, 2),
('DEBUG_LEVEL', '', '1', 'Level der Fehlermeldungen beim debuggen\r\n3 nur Ausgaben die für Admin bestimmt sind\r\n2 nur Datenbankanfragen\r\n1 nur wichtige Fehlermeldungen\r\n5 keine Ausgaben\r\n', 'numeric', 'Logging', '', 1, 2),
('LOGFILE_MYSQL', 'LOGPATH', 'log_mysql.sql', 'mySQL-Log-Datei zur Speicherung der SQL-Statements              # Version 1.6.0\r\n', 'string', 'Logging', '', 1, 2),
('LOGFILE_POSTGRES', 'LOGPATH', 'log_postgres.sql', 'postgreSQL-Log-Datei zur Speicherung der SQL-Statements         # Version 1.6.0\r\n', 'string', 'Logging', '', 1, 2),
('LOGFILE_LOGIN', 'LOGPATH', 'login_fail.log', 'Log-Datei zur Speicherung der Login Vorgänge\r\n', 'string', 'Logging', '', 1, 2),
('LOG_LEVEL', '', '2', 'Log-Level zur Speicherung der SQL-Statements                    # Version 1.6.0\r\nLoglevel\r\n0 niemals loggen\r\n1 immer loggen\r\n2 nur loggen wenn loglevel in execSQL 1 ist.\r\n', 'numeric', 'Logging', '', 1, 2),
('SAVEMAPFILE', 'LOGPATH', 'save_mapfile.map', 'Wenn SAVEMAPFILE leer ist, wird sie nicht gespeichert.\r\nAchtung, wenn die cgi-bin/mapserv ohne Authentifizierung und der Pfad zu save_mapfile.map bekannt ist, kann jeder die Karten des letzten Aufrufs in kvwmap über mapserv?map=<pfad zu save_map.map abfragen. Und wenn wfs zugelassen ist auch die Sachdaten dazu runterladen. Diese Konstante sollte nur zu debug-Zwecken eingeschaltet bleiben.\r\n', 'string', 'Logging', '', 1, 2),
('DEFAULTDBWRITE', '', '1', 'Ermöglicht die Ausführung der SQL-Statements in der Datenbank zu unterdrücken.\r\nIn dem Fall werden die Statements nur in die Log-Datei geschrieben.\r\nDie Definition von DBWRITE ist umgezogen nach start.php, damit das Unterdrücken\r\ndes Schreiben in die Datenbank auch mit Formularwerten eingestellt werden kann.\r\ndas übernimmt in dem Falle die Formularvariable disableDbWrite.\r\nHier kann jedoch noch der Defaultwert gesetzt werden\r\n', 'numeric', 'Logging', '', 1, 2),
('LOG_CONSUME_ACTIVITY', '', '1', 'Einstellungen zur Speicherung der Zugriffe\r\n', 'numeric', 'Logging', '', 1, 2),
('POSTGRES_HOST', '', 'pgsql', '', 'string', 'Datenbanken', '', 1, 2),
('POSTGRES_USER', '', 'gisadmin', '', 'string', 'Datenbanken', '', 1, 2),
('POSTGRES_PASSWORD', '', '', '', 'password', 'Datenbanken', '', 1, 2),
('POSTGRES_DBNAME', '', 'kvwmapsp', '', 'string', 'Datenbanken', '', 1, 2),
('MAPFILENAME', '', 'kvwmap', '', 'string', 'OWS-METADATEN', '', 1, 2),
('WMS_MAPFILE_REL_PATH', '', 'wms/', 'Voreinstellungen für Metadaten zu Web Map Services (WMS-Server)\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('WMS_MAPFILE_PATH', 'INSTALLPATH.WMS_MAPFILE_REL_PATH', 'mapfiles/', '', 'string', 'OWS-METADATEN', '', 1, 3),
('SUPORTED_WMS_VERSION', '', '1.3.0', '', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_SCHEMAS_LOCATION', '', 'http://schemas.opengeospatial.net', 'Metadaten zur Ausgabe im Capabilities Dokument gelten für WMS, WFS und WCS\r\nsets base URL for OGC Schemas so the root element in the\r\nCapabilities document points to the correct schema location\r\nto produce valid XML\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_TITLE', '', 'WBV Geo-Web-Dienste', 'An Stelle von WMS_TITLE\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_ABSTRACT', '', 'Kartenserver des Organisation XY', 'An Stelle von WMS_Abstract\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_KEYWORDLIST', '', 'GIS,Kataster,Geoinformation', 'WMT_MS_Capabilities/Service/KeywordList/Keyword[]\r\nWFS_Capabilities/Service/Keywords\r\nWCS_Capabilities/Service/keywords/keyword[]\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_SERVICE_ONLINERESOURCE', 'URL.APPLVERSION', 'index.php?go=OWS', 'WMT_MS_Capabilities/Service/OnlineResource\r\nWFS_Capabilities/Service/OnlineResource\r\nWCS_Capabilities/Service/responsibleParty/onlineResource/@xlink:href\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_FEES', '', 'frei', 'An Stelle WMS_FEES\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_ACCESSCONSTRAINTS', '', 'none', 'WMT_MS_Capabilities/Service/AccessConstraints\r\nWFS_Capabilities/Service/AccessConstraints\r\nWCS_Capabilities/Service/accessConstraints\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_CONTACTPERSON', '', 'Max Mustermann', 'An Stelle von WMS_CONTACTPERSON\r\nWMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactPerson\r\nWCS_Capabilities/Service/responsibleParty/individualName\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_CONTACTORGANIZATION', '', 'Organisation XY', 'An Stelle von WMS_CONTACTORGANIZATION\r\nWMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactOrganization\r\nWCS_Capabilities/Service/responsibleParty/organisationName\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_CONTACTPOSITION', '', 'EDV-Leiter', 'An Stelle von WMS_CONTACTPOSITION\r\nWMT_MS_Capabilities/Service/ContactInformation/ContactPosition\r\nWCS_Capabilities/Service/responsibleParty/positionName\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_ADDRESSTYPE', '', 'postal', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/AddressType\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_ADDRESS', '', 'Musterstraße 42', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Address\r\nWCS_Capabilities/Service/contactInfo/address/deliveryPoint\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_CITY', '', 'Musterstadt', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/City\r\nWCS_Capabilities/Service/contactInfo/address/city\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_STATEORPROVINCE', '', 'Mecklenburg-Vorpommern', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/StateOrProvince\r\nWCS_Capabilities/Service/contactInfo/address/administrativeArea\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_POSTCODE', '', '18069', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/PostCode\r\nWCS_Capabilities/Service/contactInfo/address/postalCode\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_COUNTRY', '', 'Germany', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Country\r\nWCS_Capabilities/Service/contactInfo/address/country\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_CONTACTVOICETELEPHONE', '', '0381/1234567', 'WMT_MS_Capabilities/Service/ContactInformation/ContactVoiceTelephone\r\nWCS_Capabilities/Service/contactInfo/phone/voice\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_CONTACTFACSIMILETELEPHONE', '', ' 0381/1234568', 'WMT_MS_Capabilities/Service/ContactInformation/ContactFacsimileTelephone\r\nWCS_Capabilities/Service/contactInfo/phone/facsimile\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_CONTACTELECTRONICMAILADDRESS', '', 'mustermann@mail.de', 'An Stelle von WMS_CONTACTELECTRONICMAILADDRESS\r\nWMT_MS_Capabilities/Service/ContactInformation/ContactElectronicMailAddress\r\nWCS_Capabilities/Service/contactInfo/address/eletronicMailAddress\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('OWS_SRS', '', 'EPSG:25833 EPSG:4326 EPSG:2398', 'An Stelle von WMS_SRS\r\nWMT_MS_Capabilities/Capability/Layer/SRS\r\nWMT_MS_Capabilities/Capability/Layer/Layer[*]/SRS\r\nWFS_Capabilities/FeatureTypeList/FeatureType[*]/SRS\r\nunless differently defined in LAYER object\r\nif you are setting > 1 SRS for WMS, you need to define \"wms_srs\" and \"wfs_srs\"\r\nseperately because OGC:WFS only accepts one OUTPUT SRS\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
('WFS_SRS', '', 'EPSG:25833', '', 'string', 'OWS-METADATEN', '', 1, 2),
('METADATA_AUTH_LINK', '', '', 'URL zum Authentifizieren am CSW-Metadatensystem\r\n', 'string', 'z CSW-Metadatensystem', '', 1, 2),
('METADATA_ONLINE_RESOURCE', '', '', 'URL zum CSW-Server\r\n', 'string', 'z CSW-Metadatensystem', '', 1, 2),
('METADATA_EDIT_LINK', '', '', 'URL zum Editieren von Metadaten im CSW-Metadatensystem\r\n', 'string', 'z CSW-Metadatensystem', '', 1, 2),
('METADATA_SEARCH_LINK', '', '', 'URL zum Recherchieren von Metadaten im CSW-Metadatensystem\r\n', 'string', 'z CSW-Metadatensystem', '', 1, 2),
('LOGIN_AGREEMENT', 'SNIPPETS', 'login_agreement.php', 'PHP-Seite, welche die Agreement-Message anzeigt', 'string', 'Layout', NULL, 1, 3),
('LOGIN_NEW_PASSWORD', 'SNIPPETS', 'login_new_password.php', 'PHP-Seite, auf der man ein neues Passwort vergeben kann', 'string', 'Layout', NULL, 1, 3),
('LOGIN_REGISTRATION', 'SNIPPETS', 'login_registration.php', 'PHP-Seite, auf der man sich registrieren kann', 'string', 'Layout', NULL, 1, 3),
('LOGIN_ROUTINE', 'CUSTOM_PATH', '', 'hier kann eine PHP-Datei angegeben werden, welche beim Login-Vorgang ausgeführt wird', 'string', 'Administration', NULL, 1, 2),
('LOGOUT_ROUTINE', 'CUSTOM_PATH', '', 'hier kann eine PHP-Datei angegeben werden, welche beim Logout-Vorgang ausgeführt wird', 'string', 'Administration', NULL, 1, 2),
('OWS_HOURSOFSERVICE', '', 'Wochentags 8:00 - 16:00 Uhr', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2),
('OWS_CONTACTINSTRUCTIONS', '', 'Telefon oder E-Mail', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2),
('OWS_ROLE', '', 'GIS-Administrator', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2),
('USE_EXISTING_SESSION', '', 'false', 'Wenn man auf einem Server mehrere kvwmap-Instanzen laufen hat und möchte, dass ein Nutzer sich nur einmal an einer Instanz anmelden muss, kann man diesen Parameter auf true setzen. Voraussetzung ist natürlich, dass die kvwmap-Instanzen die gleichen Nutzerdaten verwenden.', 'boolean', 'Administration', NULL, 1, 2),
('CUSTOM_RASTER', 'SHAPEPATH', 'custom_raster/', 'Das Verzeichnis, in dem die von den Nutzern hochgeladenen Rasterdateien abgelegt werden.', 'string', 'Pfadeinstellungen', NULL, 1, 2),
('OGR_BINPATH_GDAL', '', '/usr/local/gdal/bin/', 'Wenn man dem ogr oder gdal Befehl docker exec gdal voranstellt, wird das ogr bzw. gdal in dem gdal Container verwendet statt des ogr bzw. gdal im Web Container. Diese Konstante gibt an wo sich das Bin-Verzeichnis innerhalb des verwendeten GDAL-Containers befindet.', 'string', 'Pfadeinstellungen', NULL, 1, 2),
('PASSWORD_INFO', '', '', 'Hier kann ein Hinweistext eingetragen werden, welcher bei der Passwortvergabe erscheint.', 'string', 'Administration', NULL, 1, 2),
('GEO_NAME_SEARCH_URL', '', 'https://nominatim.openstreetmap.org/search.php?format=geojson&viewbox=11.9987,54.04853,12.23795,54.20613&bounded=1&q=', 'URL eines Geo-Namen-Such-Dienstes. Der Dienst muss GeoJSON zurückliefern.', 'string', 'Administration', NULL, 1, 2),
('GEO_NAME_SEARCH_PROPERTY', '', 'display_name', 'Das Attribut welches als Suchergebnis bei der Geo-Namen-Suche angezeigt werden soll.', 'string', 'Administration', NULL, 1, 2),
('CUSTOM_PATH', '', 'custom/', 'Pfad in dem sich Dateien befinden, die nicht vom kvwmap Repository getrackt werden.', 'string', 'Pfadeinstellungen', NULL, 1, 0),
('BG_IMAGE', 'GRAPHICSPATH', 'bg.gif', 'Hintergrundbild für die Oberfläche', 'string', 'Layout', NULL, 1, 3),
('ROLLENFILTER', '', 'false', 'Legt fest, ob Nutzer eigene Filter für Layer erstellen können.', 'boolean', 'Administration', NULL, 1, 2),
('NORMALIZE_AREA_THRESHOLD', '', '0.5', 'Maximale Flächengröße von Dreiecken, die durch 3 benachbarte Stützpunkte gebildet werden mit dem Winkel im mittleren Stützpunkt kleiner als NORMALIZE_ANGLE_THRESHOLD verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Quadatmeter. Zentralpunkte, deren Flächen kleiner sind, werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.5. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2),
('NORMALIZE_ANGLE_THRESHOLD', '', '0.5', 'Maximale Winkelgröße im mittleren Stützpunkt von 3 benachbarten Stützpunkten, deren Fläche kleiner als NORMALIZE_AREA_THRESHOLD ist. Zentralpunkte in denen der Winkel kleiner ist werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Angegeben in Dezimalgrad. Default 0.5 Grad.  Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2),
('NORMALIZE_POINT_DISTANCE_THRESHOLD', '', '0.005', 'Maximaler Abstand von benachbarten Punkten in einem Dreieck welches kleiner ist als NORMALIZE_AREA_THRESHOLD unter Berücksichtigung von NORMALIZE_ANGLE_THRESHOLD verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Meter. Ein Punkt bei dem der Abstand zum anderen kleiner wird bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.005. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2),
('NORMALIZE_NULL_AREA', '', '0.0001', 'Maximale Flächengröße von Dreiecken, die durch 3 benachbarte Stützpunkte gebildet werden unabhängig von den Winkeln verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Quadatmeter. Zentralpunkte, deren Flächen kleiner sind, werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.0001. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2),
('POSTGRES_CONNECTION_ID', '', '1', 'ID der Postgresql-Datenbankverbindung aus Tabelle connections', 'numeric', 'Datenbanken', NULL, 1, 2),
('MAILCOPYATTACHMENT', '', 'true', 'Sollen Dateien in E-Mail-Anhängen beim Versenden in den Archiv-Ordner kopiert (true) oder verschoben (false) werden.\r\n', 'string', 'E-Mail Einstellungen', '', 0, 2),
('MS_DEBUG_LEVEL', '', '0', 'Legt den Debug-Level für MapServer fest. Werte von 0 bis 5 sind möglich.', 'integer', 'Logging', NULL, 0, 2),
('IMPORT_POINT_STYLE_ID', '', '', 'Hier kann ein eigener Style für den Datenimport von Punkt-Objekten eingetragen werden.', 'integer', 'Layout', NULL, 0, 2);

INSERT INTO `connections` (`id`, `name`, `host`, `port`, `dbname`, `user`, `password`) VALUES
(1, 'kvwmapsp', 'pgsql', 5432, 'kvwmapsp', 'kvwmap', 'KvwMapPW1');


INSERT INTO `ddl_colors` (`id`, `red`, `green`, `blue`) VALUES
(1, 200, 200, 200),
(2, 215, 215, 215),
(3, 230, 230, 230),
(4, 181, 217, 255),
(5, 218, 255, 149),
(6, 255, 203, 172);
