BEGIN;

CREATE TABLE `rolle_nachweise_dokumentauswahl` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`stelle_id` INT( 11 ) NOT NULL ,
`user_id` INT( 11 ) NOT NULL ,
`name` VARCHAR( 100 ) NOT NULL ,
`ffr` BOOLEAN NOT NULL DEFAULT  '0',
`kvz` BOOLEAN NOT NULL DEFAULT  '0',
`gn` BOOLEAN NOT NULL DEFAULT  '0',
`andere` TEXT NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;

COMMIT;
