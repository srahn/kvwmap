BEGIN;

CREATE TABLE `rolle_saved_layers` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` INT NOT NULL ,
`stelle_id` INT NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL ,
`layers` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE = MYISAM ;

COMMIT;
