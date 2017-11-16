#---------
Installationsanweisung für kvwmap und die dafür benötigten Komponenten
für das Betriebssystem Windows mit Apache, UMN-MapServer, MySQL und PostgreSQL

Peter Korduan, 2006-12-05


#---------
MS4W:
MS4W heißt MapServer für Windows und genau das ist er auch. Das Paket kommt aber
auch gleich mit Apache, PHP und den ganzen Utilities, die man für den sinnvollen
MapServerbetrieb braucht. PHP ist so kompiliert, dass man es mit PostgreSQL, MySQL
und vielen anderen nützlichen Dingen ausführen kann. MapServer läuft mit
phpMapscript und unterstützt alles was man so braucht.
MapServer version 4.10.0 OUTPUT=GIF OUTPUT=PNG OUTPUT=JPEG
OUTPUT=WBMP OUTPUT=PDF OUTPUT=SWF OUTPUT=SVG SUPPORTS=PROJ
SUPPORTS=FREETYPE SUPPORTS=WMS_SERVER SUPPORTS=WMS_CLIENT
SUPPORTS=WFS_SERVER SUPPORTS=WFS_CLIENT SUPPORTS=WCS_SERVER
SUPPORTS=SOS_SERVER SUPPORTS=THREADS SUPPORTS=GEOS INPUT=JPEG
INPUT=POSTGIS INPUT=OGR INPUT=GDAL INPUT=SHAPEFILE DEBUG=MSDEBUG
  - Download der aktuellen Version von
    http://www.maptools.org/ms4w/index.phtml?page=downloads.html
  - Auspacken nach C:\ (dauert ein wenig)
  - Lese die Datei README-INSTALL.txt und folge den Installationsanweisungen
  - Nach Installation zum Testen mal http://localhost aufrufen

#---------
MySQL:
Das Datenbankmanagementsystem MySQL wird von kvwmap für
- die Einstellungen zu den Benutzern des Systems
- der Darstellung der Graphischen Benutzeroberfläche
- und der Konfiguration der Karten (was sonst im Mapfile steht)
verwendet.
  - Download der Windows (x86) ZIP/Setup.EXE von
    http://dev.mysql.com/downloads/mysql/5.0.html
  - Auspacken
  - Sorge dafür, dass durch die Firewall nicht der Port 3306 blockiert ist
    und dass kein MySQL-Dienst auch nicht einer aus XAMPP bereits läuft.
  - Setup.EXE starten
  - Wähle typische Installation
  - Registrierung kann übergangen werden
  - Wähle Standardkonfiguration
  - Installiere Dienst, der Automatisch startet und lasse den Pfad
    des bin-Verzeichnisses in Path Umgebungsvariable eintragen
  - Wähle "Enable root access from remote maschines" nur, wenn es gebraucht wird.
  - Teste ob mysql läuft durch ausführen von "mysql -u root -p"
    Nach Eingabe des Passwortes sollte die mysql Eingabeaufforderung erscheinen
  - Gebe "use mysql" <Enter> und "show tables" Die Tabellen der mysql Datenbank
    müssen angezeigt werden

#---------
PostgreSQL:
PostgresSQL wird von kvwmap für die Speicherung von Geodaten und Sachdaten
(z.B. ALK u. ALB) und geometrische Operationen benötigt.
Der Windowsinstaller installiert auch die PostGIS Erweiterung und den graphischen 
Datenbankclient pgAdminIII, wenn man das auswählt.
  - Zip-Datei von http://www.postgresql.org/ftp/binary/v8.2.0/win32/
    runterladen (jeweils aktuelle Version)
  - Auspacken
  - Installer ausführen
  - Bei Installationsoptionen PostGIS, ODBC und pgAdminIII auswählen
  - Betriebssystembenutzer postgres automatisch anlegen (zum Starten des Dienstes)
  - Benutzer der Datenbank anlegen mit dem Namen: kvwmap und Password: kvwmap
  - Passwort später in phAdminIII ändern, aber dann auch in der config.php von kvwmap
  - template1 mit PostGIS Support anlegen
  
#---------  
kvwmap:
kvwmap enthält die php-Scripte und sonstigen Dateien, die für den Betrieb der
Internet-GIS Anwendung funktional notwendig sind und einige Beispieldaten.
Verzeichnisstruktur:
ms4w
|-----Apache
|	|------var
|	|	|-----data
|	| 		|-alb
|	|		|-alk
|	|		|-druckrahmen
|	|		|-festpunkte
|	|		|-nachweise
|	|		|-recherchierte_antraege
|	|		|-referencemaps
|	|		|-test
|	|-wms
|
|-----apps
|	|-kvwmap-Version
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
|       |PDFClass
|-----httpd.d

#--------------
PDFClass
PDFClass von http://www.ros.co.nz/pdf/
Das für die PDF Ausgabe in kvwmap benötigte PDFClass ist mit dem PDFClass-ms4w Packet
zu installieren. Das Packet hat folgende Struktur
ms4w
|------apps
	|-PDFClass

Demo-Datensatz im Shape-Format ist in Arbeit