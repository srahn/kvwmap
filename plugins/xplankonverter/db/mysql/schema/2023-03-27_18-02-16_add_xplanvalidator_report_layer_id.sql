BEGIN;

  INSERT INTO config (`name`, `value`, `description`, `type`, `plugin`, `group`, `saved`) VALUES (
    'XPLANKONVERTER_XPLANVALIDATOR_REPORT_LAYER_ID',
    '1',
    'ID des Layers, der die Berichte des XPlanValidators der Leitstelle beinhaltet.',
    'integer',
    'xplankonverter',
    'Plugins/xplankonverter',
    2
  );

COMMIT;
