BEGIN;

CREATE TABLE `config` ( 
	`id` INT(11) NOT NULL AUTO_INCREMENT , 
	`name` VARCHAR(50) NOT NULL , 
	`prefix` VARCHAR(100) NOT NULL , 
	`value` TEXT NOT NULL , 
	`description` TEXT NULL , 
	`type` VARCHAR(20) NOT NULL , 
	`group` VARCHAR(50) NOT NULL , 
	`plugin` VARCHAR(50) NULL , 
	`saved` BOOLEAN NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

COMMIT;