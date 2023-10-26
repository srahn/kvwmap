BEGIN;

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`) VALUES (
    'XPLANKONVERTER_DEFAULT_EPSG',
    '',
    '25833',
    'Bevorzugter EPSG-Code für XPlanGML.',
    'integer',
    'xplankonverter',
    'Plugins/xplankonverter',
    2
  );

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`) VALUES (
    'XPLANKONVERTER_FUNC_VALIDATOR',
    '',
    0,
    'Funktionsbutton zum Aufruf des XPlanValidators der Leitstelle in der Planliste anzeigen.',
    'boolean',
    'xplankonverter',
    'Plugins/xplankonverter',
    2
  );

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`) VALUES (
    'XPLANKONVERTER_FUNC_SERVICE',
    '',
    0,
    'Funktionsbutton zum Erzeugen eines Dienstes zum Plan in der Planliste anzeigen.',
    'boolean',
    'xplankonverter',
    'Plugins/xplankonverter',
    2
  );

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`) VALUES (
    'XPLANKONVERTER_FUNC_INSPIRE',
    '',
    0,
    'Funktionsbutton zum Erzeugen einer INSPIRE-GML-Datei in der Planliste anzeigen.',
    'boolean',
    'xplankonverter',
    'Plugins/xplankonverter',
    2
  );

COMMIT;
