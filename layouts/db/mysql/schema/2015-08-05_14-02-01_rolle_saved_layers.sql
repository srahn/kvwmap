BEGIN;

CREATE TABLE `rolle_saved_layers` (
`user_id` INT NOT NULL ,
`stelle_id` INT NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL ,
`layers` TEXT NOT NULL ,
PRIMARY KEY (  `user_id` ,  `stelle_id` ,  `name` )
) ENGINE = MYISAM ;

COMMIT;
