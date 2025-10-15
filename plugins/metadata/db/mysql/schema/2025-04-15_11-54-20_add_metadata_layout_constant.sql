BEGIN;

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`, `editable`) VALUES (
    'METADATA_PRINT_LAYOUT_ID',
    '',
    '',
    'ID des Drucklayouts für das Metadatendokument der Ressourcen.',
    'numeric',
    'metadata',
    'Plugins/metadata',
    0,
    2
  );

COMMIT;