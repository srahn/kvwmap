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
		'NACHWEISE_EMAIL',
		'',
		'',
		'Hier kann die Email-Adresse angegeben werden, an die die Emails mit den Bearbeitungshinweisen gesendet werden.',
		'string',
		'Plugins/nachweisverwaltung',
		'nachweisverwaltung',
		0,
		2
	);

COMMIT;
