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
		'IMPORT_POINT_STYLE_ID',
		'',
		(SELECT CASE WHEN `vorhanden`.`value` IS NULL THEN `default`.`value` ELSE `default`.`value` END AS `value` FROM (SELECT '' AS `value`, 'ZOOM2POINT_STYLE_ID' AS `name`) AS `default` LEFT JOIN `config` AS `vorhanden` ON `default`.`name` = `vorhanden`.`name`),
		'Hier kann ein eigener Style für den Datenimport von Punkt-Objekten eingetragen werden.',
		'integer',
		'Layout',
		0,
		2
	);

COMMIT;
