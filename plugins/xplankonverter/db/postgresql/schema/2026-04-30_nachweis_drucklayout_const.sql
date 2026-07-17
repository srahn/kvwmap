BEGIN;
  INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES (
    'XPLANKONVERTER_VEROEFF_NACHWEIS_LAYER_ID',
    '',
    '',
    'ID des Layers in dem die Veröffentlichungsprotokolle gespeichert sind welche für den Auslegungsnachweis verwendet werden sollen.',
    'numeric',
    'xplankonverter',
    'Plugins/xplankonverter',
    1,
    2
  );
  INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES (
    'XPLANKONVERTER_VEROEFF_NACHWEIS_DDL',
    '',
    '',
    'ID des Sachdatendrucklayouts, welches für den Auslegungsnachweis verwendet werden soll.',
    'numeric',
    'xplankonverter',
    'Plugins/xplankonverter',
    1,
    2
  );
  INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES (
    'XPLANKONVERTER_COORDINATOR_EMAIL',
    '',
    'robert.kraetschmer@gdi-service.de',
    'E-Mail des Koordinators des Bauleitplanservers. Wird als Ansprechpartner für Fragen rund um die Veröffentlichung von Bauleitplänen in den Benachrichtigungen verwendet.',
    'string',
    'xplankonverter',
    'Plugins/xplankonverter',
    1,
    2
  );
COMMIT;