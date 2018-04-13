BEGIN;

ALTER TABLE `u_menues` ADD `onclick` text COMMENT 'JavaScript welches beim Klick auf den Menüpunkt ausgeführt werden soll.' AFTER `links`;

COMMIT;
