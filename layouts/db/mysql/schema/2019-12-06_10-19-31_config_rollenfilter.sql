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
				'ROLLENFILTER',
				'',
				'false',
				'Legt fest, ob Nutzer eigene Filter für Layer erstellen können.',
				'boolean',
				'Administration',
				0,
				2
			);

COMMIT;
