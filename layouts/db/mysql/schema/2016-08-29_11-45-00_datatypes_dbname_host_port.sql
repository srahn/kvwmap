BEGIN;

ALTER TABLE  `datatypes` ADD  `dbname` VARCHAR( 50 ) NOT NULL , ADD  `host` VARCHAR( 50 ) NULL , ADD  `port` INT NULL;

COMMIT;
