BEGIN;

  ALTER TABLE u_groups ADD `layerparam` varchar(100) NULL;
  ALTER TABLE layer_parameter ADD `multiple` TINYINT(1) NOT NULL DEFAULT '0';

COMMIT;