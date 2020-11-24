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
		'MS_DEBUG_LEVEL',
		'',
		'0',
		'Legt fest, ob Nutzer eigene Filter für Layer erstellen können.',
		'integer',
		'Logging',
		0,
		2
	);

COMMIT;
