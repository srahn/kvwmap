BEGIN;

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`, `editable`) VALUES (
    'METADATA_MAX_UPDATE_PROCESSES',
    '',
    '',
    'Maximale Anzahl der gleichzeitig laufenden Prozesse zum Update von Ressourcen.',
    'integer',
    'metadata',
    'Plugins/metadata',
    0,
    2
  );

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`, `editable`) VALUES (
    'METADATA_BUNDLE_PACKAGE_NAME',
    '',
    'Datentool-Gesamtpaket',
    'Name der ZIP-Datei in der die Datenpakete einer Stelle zusammengepackt zum Download zur Verfügung stehen.',
    'string',
    'metadata',
    'Plugins/metadata',
    0,
    2
  );

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`, `editable`) VALUES (
    'METADATA_DATA_PATH',
    '',
    '/var/www/data/',
    'Absoluter Pfad für das Verzeichnis in das die Daten der Ressourcen heruntergeladen, entpackt und die Datenpackete für den Download abgelegt werden.',
    'string',
    'metadata',
    'Plugins/metadata',
    0,
    2
  );

  INSERT INTO u_funktionen (`bezeichnung`) VALUES (
    'metadata_update_outdated'
  );

COMMIT;