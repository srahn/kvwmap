BEGIN;

	INSERT INTO config (`name`, `value`, `prefix`, `type`, `description`, `group`, `plugin`, `saved`)
	VALUES ('XPLANKONVERTER_CREATE_SERVICE', 'true', '', 'boolean', 'Erzeugt oder aktualisiert GeoWeb-Dienst und Metadaten nach Upload von XPlanGml.', 'Plugins/xplankonverter', 'xplankonverter', 1);

COMMIT;
