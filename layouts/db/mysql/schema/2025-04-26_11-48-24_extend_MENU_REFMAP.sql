BEGIN;

	UPDATE `config`
		SET `description` = 'Position der Referenzkarte in der Menüleiste. (oben/unten/ohne)'
	WHERE
		name LIKE 'MENU_REFMAP';

COMMIT;