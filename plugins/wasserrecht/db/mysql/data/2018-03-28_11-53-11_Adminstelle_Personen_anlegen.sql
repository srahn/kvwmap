BEGIN;

UPDATE `used_layer` SET `privileg` = '2' WHERE `used_layer`.`Stelle_ID` = $WASSERRECHT_STELLE_ADMINISTRATION AND `used_layer`.`Layer_ID` = $PERSONEN_LAYER_ID;

COMMIT;
