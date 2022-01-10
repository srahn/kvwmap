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
		'Legt den Debug-Level für MapServer fest. Werte von 0 bis 5 sind möglich.',
		'integer',
		'Logging',
		0,
		2
	);

COMMIT;
