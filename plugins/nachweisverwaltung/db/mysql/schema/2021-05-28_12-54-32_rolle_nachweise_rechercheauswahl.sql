BEGIN;

CREATE TABLE `rolle_nachweise_rechercheauswahl` ( 
`stelle_id` INT(11) NOT NULL , 
`user_id` INT(11) NOT NULL , 
`nachweis_id` INT(11) NOT NULL 
) ENGINE = InnoDB;

COMMIT;