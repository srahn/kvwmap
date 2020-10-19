BEGIN;

  -- Add a column kvp to layer_attributes table to indicate, that the attribut def is for a key value attribut 
  ALTER TABLE `layer_attributes` ADD `kvp` BOOLEAN NOT NULL DEFAULT FALSE AFTER `visible`;

COMMIT;