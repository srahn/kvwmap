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
INSERT INTO `stelle` ( `ID` , `Bezeichnung` , `start` , `stop` , `minxmax` , `minymax` , `maxxmax` , `maxymax` , `epsg_code`, `Referenzkarte_ID` , `Authentifizierung` , `ALB_status` , `wappen` , `alb_raumbezug` , `alb_raumbezug_wert` )
VALUES (
@stelle_id, 'Administration', '0000-00-00', '0000-00-00', 201165, 5867815, 477900, 6081468, 25833, '1', '1', '30', 'Logo_GDI-Service_200x47.png', '', '');

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
 '1','Uebersichtskarte', 'uebersicht_mv.png', 201165, 5867815, 477900, 6081468, '205', '146', 25833
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
(21, 'ALB-Auszug 30', NULL),
(22, 'Kolibristart', NULL);


####################################################################################
# Eintragen von Berechtigungen für einen Administrator zum Ausführen von Funktionen
####################################################################################
# 2006-05-12

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
INSERT INTO `u_funktion2stelle` (`funktion_id`,`stelle_id`) VALUES (22,@stelle_id);

###########################
# Einträge der Menüpunkte #
###########################
#### gegebenenfalls vorherige Einträge löschen
# TRUNCATE u_menues;
# TRUNCATE u_menue2stelle;

# Die nachfolgenden Statements müssen in 1.5 angepasst werden
# Alle Gruppen von Menüs sind in einer separaten Tabelle u_groups enthalten und in der Tabelle u_menues erscheinen in der Spalte
# Gruppe nur noch die ID´s der Gruppen aus der Tabelle u_groups
# Wer seine Tabellen dahingehend anpassen möchte muss das entsprechende Statement aus mysql_update.php ausführen.
# siehe "Erzeugen einer neuen Tabelle groups"

INSERT INTO `u_menues` (name, links, obermenue, menueebene, target, `order`, `title`, `button_class`) VALUES ('Stelle wählen', 'index.php?go=Stelle Wählen', 0, 1, NULL, 1, 'Zu anderen Stellen und den Einstellungen', 'optionen');
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
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`, `title`, `button_class`) VALUES ('Karte', 'index.php', 0, 1, NULL, 3, 'Karte anzeigen', 'karte');
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


#### Stellenverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`, `title`) VALUES ('Stellenverwaltung', 'index.php?go=changemenue', 0, 1, NULL, 10, 'Alle Stellen anzeigen');
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,60);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Stellen anlegen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Stellen anlegen', 'index.php?go=Stelleneditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,61);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Stellen anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Stellen anzeigen', 'index.php?go=Stellen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,62);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layer-Rechteverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layer-Rechte', 'index.php?go=Layerattribut-Rechteverwaltung', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,63);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Layer-Rechteverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Filterverwaltung', 'index.php?go=Filterverwaltung', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,64);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Funktionenverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Funktionenverwaltung', 'index.php?go=changemenue', 0, 1, NULL, 10);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,66);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Funktion anlegen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Funktion anlegen', 'index.php?go=Funktionen_Formular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,67);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Funktionen anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Funktionen anzeigen', 'index.php?go=Funktionen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,68);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Menueverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`, `title`) VALUES ('Menüverwaltung', 'index.php?go=changemenue', 0, 1, NULL, 5, 'Alle Menüpunkte anzeigen');
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,66);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Menue anlegen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Menü anlegen', 'index.php?go=Menueeditor', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,67);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Menues anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Menüs anzeigen', 'index.php?go=Menues_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,68);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Nutzerverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Nutzerverwaltung', 'index.php?go=changemenue', 0, 1, NULL, 20);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,70);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Nutzer anlegen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Nutzer anlegen', 'index.php?go=Benutzerdaten_Formular', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,71);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Nutzer anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Nutzer anzeigen', 'index.php?go=Benutzerdaten_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,72);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Layerverwaltung
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Layerverwaltung', 'index.php?go=changemenue', 0, 1, NULL, 30);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id,75);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# Layer anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layer anzeigen', 'index.php?go=Layer_Anzeigen', @last_level1menue_id, 2, NULL);
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

# Layergruppen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Layergruppen', 'index.php?go=Layergruppen_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 82);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id, 0);

# Layerübersicht
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Themenübersicht', 'index.php?go=Layer_Uebersicht', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 84);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id, 0);

#### Import/Export
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Import/Export', 'index.php?go=changemenue', 0, 1, NULL, 40);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_level1menue_id, 86);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_level1menue_id,0);

# WMS-Export
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('WMS-Export', 'index.php?go=WMS_Export', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id,88);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# WMS-Import
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('WMS-Import', 'index.php?go=WMS_Import', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 90);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Daten-Export
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Daten-Export', 'index.php?go=Daten_Export', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 92);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Shape Anzeigen
INSERT INTO u_menues (name, links, obermenue, menueebene, target) VALUES ('Shape-Anzeigen', 'index.php?go=SHP_Anzeigen', @last_level1menue_id, 2, NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 94);
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

# Druckausgabe
INSERT INTO u_menues (`name`, `links`, `obermenue`, `menueebene`, `target`, `title`, `button_class`) VALUES ('Druckausgabe', 'index.php?go=Schnelle_Druckausgabe', @last_level1menue_id, 2, '_blank', 'Karte sofort ausdrucken', 'schnelldruck');
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) VALUES (@stelle_id,@last_menue_id, 96);
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
VALUES ( 'Kartendrucklayouteditor', 'index.php?go=Druckrahmen', @last_level1menue_id, '2', NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_menue_id, '89');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target` )
VALUES ( 'Datendrucklayouteditor', 'index.php?go=sachdaten_druck_editor', @last_level1menue_id, '2', NULL);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_menue_id, '90');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);

#### Drucken
INSERT INTO u_menues ( `name` , `links` , `obermenue` , `menueebene` , `target`, `title`, `button_class`)
VALUES ( 'Drucken', 'index.php?go=Druckausschnittswahl', @last_level1menue_id, '2', NULL, 'Zur Druckausschnittswahl', 'drucken');
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO `u_menue2stelle` ( `stelle_id` , `menue_id` , `menue_order` )
VALUES (@stelle_id, @last_menue_id, '91');
INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) VALUES (@user_id,@stelle_id,@last_menue_id,0);


## Metadaten
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Metadaten', 'index.php?go=changemenue', 0, 1, NULL, 100);
SET @last_level1menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_level1menue_id, 100);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id, status) VALUES (@user_id, @stelle_id, @last_level1menue_id, 0);
# Recherche
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Recherche', 'index.php?go=Metadaten_Auswaehlen', @last_level1menue_id, 2, NULL, 110);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_menue_id, 110);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id, status) VALUES (@user_id, @stelle_id, @last_menue_id, 0);
# Eingabe
INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Neuer Metadatensatz', 'index.php?go=Metadateneingabe', @last_level1menue_id, 2, NULL, 120);
SET @last_menue_id=LAST_INSERT_ID();
INSERT INTO u_menue2stelle (stelle_id, menue_id, menue_order) VALUES (@stelle_id, @last_menue_id, 120);
INSERT INTO u_menue2rolle (user_id, stelle_id, menue_id, status) VALUES (@user_id, @stelle_id, @last_menue_id, 0);


# Layer-Gruppen anlegen
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (1, 'Hintergrundkarten', 1000);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (2, 'Verwaltungsgrenzen', 900);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (3, 'Kataster', 800);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (4, 'Umwelt', 700);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (5, 'Bauen', 600);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (6, 'Raumordnung', 500);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (7, 'Soziales', 400);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (8, 'Verkehr', 300);
INSERT INTO `u_groups` (`id`, `Gruppenname`, `order`) VALUES (9, 'Administration', 100);

# einen ersten WMS Layer anlegen
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`) VALUES('ORKa-MV (OSM)', '', '3', '1', NULL, NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, '', 'http://www.orka-mv.de/geodienste/orkamv/wms?VERSION=1.1.1&LAYERS=orkamv&STYLES=&FORMAT=image/jpeg', '', 7, '', '', 3, 'pixels', '25833', '', '0', NULL, NULL, NULL, NULL, '', 'EPSG:25833', 'stadtplan', '1.1.1', 'image/png', 60, '', '', '', 'radio', '0', '', '', '', '');
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
INSERT INTO `druckrahmen2stelle` (`stelle_id`, `druckrahmen_id`) VALUES (@last_druckrahmen_id, @stelle_id);