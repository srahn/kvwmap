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
		'ZUSATZ_UEBERGABEPROTOKOLL',
		'',
		'',
		'Hier kann ein zusätzlicher Text definiert werden, der im Übergabeprotokoll unterhalb des Titels erscheint.',
		'string',
		'Plugins/nachweisverwaltung',
		'nachweisverwaltung',
		0,
		2
	);

COMMIT;
