BEGIN;

ALTER TABLE `u_consumeNachweise` ADD `suchhauptart` VARCHAR(50) NULL AFTER `time_id`;

ALTER TABLE `u_consumeNachweise` ADD `suchunterart` VARCHAR(255) NULL AFTER `suchhauptart`;

UPDATE `u_consumeNachweise` SET suchhauptart = CONCAT_WS(',', IF(suchffr=1, '1', NULL), IF(suchkvz=1, '2', NULL), IF(suchgn=1, '3', NULL), IF(suchan=1, '4', NULL));

UPDATE `u_consumeNachweise` SET suchunterart = such_andere_art;

ALTER TABLE `u_consumeNachweise` DROP `suchffr`;

ALTER TABLE `u_consumeNachweise` DROP `suchkvz`;

ALTER TABLE `u_consumeNachweise` DROP `suchgn`;

ALTER TABLE `u_consumeNachweise` DROP `suchan`;

ALTER TABLE `u_consumeNachweise` DROP `such_andere_art`;

COMMIT;
