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
				'BG_IMAGE',
				'GRAPHICSPATH',
				'bg.gif',
				'Hintergrundbild für die Oberfläche',
				'string',
				'Layout',
				0,
				3
			);

COMMIT;
