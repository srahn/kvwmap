BEGIN;

	ALTER TABLE `stelle` MODIFY `wappen` varchar(255) NULL;
	UPDATE `stelle` SET `wappen` = NULL WHERE `wappen` LIKE 'stz.gif';

COMMIT;
