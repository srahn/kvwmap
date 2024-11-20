BEGIN;
	ALTER TABLE `classes` MODIFY `Name` varchar(255) NOT NULL;
	ALTER TABLE `classes` MODIFY `Name_low-german` varchar(255) NULL;
	ALTER TABLE `classes` MODIFY `Name_english` varchar(255) NULL;
	ALTER TABLE `classes` MODIFY `Name_vietnamese` varchar(255) NULL;
	ALTER TABLE `classes` MODIFY `Name_polish` varchar(255) NULL;
COMMIT;
