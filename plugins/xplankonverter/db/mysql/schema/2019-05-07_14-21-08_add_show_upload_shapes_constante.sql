BEGIN;

	INSERT INTO config (`name`, `value`, `prefix`, `type`, `description`, `group`, `plugin`, `saved`)
	VALUES ('XPLANKONVERTER_CREATE_UPLOAD_SHAPE_LAYER', 'false', '', 'boolean', 'Erzeugt Layer für hochgeladene Shape-Dateien.', 'Plugins/xplankonverter', 'xplankonverter', 1);

COMMIT;
