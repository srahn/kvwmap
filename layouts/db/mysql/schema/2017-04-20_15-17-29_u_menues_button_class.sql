BEGIN;

ALTER TABLE `u_menues` ADD `button_class` VARCHAR(30) NULL DEFAULT NULL AFTER `title`;

UPDATE `u_menues` SET button_class = 'optionen' WHERE links = 'index.php?go=Stelle_waehlen';

UPDATE `u_menues` SET button_class = 'drucken' WHERE links = 'index.php?go=Druckausschnittswahl';

UPDATE `u_menues` SET button_class = 'schnelldruck' WHERE links = 'index.php?go=Schnelle_Druckausgabe';

UPDATE `u_menues` SET button_class = 'karte' WHERE links IN ('index.php?go=default', 'index.php?');

UPDATE `u_menues` SET button_class = 'notiz' WHERE links = 'index.php?go=Notizenformular';

COMMIT;
