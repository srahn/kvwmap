BEGIN;

ALTER TABLE  `rolle` ADD  `instant_reload` BOOLEAN NOT NULL DEFAULT  '0',
ADD  `menu_auto_close` BOOLEAN NOT NULL DEFAULT  '0';

COMMIT;
