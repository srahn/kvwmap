BEGIN;
  ALTER TABLE `stelle` ADD COLUMN `show_shared_layers` BOOLEAN DEFAULT false;
  ALTER TABLE `layer` ADD COLUMN `shared_from` integer;
  ALTER TABLE `user` ADD COLUMN `share_rollenlayer_allowed` BOOLEAN DEFAULT false;
  ALTER TABLE `u_groups` ADD COLUMN `selectable_for_shared_layers` BOOLEAN NOT NULL DEFAULT false;
COMMIT;
