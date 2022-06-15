BEGIN;

  ALTER TABLE `styles` CHANGE `color` `color` VARCHAR(255);

COMMIT;
