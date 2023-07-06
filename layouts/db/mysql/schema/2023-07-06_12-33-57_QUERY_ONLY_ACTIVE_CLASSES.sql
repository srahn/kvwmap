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
		'QUERY_ONLY_ACTIVE_CLASSES',
		'',
		'true',
		'Wenn aktiviert, dann werden bei der Kartenabfrage nur aktive Klassen berücksichtigt.',
		'boolean',
		'Administration',
		0,
		2
	);

COMMIT;
