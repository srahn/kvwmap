<?

$constants = array (
  'HEADER' => 
  array (
    'name' => 'HEADER',
    'value' => 'header.php',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'FOOTER' => 
  array (
    'name' => 'FOOTER',
    'value' => 'footer.php',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'LOGIN' => 
  array (
    'name' => 'LOGIN',
    'value' => 'login.php',
    'prefix' => '',
    'type' => 'string',
    'description' => 'login.php
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'LAYER_ERROR_PAGE' => 
  array (
    'name' => 'LAYER_ERROR_PAGE',
    'value' => 'layer_error_page.php',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Seite zur Fehlerbehandlung, die durch fehlerhafte Layer verursacht werden; unterhalb von /snippets
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'AGREEMENT_MESSAGE' => 
  array (
    'name' => 'AGREEMENT_MESSAGE',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Seite mit der Datenschutzerklärung, die einmalig beim Login angezeigt wird
z.B. custom/ds_gvo.htm',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'CUSTOM_STYLE' => 
  array (
    'name' => 'CUSTOM_STYLE',
    'value' => 'custom.css',
    'prefix' => '',
    'type' => 'string',
    'description' => 'hier kann eine eigene css-Datei angegeben werden
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'ZOOM2COORD_STYLE_ID' => 
  array (
    'name' => 'ZOOM2COORD_STYLE_ID',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'hier können eigene Styles für den Koordinatenzoom und Punktzoom definiert werden
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'ZOOM2POINT_STYLE_ID' => 
  array (
    'name' => 'ZOOM2POINT_STYLE_ID',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'GLEVIEW' => 
  array (
    'name' => 'GLEVIEW',
    'value' => '2',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Schalter für eine zeilen- oder spaltenweise Darstellung der Attribute im generischen Layereditor  # Version 1.6.5
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'sizes' => 
  array (
    'name' => 'sizes',
    'value' => '{
    "gui.php": {
        "margin": {
            "width": 0,
            "height": 0
        },
        "header": {
            "height": 50
        },
        "scale_bar": {
            "height": 30
        },
        "lagebezeichnung_bar": {
            "height": 30
        },
        "map_functions_bar": {
            "height": 37
        },
        "footer": {
            "height": 23
        },
        "menue": {
            "width": 218,
            "hide_width": 22
        },
        "legend": {
            "width": 252,
            "hide_width": 27
        }
    }
}',
    'prefix' => '',
    'type' => 'array',
    'description' => 'Höhen und Breiten von Browser, Rand, Header, Footer, Menü und Legende																# Version 2.7
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'LEGEND_GRAPHIC_FILE' => 
  array (
    'name' => 'LEGEND_GRAPHIC_FILE',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'zusätzliche Legende; muss unterhalb von snippets liegen
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'legendicon_size' => 
  array (
    'name' => 'legendicon_size',
    'value' => '{
    "width": [
        18,
        18,
        18,
        18
    ],
    "height": [
        18,
        12,
        12,
        18
    ]
}',
    'prefix' => '',
    'type' => 'array',
    'description' => 'Höhe und Breite der generierten Legendenbilder für verschiedene Layertypen
-> Punktlayer
-> Linienlayer
-> Flächenlayer
-> Rasterlayer
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'PREVIEW_IMAGE_WIDTH' => 
  array (
    'name' => 'PREVIEW_IMAGE_WIDTH',
    'value' => '250',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Vorschaubildgröße
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'TITLE' => 
  array (
    'name' => 'TITLE',
    'value' => 'kvwmap Entwicklungsserver',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Titel, welcher im Browser angezeigt wird
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'MENU_WAPPEN' => 
  array (
    'name' => 'MENU_WAPPEN',
    'value' => 'kein',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Position des Wappens (oben/unten/kein)
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'MENU_REFMAP' => 
  array (
    'name' => 'MENU_REFMAP',
    'value' => 'unten',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Position der Referenzkarte (oben/unten)                   # Version 1.6.4
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'BG_TR' => 
  array (
    'name' => 'BG_TR',
    'value' => 'lightsteelblue',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Hintergrundfarbe Zeile bei Listen
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'BG_MENUETOP' => 
  array (
    'name' => 'BG_MENUETOP',
    'value' => '#DAE4EC',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Hintergrundfarbe Top-Menüzeilen
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'BG_MENUESUB' => 
  array (
    'name' => 'BG_MENUESUB',
    'value' => '#EDEFEF',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Hintergrundfarbe Sub-Menüzeilen
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'BG_DEFAULT' => 
  array (
    'name' => 'BG_DEFAULT',
    'value' => 'lightsteelblue',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Hintergrundfarbe (Kopf-/Fusszeile)
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'BG_FORM' => 
  array (
    'name' => 'BG_FORM',
    'value' => 'lightsteelblue',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Hintergrundfarbe (Eingabeformulare)
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'BG_FORMFAIL' => 
  array (
    'name' => 'BG_FORMFAIL',
    'value' => 'lightpink',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Hintergrundfarbe (Formularfehler)
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'BG_GLEHEADER' => 
  array (
    'name' => 'BG_GLEHEADER',
    'value' => 'lightsteelblue',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Hintergrundfarbe GLE Datensatzheader
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'TXT_GLEHEADER' => 
  array (
    'name' => 'TXT_GLEHEADER',
    'value' => '#000000',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Schriftfarbe GLE Datensatzheader
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'BG_GLEATTRIBUTE' => 
  array (
    'name' => 'BG_GLEATTRIBUTE',
    'value' => '#DAE4EC',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Hintergrundfarbe GLE Attributnamen
',
    'group' => 'Layout',
    'plugin' => '',
    'saved' => 0,
  ),
  'POSTGRESVERSION' => 
  array (
    'name' => 'POSTGRESVERSION',
    'value' => '940',
    'prefix' => '',
    'type' => 'string',
    'description' => 'PostgreSQL Server Version                         # Version 1.6.4
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'MYSQLVERSION' => 
  array (
    'name' => 'MYSQLVERSION',
    'value' => '550',
    'prefix' => '',
    'type' => 'string',
    'description' => 'MySQLSQL Server Version                         # Version 1.6.4
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAPSERVERVERSION' => 
  array (
    'name' => 'MAPSERVERVERSION',
    'value' => '641',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Mapserver Version                             # Version 1.6.8
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'PHPVERSION' => 
  array (
    'name' => 'PHPVERSION',
    'value' => '562',
    'prefix' => '',
    'type' => 'string',
    'description' => 'PHP-Version
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'MYSQL_CHARSET' => 
  array (
    'name' => 'MYSQL_CHARSET',
    'value' => 'UTF8',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Character Set der MySQL-Datenbank
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'POSTGRES_CHARSET' => 
  array (
    'name' => 'POSTGRES_CHARSET',
    'value' => 'UTF8',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'PUBLISHERNAME' => 
  array (
    'name' => 'PUBLISHERNAME',
    'value' => 'Kartenserver',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Bezeichung des Datenproviders
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'CHECK_CLIENT_IP' => 
  array (
    'name' => 'CHECK_CLIENT_IP',
    'value' => 'true',
    'prefix' => '',
    'type' => 'boolean',
    'description' => 'Erweiterung der Authentifizierung um die IP Adresse des Nutzers
Testet ob die IP des anfragenden Clientrechners dem Nutzer zugeordnet ist
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'PASSWORD_MAXLENGTH' => 
  array (
    'name' => 'PASSWORD_MAXLENGTH',
    'value' => '16',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'maximale Länge der Passwörter
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'PASSWORD_MINLENGTH' => 
  array (
    'name' => 'PASSWORD_MINLENGTH',
    'value' => '6',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'minimale Länge der Passwörter
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'PASSWORD_CHECK' => 
  array (
    'name' => 'PASSWORD_CHECK',
    'value' => '01010',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Prüfung neues Passwort
Auskommentiert, wenn das Passwort vom Admin auf "unendlichen" Zeitraum vergeben wird
erste Stelle  0 = Prüft die Stärke des Passworts (3 von 4 Kriterien müssen erfüllt sein) - die weiteren Stellen werden ignoriert
erste Stelle  1 = Prüft statt Stärke die nachfolgenden Kriterien:
zweite Stelle 1 = Es müssen Kleinbuchstaben enthalten sein
dritte Stelle 1 = Es müssen Großbuchstaben enthalten sein
vierte Stelle 1 = Es müssen Zahlen enthalten sein
fünfte Stelle 1 = Es müssen Sonderzeichen enthalten sein
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'GIT_USER' => 
  array (
    'name' => 'GIT_USER',
    'value' => 'gisadmin',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Wenn das kvwmap-Verzeichnis ein git-Repository ist, kann diese Konstante auf den User gesetzt werden, der das Repository angelegt hat.
Damit der Apache-User dann die git-Befehle als dieser User ausführen kann, muss man als root über den Befehl "visudo" die /etc/sudoers editieren.
Dort muss dann eine Zeile in dieser Form hinzugefügt werden: 
www-data        ALL=(fgs) NOPASSWD: /usr/bin/git
Dann kann man die Aktualität des Quellcodes in der Administrationsoberfläche überprüfen und ihn aktualisieren.
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAXQUERYROWS' => 
  array (
    'name' => 'MAXQUERYROWS',
    'value' => '10',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'maximale Anzahl der in einer Sachdatenabfrage zurückgelieferten Zeilen.
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'ALWAYS_DRAW' => 
  array (
    'name' => 'ALWAYS_DRAW',
    'value' => 'true',
    'prefix' => '',
    'type' => 'boolean',
    'description' => 'definiert, ob der Polygoneditor nach einem Neuladen
der Seite immer in den Modus "Polygon zeichnen" wechselt
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'EARTH_RADIUS' => 
  array (
    'name' => 'EARTH_RADIUS',
    'value' => '6384000',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Parameter für die Strecken- und Flächenreduktion
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'admin_stellen' => 
  array (
    'name' => 'admin_stellen',
    'value' => '[
    3
]',
    'prefix' => '',
    'type' => 'array',
    'description' => 'Adminstellen
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'gast_stellen' => 
  array (
    'name' => 'gast_stellen',
    'value' => '[
    35
]',
    'prefix' => '',
    'type' => 'array',
    'description' => 'Gast-Stellen
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'selectable_limits' => 
  array (
    'name' => 'selectable_limits',
    'value' => '[
    10,
    25,
    50,
    100,
    200
]',
    'prefix' => '',
    'type' => 'array',
    'description' => 'auswählbare Treffermengen
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'selectable_scales' => 
  array (
    'name' => 'selectable_scales',
    'value' => '[
    500,
    1000,
    2500,
    5000,
    7500,
    10000,
    25000,
    50000,
    100000,
    250000,
    500000,
    1000000
]',
    'prefix' => '',
    'type' => 'array',
    'description' => 'auswählbare Maßstäbe
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'supportedSRIDs' => 
  array (
    'name' => 'supportedSRIDs',
    'value' => '[
    5650,
    4326,
    2397,
    2398,
    2399,
    31466,
    31467,
    31468,
    31469,
    32648,
    25832,
    25833,
    35833,
    32633,
    325833,
    15833,
    900913,
    28992
]',
    'prefix' => '',
    'type' => 'array',
    'description' => 'Unterstützte SRIDs, nur diese stehen zur Auswahl bei der Stellenwahl
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'supportedLanguages' => 
  array (
    'name' => 'supportedLanguages',
    'value' => '[
    "german",
    "low-german",
    "english"
]',
    'prefix' => '',
    'type' => 'array',
    'description' => 'Unterstützte Sprachen, nur diese stehen zur Auswahl bei der Stellenwahl (\'german\', \'low-german\', \'english\', \'polish\', \'vietnamese\')
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'supportedExportFormats' => 
  array (
    'name' => 'supportedExportFormats',
    'value' => '[
    "Shape",
    "DXF",
    "GML",
    "KML",
    "GeoJSON",
    "UKO",
    "OVL",
    "CSV"
]',
    'prefix' => '',
    'type' => 'array',
    'description' => 'Unterstützte Exportformate
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAPFACTOR' => 
  array (
    'name' => 'MAPFACTOR',
    'value' => '3',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Faktor für die Einstellung der Druckqualität (MAPFACTOR * 72 dpi)     # Version 1.6.0
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'DEFAULT_DRUCKRAHMEN_ID' => 
  array (
    'name' => 'DEFAULT_DRUCKRAHMEN_ID',
    'value' => '42',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Standarddrucklayout für den schnellen Kartendruck						# Version 1.7.4
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAXUPLOADSIZE' => 
  array (
    'name' => 'MAXUPLOADSIZE',
    'value' => '200',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'maximale Datenmenge in MB, die beim Datenimport hochgeladen werden darf
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'MINSCALE' => 
  array (
    'name' => 'MINSCALE',
    'value' => '100',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Minmale Maßstabszahl
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'COORD_ZOOM_SCALE' => 
  array (
    'name' => 'COORD_ZOOM_SCALE',
    'value' => '50000',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Maßstab ab dem bei einem Koordinatensprung auch gezoomt wird
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'ZOOMBUFFER' => 
  array (
    'name' => 'ZOOMBUFFER',
    'value' => '100',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Puffer in der Einheit (ZOOMUNIT) der beim Zoom auf ein Objekt hinzugegeben wird
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'ZOOMUNIT' => 
  array (
    'name' => 'ZOOMUNIT',
    'value' => 'meter',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Einheit des Puffer der beim Zoom auf ein Objekt hinzugegeben wird
\'meter\' oder \'scale\'
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'DELETE_ROLLENLAYER' => 
  array (
    'name' => 'DELETE_ROLLENLAYER',
    'value' => 'true',
    'prefix' => '',
    'type' => 'boolean',
    'description' => 'Legt fest, ob die Rollenlayer beim Login eines Nutzers gelöscht werden sollen   # Version 1.6.5
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'SHOW_MAP_IMAGE' => 
  array (
    'name' => 'SHOW_MAP_IMAGE',
    'value' => 'true',
    'prefix' => '',
    'type' => 'boolean',
    'description' => 'Definiert, ob das aktuelle Kartenbild separat angezeigt werden darf oder nicht
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'kvwmap_plugins' => 
  array (
    'name' => 'kvwmap_plugins',
    'value' => '[
    "alkis",
    "anliegerbeitraege",
    "bauleitplanung",
    "baumfaellantrag",
    "bevoelkerung",
    "bodenrichtwerte",
    "fortfuehrungslisten",
    "geodoc",
    "gewaesser",
    "jagdkataster",
    "kolibri",
    "metadata",
    "mobile",
    "nachweisverwaltung",
    "probaug",
    "ukos",
    "xplankonverter"
]',
    'prefix' => '',
    'type' => 'array',
    'description' => '',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'INFO1' => 
  array (
    'name' => 'INFO1',
    'value' => 'Prüfen Sie ob Ihr Datenbankmodell aktuell ist.',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Festlegung von Fehlermeldungen und Hinweisen
',
    'group' => 'Administration',
    'plugin' => '',
    'saved' => 0,
  ),
  'APPLVERSION' => 
  array (
    'name' => 'APPLVERSION',
    'value' => 'kvwmap_dev/',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'INSTALLPATH' => 
  array (
    'name' => 'INSTALLPATH',
    'value' => '/var/www/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Installationspfad
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'WWWROOT' => 
  array (
    'name' => 'WWWROOT',
    'value' => 'apps/',
    'prefix' => 'INSTALLPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'IMAGEPATH' => 
  array (
    'name' => 'IMAGEPATH',
    'value' => 'tmp/',
    'prefix' => 'INSTALLPATH',
    'type' => 'string',
    'description' => 'Verzeichnis, in dem die temporären Bilder usw. abgelegt werden
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'URL' => 
  array (
    'name' => 'URL',
    'value' => 'https://gdi-service.de/',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'NBH_PATH' => 
  array (
    'name' => 'NBH_PATH',
    'value' => 'tools/UTM33_NBH.lst',
    'prefix' => 'WWWROOT.APPLVERSION',
    'type' => 'string',
    'description' => 'Datei mit den Nummerierungsbezirkshöhen
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAPSERV_CGI_BIN' => 
  array (
    'name' => 'MAPSERV_CGI_BIN',
    'value' => 'cgi-bin/mapserv',
    'prefix' => 'URL',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'LOGPATH' => 
  array (
    'name' => 'LOGPATH',
    'value' => 'logs/',
    'prefix' => 'INSTALLPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'SHAPEPATH' => 
  array (
    'name' => 'SHAPEPATH',
    'value' => 'data/',
    'prefix' => 'INSTALLPATH',
    'type' => 'string',
    'description' => 'Shapepath [Pfad zum Shapefileverzeichnis]
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'CUSTOM_SHAPE_SCHEMA' => 
  array (
    'name' => 'CUSTOM_SHAPE_SCHEMA',
    'value' => 'custom_shapes',
    'prefix' => '',
    'type' => 'string',
    'description' => 'ein extra Schema in der PG-DB, in der die Tabellen der Nutzer Shapes angelegt werden
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'REFERENCEMAPPATH' => 
  array (
    'name' => 'REFERENCEMAPPATH',
    'value' => 'referencemaps/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'DRUCKRAHMEN_PATH' => 
  array (
    'name' => 'DRUCKRAHMEN_PATH',
    'value' => 'druckrahmen/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => 'Pfad zum Speichern der Kartendruck-Layouts
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'THIRDPARTY_PATH' => 
  array (
    'name' => 'THIRDPARTY_PATH',
    'value' => '../3rdparty/',
    'prefix' => '',
    'type' => 'string',
    'description' => '3rdparty Pfad
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'FONTAWESOME_PATH' => 
  array (
    'name' => 'FONTAWESOME_PATH',
    'value' => 'font-awesome-4.6.3/',
    'prefix' => 'THIRDPARTY_PATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'JQUERY_PATH' => 
  array (
    'name' => 'JQUERY_PATH',
    'value' => 'jQuery-1.12.0/',
    'prefix' => 'THIRDPARTY_PATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'BOOTSTRAP_PATH' => 
  array (
    'name' => 'BOOTSTRAP_PATH',
    'value' => 'bootstrap-3.3.6/',
    'prefix' => 'THIRDPARTY_PATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'BOOTSTRAPTABLE_PATH' => 
  array (
    'name' => 'BOOTSTRAPTABLE_PATH',
    'value' => 'bootstrap-table-1.11.0/',
    'prefix' => 'THIRDPARTY_PATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'PROJ4JS_PATH' => 
  array (
    'name' => 'PROJ4JS_PATH',
    'value' => 'proj4js-2.4.3/',
    'prefix' => 'THIRDPARTY_PATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'POSTGRESBINPATH' => 
  array (
    'name' => 'POSTGRESBINPATH',
    'value' => '/usr/bin/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Bin-Pfad der Postgres-tools (shp2pgsql, pgsql2shp)
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'OGR_BINPATH' => 
  array (
    'name' => 'OGR_BINPATH',
    'value' => '/usr/bin/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Bin-Pfad der OGR-tools (ogr2ogr, ogrinfo)
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'ZIP_PATH' => 
  array (
    'name' => 'ZIP_PATH',
    'value' => 'zip',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Pfad zum Zip-Programm (unter Linux: \'zip -j\', unter Windows z.B. \'c:/programme/Zip/bin/zip.exe\')
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'CUSTOM_IMAGE_PATH' => 
  array (
    'name' => 'CUSTOM_IMAGE_PATH',
    'value' => 'bilder/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => 'Pfad für selbst gemachte Bilder
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'CACHEPATH' => 
  array (
    'name' => 'CACHEPATH',
    'value' => 'cache/',
    'prefix' => 'INSTALLPATH',
    'type' => 'string',
    'description' => 'Cachespeicherort
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'CACHETIME' => 
  array (
    'name' => 'CACHETIME',
    'value' => '168',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Cachezeit Nach welcher Zeit in Stunden sollen gecachte Dateien aktualisiert werden
wird derzeit noch nicht berücksichtigt
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'TEMPPATH_REL' => 
  array (
    'name' => 'TEMPPATH_REL',
    'value' => '../tmp/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'relative Pfadangabe zum Webverzeichnis mit temprären Dateien
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'IMAGEURL' => 
  array (
    'name' => 'IMAGEURL',
    'value' => '/tmp/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Imageurl
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'SYMBOLSET' => 
  array (
    'name' => 'SYMBOLSET',
    'value' => 'symbols/symbole.sym',
    'prefix' => 'WWWROOT.APPLVERSION',
    'type' => 'string',
    'description' => 'Symbolset
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'FONTSET' => 
  array (
    'name' => 'FONTSET',
    'value' => 'fonts/fonts.txt',
    'prefix' => 'WWWROOT.APPLVERSION',
    'type' => 'string',
    'description' => 'Fontset
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'GRAPHICSPATH' => 
  array (
    'name' => 'GRAPHICSPATH',
    'value' => 'graphics/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Graphics
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'WAPPENPATH' => 
  array (
    'name' => 'WAPPENPATH',
    'value' => 'wappen/',
    'prefix' => 'GRAPHICSPATH',
    'type' => 'string',
    'description' => 'Wappen
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'LAYOUTPATH' => 
  array (
    'name' => 'LAYOUTPATH',
    'value' => 'layouts/',
    'prefix' => 'WWWROOT.APPLVERSION',
    'type' => 'string',
    'description' => 'Layouts
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'SNIPPETS' => 
  array (
    'name' => 'SNIPPETS',
    'value' => 'snippets/',
    'prefix' => 'LAYOUTPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'CLASSPATH' => 
  array (
    'name' => 'CLASSPATH',
    'value' => 'class/',
    'prefix' => 'WWWROOT.APPLVERSION',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'PLUGINS' => 
  array (
    'name' => 'PLUGINS',
    'value' => 'plugins/',
    'prefix' => 'WWWROOT.APPLVERSION',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'SYNC_PATH' => 
  array (
    'name' => 'SYNC_PATH',
    'value' => 'synchro/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => 'Synchronisationsverzeichnis                         # Version 1.7.0
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'IMAGEMAGICKPATH' => 
  array (
    'name' => 'IMAGEMAGICKPATH',
    'value' => '/usr/bin/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Pfad zum Imagemagick convert
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'UPLOADPATH' => 
  array (
    'name' => 'UPLOADPATH',
    'value' => 'upload/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => 'Pfad zum Ordner für Datei-Uploads
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'DEFAULTMAPFILE' => 
  array (
    'name' => 'DEFAULTMAPFILE',
    'value' => 'mapfiles/defaultmapfile.map',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => 'Mapfile, mit dem das Mapobjekt gebildet wird
',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'REFMAPFILE' => 
  array (
    'name' => 'REFMAPFILE',
    'value' => 'mapfiles/refmapfile.map',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Pfadeinstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAILMETHOD' => 
  array (
    'name' => 'MAILMETHOD',
    'value' => 'sendmail',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Methode zum Versenden von E-Mails. Mögliche Optionen:
sendmail: E-Mails werden direkt mit sendmail versendet. (default)
sendEmail async: E-Mails werden erst in einem temporären Verzeichnis MAILQUEUEPATH
abgelegt und können später durch das Script tools/sendEmailAsync.sh
versendet werden. Dort muss auch MAILQUEUEPATH eingestellt werden.
',
    'group' => 'E-Mail Einstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAILSMTPSERVER' => 
  array (
    'name' => 'MAILSMTPSERVER',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'SMTP-Server, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
',
    'group' => 'E-Mail Einstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAILSMTPPORT' => 
  array (
    'name' => 'MAILSMTPPORT',
    'value' => '25',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'SMTP-Port, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
',
    'group' => 'E-Mail Einstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAILQUEUEPATH' => 
  array (
    'name' => 'MAILQUEUEPATH',
    'value' => '/var/www/logs/kvwmap/mail_queue/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Verzeichnis für die JSON-Dateien mit denzu versendenen E-Mails.
Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
',
    'group' => 'E-Mail Einstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAILARCHIVPATH' => 
  array (
    'name' => 'MAILARCHIVPATH',
    'value' => '/var/www/logs/kvwmap/mail_archiv/',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'E-Mail Einstellungen',
    'plugin' => '',
    'saved' => 0,
  ),
  'LAYER_IDS_DOP' => 
  array (
    'name' => 'LAYER_IDS_DOP',
    'value' => '79,80',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Layer-IDs',
    'plugin' => '',
    'saved' => 0,
  ),
  'LAYER_ID_SCHNELLSPRUNG' => 
  array (
    'name' => 'LAYER_ID_SCHNELLSPRUNG',
    'value' => '749',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Layer-IDs',
    'plugin' => '',
    'saved' => 0,
  ),
  'quicksearch_layer_ids' => 
  array (
    'name' => 'quicksearch_layer_ids',
    'value' => '[
    159
]',
    'prefix' => '',
    'type' => 'array',
    'description' => '',
    'group' => 'Layer-IDs',
    'plugin' => '',
    'saved' => 0,
  ),
  'DEBUGFILE' => 
  array (
    'name' => 'DEBUGFILE',
    'value' => '_debug.htm',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Ort der Datei, in der die Meldungen beim Debugen geschrieben werden
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'DEBUG_LEVEL' => 
  array (
    'name' => 'DEBUG_LEVEL',
    'value' => '1',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Level der Fehlermeldungen beim debuggen
3 nur Ausgaben die für Admin bestimmt sind
2 nur Datenbankanfragen
1 nur wichtige Fehlermeldungen
5 keine Ausgaben
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'LOGFILE_MYSQL' => 
  array (
    'name' => 'LOGFILE_MYSQL',
    'value' => '_log_mysql.sql',
    'prefix' => 'LOGPATH',
    'type' => 'string',
    'description' => 'mySQL-Log-Datei zur Speicherung der SQL-Statements              # Version 1.6.0
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'LOGFILE_POSTGRES' => 
  array (
    'name' => 'LOGFILE_POSTGRES',
    'value' => '_log_postgres.sql',
    'prefix' => 'LOGPATH',
    'type' => 'string',
    'description' => 'postgreSQL-Log-Datei zur Speicherung der SQL-Statements         # Version 1.6.0
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'LOGFILE_LOGIN' => 
  array (
    'name' => 'LOGFILE_LOGIN',
    'value' => 'login_fail.log',
    'prefix' => 'LOGPATH',
    'type' => 'string',
    'description' => 'Log-Datei zur Speicherung der Login Vorgänge
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'LOG_LEVEL' => 
  array (
    'name' => 'LOG_LEVEL',
    'value' => '2',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Log-Level zur Speicherung der SQL-Statements                    # Version 1.6.0
Loglevel
0 niemals loggen
1 immer loggen
2 nur loggen wenn loglevel in execSQL 1 ist.
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'SAVEMAPFILE' => 
  array (
    'name' => 'SAVEMAPFILE',
    'value' => 'save_mapfile.map',
    'prefix' => 'LOGPATH',
    'type' => 'string',
    'description' => 'Wenn SAVEMAPFILE leer ist, wird sie nicht gespeichert.
Achtung, wenn die cgi-bin/mapserv ohne Authentifizierung und der Pfad zu save_mapfile.map bekannt ist, kann jeder die Karten des letzten Aufrufs in kvwmap über mapserv?map=<pfad zu save_map.map abfragen. Und wenn wfs zugelassen ist auch die Sachdaten dazu runterladen. Diese Konstante sollte nur zu debug-Zwecken eingeschaltet bleiben.
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'DEFAULTDBWRITE' => 
  array (
    'name' => 'DEFAULTDBWRITE',
    'value' => '1',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Ermöglicht die Ausführung der SQL-Statements in der Datenbank zu unterdrücken.
In dem Fall werden die Statements nur in die Log-Datei geschrieben.
Die Definition von DBWRITE ist umgezogen nach start.php, damit das Unterdrücken
des Schreiben in die Datenbank auch mit Formularwerten eingestellt werden kann.
das übernimmt in dem Falle die Formularvariable disableDbWrite.
Hier kann jedoch noch der Defaultwert gesetzt werden
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'LOG_CONSUME_ACTIVITY' => 
  array (
    'name' => 'LOG_CONSUME_ACTIVITY',
    'value' => '1',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Einstellungen zur Speicherung der Zugriffe
',
    'group' => 'Logging',
    'plugin' => '',
    'saved' => 0,
  ),
  'MYSQL_HOST' => 
  array (
    'name' => 'MYSQL_HOST',
    'value' => 'mysql',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'MYSQL_USER' => 
  array (
    'name' => 'MYSQL_USER',
    'value' => 'kvwmap',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'MYSQL_PASSWORD' => 
  array (
    'name' => 'MYSQL_PASSWORD',
    'value' => getenv('MYSQL_ENV_MYSQL_ROOT_PASSWORD'),
    'prefix' => '',
    'type' => 'password',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'MYSQL_DBNAME' => 
  array (
    'name' => 'MYSQL_DBNAME',
    'value' => 'kvwmapdb',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'MYSQL_HOSTS_ALLOWED' => 
  array (
    'name' => 'MYSQL_HOSTS_ALLOWED',
    'value' => '172.17.%',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'POSTGRES_HOST' => 
  array (
    'name' => 'POSTGRES_HOST',
    'value' => 'pgsql',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'POSTGRES_USER' => 
  array (
    'name' => 'POSTGRES_USER',
    'value' => 'kvwmap',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'POSTGRES_PASSWORD' => 
  array (
    'name' => 'POSTGRES_PASSWORD',
    'value' => getenv('PGSQL_ROOT_PASSWORD'),
    'prefix' => '',
    'type' => 'password',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'POSTGRES_DBNAME' => 
  array (
    'name' => 'POSTGRES_DBNAME',
    'value' => 'kvwmapsp',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Datenbanken',
    'plugin' => '',
    'saved' => 0,
  ),
  'MAPFILENAME' => 
  array (
    'name' => 'MAPFILENAME',
    'value' => 'kvwmap',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'WMS_MAPFILE_REL_PATH' => 
  array (
    'name' => 'WMS_MAPFILE_REL_PATH',
    'value' => 'wms/',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Voreinstellungen für Metadaten zu Web Map Services (WMS-Server)
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'WMS_MAPFILE_PATH' => 
  array (
    'name' => 'WMS_MAPFILE_PATH',
    'value' => 'INSTALLPATH.WMS_MAPFILE_REL_PATH',
    'prefix' => 'INSTALLPATH.WMS_MAPFILE_REL_PATH',
    'type' => 'string',
    'description' => '',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'SUPORTED_WMS_VERSION' => 
  array (
    'name' => 'SUPORTED_WMS_VERSION',
    'value' => '1.1.0',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_SCHEMAS_LOCATION' => 
  array (
    'name' => 'OWS_SCHEMAS_LOCATION',
    'value' => 'http://schemas.opengeospatial.net',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Metadaten zur Ausgabe im Capabilities Dokument gelten für WMS, WFS und WCS
sets base URL for OGC Schemas so the root element in the
Capabilities document points to the correct schema location
to produce valid XML
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_TITLE' => 
  array (
    'name' => 'OWS_TITLE',
    'value' => 'MapServer kvwmap',
    'prefix' => '',
    'type' => 'string',
    'description' => 'An Stelle von WMS_TITLE
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_ABSTRACT' => 
  array (
    'name' => 'OWS_ABSTRACT',
    'value' => 'Kartenserver für kommunale Verwaltungen',
    'prefix' => '',
    'type' => 'string',
    'description' => 'An Stelle von WMS_Abstract
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_KEYWORDLIST' => 
  array (
    'name' => 'OWS_KEYWORDLIST',
    'value' => 'GIS,Landkreis,Kataster,Geoinformation',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/KeywordList/Keyword[]
WFS_Capabilities/Service/Keywords
WCS_Capabilities/Service/keywords/keyword[]
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_SERVICE_ONLINERESOURCE' => 
  array (
    'name' => 'OWS_SERVICE_ONLINERESOURCE',
    'value' => 'index.php?go=OWS',
    'prefix' => 'URL.APPLVERSION',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/OnlineResource
WFS_Capabilities/Service/OnlineResource
WCS_Capabilities/Service/responsibleParty/onlineResource/@xlink:href
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_FEES' => 
  array (
    'name' => 'OWS_FEES',
    'value' => 'zu Testzwecken frei',
    'prefix' => '',
    'type' => 'string',
    'description' => 'An Stelle WMS_FEES
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_ACCESSCONSTRAINTS' => 
  array (
    'name' => 'OWS_ACCESSCONSTRAINTS',
    'value' => 'none',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/AccessConstraints
WFS_Capabilities/Service/AccessConstraints
WCS_Capabilities/Service/accessConstraints
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_CONTACTPERSON' => 
  array (
    'name' => 'OWS_CONTACTPERSON',
    'value' => 'Peter Korduan',
    'prefix' => '',
    'type' => 'string',
    'description' => 'An Stelle von WMS_CONTACTPERSON
WMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactPerson
WCS_Capabilities/Service/responsibleParty/individualName
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_CONTACTORGANIZATION' => 
  array (
    'name' => 'OWS_CONTACTORGANIZATION',
    'value' => 'Universität Rostock',
    'prefix' => '',
    'type' => 'string',
    'description' => 'An Stelle von WMS_CONTACTORGANIZATION
WMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactOrganization
WCS_Capabilities/Service/responsibleParty/organisationName
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_CONTACTPOSITION' => 
  array (
    'name' => 'OWS_CONTACTPOSITION',
    'value' => 'Softwareentwickler',
    'prefix' => '',
    'type' => 'string',
    'description' => 'An Stelle von WMS_CONTACTPOSITION
WMT_MS_Capabilities/Service/ContactInformation/ContactPosition
WCS_Capabilities/Service/responsibleParty/positionName
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_ADDRESSTYPE' => 
  array (
    'name' => 'OWS_ADDRESSTYPE',
    'value' => 'postal',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/AddressType
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_ADDRESS' => 
  array (
    'name' => 'OWS_ADDRESS',
    'value' => 'Justus-von-Liebig-Weg 6',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Address
WCS_Capabilities/Service/contactInfo/address/deliveryPoint
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_CITY' => 
  array (
    'name' => 'OWS_CITY',
    'value' => 'Rostock',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/City
WCS_Capabilities/Service/contactInfo/address/city
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_STATEORPROVINCE' => 
  array (
    'name' => 'OWS_STATEORPROVINCE',
    'value' => 'Mecklenburg-Vorpommern',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/StateOrProvince
WCS_Capabilities/Service/contactInfo/address/administrativeArea
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_POSTCODE' => 
  array (
    'name' => 'OWS_POSTCODE',
    'value' => '18059',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/PostCode
WCS_Capabilities/Service/contactInfo/address/postalCode
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_COUNTRY' => 
  array (
    'name' => 'OWS_COUNTRY',
    'value' => 'Germany',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Country
WCS_Capabilities/Service/contactInfo/address/country
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_CONTACTVOICETELEPHONE' => 
  array (
    'name' => 'OWS_CONTACTVOICETELEPHONE',
    'value' => '0049-381-498-2164',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/ContactInformation/ContactVoiceTelephone
WCS_Capabilities/Service/contactInfo/phone/voice
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_CONTACTFACSIMILETELEPHONE' => 
  array (
    'name' => 'OWS_CONTACTFACSIMILETELEPHONE',
    'value' => '0049-381-498-2188',
    'prefix' => '',
    'type' => 'string',
    'description' => 'WMT_MS_Capabilities/Service/ContactInformation/ContactFacsimileTelephone
WCS_Capabilities/Service/contactInfo/phone/facsimile
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_CONTACTELECTRONICMAILADDRESS' => 
  array (
    'name' => 'OWS_CONTACTELECTRONICMAILADDRESS',
    'value' => 'peter.korduan@gdi-service.de',
    'prefix' => '',
    'type' => 'string',
    'description' => 'An Stelle von WMS_CONTACTELECTRONICMAILADDRESS
WMT_MS_Capabilities/Service/ContactInformation/ContactElectronicMailAddress
WCS_Capabilities/Service/contactInfo/address/eletronicMailAddress
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'OWS_SRS' => 
  array (
    'name' => 'OWS_SRS',
    'value' => 'EPSG:25833 EPSG:4326 EPSG:2398',
    'prefix' => '',
    'type' => 'string',
    'description' => 'An Stelle von WMS_SRS
WMT_MS_Capabilities/Capability/Layer/SRS
WMT_MS_Capabilities/Capability/Layer/Layer[*]/SRS
WFS_Capabilities/FeatureTypeList/FeatureType[*]/SRS
unless differently defined in LAYER object
if you are setting > 1 SRS for WMS, you need to define "wms_srs" and "wfs_srs"
seperately because OGC:WFS only accepts one OUTPUT SRS
',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'WFS_SRS' => 
  array (
    'name' => 'WFS_SRS',
    'value' => 'EPSG:25833',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'OWS-METADATEN',
    'plugin' => '',
    'saved' => 0,
  ),
  'METADATA_AUTH_LINK' => 
  array (
    'name' => 'METADATA_AUTH_LINK',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'URL zum Authentifizieren am CSW-Metadatensystem
',
    'group' => 'z CSW-Metadatensystem',
    'plugin' => '',
    'saved' => 0,
  ),
  'METADATA_ONLINE_RESOURCE' => 
  array (
    'name' => 'METADATA_ONLINE_RESOURCE',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'URL zum CSW-Server
',
    'group' => 'z CSW-Metadatensystem',
    'plugin' => '',
    'saved' => 0,
  ),
  'METADATA_EDIT_LINK' => 
  array (
    'name' => 'METADATA_EDIT_LINK',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'URL zum Editieren von Metadaten im CSW-Metadatensystem
',
    'group' => 'z CSW-Metadatensystem',
    'plugin' => '',
    'saved' => 0,
  ),
  'METADATA_SEARCH_LINK' => 
  array (
    'name' => 'METADATA_SEARCH_LINK',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'URL zum Recherchieren von Metadaten im CSW-Metadatensystem
',
    'group' => 'z CSW-Metadatensystem',
    'plugin' => '',
    'saved' => 0,
  ),
);

$config_file = 'config.php';
if(file_exists($config_file)){
	$own_constants = $this->get_constants_from_config(file($config_file), '');
	foreach($constants as &$constant){
		if(array_key_exists($constant['name'], $own_constants)){
			$constant['value'] = $own_constants[$constant['name']]['value'];
			$constant['saved'] = 1;
		}
	}
}

$sql = "SELECT * FROM config WHERE plugin = ''";
$result=$this->database->execSQL($sql,0, 0);
if($result[0]){
	echo '<br>Fehler bei der Abfrage der Tabelle config.<br>';
}
else{
	if(mysql_num_rows($result[1]) == 0){
		$sql = '';
		foreach($constants as $constant){
			if(!in_array($constant['name'], array('MYSQL_HOST','MYSQL_USER','MYSQL_PASSWORD','MYSQL_DBNAME','MYSQL_HOSTS_ALLOWED'))){
				$sql.="INSERT INTO config (name, prefix, value, description, type, `group`, `plugin`, `saved`) VALUES ('".$constant['name']."', '".$constant['prefix']."', '".addslashes($constant['value'])."', '".addslashes($constant['description'])."', '".$constant['type']."', '".$constant['group']."', '".$constant['plugin']."', ".$constant['saved'].");\n";
			}
			else{
				if(defined($constant['name']))$constant['value'] = var_export(constant($constant['name']), true);
				$credentials.= 'define('.$constant['name'].', '.$constant['value'].");\n";
			}
		}
		# config Tabelle befüllen
		$result = $this->database->exec_commands($sql, NULL, NULL);
	}
}

if($result[0] == 0){
	# credentials.php schreiben
	if(file_put_contents('credentials.php', "<?\n\n".$credentials."\n?>") === false){
		$result[0]=1;
		$result[1]='Fehler beim Schreiben der credentials-Datei';
	}
	else{
		# config.php schreiben
		$result = $this->write_config_file('');
	}
}

?>
