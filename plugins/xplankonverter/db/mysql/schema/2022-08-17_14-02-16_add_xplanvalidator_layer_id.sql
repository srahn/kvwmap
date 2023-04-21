BEGIN;

  INSERT INTO config (`name`, `value`, `description`, `type`, `plugin`, `group`, `saved`) VALUES (
    'XPLANKONVERTER_XPLANVALIDATOR_SEMANTIC_REPORT_LAYER_ID',
    '2',
    'ID des Layers, der die Berichte über die semantische Prüfung des XPlanValidators der Leitstelle beinhaltet.',
    'integer',
    'xplankonverter',
    'Plugins/xplankonverter',
    2
  );

COMMIT;
