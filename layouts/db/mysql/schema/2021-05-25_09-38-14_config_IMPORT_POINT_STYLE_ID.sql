BEGIN;

  INSERT INTO `config` (
		`name`,
		`prefix`,
		`value`,
		`description`,
		`type`,
		`group`,
		`saved`,
		`editable`
	) VALUES (
		'IMPORT_POINT_STYLE_ID',
		'',
		(SELECT `value` FROM (SELECT * FROM `config`) as foo WHERE `name` = 'ZOOM2POINT_STYLE_ID'),
		'Hier kann ein eigener Style für den Datenimport von Punkt-Objekten eingetragen werden.',
		'integer',
		'Layout',
		0,
		2
	);

COMMIT;
