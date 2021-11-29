BEGIN;
	ALTER TABLE `layer` CHANGE COLUMN `kurzbeschreibung` `kurzbeschreibung` TEXT COMMENT 'Freitext zur Beschreibung des Layerinhaltes';
	ALTER TABLE `layer` ADD COLUMN `datasource` TEXT COMMENT 'Freifeld zur Beschreibung der Datenquelle' AFTER `kurzbeschreibung`;
	ALTER TABLE `layer` CHANGE COLUMN `datenherr` `dataowner_name` TEXT COMMENT 'Name des Ansprechpartners';
	ALTER TABLE `layer` ADD COLUMN `dataowner_email` varchar(100) COMMENT 'E-Mail Adresse der Ansprechperson.' AFTER `dataowner_name`;
	ALTER TABLE `layer` ADD COLUMN `dataowner_tel` varchar(50) COMMENT 'Telefonnummer der Ansprechperson.' AFTER `dataowner_email`;
	ALTER TABLE `layer` ADD COLUMN `uptodateness` varchar(100) COMMENT 'Aktualität der Daten des Layers.' AFTER `dataowner_tel`;
	ALTER TABLE `layer` ADD COLUMN `updatecycle` varchar(100) COMMENT 'Aktualisierungszyklus der Daten des Layers.' AFTER `uptodateness`;
COMMIT;
