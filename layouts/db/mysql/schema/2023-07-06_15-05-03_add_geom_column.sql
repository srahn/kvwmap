BEGIN;

	ALTER TABLE `layer` ADD `geom_column` VARCHAR(68) NULL AFTER `schema`;

	UPDATE
		`layer` l
		LEFT JOIN `layer_attributes` a ON l.Layer_ID = a.layer_id
	SET
		l.`geom_column` = a.`real_name`
	WHERE
		a.type = 'geometry';

COMMIT;
