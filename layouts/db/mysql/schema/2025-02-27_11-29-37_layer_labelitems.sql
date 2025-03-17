BEGIN;

CREATE TABLE `layer_labelitems` (
  `layer_id` INT(11) NOT NULL , 
  `name` VARCHAR(100) NOT NULL , 
  `alias` VARCHAR(100) NULL ,
  `order` int(11) NOT NULL) ENGINE = InnoDB;

COMMIT;
