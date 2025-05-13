BEGIN;
	ALTER TABLE `stelle` ADD COLUMN `ows_contenturl` text COMMENT 'Home-Page des Verantwortlichen für Metadaten.' AFTER `ows_contentorganization`;
	ALTER TABLE `stelle` ADD COLUMN `ows_contacturl` text COMMENT 'Home-Page des Verantwortlichen für die Geodaten.' AFTER `ows_contactorganization`;
	ALTER TABLE `stelle` ADD COLUMN `ows_distributionurl` text COMMENT 'Home-Page des Verantwortlichen für den Vertrieb.' AFTER `ows_distributionorganization`;
COMMIT;