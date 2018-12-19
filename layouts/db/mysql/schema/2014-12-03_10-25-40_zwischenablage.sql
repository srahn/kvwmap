BEGIN;

CREATE TABLE  `zwischenablage` (
`user_id` INT( 11 ) NOT NULL ,
`stelle_id` INT( 11 ) NOT NULL ,
`layer_id` INT( 11 ) NOT NULL ,
`oid` BIGINT( 20 ) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `stelle_id`, `layer_id`,`oid`)
) ENGINE = MYISAM ;

COMMIT;
