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
		'NUTZER_ARCHIVIEREN',
		'',
		'false',
		'Ist dieser Parameter auf true gesetzt, werden Nutzer nicht gelöscht sondern archiviert.',
		'boolean',
		'Administration',
		0,
		2
	);

COMMIT;
