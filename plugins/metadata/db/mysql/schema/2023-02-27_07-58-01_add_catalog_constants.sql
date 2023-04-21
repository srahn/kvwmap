BEGIN;

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`, `editable`) VALUES (
    'METADATA_CATALOG',
    '',
    '',
    'URL des GeoNetwork Metainformationssystems (MIS).',
    'string',
    'metadata',
    'Plugins/metadata',
    0,
    2
  );

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`, `editable`) VALUES (
    'METADATA_CATALOGUSER',
    '',
    'admin',
    'Nutzername für den ZUgang zum GeoNetwork Metainformationssystems (MIS).',
    'string',
    'metadata',
    'Plugins/metadata',
    0,
    2
  );

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`, `editable`) VALUES (
    'METADATA_CATALOGPASS',
    '',
    '',
    'Passwort für den Zugang zum GeoNetwork Metainformationssystem (MIS).',
    'string',
    'metadata',
    'Plugins/metadata',
    0,
    2
  );
COMMIT;