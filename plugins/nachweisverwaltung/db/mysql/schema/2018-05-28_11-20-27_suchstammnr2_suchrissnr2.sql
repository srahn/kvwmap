BEGIN;

ALTER TABLE `rolle_nachweise` ADD `suchstammnr2` VARCHAR(15) NULL AFTER `suchstammnr`;

ALTER TABLE `rolle_nachweise` ADD `suchrissnr2` VARCHAR(20) NULL AFTER `suchrissnr`;

ALTER TABLE `u_consumeNachweise` ADD `suchstammnr2` VARCHAR(15) NULL AFTER `suchstammnr`;

ALTER TABLE `u_consumeNachweise` ADD `suchrissnr2` VARCHAR(20) NULL AFTER `suchrissnr`;

COMMIT;
