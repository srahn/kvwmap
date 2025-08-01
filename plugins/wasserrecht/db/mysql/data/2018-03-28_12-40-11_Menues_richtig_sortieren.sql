BEGIN;

UPDATE `u_menues` SET `order` = '0' WHERE `u_menues`.`id` = $WASSERRECHT_MENUE_NEUE_GEWAESSERBENUTZUNG;
UPDATE `u_menues` SET `order` = '1' WHERE `u_menues`.`id` = $WASSERRECHT_MENUE_NEUE_PERSON;
UPDATE `u_menues` SET `order` = '2' WHERE `u_menues`.`id` = $WASSERRECHT_MENUE_NEUE_WRZ;
UPDATE `u_menues` SET `order` = '3' WHERE `u_menues`.`id` = $WASSERRECHT_MENUE_NEUE_ANLAGE;
UPDATE `u_menues` SET `order` = '4' WHERE `u_menues`.`id` = $WASSERRECHT_MENUE_WASSERENTNAHMEBENUTZER;
UPDATE `u_menues` SET `order` = '5' WHERE `u_menues`.`id` = $WASSERRECHT_MENUE_WASSERENTNAHMEENTGELT;
UPDATE `u_menues` SET `order` = '6' WHERE `u_menues`.`id` = $WASSERRECHT_MENUE_ZENTRALE_STELLE;
UPDATE `u_menues` SET `order` = '7' WHERE `u_menues`.`id` = $WASSERRECHT_MENUE_ERSTATTUNG_DES_VERWALTUNGSAUFWANDS;

COMMIT;