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
		'OVERRIDE_LANGUAGE_VARS',
		'',
		'false',
		'Wenn mit true aktiviert, werden Variablen mit Texten der unterschiedlichen Sprachen durch Variablen in gleichnamigen custom-Dateien überschrieben falls vorhanden.',
		'boolean',
		'Layout',
		0,
		2
	);

COMMIT;
