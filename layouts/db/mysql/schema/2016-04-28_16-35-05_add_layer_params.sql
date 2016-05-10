BEGIN;

ALTER TABLE `layer`
  ADD `default_params` text NOT NULL AFTER `Data`;

ALTER TABLE `u_rolle2used_layer`
  ADD `rolle_params` varchar(255) NOT NULL;

COMMIT;