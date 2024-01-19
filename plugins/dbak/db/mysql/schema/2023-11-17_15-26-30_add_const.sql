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
		'DBAK_URL',
		'',
		'',
		'URL zum Server der die Schnittstelle dbak unterstützt.',
		'string',
		'Plugins/dbak',
		'dbak',
		1,
		2
	), (
		'DBAK_USER',
		'',
		'',
		'Nutzer für die dbak Schnittstelle.',
		'string',
		'Plugins/dbak',
		'dbak',
		1,
		2
	), (
		'DBAK_URL',
		'',
		'',
		'Passwort für die dbak Schnittstelle.',
		'string',
		'Plugins/dbak',
		'dbak',
		1,
		2
	);

COMMIT;
