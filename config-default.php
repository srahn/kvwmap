<?php
####################################################################
#                                                                  #
#   Konfigurationsdatei zu kvwmap                                  #
#                                                                  #
####################################################################

#define('VERSION','2.7');						# in Version 2.8 gelöscht
define('APPLVERSION','kvwmap/');
# Bezeichnung der MySQL-Datenbank mit den Benutzerdaten
$dbname='kvwmapdb';
# Wenn der pgdbname leer gelassen wird, wird versucht die Information
# für die Verbindung zur PostGIS-Datenbank aus der Tabelle Stelle zu lesen.
$pgdbname='kvwmapsp';

########################## Metadaten zum Landkreis
define('LANDKREIS', 'Landkreis');
define('AMT', 'Kataster-/Vermessungsamt ');
define('STRASSE', 'Strasse');
define('STRASSE2', 'Strasse2');																	# Version 1.7.1
define('PLZ', 'PLZ');
define('ORT', 'Ort');
define('POSTANSCHRIFT', 'Postanschrift');
define('POSTANSCHRIFT_STRASSE', 'Postanschrift Strasse');
define('POSTANSCHRIFT_PLZ', 'Postanschrift PLZ');
define('POSTANSCHRIFT_ORT', 'Postanschrift Ort');
# definiert, ob Nutzername im ALB-Auszug 30 angezeigt wird, oder nicht
define('BEARBEITER', 'false');			# true/false					# Version 1.7.2
# Gutachterausschuss BORIS
define('GUTACHTERAUSSCHUSS', '12345');								# Version 1.7.3			# in Version 1.7.5 wieder gelöscht	# seit Version 1.11.0 wieder da
#$gutachterausschuesse = array('12345', '6789');       # Version 1.7.5		# in Version 1.11.0 gelöscht
# katasterführende Stellen ALB
# bei zwei katasterführenden Stellen in einer kvwmap-DB (Nur für Adressänderungen wichtig, sonst auskommentieren)
# erste Stelle bis einschließlich GBBZ-Schlüssel, zweite Stelle bis einschließlich GBBZ-Schlüssel, ....
# wer nur eine katasterführende Stelle hat, kann das Array weglassen oder auskommentieren
#$katasterfuehrendestelle = array('0019' => '132845', '0021' => '132846');		# Version 1.9.0
$katasterfuehrendestelle = array('132845' => '0019', '132846' => '0021');		# Version 1.10.0  (Schlüssel und Werte wurden vertauscht)

#define('ALKIS', true);																# Version 1.8.0					# in Version 2.1 gelöscht

########################## Layout-Vorgaben
# hier kann eine eigene css-Datei angegeben werden
define('CUSTOM_STYLE', ''); 											# Version 1.13
# hier können eigene Styles für den Koordinatenzoom und Punktzoom definiert werden
#define('ZOOM2COORD_STYLE_ID', 3244); 												# Version 1.13
#define('ZOOM2POINT_STYLE_ID', 3244); 												# Version 1.13
# definiert, ob der Polygoneditor nach einem Neuladen
# der Seite immer in den Modus "Polygon zeichnen" wechselt
define('ALWAYS_DRAW', 'true');     #true/false             # Version 1.6.9
# Schalter für eine zeilen- oder spaltenweise Darstellung der Attribute im generischen Layereditor  # Version 1.6.5
define('GLEVIEW', 2);                  # 1 / 2              # Version 1.6.5
# Header und Footer
define('HEADER', 'header.php');															# Version 1.8.0
define('FOOTER', 'footer.php');															# Version 1.8.0

# Höhe von Header und Footer zusammen
#define('HEADER_FOOTER_HEIGHT', 166);																# Version 2.0 gelöscht in 2.7
# Breite von Menü und Legende zusammen
#$menue_legend_widths = array('gui.php' => 485, 'gui_button.php' => 486);		# Version 2.0 gelöscht in 2.7

# Höhen und Breiten von Browser, Rand, Header, Footer, Menü und Legende																# Version 2.7
$sizes = array(
	'gui.php' => array(
		'margin' => array(
			'width'  => 0,
			'height' => 0
		),
		'header' => array(
			'height' => 50
		),
		'scale_bar' => array(
			'height' => 30
		),
		'lagebezeichnung_bar' => array(
			'height' => 30
		),
		'map_functions_bar' => array(
			'height' => 37
		),
		'footer' => array(
			'height' => 23
		),
		'menue' => array(
			'width'  => 218,				# Version 2.8 (neu ist nur das Komma, der Wert müsste aber gegebenenfalls auch angepasst werden, da die Button-Menüs jetzt breiter sind)
			'hide_width' => 22			# Version 2.8
		),
		'legend' => array(
			'width' => 252,
			'hide_width' => 27
		)
	)
);

# zusätzliche Legende; muss unterhalb von snippets liegen
#define('LEGEND_GRAPHIC_FILE', '');		# Version 2.7

# Höhe und Breite der generierten Legendenbilder für verschiedene Layertypen			# Version 2.8
$legendicon_size = array(
	'width' => array(
		0 => 18,			# Punktlayer
		1 => 18,			# Linienlayer
		2 => 18,			# Flächenlayer
		3 => 18				# Rasterlayer
	),
	'height' => array(
		0 => 18,			# Punktlayer
		1 => 12,			# Linienlayer
		2 => 12,			# Flächenlayer
		3 => 18				# Rasterlayer
	)
);

# login.php
define('LOGIN', 'login.php');																# Version 1.8.0
# Seite zur Fehlerbehandlung, die durch fehlerhafte Layer verursacht werden; unterhalb von /snippets
define('LAYER_ERROR_PAGE', 'layer_error_page.php');					# Version 2.0
# Seite mit der Datenschutzerklärung, die einmalig beim Login angezeigt wird
define('AGREEMENT_MESSAGE', '');					# Version 2.8
# Geschwindigkeit der Warteanimation (normal: 6, 0 = keine Animation)
#define('WAITING_ANIMATION_SPEED', 6);											# Version 2.0			# in Version 2.6 über den Bugfix 2.6.23 gelöscht
# Vorschaubildgröße
define('PREVIEW_IMAGE_WIDTH', 250);													# Version 2.1

### Einstellungen für das Menü
# Da die Bezeichnungen der Menüs frei wählbar sind, muss man hier angegen
# welchen Namen man für welche Aufgabe gewählt hat.
# Hier werden die Werte aus der Spalte name der Tabelle u_menues
# Konstanten zugewiesen um sie später beim Layout verwenden zu können
define('TITLE','Web-GIS kvwmap');             		# Version 1.7.6
define('TITLE_DRUCKEN','Druckausschnittswahl');             # Version 1.6.6
define('TITLE_KARTE','Karte anzeigen');                     # Version 1.6.6
define('TITLE_NOTIZEN','neue Notiz');                       # Version 1.6.6
define('TITLE_HILFE','Hilfe');                  						# Version 1.6.6
define('TITLE_OPTIONEN','Optionen');										# Version 1.7.3
define('TITLE_SCHNELLDRUCK','schnelle Druckausgabe');					# Version 1.7.5
# Schalter für die PopUp-Funktion des Menüs
#define('POPUPMENUE','false');          # true / false 				# Version 2.7 gelöscht

# Position des Wappens (oben/unten/kein)
define('MENU_WAPPEN','oben');          # oben / unten / kein
# Position der Referenzkarte (oben/unten)                   # Version 1.6.4
define('MENU_REFMAP','unten');          # oben / unten      # Version 1.6.4
# Schalter für die Ajax-Funktionalität des Menüs            # Version 1.6.4
#define('AJAX_MENUE','true');          # true / false        # Version 1.6.4   # in Version 2.0 wieder gelöscht
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
define('LAGEBEZEICHNUNGSART', '');						# in Version 1.7.6 wieder eingeführt für Fälle ohne ALK

# Erweiterung der Authentifizierung um die IP Adresse des Nutzers
# Testet ob die IP des anfragenden Clientrechners dem Nutzer zugeordnet ist
define('CHECK_CLIENT_IP',true);                               # Version 1.6.9 Setzt zusätzliche Spalte in Tabelle stelle voraus.

# maximale Länge der Passwörter
define('PASSWORD_MAXLENGTH', 16);		# Version 2.1

# minimale Länge der Passwörter
define('PASSWORD_MINLENGTH', 6);		# Version 2.4

# Prüfung neues Passwort
# Auskommentiert, wenn das Passwort vom Admin auf "unendlichen" Zeitraum vergeben wird
# erste Stelle  0 = Prüft die Stärke des Passworts (3 von 4 Kriterien müssen erfüllt sein) - die weiteren Stellen werden ignoriert
# erste Stelle  1 = Prüft statt Stärke die nachfolgenden Kriterien:
# zweite Stelle 1 = Es müssen Kleinbuchstaben enthalten sein
# dritte Stelle 1 = Es müssen Großbuchstaben enthalten sein
# vierte Stelle 1 = Es müssen Zahlen enthalten sein
# fünfte Stelle 1 = Es müssen Sonderzeichen enthalten sein
define('PASSWORD_CHECK', '01010');															# Version 2.3

# Wenn das kvwmap-Verzeichnis ein git-Repository ist, kann diese Konstante auf den User gesetzt werden, der das Repository angelegt hat.
# Damit der Apache-User dann die git-Befehle als dieser User ausführen kann, muss man als root über den Befehl "visudo" die /etc/sudoers editieren.
# Dort muss dann eine Zeile in dieser Form hinzugefügt werden: 
# www-data        ALL=(fgs) NOPASSWD: /usr/bin/git
# Dann kann man die Aktualität des Quellcodes in der Administrationsoberfläche überprüfen und ihn aktualisieren.
define('GIT_USER', 'gisadmin');																	# Version 2.1

########################## Pfadeinstellungen
# Installationspfad
define('INSTALLPATH','/var/www/');
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
    define('URL','http://localhost/');
  }
}
# Datei mit den Nummerierungsbezirkshöhen
define('NBH_PATH', WWWROOT.APPLVERSION.'tools/UTM33_NBH.lst');			# Version 2.1
define('MAPSERV_CGI_BIN',URL.'cgi-bin/mapserv');
define('LOGPATH',INSTALLPATH.'logs/');
# Shapepath [Pfad zum Shapefileverzeichnis]
define('SHAPEPATH',INSTALLPATH.'data/');
# Custom-Shapepath [Name des Verzeichnisses, in dem die von den Usern hochgeladenen SHPs liegen (muss im SHAPEPATH liegen)]
#define('CUSTOM_SHAPEPATH', 'custom_shps/');							# Version 1.7.4		# in Version 1.11.0 gelöscht
# ein extra Schema in der PG-DB, in der die Tabellen der Nutzer Shapes angelegt werden
define('CUSTOM_SHAPE_SCHEMA', 'custom_shapes');					# Version 1.11.0
define('REFERENCEMAPPATH',SHAPEPATH.'referencemaps/');
define('NACHWEISDOCPATH',SHAPEPATH.'nachweise/');
# Dateiname und Pfad der Festpunkte, mit der die Tabelle der Festpunkte aktualisiert werden soll
#define('PUNKTDATEINAME','festpunkte.csv');										# in Version 2.6 gelöscht
define('PUNKTDATEIPATH',SHAPEPATH.'festpunkte/');
define('PUNKTDATEIARCHIVPATH',PUNKTDATEIPATH.'archiv/');
define('KVZAUSGABEDATEINAME','festpunkte.kvz');
define('KVZKOPF', '# Datenaustauschformat Landkreis Rostock
#KST PKN             VMA  RECHTSWERT   HOCHWERT    HOEHE    GST  VWL  DES  ART
# ');																																									# in Version 2.6 angepasst
define('SKIZZEN_DATEI_TYP','tif');            # Version 1.6.8
# Pfad zu den WLDGE Dateien
define('WLDGEFILEPATH',SHAPEPATH.'alb/');
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
define('RECHERCHEERGEBNIS_PATH',SHAPEPATH.'recherchierte_antraege/');
# Pfad zum Speichern der Kartendruck-Layouts
define('DRUCKRAHMEN_PATH',SHAPEPATH.'druckrahmen/');

# Pfad zu den Funktionen
#define('FKT_PATH',WWWROOT.APPLVERSION.'funktionen/');			# in Version 1.7.3 wieder gelöscht

# Pfad zu den PDF-Generator Klassen
#define('PDFCLASSPATH', '../PDFClass/');																						# in Version 2.6 gelöscht

# 3rdparty Pfade
define('THIRDPARTY_PATH', '../3rdparty/');																	# Version 2.6
define('FONTAWESOME_PATH', THIRDPARTY_PATH . 'font-awesome-4.6.3/');				# Version 2.6
define('JQUERY_PATH', THIRDPARTY_PATH . 'jQuery-1.12.0/');									# Version 2.6
define('BOOTSTRAP_PATH', THIRDPARTY_PATH . 'bootstrap-3.3.6/');							# Version 2.6
define('BOOTSTRAPTABLE_PATH', THIRDPARTY_PATH . 'bootstrap-table-1.11.0/');	# Version 2.6
define('PROJ4JS_PATH', THIRDPARTY_PATH . 'proj4js-2.4.3/');									# Version 2.8

# Bin-Pfad der Postgres-tools (shp2pgsql, pgsql2shp)
define('POSTGRESBINPATH', '/usr/bin/');         # Version 1.6.4

# Bin-Pfad der OGR-tools (ogr2ogr, ogrinfo)
define('OGR_BINPATH', '/usr/bin/');					# Version 1.7.4
# Bin-Pfad der OGR-tools (ogr2ogr, ogrinfo) im GDAL-Container
define('OGR_BINPATH_GDAL', '/usr/local/gdal/bin/');			# Version 2.9

# Pfad zum Zip-Programm (unter Linux: 'zip -j', unter Windows z.B. 'c:/programme/Zip/bin/zip.exe')
define('ZIP_PATH', 'zip');													# Version 1.7.3  hier wurde das ' -j' angehängt		# Version 2.8 das ' -j' wurde wieder entfernt

# EPSG-Code dem die Koordinaten der Flurstücke zugeordnet werden sollen in den Tabellen
# alb_flurstuecke und alb_x_flurstuecke wenn man postgres verwendet
# die Geometriespalte muß auch mit dieser EPSG Nummer angelegt sein.
define('EPSGCODE','2398'); # Krassowski, Pulkowo 42, Gauß Krüger 3° Streifen 4 (12°)
#define('EPSGCODE','2399'); # Krassowski, Pulkowo 42, Gauß Krüger 3° Streifen 5 (15°)

# EPSG-Code der ALKIS-Daten
define('EPSGCODE_ALKIS','25833');

# DHK-Call-Schnittstelle
define('DHK_CALL_URL', 'http://dhkserver/call?form=login');						# Version 2.1
define('DHK_CALL_USER', '12345');																			# Version 2.1
define('DHK_CALL_PASSWORD', '6789');																	# Version 2.1
define('DHK_CALL_ANTRAGSNUMMER', 'BWAPK_0000002');										# Version 2.4
define('DHK_CALL_PROFILKENNUNG', 'mvaaa');														# Version 2.4

# Parameter für die Strecken- und Flächenreduktion
define('EARTH_RADIUS', 6384000);																										# Version 2.1
#define('M_QUASIGEOID', 38);																													# Version 2.1			in Version 2.6 über Bugfix 2.6.61 gelöscht

# auswählbare Treffermengen
$selectable_limits = array(10, 25, 50, 100, 200);			# Version 2.4

# auswählbare Maßstäbe
$selectable_scales = array(500, 1000, 2500, 5000, 7500, 10000, 25000, 50000, 100000, 250000, 500000, 1000000);		# Version 2.2

# Unterstützte SRIDs, nur diese stehen zur Auswahl bei der Stellenwahl
$supportedSRIDs = array(4326,2397,2398,2399,31466,31467,31468,31469,32648,25832,25833,35833,32633,325833,15833,900913,28992);                    # Version 1.6.8

# Unterstützte Sprachen, nur diese stehen zur Auswahl bei der Stellenwahl ('german', 'low-german', 'english', 'polish', 'vietnamese')
$supportedLanguages = array('german');															# Version 2.0

# Unterstützte Exportformate
$supportedExportFormats = array('Shape', 'GML', 'KML', 'GeoJSON', 'UKO', 'OVL', 'CSV');																										# Version 2.7

# Hier kann festgelegt werden, ob in den Optionen das Feld "Zeitpunkt für historische Daten" erscheinen soll, oder nicht (true/false)
#define('HIST_TIMESTAMP', true);								# Version 2.3 		# in Version 2.4 wieder gelöscht (ist jetzt stellenbezogen)

# Name der Stopwortdatei
define('STOPWORDFILE',SHAPEPATH.'gazetteer/top10000de.txt');

# Imagepath
define('IMAGEPATH',INSTALLPATH.'tmp/');

# E-Mail Einstellungen
# Methode zum Versenden von E-Mails. Mögliche Optionen:
# sendmail: E-Mails werden direkt mit sendmail versendet. (default)
# sendEmail async: E-Mails werden erst in einem temporären Verzeichnis MAILQUEUEPATH
# 	abgelegt und können später durch das Script tools/sendEmailAsync.sh
# 	versendet werden. Dort muss auch MAILQUEUEPATH eingestellt werden.
define('MAILMETHOD', 'sendEmail async');						# Version 2.4
# SMTP-Server, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
define('MAILSMTPSERVER', 'smtp.p4.net');						# Version 2.4
# SMTP-Port, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
define('MAILSMTPPORT', 25);													# Version 2.4
# Verzeichnis für die JSON-Dateien mit denzu versendenen E-Mails.
# Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
define('MAILQUEUEPATH', '/var/www/logs/kvwmap/mail_queue/');			# Version 2.4
define('MAILARCHIVPATH', '/var/www/logs/kvwmap/mail_archiv/');			# Version 2.4

# Pfad für selbst gemachte Bilder
define('CUSTOM_IMAGE_PATH',SHAPEPATH.'bilder/');                # Version 1.6.9
#Cachespeicherort
define('CACHEPATH',INSTALLPATH.'cache/');                             # Version 1.6.8
#Cachezeit Nach welcher Zeit in Stunden sollen gecachte Dateien aktualisiert werden
#wird derzeit noch nicht berücksichtigt
define('CACHETIME',168);                                          # Version 1.6.8
# relative Pfadangabe zum Webverzeichnis mit temprären Dateien
define('TEMPPATH_REL','../tmp/');
#Imageurl
define('IMAGEURL','/tmp/');
# Symbolset
define('SYMBOLSET',WWWROOT.APPLVERSION.'symbols/symbole.sym');
# Fontset
define('FONTSET',WWWROOT.APPLVERSION.'fonts/fonts.txt');
# Graphics
define ('GRAPHICSPATH','graphics/');
# Wappen
define('WAPPENPATH',GRAPHICSPATH.'wappen/');
# Wasserzeichenbild für Ausdrucke
#define('WASSERZEICHEN',WAPPENPATH.'wappen_wz.jpg');			# in Version 2.4 gelöscht
# Layouts
define ('LAYOUTPATH',WWWROOT.APPLVERSION.'layouts/');
define ('SNIPPETS',LAYOUTPATH.'snippets/');
define('CLASSPATH', WWWROOT.APPLVERSION.'class/');
define('PLUGINS',WWWROOT.APPLVERSION.'plugins/');		# Version 1.11.0
define('TEMPTABLEPREFIX','x_');
# Default Templates für Sachdatenanzeige
define('DEFAULTTEMPLATE',' ');
define('DEFAULTHEADER',' ');
define('DEFAULTFOOTER',' ');
# Erlaubte maximale Länge der Stammnummer in der Fachschale Nachweisverwaltung
#define('STAMMNUMMERMAXLENGTH',8);																											# in Version 1.11.0 gelöscht
# Erlaubte maximale Länge der Rissnummer in der Fachschale Nachweisverwaltung
define('RISSNUMMERMAXLENGTH',8);																													# Version 1.11.0
# Erlaubte maximale Länge der Antragsnummer in der Fachschale Nachweisverwaltung
define('ANTRAGSNUMMERMAXLENGTH',8);																											# Version 1.11.0
# maximale Anzahl der in einer Sachdatenabfrage zurückgelieferten Zeilen.
define('MAXQUERYROWS',10);

# Erlaubte maximale Länge der Blattnummer in der Fachschale Nachweisverwaltung
define('BLATTNUMMERMAXLENGTH',4);                         # Version 1.6.7

# das primäre Ordnungskriterium der Nachweisverwaltung: rissnummer/stammnr
define('NACHWEIS_PRIMARY_ATTRIBUTE', 'stammnr');			# Version 1.10.0

# das zusätzliche Ordnungskriterium der Nachweisverwaltung (kann bei eindeutigem primärem leer gelassen werden): fortfuehrung
define('NACHWEIS_SECONDARY_ATTRIBUTE', '');			# Version 1.11.0

$nachweis_unique_attributes = array('gemarkung', 'flur', NACHWEIS_PRIMARY_ATTRIBUTE, NACHWEIS_SECONDARY_ATTRIBUTE, 'art', 'blattnr');		# Version 2.5

# PostgreSQL Server Version                         # Version 1.6.4
define('POSTGRESVERSION', '804');                   # Version 1.6.4   (800 == 8.0)

# MySQLSQL Server Version                         # Version 1.6.4
define('MYSQLVERSION', '500');                   # Version 1.6.4   (4.1.0 == 410)

# Mapserver Version                             # Version 1.6.8
define('MAPSERVERVERSION', '620');              # Version 1.6.8     (5.0.2 == 502)

# PHP-Version
define('PHPVERSION', '450');										# Version 1.7.1  (5.2.0 == 520)

# Schalter für die mobile Variante
define('MOBILE', 'false');                                  # Version 1.6.7 (noch in Entwicklung)

# Pfad zur GPS-Logdatei                         # Version 1.7.0
define('GPSPATH', SHAPEPATH.'gpsulog.txt');
#define('GPSPATH', 'http://localhost:8081/');
#define('GPSPATH', 'http://www.gdi-service.de/gps_position_nmea_gga.txt');

# Synchronisationsverzeichnis                         # Version 1.7.0
define('SYNC_PATH', SHAPEPATH.'synchro/');

# Faktor für die Einstellung der Druckqualität (MAPFACTOR * 72 dpi)     # Version 1.6.0
define('MAPFACTOR', 3);                                                # Version 1.6.0

# Standarddrucklayout für den schnellen Kartendruck						# Version 1.7.4
define('DEFAULT_DRUCKRAHMEN_ID', 42);													# Version 1.7.4

# Zeigt an, ob Image Magick und Ghostscript installiert sind oder nicht (wird für neue Druckvorschau benötigt)
#define('IMAGEMAGICK', 'true');                        # Version 1.6.3			# in Version 2.9 gelöscht

# Pfad zum Imagemagick convert
define('IMAGEMAGICKPATH', '/usr/bin/');                        # Version 1.7.3

# Definiert, ob Flächen- oder Punktförmige Bodenrichtwerte erfasst werden ('punkt' oder 'flaeche')
#define('BODENRICHTWERTTYP', 'flaeche');           # Version 1.6.3			# Version 1.7.3: wird nicht mehr verwendet, kann gelöscht werden

# Pfad zum Ordner für Datei-Uploads
define('UPLOADPATH',SHAPEPATH.'upload/');       # Version 1.6.7

# maximale Datenmenge in MB, die beim Datenimport hochgeladen werden darf
define('MAXUPLOADSIZE', 200);										# Version 2.9

# Definiert, ob die Festpunkte in 2 Streifen liegen oder nicht
#define('FESTPUNKTE_2_STREIFEN', 'true');  #true/false   # Version 1.6.7			# in Version 2.6 gelöscht

# Legt fest, ob die Hausnummernzusätze groß oder klein dargestellt werden
define('HAUSNUMMER_TYPE', 'LOWER');     # UPPER/LOWER   # Version 1.6.8

# Definiert ob die einzulesende Festpunktedatei auf doppelte Punktkennzeichen getestet werden soll, oder nicht    # Version 1.6.8
#define('CHECKPUNKTDATEI', 'true');      # true/false                                                                # Version 1.6.8			# in Version 2.6 gelöscht

# Minmale Maßstabszahl
define('MINSCALE', 100);                                                        # Version 1.7.0

# Maßstab ab dem bei einem Koordinatensprung auch gezoomt wird
define('COORD_ZOOM_SCALE', 50000);																							# Version 2.4

# Puffer in der Einheit (ZOOMUNIT) der beim Zoom auf ein Objekt hinzugegeben wird
define('ZOOMBUFFER', 100);                                                 # Version 2.1

# Einheit des Puffer der beim Zoom auf ein Objekt hinzugegeben wird
# 'meter' oder 'scale'
define('ZOOMUNIT', 'meter');                                               # Version 2.5

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
define("LAYERNAME_FLURSTUECKE",'Flurstücke Demo');
define("LAYERNAME_GEBAEUDE",'Gebaeude');
define("LAYERNAME_NUTZUNGEN",'Nutzung');
define("LAYERNAME_AUSGESTALTUNGEN",'Ausgestaltung');
# Namen für Layer mit administrativen Grenzen
define("LAYERNAME_GEMARKUNGEN",'Gemeinde');
define("LAYERNAME_GEMEINDEN",'Gemarkung');
define("LAYERNAME_FLUR",'Flur');
define("LAYERNAME_BODENRICHTWERTE",'BORIS');	# Version 1.7.3
define("LAYER_ID_ADRESSAENDERUNGEN", '162');  # Version 1.6.7		# in Version 2.1 gelöscht
define("LAYER_ID_ADRESSAENDERUNGEN_PERSON", '827');  						# Version 2.1
define("LAYER_ID_ADRESSAENDERUNGEN_ANSCHRIFT", '162');  				# Version 2.1
define("LAYER_IDS_DOP", '79,80');							# Version 1.8.0
define("LAYER_ID_JAGDBEZIRKE", '432');				# Version 1.10.0
define("LAYER_ID_NACHWEISE", 786);						# Version 2.6
define("LAYER_ID_SCHNELLSPRUNG", 749);				# Version 2.0
$quicksearch_layer_ids = array(752);					# Version 2.0

######################### Dateieinstellungen
# Datei in der das MapFile als Dokumentation zur Kartenausgabe geschrieben wird
define("DEFAULTMAPFILE",SHAPEPATH.'mapfiles/defaultmapfile.map');
# Wenn SAVEMAPFILE leer ist, wird sie nicht gespeichert.
# Achtung, wenn die cgi-bin/mapserv ohne Authentifizierung und der Pfad zu save_mapfile.map bekannt ist, kann jeder die Karten des letzten Aufrufs in kvwmap über mapserv?map=<pfad zu save_map.map abfragen. Und wenn wfs zugelassen ist auch die Sachdaten dazu runterladen. Diese Konstante sollte nur zu debug-Zwecken eingeschaltet bleiben.
define("SAVEMAPFILE",LOGPATH.'save_mapfile.map');                                # Version 1.11.0 
define("REFMAPFILE",SHAPEPATH.'mapfiles/refmapfile.map');
# Ort der Datei, in der die Meldungen beim Debugen geschrieben werden
define('DEBUGFILE',LOGPATH.$_SESSION['login_name'].'_debug.htm');			# in Version 2.8 gibt es die Konstante VERSION nicht mehr
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
# Log-Datei zur Speicherung der Login Vorgänge
define('LOGFILE_LOGIN', LOGPATH . 'login_fail.log'); 							# Version 2.9.0
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

# Adminstellen
$admin_stellen = array(3);						# Version 2.2

# Gast-Stellen
#define('GAST_STELLE', 35);						# Version 1.7.5 			# in Version 1.8.0 geloescht
$gast_stellen = array(35);						# Version 1.8.0

#### Einstellungen zur Speicherung der Zugriffe
define('LOG_CONSUME_ACTIVITY',1);

# Legt fest, ob die Rollenlayer beim Login eines Nutzers gelöscht werden sollen   # Version 1.6.5
define('DELETE_ROLLENLAYER', 'true');   # true / false                            # Version 1.6.5

# Definiert, ob das aktuelle Kartenbild separat angezeigt werden darf oder nicht
define('SHOW_MAP_IMAGE', 'true');       # true / false                            # Version 1.6.7

############################ kvwmap-plugins #################		# Version 1.11.0
#																																# Version 1.11.0
$kvwmap_plugins = array();																			# Version 1.11.0
#$kvwmap_plugins[] = 'alkis';																		# Version 2.8
#$kvwmap_plugins[] = 'anliegerbeitraege';												# Version 2.0.0
#$kvwmap_plugins[] = 'bauleitplanung';													# Version 1.11.0
#$kvwmap_plugins[] = 'baumfaellantrag';													# Version 2.0.0
#$kvwmap_plugins[] = 'bevoelkerung';														# Version 1.11.0
#$kvwmap_plugins[] = 'bodenrichtwerte';													# Version 2.0.0
#$kvwmap_plugins[] = 'fortfuehrungslisten';											# Version 2.6.0
#$kvwmap_plugins[] = 'geodoc';																	# Version 2.0.0
#$kvwmap_plugins[] = 'gewaesser';																# Version 1.11.0
#$kvwmap_plugins[] = 'jagdkataster';														# Version 2.0.0
#$kvwmap_plugins[] = 'kolibri';																	# Version 2.8
#$kvwmap_plugins[] = 'metadata';																# Version 2.8
#$kvwmap_plugins[] = 'mobile';																	# Version 2.7
#$kvwmap_plugins[] = 'nachweisverwaltung';											# Version 2.0.0
#$kvwmap_plugins[] = 'probaug';																	# Version 2.0.0
#$kvwmap_plugins[] = 'ukos';																		# Version 2.8.0
#$kvwmap_plugins[] = 'wasserrecht';															# Version 2.8.0
#$kvwmap_plugins[] = 'xplankonverter';													# Version 2.8.0
#############################################################		# Version 1.11.0

# Festlegung von Fehlermeldungen und Hinweisen
define ('INFO1','Prüfen Sie ob Ihr Datenbankmodell aktuell ist.');

# Character Set der MySQL-Datenbank
define('MYSQL_CHARSET','UTF8');													# Version 1.7.6		# in Version 1.11.0 auf UTF8 gesetzt
define('POSTGRES_CHARSET','UTF8');											# Version 1.8.0		# in Version 1.11.0 auf UTF8 gesetzt


################################ Datenbankangaben setzen ######################		
# Datenbank für die Nutzerdaten (mysql)
define('MYSQL_HOST', 'localhost');																	# Version 2.0
define('MYSQL_USER', '');																						# Version 2.0
define('MYSQL_PASSWORD', '');																				# Version 2.0
define('MYSQL_DBNAME', $dbname);																		# Version 2.0
define('MYSQL_ROOT_PASSWORD', getenv('MYSQL_ENV_MYSQL_ROOT_PASSWORD'));
define('MYSQL_HOSTS_ALLOWED', '172.17.%');

// $userDb=new database();																				# in Version 2.0 gelöscht
// $userDb->host='localhost';																			# in Version 2.0 gelöscht
// $userDb->user='';																							# in Version 2.0 gelöscht
// $userDb->passwd='';																						# in Version 2.0 gelöscht
// $userDb->dbName=$dbname;																				# in Version 2.0 gelöscht

//$GISdb = $userDb; 													# Version 1.7.6			# in Version 2.0 gelöscht

# Datenbank mit den Geometrieobjekten (PostgreSQL mit PostGIS Aufsatz)
define('POSTGRES_HOST', 'localhost');																# Version 2.0
define('POSTGRES_USER', '');																				# Version 2.0
define('POSTGRES_PASSWORD', '');																		# Version 2.0
define('POSTGRES_DBNAME', $pgdbname);																# Version 2.0
#define('POSTGRES_ROOT_PASSWORD', getenv('PGSQL_ROOT_PASSWORD'));    # in Version 2.7 gelöscht

// if ($pgdbname!='') {																													# in Version 2.0 gelöscht
	// if(in_array($_REQUEST['go'], $fast_loading_cases)){		# Version 1.7.6			# in Version 2.0 gelöscht
		// $PostGISdb=new pgdatabase_core();										# Version 1.7.6			# in Version 2.0 gelöscht
	// }																											# Version 1.7.6			# in Version 2.0 gelöscht
	// else{																									# Version 1.7.6			# in Version 2.0 gelöscht
  	// $PostGISdb=new pgdatabase();													# Version 1.7.6			# in Version 2.0 gelöscht
	// }																											# Version 1.7.6			# in Version 2.0 gelöscht
  // $PostGISdb->host='localhost';																							# in Version 2.0 gelöscht
  // $PostGISdb->user='';																												# in Version 2.0 gelöscht
  // $PostGISdb->passwd='';																											# in Version 2.0 gelöscht
  // $PostGISdb->dbName=$pgdbname;																							# in Version 2.0 gelöscht
// }																																						# in Version 2.0 gelöscht

###########################################################################

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
define("OWS_CONTACTELECTRONICMAILADDRESS","peter.korduan@gdi-service.de");

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


?>
