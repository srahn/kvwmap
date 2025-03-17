BEGIN;

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`, `editable`) VALUES (
    'METADATA_RESSOURCES_LAYER_ID',
    '',
    '',
    'ID des Layers für die Ressourcen.',
    'numeric',
    'metadata',
    'Plugins/metadata',
    0,
    2
  );

COMMIT;