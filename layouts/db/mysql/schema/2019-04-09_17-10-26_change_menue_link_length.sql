BEGIN;

ALTER TABLE `u_menues` CHANGE `links` `links` VARCHAR(2000);

COMMIT;
