BEGIN;

  INSERT INTO config (`name`, `value`, `description`, `type`, `plugin`, `group`, `saved`) VALUES (
    'XPLANKONVERTER_DEFAULT_EPSG',
    '25833',
    'Bevorzugter EPSG-Code für XPlanGML.',
    'integer',
    'xplankonverter',
    'Plugins/xplankonverter',
    2
  );

COMMIT;
