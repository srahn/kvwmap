BEGIN;

	ALTER TABLE `stelle` ADD `ows_contentorganization` VARCHAR(150) NULL AFTER `ows_contactposition`;
	ALTER TABLE `stelle` ADD `ows_contentemailaddress` VARCHAR(100) NULL AFTER `ows_contentorganization`;
	ALTER TABLE `stelle` ADD `ows_contentperson` VARCHAR(100) NULL AFTER `ows_contentemailaddress`;
	ALTER TABLE `stelle` ADD `ows_contentposition` VARCHAR(100) NULL AFTER `ows_contentperson`;
	ALTER TABLE `stelle` ADD `ows_geographicdescription` VARCHAR(100) NULL AFTER `ows_contentposition`;
	ALTER TABLE `stelle` ADD `ows_distributionorganization` VARCHAR(150) NULL AFTER `ows_geographicdescription`;
	ALTER TABLE `stelle` ADD `ows_distributionemailaddress` VARCHAR(100) NULL AFTER `ows_distributionorganization`;
	ALTER TABLE `stelle` ADD `ows_distributionperson` VARCHAR(100) NULL AFTER `ows_contentemailaddress`;
	ALTER TABLE `stelle` ADD `ows_distributionposition` VARCHAR(100) NULL AFTER `ows_distributionperson`;
	
COMMIT;
