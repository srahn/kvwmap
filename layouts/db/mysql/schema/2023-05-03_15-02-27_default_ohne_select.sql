BEGIN;

UPDATE `layer_attributes` SET `default` = REPLACE(`default`, 'SELECT ', '');

COMMIT;
