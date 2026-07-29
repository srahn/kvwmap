BEGIN;

	INSERT INTO config (`name`, `value`, `prefix`, `type`, `description`, `group`, `plugin`, `saved`)
	VALUES ('XPLANKONVERTER_CC_EMAILS', '', '', 'string', 'Lists potential commaseparated list of cc emails for xplankonverter zusammenzeichnung-upload', 'Plugins/xplankonverter', 'xplankonverter', 1).
	VALUES ('XPLANKONVERTER_XPLANVALIDATOR_VALIDATE', 'true', '', 'boolean', 'Toggle for validation step within zusammenzeichnung upload. Useful in case api is down, but files should be uploaded', 'Plugins/xplankonverter', 'xplankonverter', 1);

COMMIT;
