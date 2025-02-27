BEGIN;

  INSERT INTO config (`name`, `prefix`, `value`, `description`, `type`, `plugin`, `group`, `saved`) VALUES (
    'XPLANKONVERTER_ENABLE_PUBLISH',
		'',
    'false',
    'Stellt ein ob die Pläne über eine extra Schaltfläche für die Sichtbarkeit in Diensten freigeschaltet werden sollen.',
    'boolean',
    'xplankonverter',
    'Plugins/xplankonverter',
    0
  );

COMMIT;
