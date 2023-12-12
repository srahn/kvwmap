BEGIN;

	-- Wenn Menü gelöscht wird, werden die Zuordnungen zu den Stellen in u_menue2stelle gelöscht.
	ALTER TABLE `u_menue2stelle` ADD CONSTRAINT `fk_menue2stelle_meune` FOREIGN KEY (`menue_id`) REFERENCES `u_menues`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
	-- Wenn Stelle gelöscht wird, werden die Zuordnungen zu den Menüs in u_menue2stelle gelöscht.
	ALTER TABLE `u_menue2stelle` ADD CONSTRAINT `fk_menue2stelle_stelle` FOREIGN KEY (`stelle_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
	-- Wenn Zuordnung in u_menue2stelle gelöscht wird, werden die Zuordnungen zur Rolle in u_menue2rolle gelöscht. 
	DELETE `u_menue2rolle`
	FROM
		`u_menue2rolle` LEFT JOIN
		`u_menue2stelle` ON u_menue2rolle.stelle_id = u_menue2stelle.stelle_id AND u_menue2rolle.menue_id = u_menue2stelle.menue_id
	WHERE
		u_menue2stelle.stelle_id IS NULL AND
		u_menue2stelle.menue_id IS NULL;
	ALTER TABLE `u_menue2rolle` ADD CONSTRAINT `fk_menue2rolle_menue2stelle` FOREIGN KEY (`menue_id`,`stelle_id`) REFERENCES `u_menue2stelle`(`menue_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;
	-- Wenn Rolle gelöscht wird, werden die Zuordnungen der Rolle zu Menues in u_menue2rolle gelöscht.
	ALTER TABLE `u_menue2rolle` ADD CONSTRAINT `fk_menue2rolle_rolle` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
