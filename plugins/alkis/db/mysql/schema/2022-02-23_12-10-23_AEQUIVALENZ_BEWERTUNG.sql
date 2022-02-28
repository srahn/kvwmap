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
		'AEQUIVALENZ_BEWERTUNG',
		'',
		'false',
		'Wird dieser Parameter auf true gesetzt, wird die gesetzliche Klassifizierung der Bodenschätzung auf Basis der Äquivalenzbewertung ausgegeben.',
		'boolean',
		'Plugins/alkis',
		'alkis',
		0,
		2
	);

COMMIT;
