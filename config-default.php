<?php
####################################################################
#                                                                  #
#   Konfigurationsdatei zu kvwmap                                  #
#                                                                  #
####################################################################
# aktuelle Versionsnummer
define('VERSION','1.9.0');
define('APPLVERSION','kvwmap_'.VERSION.'/');
# Bezeichnung der MySQL-Datenbank mit den Benutzerdaten
$dbname='kvwmapdb';
# Wenn der pgdbname leer gelassen wird, wird versucht die Information
# für die Verbindung zur PostGIS-Datenbank aus der Tabelle Stelle zu lesen.
$pgdbname='kvwmapsp';
#$pgdbname='alkis_25833';

########################## Metadaten zum Landkreis
define('LANDKREIS', 'für den Landkreis');
define('AMT', 'Kataster-/Vermessungsamt  0017');
define('STRASSE', 'Nordvorpommern und die');
define('STRASSE2', '');																	# Version 1.7.1
define('PLZ', 'Hansestadt Stralsund');
define('ORT', '');
define('POSTANSCHRIFT', '');
define('POSTANSCHRIFT_STRASSE', '');
define('POSTANSCHRIFT_PLZ', '');
define('POSTANSCHRIFT_ORT', '');
# definiert, ob Nutzername im ALB-Auszug 30 angezeigt wird, oder nicht
define('BEARBEITER', 'false');			# true/false					# Version 1.7.2
# Gutachterausschuss BORIS
#define('GUTACHTERAUSSCHUSS', '12345 NVP');								# Version 1.7.3			# in Version 1.7.5 wieder gelöscht
$gutachterausschuesse = array('12345', '6789');       # Version 1.7.5
# katasterführende Stellen ALB
# bei zwei katasterführenden Stellen in einer kvwmap-DB (Nur für Adressänderungen wichtig, sonst auskommentieren)
# erste Stelle bis einschließlich GBBZ-Schlüssel, zweite Stelle bis einschließlich GBBZ-Schlüssel, ....
# wer nur eine katasterführende Stelle hat, kann das Array weglassen oder auskommentieren
#$katasterfuehrendestelle = array('0019' => '132845', '0021' => '132846');		# Version 1.9.0
$katasterfuehrendestelle = array('132845' => '0019', '132846' => '0021');		# Version 1.10.0  (Schlüssel und Werte wurden vertauscht)


# definiert, ob zu Tetszwecken auf ein PostNAS-Schema zugegriffen wird, oder nicht
define('ALKIS', false);																# Version 1.8.0
#define('ALKIS', true);																# Version 1.8.0
#define("LAYERNAME_FLURSTUECKE",'Flurstuecke_Alkis');
#define('EPSGCODE','25833');


########################## Layout-Vorgaben
# Schalter für die neue Legende
#define('NEW_LEGEND', 'true');                               # Version 1.6.7			in Version 1.7.3 wieder gelöscht

# definiert, ob der Polygoneditor nach einem Neuladen
# der Seite immer in den Modus "Polygon zeichnen" wechselt
define('ALWAYS_DRAW', 'true');     #true/false             # Version 1.6.9
# Schalter für eine zeilen- oder spaltenweise Darstellung der Attribute im generischen Layereditor  # Version 1.6.5
define('GLEVIEW', 2);                  # 1 / 2              # Version 1.6.5
# Header und Footer
define('HEADER', 'header.php');															# Version 1.8.0
define('FOOTER', 'footer.php');															# Version 1.8.0
# login.php
define('LOGIN', 'login.php');																# Version 1.8.0

# Google Maps Api Key, zu beschaffen für die eigenen Domain unter: http://code.google.com/intl/de/apis/maps/signup.html
# Schlüssel für https://kvwmap.geoinformatik.uni-rostock.de)
define('GOOGLE_API_KEY','ABQIAAAA3NVkF8JG1Orwwdy6Z6N2ghQjhy709LeHrxCbc4HTlSWWZ4JR9RShfK5WWmrk3Wm9SGzJyhwtosboyQ');
# Schlüssel für http://localhost
#define('GOOGLE_API_KEY','ABQIAAAA3NVkF8JG1Orwwdy6Z6N2ghT2yXp_ZAY8_ufC3CFXhHIE1NvwkxTr0pvpUqbRW25i9TOdNAALRKTbTg');

### Einstellungen für das Menü
# Da die Bezeichnungen der Menüs frei wählbar sind, muss man hier angegen
# welchen Namen man für welche Aufgabe gewählt hat.
# Hier werden die Werte aus der Spalte name der Tabelle u_menues
# Konstanten zugewiesen um sie später beim Layout verwenden zu können
define('TITLE','kvwmap Entwicklungsserver');             		# Version 1.7.6
define('TITLE_DRUCKEN','Druckausschnittswahl');             # Version 1.6.6
define('TITLE_KARTE','Karte anzeigen');                     # Version 1.6.6
define('TITLE_NOTIZEN','neue Notiz');                       # Version 1.6.6
define('TITLE_HILFE','Hilfe');                  						# Version 1.6.6
define('TITLE_OPTIONEN','Stelle w&auml;hlen');										# Version 1.7.3
define('TITLE_SCHNELLDRUCK','schnelle Druckausgabe');					# Version 1.7.5
# Schalter für die PopUp-Funktion des Menüs
define('POPUPMENUE','false');          # true / false
# Position des Wappens (oben/unten/kein)
define('MENU_WAPPEN','oben');          # oben / unten / kein
# Position der Referenzkarte (oben/unten)                   # Version 1.6.4
define('MENU_REFMAP','unten');          # oben / unten      # Version 1.6.4
# Schalter für die Ajax-Funktionalität des Menüs            # Version 1.6.4
define('AJAX_MENUE','true');          # true / false        # Version 1.6.4
# Hintergrundfarbe Zeile bei Listen
define('BG_TR','lightsteelblue');  # lightblue lightsteelblue		# Version 1.7.3
# Hintergrundfarbe Top-Menüzeilen
define('BG_MENUETOP','#DAE4EC');																# Version 1.7.3
# Hintergrundfarbe Sub-Menüzeilen
define('BG_MENUESUB','#EDEFEF'); 																# Version 1.7.3
# Hintergrundfarbe (Kopf-/Fusszeile)
define('BG_DEFAULT','lightsteelblue');  # lightblue lightsteelblue
# Hintergrundfarbe (Eingabeformulare)
define('BG_FORM','lightsteelblue');     #66CCFF cornflowerblue  steelblue mediumslateblue
# Hintergrundfarbe (Formularfehler)
define('BG_FORMFAIL','lightpink');      #FFAEAE thistle lightpink mistyrose hotpink lightcoral

# Hintergrundfarbe GLE Datensatzheader
define('BG_GLEHEADER','lightsteelblue'); # #99D942		# Version 1.8.0
# Schriftfarbe GLE Datensatzheader
define('TXT_GLEHEADER','#000000');						# Version 1.8.0
# Hintergrundfarbe GLE Attributnamen
define('BG_GLEATTRIBUTE','#DAE4EC');					# Version 1.8.0

# Bezeichung des Datenproviders
define('PUBLISHERNAME','Kartenserver');
# Auswahl der Art der Lagebezeichung für den aktuell angezeigten Kartenausschnitt
# Je nach dem was hier eingetragen wird wird ein Fall zur Anzeige der Lage verwendet
# Die Unterscheidung wird in der Funkiton getLagebezeichnung in kvwmap.php vorgenommen
# Varianten:
# Flurbezeichnung: bedeutet Ausgabe von Gemeinde, Gemarkung und Flur, soweit in ALK tabellen vorhanden
# Wenn kein Wert gesetzt wird, erfolgt keine Anzeige einer Lagebezeichung
#define('LAGEBEZEICHNUNGSART', 'Flurbezeichnung');						# in Version 1.7.6 wieder eingeführt für Fälle ohne ALK

# Erweiterung der Authentifizierung um die IP Adresse des Nutzers
# Testet ob die IP des anfragenden Clientrechners dem Nutzer zugeordnet ist
define('CHECK_CLIENT_IP',true);                               # Version 1.6.9 Setzt zusätzliche Spalte in Tabelle stelle voraus.

########################## Pfadeinstellungen
# Installationspfad
define('INSTALLPATH','/home/fgs/fgs/');
define('WWWROOT',INSTALLPATH.'apps/');
# --------------- Version 1.6.6 Start
# diese Einstellung ermöglicht die Vergabe von mehrere Call Back Links
# Wenn Benutzer z.B. von einem anderen Netzwerk aus auf kvwmap zugreifen,
# als Nutzer im Intranet.
$ip=explode('.',getenv('REMOTE_ADDR'));
$subnetaddr=$ip[0].'.'.$ip[1];
switch ($subnetaddr) {
  case "x.y" : {
    define('URL','https://andere.adresse.de/');
  } break;
  default : {
    define('URL','http://www.mdi-de.org:8080/');
  }
}
# -----------------Version 1.6.6 End
define('MAPSERV_CGI_BIN',URL.'cgi-bin/mapserv');
define('LOGPATH',INSTALLPATH.'www/logs/');
# Shapepath [Pfad zum Shapefileverzeichnis]
define('SHAPEPATH',INSTALLPATH.'www/var/data/');
# Custom-Shapepath [Name des Verzeichnisses, in dem die von den Usern hochgeladenen SHPs liegen (muss im SHAPEPATH liegen)]
define('CUSTOM_SHAPEPATH', 'custom_shps/');							# Version 1.7.4
define('REFERENCEMAPPATH',SHAPEPATH.'referencemaps/');
define('NACHWEISDOCPATH',SHAPEPATH.'nachweise/');
# Dateiname und Pfad der Festpunkte, mit der die Tabelle der Festpunkte aktualisiert werden soll
define('PUNKTDATEINAME','festpunkte.csv');
#define('PUNKTDATEINAME','alk');
define('PUNKTDATEIPATH',SHAPEPATH.'Festpunkte/');
define('PUNKTDATEIARCHIVPATH',PUNKTDATEIPATH.'archiv/');
define('KVZAUSGABEDATEINAME','festpunkte.kvz');
define('KVZKOPF', '# Datenaustauschformat M-V
#LS PKZ            VMA  RECHTSWERT    HOCHWERT    HOEHE H H  L L
#                                                       Z G  Z G');
define('SKIZZEN_DATEI_TYP','tif');            # Version 1.6.8
# Pfad zu den WLDGE Dateien
define('WLDGEFILEPATH',SHAPEPATH.'ALB/');
# Name der WLDGE Datei, die geladen werden soll
define('WLDGEFILENAME','FF_klein.wldge');
# Schalter zum Vergleich der Datum der Grundausstattung und Fortführung in der Datenbank und der WLDGE Dateien
# beim Einlesen der ALB-Daten
# default 1
define('WLDGE_DATUM_PRUEFUNG',1);
#define('WLDGE_DATUM_PRUEFUNG',0);
/*
 * Wenn historische_loeschen=1 werden alle in der Fortführungsdatei aufgeführten
 * historischen Objekte aus dem Bestand gelöscht
*/
  define('WLDGE_HISTORISCHE_LOESCHEN_DEFAULT',0);             # Version 1.7.0

# Pfad zum Speichern der Nachweisrecherche
define('RECHERCHEERGEBNIS_PATH',SHAPEPATH.'Recherchierte_Antraege/');
# Pfad zum Speichern der Druckrahmen
define('DRUCKRAHMEN_PATH',SHAPEPATH.'Druckrahmen/');

# Pfad zu den Funktionen
#define('FKT_PATH',WWWROOT.APPLVERSION.'funktionen/');			# in Version 1.7.3 wieder gelöscht
# Pfad zu den PDF-Generator Klassen
define('PDFCLASSPATH',WWWROOT.'PDFClass/');

# Bin-Pfad der Postgres-tools (shp2pgsql, pgsql2shp)
define('POSTGRESBINPATH', '/usr/local/bin/');         # Version 1.6.4

# Bin-Pfad der OGR-tools (ogr2ogr, ogrinfo)
define('OGR_BINPATH', '/usr/local/bin/');					# Version 1.7.4

# Pfad zum Zip-Programm (unter Linux: 'zip -j', unter Windows z.B. 'c:/programme/Zip/bin/zip.exe')
define('ZIP_PATH', 'zip -j');													# Version 1.7.3  hier wurde das ' -j' angehängt

# EPSG-Code dem die Koordinaten der Flurstücke zugeordnet werden sollen in den Tabellen
# alb_flurstuecke und alb_x_flurstuecke wenn man postgres verwendet
# die Geometriespalte muß auch mit dieser EPSG Nummer angelegt sein.
define('EPSGCODE','2398'); # Krassowski, Pulkowo 42, Gauß Krüger 3° Streifen 4 (12°)
#define('EPSGCODE','2399'); # Krassowski, Pulkowo 42, Gauß Krüger 3° Streifen 5 (15°)

# Unterstützte SRIDs, nur diese stehen zur Auswahl bei der Stellenwahl
$supportedSRIDs = array(4326,2397,2398,2399,31466,31467,31468,32648,25832,25833,35833,32633,325833,15833,900913);                    # Version 1.6.8

# Name der Stopwortdatei
define('STOPWORDFILE',SHAPEPATH.'gazetteer/top10000de.txt');

# Imagepath
define('IMAGEPATH',INSTALLPATH.'tmp/ms_tmp/');
# Pfad für selbst gemachte Bilder
define('CUSTOM_IMAGE_PATH',SHAPEPATH.'Bilder/');                # Version 1.6.9
#Cachespeicherort
define('CACHEPATH',INSTALLPATH.'cache/');                             # Version 1.6.8
#Cachezeit Nach welcher Zeit in Stunden sollen gecachte Dateien aktualisiert werden
#wird derzeit noch nicht berücksichtigt
define('CACHETIME',168);                                          # Version 1.6.8
# relative Pfadangabe zum Webverzeichnis mit temprären Dateien
define('TEMPPATH_REL','../ms_tmp/');
#Imageurl
define('IMAGEURL','/ms_tmp/');
# Symbolset
define('SYMBOLSET',WWWROOT.APPLVERSION.'symbols/symbole.sym');
# Fontset
define('FONTSET',WWWROOT.APPLVERSION.'fonts/fonts.txt');
# Graphics
define ('GRAPHICSPATH','graphics/');
# Wappen
define('WAPPENPATH',GRAPHICSPATH.'wappen/');
# Wasserzeichenbild für Ausdrucke
define('WASSERZEICHEN',WAPPENPATH.'wappen_wz.jpg');
# Layouts
define ('LAYOUTPATH',WWWROOT.APPLVERSION.'layouts/');
define ('SNIPPETS',LAYOUTPATH.'snippets/');
define('CLASSPATH',WWWROOT.APPLVERSION.'class/');
define('TEMPTABLEPREFIX','x_');
# Default Templates für Sachdatenanzeige
define('DEFAULTTEMPLATE',' ');
define('DEFAULTHEADER',' ');
define('DEFAULTFOOTER',' ');
# Erlaubte maximale Länge der Stammnummer in der Fachschale Nachweisverwaltung
define('STAMMNUMMERMAXLENGTH',8);
# maximale Anzahl der in einer Sachdatenabfrage zurückgelieferten Zeilen.
define('MAXQUERYROWS',10);

# Erlaubte maximale Länge der Blattnummer in der Fachschale Nachweisverwaltung
define('BLATTNUMMERMAXLENGTH',4);                         # Version 1.6.7

# PostgreSQL Server Version                         # Version 1.6.4
define('POSTGRESVERSION', '804');                   # Version 1.6.4   (800 == 8.0)

# MySQLSQL Server Version                         # Version 1.6.4
define('MYSQLVERSION', '500');                   # Version 1.6.4   (4.1.0 == 410)

# Mapserver Version                             # Version 1.6.8
define('MAPSERVERVERSION', '542');              # Version 1.6.8     (5.0.2 == 502)

# PHP-Version
define('PHPVERSION', '450');										# Version 1.7.1  (5.2.0 == 520)

# Schalter für die mobile Variante
define('MOBILE', 'true');                                  # Version 1.6.7 (noch in Entwicklung)

# Pfad zur GPS-Logdatei                         # Version 1.7.0
define('GPSPATH', SHAPEPATH.'gps/gpsulog.txt');
#define('GPSPATH', 'http://localhost:8081/');
#define('GPSPATH', 'http://www.gdi-service.de/gps_position_nmea_gga.txt');

# Synchronisationsverzeichnis                         # Version 1.7.0
define('SYNC_PATH', SHAPEPATH.'synchro/');

# Faktor für die Einstellung der Druckqualität (MAPFACTOR * 72 dpi)     # Version 1.6.0
define('MAPFACTOR', 3);                                                # Version 1.6.0

# Standarddruckrahmen für die schnelle Druckausgabe						# Version 1.7.4
define('DEFAULT_DRUCKRAHMEN_ID', 42);													# Version 1.7.4

# Zeigt an, ob Image Magick und Ghostscript installiert sind oder nicht (wird für neue Druckvorschau benötigt)
define('IMAGEMAGICK', 'true');                        # Version 1.6.3

# Pfad zum Imagemagick convert
define('IMAGEMAGICKPATH', '/usr/bin/');                        # Version 1.7.3

# Definiert, ob Flächen- oder Punktförmige Bodenrichtwerte erfasst werden ('punkt' oder 'flaeche')
#define('BODENRICHTWERTTYP', 'flaeche');           # Version 1.6.3			# Version 1.7.3: wird nicht mehr verwendet, kann gelöscht werden

# Pfad zum Ordner für Datei-Uploads
define('UPLOADPATH',SHAPEPATH.'upload/');       # Version 1.6.7

# Definiert, ob die Festpunkte in 2 Streifen liegen oder nicht
define('FESTPUNKTE_2_STREIFEN', 'true');  #true/false   # Version 1.6.7

# Legt fest, ob die Hausnummernzusätze groß oder klein dargestellt werden
define('HAUSNUMMER_TYPE', 'LOWER');     # UPPER/LOWER   # Version 1.6.8

# Definiert ob die einzulesende Festpunktedatei auf doppelte Punktkennzeichen getestet werden soll, oder nicht    # Version 1.6.8
define('CHECKPUNKTDATEI', 'true');      # true/false                                                                # Version 1.6.8

# Minmale Maßstabszahl
define('MINSCALE', 1);                                                        # Version 1.7.0


# URL zum Authentifizieren am CSW-Metadatensystem
define('METADATA_AUTH_LINK', 'http://berg.preagro.de:8088/geonetwork/srv/en/xml.user.login?username=admin&password=!admin!');		# Version 1.7.5

# URL zum CSW-Server
define('METADATA_ONLINE_RESOURCE', 'http://berg.preagro.de:8088/geonetwork/srv/en/csw');			# Version 1.7.5

# URL zum Editieren von Metadaten im CSW-Metadatensystem
define('METADATA_EDIT_LINK', 'http://berg:8088/geonetwork/srv/en/metadata.edit?id=');
#define('METADATA_EDIT_LINK', 'http://berg.preagro.de:8088/geonetwork/srv/de/metadata.create?id=1&group=3');		# Version 1.7.5

# URL zum Recherchieren von Metadaten im CSW-Metadatensystem
define('METADATA_SEARCH_LINK', 'http://berg.preagro.de:8088/geonetwork/srv/de/main.home');		# Version 1.7.5

######################### Voreinstellungen zu den Layern
# Layernamen für die ALK
define("LAYERNAME_FLURSTUECKE",'Flurst%cke');
define("LAYERNAME_GEBAEUDE",'Gebaeude');
define("LAYERNAME_NUTZUNGEN",'Nutzung');
define("LAYERNAME_AUSGESTALTUNGEN",'Ausgestaltung');
# Namen für Layer mit administrativen Grenzen
define("LAYERNAME_GEMARKUNGEN",'Gemeinde');
define("LAYERNAME_GEMEINDEN",'Gemarkung');
define("LAYERNAME_FLUR",'Flur');
define("LAYERNAME_BODENRICHTWERTE",'BORIS');	# Version 1.7.3
define("LAYER_ID_ADRESSAENDERUNGEN", '162');  # Version 1.6.7
define("LAYER_IDS_DOP", '79,80');							# Version 1.8.0
define("LAYER_ID_JAGDBEZIRKE", '432');				# Version 1.10.0

######################### Dateieinstellungen
# Datei in der das MapFile als Dokumentation zur Kartenausgabe geschrieben wird
define("DEFAULTMAPFILE",SHAPEPATH.'MapFiles/defaultmapfile_dev.map');
define("SAVEMAPFILE",LOGPATH.'save_mapfile.map');
define("REFMAPFILE",SHAPEPATH.'MapFiles/refmapfile.map');
# Ort der Datei, in der die Meldungen beim Debugen geschrieben werden
define('DEBUGFILE',LOGPATH.VERSION.'_'.$_SESSION['login_name'].'_debug.htm');
# Level der Fehlermeldungen beim debuggen
# 3 nur Ausgaben die für Admin bestimmt sind
# 2 nur Datenbankanfragen
# 1 nur wichtige Fehlermeldungen
# 5 keine Ausgaben
define('DEBUG_LEVEL',1);

# mySQL-Log-Datei zur Speicherung der SQL-Statements              # Version 1.6.0
define('LOGFILE_MYSQL',LOGPATH.VERSION.'_log_mysql.sql');         # Version 1.6.0
# postgreSQL-Log-Datei zur Speicherung der SQL-Statements         # Version 1.6.0
define('LOGFILE_POSTGRES',LOGPATH.VERSION.'_log_postgres.sql');   # Version 1.6.0
# Log-Level zur Speicherung der SQL-Statements                    # Version 1.6.0
define('LOG_LEVEL',2);                                            # Version 1.6.0
# Loglevel
# 0 niemals loggen
# 1 immer loggen
# 2 nur loggen wenn loglevel in execSQL 1 ist.

# Ermöglicht die Ausführung der SQL-Statements in der Datenbank zu unterdrücken.
# In dem Fall werden die Statements nur in die Log-Datei geschrieben.
# Die Definition von DBWRITE ist umgezogen nach start.php, damit das Unterdrücken
# des Schreiben in die Datenbank auch mit Formularwerten eingestellt werden kann.
# das übernimmt in dem Falle die Formularvariable disableDbWrite.
# Hier kann jedoch noch der Defaultwert gesetzt werden
define('DEFAULTDBWRITE',1);                                       # Version 1.6.6

# Die ID der Stelle aus der Datenbank, auf die alle Nutzer Zugriff haben
# und die als Einstiegsseite für neue Benutzer eingestellt ist
define('DEFAULTSTELLE','4');

# Gast-Stellen
#define('GAST_STELLE', 35);						# Version 1.7.5 			# in Version 1.8.0 geloescht
$gast_stellen = array(35);						# Version 1.8.0

#### Einstellungen zur Speicherung der Zugriffe
define('LOG_CONSUME_ACTIVITY',1);

# Legt fest, ob die Rollenlayer beim Login eines Nutzers gelöscht werden sollen   # Version 1.6.5
define('DELETE_ROLLENLAYER', 'true');   # true / false                            # Version 1.6.5

# Definiert, ob das aktuelle Kartenbild separat angezeigt werden darf oder nicht
define('SHOW_MAP_IMAGE', 'true');       # true / false                            # Version 1.6.7



############################# Klassenbibliotheken lesen
# laden der Klassenbibliotheken
include (CLASSPATH.'kvwmap_core.php');							# Version 1.7.6
include (CLASSPATH.'kataster_core.php');						# Version 1.7.6
include (CLASSPATH.'mysql.php');										# Version 1.7.6
include (CLASSPATH.'postgresql_core.php');					# Version 1.7.6
include (CLASSPATH.'users_core.php');								# Version 1.7.6
if($_REQUEST['go'] != 'getMap_ajax'){								# Version 1.7.6    (die folgenden Klassen nicht laden, wenn man nur in der Karte navigiert)
	include (CLASSPATH.'kvwmap.php');
	include (CLASSPATH.'kataster.php');
	include (CLASSPATH.'postgresql.php');
	include (CLASSPATH.'users.php');
	include (CLASSPATH.'alb.php');
	include (CLASSPATH.'alk.php');
	include (CLASSPATH.'antrag.php');
	include (CLASSPATH.'bau.php');
	include (CLASSPATH.'nachweis.php');
	include (CLASSPATH.'geothermie.php');
	include (CLASSPATH.'bodenrichtwerte.php');
	include (CLASSPATH.'verundentsorgung.php');
	include (CLASSPATH.'metadaten.php');
	include (CLASSPATH.'spatial_processor.php');
	include (CLASSPATH.'bauleitplanung.php');           # Version 1.6.1
	include (CLASSPATH.'jagdkataster.php');             # Version 1.6.1
	include (CLASSPATH.'polygoneditor.php');            # Version 1.6.3
	include (CLASSPATH.'pointeditor.php');              # Version 1.6.3
	include (CLASSPATH.'dbf.php');                      # Version 1.6.5
	include (CLASSPATH.'anliegerbeitraege.php');        # Version 1.6.6   (nur für die, die diese Fachschale nutzen wollen)
	include (CLASSPATH.'gebaeude_editor.php');          # Version 1.6.6   (nur für die, die diese Fachschale nutzen wollen)
	include (CLASSPATH.'documents.php');                # Version 1.6.6
	include (CLASSPATH.'esaf.php');                     # Version 1.6.6
	include (CLASSPATH.'shape.php');                    # Version 1.6.6
	include (CLASSPATH.'gps.php');                      # Version 1.6.7   (noch in Entwicklung)
	include (CLASSPATH.'wms.php');                      # Version 1.6.7   (noch in Entwicklung)
	include (CLASSPATH.'funktion.php');                 # Version 1.6.9
	include (CLASSPATH.'lineeditor.php');               # Version 1.7.0
	include (CLASSPATH.'wfs.php');                      # Version 1.7.0
	include (CLASSPATH.'synchronisation.php');          # Version 1.7.0
	include (CLASSPATH.'rok.php');          						# Version 1.7.1
	include (CLASSPATH.'tif.php');          						# Version 1.7.2
	include (CLASSPATH.'gpx.php');          						# Version 1.7.4
	include (CLASSPATH.'datendrucklayout.php');         # Version 1.7.5
	include (CLASSPATH.'metadaten_csw.php');						# Version 1.7.5
	include (CLASSPATH.'uko.php');          						# Version 1.8.0
}																											# Version 1.7.6
include (WWWROOT.APPLVERSION.'funktionen/allg_funktionen.php');		# In Version 1.7.3 angepasst

################################ Erweiterungen laden
# PHP-Extensions laden
#dl('php_mapscript.so');             #Version 5.0.2
#dl('php_mapscript_4.10.0.so');     #Version 4.10

# Einstellen des Debuglevels und öffnen der Debug-log-datei
if (DEBUG_LEVEL>0) {
 # Datei für debug Meldungen öffnen
 $debug=new debugfile(DEBUGFILE);
}

# Öffnen der Log-Dateien
# Derzeit werden in den Log-Dateien nur die SQL-Statements gespeichert, die über execSQL
# in den Classen mysql und postgres ausgeführt werden.
if (LOG_LEVEL>0) {
 # Datei für mysql-logs öffnen
 $log_mysql=new LogFile(LOGFILE_MYSQL,'text','Log-Datei MySQL', '#------v: '.date("Y:m:d H:i:s",time())); # Version 1.6.4
 # Datei für postgres-logs öffnen
 $log_postgres=new LogFile(LOGFILE_POSTGRES,'text', 'Log-Datei-Postgres', '------v: '.date("Y:m:d H:i:s",time())); # Version 1.6.4
}

# Festlegung von Fehlermeldungen und Hinweisen
define ('INFO1','Prüfen Sie ob Ihr Datenbankmodell aktuell ist.');

# Character Set der MySQL-Datenbank
define(MYSQL_CHARSET,'latin1');													# Version 1.7.6
define(POSTGRES_CHARSET,'LATIN1');												# Version 1.8.0


################################ Datenbankangaben setzen######################			
# Datenbank für die Nutzerdaten (mysql)
$userDb=new database();
$userDb->host='localhost';
$userDb->user='kvwmap';
$userDb->passwd='kvwmap';
$userDb->dbName=$dbname;

$GISdb = $userDb; 																			# Version 1.7.6


# Datenbank mit den Geometrieobjekten (PostgreSQL mit PostGIS Aufsatz)
if ($pgdbname!='') {
	if($_REQUEST['go'] == 'getMap_ajax'){									# Version 1.7.6
		$PostGISdb=new pgdatabase_core();										# Version 1.7.6
	}																											# Version 1.7.6
	else{																									# Version 1.7.6
  	$PostGISdb=new pgdatabase();												# Version 1.7.6
	}																											# Version 1.7.6
  $PostGISdb->host='localhost';
  $PostGISdb->user='kvwmap';
  $PostGISdb->passwd='kvwmap';
  $PostGISdb->dbName=$pgdbname;
}																																								

##################################################

# Metadaten
########################## Voreinstellungen für die Ausgabe von Dienste MapFiles
define('MAPFILENAME','kvwmap');
# Voreinstellungen für Metadaten zu Web Map Services (WMS-Server)
define('WMS_MAPFILE_REL_PATH','wms/');
define('WMS_MAPFILE_PATH',INSTALLPATH.WMS_MAPFILE_REL_PATH);
define('SUPORTED_WMS_VERSION','1.1.0');

# Metadaten zur Ausgabe im Capabilities Dokument gelten für WMS, WFS und WCS
# sets base URL for OGC Schemas so the root element in the
# Capabilities document points to the correct schema location
# to produce valid XML
define("OWS_SCHEMAS_LOCATION","http://schemas.opengeospatial.net");
# unified OWS tags
# /WMT_MS_Capabilities/Service/Title
# /WMT_MS_Capabilities/Capability/Layer/Title
# /WFS_Capabilities/Service/Title

# An Stelle von WMS_TITLE
define("OWS_TITLE","MapServer kvwmap");
# /WMT_MS_Capabilities/Service/Abstract
# /WFS_Capabilities/Service/Abstract

# An Stelle von WMS_Abstract
define("OWS_ABSTRACT","Kartenserver für kommunale Verwaltungen");

# /WMT_MS_Capabilities/Service/KeywordList/Keyword[]
# /WFS_Capabilities/Service/Keywords
# /WCS_Capabilities/Service/keywords/keyword[]
define("OWS_KEYWORDLIST","GIS,Landkreis,Kataster,Geoinformation");

# /WMT_MS_Capabilities/Service/OnlineResource
# /WFS_Capabilities/Service/OnlineResource
# /WCS_Capabilities/Service/responsibleParty/onlineResource/@xlink:href
define("OWS_SERVICE_ONLINERESOURCE",URL.APPLVERSION.'index.php?go=OWS');

# sets:
# /WMT_MS_Capabilities/Capability/Request/GetCapabilities/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/GetCapabilities/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/GetMap/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/GetMap/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/GetFeatureInfo/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/GetFeatureInfo/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/DescribeLayer/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/DescribeLayer/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/GetLegendGraphic/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WMT_MS_Capabilities/Capability/Request/GetLegendGraphic/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WMS_DescribeLayerResponse/LayerDescription/@wfs is WFS is enabled
# /WFS_Capabilities/Capability/Request/GetCapabilities/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WFS_Capabilities/Capability/Request/GetCapabilities/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WFS_Capabilities/Capability/Request/DescribeFeatureType/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WFS_Capabilities/Capability/Request/DescribeFeatureType/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WFS_Capabilities/Capability/Request/GetFeature/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WFS_Capabilities/Capability/Request/GetFeature/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WCS_Capabilities/Capability/Request/GetCapabilities/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WCS_Capabilities/Capability/Request/GetCapabilities/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WCS_Capabilities/Capability/Request/DescribeFeatureType/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WCS_Capabilities/Capability/Request/DescribeFeatureType/DCPType/HTTP/Post/OnlineResource/@xlink:href
# /WCS_Capabilities/Capability/Request/GetFeature/DCPType/HTTP/Get/OnlineResource/@xlink:href
# /WCS_Capabilities/Capability/Request/GetFeature/DCPType/HTTP/Post/OnlineResource/@xlink:href

# sets:
# /WMT_MS_Capabilities/Service/Fees
# /WFS_Capabilities/Service/Fees
# /WCS_Capabilities/Service/fees

# An Stelle WMS_FEES
define("OWS_FEES","zu Testzwecken frei");

# /WMT_MS_Capabilities/Service/AccessConstraints
# /WFS_Capabilities/Service/AccessConstraints
# /WCS_Capabilities/Service/accessConstraints
define("OWS_ACCESSCONSTRAINTS","none");

# OGC:WMS specific tags

# An Stelle von WMS_CONTACTPERSON
# /WMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactPerson
# /WCS_Capabilities/Service/responsibleParty/individualName
define("OWS_CONTACTPERSON","Peter Korduan");

# An Stelle von WMS_CONTACTORGANIZATION
# /WMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactOrganization
# /WCS_Capabilities/Service/responsibleParty/organisationName
define("OWS_CONTACTORGANIZATION","Universität Rostock");

# An Stelle von WMS_CONTACTPOSITION
# /WMT_MS_Capabilities/Service/ContactInformation/ContactPosition
# /WCS_Capabilities/Service/responsibleParty/positionName
define("OWS_CONTACTPOSITION","Softwareentwickler");

# /WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/AddressType
define("OWS_ADDRESSTYPE","postal");

# /WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Address
# /WCS_Capabilities/Service/contactInfo/address/deliveryPoint
define("OWS_ADDRESS","Justus-von-Liebig-Weg 6");

# /WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/City
# /WCS_Capabilities/Service/contactInfo/address/city
define("OWS_CITY","Rostock");

# /WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/StateOrProvince
# /WCS_Capabilities/Service/contactInfo/address/administrativeArea
define("OWS_STATEORPROVINCE","Mecklenburg-Vorpommern");

# /WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/PostCode
# /WCS_Capabilities/Service/contactInfo/address/postalCode
define("OWS_POSTCODE","18059");

# /WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Country
# /WCS_Capabilities/Service/contactInfo/address/country
define("OWS_COUNTRY","Germany");

# /WMT_MS_Capabilities/Service/ContactInformation/ContactVoiceTelephone
# /WCS_Capabilities/Service/contactInfo/phone/voice
define("OWS_CONTACTVOICETELEPHONE","0049-381-498-2164");

# /WMT_MS_Capabilities/Service/ContactInformation/ContactFacsimileTelephone
# /WCS_Capabilities/Service/contactInfo/phone/facsimile
define("OWS_CONTACTFACSIMILETELEPHONE","0049-381-498-2188");

# An Stelle von WMS_CONTACTELECTRONICMAILADDRESS
# /WMT_MS_Capabilities/Service/ContactInformation/ContactElectronicMailAddress
# /WCS_Capabilities/Service/contactInfo/address/eletronicMailAddress
define("OWS_CONTACTELECTRONICMAILADDRESS","peter.korduan@uni-rostock.de");

# An Stelle von WMS_SRS
# /WMT_MS_Capabilities/Capability/Layer/SRS
# /WMT_MS_Capabilities/Capability/Layer/Layer[*]/SRS
# /WFS_Capabilities/FeatureTypeList/FeatureType[*]/SRS
# unless differently defined in LAYER object
# if you are setting > 1 SRS for WMS, you need to define "wms_srs" and "wfs_srs"
# seperately because OGC:WFS only accepts one OUTPUT SRS
define("OWS_SRS","EPSG:25833 EPSG:4326 EPSG:2398");
define("WFS_SRS","EPSG:25833");

/*
# sets /WMT_MS_Capabilities/Capability/Layer/Attribution/OnlineResource/@xlink:href
define("WMS_ATTRIBUTION_ONLINERESOURCE","http://www.preagro.de/");

# sets /WMT_MS_Capabilities/Capability/Layer/Attribution/Title
define("WMS_ATTRIBUTION_TITLE" "Daten aus Kommunen und Landkreisen");"

# sets /WMT_MS_Capabilities/Capability/Layer/Attribution/LogoURL/@width
define("WMS_ATTRIBUTION_LOGOURL_WIDTH","655");

# sets /WMT_MS_Capabilities/Capability/Layer/Attribution/LogoURL/@height
define("WMS_ATTRIBUTION_LOGOURL_HEIGHT" "130");

# sets /WMT_MS_Capabilities/Capability/Layer/Attribution/LogoURL/Format
define("WMS_ATTRIBUTION_LOGOURL_FORMAT","image/png");

# sets /WMT_MS_Capabilities/Capability/Layer/Attribution/LogoURL/OnlineResource/@xlink:href
define("WMS_ATTRIBUTION_LOGOURL_HREF","http://mapserver.gis.umn.edu/mum/header_fsa.png");

# we support GetFeatureInfo text/html queries
# you NEED query headers, footers, and body templates
define("WMS_FEATURE_INFO_MIME_TYPE","text/html");

# sets namespace URI when performing a DescribeFeatureType or GetFeature
# in the root element of the XML response
define("WFS_NAMESPACE_URI","http://www.preagro.de/");

# sets the XML namespace prefix to be used when defining types for data
# in this mapfile
define("WFS_NAMESPACE_PREFIX","ms_ogc_workshop");

# OGC:WCS

# /WCS_Capabilities/Service/label
define("WCS_LABEL","Sample OWS for MapServer OGC Web Services Workshop");

# /WCS_Capabilities/Service/description
define("WCS_DESCRIPTION","Sample OWS for MapServer OGC Web Services Workshop.  Enjoy!");

# /WCS_Capabilities/Service/metadataLink/@xlink:href
define("WCS_METADATALINK_HREF" "http://localhost/ms_ogc_workshop/index.html");
*/
$conf_files = searchdir(WWWROOT.APPLVERSION.'conf/', true);
for($i = 0; $i < count($conf_files); $i++){
  include($conf_files[$i]);
}

?>
