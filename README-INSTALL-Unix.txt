Installationsanweisung unter Unix-Betriebssystem

Hier wird eine Dokumentation der Installationsschritte für ein
Linux-Betriebssystem erstellt.

Weiter unten finden Sie Infos zur Installation in fgs

---------------------------------------------------------------
Update einer bestehenden Version
- Runterladen der letzten Version von kvwmap von: https://sourceforge.net/project/showfiles.php?group_id=137679
- Auspacken im htdocs Verzeichnis des Webservers in einem neuen Verzeichnis entsprechend der Versionsnummer
  z.B. kvwmap_15
- Anpassen der config.php (vorhandene sichern, überschreiben durch eigene und ergänzen neuer Konstanten aus der neuen Datei)
- Kopieren der Datenbanken mit neuem Namen entsprechend der neuen Versionsnummer z.B. kvwmapdb15, kvwmapsp15
- Anpassung der condig.php mit neuen Datenbanknamen
- Ausführen der Änderungen an den Datenbanken entsprechend der Angaben in den Dateien mysql_update.php und postgres_update.php
  aus den Verzeichnissen layout/sql_dumps
- Kopieren der eigenen Grafiken und Snippets in die Verzeichnisse layouts/snippets und graphics und graphics/wappen
  darauf achten, dass man keine vorhandenen überschreibt, ggf. eigene umbenennen und in config.php und Datenbank eigene Einträge anpassen.

----------------------------------------------------
Installation der für kvwmap notwendigen Komponenten

Laden Sie sich folgende Pakete aus dem Internet, wenn sie noch nicht auf dem Rechner sind.
Es muss sich um Quellcode Packete handeln, die noch kompiliert werden müssen.

XAMPP (Apache, PHP, MySQL):
  XAMPP Linux 1.4.14 von
  http://www.apachefriends.org/de/xampp-linux.html

UMN-MapServer:
  mapserver-4.6.0.tar.gz: source code von http://mapserver.gis.umn.edu/dload.html
  gd: a graphics library for fast image creation. Version 1.2 with GIF support is included in the 3.4 and earlier MapServer source distributions. 
  FreeType 2: TrueType font engine. 
  Proj.4: cartographic projection library from the USGS. The mapserver uses PROJ.4 for it's projection support. It's up to the user to get the PROJ.4 parameters correct. 
  Links für den Download finden sich auch unter: http://mapserver.gis.umn.edu/dload.html
  gdal (inclusieve Suport für tif, jpeg, png): gdal-1.2.6.tar.gz von http://www.gdal.org/dl
  geos: geos-2.1.3.tar.bz2 von http://geos.refractions.net
  curl-lib: curl-7.14.0.tar.gz von http://curl.mirror.at.stealer.net/download.html
  
PostgreSQL:
  PostGIS postgis-1.0.2.tar.gz von
  http://postgis.refractions.net/download

kvwmap:
  PDFClass  von http://www.ros.co.nz/pdf/
  Demo-Datensatz im Shape-Format in Arbeit
  
Alle Pakete entpacken nach /usr/local

Pakete entsprechend der Installationsanweisungen installieren und die Bibliotheken, die nach make install unter
/usr/local/lib abgelegt werden nach /usr/lib kopieren, oder dort entsprechende Links setzen.

XAMPP testen
  - im Browser localhost eingeben
  - von PHP5 nach PHP4 umschalten

Testen von mapserver mit mapserv -v

Testen von mapserver im cgi-bin vom WebServer

phpmapscript_46.so in das extension Verzeichnis von php kopieren.

Testen von php und php_MapScript mit phpinfo() Befehl.

--------------------------------------------------------------------------------------------
Installation von kvwmap:
- Entpacken von kvwmap_Version in das htdocs Verzeichnis des Web-Servers -> <kvwmap-home>
- Anpassen der Werte in config.php
- Anlegen einer Leeren Datenbank kvwmapdb_Versionsnummer z.B. kvwmapdb144 in phpMyAdmin
- Ausführen des mysql-Skriptes <kvwmap-home>/layouts/sql_dumps/mysql_instll.sql im SQL-Fenster von phpMyAdmin
- Ausführen des Skriptes <kvwmap-home>/layouts/sql_dump/kvwmap_install_pg auf der Komando Zeile
  Dabei wir ein neuer User, die Datenbank und die benötigten Tabellen in postgres angelegt.
  Der Aufruf sieht so aus: kvwmap_install_pg alkpostgistemplate kvwmap kvwmapsp_Version
  Nähere Infos im Skript oder nach Aufruf von kvwmap_install_pg ohne Argumente. 
- Alternativ kann auch das pgsql-Skript <kvwmap-home>/layouts/sql_dumps/postgis_install.sql in einem
  SQL-Client wie pgAdminIII ausgeführt werden
- mysql_install_help.sql enthält nützliche Statements zum Einrichten eines Projektes

- Einrichtung eines Zugriffsschutzes auf das Verzeichnis kvwmap mit Apache Konfigurationsdatein  
#------------------
Bei der Installation in fgs braucht das kvwmap-fgs Packet nur in das fgs-home Verzeichnis kopiert werden
kvwmap-<version> enthält die php-Scripte und sonstigen Dateien, die für den Betrieb der
Internet-GIS Anwendung funktional notwendig sind und einige Beispieldaten.
Verzeichnisstruktur:
fgs
|-----apps
|	|-kvwmap-<version>
|	|	|-class
|	|	|-conf
|	|	|-----fonts
|	|	|	|-custom
|	|	|-funktionen
|	|	|----graphics
|	|	|	|-custom
|	|	|	|-wappen
|	|	|-help
|	|	|----layouts
|	|	|	|-snippets
|	|	|	|-sql_dumps
|	|	|-----symbols
|	|		|-custom
|
|-----www
	|-conf
	|-conf.d
	|------var
	|	|-----data
	|		|-alb
	|		|-alk
	|		|-druckrahmen
	|		|-festpunkte
	|		|-nachweise
	|		|-recherchierte_antraege
	|		|-referencemaps
	|		|-test
	|-wms
Die Apacheeinstellungen zu kvwmap finden sich in conf.d in der Datei:
httpd_kvwmap-<version>.conf
conf enthält eine Benutzerdatei htpasswd.txt, die mit dem Programm htpasswd
erweitert werden kann.
Das für die PDF Ausgabe in kvwmap benötigte PDFClass ist mit dem PDFClass-fgs Packet
zu installieren. Das Packet hat folgende Struktur
fgs
|------apps
	|-PDFClass