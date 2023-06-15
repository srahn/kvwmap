BEGIN;

  INSERT INTO `config` (
		`name`,
		`prefix`,
		`value`,
		`description`,
		`type`,
		`group`,
		`plugin`,
		`saved`,
		`editable`
	) VALUES (
		'STRASSENNAMEN_EINDEUTIG',
		'',
		'true',
		'Dieser Parameter kann auf true gesetzt werden, wenn die Strassennamen pro Gemeinde eindeutig sind.',
		'boolean',
		'Plugins/alkis',
		'alkis',
		0,
		2
	);

COMMIT;
