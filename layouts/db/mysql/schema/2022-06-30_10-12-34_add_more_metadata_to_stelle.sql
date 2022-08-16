BEGIN;

	ALTER TABLE `stelle` ADD `ows_contactvoicephone` VARCHAR(100) NULL AFTER `ows_contactposition`;
	ALTER TABLE `stelle` ADD `ows_contactfacsimile` VARCHAR(100) NULL AFTER `ows_contactvoicephone`;
	ALTER TABLE `stelle` ADD `ows_contactaddress` VARCHAR(100) NULL AFTER `ows_contactfacsimile`;
	ALTER TABLE `stelle` ADD `ows_contactpostalcode` VARCHAR(100) NULL AFTER `ows_contactaddress`;
	ALTER TABLE `stelle` ADD `ows_contactcity` VARCHAR(100) NULL AFTER `ows_contactpostalcode`;
	ALTER TABLE `stelle` ADD `ows_contactadministrativearea` VARCHAR(100) NULL AFTER `ows_contactcity`;

	ALTER TABLE `stelle` ADD `ows_contentvoicephone` VARCHAR(100) NULL AFTER `ows_contentposition`;
	ALTER TABLE `stelle` ADD `ows_contentfacsimile` VARCHAR(100) NULL AFTER `ows_contentvoicephone`;
	ALTER TABLE `stelle` ADD `ows_contentaddress` VARCHAR(100) NULL AFTER `ows_contentfacsimile`;
	ALTER TABLE `stelle` ADD `ows_contentpostalcode` VARCHAR(100) NULL AFTER `ows_contentaddress`;
	ALTER TABLE `stelle` ADD `ows_contentcity` VARCHAR(100) NULL AFTER `ows_contentpostalcode`;
	ALTER TABLE `stelle` ADD `ows_contentadministrativearea` VARCHAR(100) NULL AFTER `ows_contentcity`;

	ALTER TABLE `stelle` ADD `ows_distributionvoicephone` VARCHAR(100) NULL AFTER `ows_distributionposition`;
	ALTER TABLE `stelle` ADD `ows_distributionfacsimile` VARCHAR(100) NULL AFTER `ows_distributionvoicephone`;
	ALTER TABLE `stelle` ADD `ows_distributionaddress` VARCHAR(100) NULL AFTER `ows_distributionfacsimile`;
	ALTER TABLE `stelle` ADD `ows_distributionpostalcode` VARCHAR(100) NULL AFTER `ows_distributionaddress`;
	ALTER TABLE `stelle` ADD `ows_distributioncity` VARCHAR(100) NULL AFTER `ows_distributionpostalcode`;
	ALTER TABLE `stelle` ADD `ows_distributionadministrativearea` VARCHAR(100) NULL AFTER `ows_distributioncity`;

COMMIT;
